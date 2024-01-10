<?php
	require "config/config.php";
	$id = $_GET['id'];
	$sql = "DELETE FROM categories WHERE id=$id";
	$pdostatement = $pdo->prepare($sql);
	$pdostatement->execute();
	header('Location: category.php');
?>