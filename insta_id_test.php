<?php

	$username = "successful_fella";
	$url = "https://www.instagram.com/$username/?__a=1";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$headers = [
	    'authority: www.instagram.com',
	    'method: GET',
	    "path: /$username/?__a=1",
	    'scheme: https',
	    'accept: application/json, text/plain, */*',
		// 'accept-encoding: gzip, deflate, br',
	    'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7',
	    'origin: http://localhost',
		'referer: http://localhost/instagram',
		'sec-fetch-dest: empty',
		'sec-fetch-mode: cors',
		'sec-fetch-site: cross-site',
		'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36'
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec($ch);

	curl_close($ch);

	print  $server_output;