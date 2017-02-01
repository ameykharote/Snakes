<?php
	include "connection.php";
	//include('../common-code.php');
	//session_start();	
	
	if(isset($_POST['score'])){
		$email_id = $_SESSION['tek_emailid'];
		$fname = $_SESSION['tek_fname'];
		$megapoints = $_POST['mega_point'];
		$score = $_POST['score'];
		
		
		$sqlchk = "SELECT `email_id`, `fname`, `score`, `played` FROM `scores` WHERE `email_id` = '$email_id'";
		$reschk = mysqli_query($link , $sqlchk);
		$row2 = mysqli_fetch_assoc($reschk);
		$temp = mysqli_num_rows($reschk);
		//echo $temp;
		if ($score > '35' && $row2['played'] == 0){
			$insert1 = "UPDATE `scores` SET mega_point = '1000' WHERE `email_id` = '$email_id'";
			$exew = mysqli_query($link , $insert1) or die(mysqli_error());
			$insert3 = "UPDATE `scores` SET `played`= '1' WHERE `email_id` = '$email_id'"; 
			$exen = mysqli_query($link , $insert3) or die(mysqli_error());
			//$sendscore(snakes,1000,$email_id);
		}
		else{
			$insert1 = "UPDATE `scores` SET mega_point = 'No Megapoints' WHERE `email_id` = '$email_id'";
			$exew = mysqli_query($link , $insert1) or die(mysqli_error());
		}
		if(mysqli_num_rows($reschk) > 0){
			
			$sqlscorecheck = "SELECT `score` FROM `scores` WHERE `email_id` = '$email_id'";
			$row = mysqli_fetch_assoc(mysqli_query($link , $sqlscorecheck));
			$currentscore = $row['score'];
			if($currentscore < $score){
				$newsql = "UPDATE `scores` SET `score`= '$score' WHERE `email_id` = '$email_id'";
				$_SESSION["score"] = $score;
				$resnew = mysqli_query($link , $newsql);
				if(!$resnew){
					//debug
				}
			}
			else{
				//do sumthing 
			}
		}
		else{
			//echo "success1";
			$sql = "INSERT INTO `scores`(`email_id`, `fname`, `score`) VALUES ('$email_id','$fname','$score')";
			$res = mysqli_query($link , $sql);
			if($res){
				//echo "success2";
				//debug
			}
		}	
	}
	else{
		die('connection problem!'); //debug
	}
?>