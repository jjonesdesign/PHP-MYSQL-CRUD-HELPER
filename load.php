<?php
require_once getcwd() . '/classes/BaseClass.php';

$dir= new RecursiveDirectoryIterator(getcwd() . "/classes/");
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    $fname = $file->getFilename();
    if($fname == "BaseClass.php"){
        continue;
    }
    if (preg_match('%\.php$%', $fname)) {
        include($file->getPathname());
    }
}