<?php

set_time_limit(0);
ini_set('display_errors', 1);

session_name('CAKEPHP');
session_start();

if(!isset($_SESSION['Auth']) || ( isset($_SESSION['Auth']) && empty($_SESSION['Auth']) ) )
{
	exit(403);
}

// $upload_dir = DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR;
// $upload_dir = DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;

// if(strstr($_SERVER['HTTP_ORIGIN'],'local')){
// 	$upload_dir = DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'webroot'.$upload_dir;
// }

define('DS',DIRECTORY_SEPARATOR);

$upload_dir = realpath($_SERVER['DOCUMENT_ROOT']). DS . '..' . DS . 'tmp' . DS . 'questionanswers' . DS;
// die(json_encode($root));

// $upload_dir = '/data/www/testportal.test-correct.nl/app/webroot/img/upload/';
// $imgSrc = '/img/upload/';

$files = $_FILES;

foreach($files as $file)
{
	// exit(json_encode($file));
	$stream = file_get_contents($file['tmp_name']);
	$ext = pathinfo($file['tmp_name'], PATHINFO_EXTENSION);
	// $base64 = 'data:'.$file['type'].';base64,'.base64_encode($stream);
	// $return = array('url' => $base64);
	$hash = generateRandomString(20);
	$filename = $hash;
	move_uploaded_file($file['tmp_name'],$upload_dir.$filename);

	$filesrc = $_SERVER['HTTP_ORIGIN'].DS.'custom'.DS.'imageload.php?filename='.urlencode($filename);
	$return = array('url' => $filesrc,'uploaded'=>1);

	// $return = array('url' => $imgSrc.$hash.'_'.$file['name']);
}

exit(json_encode($return));

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
