<?php

class users{

	
	public $pseudo;	


	function __construct($ID){

		$this->idUser = $ID;
		


	}

	static public function checkuser($pseudo,$mdp){

		require('co_pdo.php');

		unset($_SESSION['ID']); //cleaning the session

		$reqCheckExist = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
		$reqCheckExist->execute(array($pseudo));
		$value = $reqCheckExist->rowCount();

		if($value > 0){

			$response = $reqCheckExist->fetch();
			if($response['password'] == md5($mdp)){

				$_SESSION['ID'] = $response['ID'];
				$_SESSION['pseudo'] = $pseudo;
				return 'Félicitations! Vous êtes maintenant connecté! <img src="img/loader2.gif" alt="" />';


			}else{

				return 'Mauvais mot de passe.';
			}
		}else{

			return 'Utilisateur inconnu';
		}

	}

	static public function waitForInputIns($data){

		if(isset($data['go_ins'])){

			print_r($data);

		   if(!empty($data['psd_ins']) AND !empty($data['name_ins']) AND !empty($data['birthdate_ins']) AND !empty($data['pass_ins']) AND !empty($data['pass_ins_confirm']) AND !empty($data['mail_ins']) AND !empty($data['mail_ins_confirm'])){



			if(strlen($data['psd_ins']) <= 15){


				if($data['pass_ins'] == $data['pass_ins_confirm']){

						if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $data['mail_ins'])){


							if($data['mail_ins_confirm'] == $data['mail_ins']){

								return 'Inscription validée! Vous pouvez vous connecter!';

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

				$rep = users::checkuser(htmlspecialchars($data['pseudo']), htmlspecialchars($data['password']));
				
				return $rep;

			}else{

				return 'Tous les champs ne sont pas remplis!';
			}
		}
	}



	public function getPseudo(){

		include('co_pdo.php');
		$req = $bdd->prepare('SELECT pseudo FROM users WHERE ID = ?');
		$req->execute(array($this->idUser));
		$rep = $req->fetch();
		return $rep['pseudo'];
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

	public function getMail(){

		include('co_pdo.php');
		$req1 = $bdd->prepare('SELECT email FROM users WHERE ID = ?');
		$req1->execute(array($this->idUser));
		$resq = $req1->fetch();
		return $resq['email'];
	}

	public function getId(){

		return $this->idUser;
	}
}
?>
