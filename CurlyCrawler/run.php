<?php
namespace CurlyCrawler;
set_time_limit(0);
//ini_set('display_errors','on');
require_once 'SecureCrawler.php';
require_once 'RequestUrlSet.php';
require_once 'RequestUrlSetFactory.php';
require_once 'CurlRequest.php';
require_once 'CurlRequestFactory.php';
require_once 'StringSearchEngine.php';
require_once 'RegExpSearchEngine.php';
require_once 'NestedXPathSearchEngine.php';
require_once 'DatabaseXPathResultProcessor.php';
require_once 'SaveToFileHook.php';
require_once 'HtmlToSimpleXml.php';
require_once 'StdOutLogger.php';
$jarName = 'cookies.txt';
//$loginUrl = 'http://www.seznam.cz/';
//$searchUrl = 'http://www.seznam.cz/';
$loginUrl = 'http://badoo.com/signin/';
$searchUrl = 'http://badoo.com/search/';


$loginPostFields = array(
	'rt' => '241561', 'email' => 'stimpys@seznam.cz', 'password' => 'jikokol', 'submit' => 'post', 'remember' => '1'
);

$params = array();
//for( $i=1; $i<2; $i++ ){
for( $i=1; $i<9; $i++ ){
//for( $i=29; $i<60; $i++ ){
	$params['page'][] = $i;
}
$urlSet = RequestUrlSetFactory::createWithParams($searchUrl,$params);

//	CURLOPT_HTTPHEADER => array(
//		'Host'=> 'eu1.badoo.com',
//		'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',
//	),
$defaultRequestOptions = new CurlRequestOptions();
$defaultRequestOptions
	->setRetries(2)
	->setCookieFile($jarName)
	->setTimeout(5)
	->setFollowLocation(true);
$requestFactory = new CurlRequestFactory();
$requestFactory->setDefaultRequestOptions($defaultRequestOptions);

$loginOptions = new CurlRequestOptions();
$loginOptions
	->setCookieJar($jarName)
	->setCookieFile(false)
	->setPostFields($loginPostFields);
$loginRequest = $requestFactory->create($loginUrl,$loginOptions);

$userPath = "//a[@rel='profile-view']";
//$userPath = "//span[@class=' female']";
//$userPath = "//span[@class=' female']//a";
//$userPath = "//span[@class=' female']//a[@rel='profile-view']";
//$interestsPath = "div[@class='uinf_ints']//span";
//$interestsPath = "//span";
//$interestsPath = "//span[text()='Simpsonovi']";
//$interestsPath = "//span[text()='Drum and bass']";
//$interestsPath = "//span[@class='ints_i_tx' and text()='Drum and bass']";
$interestsPath = "div[@class='uinf_ints']//span[@class='ints_i_tx' and text()='Drum and bass']";
//$interestsPath = "div[@class='uinf_ints']/span/span[@class='ints_i_tx' and text()='Drum and bass']";
//$profilePhotoPath = "img";
//$userNamePath = "span[@class='c_name_tx']/text()";
//$profileUrlPath = "@href";
//$searchEngine = new StringSearchEngine();
//$searchEngine->addSearchPattern('Drum and bass');
//$searchEngine = new RegExpSearchEngine();
//$searchEngine->addSearchPattern('/set-as-HP-link/i');
$searchEngine = new NestedXPathSearchEngine();
//$searchEngine;
	$searchEngine->addSearchPattern($userPath);
	$searchEngine->addSearchPattern($interestsPath);
	$searchEngine->addResultProcessor( new DatabaseXPathResultProcessor() );
	
	$saveHtml = new SaveToFileHook(__DIR__.'/temp/');
	$convertHtml = new HtmlToSimpleXml();
	$searchEngine->beforeSearchHook( $saveHtml->setNext($convertHtml) );
	
//$crawler = new Crawler($requestFactory, $searchEngine, $resultProcessor);
$crawler = new SecureCrawler($requestFactory, $searchEngine);
//$crawler->setLoginRequest($loginRequest);
$stdoutLogger = new StdOutLogger(PHP_EOL);
//$stdoutLogger = new StdOutLogger('<br/>');
$saveHtml->setLogger( $stdoutLogger );
$crawler->addLogger( $stdoutLogger );
$crawler->setMicroDelay(500000);
//$crawler->setDelay(0);
//$crawler->setDelay(10);
//$crawler->talk();
//if( php_sapi_name()!='cli' )
//	$crawler->talk('<br/>');

//echo '<!DOCTYPE html><html lang="cs" dir="ltr"><head><meta http-equiv="Content-type" content="text/html;charset=utf-8"/>';

$crawler->crawl($urlSet);

