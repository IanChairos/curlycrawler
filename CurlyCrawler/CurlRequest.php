<?php
namespace CurlyCrawler;
require_once 'IRequest.php';
require_once 'CurlRequestOptions.php';
require_once 'CurlRequestException.php';
/**
 * Wrapper for handling cURL requests
 *
 * @author Langosh
 */
class CurlRequest implements IRequest
{
	/** @var mixed cURL handle */
	public $handle;
	
	/** @var string URL target */
	private $url;
	
	/** @var CurlRequestOptions pre set request options */
	private $options;
	
	/** @var mixed last response */
	private $lastData;
	
	/**
	 * @param string $url
	 * @param CurlRequestOptions $options
	 */
	public function __construct( $url,CurlRequestOptions $options = null )
	{
		if( !$url )
			throw CurlRequestException::emptyURL();
		
		$this->handle = curl_init($url);
		if( !$this->handle )
			throw CurlRequestException::noHandle();
		
		$this->options = new CurlRequestOptions($this->getDefaultOptions());
		if( $options )
			$this->options->mergeWith($options);
	}
	
	/**
	 * Executes cURL request and returns response
	 * @param CurlRequestOptions $options
	 * @return mixed response
	 * @throws CurlRequestException on curl_error
	 */
	public function execute( $options = null )
	{
		$requestOptions = clone $this->options;
		if( $options instanceof CurlRequestOptions )
			$requestOptions->mergeWith($options);
		
		// try to set all CURLOPTs for this request
		if( curl_setopt_array($this->handle,$requestOptions->getOptions()) === FALSE )
			throw CurlRequestException::badOptionValue($requestOptions->getOptions());
		
		$data = null;
		$retries = $this->options->getRetries();
		for( $try=0; $try < $retries; $try++ ){
			// exec actual request
			$data = curl_exec($this->handle);
			// store error if any
			$error = curl_error($this->handle);
			
			// if this was the last try and we still could not make it through
			if( $error && (($try+1)==$retries) )
				throw CurlRequestException::retriesExhausted($error);
			
			// if we have data we are done
			if( $data )
				break;
		}
		
		$this->lastData = $data;
		return $data;
	}
	
	/**
	 * Initializes cURL handle with defined url
	 * @param string $url
	 * @return CurlRequest
	 */
	public function open( $url )
	{
		if( $this->handle === null ){
			if( !$url )
				throw CurlRequestException::emptyURL();
			$this->handle = curl_init( $url );
			$this->url = $url;
		}
		return $this;
	}
	
	/**
	 * ReInitializes cURL handle with last defined url
	 * @return CurlRequest
	 */
	public function reopen()
	{
		if( ($this->handle === null) && $this->url )
			$this->handle = curl_init( $this->url );
		return $this;
	}
	
	/**
	 * Close cURL handle and release resources
	 * @return CurlRequest
	 */
	public function close()
	{
		curl_close($this->handle);
		$this->handle = null;
		return $this;
	}

	/**
	 * Returns last response
	 * @return mixed
	 */
	public function getData()
	{
		return $this->lastData;
	}
	
	/**
	 * @return array cURL options
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Returns basic default options for request
	 * @return array cURL options
	 */
	private function getDefaultOptions()
	{
		return array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_CONNECTTIMEOUT => 120,
		);
	}
	
	public function __destruct()
	{
		if( $this->handle === null )
			return;
		
		$this->close();
	}
	
	
}
