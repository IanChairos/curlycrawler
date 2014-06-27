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
require_once 'FileLogger.php';
require_once 'UserDetailsProcessor.php';
require_once 'JsonToXml.php';

$jarName = 'cookies.txt';
$loginUrl = 'http://badoo.com/signin/';
$searchUrl = 'http://badoo.com/search/';


$loginPostFields = array(
	'rt' => '241561', 'email' => '****', 'password' => '****', 'submit' => 'post', 'remember' => '1'
);

//$resultEngine = new DatabaseXPathResultProcessor();
//$users = $resultEngine->getUsers();

$prc = new UserDetailsProcessor();
$users = $prc->getUsers(20,60);

//$prc->test('čočka');
//exit;

//$users = array( current($users) );
//$urls = array($u->url);

//echo '<pre>';
//print_r($users);
//echo '</pre>';
//exit;


foreach( $users as $user ) {
	$urls[] = 'http://eu1.badoo.com/interests/interests-edit-ws.phtml?action=show-list&user_id='.$user->badoo_id.'&ws=1';
//	$urls[] = 'http://eu1.badoo.com/interests/interests-edit-ws.phtml?action=show-list&user_id='.ltrim('0',$user->badoo_id);
}

$headers = array(
	'Accept-Charset'=>'utf-8',
	'Accept-Language'=>'cs-CZ,cs'
);

//$urls = array();
//$urls = array_map(function($item){ return $item->url; }, $users);

//$params = array();
////for( $i=1; $i<2; $i++ ){
//for( $i=1; $i<9; $i++ ){
////for( $i=29; $i<60; $i++ ){
//	$params['page'][] = $i;
//}
//$urlSet = RequestUrlSetFactory::createWithParams($searchUrl,$params);
$urlSet = RequestUrlSetFactory::create($urls);

//	CURLOPT_HTTPHEADER => array(
//		'Host'=> 'eu1.badoo.com',
//		'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',
//	),
$defaultRequestOptions = new CurlRequestOptions();
$defaultRequestOptions
	->setRetries(2)
	->setCookieFile($jarName)
	->setTimeout(5)
	->setFollowLocation(true)
	->setHttpHeaders($headers);
$requestFactory = new CurlRequestFactory();
$requestFactory->setDefaultRequestOptions($defaultRequestOptions);

$loginOptions = new CurlRequestOptions();
$loginOptions
	->setCookieJar($jarName)
	->setCookieFile(false)
	->setPostFields($loginPostFields);
$loginRequest = $requestFactory->create($loginUrl,$loginOptions);

//$interests = ''http://eu1.badoo.com/interests/interests-edit-ws.phtml?action=show-list&user_id=309287311

//$fp = __DIR__.'/temp/144700419-da625746005e863e864047980d0cc2cb.html';
//$searchInput = file_get_contents($fp);

$contPath = "//span[@class='ints_i']";
$interestsPath = "span[@class='ints_i_tx']";
$stdoutLogger = new StdOutLogger('<br/>');
$fileLogger = new FileLogger(__DIR__.'/log.txt');
//$stdoutLogger = new StdOutLogger(PHP_EOL);

$searchEngine = new NestedXPathSearchEngine();
	$searchEngine->addSearchPattern($contPath);
	$searchEngine->addSearchPattern($interestsPath);
	$resProc = new UserDetailsProcessor();
	$resProc->setLogger($fileLogger);
	$searchEngine->addResultProcessor( $resProc );
	
//	$saveHtml = new SaveToFileHook(__DIR__.'/temp/');
//	$convertHtml = new HtmlToSimpleXml();
	$convertor = new JsonToXml();
//	$searchEngine->beforeSearchHook( $saveHtml->setNext($convertor) );
	$searchEngine->beforeSearchHook( $convertor );
	
//	$r = $resProc->getInterestNamesFor('144700419');
//	echo '<pre>';
//	print_r($r);
//	echo '</pre>';
//	exit;
	
//	$res = $searchEngine->search($searchInput);
	
//	$resProc->process($searchInput, array('sourceURL'=>$urls[0]), $res);
	
//	echo '<pre>';
//	print_r($res);
//	echo '</pre>';
//	exit;
	
	
$crawler = new SecureCrawler($requestFactory, $searchEngine);
//$crawler->setLoginRequest($loginRequest);
//$stdoutLogger = new StdOutLogger('<br/>');
//$saveHtml->setLogger( $stdoutLogger );
$crawler->addLogger( $stdoutLogger );
$crawler->addLogger( $fileLogger );
//$crawler->setMicroDelay(500000);
//$crawler->setDelay(0);
$crawler->setDelay(7);
$crawler->crawl($urlSet);

