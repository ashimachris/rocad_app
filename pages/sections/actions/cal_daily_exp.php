<?php
require_once('../../../db_con/config.php');
 if (isset($_POST["displayAmount"])) {
		$value1 = $_POST['value1'];
		////////////////////////
		// $qry_selected = mysqli_query($config, "SELECT SUM(amount) AS total_selected FROM `daily_expenses_reports` where id in($value1) AND status='1' and (date(time_date)=curdate())");
		$qry_selected = mysqli_query($config, "SELECT SUM(amount) AS total_selected FROM `daily_expenses_reports` where id in($value1) AND status='1' ");
$total_selected =  mysqli_fetch_assoc($qry_selected);
$sum_selected=$total_selected['total_selected'];
 }
 if($sum_selected>0){
 echo "Total of Selected: &#8358;".number_format($sum_selected,2);
 }