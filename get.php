<?php
/**
* Curl send get request, support HTTPS protocol
* @param string $url The request url
* @param string $refer The request refer
* @param int $timeout The timeout seconds
* @return mixed
*/
function getRequest($url, $refer = "", $timeout = 10)
{
    $ssl = stripos($url,'https://') === 0 ? true : false;
    $curlObj = curl_init();
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_AUTOREFERER => 1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)',
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_HTTPHEADER => ['Expect:'],
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    ];
    if ($refer) {
        $options[CURLOPT_REFERER] = $refer;
    }
    if ($ssl) {
        //support https
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
    }
    curl_setopt_array($curlObj, $options);
    $returnData = curl_exec($curlObj);
    if (curl_errno($curlObj)) {
        //error message
        $returnData = curl_error($curlObj);
    }
    curl_close($curlObj);
    return $returnData;
}

/**
* Curl send post request, support HTTPS protocol
* @param string $url The request url
* @param array $data The post data
* @param string $refer The request refer
* @param int $timeout The timeout seconds
* @param array $header The other request header
* @return mixed
*/
function postRequest($url, $data, $refer = "", $timeout = 10, $header = [])
{
    $curlObj = curl_init();
    $ssl = stripos($url,'https://') === 0 ? true : false;
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_AUTOREFERER => 1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)',
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_HTTPHEADER => ['Expect:'],
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_REFERER => $refer
    ];
    if (!empty($header)) {
        $options[CURLOPT_HTTPHEADER] = $header;
    }
    if ($refer) {
        $options[CURLOPT_REFERER] = $refer;
    }
    if ($ssl) {
        //support https
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
    }
    curl_setopt_array($curlObj, $options);
    $returnData = curl_exec($curlObj);
    if (curl_errno($curlObj)) {
        //error message
        $returnData = curl_error($curlObj);
    }
    curl_close($curlObj);
    return $returnData;
}
?>

<?php
// An indexed array "xmin":55.33186912536621,"ymin":25.226411819458008,"xmax":55.41220664978027,"ymax":25.277910232543945

 $url = "https://opensky-network.org/api/states/all?time=" . mktime() . "&lomin=55.33186912536621&lamin=25.226411819458008&lomax=55.41220664978027&lamax=25.277910232543945"; // path to your JSON file

/*  while(!$data)
{
	$data = file_get_contents($url);	
}
 */
 
 $data = getRequest($url);
//echo $data;
  // put the contents of the file into a variable
//$data = file_get_contents($url);



//echo $data;
$characters = json_decode($data); // decode the JSON feed

//$request = remote::request($url);

//  if (!empty($request->content)) {
//    $data = json_decode($request->content); 
//  }
$tsstring = gmdate('D, d M Y H:i:s ', mktime()) . 'GMT';
header("Last-Modified: $tsstring");
header('Content-Type: application/text');
header('Content-Type: application/json;charset=UTF-8');
  $flights = $characters->states;
  $str_flights = " ";
 
  foreach  ($flights as $flight)
 {
	 
	$str_flights = $str_flights . "{";
	$str_flights = $str_flights . '"time_":"' 			. 	(string)date(DATE_ISO8601,$characters->time) 	. '",';
	$str_flights = $str_flights . '"icao24":"'			.  	(string)$flight[0]  		. '",';
	$str_flights = $str_flights . '"callsign":"'			.	(string)$flight[1] . '",';
	$str_flights = $str_flights . '"origin_country":"'	.	(string)$flight[2] . '",';
	$str_flights = $str_flights . '"time_position":"'	.	(string)date(DATE_ISO8601,$flight[3] ) . '",';
	$str_flights = $str_flights . '"last_contact":"'	.	(string)date(DATE_ISO8601,$flight[4] ) . '",';
	$str_flights = $str_flights . '"longitude":"'		.	(string)$flight[5] . '0",';
	$str_flights = $str_flights . '"latitude":"'		.	(string)$flight[6] . '0",';
	$str_flights = $str_flights . '"baro_altitude":"0'	.	(string)$flight[7] . '",';
	$str_flights = $str_flights . '"on_ground":"'		.	(string)$flight[8] . '",';
	$str_flights = $str_flights . '"velocity":"0'		.	(string)$flight[9] . '",';
	$str_flights = $str_flights . '"true_track":"0'		.	(string)$flight[10] . '",';
	$str_flights = $str_flights . '"vertical_rate":"'	.	(string)$flight[11] . '",';
	$str_flights = $str_flights . '"sensors":"'			.	(string)$flight[12] . '",';
	$str_flights = $str_flights . '"geo_altitude":"0'	.	(string)$flight[13] . '",';
	$str_flights = $str_flights . '"squawk":"'			.	(string)$flight[14] . '",';
	$str_flights = $str_flights . '"spi":"'				.	(string)$flight[15] . '",';
	$str_flights = $str_flights . '"position_source":"'	.	(string)$flight[16] . '"';
	$str_flights = $str_flights . '},';

 }

 echo  '[' . substr ($str_flights,0,strlen($str_flights)-1) . ']' ;
?>