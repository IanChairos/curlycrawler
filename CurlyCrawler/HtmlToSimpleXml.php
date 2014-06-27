<?php
namespace CurlyCrawler;
require_once 'SearchHook.php';
/**
 * HtmlToSimpleXml
 *
 * @author Langosh
 */
class HtmlToSimpleXml extends SearchHook {
	
	public function exec( &$data, &$params )
	{
		
//		$dom = new \DOMDocument();
//		
//		if( $this->warnings ) {
//			$xmlData = $dom->saveXML();
//			$data = new \SimpleXMLElement($xmlData);
//		}else{
//		file_put_contents('/temp/wrk', $data);
//		$data = file_get_contents('/temp/wrk');
//			@$dom->loadHTMLFile('/temp/wrk');
//			$xmlData = '<?xml encoding="UTF-8" '.$data.'';
//		var_dump( file_put_contents(TEMP_DIR.'/tidy.txt', $data.'') );exit;	
//	$pageDom = new DomDocument();    
//    $searchPage = mb_convert_encoding($data, 'ISO-8859-1', "ISO-8859-2"); 
    $data = mb_convert_encoding($data, 'HTML-ENTITIES', "UTF-8"); 
//    @$pageDom->loadHTML($searchPage);
			
//			$doc = new \DOMDocument();
//			@$doc->loadHTML($searchPage);

//		$data = str_replace("\n", '', $data.'');
//		$data = str_replace("&#13;", '', $data.'');
//		$html = file_get_contents('http://www.some-site.org/page.aspx');
		$config = array(
//		  'clean' => 'yes',
//			'enclose-block-text'=>1,
			'enclose-text'=>1,
//		  'input-xml' => 1,
		  'output-xml' => 1,
		  'bare' => 1,
		  'doctype' => 'omit',
		);
//		$tidy = new \tidy;
//		$tidy->
//		$tidy = \tidy_parse_string($searchPage, $config, 'utf8');
		$tidy = \tidy_parse_string($data, $config, 'utf8');
		$tidy->cleanRepair();
		
//		$str = strip_tags($tidy.'','<body>');
		
	file_put_contents('/temp/wrk', $tidy->body().'');	
		
//		$tidy = '<?xml version="1.0" encoding="UTF-8" ><html>'.$tidy.'</html>';
		
//		$dom = new \DOMDocument;
//		$dom->loadHTML($tidy);
		
//			// dirty fix
//			foreach ($doc->childNodes as $item)
//				if ($item->nodeType == XML_PI_NODE)
//					$doc->removeChild($item); // remove hack
//			$doc->encoding = 'UTF-8'; // insert proper
			
//			var_dump( mb_detect_encoding($data) ); exit;
//			
//			@$dom->loadHTML( $data);
//			$dom->encoding = "UTF-8";
//			$xmlData = $dom->saveXML();
//			$xmlData=$tidy->
//			$xmlData = 
			
//			var_dump($xmlData);exit;
			
			
//			$data = new \SimpleXMLElement($xmlData);
			$data = new \SimpleXMLElement($tidy);
//			$data->
		var_dump( file_put_contents(TEMP_DIR.'/tidy.txt', $data->asXML()) );
//		}
//		var_dump($data);
//		var_dump($xmlData);
//		exit;
		// clear memory
//		unset($dom);
		
		if( $this->getNext() )
			$this->getNext()->exec($data,$params);
	}
	
}
