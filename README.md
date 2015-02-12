# Simple Web GUI for Omxplayer on a Raspberry Pi made in PHP

Currently in alpha but should work.

## Features
* Play/Stop videos through the browser - Also mobile browser compatible
* All hotkeys from omxplayer mapped to the webpage
* Multilanguage
* Permanent playlist - Just add folders and streams
* Search for filenames with wildcards
* Autoplay next video
* Mark of already viewed videos

## Requirements
`sudo apt-get install php5-cli`
* or when you already have a webserver installed with PHP 5.4+ than you need nothing extra :)

## Run
To create a simple php webserver listening on port 4321, you can change the port to whatever you want.

`php -S 0.0.0.0:4321 -t YOURPATHTOOMXWEBGUI > /dev/null 2>&1 &`

Open the page with http://IPTOYOURPI:4321

* or when you already have a webserver just open the page in the browser.

## Autostart
Add the following line to crontab with `sudo crontab -e` to start the simple php webserver on reboot

`@reboot php -S 0.0.0.0:4321 -t YOURPATHTOOMXWEBGUI > /dev/null 2>&1 &`

## Screenshot
![alt text](http://i.imgur.com/ZIrqPFX.jpg "Screenshot")
