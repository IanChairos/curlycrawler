<?php
namespace CurlyCrawler;
require_once 'IResultProcessor.php';
use \PDO as DatabaseConnection;
/**
 * Example UserDetailsProcessor
 *
 * @author Langosh
 */
class UserDetailsProcessor implements IResultProcessor {
	
	/** @var DatabaseConnection */
	private $db;
	
	/** @var ILogger */
	private $logger;
	
	public function __construct( )
	{
		$this->db = new DatabaseConnection('mysql:dbname=crawler;host=127.0.0.1;charset=utf-8', 'root', 'kolikol',
				array(DatabaseConnection::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'") );
	}
	
	public function process($searchInput, $searchParams, $results)
	{
		if( !$results )
			return;
		
		if( !isset($searchParams['sourceURL']) )
			return;
		
		$url = $searchParams['sourceURL'];
		$urlParts = parse_url($url);
		parse_str($urlParts['query'],$queryParams);
		$badooId = $queryParams['user_id'];
		
//		var_dump( ($badooId) );exit;
		
//		$urlparts = explode('/',$url);
//		$urlparts = end($urlparts);
//		$urlparts = explode('=',$url);
//		$badooId = end($urlparts);
		
		
		$this->log('Processing interests for: ['.$badooId.']',ILogger::MSG_TYPE_INFO);
		
		foreach( $results as $row ){
			$interestName = current($row->xpath('span/text()'));
			$this->createUserInterest($badooId,(string)$interestName);
		}
	}
	
	public function createUserInterest( $user,$interestName )
	{
		$new = $this->createInterest($interestName);
		if( $new )
			$this->log('New interest found: ['.$interestName.']',ILogger::MSG_TYPE_INFO);
		$interest = $this->getInterest($interestName);
		if( !$interest ){
			$this->log('Could not retrieve interest: ['.$interestName.']',ILogger::MSG_TYPE_WARNING);
			return;
		}
		
		$s = $this->db->exec("INSERT IGNORE INTO `user_interests` (user_id,interest_id) VALUES ('$user','{$interest->id}')");
//		var_dump($s,$interestName);
	}
	
	public function createInterest( $name )
	{
		return $this->db->exec("INSERT IGNORE INTO `interest` (name) VALUES ('$name')");
	}
	
	public function getInterest( $name )
	{
//		$this->log('<debug> get interest: ['.$name.']',ILogger::MSG_TYPE_INFO);
		$name = mysql_real_escape_string($name);
		$st = $this->db->query("SELECT id,name FROM `interest` WHERE name='$name'");
		$res = $st->fetch(DatabaseConnection::FETCH_OBJ);
		return $res;
	}
	
	public function getUser( $id )
	{
		$st = $this->db->query("SELECT id,badoo_id,name,image,url,date FROM `users_found` WHERE id='$id'");
		return $st->fetch(DatabaseConnection::FETCH_OBJ);
	}
	
	public function getUsers( $limit = 0, $offset = 0 )
	{
		$sql = 'SELECT id,badoo_id,name,image,url,date FROM `users_found` WHERE 1 ORDER BY date DESC';
		if( $limit ){
			if( $offset ){
				$sql .= ' LIMIT '.$offset.','.$limit;
			}else{
				$sql .= ' LIMIT '.$limit;
			}
		}
		$st = $this->db->query($sql);
		$st->setFetchMode(DatabaseConnection::FETCH_OBJ);
		return $st->fetchAll();
	}
	
	public function getInterestsFor( $badooId )
	{
		$st = $this->db->query('SELECT i.* FROM `interest` i JOIN `user_interests` ui ON (ui.interest_id = i.id AND ui.user_id = '.$badooId.') WHERE 1');
		$st->setFetchMode(DatabaseConnection::FETCH_OBJ);
		return $st->fetchAll();
	}
	
	public function getInterestNamesFor( $badooId )
	{
		$res = $this->getInterestsFor($badooId);
		return array_map( function($item){ return $item->name; }, $res);
	}
	
	public function getUsersWithInterests()
	{
		$st = $this->db->query('SELECT u.*,i.* FROM `users_found` u JOIN `user_interests` ui ON (ui.user_id = u.badoo_id) JOIN `interest` i ON (i.id = ui.interest_id) WHERE 1 ORDER BY date DESC LIMIT 10');
		$st->setFetchMode(DatabaseConnection::FETCH_OBJ);
		return $st->fetchAll();
	}

	public function setLogger( ILogger $logger = null )
	{
		$this->logger = $logger;
	}
	
	private function log( $msg, $type )
	{
		if( $this->logger )
			$this->logger->log($msg,$type);
	}
	
	public function test($n)
	{
		return $this->db->exec("INSERT IGNORE INTO `interest` (name) VALUES ('$n')");
	}
	
}
