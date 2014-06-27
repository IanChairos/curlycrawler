<?php
namespace CurlyCrawler;
/**
 * Wrapper for holding and manipulating cURL options for cURL requests
 *
 * @author Langosh
 */
class CurlRequestOptions {
	
	/** @var array of cURLopts */
	private $options;
	
	/** @var int how many times to try to send this request on failure */
	private $retries = 2;
	
	/**
	 * Creates new options holder with passed cURL options
	 * @param array $options
	 */
	public function __construct( array $options = array() ) {
		$this->setOptions($options);
	}
	
	public function setCookie( $cookie )
	{
		$this->options[CURLOPT_COOKIE] = $cookie;
		return $this;
	}
	
	public function setCookieJar( $jarPath )
	{
		$this->options[CURLOPT_COOKIEJAR] = $jarPath;
		return $this;
	}
	
	public function setCookieFile( $cookieFilePath )
	{
		$this->options[CURLOPT_COOKIEFILE] = $cookieFilePath;
		return $this;
	}
	
	public function setCookieSessiom( $cookieSession )
	{
		$this->options[CURLOPT_COOKIESESSION] = $cookieSession;
		return $this;
	}
	
	public function setFollowLocation( $follow )
	{
		$this->options[CURLOPT_FOLLOWLOCATION] = (bool)$follow;
		return $this;
	}
	
	public function setTimeout( $timeout )
	{
		$this->options[CURLOPT_CONNECTTIMEOUT] = (int)$timeout;
		return $this;
	}
	
	public function setTimeoutMillisecs( $timeoutMs )
	{
		$this->options[CURLOPT_CONNECTTIMEOUT_MS] = (int)$timeoutMs;
		return $this;
	}
	
	public function setPostFields( array $postFields )
	{
		$this->options[CURLOPT_POSTFIELDS] = $postFields;
		return $this;
	}
	
	public function setHttpHeaders( array $headers )
	{
		$this->options[CURLOPT_HTTPHEADER] = $headers;
		return $this;
	}
	
	public function getOptions() {
		return $this->options;
	}

	public function setOptions($options) {
		foreach( $options as $k => $v ){
			$this->setOption($k,$v);
		}
	}
	
	/**
	 * Try to set given option
	 * - triggers error on bad value
	 * @param int $key
	 * @param mixed $value
	 */
	public function setOption($key,$value)
	{
		// filter out invalid options
		if( $this->isValidOption($key) ){
			$this->options[$key] = $value;
		}else{
			trigger_error('Invalid cURL option encountered: ['.$key.' => '.$value.']', E_USER_NOTICE);
		}
	}

	public function hasOption($key)
	{
		return isset($this->options[$key]);
	}
	
	private function isValidOption( $key )
	{
		$validConstants = $this->getCurloptConstantNames();
		return isset($validConstants[$key]);
	}
	
	/**
	 * Sets number of retries till the request fails
	 * @param int $number
	 */
	public function setRetries( $number )
	{
		$number = (int)$number;
		$this->retries = max( array($number,1) );
		return $this;
	}
	
	/**
	 * Returns number of max retries set
	 * @return int (always > 0)
	 */
	public function getRetries()
	{
		return $this->retries;
	}
	
	/**
	 * Merges this options with another options
	 * @param CurlRequestOptions $options
	 */
	public function mergeWith( CurlRequestOptions $options )
	{
		$this->options = $options->getOptions() + $this->options;
	}
	
	/**
	 * Returns current cURL options set in human readable format
	 * @return array
	 */
	public function getDebug()
	{
		return self::getDebugOptions($this->options);
	}
	
	/**
	 * Translates cURL constants in passed array to named constants
	 * @param array $options
	 * @return array
	 */
	static public function getDebugOptions( array $options )
	{
		$constantNames = self::getCurloptConstantNames();
		$dbg = array();
		foreach( $options as $k => $v ) {
			if( isset($constantNames[$k]) ){
				$dbg[$constantNames[$k]] = $v;
			}
		}
		return $dbg;
	}
	
	/**
	 * Returns array of cURL constant values => constant names
	 *  - used for debug purposes
	 * @return array
	 */
	static public function getCurloptConstantNames()
	{
		return array(
			CURLOPT_AUTOREFERER=> 'CURLOPT_AUTOREFERER',
			CURLOPT_BINARYTRANSFER => 'CURLOPT_BINARYTRANSFER',
			CURLOPT_BUFFERSIZE => 'CURLOPT_BUFFERSIZE',
			CURLOPT_CAINFO => 'CURLOPT_CAINFO',
			CURLOPT_CAPATH => 'CURLOPT_CAPATH',
			CURLOPT_CERTINFO => 'CURLOPT_CERTINFO',
			CURLOPT_CLOSEPOLICY => 'CURLOPT_CLOSEPOLICY',
			CURLOPT_CONNECTTIMEOUT => 'CURLOPT_CONNECTTIMEOUT',
			CURLOPT_CONNECTTIMEOUT_MS => 'CURLOPT_CONNECTTIMEOUT_MS',
			CURLOPT_COOKIE => 'CURLOPT_COOKIE',
			CURLOPT_COOKIEFILE => 'CURLOPT_COOKIEFILE',
			CURLOPT_COOKIEJAR => 'CURLOPT_COOKIEJAR',
			CURLOPT_COOKIESESSION => 'CURLOPT_COOKIESESSION',
			CURLOPT_CRLF => 'CURLOPT_CRLF',
			CURLOPT_CUSTOMREQUEST => 'CURLOPT_CUSTOMREQUEST',
			CURLOPT_DNS_CACHE_TIMEOUT => 'CURLOPT_DNS_CACHE_TIMEOUT',
			CURLOPT_DNS_USE_GLOBAL_CACHE => 'CURLOPT_DNS_USE_GLOBAL_CACHE',
			CURLOPT_EGDSOCKET => 'CURLOPT_EGDSOCKET',
			CURLOPT_ENCODING => 'CURLOPT_ENCODING',
			CURLOPT_FAILONERROR => 'CURLOPT_FAILONERROR',
			CURLOPT_FILE => 'CURLOPT_FILE',
			CURLOPT_FILETIME => 'CURLOPT_FILETIME',
			CURLOPT_FOLLOWLOCATION => 'CURLOPT_FOLLOWLOCATION',
			CURLOPT_FORBID_REUSE => 'CURLOPT_FORBID_REUSE',
			CURLOPT_FRESH_CONNECT => 'CURLOPT_FRESH_CONNECT',
			CURLOPT_FTPAPPEND => 'CURLOPT_FTPAPPEND',
			CURLOPT_FTPLISTONLY => 'CURLOPT_FTPLISTONLY',
			CURLOPT_FTPPORT => 'CURLOPT_FTPPORT',
			CURLOPT_FTPSSLAUTH => 'CURLOPT_FTPSSLAUTH',
			CURLOPT_FTP_CREATE_MISSING_DIRS => 'CURLOPT_FTP_CREATE_MISSING_DIRS',
			CURLOPT_FTP_FILEMETHOD => 'CURLOPT_FTP_FILEMETHOD',
			CURLOPT_FTP_SKIP_PASV_IP => 'CURLOPT_FTP_SKIP_PASV_IP',
			CURLOPT_FTP_SSL => 'CURLOPT_FTP_SSL',
			CURLOPT_FTP_USE_EPRT => 'CURLOPT_FTP_USE_EPRT',
			CURLOPT_FTP_USE_EPSV => 'CURLOPT_FTP_USE_EPSV',
			CURLOPT_HEADER => 'CURLOPT_HEADER',
			CURLOPT_HEADERFUNCTION => 'CURLOPT_HEADERFUNCTION',
			CURLOPT_HTTP200ALIASES => 'CURLOPT_HTTP200ALIASES',
			CURLOPT_HTTPAUTH => 'CURLOPT_HTTPAUTH',
			CURLOPT_HTTPGET => 'CURLOPT_HTTPGET',
			CURLOPT_HTTPPROXYTUNNEL => 'CURLOPT_HTTPPROXYTUNNEL',
			CURLOPT_HTTP_VERSION => 'CURLOPT_HTTP_VERSION',
			CURLOPT_INFILE => 'CURLOPT_INFILE',
			CURLOPT_INFILESIZE => 'CURLOPT_INFILESIZE',
			CURLOPT_INTERFACE => 'CURLOPT_INTERFACE',
			CURLOPT_IPRESOLVE => 'CURLOPT_IPRESOLVE',
			CURLOPT_KEYPASSWD => 'CURLOPT_KEYPASSWD',
			CURLOPT_KRB4LEVEL => 'CURLOPT_KRB4LEVEL',
			CURLOPT_LOW_SPEED_LIMIT => 'CURLOPT_LOW_SPEED_LIMIT',
			CURLOPT_LOW_SPEED_TIME => 'CURLOPT_LOW_SPEED_TIME',
			CURLOPT_MAXCONNECTS => 'CURLOPT_MAXCONNECTS',
			CURLOPT_MAXREDIRS => 'CURLOPT_MAXREDIRS',
			CURLOPT_NETRC => 'CURLOPT_NETRC',
			CURLOPT_NOBODY => 'CURLOPT_NOBODY',
			CURLOPT_NOPROGRESS => 'CURLOPT_NOPROGRESS',
			CURLOPT_NOSIGNAL => 'CURLOPT_NOSIGNAL',
			CURLOPT_PORT => 'CURLOPT_PORT',
			CURLOPT_POSTFIELDS => 'CURLOPT_POSTFIELDS',
			CURLOPT_POSTQUOTE => 'CURLOPT_POSTQUOTE',
			CURLOPT_POSTREDIR => 'CURLOPT_POSTREDIR',
			CURLOPT_PRIVATE => 'CURLOPT_PRIVATE',
			CURLOPT_PROGRESSFUNCTION => 'CURLOPT_PROGRESSFUNCTION',
			CURLOPT_PROTOCOLS => 'CURLOPT_PROTOCOLS',
			CURLOPT_PROXY => 'CURLOPT_PROXY',
			CURLOPT_PROXYAUTH => 'CURLOPT_PROXYAUTH',
			CURLOPT_PROXYPORT => 'CURLOPT_PROXYPORT',
			CURLOPT_PROXYTYPE => 'CURLOPT_PROXYTYPE',
			CURLOPT_PROXYUSERPWD => 'CURLOPT_PROXYUSERPWD',
			CURLOPT_PUT => 'CURLOPT_PUT',
			CURLOPT_QUOTE => 'CURLOPT_QUOTE',
			CURLOPT_RANDOM_FILE => 'CURLOPT_RANDOM_FILE',
			CURLOPT_RANGE => 'CURLOPT_RANGE',
			CURLOPT_READDATA => 'CURLOPT_READDATA',
			CURLOPT_READFUNCTION => 'CURLOPT_READFUNCTION',
			CURLOPT_REDIR_PROTOCOLS => 'CURLOPT_REDIR_PROTOCOLS',
			CURLOPT_REFERER => 'CURLOPT_REFERER',
			CURLOPT_RESUME_FROM => 'CURLOPT_RESUME_FROM',
			CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
			CURLOPT_SSH_AUTH_TYPES => 'CURLOPT_SSH_AUTH_TYPES',
			CURLOPT_SSH_HOST_PUBLIC_KEY_MD5 => 'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5',
			CURLOPT_SSH_PRIVATE_KEYFILE => 'CURLOPT_SSH_PRIVATE_KEYFILE',
			CURLOPT_SSH_PUBLIC_KEYFILE => 'CURLOPT_SSH_PUBLIC_KEYFILE',
			CURLOPT_SSLCERT => 'CURLOPT_SSLCERT',
			CURLOPT_SSLCERTPASSWD => 'CURLOPT_SSLCERTPASSWD',
			CURLOPT_SSLCERTTYPE => 'CURLOPT_SSLCERTTYPE',
			CURLOPT_SSLENGINE => 'CURLOPT_SSLENGINE',
			CURLOPT_SSLENGINE_DEFAULT => 'CURLOPT_SSLENGINE_DEFAULT',
			CURLOPT_SSLKEY => 'CURLOPT_SSLKEY',
			CURLOPT_SSLKEYPASSWD => 'CURLOPT_SSLKEYPASSWD',
			CURLOPT_SSLKEYTYPE => 'CURLOPT_SSLKEYTYPE',
			CURLOPT_SSLVERSION => 'CURLOPT_SSLVERSION',
			CURLOPT_SSL_CIPHER_LIST => 'CURLOPT_SSL_CIPHER_LIST',
			CURLOPT_SSL_VERIFYHOST => 'CURLOPT_SSL_VERIFYHOST',
			CURLOPT_SSL_VERIFYPEER => 'CURLOPT_SSL_VERIFYPEER',
			CURLOPT_STDERR => 'CURLOPT_STDERR',
			CURLOPT_TCP_NODELAY => 'CURLOPT_TCP_NODELAY',
			CURLOPT_TIMECONDITION => 'CURLOPT_TIMECONDITION',
			CURLOPT_TIMEOUT => 'CURLOPT_TIMEOUT',
			CURLOPT_TIMEOUT_MS => 'CURLOPT_TIMEOUT_MS',
			CURLOPT_TIMEVALUE => 'CURLOPT_TIMEVALUE',
			CURLOPT_TRANSFERTEXT => 'CURLOPT_TRANSFERTEXT',
			CURLOPT_UNRESTRICTED_AUTH => 'CURLOPT_UNRESTRICTED_AUTH',
			CURLOPT_UPLOAD => 'CURLOPT_UPLOAD',
			CURLOPT_URL => 'CURLOPT_URL',
			CURLOPT_USERAGENT => 'CURLOPT_USERAGENT',
			CURLOPT_USERPWD => 'CURLOPT_USERPWD',
			CURLOPT_VERBOSE => 'CURLOPT_VERBOSE',
			CURLOPT_WRITEFUNCTION => 'CURLOPT_WRITEFUNCTION',
			CURLOPT_WRITEHEADER => 'CURLOPT_WRITEHEADER',
		);
		
	}
	
}
