<?php
/**
* Translations
*/

class Translations{

    /**
    * The language
    *
    * @var string
    */
    static $language = "en";

    /**
    * Available languages
    *
    * @var array
    */
    static $languages = array("en" => "English", "de" => "Deutsch");

    /**
    * Translations
    *
    * @var array
    */
    static $translations = array(
        "en" => array(
            "shortcut-q" => "Stop\nPlayer",
            "shortcut-p" => "Pause\nResume",
            "shortcut--" => "Volume Down",
            "shortcut-+" => "Volume Up",
            "shortcut-left" => "Slow Backward",
            "shortcut-right" => "Slow Forward",
            "shortcut-down" => "Fast Backward",
            "shortcut-up" => "Fast Forward",
            "shortcut-z" => "Show Info",
            "shortcut-1" => "Speed\nDown",
            "shortcut-2" => "Speed\nUp",
            "shortcut-j" => "Prev Audio Stream",
            "shortcut-k" => "Next Audio Stream",
            "shortcut-i" => "Previous Chapter",
            "shortcut-o" => "Next Chapter",
            "shortcut-n" => "Prev Subtitle Stream",
            "shortcut-m" => "Next Subtitle Stream",
            "shortcut-s" => "Toggle subtitles",
            "shortcut-d" => "Decrease Subtitle delay",
            "shortcut-f" => "Increase Subtitle delay",
            "yes" => "Yes",
            "no" => "No",
            "enabled" => "Enabled",
            "disabled" => "Disabled",
            "folders.desc" => "Add folders where you have the video files in - One folder per line - Search is recursive\nYou can also add streams like rtmp://, rtstp://, http://, etc...",
            "option.speedfix.name" => "Double-speed fix",
            "option.speedfix.desc" => "Enable this when your player starts with playspeed 2.000",
            "option.autoplay-next.name" => "Autoplay next video",
            "option.autoplay-next.desc" => "Only work when browser remains opened",
            "save" => "Save",
            "saved" => "Saved",
            "status" => "Status",
            "search.input" => "Search for filename, wildcards * allowed",
            "loading" => "Loading",
            "error.folders" => ", but not all folders where readable - Not existing or unreadable folders have been removed",
            "reload.page" => "Reload the page to see the changes",
            "playing" => "Playing",
            "video.notselected" => "No video selected",
            "ui.language" => "Interface Language"
        ),
        "de" => array(
            "shortcut-q" => "Stoppe\nPlayer",
            "shortcut-p" => "Pausieren\nFortfahren",
            "shortcut--" => "Lautstärke runter",
            "shortcut-+" => "Lautstärke rauf",
            "shortcut-left" => "Langsam zurück",
            "shortcut-right" => "Langsam vorwärts",
            "shortcut-down" => "Schnell zurück",
            "shortcut-up" => "Schnell vorwärts",
            "shortcut-z" => "Zeige Info",
            "shortcut-1" => "Video Langsamer",
            "shortcut-2" => "Video Schneller",
            "shortcut-j" => "Vorheriger Audio Stream",
            "shortcut-k" => "Nächster Audio Stream",
            "shortcut-i" => "Letztes Kapitel",
            "shortcut-o" => "Nächstes Kapitel",
            "shortcut-n" => "Vorheriger Untertitel Stream",
            "shortcut-m" => "Nächster Untertitel Stream",
            "shortcut-s" => "Untertitel an/aus",
            "shortcut-d" => "Weniger Untertitel Verzögerung",
            "shortcut-f" => "Mehr Untertitel Verzögerung",
            "yes" => "Ja",
            "no" => "Nein",
            "enabled" => "Aktiviert",
            "disabled" => "Deaktiviert",
            "folders.desc" => "Füge Ordnerpfade hinzu in denen deine Video Dateien liegen. Suche ist rekursiv\nDu kannst auch Streams wie rtmp://, rtstp://, http://, etc... hinzufügen",
            "option.speedfix.name" => "Double-speed Fix",
            "option.speedfix.desc" => "Aktiviere das wenn der Player mit Playspeed 2.000 startet",
            "option.autoplay-next.name" => "Automatisch nächstes Video",
            "option.autoplay-next.desc" => "Funktioniert nur wenn du das Browser Fenster offen lässt",
            "save" => "Speichern",
            "saved" => "Gespeichert",
            "status" => "Status",
            "search.input" => "Suche nach Dateiname, Wildcards * sind erlaubt",
            "loading" => "Wird geladen",
            "error.folders" => ", aber nicht alle Ordner waren lesbar - Nicht vorhandende oder nicht lesbare Ordner wurden entfernt",
            "reload.page" => "Lade die Seite neu um die Änderungen zu sehen",
            "playing" => "Spielt",
            "video.notselected" => "Kein Video selektiert",
            "ui.language" => "Interface Sprache"
        )
    );

    /**
    * Translate
    *
    * @param string $key
    * @return string
    */
    static function translate($key){
        if(isset(self::$translations[self::$language][$key])) return self::$translations[self::$language][$key];
        if(isset(self::$translations["en"][$key])) return self::$translations["en"][$key];
        return $key;
    }
}