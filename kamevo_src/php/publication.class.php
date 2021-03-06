<?php

	class publication extends userProfile{

		public $title_of_publi;
		private $errorMessage;
		private $textComment;



		function __construct($id_post){

				$this->current_post_id = $id_post;
				$this->errorMessage = '';
				$this->updateViews($id_post);

		}

		private function sendComment(){


			require('co_pdo.php');

			$this->textComment =  htmlspecialchars($_POST['comment']);
			$this->sendNotifMentionC();

			$id_post_to_send = $this->current_post_id;
			$id_sender_of_comment = $_SESSION['ID'];

			date_default_timezone_set ( 'Europe/Paris' );
			$dateCrea = date('d/m/Y à H:i 	s\s');

			//for ($i=0; $i < 100 ; $i++) { 
				$sendCom = $bdd->prepare('INSERT INTO comments(id_post,poster,comment,note,date) VALUES (?,?,?,?,?)');
				$sendCom->execute(array($id_post_to_send,$id_sender_of_comment,$this->textComment,'0',$dateCrea));

			//}
			
			/*UPDATING LAST PROFIL ACTIVITY*/
				date_default_timezone_set('Europe/Paris');
				$majActivity = $bdd->prepare('UPDATE users SET lastCo = ? WHERE ID = ?');
				$majActivity->execute(array(date('Y-m-d H:i:s'),$_SESSION['ID']));
				$majActivity->closeCursor();
					
			//echo 'Commentaire posté!';
			$_POST = array(); //cleaning receving datas



		}

		private function sendNotifMentionC(){

			$commentaire = $this->textComment;
 
			$this->textComment = preg_replace_callback('#@([A-Za-z0-9]+)#', 'publication::replaceTextC', $commentaire); 
 	
		}
		public function replaceTextC($matches) { 
	    
		    include 'co_pdo.php';

		    $req = $bdd->prepare('SELECT ID FROM users WHERE pseudo = ?'); 


		    $req->execute(array($matches[1])); 
		 
		    if($req->rowCount() == 1) { 


		        $idUtilisateur = $req->fetch()['ID']; 


		        $notif = $bdd->prepare('INSERT INTO notifs(destinataire, message, ack) VALUES (?, ?, ?)');
		        $notif->execute(array($idUtilisateur, 'Vous avez été mentionné dans <a href="details.php?idpost='.$this->current_post_id.'">un commentaire</a>', 'unread'));
		        $notif->closeCursor();


		        return '@<a href="user.php?id='.$idUtilisateur.'">'.$matches[1].'</a>'; 
		    }



		    return $matches[0]; 
		} 




		public function loadComments(){
				
			require('co_pdo.php');	
			if(isset($_POST['submit']) AND !empty($_POST['comment'])) $this->sendComment(); ?>
			<div class="block-z">
			<div class="comments" id="startComment">
			
				<!-- Post comment form --> 
				<div class="forms">
  					<form method="post" class="comment-form" action="#startComment">
	  					<h6 class="comment-name"><strong><?=$this->getPsdFromId($_SESSION['ID']); ?> </strong></h6><br/>
  						<textarea class="comment-input" name="comment" placeholder="Mon commentaire ..."></textarea><br/>
  						<input type="submit" name="submit" class="post-btn" value="publier mon commentaire">
  					</form>
  					<span class="respComSend" id="resComSend" style="color:red;background:yellow;"><?=$this->errorMessage; ?></span>
  				</div>
  				<!-- End of post comment form --> 
  				<?php 

  				$CommentsPerPage = 20;
				$nbTotalCommentsReq = $bdd->prepare('SELECT ID FROM comments WHERE id_post = ?');
				$nbTotalCommentsReq->execute(array($this->current_post_id));
				$nbTotalComments = $nbTotalCommentsReq->rowCount();

				$totalPages = ceil($nbTotalComments/$CommentsPerPage);

				if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $totalPages) {
   					 $currentPage = (int)$_GET['page'];
				} else {
   					$currentPage = 1;
				}

				$start = ($currentPage-1)*$CommentsPerPage;
				echo '<div id="pagesDisplay">';
  				for($i=1;$i<=$totalPages;$i++) {
         			if($i == $currentPage) {
            			echo $i.' ';
         			}elseif ($i == $currentPage+1) {
         				echo '<a href="details.php?idpost='.$this->current_post_id.'&page='.$i.'" class="nextPage">'.$i.'</a> ';
         			} else {
            			echo '<a href="details.php?idpost='.$this->current_post_id.'&page='.$i.'">'.$i.'</a> ';
         			}
      			} 
      			?> 
      			</div>
      			<div id="allCommentsDiv">
  				<?php
  				/*Request to load all the comments */

  				$updateCom = $bdd->prepare('SELECT * FROM comments WHERE id_post = ? ');
				$updateCom->execute(array($this->current_post_id));
				while($dataCom = $updateCom->fetch()){


					$this->updateLikesComments($dataCom['ID']); //updating likes for every comments
				} 

  				$loadCom = $bdd->prepare('SELECT * FROM comments WHERE id_post = ? ORDER BY ID DESC LIMIT '.$start.','.$CommentsPerPage);
				$loadCom->execute(array($this->current_post_id));

				

				
				while($dataCom = $loadCom->fetch()){
				 ?>

					

			
  				<!-- Only one comment -->
  				<div class="oneComment" id="comment<?=$dataCom['ID']; ?>">
	  	 			<div class="block-comment com">
						<img class="block-img" src="<?=$this->getAvatarFromId($dataCom['poster']); ?>" alt="William">
		 				 <h6 class="block-name"><strong><a href="user.php?id=<?=$dataCom['poster']; ?>"><?=$this->getPsdFromId($dataCom['poster']); ?></a> </strong> | <span class="comment-date"><?=$dataCom['date']; ?></span></h6>
						<p class="comment-content">
							 <?=$dataCom['comment']; ?>
		  				 <br/><br/>
		  				<a href="#comment<?=$dataCom['ID']; ?>" class="comment-like" onclick="userVoteComment(2,<?=$dataCom['ID']; ?>)">Je n'aime pas ( <span class="nblikescomid<?=$dataCom['ID']; ?>" id="nblikescomid<?=$dataCom['ID']; ?>"><?=$dataCom['dislikes']; ?></span> )</a>
		  				<a href="#comment<?=$dataCom['ID']; ?>" class="comment-like" onclick="userVoteComment(1,<?=$dataCom['ID']; ?>)">J'aime ( <span class="nbdislikescomid<?=$dataCom['ID']; ?>" id="nbdislikescomid<?=$dataCom['ID']; ?>"><?=$dataCom['likes']; ?></span> )</a>
		 					<br/></p><span id="ErrorcommentId<?=$dataCom['ID']; ?>" class="ErrorcommentId<?=$dataCom['ID']; ?>"></span>
					</div>
				</div>
				<?php } ?>
				</div>

			</div>
			<?php
		}

		/*private function getPsdFromId($userIdSearch){

			include('co_pdo.php');
			$req = $bdd->prepare('SELECT pseudo FROM users WHERE ID = ?');
			$req->execute(array($userIdSearch));
			$rep = $req->fetch();
			return $rep['pseudo'];


		}*/
		public function getIdCreator(){

			include('co_pdo.php');

			$getAth = $bdd->prepare('SELECT author FROM posts WHERE ID = ?');
			$getAth->execute(array($this->current_post_id));
			$rep = $getAth->fetch();
			$id_author =  $rep['author'];
			$getAth->closeCursor();

			return $id_author;




		}

		public function updateLikesComments($idComment){

			require('co_pdo.php');

			$ComgetL = $bdd->prepare('SELECT * FROM votecomments WHERE id_com = ? AND vote = 1'); //1 = like
			$ComgetL->execute(array($idComment));
			$Comnblikes = $ComgetL->rowCount();


			$ComgetD = $bdd->prepare('SELECT * FROM votecomments WHERE id_com = ? AND vote = 2'); //2 = dislike
			$ComgetD->execute(array($idComment));
			$Comnbdislikes = $ComgetD->rowCount();

			$ComLDupds = $bdd->prepare('UPDATE comments SET likes = ?, dislikes = ? WHERE ID = ?');
			$ComLDupds->execute(array($Comnblikes,$Comnbdislikes,$idComment));

			$ComLDupds->closeCursor();

	}


		
	public function getAvatarFromId($userIdSearch){

			include('co_pdo.php');
			$req = $bdd->prepare('SELECT avatar FROM users WHERE ID = ?');
			$req->execute(array($userIdSearch));
			$rep = $req->fetch();
			return 'userDataUpload/picProfile/'.$rep['avatar'];


     }






	private function updateViews($id_post){

		/*  Visites uniques: 



		  */

		include('co_pdo.php');

		$ip_client = $this->get_ip();


		$checkIp = $bdd->prepare('SELECT * FROM views_posts WHERE ip = ? AND id_post = ?');
		$checkIp->execute(array($ip_client,$id_post));

		$nb = $checkIp->rowCount();
		$checkIp->closeCursor();

		if($nb == 0){
			$upViews = $bdd->prepare('INSERT INTO views_posts(IP,id_post,nb_visites) VALUES (?,?,?)');
			$upViews->execute(array($ip_client,$id_post,1));
			$upViews->closeCursor();

			$upViewsGlobal = $bdd->prepare('UPDATE posts SET uniq_views = uniq_views+1 WHERE ID = ?');
			$upViewsGlobal->execute(array($id_post));
			$upViewsGlobal->closeCursor();

		}else{

			$upViews = $bdd->prepare('UPDATE views_posts SET nb_visites = nb_visites+1 WHERE ip = ?');
			$upViews->execute(array($ip_client));
			$upViews->closeCursor();

			$upViewsGlobal2 = $bdd->prepare('UPDATE posts SET total_views = total_views+1 WHERE ID = ?');
			$upViewsGlobal2->execute(array($id_post));
			$upViewsGlobal2->closeCursor();

		}





	}

	public function get_ip() {
		// IP si internet partagé
			if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		// IP derrière un proxy
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		// Sinon : IP normale
		else {
			return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		}
}



	}


?>