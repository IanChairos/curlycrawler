<?php
namespace CurlyCrawler;
require_once 'CurlRequestOptions.php';
/**
 * CurlRequestException
 *
 * @author Langosh
 */
class CurlRequestException extends \Exception {
	
	static public function emptyURL()
	{
		return new self('Cannot create cURL request without URL.');
	}
	
	static public function noHandle()
	{
		return new self('Could not init cURL request handle.');
	}
	
	static public function retriesExhausted( $curl_error )
	{
		return new self('cURL Request failed - # of retries reached limit. Error: '.$curl_error);
	}
	
	static public function badOptionValue( CurlRequestOptions $optionsThatFailed )
	{
		return new self('Bad option value encountered. Options: '.$optionsThatFailed->getDebug().'');
	}
	
}
