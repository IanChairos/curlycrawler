<?php
namespace CurlyCrawler;
/**
 * MultiPatternSearchEngine
 *
 * @author Langosh
 */
abstract class MultiPatternSearchEngine extends SearchEngine {
	
	/** @var array of patterns to search for */
	private $searchPatterns;
	
	public function addSearchPattern( $pattern )
	{
		$this->searchPatterns[] = $pattern;
		return $this;
	}
	
	public function setSearchPatterns( array $searchPatterns )
	{
		$this->searchPatterns = $searchPatterns;
		return $this;
	}
	
	public function getSearchPatterns() {
		return $this->searchPatterns;
	}
	
}
