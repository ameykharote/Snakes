<?php
	include "connection.php";
	//session_start();
	if ((!isset($_SESSION['tek_emailid'])||empty($_SESSION['tek_emailid']))&&(!isset($_SESSION['tek_fname'])||empty($_SESSION['tek_fname']))) {
        echo '<script>window.top.location.href = "http://teknack.in"</script>';
	}
	

?>
<!DOCTYPE html>
<html>
<head>
<style>
body {
    background-color:black;
}

#header {
	line-height:30px;
    background-color:black;
    color:white;
    text-align:center;
}

h1,h2{ 
    font-family: "Comic Sans MS", cursive, sans-serif;
	color: green;
	text-align: center;
	line-height:30%;
}
p{
    font-family: "Comic Sans MS", cursive, sans-serif;
	color: green;

}
#d_main
	{
	color: green;
	line-height:20px;
	font-family: "Comic Sans MS", cursive, sans-serif;
	border-color: green;
}

a{
	font-family: "Comic Sans MS", cursive, sans-serif;
	float:left;
	color:#1aff1a;
}

</style>
</head>
<body>
<div id="header" href="index.html">
<h1><img src="header.png" style="width:900px;height:120px;">
<h1 style="color:#1aff1a;">Leaderboard<h1>
</div>

<div id="nav">
<a href = "index.php">Play again</a><br></br>
<a href = "http://teknack.in">Teknack 2016</a>
</div>

<div id="d_main">
<?php 
	include 'connection.php';
	$sql = "SELECT * FROM scores ORDER BY score DESC LIMIT 10";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	$i=1;
		echo "<center>
			<table border='1' style='width:50%'>
				<thead>
					<tr>
						<td>Rank</td>
						<td>Username</td>
						<td>Email id</td>
						<td>Score</td>
					</tr>
				<thead>";
	while ($row = mysqli_fetch_assoc($result)){
	echo "<tbody>
			<tr>
				<td>".$i."</td>
				<td>".$row['fname']."</td>
				<td>".$row['email_id']."</td>
				<td>".$row['score']."</td>
			</tr>
		<tbody>";
		$i=$i+1;
	}
	echo "</table></center>";
?>
</div>
</body>
</html>