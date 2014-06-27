<?php
namespace CurlyCrawler;
set_time_limit(0);
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

$lastPage = 1;
$limit = 100;

$delay = 3;
$retries = 3;
$timeout = 3;
		set_time_limit(0);
		$jarName = 'cookies.txt';
		$loginUrl = 'http://badoo.com/signin/';
		$searchUrl = 'http://badoo.com/search/';

		$loginPostFields = array(
			'rt' => '1e9472', 'email' => '****', 'password' => '****', 'submit' => 'post', 'remember' => '1'
		);

		$params = array();
		for( $i=$lastPage; $i<($lastPage+$limit); $i++ ){
			$params['page'][] = $i;
		}
		$urlSet = RequestUrlSetFactory::createWithParams($searchUrl,$params);

		$defaultRequestOptions = new CurlRequestOptions();
		$defaultRequestOptions
			->setRetries($retries)
			->setCookieFile($jarName)
			->setTimeout($timeout)
			->setFollowLocation(true);
		$requestFactory = new CurlRequestFactory();
		$requestFactory->setDefaultRequestOptions($defaultRequestOptions);

		$loginOptions = new CurlRequestOptions();
		$loginOptions
			->setCookieJar($jarName)
			->setCookieFile(false)
			->setPostFields($loginPostFields);
		$loginRequest = $requestFactory->create($loginUrl,$loginOptions);

		$fileLogger = new FileLogger(__DIR__.'/daemon.log');
//		$flashLogger = new NetteFlashMessageLogger($this->getPresenter());
//		$flashLogger->catchAll();
		
		$userPath = "//a[@rel='profile-view']";
		$interestsPath = "div[@class='uinf_ints']//span[@class='ints_i_tx' and text()='Drum and bass']";
		$searchEngine = new NestedXPathSearchEngine();
			$searchEngine->addSearchPattern($userPath);
			$searchEngine->addSearchPattern($interestsPath);
			$searchEngine->addResultProcessor( new DatabaseXPathResultProcessor() );
			$convertHtml = new HtmlToSimpleXml();
			$searchEngine->beforeSearchHook( $convertHtml );

		$crawler = new SecureCrawler($requestFactory, $searchEngine);
//		$crawler->setLoginRequest($loginRequest);
//		$stdoutLogger = new StdOutLogger(PHP_EOL);
		//$stdoutLogger = new StdOutLogger('<br/>');
//		$saveHtml->setLogger( $stdoutLogger );
		$crawler->addLogger( $fileLogger );
//		$crawler->addLogger( $flashLogger );
		$crawler->setDelay($delay);
		$crawler->crawl($urlSet);
