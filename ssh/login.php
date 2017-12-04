<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body>
		<?php
			session_start();			
			 If(isset($_POST['submit']) || isset($_GET['user_id'])){
				require_once 'conf.php';
				if(isset($_POST['user_id']))
					$user_id = mysql_real_escape_string($_POST['user_id']);
				else
					$user_id = mysql_real_escape_string($_GET['user_id']);
				echo "User id: $user_id<br/>";
				if(mysql_query("DESCRIBE sshs_$user_id")) {
						$_SESSION['user_id'] = $user_id;
						echo "OK";
						header('location: index.php');
				}else{
					echo "User id không tồn tại.";
				}
			 }
        ?> 
        Mở <b>Data\config.ini</b> để lấy user_id<br/><br/>
        <form action='' method='POST'>
                <b>User id:</b> <input type='text' name='user_id'/><br/>
                <input type='submit' name='submit' value='Login'/>
        </form>
    </body>
</html>