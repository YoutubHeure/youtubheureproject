
<?php session_start(); 
  if(!isset($_SESSION['ID'])){
      header('location:index.php?return=settingsErrorAccess');
  }else{
    require('php/users.class.php');
    require('php/settings.class.php');
    $user = new users($_SESSION['ID']); //initialize the user objet 
  }

  ?>
<html>
<head>
 <meta charset="UTF-8">
  <link rel="stylesheet" href="css/menu_co.css">
   <link rel="stylesheet" href="css/popup.css">
  <link rel="stylesheet" href="frameworks/w3.css">
   <link rel="stylesheet" href="settings/css/style.max.css">
  <link rel="stylesheet" href="settings/css/settings.css">
   <link rel="stylesheet" href="frameworks/font-awesome/css/font-awesome.min.css">
  <script type="text/javascript" src="frameworks/jquery.min.js"></script>
 <title>Kamevo - Paramètres</title>
</head>
<body>
 <?php require("menu_co.php"); ?>
  <div class="container">
   <?php require("settingsContent.php"); ?>
  </div>
  <script src="js/explore.js"></script>
</body>
</html>