<?php
namespace CurlyCrawler;
/**
 * Urls for requests
 *
 * @author Langosh
 */
class RequestUrlSet {
	
	/** @var array */
	private $urls = array();
	
	static public function create()
	{
		return new self();
	}
	
	/**
	 * Returns array of urls
	 * @return type
	 */
	public function getUrls()
	{
		return $this->urls;
	}
	
	/**
	 * Sets the urls
	 * @param array $urls
	 * @return RequestUrlSet
	 */
	public function setUrls( array $urls )
	{
		$this->urls = $urls;
		return $this;
	}
	
	/**
	 * Adds url to the set
	 * @param string $url
	 * @return RequestUrlSet
	 */
	public function addUrl( $url )
	{
		$this->urls[] = $url;
		return $this;
	}

	
}
