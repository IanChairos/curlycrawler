<?php
namespace CurlyCrawler;
require_once 'SearchEngine.php';
/**
 * SinglePatternSearchEngine
 *
 * @author Langosh
 */
abstract class SinglePatternSearchEngine extends SearchEngine {
	
	/** @var mixed pattern to search for */
	private $pattern;
	
	public function setSearchPattern( $pattern )
	{
		$this->pattern = $pattern;
		return $this;
	}
	
	public function getSearchPattern()
	{
		return $this->pattern;
	}
	
}
