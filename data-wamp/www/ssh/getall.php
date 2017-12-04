<?php
    If(!isset($_SESSION['user_id'])){
        exit("User id không tồn tại");
    }
    $user_id = $_SESSION['user_id'];
    $countries = array();
    $offers = array();
    $res1 = mysql_query("select distinct country from sshs_".$user_id);
    $res2 = mysql_query("select distinct offers from sshs_".$user_id);
    $i1 = 0;
    while($r1 = mysql_fetch_assoc($res1)){
        $countries[$i1] = $r1['country'];
        $i1++;
    }
    $i2 = 0;
    while($r2 = mysql_fetch_assoc($res2)){
        $offers[$i2]= $r2['offers'];
        $i2++;
    }
?>
<form action="" method="POST">
    <table>
        <tr>
            <td>Country</td>
            <td>
                <select name='country'>
					<option value='all'>Tất cả</option>
                <?php
                foreach($countries as $c){
					echo "<option value='$c'>$c</option>";
                }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Offers</td>
            <td>
                <select name='offer'>
					<option value='all'>Tất cả</option>
                <?php
                foreach($offers as $o){
					If($o==''){
						echo "<option value=''>None</option>";
					}else{
						echo "<option value='$o'>$o</option>";
					}
                }
                ?>
                </select>
            </td>
        </tr>
        <tr><td>Giới hạn:</td><td><input type="text" name="limit" value="0"/></td></tr>
        <tr><td colspan="2" align="center"><input type="submit" name="getnow" value="Get now"/></td><tr>
    </table>
</form>
<?php
If(isset($_POST['getnow'])){
	$country = $ssh = $offer = $type = $num = '';
	$conditions = 1;
	$num_ssh = 0;
	$country = mysql_real_escape_string($_POST["country"]);
	$offer = mysql_real_escape_string($_POST["offer"]);
	$limit = (int)$_POST["limit"];
	If($offer<>'all' && $country<>'all'){
		$conditions = "offers='$offer' AND (country = '$country' AND dead='')";
	}
	elseif($country<>'all' && $offer=='all'){
		$conditions = "country='$country'  AND dead=''";
	}elseif($country='all' && $offer<>'all'){
		If($offer=='' || $offer=='None'){
			$conditions = "offers ='' AND dead=''";
		}else{
			$conditions = "offers='$offer' AND dead=''";
		}
	}else{
		$conditions =  "dead=''";
	}
	if($limit>0)
		$num = "limit $limit";
	$sql = "SELECT *
			FROM sshs_$user_id
			WHERE $conditions $num";
	$res = @mysql_query($sql);
	$num_ssh = @mysql_num_rows($res);
	if($num_ssh>0){
		$ssh = "Offer: $offer - Country: $country\n";
		while($s = mysql_fetch_assoc($res)){
			$ssh .= $s['ip']."|".$s['user']."|".$s['passwd']."|".$s['country']."\n";
		}
		$ssh = substr($ssh,0,-1);
		echo "
		<form action='export.php' method='POST'>
			<table>
				<tr>
					<td>Tổng số ssh: <b>$num_ssh</b></td>
					<td><input type='submit' name='export' value='export'/></td>
				</tr>
				<tr><td colspan='2'><textarea name='listssh' cols='60' rows='25'>$ssh</textarea></td></tr>
			</table>
		</form>";
	}else{
		echo "Không còn ssh";
	}
}
?>
<h2>Lưu ý:</h2>
<h3>- Chỉ get được ssh live</h3>
