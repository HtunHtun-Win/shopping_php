<?php

	if (empty($_SESSION['token'])) {
		$token = md5(uniqid(mt_rand(),true));
		$_SESSION['token'] = $token;
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if ($_SESSION['token'] != $_POST['token']) {
			echo "invalid csrf";
			session_destroy();
			header("location: index.php");
		}
	}

	function escape($str){
		return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
	}
?>