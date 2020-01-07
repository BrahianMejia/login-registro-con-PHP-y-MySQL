<?php

	session_start();

	if(isset($_SESSION['usuario']))
	{
		header('Location: index.php');
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
		$password = $_POST['password'];
		$password = hash('sha512', $password);

		$errores = '';

		try
		{
			$conexion = new PDO('mysql:host=localhost;dbname=curso_login', 'root', '');
		}
		catch(PDOException $e)
		{
			echo "ERROR: " . $e->getMessage();
		}

		$comando = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND pass = :password');

		$comando->execute(array(':usuario' => $usuario, ':password' => $password));

		$resultado = $comando->fetch();

		if($resultado)
		{
			$_SESSION['usuario'] = $usuario;
			header('Location: index.php');
		}
		else
		{
			$errores .= '<li>Datos incorrectos</li>';
		}
	}

	require('views/login.view.php');

?>