<?php

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if ($_SESSION['token'] != $_POST['token']) {
			echo "invalid csrf";
		}else{
			unset($_SESSION['token']);
		}
	}

	if (empty($_SESSION['token'])) {
		$token = md5(uniqid(mt_rand(),true));
		$_SESSION['token'] = $token;
	}

	function escape($str){
		return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
	}
?>