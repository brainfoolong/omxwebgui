# Simple Web GUI
Simple Web GUI for Omxplayer on a Raspberry Pi.
Currently not working, just some preparations done.

## Requirements
`sudo apt-get install php5-cli`

## Run
To create a simple php webserver listening on port 4321, you can change the port to whatever you want.
`php -S 0.0.0.0:4321 -t YOURPATHTOOMXWEBGUI > /dev/null 2>&1 &`

## Autostart
Add the following line to crontab with `sudo crontab -e` to start on reboot
`@reboot php -S 0.0.0.0:4321 -t YOURPATHTOOMXWEBGUI > /dev/null 2>&1 &`