<?php
namespace CurlyCrawler;
require_once 'SearchEngine.php';
/**
 * RegExpSearchEngine
 *
 * @author Langosh
 */
class RegExpSearchEngine extends SinglePatternSearchEngine {
	
	protected function _search($data, &$params)
	{
		if( !isset($params[ISearchEngine::PARAM_SEARCH]) )
			$params[ISearchEngine::PARAM_SEARCH] = $this->getSearchPattern();
		
		$searchPattern = $this->getSearchPattern();
		
		if( !$searchPattern )
			throw SearchEngineException::emptySearch();
		
		$matches = array();
		preg_match($searchPattern, $data, $matches);
		if( $matches )
			return $matches;
		
		return false;
	}

}
