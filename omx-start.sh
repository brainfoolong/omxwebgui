## script to start omxplayer

# check if player is already running
ps cax | grep "omxplayer" > /dev/null
if [ $? -eq 0 ]; then
    sudo killall omxplayer && sudo killall omxplayer.bin
fi
# delete mkfifo file if exist
if [ -e $3 ]
then
    rm $3
fi
mkfifo $3
omxplayer -o hdmi -b "$1" < $3 &
# Playing Audio Files (MP3) doesn't seem to work with this
#echo -n "." > $3 &

# fix for double play speed at start
if [ "$2" = "1" ]; then
    echo -n "1" > $3 &
fi
