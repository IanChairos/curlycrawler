<?php
namespace CurlyCrawler;
require_once 'SearchHook.php';
/**
 * SaveToFileHook
 * - saves input data into file
 *
 * @author Langosh
 */
class SaveToFileHook extends SearchHook {
	
	/** @var ILogger */
	private $logger;
	
	/** @var string path to file */
	private $path = '';
	
	/**
	 * @param string $path for files
	 */
	public function __construct( $path )
	{
		$this->setSavePath($path);
	}
	
	public function exec(&$data, &$params) {
		if( isset($params['sourceURL']) ){
			$url = $params['sourceURL'];
			$urlParts = explode('/',$url);
			$paramPair = end($urlParts);
			$paramParts = explode('=', $paramPair);
			$lastParamValue = end($paramParts);
			$fileName = $lastParamValue.'-'.md5($url);
		}else{
			$fileName = uniqid();
		}
		
		$responseFilePath = rtrim($this->path,'/').'/'.$fileName.'.html';
		
		$this->log('[Saving response] in: '.$responseFilePath,ILogger::MSG_TYPE_INFO);
		
		$fileWritten = @file_put_contents($responseFilePath,$data);
		
		if( false === $fileWritten )
			$this->log('Error writing file ['.$responseFilePath.']', ILogger::MSG_TYPE_WARNING);
		
		if( $this->getNext() )
			$this->getNext()->exec($data,$params);
	}
	
	public function setSavePath( $path )
	{
		$this->path = $path;
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
	
}
