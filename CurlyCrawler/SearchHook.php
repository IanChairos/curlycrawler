<?php
namespace CurlyCrawler;
/**
 * SearchHook
 * - search input preprocessing
 * - action performed on data before searching them utilizing a search engine
 * @author Langosh
 */
abstract class SearchHook {
	
	/** @var SearchHook|null next hook in chain */
	private $next = null;
	
	/**
	 * Apply changes on $data and $params
	 * @param mixed $data
	 * @param array $params
	 */
	abstract public function exec( &$data, &$params );

	/**
	 * Sets the next hook to be called after this one
	 * @param SearchHook $hook
	 * @return SearchHook
	 */
	public function setNext( SearchHook $hook )
	{
		$this->next = $hook;
		return $this;
	}
	
	/**
	 * @return SearchHook
	 */
	public function getNext()
	{
		return $this->next;
	}
	
}
