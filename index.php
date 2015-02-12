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
set_time_limit(15);
if(version_compare(PHP_VERSION, "5.4", "<")) die("You need PHP 5.4 or higher");
header("X-UA-Compatible: IE=edge");

require(__DIR__."/lib/functions.php");
require(__DIR__."/lib/Translations.class.php");
require(__DIR__."/lib/OMX.class.php");

try{

    $optionsFile = __DIR__."/options.json";
    $options = file_exists($optionsFile) ? json_decode(file_get_contents($optionsFile), true) : array();
    $folders = isset($options["folders"]) ? $options["folders"] : array();
    Translations::$language = isset($options["language"]) ? $options["language"] : "en";

    # json requests
    if(isset($_GET["json"])){
        $data = NULL;
        switch($_GET["action"]){
            case "get-status":
                $data = array("status" => "stopped");
                $output = $return = "";
                exec('sh '.escapeshellcmd(__DIR__."/omx-status.sh"), $output, $return);
                if($return){
                    $data["status"] = "playing";
                    if(file_exists(OMX::$fifoStatusFile)){
                        $json = json_decode(file_get_contents(OMX::$fifoStatusFile), true);
                        $data["path"] = $json["path"];
                    }
                }
                break;
        }
        echo json_encode($data);
        exit;
    }

    # ajax requests
    if(isset($_POST["action"])){
        switch($_POST["action"]){
            # getting the filelist
            case "get-filelist":
                $files = array();
                foreach($folders as $folder){
                    $files = array_merge($files, isStreamUrl($folder) ? array($folder) : getVideoFiles($folder));
                }
                foreach($files as $file){
                    echo '<div class="file" data-path="'.$file.'">'.$file.'</div>';
                }
                exit;
                break;
            # save options
            case "save-options":
                $folders = explode("\n", trim($_POST["folders"]));
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
                $options["folders"] = $folders;
                $options = array_merge($options, $_POST["option"]);
                file_put_contents($optionsFile, json_encode($options));
                echo t("saved");
                if($error) echo t("error.folders");
                echo "<br/>".t("reload.page");
                break;
            # key/mouse click commands
            case "shortcut":
                $startCmd = escapeshellarg($_POST["path"])." ".(isset($options["speedfix"]) && $options["speedfix"] ? "1" : "0");
                switch($_POST["shortcut"]){
                    case "start":
                        file_put_contents(OMX::$fifoStatusFile, json_encode(array("path" => $_POST["path"])));
                        OMX::sendCommand($startCmd, "start");
                        break;
                    case "p":
                        if(!file_exists(OMX::$fifoFile)){
                            OMX::sendCommand($startCmd, "start");
                        }else{
                            OMX::sendCommand("p", "pipe");
                        }
                        break;
                    default:
                        $key = OMX::$hotkeys[$_POST["shortcut"]];
                        OMX::sendCommand(isset($key["shortcut"]) ? $key["shortcut"] : $_POST["shortcut"], "pipe");
                }
                break;
        }
        die();
    }

    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=500, initial-scale=1.1">
            <link rel="stylesheet" type="text/css" href="css/site.css">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript">
                var omxHotkeys = <?=json_encode(OMX::$hotkeys)?>;
                var options = <?=json_encode($options)?>;
                var language = <?=json_encode(Translations::$language)?>;
                var translations = <?=json_encode(Translations::$translations)?>;
            </script>
            <script type="text/javascript" src="js/scripts.js"></script>
            <title>OMX Web GUI - By BrainFooLong</title>
        </head>
        <body>
            <div class="page">
                <div class="ajax-error"><div></div></div>
                <div class="ajax-success"><div></div></div>
                <div class="header">
                    <a href="https://github.com/brainfoolong/omxwebgui" target="_blank"><img src="images/logo.png" alt="" class="logo"/></a>
                    <div class="box">
                        <form name="opt" method="post" action="">
                            <input type="hidden" name="action" value="save-options"/>
                            <p><?=nl2br(t("folders.desc"))?></p>
                            <textarea cols="45" rows="3" name="folders"><?=htmlentities(implode("\n", $folders))?></textarea><br/>
                            <?=t("ui.language")?>: <select name="option[language]">
                            <?php foreach(Translations::$languages as $lang => $label){
                                echo '<option value="'.$lang.'"';
                                if(isset($options["language"]) && $options["language"] == $lang) echo ' selected="selected"';
                                echo '>'.$label.'</option>';
                            }?>
                            </select><br/>
                            <?php displayYesNoOption("speedfix", $options) ?><br/>
                            <?php displayYesNoOption("autoplay-next", $options) ?><br/>
                            <br/>
                            <input type="button" class="action button" data-action="save-options" value="<?=t("save")?>"/>
                        </form>
                    </div>
                </div>
                <div class="files">
                    <div class="status-line"><b><?=t("status")?>:</b> <span id="status"></span></div>
                    <div class="results">
                        <input type="text" class="search" value="<?=t("search.input")?>"/>
                        <div id="filelist"><?=t("loading")?>...</div>
                    </div>
                </div>
                <div class="omx-buttons">
                    <?php foreach(OMX::$hotkeys as $key => $value){
                        $keyValue = $key;
                        switch($key){
                            case "left": $keyValue = "&#x2190"; break;
                            case "right": $keyValue = "&#x2192"; break;
                            case "up": $keyValue = "&#x2191"; break;
                            case "down": $keyValue = "&#x2193"; break;
                        }
                        echo '<div class="button" data-action="shortcut" data-shortcut="'.$key.'"><span class="shortcut">'.$keyValue.'</span><span class="label">'.nl2br(t("shortcut-$key")).'</span></div>';
                    }?>
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