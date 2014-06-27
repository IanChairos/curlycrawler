<?php
namespace CurlyCrawler;
//set_time_limit(0);
require_once 'RequestUrlSet.php';
require_once 'CurlRequestFactory.php';
require_once 'StringSearchEngine.php';
require_once 'NestedXPathSearchEngine.php';
require_once 'HtmlToSimpleXml.php';

//ini_set('display_errors','on');

//$source = 'temp/seznam.cz.html';
//$sd = '<!DOCTYPE html><html><head></head><body></body></html>';
$resultsFile = 'temp/found.matches.html';
//$source = 'temp/1b67f3594b99ff9a9311a557270619b5.html';
$source = __DIR__.'/temp/16-f284077597772eb01ffbf18f2215bfdc.html';
$data = file_get_contents($source);
echo strlen($data);

$userPath = "//a[@rel='profile-view']";
//$interestsPath = "spans";
$interestsPath = "div[@class='uinf_ints']";
$interestsPath2 = "span[@class='ints_i_tx' and text()='Drum and bass']";

$searchEngine = new NestedXPathSearchEngine();
	$searchEngine->addSearchPattern($userPath);
	$searchEngine->addSearchPattern($interestsPath);
	$searchEngine->addSearchPattern($interestsPath2);
	$searchEngine->beforeSearchHook( new HtmlToSimpleXml() );
	
	
	$results = $searchEngine->search($data);

echo '<pre>';
print_r($results);
echo '</pre>';
exit;





exit;


$dom = new \DOMDocument();
//$dom->loadHTML($sd);
//$xml = $dom->saveHTML();
@$dom->loadHTMLFile($source);

$xmlData = $dom->saveXML();

$xml = new \SimpleXMLElement($xmlData);

$userPath = "//span[@class=' female']/a[@rel='profile-view']";
//$interestsPath = "div[@class='uinf_ints']//span";
//$interestsPath = "div[@class='uinf_ints']//span[@class='ints_i_tx' and text()='Drum and bass']";
$interestsPath = "div[@class='uinf_ints']/span/span[@class='ints_i_tx' and text()='Drum and bass']";
$profilePhotoPath = "img";
$userNamePath = "span[@class='c_name_tx']/text()";
$profileUrlPath = "@href";

$se = new XPathSearchEngine();

$se->addSearchPattern($userPath);
$se->addSearchPattern($interestsPath);

$se->setSearchMode(SearchEngine::MODE_FIND_ALL);

$res = $se->search($xml);

$users = array();

foreach( $res as $user ) {
	$img = current($user->xpath($profilePhotoPath));
//	echo $img.'';
//	var_dump($img->asXML());
	$name = current($user->xpath($userNamePath));
//	var_dump((string)$name);
	/* @var $href \SimpleXMLElement */
	$href =  current($user->xpath($profileUrlPath));
//	var_dump((string)$href);
	
	$userInfo = new \stdClass;
	$userInfo->img = $img->asXML();
	$url = (string)$href;
	$userInfo->href = $url;
	$userInfo->name = (string)$name;
	$users[] = $userInfo;
	
//	@$currentResults = file_get_contents($resultsFile);
//	$html = '<div><a href="'.$userInfo->href.'" target="_blank"><h3>'.$userInfo->name.'</h3>'.$userInfo->img.'</a></div>';
//	$results = $html . $currentResults;
//	file_put_contents($resultsFile,$results);
//	echo '<hr/>';
}


print_r($users);

//var_dump($res); echo '<hr/>';
exit;



$userElements = $xml->xpath($userPath);

$matches = array();
foreach( $userElements as $userElement ){
	/* @var $userElement \SimpleXMLElement */
	
//	var_dump($userElement); echo '<hr/>';
	$interests = $userElement->xpath($interestsPath);
	var_dump($interests); echo '<hr/>';
	
//	foreach( $interests as $interestElement ){
//		$text = (string)$interestElement;
//		if( $text == 'Drum and bass' ){
//			$matches[] = $userElement;
//		}
//	}
//	$matches = $interests;
}

echo '<pre>';
//print_r($dom);
//print_r($xml);
//var_dump($xmlData);
//var_dump($xml);
//print_r($xquery);
print_r($matches);
//var_dump($matches);
echo '</pre>';








exit;
//$baseUrl = 'http://www.seznam.cz';
//$baseUrl = 'http://eu1.badoo.com/search/';
$baseUrl = 'http://badoo.com/search/';
$params = array();
for( $i=1; $i<2; $i++ ){
	$params['page'][] = $i;
}

//$params = array(
//	'name' => array( 'jirka', 'pepa' ),
//	'pass' => array( 'a', 'b', 'c' )
//	'passa' => array(),
//	'pass' => array()
//);

//$req = RequestUrlSetFactory::createWithParams($baseUrl, $params);
//$req = RequestUrlSetFactory::create( array($baseUrl,'http://localhost') );
$req = RequestUrlSetFactory::create( array($baseUrl) );


// prihlaseni
$user = 'stimpys@seznam.cz';
$pass = 'jikokol';
$jarName = 'cookies.txt';
//$jar = fopen($jarName,'w+');

$h = curl_init('http://badoo.com/signin/');
curl_setopt($h, CURLOPT_COOKIEJAR, $jarName);
curl_setopt ($h, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($h, CURLOPT_FOLLOWLOCATION, true);

// TODO pridat stahovani [rt]
$post_array = array('rt' => '8ea96b', 'email' => $user, 'password' => $pass, 'submit' => 'post');
curl_setopt($h, CURLOPT_POSTFIELDS, $post_array);
 
$d = curl_exec($h);

//echo ($d); exit;
//
//$pos = strpos($d,'<meta http-equiv="Refresh" content="0; url=');
//var_dump( $pos); exit;
//
//$hiddenUrl = '';
//while( $d[$pos] !== '"' ) {
//	$hiddenUrl .= $d[$pos];
//	$pos++;
//}
//
//echo " $hiddenUrl ";
//	
//$h = curl_init($hiddenUrl);
//curl_setopt($h, CURLOPT_COOKIEJAR, $jar);
//curl_setopt ($h, CURLOPT_RETURNTRANSFER, true);
//$d = curl_exec($h);
//
//
//echo $d;
//exit;



//$urls = array();

//for( $i=1; $i<50; $i++ ){
//	$urls[] = 'http://badoo.com/dating/?to_custom=randit&age_f=18&age_t=30&rt=b2fa1c&location_id=0_0_0&location=&is_extended=&pos=custom'
//	$urls[] = 'http://badoo.com/dating/girls/page-'.$i.'/age-18-30/';
//}
//$req = RequestUrlSet::create( $urls );

	

echo '<pre>';

print_r( $req->getUrls() );

$rf = new CurlRequestFactory();


$se = new StringSearchEngine();
//$sparams = array( ISearchEngine::PARAM_SEARCH => 'Copyright' );
$sparams = array(
	ISearchEngine::PARAM_SEARCH => array('Jun Kie'),
//	ISearchEngine::PARAM_SEARCH => array('registraci za sebou'),
//	ISearchEngine::PARAM_SEARCH => array('Drum and bass','DnB','Drum\'n\'Bass'),
);
print_r( $sparams );


//print_r($data);


//$responses = ;
//
//$headers = array(
//	'Host'=> 'eu1.badoo.com',
//	'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',
//	'Cookie' => 'ez=1.69; statjb377260=h%3A328d57ba%2Cs%3A7e8636fe%2Cu%3Ab377260; statfb377260=fp%3A22686f73%2Cs%3A48e3fe1e%2Cu%3Ab377260%2Cci%3Ab377260; __utma=207185671.1732508065.1348074403.1348074403.1348077358.2; __utmc=207185671; __utmz=207185671.1348074403.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); aid=188183136; email=stimpys%40seznam.cz; sads1=1echCDrOg7PH2kSEB0taLAVi_J.s3YZTy; s1=4ILhsc4PsYDAcTgXOtKARhER3ZsfjyMpS; __utma=208253569.2141713601.1355839978.1360701706.1360714116.164; __utmb=208253569.8.10.1360714116; __utmc=208253569; __utmz=208253569.1357857827.103.4.utmcsr=badoo.com|utmccn=(referral)|utmcmd=referral|utmcct=/search/; __utmv=208253569.|1=Gender=M=1^2=Sexuality=Straight=1^3=Age=24=1^4=Registration%20City=Prague=1^5=Relationship%20Status=Single=1^6=Interested%20In=Dating_Women=1^7=Date%20registered=12%2F07%2F2010=1^8=Verified%20status=No=1^9=Is%20web%20user=Yes=1^10=Is%20mobile%20app%20user=No=1^11=Is%20mobile%20web%20user=No=1^12=Date%20of%20last%20SPP%20Renewal=09%2F15%2F2012=1^13=SPP=No=1^14=Number%20of%20purchase%20transactions=Yes=1^15=Has%20uploaded%20a%20photo=Yes=1^16=Average%20photo%20rating=7.00450=1^17=Partner%20ID=1=1^18=Has%20user%20ID=188183136=1^19=Number%20of%20unique%20friends%20ever%20added=0=1^20=Number%20of%20mutual%20attractions%20to%20date=0=1^21=Email%20subscription%20active=No=1^22=Credit%20Balance=0=1^23=Has%20had%20Trial%20SPP%20ever=No=1; wpr=1'
//);

foreach( $req->getUrls() as $url ) {
	echo 'URL: '.$url;
//	$data = $rf->create($url)->execute( array( CURLOPT_HTTPHEADER => $headers, CURLOPT_COOKIEFILE => $jarName ) );
	$data = $rf->create($url)->execute( array(
//				CURLOPT_REFERER => 'http://eu1.badoo.com/search/',
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_CONNECTTIMEOUT => 20,
				CURLOPT_FOLLOWLOCATION => TRUE,
//				CURLOPT_HEADER => TRUE,
				CURLOPT_COOKIEFILE => $jarName ) );
//	echo '<iframe>'.$data.'</iframe>';
//	echo '<pre>'.$data.'</pre>';
//	echo '<script type="text/javascript> console.log('.$data.');</script>';
//	echo $data;
	
	file_put_contents(md5($url),$data);
	
//	echo '<div style="display:none">'.$data.'</div>';
	var_dump( $se->search($data, $sparams) );
	
	echo '<br></hr>';
//	flush();
//	usleep(500000);
	sleep(1);
//	$responses[] = $rf->create($url)->execute();
}


echo '</pre>';
