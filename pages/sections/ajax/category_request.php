<?php

	require_once('../../../db_con/config.php');

	$category = $_POST['category'];   // category
	
	$sql = "SELECT SUM(qty) AS total FROM `history` WHERE title='$category'";

	
	$result = mysqli_query($config,$sql) or die(mysqli_error($config));;

	if($result)
	{   $rst = mysqli_fetch_array($result)['total'];
		$output = array('status'=> 'true', 'result' => $rst);
	}else{
		$output= array('status'=>'false', 'result'=>"Query fails");
	}

	// encoding array to json format
	echo json_encode($output);
;
?>