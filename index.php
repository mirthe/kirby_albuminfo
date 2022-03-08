<?php 

// TODO opschonen en aanbieden?
// https://getkirby.com/docs/guide/plugins/best-practices

Kirby::plugin('mirthe/albuminfo', [
    'options' => [
        'cache' => true
    ],
    'tags' => [
        'albuminfo' => [
            'attr' =>[
                'artist',
                'title'
            ],
            'html' => function($tag) {
                
                $albumartist = urlencode($tag->artist);
                $albumtitle = urlencode($tag->title);

                $apikey = option('lastfm.apiKey');
                $url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=$apikey&format=json&artist=$albumartist&album=$albumtitle";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close ($ch);

                $albuminfojson = json_decode($output,true);
                // print_r($albuminfojson); exit();
                
                $mijnoutput = '<div class="well">';
                $mijnoutput .= '<div class="well-img"><img src="'.$albuminfojson['album']['image']['3']['#text'].'" alt=""></div>';
                $mijnoutput .= '<div class="well-body">';
                // $mijnoutput .= '<a href="https://open.spotify.com/search/'.$albuminfojson['album']['artist'].' '.$albuminfojson['album']['name'].'" class="floatright" title="Beluisten op Spotify">Spotify</a>';
                $mijnoutput .= '<p>'.$albuminfojson['album']['artist']."<br>";
                $mijnoutput .= '<a href="'.$albuminfojson['album']['url'].'" title="Bekijken op Last.fm">'.$albuminfojson['album']['name']."</a></p>";

                // TODO use collapse
                // if( isset( $albuminfojson['album']['wiki'] ) ){
                //     $mijnoutput .= "<p>".$albuminfojson['album']['wiki']['summary']."</p>";
                // }

                if (array_key_exists('tracks',$albuminfojson['album'])){
                    foreach ($albuminfojson['album']['tracks'] as $tracks) {
                        if( count($tracks) > 0){
                            $mijnoutput .= "<ul class=\"songs\">";
                            for($i = 0; $i < count($tracks); $i++) {
                                $mijnoutput .= '<li>'. $tracks[$i]['name'] . "</li>";
                            }
                            $mijnoutput .= "</ul>";
                        }
                        else {
                            $mijnoutput .= "<p><em>De tracklist is niet bekend bij de Last.fm API.</em></p>";
                        }
                    }
                }

                $i = 0;
                $mijnoutput .= "<ul class=\"genres\">";
                foreach ($albuminfojson['album']['tags']['tag'] as $genre) {
                    $mijnoutput .= '<li>'. $genre['name'] . "</li>";
                    if (++$i == 5) break;
                }
                $mijnoutput .= "</ul>";

                $mijnoutput .= '</div></div>';
               
                return $mijnoutput;
                }
            ]
        ]
    ]);
?>