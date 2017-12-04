<?php
If(isset($_POST['export'])){
	$output = $_POST['listssh'];
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Transfer-Encoding: binary;\n");
	header("Content-Disposition: attachment; filename=\"list_ssh_exported_by_FA_Autolead.txt\";\n");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Description: File Transfer");
	header("Content-Length: ".strlen($output).";\n");
	echo $output;
	die;
}
?>