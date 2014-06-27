<?php
namespace CurlyCrawler;
require_once 'IRequestFactory.php';
require_once 'CurlRequest.php';
/**
 * CurlRequestFactory
 * - used for repeated creation of cURL requests
 * with predefined options injected to each req.
 * @author Langosh
 */
class CurlRequestFactory implements IRequestFactory
{
	
	/** @var CurlRequestOptions used as default for each request */
	private $defaultOptions;
	
	/**
	 * @param CurlRequestOptions $defaultOptions
	 */
	public function __construct( CurlRequestOptions $defaultOptions = null )
	{
		$this->defaultOptions = $defaultOptions ? clone $defaultOptions : new CurlRequestOptions();
	}
	
	/**
	 * Creates cURL request instance
	 * @param string $url target url
	 * @param CurlRequestOptions $options for this request (is merged with defaults)
	 * @return CurlRequest
	 */
	public function create( $url,$options = null )
	{
		$requestOptions = clone $this->getDefaultRequestOptions();
		if( $options instanceof CurlRequestOptions )
			$requestOptions->mergeWith($options);
		
		$instance = new CurlRequest($url,$requestOptions);
		return $instance;
	}
	
	/**
	 * Set options that will apply by default to each cURL request
	 * @param CurlRequestOptions $options cURL options
	 */
	public function setDefaultRequestOptions( CurlRequestOptions $options )
	{
		$this->defaultOptions = $options;
	}
	
	public function getDefaultRequestOptions()
	{
		return $this->defaultOptions;
	}
	
}
