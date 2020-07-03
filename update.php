<?php

	require __DIR__ . '/vendor/autoload.php';

	$instagram_graph_url = "https://www.instagram.com/graphql/query/";

	function addRow($username, $followers, $followings, $media) {
		$client = new \Google_Client();
		$client->setApplicationName('My PHP App');
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		$json = file_get_contents(__DIR__.'/excelapi.json');
		$client->setAuthConfig(json_decode($json, true));
		$sheets = new \Google_Service_Sheets($client);
		$spreadsheetId = '1P3XyPtUcRPHKNVhLbrAcvJ-vK5O_3YVQz7a3SpIP0nA';
		$range = 'A2:H';
		$values = [
		    [$username, $followers, $followings, $media, date('Y-m-d')]
		];
		$body = new Google_Service_Sheets_ValueRange([
		    'values' => $values
		]);
		$params = [
		    'valueInputOption' => "USER_ENTERED"
		];
		$result = $sheets->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
	}

	function getFollowers($user_id) {
		global $instagram_graph_url;
		$query = '?query_hash=37479f2b8209594dde7facb0d904896a&variables={"id":"'.$user_id.'","first":24}';
		$result = json_decode(file_get_contents($instagram_graph_url.$query));
		return $result->data->user->edge_followed_by->count;
	}

	function getFollowings($user_id) {
		global $instagram_graph_url;
		$query = '?query_hash=58712303d941c6855d4e888c5f0cd22f&variables={"id":"'.$user_id.'","first":24}';
		$result = json_decode(file_get_contents($instagram_graph_url.$query));
		return $result->data->user->edge_follow->count;
	}

	function getMedia($user_id) {
		global $instagram_graph_url;
		$query = '?query_hash=f2405b236d85e8296cf30347c9f08c2a&variables={"id":"'.$user_id.'","first":24}';
		$result = json_decode(file_get_contents($instagram_graph_url.$query));
		return $result->data->user->edge_owner_to_timeline_media->count;
	}

	function getIDs() {
		$id_json = file_get_contents(__DIR__.'/accounts.json');
		$id_arr = json_decode($id_json, true);
		return $id_arr;
	}

	$ids = getIDs();

	foreach($ids as $id => $username) {
		$followers = getFollowers($id);
		$followings = getFollowings($id);
		$media = getMedia($id);
		addRow($username, $followers, $followings, $media);
	}

	echo "Updated";