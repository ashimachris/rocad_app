<?php

if($mail){

    if(isset($_GET['approve_id'])){
        $id = $_GET['approve_id'];
         $qryasset="SELECT * FROM `daily_expenses_reports` where id=". $_GET['approve_id'];

         $qry_amount = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` where id=$id");
         $total_amount =  mysqli_fetch_assoc($qry_amount)['total_amount'];

    }else{
        $qryasset="SELECT * FROM `daily_expenses_reports` where `id` IN (".(implode(",", $ids)).")";

         $qry_amnt="SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` where `id` IN (".(implode(",", $ids)).")";
         $qry_amount = mysqli_query($config, $qry_amnt);
         $total_amount =  mysqli_fetch_assoc($qry_amount)['total_amount'];
    }



    $assets=mysqli_query($config,$qryasset) or die(mysqli_error($config));
    $assetss=mysqli_query($config,$qryasset) or die(mysqli_error($config));
    $row_assetss=mysqli_fetch_assoc($assetss);


    // Recipient Email
    // $to = "abbas@ereg.ng,agbachris555@gmail.com";
   $to = "deleakintayo@rocad.com,ronaldo@rocad.com,rene@rocad.com,umar@rocad.com,tamer@rocad.com,agbachris555@gmail.com,john@rocad.com,joshua@rocad.com,ibrahim.labaran@rocad.com";
    //$to = "agbachris555@gmail.com";
    // Subject
    $subject = "Approved Expenses ".number_format($total_amount,2)." ".date("d-m-Y");

    // HTML content with a table
    $sn=1;
    $tableContent = "
    <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
        <thead>
            <tr style='background-color: #f2f2f2;'>
                <th>S/N</th>
                <th>SITE</th>
                <th>REFERENCE</th>
                <th>PLANT/CODE</th>
                <th>DESCRIPTION</th>
                <th>AMOUNT</th>
                <th>SUPPLIER/ACCT NAME</th>
                <th>BANK</th>
                <th>ACCT NO</th>
                <th>PREPARED BY</th>
                <th>SIGNED BY</th>
            </tr>
        </thead>
        <tbody>";
        $j=0; 
        while($row_assets=mysqli_fetch_assoc($assets)){
            $j++;
            $sign_by = $row_assets['sign_by'];
            $reference = $row_assets['reference'];

            $qry_store = mysqli_query($config, "SELECT * FROM `storeloadingdetails` where reference='$reference'");
            $r_store =  mysqli_fetch_assoc($qry_store);
            $supplier = $r_store['supl'];
            $pay_to = $r_store['pay_to'];
            $bank_name = $r_store['bank_name'];

            // updated invoice
             $sql_query = "UPDATE `invoices` SET ispaid=1 where `reference`='$reference' ";
             $updated = mysqli_query($config, $sql_query);
              if($row_assets['plant_no']=='0'){
                $plant_no = "Nil";
                  }else{
                    $plant_no=$row_assets['plant_no']; 
                   }

            $tableContent .="
                <tr style='text-transform: uppercase;''>
                      <td class='text-center'>$j</td>
               
                  <td>";
                     $siteID=$row_assets['fromsite'];require '../layout/site.php';
                       $site = $row_site['site_state'];
                       $site_lga = $row_site['site_lga'];
                       $site_loc = $row_site['site_loc'];  

                  $tableContent .="     
                        $site - $site_lga - $site_loc
                  </td>
                  <td>".$reference."</td>
                  <td>".$plant_no."</td>
                  <td>".$row_assets['description']."</td>
                  <td>". number_format($row_assets['amount'],2)." </td>
                  <td>$supplier</td>
                  <td>$bank_name</td>
                  <td>$pay_to</td>

                   <td>";
                    if(!empty($row_assets['authby'])){
                    $authbyID=$row_assets['authby'];
                   }else{
                      $authbyID=0;$tableContent .="<span class='label label-danger'>Waiting...</span>";
                    }require '../layout/authby.php';
                    
                    $auth_fullname = $row_authby['fullname'];

                    $tableContent .="
                    $auth_fullname
                   </td>

                    <td>$sign_by</td>
                   
                 </tr>";
             }

        $tableContent .="  
        </tbody>
    </table>";


     $date = date("d-m-Y");
    /*
     <td>";
       $prebyID=$row_assets['preby'];require '../layout/preby.php';
       $fullname = $row_preby['fullname']; 
       $tableContent .=" 
            $fullname
       </td>
     if (!is_dir("email-attachments")) {
          mkdir("email-attachments", 0755, true);
        } 
 
   
    // Path where the file will be saved
    $fileName = "daily_expenses-$date.pdf";
    $filePath = 'email-attachments/' . $fileName;  // Ensure this path is writable


    // Create the HTML file with the table content
    file_put_contents($filePath, $tableContent);


    // Prepare email headers for attachment
    $boundary = uniqid('boundary_');

    // Email headers with the necessary boundary for attachment
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "From: noreply@rocad.ng" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"" . "\r\n";

    // Email body message
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
    $body .= "<html><body><h2>Expenses approved on $date attached as a file.</h2></body></html>\r\n\r\n";

    // File attachment
    $attachment = chunk_split(base64_encode(file_get_contents($filePath)));
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: application/octet-stream; name=\"$fileName\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $attachment . "\r\n\r\n";
    $body .= "--$boundary--";

    // Send the email with attachment
    if (mail($to, $subject, $body, $headers)) {
        echo "Email sent successfully with attachment!";
    } else {
        echo "Failed to send email.";
    }
    */

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: app.rocad.com" . "\r\n";

    // Email Body
    $message = "<html><body>";
    $message .= "<h2>Approved Expenses on  ".date("d-m-Y")." </h2>";
    $message .= "<h3>Total approved: ".number_format($total_amount,2)." </h3>";
    $message .= "<h3>Paid from: " .$row_assetss['bank_mail'] ."</h3>";
    $message .= "<h3>Comment: " .$row_assetss['comment_mail'] ."</h3>";
    $message .= "<h3>Approved by : " .$sender ."</h3>";
    $message .= $tableContent;
    $message .= "</body></html>";

    // Sending the Email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
?>
