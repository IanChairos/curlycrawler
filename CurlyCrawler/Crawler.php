<?php
namespace CurlyCrawler;
require_once 'IRequestFactory.php';
require_once 'ISearchEngine.php';
require_once 'IResultProcessor.php';
/**
 * Crawler
 * - goes through set of urls and searches them for certain matches
 * - optionally can log the progress messages
 * @author Langosh
 */
class Crawler {

	/** @var IRequestFactory */
	private $requestFactory;
	
	/** @var SearchEngine */
	private $searchEngine;
	
	/** @var array of ILogger instances */
	private $loggers;
	
	/** @var int|float delay between requests in secs|usecs */
	private $delay = 1;

	public function __construct( IRequestFactory $requestFactory, ISearchEngine $searchEngine, array $loggers = array() )
	{
		$this->requestFactory = $requestFactory;
		$this->searchEngine = $searchEngine;
		$this->setLoggers($loggers);
	}
	
	/**
	 * Starts crawling urls
	 * @param $urlSet RequestUrlSet
	 */
	public function crawl( RequestUrlSet $urlSet )
	{
		$this->log('[Started]');
		
		$urls = $urlSet->getUrls();
		$urlCount = count($urls);
		for( $i=0; $i<$urlCount; $i++ ){
			$url = $urls[$i];
			$request = $this->requestFactory->create( $url );
			
			$this->log('[Executing request] url: '.$url);
			try{
				$response = $request->execute();
			}catch( CurlRequestException $e ){
				$this->log('[CurlRequestException]: '.$e->getMessage(),ILogger::MSG_TYPE_ERROR);
				continue;
			}
			
			$this->log('[Searching]');
			try{
				$results = $this->searchEngine->search( $response, array('sourceURL'=>$url) );
				if( $results )
					$this->log('[Match(es) found]');
			}catch( SearchEngineException $e ) {
				$this->log('[SearchEngineException]: '.$e->getMessage(),ILogger::MSG_TYPE_ERROR);
			}
			
			
			// if delay is set and this is not the last request, sleep
			if( $this->delay && ($i+1)<$urlCount ){
				// microsleep vs. sleep
				if( is_float($this->delay) ){
					$this->log('[Sleeping] for: '.$this->delay.' micro secs');
					usleep($this->delay);
				}else{
					$this->log('[Sleeping] for: '.$this->delay.' secs');
					sleep($this->delay);
				}
			}
		}
		
		$this->log('[Finished]');
	}

	public function setLoggers( array $loggers )
	{
		$this->removeLoggers();
		foreach( $loggers as $logger ){
			$this->addLogger($logger);
		}
	}
	
	public function addLogger( ILogger $logger )
	{
		$this->loggers[] = $logger;
	}
	
	public function clearLoggers()
	{
		$this->loggers = array();
	}
	
	/**
	 * Sends message to loggers
	 * @param string $msg
	 * @param int $type
	 */
	protected function log( $msg,$type = ILogger::MSG_TYPE_INFO )
	{
		foreach( $this->loggers as $logger ){
			$logger->log( $msg,$type );
		}
	}
	
	/**
	 * Sets the delay between individual requests
	 * @param int $seconds
	 */
	public function setDelay( $seconds )
	{
		$this->delay = $seconds > 0 ? (int)$seconds : 0;
	}
	
	/**
	 * Sets the delay between individual requests in microseconds
	 * @param int $microSeconds
	 */
	public function setMicroDelay( $microSeconds )
	{
		$microSeconds = (float)$microSeconds;
		$this->delay = $microSeconds > 0 ? $microSeconds : 0;
	}
	
	public function getRequestFactory()
	{
		return $this->requestFactory;
	}

	public function setRequestFactory( IRequestFactory $requestFactory )
	{
		$this->requestFactory = $requestFactory;
		return $this;
	}

	public function getSearchEngine()
	{
		return $this->searchEngine;
	}

	public function setSearchEngine( ISearchEngine $searchEngine )
	{
		$this->searchEngine = $searchEngine;
		return $this;
	}
	

}
