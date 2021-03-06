<?php session_start(); 
	if(!isset($_SESSION['ID'])){
		header('location:index.php');
	}else{
		require('php/users.class.php');
		require('php/post.class.php');
		$user = new users($_SESSION['ID']); //initialize the user objet 
	}

	?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Kamevo - Publier</title>
	<link rel="stylesheet" href="css/chat.css">
      <link rel="stylesheet" href="css/notes.css">
	<link rel="stylesheet" href="post/css/style.max.css">
	<link rel="stylesheet" href="post/css/post.css">
	<link rel="stylesheet" href="post/css/popup.css">
	<link rel="stylesheet" href="css/menu_co.css">
    <link rel="stylesheet" href="frameworks/w3.css">
    <link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
    <link rel="stylesheet" href="frameworks/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.css">
    <script type="text/javascript" src="frameworks/jquery.min.js"></script>
</head>
<body>
	<?php require("menu_co.php"); //show menu?>
	<div class="container">
		<?php require("disp_post_content.php"); //show new post page content ?>
	</div>
<script src="js/explore.js"></script>
</body>
</html>