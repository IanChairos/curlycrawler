<?php
namespace CurlyCrawler;
require_once 'IResultProcessor.php';
use \PDO as DatabaseConnection;
/**
 * Example DatabaseXPathResultProcessor
 *
 * @author Langosh
 */
class DatabaseXPathResultProcessor implements IResultProcessor {
	
	/** @var DatabaseConnection */
	private $db;
	
	public function __construct( )
	{
		$this->db = new DatabaseConnection('mysql:dbname=crawler;host=127.0.0.1;charset=utf8', '****', '****');
	}
	
	public function process($searchInput, $searchParams, $results)
	{
		if( !$results )
			return;
		
		foreach( $results as $element ) {
			$profilePhotoPath = "img";
			$userNamePath = "span[@class='c_name_tx']/text()";
			$profileUrlPath = "@href";
			/* @var $element \SimpleXMLElement */
			$img = current($element->xpath($profilePhotoPath));
			$name = current($element->xpath($userNamePath));
			$href =  current($element->xpath($profileUrlPath));
			
			$image = $img->asXML();
			$url = (string)$href;
			$name = (string)$name;
			preg_match('/\/(\d+)\//', $url, $regExpMatches);
			$badooId = end($regExpMatches);
			
			$this->db->exec("INSERT INTO `users_found` (badoo_id,name,image,url,date) VALUES ('$badooId','$name','$image','$url',NOW()) ON DUPLICATE KEY UPDATE date = NOW(), image = '$image', url = '$url', name='$name'");
		}
		
	}
	
	public function getUsers()
	{
		$st = $this->db->query('SELECT id,badoo_id,name,image,url,date FROM `users_found` WHERE 1 ORDER BY date DESC LIMIT 30');
		$st->setFetchMode(DatabaseConnection::FETCH_OBJ);
		return $st->fetchAll();
	}

}
