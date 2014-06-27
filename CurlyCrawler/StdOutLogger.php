<?php
namespace CurlyCrawler;
require_once 'ILogger.php';
/**
 * Logs messages to standard output
 *
 * @author Langosh
 */
class StdOutLogger implements ILogger {
	
	/** @var string Line separator */
	private $lineSeparator;
	
	public function __construct( $lineSeparator = PHP_EOL )
	{
		$this->lineSeparator = $lineSeparator;
	}
	
	public function log($message, $type = ILogger::MSG_TYPE_INFO ) {
		echo '['.$type.'] '.$message.$this->lineSeparator;
	}
	
	/**
	 * @param string $separator
	 * @return StdOutLogger
	 */
	public function setLineSeparator( $separator )
	{
		$this->lineSeparator = $separator;
		return $this;
	}
}
