<?php
	$cn=mysqli_connect('localhost','id9565921_root','password');
	mysqli_select_db($cn,'id9565921_dummy_user');
?>
<?php
	if(isset($_POST['transfer']))
	{
		echo "<script>document.getElementByClass('alert-box').style.display='none';</script>";
		$user=$_POST['select-user'];
		$query3="select * from users where Name='".$user."'";
		$result3=mysqli_query($cn,$query3);
		$r=mysqli_fetch_array($result3);
		$receiver=$r[0];
		$receiver_name=$r[1];
		$sender=$_GET['id'];
		$credit=$_POST['amount'];
		$query4="select * from users where id=".$sender;
		$result4=mysqli_query($cn,$query4);
		$r4=mysqli_fetch_array($result4);
		$query5="select * from users where id=".$receiver;
		$result5=mysqli_query($cn,$query5);
		$r5=mysqli_fetch_array($result5);
		$sender_credit=$r4[3];
		$sender_name=$r4[1];
		$receiver_credit=$r5[3];
		if($credit>$sender_credit)
		{
			echo "<script>alert('Not Enough Credits');</script>";
		}
		else if($credit<1)
		{
			echo "<script>alert('Credit should not be less than 0');</script>";
		}
		else{
			$sender_credit=$sender_credit-$credit;
			$query6="update users set Credits=".$sender_credit." where id=".$sender;
			mysqli_query($cn,$query6);
			$receiver_credit=$receiver_credit+$credit;
			$query7="update users set Credits=".$receiver_credit." where id=".$receiver;
			mysqli_query($cn,$query7);
			$query8="insert into transfers values('".$sender_name."','".$receiver_name."','".$credit."')";
			mysqli_query($cn,$query8);
			//header("Location:index.php");
			echo "<script>window.location='index.php'</script>";
		}
	}
?>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="home.css">
</head>
<body>

<h1>CREDIT MANAGEMENT</h1>
<table border="1" class="user-data">
	<tr>
		<th>Sr. No</th>
		<th>Name</th>
		<th>Email</th>
		<th>Credits</th>
		<th class="extra"></th>
	</tr>
	<?php
		$query="select * from users";
		$result=mysqli_query($cn,$query);
		while($r=mysqli_fetch_array($result))
		{
			echo "<tr>";
			echo "<td>".$r[0]."</td>";
			echo "<td>".$r[1]."</td>";
			echo "<td>".$r[2]."</td>";
			echo "<td>".$r[3]."</td>";
			echo "<td><a href='index.php?id=".$r[0]."' onclick='transfer()'>Transfer Credits</a></td>";
			echo "</tr>";
		}		
	?>
</table>
	<?php
		if(isset($_GET['id']))
		{
			echo "<form method='POST'><div id='box' class='alert-box'>";
			echo "<span onclick='dis()'>x</span>";
			//echo "<div class='inner-alert'>";
			echo "<table><tr>";
			echo "<td>Enter credits to transfer</td><td><input type='number' name='amount' required></td></tr>";
			$id=$_GET['id'];
			$query2="select * from users where id!=".$id;
			$result2=mysqli_query($cn,$query2);
			echo "<tr><td>Select User to transfer</td><td><select name='select-user'>";
			while($r=mysqli_fetch_array($result2))
			{
				echo "<option>".$r[1]."</option>";
			}
			echo "</select></td></tr>";
			echo "<tr><td align='center'colspan='2'><input type='submit' class='transfer-btn' name='transfer' value='Transfer'></td></tr>";
			//echo "</div>";
			echo "</div></form>";
		}
	?>
	<script>
		function dis(){
			window.location="index.php";
		}
	</script>
</body>
</html>