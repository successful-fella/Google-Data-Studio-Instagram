<?php

	$username = "successful_fella";
	$url = "https://www.instagram.com/$username/?__a=1";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$headers = [
	    ':authority: 0',
	    'X-Apple-Store-Front: 143444,12',
	    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	    'Accept-Encoding: gzip, deflate',
	    'Accept-Language: en-US,en;q=0.5',
	    'Cache-Control: no-cache',
	    'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
	    'Host: www.example.com',
	    'Referer: http://www.example.com/index.php', //Your referrer address
	    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
	    'X-MicrosoftAjax: Delta=true'
	];

	// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec($ch);

	curl_close($ch);

	print  $server_output;