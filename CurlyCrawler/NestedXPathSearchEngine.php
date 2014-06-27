<?php
namespace CurlyCrawler;
require_once 'MultiPatternSearchEngine.php';
/**
 * NestedXPathSearchEngine
 * - N level search; returns first levels that matched all criteria
 * @author Langosh
 */
class NestedXPathSearchEngine extends MultiPatternSearchEngine {
	
	/**
	 * Nested search - xpath[0] must contain xpath[1] etc...
	 *  - returns the first element - xpath[0]
	 * @param \SimpleXMLElement $data
	 * @param array $params
	 * @return array
	 */
	protected function _search( $data, &$params )
	{
		if( !isset($params[ISearchEngine::PARAM_SEARCH]) ){
			$params[ISearchEngine::PARAM_SEARCH] = $this->getSearchPatterns();
		}
		$searchPaths = $params[ISearchEngine::PARAM_SEARCH];
		
		if( count($searchPaths) < 2 )
			throw SearchEngineException::invalidSearch('Search must contain at least 2 paths.');
		
		$rootPath = array_shift($searchPaths);
		$matchedRoots = $this->xpath($data,$rootPath);
		
		$childrenPath = array_shift($searchPaths);
		foreach( $searchPaths as $childPath ) {
			$childrenPath .= '//'.trim($childPath,'/');
		}
		
		$matches = array();
		foreach( $matchedRoots as $rootNode ) {
			$m = $this->xpath($rootNode,$childrenPath);
			if( $m )
				$matches[] = $rootNode;
		}
		
		return $matches;
	}
	
	/**
	 * Searches for one xpath match
	 * @param \SimpleXMLElement $data
	 * @param string $params
	 * @return array
	 */
	protected function xpath( $data, $path )
	{
		if( !($data instanceof \SimpleXMLElement) )
			throw SearchEngineException::invalidInput( 'Expected instanceof \SimpleXMLElement.' );
		
		return $data->xpath($path);
	}

	
}
