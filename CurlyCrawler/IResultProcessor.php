<?php
namespace CurlyCrawler;
/**
 * IResultProcessor
 * - search engine result processing
 * @author Langosh
 */
interface IResultProcessor {
	
	public function process( $searchInput, $searchParams, $results );
	
}
