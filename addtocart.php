<?php 
	session_start();
	require 'config/config.php';

	if($_GET['id']){
		$id = $_GET['id'];
		$curQty = $_SESSION['cart']['id'.$id]+1;
		//check qty
		$sql = "SELECT * FROM products WHERE id=$id";
		$pdostatement = $pdo->prepare($sql);
		$pdostatement->execute();
		$product = $pdostatement->fetch();
		//
		if($curQty>$product['quantity']) {
			echo "<script>alert('Not Enought Quantity!');window.location.href='index.php'</script>";
		}else{
			if(isset($_SESSION['cart']['id'.$id])) {
				$_SESSION['cart']['id'.$id] += 1;
			}else{
				$_SESSION['cart']['id'.$id] = 1;
			}
			header("Location: index.php");
		}
	}elseif($_POST){
		$id = $_POST['id'];
		$qty = $_POST['qty'];
		$curQty = $_SESSION['cart']['id'.$id]+$qty;
		//check qty
		$sql = "SELECT * FROM products WHERE id=$id";
		$pdostatement = $pdo->prepare($sql);
		$pdostatement->execute();
		$product = $pdostatement->fetch();
		//
		if($curQty>$product['quantity']) {
			echo "<script>alert('Not Enought Quantity!');window.location.href='product_detail.php?id=$id'</script>";
		}else{
			if(isset($_SESSION['cart']['id'.$id])) {
				$_SESSION['cart']['id'.$id] += $qty;
			}else{
				$_SESSION['cart']['id'.$id] = $qty;
			}
			header("Location: product_detail.php?id=$id");
		}
	}
?>