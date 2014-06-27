<?php
namespace CurlyCrawler;
/**
 * IRequestFactory
 * @author Langosh
 */
interface IRequestFactory {
	
	/** @return IRequest */
	public function create( $url,$options = null );
	
}
