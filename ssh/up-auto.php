<?php
    If(!isset($_SESSION['user_id'])){
        exit("User id không tồn tại");
    }
    $user_id = $_SESSION['user_id'];
	$ma_countries = array('AUTO','AD','AE','AF','AG','AI','AL','AM','AO','AQ','AR','AS','AT','AU','AW','AX','AZ','BA','BB','BD','BE','BF','BG','BH','BI','BJ','BL','BM','BN','BO','BQ','BR','BS','BT','BV','BW','BY','BZ','CA','CC','CD','CF','CG','CH','CI','CK','CL','CM','CN','CO','CR','CU','CV','CW','CX','CY','CZ','DE','DJ','DK','DM','DO','DZ','EC','EE','EG','EH','ER','ES','ET','FI','FJ','FK','FM','FO','FR','GA','GB','GD','GE','GF','GG','GH','GI','GL','GM','GN','GP','GQ','GR','GS','GT','GU','GW','GY','HK','HM','HN','HR','HT','HU','ID','IE','IL','IM','IN','IO','IQ','IR','IS','IT','JE','JM','JO','JP','KE','KG','KH','KI','KM','KN','KP','KR','KW','KY','KZ','LA','LB','LC','LI','LK','LR','LS','LT','LU','LV','LY','MA','MC','MD','ME','MF','MG','MH','MK','ML','MM','MN','MO','MP','MQ','MR','MS','MT','MU','MV','MW','MX','MY','MZ','NA','NC','NE','NF','NG','NI','NL','NO','NP','NR','NU','NZ','OM','PA','PE','PF','PG','PH','PK','PL','PM','PN','PR','PS','PT','PW','PY','QA','RE','RO','RS','RU','RW','SA','SB','SC','SD','SE','SG','SH','SI','SJ','SK','SL','SM','SN','SO','SR','SS','ST','SV','SX','SY','SZ','TC','TD','TF','TG','TH','TJ','TK','TL','TM','TN','TO','TR','TT','TV','TW','TZ','UA','UG','UM','US','UY','UZ','VA','VC','VE','VG','VI','VN','VU','WF','WS','YE','YT','ZA','ZM','ZW');
?>
<table cellspacing="10" border="0">
<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader"/>
<tr><td>Chọn file ssh:</td><td><input type="file" name="file" size="50"/></td></tr>
<tr><td>Off đã dùng:</td><td><input type="text" name="offers" size="10"/></td></tr>
<tr>
	<td>Fresh - Die?:</td>
	<td>  
		<select name="fresh_die">
			<option value="fresh">Fresh</option>
			<option value="die">Die</option>
		</select>
	</td>
</tr>
<tr><td colspan="2" align="center"><input name="_upl" type="submit" id="_upl" value="Upload"/></td></tr>
</form>
</table>
<?php 
if( $_POST['_upl'] == "Upload") {
	if(@copy($_FILES['file']['tmp_name'], 'fiels/'.$_FILES['file']['name'])) { 		
        $file = 'fiels/'.$_FILES['file']['name'];
        If(substr($file,-4,4)=='.txt'){
			$offers = trim(mysql_real_escape_string($_POST['offers']));
			$dead = ($_POST['fresh_die']=='die')?'die':'';
			if($dead=='die' && $offers=='')
				$offers = 'upload_marked_die';
			if(substr($offers,-1)!=="," && $offers!=='')
				$offers .= ",";
            $sshs = explode("\n",file_get_contents($file));
            $count = 0;
			$upd_count = 0;
            $dup_count = 0;		
			$error_count = 0;
            for($i=0;$i<count($sshs);$i++){
                If(!strpos($sshs[$i],"|")){
					$error_count++;
                    continue;
                }
                $ssh = explode("|",$sshs[$i]);
				If(count($ssh)<3){
					$error_count++;
					continue;
				}
				$ip = $ssh[0];
				$user = $ssh[1];
				$passwd = rtrim($ssh[2]);								
				$country = rtrim($ssh[3]);
				if($dead=='die'){
					mysql_query("UPDATE sshs_$user_id SET dead='die' WHERE ip='$ip' AND dead=''");
					if(mysql_affected_rows()==1)
						$upd_count++;
					else
						$dup_count++;
				}else{
					$sql = "INSERT INTO sshs_".$user_id."(ID,ip,user,passwd,country,offers,dead) VALUES(NULL,'$ip','$user','$passwd','$country','$offers','$dead')";
					mysql_query($sql);
					If(mysql_affected_rows()>0){
						$count++;
					}else{					
						if($offers=='')
							mysql_query("UPDATE sshs_$user_id SET dead='' WHERE ip='$ip' AND dead='die'");
						else
							mysql_query("UPDATE sshs_$user_id SET offer='$offers',
						dead='' WHERE ip='$ip' AND dead='die'");
						if(mysql_affected_rows()==1)
							$upd_count++;
						else
							$dup_count++;
					}
				}
            }
			echo "Số ssh đã thêm:      $count<br/>
				  Số ssh fresh lại:    $upd_count<br/>
				  Số ssh bị trùng:     $dup_count<br/>
				  Số ssh không hợp lệ: $error_count";
        }else{
            echo 'Chỉ cho phép thêm file text(.txt)';
        }
		unlink('fiels/'.$file);
    }else{ 
        echo 'Upload thất bại';
    }
}
?>
<div id='notes' style="margin-top:50px;border:1px solid #ffffff;max-width:350px;padding:5px">
- Upload file text (abc.txt)<br/>
- Mã country chuẩn quốc tế: US, CA, RU, IN...<br/>
- SSH có định dạng và ít nhất 4 thuộc tính sau: ip|user|pass|country<br/>
- <strong>SSh đã dùng cho nhiều off thì mỗi off cách nhau bởi dấu phẩy. Phải đúng tên off đã dùng (Nên copy/paste tên off cho chuẩn)</strong>
</div>