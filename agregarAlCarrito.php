<?php
if(!isset($_POST["codigo"])) return;
$codigo = $_POST["codigo"];
include_once "base_de_datos.php";
$sentencia = $base_de_datos->prepare("SELECT * FROM productos WHERE codigo = ? LIMIT 1;");
$sentencia->execute([$codigo]);
$producto = $sentencia->fetch(PDO::FETCH_OBJ);
if($producto){
	if($producto->existencia < 1){
		header("Location: ./vender.php?status=5");
		exit;
	}
	session_start();
	$indice = false;
	for ($i=0; $i < count($_SESSION["carrito"]); $i++) { 
		if($_SESSION["carrito"][$i]->codigo === $codigo){
			$indice = $i;
			break;
		}
	}
	if($indice === FALSE){
		$producto->cantidad = 1;
		$producto->total = $producto->precioVenta;
		array_push($_SESSION["carrito"], $producto);
	}else{
		$_SESSION["carrito"][$indice]->cantidad++;
		$_SESSION["carrito"][$indice]->total = $_SESSION["carrito"][$indice]->cantidad * $_SESSION["carrito"][$indice]->precioVenta;
	}
	header("Location: ./vender.php");
}else header("Location: ./vender.php?status=4");
?>