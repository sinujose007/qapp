<?php
/**
* Root file of the app
*
*
*
* Reading method names and process the request using controller function.
* Will redirect to 404 if method not found
*
* 
* PHP 7.0
*
* Copyright 2021_?
*
* @location /public/index.php
* @created 2021-07-17
*/

require "../bootstrap.php";
use Src\Controller\SourceController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// read the endpoint name from the url 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basename = pathinfo($uri, PATHINFO_BASENAME);

// all of our endpoints start with /questions, everything else results in a 404 Not Found
if ($basename !== 'questions') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the question id is, of course, optional and must be a number: can impliment in future
$questionId = null;

//fetch the query parameter lang in which questions and choices should translate
$lang = null;
if(isset($_GET['lang'])){
	$lang = $_GET['lang'];
}

//fetch the method
$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and  ID to the Controller and process the HTTP request:
$controller = new SourceController($requestMethod, $questionId, $lang);
$controller->processRequest();
