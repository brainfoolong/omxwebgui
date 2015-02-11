# check if player is already running
ps cax | grep "omxplayer" > /dev/null
if [ $? -eq 0 ]; then
    sudo killall omxplayer && sudo killall omxplayer.bin
fi
# delete mkfifo file if exist
if [ -e $2 ]
then
    rm $2
fi
mkfifo $2
omxplayer $1 < $2 &
echo -n "." > $2 &
echo -n "1" > $2 &