<?php
//Pull in our functions file
require_once 'functions.php';

if ($urlPathArray[0] == "") {
     require_once 'demos/0-index.php';
}elseif ($urlPathArray[0] == "index.php"){
    header("Location: /");
}

elseif($urlPathArray[0] == "demo1"){
    require_once 'demos/1-initdb.php';
}

elseif($urlPathArray[0] == "demo2"){
    require_once 'demos/2-autoinc-ids.php';
}