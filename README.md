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

![Example of plugin in use](image: /example.png?raw=true)

## Todo

- Offer as an official Kirby plugin
- Might use other service(s)
- Add sample SCSS to this readme
- Cleanup code
- Lots..