<?php
include('header.php'); //including the header
//require('php/users.class.php');
$resp = users::waitForInput($_POST);
?>
<div class="container">

			<div class="block">
			<div class="block-title">
			
			   <h2 class="block-name"><strong>Connexion</strong></h2>
			  
			</div>
			<p class="block-bio">Utilisez cette page pour vous connecter à votre espace membre et ainsi profiter de nombreux avantages.<br /><br />

			<i>Pas encore membre du site? C'est <a href="inscription.php">par-ici</a>!</i></p>
			<div class="line-separator6"></div>
			<div class='formco'><form method='POST' action="">
				<label for="pseudo">Pseudo:</label> <input type="text" name="pseudo"><br /><br />
				<label for="password">Mot de passe:</label><input type="password" name="password"><br /><br />
				<input type="submit" name="go" value="Connexion">
			</form>
			<div class="error"><?=$resp; ?></div>
			</div>
			  
			<div class="line-separator6"></div>
			<br/>
			   
			</div>
			</div>

			</div>

<?php
require('footer.php');