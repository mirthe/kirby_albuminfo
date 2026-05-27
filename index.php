<?php Kirby::plugin('mirthe/albuminfo', [
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
                $albumartist = strtolower(urlencode($tag->artist));
                $albumtitle = strtolower(urlencode($tag->title));

                $cache = kirby()->cache('mirthe.albuminfo');
                $cacheKey = 'lastfm-' . $albumartist . '-' . $albumtitle;
                $albuminfojson = $cache->get($cacheKey);

                if ($albuminfojson === null) {
                    $apikey = option('lastfm.apiKey');
                    $url = "https://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=$apikey&format=json&artist=".urlencode($tag->artist)."&album=".urlencode($tag->title);

                    $ch = curl_init($url);
                    curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_USERAGENT      => kirby()->site()->title(),
                        CURLOPT_FAILONERROR    => true,
                    ]);
                    $output = curl_exec($ch);
                    curl_close($ch);

                    $albuminfojson = json_decode($output, true);

                    if (is_array($albuminfojson) && isset($albuminfojson['album'])) {
                        $cache->set($cacheKey, $albuminfojson, 604800);
                    }
                }

                if (!is_array($albuminfojson) || empty($albuminfojson['album']) || !is_array($albuminfojson['album'])) {
                    return '<div class="well"><div class="well-body">Album niet gevonden</div></div>';
                }

                $album = $albuminfojson['album'];
                $image = '';
                if (!empty($album['image']) && is_array($album['image'])) {
                    foreach ($album['image'] as $imageItem) {
                        if (!empty($imageItem['#text'])) {
                            $image = $imageItem['#text'];
                        }
                    }
                }

                $mijnoutput = '<div class="well">';
                if ($image !== '') {
                    $mijnoutput .= '<div class="well-img"><img src="'.htmlspecialchars($image, ENT_QUOTES).'" alt="'.htmlspecialchars($album['name'] ?? '', ENT_QUOTES).'" /></div>';
                }

                $mijnoutput .= '<div class="well-body">';
                $mijnoutput .= '<p>'.htmlspecialchars($album['artist'] ?? '', ENT_QUOTES)."<br>";
                $mijnoutput .= '<a href="'.htmlspecialchars($album['url'] ?? '#', ENT_QUOTES).'" title="Bekijken op Last.fm">'.htmlspecialchars($album['name'] ?? '', ENT_QUOTES)."</a></p>";

                $tracks = [];
                if (!empty($album['tracks']['track'])) {
                    $tracks = $album['tracks']['track'];
                    if (isset($tracks['name'])) {
                        $tracks = [$tracks];
                    }
                }

                if (is_array($tracks) && count($tracks) > 0) {
                    $mijnoutput .= "<ul class=\"songs\">";
                    foreach ($tracks as $track) {
                        if (!empty($track['name'])) {
                            $mijnoutput .= '<li>'.htmlspecialchars($track['name'], ENT_QUOTES)."</li>";
                        }
                    }
                    $mijnoutput .= "</ul>";
                } else {
                    $mijnoutput .= "<p><em>De tracklist is niet bekend bij de Last.fm API.</em></p>";
                }

                $tags = [];
                if (!empty($album['tags']['tag'])) {
                    $tags = $album['tags']['tag'];
                    if (isset($tags['name'])) {
                        $tags = [$tags];
                    }
                }

                if (is_array($tags) && count($tags) > 0) {
                    $mijnoutput .= "<ul class=\"genres\">";
                    $i = 0;
                    foreach ($tags as $genre) {
                        if (!empty($genre['name'])) {
                            $mijnoutput .= '<li>'.htmlspecialchars($genre['name'], ENT_QUOTES)."</li>";
                            if (++$i === 5) {
                                break;
                            }
                        }
                    }
                    $mijnoutput .= "</ul>";
                }

                $mijnoutput .= '</div></div>';
                return $mijnoutput;
            }
        ]
    ]
]);

?>
