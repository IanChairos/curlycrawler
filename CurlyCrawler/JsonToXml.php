<?php
namespace CurlyCrawler;
/**
 * JsonToXml
 *
 * @author Langosh
 */
class JsonToXml extends SearchHook {
	
	public function exec(&$data, &$params) {
		$decoded = json_decode($data,true);
		
		if( !isset($decoded['interests']) )
			return;
		
		// utf8 encoding hack
		$xmlData = '<?xml version="1.0" encoding="UTF-8" ?><root>'.$decoded['interests'].'</root>';
		$data = new \SimpleXMLElement($xmlData);
	}
}
