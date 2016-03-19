<?php
/**
* Alias for Translations::translate
*
* @param string $key
* @return string
*/
function t($key){
    return Translations::translate($key);
}

/**
* Display yes/no radio buttons
*
* @param mixed $name
* @param mixed $options
*/
function displayYesNoOption($name, &$options){
    echo '<span title="'.t("option.$name.desc").'">';
    echo t("option.$name.name").': <input type="radio" name="option['.$name.']" value="1" '.(isset($options[$name]) && $options[$name] ? 'checked="checked"' : "").'/> '.t("enabled").' <input type="radio" name="option['.$name.']" value="0" '.(!isset($options[$name]) || !$options[$name] ? 'checked="checked"' : "").'/> '.t("disabled");
    echo '</span>';
}

/**
* Get all video files recursive
*
* @param mixed $dir
* @return array
*/
function getVideoFiles($dir){
    global $totalIterations;
    $arr = array();
    if(is_dir($dir) && is_readable($dir)){
        $files = scandir($dir, SCANDIR_SORT_ASCENDING);
        foreach($files as $file){
            if($file == "." || $file == "..") continue;
            $path = $dir."/".$file;
            if(is_dir($path)){
                $arr = array_merge($arr, getVideoFiles($path));
            }else{
                if(!preg_match("~\.(mp3|mp4|mkv|mpg|avi|mpeg)~i", $file)) continue;
                $arr[] = $path;
            }
        }
    }
    return $arr;
}

/**
* Is path an url
*
* @param mixed $path
* @return bool
*/
function isStreamUrl($path){
    return $path && preg_match("~[a-z]+\:\/\/~i", $path) ? true : false;
}

/**
* Get path hash
*
* @param mixed $path
* @return string
*/
function getPathHash($path){
    return substr(md5($path), 0, 12);
}
