<?php
namespace CurlyCrawler;
require_once 'SinglePatternSearchEngine.php';
require_once 'SearchEngineException.php';
/**
 * StringSearchEngine
 * - Searches input string for single|multiple strings occurence
 * @author Langosh
 */
class StringSearchEngine extends SinglePatternSearchEngine {
	
	const PARAM_CASE_SENSITIVE = 5;
	
	/**
	 * Searches for either the string passed in params or default pre set string
	 * and returns first string occurence position or false when nothing is found
	 * @param string $data
	 * @param array $params
	 * @return int|boolean
	 */
	protected function _search( $data, &$params )
	{
		$caseInsensitive = isset($params[self::PARAM_CASE_SENSITIVE]) && $params[self::PARAM_CASE_SENSITIVE];
		
		if( !isset($params[ISearchEngine::PARAM_SEARCH]) ){
			$params[ISearchEngine::PARAM_SEARCH] = $this->getSearchPattern();
		}
		$searchString = $params[ISearchEngine::PARAM_SEARCH];
		
		if( !$searchString )
			throw SearchEngineException::emptySearch();
		
		if( $caseInsensitive ){
			return stripos($data,$searchString);
		}else{
			return strpos($data,$searchString);
		}
	}

}
