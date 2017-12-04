<?php
    If(!isset($_SESSION['user_id'])){
        exit("User id không tồn tại");
    }
	$user_id = $_SESSION['user_id'];
	$runused = mysql_query("select count(ID) as unused from sshs_".$user_id." where offers=''");
	$numunused = mysql_fetch_assoc($runused);
	$nunused = $numunused['unused'];
	$rlive = mysql_query("select count(ID) as total from sshs_".$user_id);
	$numlive = mysql_fetch_assoc($rlive);
	$nlive = $numlive['total'];
	echo "<h1>Thống kê ssh</h2>";
	echo "<h2>Tổng số SSH trên server: $nlive</h3>";
	echo "<h2>Tổng số SSH chưa dùng: $nunused</h3>";
	$rc = mysql_query("select distinct country from sshs_".$user_id);
	$ro = mysql_query("select distinct offers from sshs_".$user_id);
	If(mysql_num_rows($rc)>0){
		echo "<div class='list'><h3>Quốc gia:<ul>";
		while($c = mysql_fetch_assoc($rc)){
			$country = $c["country"];
			$rrc = mysql_query("select count(ID) as total from sshs_".$user_id." where country='$country'");
			$rrcunused = mysql_query("select count(ID) as unused from sshs_".$user_id." where country='$country' and offers=''");
			$rrcdead= mysql_query("select count(ID) as die from sshs_".$user_id." where country='$country' and dead='die'");
			$numsshc = mysql_fetch_assoc($rrc);
			$numsshcunused = mysql_fetch_assoc($rrcunused);
			$numsshcdead = mysql_fetch_assoc($rrcdead);
			echo "<li><a href='?mod=list&type=country&value=$country'>$country</a> => Tổng: ".$numsshc["total"]." | Chưa dùng: ".$numsshcunused['unused']."|Die: ".$numsshcdead['die'];
		}
		echo "</ul></div>";
	}
	If(mysql_num_rows($ro)>0){
		echo "<div class='list'><h3>Offer:<ul>";
		$os = array();
		$i=0;
		while($oss = mysql_fetch_assoc($ro)){
			$offer = explode(',',$oss["offers"]);
			Foreach($offer as $off){
				If(!in_array(rtrim($off),$os)){
					$os[$i] = $off;
					$i++;
				}
			}
		}
		echo "</li>";
		foreach($os as $o){
                    If($o<>''){
                        $rro = mysql_query("select count(ID) as total from sshs_".$user_id." where offers like '%$o,%'");
                        $numssho = mysql_fetch_assoc($rro);
                        echo "<li><a href='?mod=list&type=offers&value=$o'>$o</a>: ".$numssho["total"];
                    }
		}
		echo "</ul></div>";
	}
?>