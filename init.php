<?php
require_once('includes/connect.php');
// require_once('includes/functions.php');

$css = '../CSS/';
$cssHome = 'CSS/';
$images = '../images/';
$includes = '../includes/';
$includesHome = 'includes/';
$js = '../js/';
$adminPages = '../AdminPages/';
$UserPages = '../UserPages/';

if (!(isset($noNavAdmin))) {
    include($includes . 'inline_header.php');
}
if (!(isset($noNavUser))) {
    include($includes . 'header.php');
}
if (isset($footer)) {
    include('includes/footer.php');
}