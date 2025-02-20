<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
} 

  require_once('../../../db_con/config.php');
  $auth=$_SESSION['admin_rocad'];

    $prebyID=$_SESSION['admin_rocad'];require '../../layout/preby.php';

    $sender = $row_preby['fullname'];


  $reference = $_POST['reference'];

  $sql_query = "UPDATE storeloadingdetails SET status=2 WHERE reference='$reference' ";
  $approved = mysqli_query($config, $sql_query);

  $wry_store = "SELECT * FROM `storeloadingdetails` WHERE reference='$reference'";

  $store=mysqli_query($config,$wry_store);

  if($store)
  {   
    $row_store = mysqli_fetch_array($store);

    $descrip = $row_store['descrip'];
    $PlantNo = $row_store['PlantNo'];
    $qty = $row_store['qty'];
    $totalvalue = $row_store['totalvalue'];
    $conditions = $row_store['conditions'];
    $preby = $row_store['preby'];
    $isReject = $row_store['isReject'];
    $authby = $row_store['authby'];
    $fromsite = $row_store['fromsite'];
    $tosite = $row_store['tosite'];
    $method = $row_store['method'];
    $note = $row_store['note'];
    $status = $row_store['status'];
    $infoD = $row_store['infoD'];
    $time_date = $row_store['time_date'];
    $dept = $row_store['dept'];
    $reqfor = $row_store['reqfor'];
    $qtyw = $row_store['qtyw'];
    $supl = $row_store['supl'];
    $pay_to = $row_store['pay_to'];
    $bank_name = $row_store['bank_name'];
    $invDate = $row_store['invDate'];
    $lpo = $row_store['lpo'];
    $inspby = $row_store['inspby'];
    $title = $row_store['title'];
    $invoice = $row_store['invoice'];
    $sign_by = $row_store['sign_by'];

    if($authby==""){
      $authby=$auth; 
    }


   $sql = "insert into `daily_expenses_reports`(`plant_no`,`site`,`description`,`amount`,`time_date`,`preby`,`wstatus`,`reference`, fromsite, status, invoice, authby, sign_by)
            values('".$PlantNo."','".$fromsite."','".$reqfor."','".$totalvalue."','".$time_date."','".$preby."','".$status."','".$reference."', '$fromsite', 1, '$invoice', $authby, '$sign_by')";

    $result = mysqli_query($config,$sql) or die(mysqli_error($config));
 
     $insert = "INSERT into invoices (ckeck_ref,cct,ac_name,ac_bank,ac_no,file_name,reference,PlantNo,supl,pay_to,ispaid,infoD,title,uploadby,lpo,uploaded_on) 
        VALUES ('0','3','$supl','$bank_name','$pay_to','$invoice','$reference','$PlantNo','$supl','$pay_to','0','$infoD','$title','$preby','$reference','$time_date')";

    mysqli_query($config,$insert) or die(mysqli_error($config));

   $sqls="insert into `history`(`PlantNo`,`time_date`,`lprice`,`info`,`pre_by`,`title`,`site`,`requi`)values('".$PlantNo."','".$time_date."','".$totalvalue."','".$reqfor."','".$preby."','".$title."','".$fromsite."','".$reference."')";       

     mysqli_query($config,$sqls) or die(mysqli_error($config));


     // send mail here
    $to = "deleakintayo@rocad.com,ronaldo@rocad.com,rene@rocad.com,umar@rocad.com,tamer@rocad.com,john@rocad.com,joshua@rocad.com,ibrahim.labaran@rocad.com";
    // $to = "abbas@ereg.ng,agbachris555@gmail.com";
    // Subject
    $subject = "Requisition Approved By  $sender on ".date("d-m-Y");

     // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: app.rocad.com" . "\r\n";

    // Email Body
    $message = "<html><body>";
    $message .= "<h2>Requisition with reference $reference is approved on  ".date("d-m-Y")." </h2>";
    $message .= "<h3>Description: <i>$reqfor</i></h3>";
    $message .= "<h3>Approved by : " .$sender ."</h3>";
    $message .= "</body></html>";

    // Sending the Email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }

    $output = array('status'=> 'true', 'result' => $result);
  }else{
    $output= array('status'=>'false', 'result'=>"Query fails");
  }

  // encoding array to json format
  echo json_encode($output);
;
?>