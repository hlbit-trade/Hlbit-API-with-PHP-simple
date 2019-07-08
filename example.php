<?php

function auth($method, array $req = array()) {
	// API settings
	$key = ''; // your API-key
	$secret = ''; // your Secret-key
	$endpoint  = 'https://hlbit.trade/api/tapi/';

	$req['method'] = $method;

	$post_data = http_build_query($req, '', '&');

	$headers = array(
		'appkey: '.$key,
		'secret: '.$secret,
	);

	// our curl handle (initialize if required)
	static $ch = null;
	if (is_null($ch)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36
		'.php_uname('s').'; PHP/'.phpversion().')');
	}
	curl_setopt($ch, CURLOPT_URL, $endpoint);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	// run the query
	$res = curl_exec($ch);
	
	if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
	
	$dec = json_decode($res, true);
	
	if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists: '.$res);

	curl_close($ch);
	$ch = null;
	return $dec;
}
// Get Info Example
$result = auth('getInfo');

// Get Balance Example
// $result = auth('getBalance',['type'=>'fiat']);

// List Order Example
// $result = auth('listOrder',['pair'=>'btcusd']);

print_r($result);

?>
