<?php
namespace CurlyCrawler;
require_once 'CurlyCrawlerException.php';
/**
 * SearchEngineException
 *
 * @author Langosh
 */
class SearchEngineException extends CurlyCrawlerException {
	
	static public function emptySearch()
	{
		throw new self('Cannot pefrom search without patterns.');
	}
	
	static public function invalidSearch( $additionalMessage = '' )
	{
		throw new self('Cannot pefrom search. '.$additionalMessage);
	}
	
	static public function invalidInput( $additionalMessage = '' )
	{
		throw new self('Invalid input.'.$additionalMessage);
	}
	
}
