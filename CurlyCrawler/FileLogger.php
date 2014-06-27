<?php
namespace CurlyCrawler;
/**
 * FileLogger
 * - logs messages into a file
 * @author Langosh
 */
class FileLogger implements ILogger {
	
	/** @var string path */
	private $path;
	
	/**
	 * @param string $path to file
	 */
	public function __construct( $path )
	{
		$this->path = $path;
		file_put_contents($this->path,PHP_EOL.'[System] Logger Init: '.strftime("%X %H:%M:%S").PHP_EOL,FILE_APPEND);
	}
	
	public function log($message,$type) {
		$msg = "[$type] $message".PHP_EOL;
		file_put_contents($this->path,$msg,FILE_APPEND);
	}
}
