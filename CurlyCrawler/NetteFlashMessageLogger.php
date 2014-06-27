<?php
namespace CurlyCrawler;

use Nette\Application\IPresenter;
/**
 * Logs messages into presenter's flash messages
 *
 * @author Langosh
 */
class NetteFlashMessageLogger extends BaseLogger {
	
	/** @var IPresenter */
	private $presenter;
	
	public function __construct( IPresenter $presenter )
	{
		$this->presenter = $presenter;
	}

	protected function _log( $message, $type ) {
		$msgTypeNames = array(
			ILogger::MSG_TYPE_INFO => 'info',
			ILogger::MSG_TYPE_WARNING => 'warning',
			ILogger::MSG_TYPE_ERROR => 'error'
		);
		$this->presenter->flashMessage($message,$msgTypeNames[$type]);
	}
	
}
