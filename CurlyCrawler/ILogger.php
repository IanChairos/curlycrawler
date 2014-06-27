<?php
namespace CurlyCrawler;
/**
 * @author Langosh
 */
interface ILogger {
	
	const MSG_TYPE_INFO = 'info';
	const MSG_TYPE_WARNING = 'warning';
	const MSG_TYPE_ERROR = 'error';

	/**
	 * Logs message
	 * @param string $message
	 * @param string $type
	 */
	public function log( $message, $type );
	
}
