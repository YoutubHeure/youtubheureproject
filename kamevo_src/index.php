<!DOCTYPE html>
<html>
<head>
	<?php
		require ("head.php");
	?>
</head>
<body>
	<?php
		require ("menu.php");
	?>
	<div class="site-pusher">
      <div class="content">
		<?php include('social.php'); ?>
		  <?php
		  if(!isset($_SESSION['ID'])){ getPosts('homeNoConnect'); }else{ getPosts('homeConnect'); } 
		  	

			require ("pub.php");
		  ?>	
		</div>
		<?php
			//require ("displaypost.php");
			require ("groups.php");
			$id = 14;
		?>
	</div>
<?php require ("javascript.php"); ?>
</body>
</html>