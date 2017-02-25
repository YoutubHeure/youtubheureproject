<!DOCTYPE html>
<html>
<head>
	<title>Kamevo - Bienvenue</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="../frameworks/w3.css">
    <link rel="stylesheet" href="../frameworks/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="../frameworks/jquery.min.js"></script>
</head>
<body>
<?php require("menu.php") ?>
	<div class="container-left">
		<img src="coding.png" width="100%" alt='logo' class="wannado">
	</div><div class="container-right">
	<h1 class="main-title">Bienvenue sur Kamevo.com</h1>
	<h4>Pour continuer votre navigation , veuillez vous créer un compte :</h4><br />
      <form class="content">
      <div class="form-row">
        <input type="text" class="input-text" id="input" placeholder="Pseudo" />
         <label class="label-helper" for="input">Pseudo</label>
        </div><div class="form-row" >
         <input type="text" class="input-text" id="input2" placeholder="Prénom" />
        <label class="label-helper" for="input2">Prénom</label>
      </div><div class="form-row" >
        <input type="date" class="input-text" id="input-date" value="2000-06-08" />
         <label class="label-helper" for="input-date">Date de naissance</label>
        </div><div class="form-row" >
         <input type="password" class="input-text" id="input3" placeholder="Mot de passe" />
        <label class="label-helper" for="input3">Mot de passe</label>
      </div><div class="form-row" >
       <input type="password" class="input-text" id="input4" placeholder="Confirmation" />
        <label class="label-helper" for="input3">Confirmation de votre Mot de passe</label>
        </div><div class="form-row" >
         <input type="email" class="input-text" id="input5" placeholder="Adresse E-mail" />
        <label class="label-helper" for="input3">Adresse E-Mail</label>
      </div><div class="form-row" >
         <input type="email" class="input-text" id="input6" placeholder="Confirmation" />
        <label class="label-helper" for="input3">Confirmation de votre Adresse E-Mail</label>
      </div>
       <input type="submit" name="submit" class="leto" value="S'inscire">
      <div class="checkall">
      	<input type="checkbox" name="checked" id="checks" class="checks">
        J'ai lu et j'accepte les <a href="#">conditions d'utilisations</a> de Kamevo.com .
      </div>
      </form> <!-- end of the content -->
	</div>
</body>
</html>