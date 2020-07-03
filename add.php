<?php 

	if($_SERVER['REQUEST_METHOD'] === "POST") {
		$username = $_POST['instagram'];
		$id_json = file_get_contents(__DIR__.'/accounts.json');
		$id_arr = json_decode($id_json, true);
		// $insta_resp = json_decode(file_get_contents("https://www.instagram.com/$username/?__a=1"));
		// $id = $insta_resp->graphql->user->id;
		// if($insta_resp->graphql->user->is_private) {
		// 	echo "Account private!"; die;
		// }
		$username = '@'.$username;
		foreach ($id_arr as $id_old => $username_old) {
			if($username_old == $username) {
				echo "Username already exists"; die;
			}
		}
		$id_arr[$_POST['instagram_id']] = $username;
		file_put_contents('accounts.json', json_encode($id_arr));
		echo "User added to analytics";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Instagram Account</title>
</head>
<body>
	<form method="POST">
		<input type="text" name="instagram" id="username" placeholder="Instagram Username"> <button type="button" onclick="window.open('https://www.instagram.com/'+document.getElementById('username').value+'/?__a=1', '_blank')">Get ID</button><br>
		<input type="text" name="instagram_id" placeholder="Instagram ID"><br>
		<button type="submit">Add</button>
	</form>
	<p>Update Lastest Data: </p><button type="button" onclick="this.innerHTML='Please wait...';this.disabled=true;window.location.href='update.php'">Update</button>
</body>
</html>