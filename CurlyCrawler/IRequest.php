<?php
namespace CurlyCrawler;
/**
 * IRequest
 * @author Langosh
 */
interface IRequest {
	
	/** @return mixed */
	public function execute($options = null);
	
	/** @return mixed */
	public function getOptions();
	
}
