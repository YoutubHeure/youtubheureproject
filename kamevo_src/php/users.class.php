<?php

class users{

	
	public $pseudo;	
	public $idUser;
	public $points;
	public $grade;
	public $mail;
	public $name;
	public $abonnes;
	public $abonnements;
	public $lastvisit;
	public $avatar;
	public $banner;
	public $bio;
	public $nbNotifs = ''; //define as a string to be empty in case of non-notif
	public $nbNotifsMp = ''; //define as a string to be empty in case of non-notif


	public function __construct($ID){

		$this->initialize($ID);
	}

	public function getAvatar($ID){

		include('co_pdo.php');
		 $getUsr = $bdd->prepare('SELECT * FROM users WHERE ID = ?');
		  $getUsr->execute(array($ID));
		 $usr = $getUsr->fetch();
		return $usr['banner'];

	}

	public function getNbNotifs(){

		include('co_pdo.php');

		$req = $bdd->prepare('SELECT * FROM notifs WHERE destinataire = ? AND ack = "unread" ');
		$req->execute(array($this->idUser));
		$nb = $req->rowCount();

		$req->closeCursor();

		if($nb == 0){

			$this->nbNotifs = '';
			return '';

		}else{

			$this->nbNotifs = $nb;
			return $nb;
		}

	}

	public function getNbNotifsMp(){

		include('co_pdo.php');

		$req = $bdd->prepare('SELECT * FROM messages WHERE messTo = ? AND ack = "unread" ');
		$req->execute(array($this->idUser));
		$nb = $req->rowCount();

		$req->closeCursor();

		if($nb == 0){

			$this->nbNotifsMp = '';
			return '';

		}else{

			$this->nbNotifsMp = $nb;
			return $nb;
		}

	}


	public function printNotifs(){

		include('co_pdo.php');

		$req = $bdd->prepare('SELECT * FROM notifs WHERE destinataire = ? AND ack = "unread" ORDER BY ID desc');
		$req->execute(array($this->idUser));
		$nb = $req->rowCount();

		

		if($nb == 0){ ?>

			  <div class="note-res">
			  	<img src="img/Ionic.png" alt="note-img" class="note-res-img" style="visibility:hidden;">
			  	 <div class="note-res-about">
				   <p class="nr-about"><strong><span class="note-res-name">Aucune</span> notification!</strong></p>
				 </div>
  			</div>
			

		<?php }else{ 

			while($noti = $req->fetch()){ ?>


			  <div class="note-res" id="<?=$noti['ID']; ?>">
			  	<img src="img/Ionic.png" alt="note-img" class="note-res-img">
			  	 <div class="note-res-about">
				   <p class="nr-about"><strong><span class="note-res-name"><?=$noti['message']; ?></span></strong><span id="<?=$noti['ID']; ?>" class="delNotif"> [Lu]</span></p>
				 </div>
 			 </div>

			<?php }


		 }

	}
	public function printNotifsMp(){

		include('co_pdo.php');

		echo "<script>function clearNotifMp(notifID){

			var idOfNotifMp = notifID; 		

	 		$.ajax({

       			url : 'php/notifMp.php', 
      			type : 'POST',
      			dataType : 'html',
       			data : 'mode=delete&id='+idOfNotifMp,
       		
       			 success : function(html_return, statut){ // code_html contient le HTML renvoyé

       			 	$('#mpid'+idOfNotifMp).fadeOut(500);
       			 	
       			 	if(html_return != 'ok'){

       			 		console.log('Cleared.');
       			 	}     			 		
           
       			},

       			 error : function(resultat, statut, erreur){

       			 		alert('Une erreur est survenue!');

       			}

    		});

		}</script>";

		$req = $bdd->prepare('SELECT * FROM messages WHERE messTo = ? AND ack = "unread" ORDER BY ID desc');
		$req->execute(array($this->idUser));
		$nb = $req->rowCount();

		

		if($nb == 0){ ?>

			  <div class="msg-res">
			     <div class="msg-res-about">
			     <p class="mr-about"><strong> Vous n'avez aucun nouveau message</strong></p>
			   </div>
			  </div>
			

		<?php }else{ 

			$cptUsersCollapsed = 0;
			$listUserCollasped = array();

			while($notiMp = $req->fetch()){ 

				$nbMpSameUser = 0;

				$testD = $bdd->prepare('SELECT * FROM messages WHERE messTo = ? AND ack = "unread" AND messFrom = ?ORDER BY ID desc');	
				$testD->execute(array($this->idUser, $notiMp['messFrom']));
				$nbMpSameUser = $testD->rowCount();
				$testD->closeCursor();

				if($nbMpSameUser > 1 && !in_array($notiMp['messFrom'], $listUserCollasped)){

					$listUserCollasped[$cptUsersCollapsed] = $notiMp['messFrom'];
					$cptUsersCollapsed++;

					?>

				<div class="msg-res" id="mpid<?=$notiMp['messFrom']; ?>" onclick="clearNotifsMp(<?=$notiMp['messFrom']; ?>)">
			   		 <img src="img/Ionic.png" alt="note-img" class="note-res-img">
			     	<div class="msg-res-about">
			     	<p class="mr-about"><strong> Vous avez <?=$nbMpSameUser; ?> messages non-lu de <br/> <span class="msg-res-name"><?=self::getPseudoByID($notiMp['messFrom']); ?></span></strong></p>
			  	 	</div>
			  	</div>

				<?php }elseif(!in_array($notiMp['messFrom'], $listUserCollasped)){

				?>

			  <div class="msg-res" id="mpid<?=$notiMp['messFrom']; ?>" onclick="clearNotifMp(<?=$notiMp['messFrom']; ?>)">
			    <img src="img/Ionic.png" alt="note-img" class="note-res-img">
			     <div class="msg-res-about">
			     <p class="mr-about"><strong> Vous avez un message non-lu de <br/> <span class="msg-res-name"><?=self::getPseudoByID($notiMp['messFrom']); ?></span></strong></p>
			   </div>
			  </div>

			<?php }

			}
		 }

		 $req->closeCursor();
	}

	static private function ifUserExist($pseudo){

			require('co_pdo.php');


			$userExist = 'non';

			$reqUExist = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
			$reqUExist->execute(array($pseudo));
			$value = $reqUExist->rowCount();
			if($value>0) $userExist='oui'; 

			return $userExist;


	}

	static private function ifMailExist($mail){

			require('co_pdo.php');


			$mailExist = 'non';

			$reqUExist = $bdd->prepare('SELECT * FROM users WHERE email = ?');
			$reqUExist->execute(array($mail));
			$value = $reqUExist->rowCount();
			if($value>0) $mailExist='oui'; 

			return $mailExist;


	}
    
    static public function getGrade($ID) {
        require('co_pdo.php');
        
        $reqGrade = $bdd->prepare('SELECT grade FROM users WHERE ID = ?');
        $reqGrade->execute(array($ID));
        return($reqGrade->fetch());
    }

	private function initialize($ID){

		include('co_pdo.php');
		$req = $bdd->prepare('SELECT * FROM users WHERE ID = ?');
		$req->execute(array($ID));
		$rep = $req->fetch();

		$this->idUser = $rep['ID'];
		$this->pseudo = $rep['pseudo'];
		$this->mail = $rep['email'];
		$this->name = $rep['Nom'];
		$this->grade = $rep['grade'];
		$this->lastvisit = $rep['lastCo'];
		$this->abonnes = $rep['abonnes'];
		$this->abonnements = $rep['abonnements'];
		$this->points = $rep['points'];
		$this->avatar = $rep['avatar'];
		$this->banner = $rep['banner'];
		$this->bio = $rep['bio'];

	}

	static public function checkuser($identifiant,$mdp, $isMail){

		require('co_pdo.php');

		unset($_SESSION['ID']); //cleaning the session

		if ($isMail == true) {
			$reqCheckExist = $bdd->prepare('SELECT * FROM users WHERE email = ?');
		} else {
			$reqCheckExist = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
		}
		$reqCheckExist->execute(array($identifiant));
		$value = $reqCheckExist->rowCount();

		if($value > 0){

			$response = $reqCheckExist->fetch();
			if($response['password'] == hash('sha512', $mdp)){

				$_SESSION['ID'] = $response['ID'];
				$_SESSION['pseudo'] = $response['pseudo'];

				/*UPDATING LAST PROFIL ACTIVITY*/
				date_default_timezone_set('Europe/Paris');
				$majActivity = $bdd->prepare('UPDATE users SET lastCo = ? WHERE ID = ?');
				$majActivity->execute(array(date('Y-m-d H:i:s'),$response['ID']));
				$majActivity->closeCursor();

				return 'Félicitations! Vous êtes maintenant connecté!';


			}else{

				return 'Mauvais mot de passe.';
			}
		}else{

			return 'Utilisateur inconnu';
		}

	}

	static public function waitForInputIns($data){

		if(isset($data['go_ins'])){

		   if(!empty($data['psd_ins']) AND !empty($data['name_ins']) AND !empty($data['birthdate_ins']) AND !empty($data['pass_ins']) AND !empty($data['pass_ins_confirm']) AND !empty($data['mail_ins']) AND !empty($data['mail_ins_confirm'])){



			if(strlen($data['psd_ins']) <= 35){


				if($data['pass_ins'] == $data['pass_ins_confirm']){

						if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $data['mail_ins'])){


							if($data['mail_ins_confirm'] == $data['mail_ins']){

								if(!empty($data['cgu_ins'])){

									if($data['category'] != "no"){

									if(users::ifUserExist($data['psd_ins']) == 'non'){
										
										if(users::ifMailExist($data['mail_ins']) == 'non') {
											
											include('co_pdo.php');
											
											$pass = hash('sha512', $data['pass_ins']);
												$req = $bdd->prepare('INSERT INTO users (`pseudo`, `password`, `Nom`, `email`, `grade`, `category`) VALUES (:pseudo, :password, :nom, :mail, :grade, :category)');
												$req->execute(array(
													'pseudo' => $data['psd_ins'],
													'password' => $pass,
													'nom' => $data['name_ins'],
													'mail' => $data['mail_ins'],
													'grade' => 1,
													'category' => $data['category']));
											require('mailInsc.php');
											mail($data['mail_ins'], $subject, $message);
										
											return users::checkuser($data['mail_ins'],$data['pass_ins'], true);
													header('location: ./user.php?id=' . $_SESSION['ID']); // je redirige vers une page random pour que la connexion soit actualisée
											
										}else{
											return 'Ce mail est déjà enregistré!';
										}
									}else{

										return 'Ce pseudo existe déjà!';
									}

								  }else{
								  	 return 'Veuillez choisir une catégorie';
								  }	

								}else{

									return 'Vous devez accepter les CGU!';
								}

								

							}else{

								return 'Les deux adresses mails ne correspondents pas!';
							}

						}else{

							return 'Adresse mail invalide!';

						}

				}else{

					return 'Les deux mots de passe ne correspondent pas!';
				}


			}else{

				return 'Ce pseudo est trop grand';
			}

		}else{


			return 'Vous devez remplir tout les champs!';
		}
	}

		

	} //end of static func

	static public function waitForInput($data){
		
		if(isset($data['go'])){

			if(isset($data['pseudo'],$data['password']) AND !empty($data['pseudo']) AND !empty($data['password'])){

				if (filter_var($data['pseudo'], FILTER_VALIDATE_EMAIL)) {
					$rep = users::checkuser($data['pseudo'], $data['password'], true);
				} else {
					$rep = users::checkuser($data['pseudo'], $data['password'], false);
				}
				
				return $rep;

			}else{

				return 'Tous les champs ne sont pas remplis!';
			}
		}
	}

	
	public function getPseudoByID($id_func) {
		include('php/co_pdo.php');
		
		$req1 = $bdd->prepare('SELECT pseudo FROM users WHERE ID = :id');
		$req1->execute(array(
			'id' => $id_func
		));
		while ($results = $req1->fetch()) {
			return $results['pseudo'];
		}
	}

}
?>
