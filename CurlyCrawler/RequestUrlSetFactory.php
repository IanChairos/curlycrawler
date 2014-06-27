<?php
namespace CurlyCrawler;
require_once 'RequestUrlSet.php';
/**
 * RequestUrlSetFactory
 *
 * @author Langosh
 */
class RequestUrlSetFactory {
	
	/**
	 * Creates url set from the combination of $baseUrl and passed (GET) $params
	 * @param string $baseUrl
	 * @param array $params
	 * @return RequestUrlSet
	 */
	static public function createWithParams( $baseUrl, array $params = array() )
	{
		// no params, single url request
		if( !$params )
			return self::create( array($baseUrl) );
		
		// get max length from paramValues = #of urls
		foreach( $params as $paramName => $paramValues ) {
			static $max = 0;
			$c = count($paramValues);
			if( $c > $max )
				$max = $c;
		}
		
		// no param values defined, single url request
		if( 0 == $max )
			return self::create( array($baseUrl) );
		
		// gather all available params for each iteration
		$paramSet = array();
		for( $i=0; $i<$max; $i++ ){
			foreach( $params as $paramName => $paramValues ) {
				if( !key_exists($i, $paramValues) )
					continue;
				
				$paramSet[$i][$paramName] = $paramValues[$i];
			}
		}
		
		// generate urls
		$urlSet = array();
		foreach( $paramSet as $paramRow ) {
			$urlSet[] = $baseUrl.'?'.http_build_query($paramRow);
		}
		
		// create object instance
		return self::create($urlSet);
	}
	
	/**
	 * Creates set with predefined urls
	 * @param array $baseUrl
	 * @return RequestUrlSet
	 */
	static public function create( array $urlSet )
	{
		$instance = RequestUrlSet::create();
		$instance->setUrls($urlSet);
		return $instance;
	}
	
}
