<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Add random salt strings incase we need them for examples
$DB_SALT1 = "7dmrPOU3O9JDJAxHODxsfATfkiMHWB5mnfdsrxEw";
$MD_SALT2 = "lLOD9VNLjiRkZ0RIPU4NEuY018Ms3tvMB22W1pUy";
$AUTH_SALT = "yeXG7Tn4VYWeE6WHe9XpKMPsuzh6eeUBaH41GntZ";
$API_SALT = "TW1eu7TVb2UgRL8VFdHQOacAfUPcicRwEhjCzXZL";

//Load Classes
require_once 'load.php';

//Load Secret
require_once 'secret.php';

//Initial Network Connection Class
$NetworkConnection = new NetworkConnection();

//URL Handeling Functions
$websiteURL = cleanUrlPath(curPageURL());
$urlArray = parse_url(curPageURL()); //Break down our current URL down into path and query
$urlpath = $urlArray['path']; // get just the path location of our url
$urlPathArray = cleanUrlPath($urlpath); // gives back a cleaned url. no forward or trailing slashes. Array Returned

function curPageURL() {
    $pageURL = 'http';
    //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
function cleanUrlPath($urlPath) {
    $urlPath = ltrim($urlPath, chr(47));
    $urlPath = rtrim($urlPath, '/');
    $urlArrayPath = explode("/", $urlPath);
    return $urlArrayPath;
}

//Function to check the tracking database to see if our UID has been used elsewhere yet
function checkUidIsUnique($uid) {
    try {
        $NetworkConnection = new NetworkConnection();
        $PDO = $NetworkConnection->getNewDbConnection();
            
        $stmt = $PDO->prepare("SELECT * FROM uids WHERE uid = ?");
        $stmt->execute([$uid]);
        $dbUid = $stmt->fetch();

        if (!$dbUid) {
            //uid does not yet exist, yay!
            return true;
        } else {
            //UID already exists
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}