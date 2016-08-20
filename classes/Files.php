<?php

/**
 * Description of Files
 *
 * @author TSmit, 2016
 */
class Files {

    public function dateienDetailsAusOrdnerAuflisten($ordner) {
        $alledateien = scandir($ordner);
        $wirklichdateien = array();
        foreach ($alledateien as $name) {
            if (!is_dir($name)) {
                $wirklichdateien[] = $name;
            }
        }
        return $wirklichdateien;
    }

    public function subOrdnerAuflisten($ordner) {
        $alledateien = scandir($ordner);
        $wirklichordner = array();
        foreach ($alledateien as $name) {
            if (is_dir($ordner . $name) AND $name != '.' AND $name != '..') {
                $wirklichordner[] = $name;
            }
        }
        return $wirklichordner;
    }

    public function ordnerErzeugen($ordnerPfad) {
        mkdir($ordnerPfad, 0777, true);
    }

    public function deleteOrdnerInhalt($ordnerPfad) {
        $handle = opendir($ordnerPfad);
        while ($data = readdir($handle)) {
            if (!is_dir($data) && $data != "." && $data != "..")
                unlink($ordnerPfad.'/'.$data);
        }
        closedir($handle);
    }

}
