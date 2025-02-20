<?php

require_once('../../../db_con/config.php');

if(session_status()===PHP_SESSION_NONE){
session_start();
}
 
$preby =$_SESSION['admin_rocad'];

// $prebyID=$preby;require '../../layout/preby.php';
// $sender = $row_preby['fullname'];


$action = $_REQUEST['action'];   

$activity_id = $_POST['activity_id'];   
$asset_id = $_POST['asset_id'];  

if($action=="approve"){
    $status=1;
}else{
    $status=2;
}

  
  $sql_query = "UPDATE `asset_activity` SET approved_by='$preby', status='$status'  where `id`='$activity_id' ";
  $updated = mysqli_query($config, $sql_query);
    
  if($action=="approved"){
    $quantity_left = $_POST['quantity_left'];  
    $approved =   mysqli_query($config, "UPDATE `structural_asset` set qty_store = '$quantity_left' where id = '$asset_id' ");
   }

if ($updated) {
    $output = array('status' => 'true', 'result' => $updated);
} else {
    $output = array('status' => 'false', 'result' => "Query fails");
}

// encoding array to json format
echo json_encode($output);

?>
