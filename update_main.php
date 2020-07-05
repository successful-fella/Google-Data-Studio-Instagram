<?php

	require __DIR__ . '/vendor/autoload.php';

	$instagram_graph_url = "https://www.instagram.com/graphql/query/";

	function addRow($id, $username, $followers, $followings, $media, $last_post1, $last_post2, $last_post3) {
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
		    [$id, $username, $followers, $followings, $media, $last_post1, $last_post2, $last_post3, date('Y-m-d')]
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
		$query = '?query_hash=f2405b236d85e8296cf30347c9f08c2a&variables={"id":"'.$user_id.'","first":3}';
		$result = json_decode(file_get_contents($instagram_graph_url.$query));
		return $result;
	}

	function getIDsOld() {
		$id_json = file_get_contents(__DIR__.'/accounts.json');
		$id_arr = json_decode($id_json, true);
		return $id_arr;
	}

	function getIDs() {
		$client = new \Google_Client();
		$client->setApplicationName('My PHP App');
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		$json = file_get_contents(__DIR__.'/excelapi.json');
		$client->setAuthConfig(json_decode($json, true));
		$sheets = new \Google_Service_Sheets($client);
		$spreadsheetId = '1P3XyPtUcRPHKNVhLbrAcvJ-vK5O_3YVQz7a3SpIP0nA';
		$range = 'Usernames!B:C';
		$response = $sheets->spreadsheets_values->get($spreadsheetId, $range);
		$values = $response->getValues();
		array_shift($values);
		$wanted_arr = array();
		foreach ($values as $value) {
			$wanted_arr[$value[1]] = $value[0];
		}
		// print_r($wanted_arr);
		return $wanted_arr;
	}

	$ids = getIDs();

	foreach($ids as $id => $username) {
		$followers = getFollowers($id);
		$followings = getFollowings($id);
		$media = getMedia($id);
		$media_count = $media->data->user->edge_owner_to_timeline_media->count;
		$post_url = "https://www.instagram.com/p/";
		$last_post1 = $post_url . $media->data->user->edge_owner_to_timeline_media->edges[0]->node->shortcode;
		$last_post2 = $post_url . $media->data->user->edge_owner_to_timeline_media->edges[1]->node->shortcode;
		$last_post3 = $post_url . $media->data->user->edge_owner_to_timeline_media->edges[2]->node->shortcode;
		addRow($id, $username, $followers, $followings, $media_count, $last_post1, $last_post2, $last_post3);
	}

	echo "Updated";