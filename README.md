# Kirby Plugin: Albuminfo

This plugin allows you to show information of an album from the Last.fm API. 
Though that might change to a different service at some later point

## Git submodule

```
git submodule add https://github.com/mirthe/kirby_albuminfo site/plugins/albuminfo
```

## Usage

Add the following to your config:

    'lastfm.apiKey' => 'XX'

## Example 

Placed for example with 

    (albuminfo: artist: Villagers of Ioannina City title: Age of Aquarius)

<img src="https://github.com/mirthe/kirby_albuminfo/blob/7a8b41631ba7a5eea949a9b5c1ac5923573981ec/example.png" alt="Example of usage">

## Example CSS

See https://css-tricks.com/how-to-make-a-media-query-less-card-component/

## Todo

- Offer as an official Kirby plugin
- Might use other service(s)
- Add sample SCSS to this readme
- Cleanup code
- Lots..
