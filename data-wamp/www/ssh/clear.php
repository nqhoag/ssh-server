<?php
    If(!isset($_SESSION['user_id'])){
        exit("User id không tồn tại");
    }
    $user_id = $_SESSION['user_id'];
	$query1 = mysql_query("SELECT DISTINCT country FROM sshs_$user_id");
	$country = array();
	$i1=0;
	While($r1 = mysql_fetch_assoc($query1)) {
	   $country[$i1] = $r1['country'];
	   $i1++;
	}
	$query2 = mysql_query("SELECT DISTINCT offers FROM sshs_$user_id");
	$offers = array();
	$i2=0;
	While($r2 = mysql_fetch_assoc($query2)) {
	   $offers[$i2] = $r2['offers'];
	   $i2++;
	}
	If(isset($_POST['clear'])){
		$condition = "";
		$country = $_POST['country'];
		$offers = $_POST['offers'];
		If($country=='all' && $offers=='all'){
			$condition = '1';
		}elseif($offers=='all'){
			$condition = "country = '$country'";
		}elseif($country=='all'){
			$condition = "offers = '$offers'";
		}else{
			$condition = "country='$country' AND offers='$offers'";
		}
		$sql = "DELETE FROM sshs_".$user_id." WHERE $condition";
		$res = mysql_query($sql) or die(mysql_error());
		echo "Số ssh nước $country đã xóa: ".  mysql_affected_rows();
	}else{
?>
<form action="" method="POST">
	<table>
	<tr><td>Country</td><td>
	<select name="country">
		<option value='all'>Tất cả</option>
		<?php 
			Foreach($country as $c){
				echo "<option value='$c'>$c</option>"; 
			}
		?>
	</select></td></tr>
	<tr><td>Offers</td><td>
	<select name="offers">
		<option value='all'>Tất cả</option>
		<?php 
			Foreach($offers as $o){
				If($o==''){
					echo "<option value=''>None</option>";
				}else{
					echo "<option value='$o'>$o</option>";
				}
			}
		?>
	</select></td></tr>
	<input type="submit" name="clear" value="Clear"/>
</form>
<?php
	}
?>