<?php
namespace CurlyCrawler;
/**
 * ISearchEngine
 * 
 * @author Langosh
 */
interface ISearchEngine {
	const PARAM_SEARCH = 'pattern';
	
	public function search( $data, array $params = array() );
			
}
