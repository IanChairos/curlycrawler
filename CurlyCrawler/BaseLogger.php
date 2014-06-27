<?php
namespace CurlyCrawler;
/**
 * BaseLogger
 * - parent for loggers
 * @author Langosh
 */
abstract class BaseLogger implements ILogger {
	
	/** @var array of types which the logger is interested in */
	private $catchTypes = array();
	
	/**
	 * Logs message of given type if that type is set to catch
	 * @param type $message
	 * @param type $type
	 */
	public function log( $message, $type )
	{
		if( isset($this->catchTypes[$type]) )
			$this->_log($message,$type);
	}
	
	/**
	 * Actual logging implementation
	 * @abstract
	 * @param string $message
	 * @param string $type
	 */
	abstract protected function _log( $message, $type );

	
	public function catchAll()
	{
		$this->catchTypes = array(
			ILogger::MSG_TYPE_INFO=>1,
			ILogger::MSG_TYPE_WARNING=>1,
			ILogger::MSG_TYPE_ERROR=>1
		);
		return $this;
	}
	
	public function catchNone()
	{
		$this->catchTypes = array();
		return $this;
	}
	
	public function catchInfo()
	{
		$this->catchTypes[ILogger::MSG_TYPE_INFO] = 1;
		return $this;
	}
	
	public function catchWarning()
	{
		$this->catchTypes[ILogger::MSG_TYPE_WARNING] = 1;
		return $this;
	}
	
	public function catchError()
	{
		$this->catchTypes[ILogger::MSG_TYPE_ERROR] = 1;
		return $this;
	}
	
}
