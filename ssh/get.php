<?php
    error_reporting(1);
    If(isset($_GET['user_id'])){
        require_once("conf.php");
        $country = mysql_real_escape_string($_GET["country"]);
        $user_id = mysql_real_escape_string($_GET["user_id"]);
        $offer = mysql_real_escape_string($_GET["offer"]);
        $sql = "SELECT * FROM sshs_".$user_id." 
				WHERE (country='$country' OR country like '%($country)') AND (offers NOT LIKE '%$offer,%' AND dead='') 
				ORDER BY RAND()
				LIMIT 1";
		if(substr_count($offer,'-RRS')>0)
			$sql = "select * from sshs_$user_id where country='$country' and dead='' order by rand() limit 1";
        $res = mysql_query($sql) OR die(mysql_error());
		$s = '';
        while($ssh = mysql_fetch_assoc($res)){
            $s = $ssh["ssh"];
			if($s==''){
				$s = $ssh['ip']."|".$ssh['user']."|".$ssh['passwd'];
			}
			$offers = $ssh['offers']."$offer,";
			mysql_query("UPDATE sshs_$user_id SET offers='$offers' WHERE ID=".$ssh['ID']);
        }
        echo $s;
    }