<?php
namespace CurlyCrawler;
require_once 'ISearchEngine.php';
/**
 * SearchEngine
 *
 * @author Langosh
 */
abstract class SearchEngine implements ISearchEngine {
	
	/** @var SearchHook search preprocessing */
	private $beforeHook = null;
	
	/** @var array of search result processors */
	private $resultProcessors = array();
	
	/**
	 * Searches input for matches
	 * - first applies beforeHooks on input data
	 * - then searches for matches utilizing engine implementation
	 * - then sends results to each of assigned result processors
	 * @param mixed $data input
	 * @param array $params
	 * @return mixed found results
	 */
	public function search( $data, array $params = array() )
	{
		if( $this->beforeHook )
			$this->beforeHook->exec($data,$params);
		
		$results = $this->_search($data,$params);
		
		foreach( $this->resultProcessors as $processor ){
			$processor->process($data,$params,$results);
		}
		
		return $results;
	}
	
	/**
	 * Actual search implementation
	 * @abstract
	 * @param mixed $data input
	 * @param array $params
	 */
	abstract protected function _search( $data, &$params );
	
	public function beforeSearchHook( SearchHook $hook )
	{
		$this->beforeHook = $hook;
		return $this;
	}
	
	public function addResultProcessor( IResultProcessor $processor )
	{
		$this->resultProcessors[] = $processor;
	}
	
	public function clearResultProcessors()
	{
		$this->resultProcessors = array();
	}
	
}
