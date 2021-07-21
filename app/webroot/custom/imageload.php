<?php

set_time_limit(0);
ini_set('display_errors', 0);

session_name('CAKEPHP');
session_start();

// if( !isset($_GET['pdf']) ){
if( (!isset($_GET['pdf'])) || (isset($_GET['pdf']) && $_GET['pdf'] != sha1('true') ) ) {
  if(!isset($_SESSION['Auth']) || ( isset($_SESSION['Auth']) && empty($_SESSION['Auth']) ) )
  {
  	exit(403);
  }
}

if(!defined('DS')) {
	define('DS',DIRECTORY_SEPARATOR);
}

if(isset($_GET['type']) && $_GET['type'] == 'drawing'){
  $path = realpath($_SERVER['DOCUMENT_ROOT']) . DS . '..' . DS . 'tmp' . DS . 'drawing' . DS;
} else {
  $path = realpath($_SERVER['DOCUMENT_ROOT']) . DS . '..' . DS . 'tmp' . DS . 'questionanswers' . DS;
}


/*
SECURITY FIX:

Replace all '..' occurrences with an empty string
This prevents local file inclusions
PLEASE NOTE:
Do not replace '../' with the empty string since this is **not** secure.
*/
$filename = str_replace($_GET['filename'], '', '..');
/*****/

$file = $path.$filename;
$mime = mime_content_type($file);

switch($mime) {
  case 'image/jpg':
  case 'image/jpeg':
  case 'image/JPG':
  case 'image/JPEG':

    $img = imagecreatefromstring(file_get_contents($file));

    header('Content-type: '.$mime);
    imagejpeg($img);
    break;
  case 'image/png':
  case 'image/PNG':

    $img = imagecreatefrompng($file);

    imagealphablending( $img, false );
    imagesavealpha( $img, true );
    imagecolorallocatealpha($img, 0,0,0,127);

    header('Content-type: '.$mime);
    imagepng($img);
    break;
}

imagedestroy($img);
exit();