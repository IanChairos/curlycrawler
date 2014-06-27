<?php
//namespace CurlyCrawler;
//require_once 'SearchEngine.php';
///**
// * XPathSearchEngine
// *
// * @author Langosh
// */
//class XPathSearchEngine extends SearchEngine {
//	
//	/**
//	 * Nested search - xpath[0] must contain xpath[1] etc...
//	 *  - returns the first element
//	 * @param \SimpleXMLElement $data
//	 * @param array $params
//	 * @return array
//	 */
//	protected function findAll($data, array $params = array())
//	{
//		$paths = $params[ISearchEngine::PARAM_SEARCH];
//		
//		$rootParams = $params;
//		$rootPath = array_shift($paths);
//		$rootParams[ISearchEngine::PARAM_SEARCH] = array($rootPath);
//		$matchedRoots = $this->findOne($data, $params);
//		
//		// TODO osetrit pocet '/'
//		$childPath = implode('//', $paths);
////		echo 'childPath : '.$childPath.' <br />'.PHP_EOL;
//		
//		$matches = array();
//		foreach( $matchedRoots as $rootNode ) {
//			$childParams = $params;
//			$childParams[ISearchEngine::PARAM_SEARCH] = array($childPath);
//			if( $this->findOne($rootNode,$childParams) )
//				$matches[] = $rootNode;
//		}
////		echo 'roots: '.$rootPath.PHP_EOL; var_dump($matchedRoots);
////		echo 'matches: '; var_dump($matches);
//		
//		return $matches;
//	}
//	
//	/**
//	 * Searches for one xpath match
//	 * @param \SimpleXMLElement $data
//	 * @param array $params
//	 * @return array
//	 */
//	protected function findOne($data, array $params = array())
//	{
//		if( !($data instanceof \SimpleXMLElement) )
//			throw SearchEngineException::invalidInput( 'Expected instanceof \SimpleXMLElement.' );
//				
//		$path = current($params[ISearchEngine::PARAM_SEARCH]);
//		return $data->xpath($path);
//	}
//
//}
