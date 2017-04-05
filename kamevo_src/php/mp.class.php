<?php
/*****
This class is an extension of users class. Please note that the user objet is not directly link it.
******************/
class mpClass extends users{ 

	private $userDatas; //contain all all current users infos.



	public function __construct($userInf){

		//nothing here, sorry :D (Wista)	
		$this->userDatas = $userInf;


	}



	public function displayUserFollowed(){

		include 'co_pdo.php';

		$getF = $bdd->prepare('SELECT abonnement FROM subs WHERE abonne = ?');
		$getF->execute(array($this->userDatas->idUser));

		if($getF->rowCount() == 0){?>

			<div class="chatter" id="noFollow">	
 			 <h5 class="chatter-name"> Vous ne suivez personne!</h5>	
  			</div>


		<?php }else {
		
		while ($foll = $getF->fetch()) {?>

			 <div class="chatter" id="<?=$foll['abonnement'] ?>">
 				<img src="userDataUpload/picProfile/<?=$this->getAvatarUser($foll['abonnement']); ?>" alt="chatter-img" class="chatter-img">	
 			 <h5 class="chatter-name"> <?php echo $this->getPseudoByID($foll['abonnement']); ?></h5>	
  			</div>
				
				 

				
		<?php }

	}



	}

	private function getAvatarUser($userIDAv){


		include('co_pdo.php');

		$req = $bdd->prepare('SELECT avatar FROM users WHERE ID = ?');
		$req->execute(array($userIDAv));
		$rep = $req->fetch();

		return $rep['avatar'];

	}









}
?>