<?php
/**
* Simple Web GUI for Omxplayer on a Raspberry Pi
*
* @link https://github.com/brainfoolong/omxwebgui
* @author BrainFooLong
* @license GPL v3
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
if(version_compare(PHP_VERSION, "5.4", "<")) die("You need PHP 5.4 or higher");
try{

    $omxHotkeys = array(
        "z" => array("label" => "Show Info", "key" => "90"),
        "2" => array("label" => "-<br/>Speed", "key" => "50,98"),
        "1" => array("label" => "+<br/>Speed", "key" => "49,57"),
        "j" => array("label" => "Prev Audio Stream", "key" => "74"),
        "k" => array("label" => "Next Audio Stream", "key" => "75"),
        "i" => array("label" => "Previous Chapter", "key" => "73"),
        "o" => array("label" => "Next Chapter", "key" => "79"),
        "n" => array("label" => "Prev Subtitle Stream", "key" => "78"),
        "m" => array("label" => "Next Subtitle Stream", "key" => "77"),
        "s" => array("label" => "Toggle subtitles", "key" => "83"),
        "d" => array("label" => "-<br/>Subtitle delay", "key" => "68"),
        "f" => array("label" => "+<br/>Subtitle delay", "key" => "70"),
        "q" => array("label" => "Stop", "key" => "81"),
        "p" => array("label" => "Pause<br/>Resume", "key" => "80"),
        "-" => array("label" => "-<br/>Volume", "key" => "189,109"),
        "+" => array("label" => "+<br/>Volume", "key" => "187,107"),
        "left" => array("label" => "Slow Backward", "key" => "37"),
        "right" => array("label" => "Slow Forward", "key" => "39"),
        "down" => array("label" => "Fast Backward", "key" => "40"),
        "up" => array("label" => "Fast Forward", "key" => "38")
    );

    function isStreamUrl($path){
        return $path && preg_match("~[a-z]+\:\/\/~i", $path) ? true : false;
    }

    function getVideoFiles($dir){
        $arr = array();
        if(is_dir($dir) && is_readable($dir)){
            $files = scandir($dir, SORT_ASC);
            foreach($files as $file){
                if($file == "." || $file == "..") continue;
                if(!preg_match("~\.(mp4|mkv|mpg|avi|mpeg)~i", $file)) continue;
                $path = $dir."/".$file;
                if(is_dir($path)){
                    $arr = array_merge($arr, getVideoFiles($path));
                }else{
                    $arr[] = $path;
                }
            }
        }
        return $arr;
    }

    $folderFile = __DIR__."/folders.txt";

    if(isset($_POST["action"])){
        switch($_POST["action"]){
            case "save-paths":
                $folders = explode("\n", trim($_POST["text"]));
                $error = false;
                foreach($folders as $key => $folder){
                    $folder = trim(str_replace("\\", "/", $folder));
                    $folders[$key] = $folder;
                    if(isStreamUrl($folder)) continue;
                    if(!is_dir($folder) || !is_readable($folder) || !$folder || $folder == "/"){
                        $error = true;
                        unset($folders[$key]);
                    }
                }
                file_put_contents($folderFile, implode("\n", $folders));
                echo "Saved";
                if($error) echo ", but not all folders where readable - Not existing or unreadable folders have been removed";
                echo "<br/>Reload the page to see the changes";
            break;
            case "shortcut":

            break;
        }
        die();
    }


    $folders = file_exists($folderFile) ? file($folderFile, FILE_IGNORE_NEW_LINES) : array();
    $files = array();
    foreach($folders as $folder){
        $files = array_merge($files, isStreamUrl($folder) ? array($folder) : getVideoFiles($folder));
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/site.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript">
    var omxHotkeys = <?=json_encode($omxHotkeys)?>;
    </script>
    <title>OMX Web GUI - By BrainFooLong</title>
    </head>
    <body>
    <div class="page">
        <div class="ajax-error"><div></div></div>
        <div class="ajax-success"><div></div></div>
        <div class="header">
            <img src="images/logo.png" alt="" class="logo"/>
            <div class="box">
                <p>Add folders where you have the video files in - One folder per line - Search is recursive<br/>You can also add streams like rtmp://, rtstp://, etc...</p>
                <textarea cols="45" rows="9" class="paths"><?=htmlentities(implode("\n", $folders))?></textarea><br/>
                <input type="button" class="action button" data-action="save-paths" value="Save"/>
            </div>
        </div>
        <div class="files">
            <div class="current">Currently Playing: None</div>
            <div class="results">
                <input type="text" class="search" value="Search for filename, wildcards * allowed"/>
                <?
                foreach($files as $file){
                    ?>
                    <div class="file" data-path="<?=$file?>"><?=$file?></div>
                    <?
                }
                ?>
            </div>
        </div>
        <div class="omx-buttons">
            <?foreach($omxHotkeys as $key => $value){
                $keyValue = $key;
                switch($key){
                    case "left": $keyValue = "&#x2190"; break;
                    case "right": $keyValue = "&#x2192"; break;
                    case "up": $keyValue = "&#x2191"; break;
                    case "down": $keyValue = "&#x2193"; break;
                }
                ?>
                <div class="button" data-action="shortcut" data-shortcut="<?=$key?>"><span class="shortcut"><?=$keyValue?></span><span class="label"><?=$value["label"]?></span></div>
            <?}?>
            <div class="clear"></div>
        </div>
        <div class="footer">
            Powered by BrainFooLong - Contribute on GitHub <a href="https://github.com/brainfoolong/omxwebgui" target="_blank">omxwebgui</a>
        </div>
    </div>
    </body>
    </html>
    <?php
}catch(Exception $e){
    header("HTTP/1.0 500 Internal Server Error");
    echo $e->getMessage()."\n\n";
    echo $e->getTraceAsString();
}