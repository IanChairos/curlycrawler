<?php
namespace CurlyCrawler;
require_once 'IRequest.php';
require_once 'Crawler.php';
require_once 'CurlRequest.php';
/**
 * SecureCrawler
 * - URL Crawler with logIn capability
 * @author Langosh
 */
class SecureCrawler extends Crawler {
	
	/** @var IRequest */
	private $loginRequest;
	
	/**
	 * Sets the request to execute to log in
	 * @param IRequest $request
	 */
	public function setLoginRequest( IRequest $request )
	{
		$this->loginRequest = $request;
	}
	
	/**
	 * Tries to log in and then
	 * starts going sequentialy through passed url set
	 * @param RequestUrlSet $urlSet
	 */
	public function crawl( RequestUrlSet $urlSet )
	{
		if( $this->loginRequest ){
			$this->log('[Logging in]');
			try{
				$this->loginRequest->execute();
			}catch( CurlRequestException $e ){
				$this->log('[LogIn][CurlRequestException]: '.$e->getMessage(),ILogger::MSG_TYPE_ERROR);
				$this->log('[Aborted]: could not log in');
				exit;
			}
		}
		parent::crawl($urlSet);
	}
	
}
