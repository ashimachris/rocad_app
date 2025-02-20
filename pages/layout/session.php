<?php
require "../../db_con/config.php";
date_default_timezone_set("Africa/Lagos");
$adminID = $_SESSION['admin_rocad'];
$usergroup = $_SESSION['usergroup'];
//mysqli_select_db($config,$database_config);
$query_all_admin = "SELECT * FROM `admin` WHERE id=$adminID";
$all_admin = mysqli_query($config,$query_all_admin)or die (mysqli_error($config));
$row_admin = mysqli_fetch_assoc($all_admin);
$_SESSION['admin_name']=$row_admin['fullname'];
///////////////PRE set
//!eps!kTlAShv
//Staff Cat

$query_cat = "SELECT * FROM `staff_cat`";

$cat= mysqli_query($config,$query_cat) or die(mysqli_error($config));

//Company Site

$query_site = "SELECT * FROM `rocad_site`";

$site= mysqli_query($config,$query_site) or die(mysqli_error($config));



function allow_access($admin, $subadmin, $staff, $storekeeper, $account, $visitor, $usergroup){

    if ($admin == 0 AND $usergroup == 4) {

        echo "style='display:none;'";

    }

    if ($subadmin == 0  AND $usergroup == 3) {

        echo "style='display:none;'";

    }

    if ($staff == 0  AND $usergroup == 2) {

        echo "style='display:none;'";

    }

    if ($storekeeper == 0  AND $usergroup == 1) {

        echo "style='display:none;'";

    }
    if ($account == 0  AND $usergroup == 5) {

        echo "style='display:none;'";

    }
    if ($visitor == 0  AND $usergroup == 6) {

        echo "style='display:none;'";

    }

}

function allow_access_all($admin, $subadmin, $staff, $storekeeper, $account, $visitor, $usergroup){

    if ($admin == 0 AND $usergroup == 4) {

        echo "<script>window.location='../sections/access_denied.php';</script>";

    }

    if ($subadmin == 0  AND $usergroup == 3) {

        echo "<script>window.location='../sections/access_denied.php';</script>";

    }

    if ($staff == 0  AND $usergroup == 2) {

        echo "<script>window.location='../sections/access_denied.php';</script>";

    }

    if ($storekeeper == 0  AND $usergroup == 1) {

        echo "<script>window.location='../sections/access_denied.php';</script>";

    }
    if ($account == 0  AND $usergroup == 5) {

        echo "<script>window.location='../sections/access_denied.php';</script>";

    }
    if ($visitor == 0  AND $usergroup == 6) {

        echo "<script>window.location='../sections/access_denied.php';</script>";

    }

}

?>