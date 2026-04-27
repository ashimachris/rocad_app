<?php
require_once('../../db_con/config.php');

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";

/* =========================================================
   AJAX CONTROLLER
========================================================= */
if(isset($_POST['action'])){

    $from     = mysqli_real_escape_string($config,$_POST['from']);
    $to       = mysqli_real_escape_string($config,$_POST['to']);
    $site     = mysqli_real_escape_string($config,$_POST['site']);
    $keyword  = mysqli_real_escape_string($config,$_POST['keyword'] ?? '');
    $category = mysqli_real_escape_string($config,$_POST['category'] ?? '');
    $sub      = mysqli_real_escape_string($config,$_POST['sub'] ?? '');

    if(!$site || !$from || !$to){
        exit("Required filters missing.");
    }

    $where = " WHERE der.status='2'
               AND der.fromsite='$site'
               AND DATE(der.time_date) BETWEEN '$from' AND '$to' ";

    if($keyword){
        $where .= " AND der.description LIKE '%$keyword%'";
    }

    if($category){
        $where .= " AND der.expense_category_id='$category'";
    }

    if($sub){
        $where .= " AND der.expense_sub_category_id='$sub'";
    }

    /* ====================== LOAD CATEGORIES ====================== */
    if($_POST['action']=="get_categories"){

        $qry = "
        SELECT ec.id, ec.name, SUM(der.amount) total
        FROM daily_expenses_reports der
        JOIN expenses_category ec ON ec.id=der.expense_category_id
        $where
        GROUP BY ec.id
        ORDER BY total DESC";

        $res=mysqli_query($config,$qry);

        $html="<ul class='list-group'>";
        while($row=mysqli_fetch_assoc($res)){
            $html.="<li class='list-group-item drill-category'
                        data-id='{$row['id']}'>
                        <b>{$row['name']}</b>
                        <span class='badge bg-blue pull-right'>
                        &#8358;".number_format($row['total'],2)."
                        </span>
                    </li>";
        }
        $html.="</ul>";

        echo $html;
        exit;
    }

    /* ====================== LOAD SUB CATEGORIES ====================== */
    if($_POST['action']=="get_sub"){

        $qry = "
        SELECT esc.id, esc.name, SUM(der.amount) total
        FROM daily_expenses_reports der
        JOIN expenses_sub_category esc 
            ON esc.id=der.expense_sub_category_id
        $where
        GROUP BY esc.id
        ORDER BY total DESC";

        $res=mysqli_query($config,$qry);

        $html="<ul class='list-group'>";
        while($row=mysqli_fetch_assoc($res)){
            $html.="<li class='list-group-item drill-expense'
                        data-id='{$row['id']}'>
                        {$row['name']}
                        <span class='badge bg-yellow pull-right'>
                        &#8358;".number_format($row['total'],2)."
                        </span>
                    </li>";
        }
        $html.="</ul>";

        echo $html;
        exit;
    }

    /* ====================== LOAD EXPENSE RECORDS ====================== */
    if($_POST['action']=="get_expenses"){

        $qry="SELECT description, amount, time_date 
              FROM daily_expenses_reports der
              $where
              ORDER BY time_date DESC";

        $res=mysqli_query($config,$qry);

        $html="<table class='table table-bordered table-striped'>";
        $html.="<thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
                </thead><tbody>";

        $total=0; $count=0; $max=0; $min=0;

        while($row=mysqli_fetch_assoc($res)){
            $amt=$row['amount'];
            $total+=$amt; $count++;

            if($amt>$max) $max=$amt;
            if($min==0 || $amt<$min) $min=$amt;

            $html.="<tr>
                        <td>{$row['description']}</td>
                        <td>&#8358;".number_format($amt,2)."</td>
                        <td>{$row['time_date']}</td>
                    </tr>";
        }

        $html.="</tbody></table>";

        $avg=$count?($total/$count):0;

        echo json_encode([
            "html"=>$html,
            "summary"=>[
                "total"=>$total,
                "count"=>$count,
                "avg"=>$avg,
                "max"=>$max,
                "min"=>$min
            ]
        ]);
        exit;
    }
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once "../layout/topmenu.php"; ?>
<?php include_once "../layout/left-sidebar.php"; ?>

<div class="content-wrapper">
<section class="content-header">
<h1>Enterprise Category Forensic Report</h1>
</section>

<section class="content">
<div class="row">

<!-- ================= FILTER PANEL ================= -->
<div class="col-md-3">
<div class="box box-danger">
<div class="box-header"><h4>Deep Search Filters</h4></div>
<div class="box-body">

<label>Site</label>
<select id="site" class="form-control" required>
<option value="">Select Location (Site)</option>
<?php
$site_q="SELECT * FROM rocad_site 
         WHERE sitename!='' AND status=1 
         ORDER BY sitename ASC";
$as_site=mysqli_query($config,$site_q);
while($row_site=mysqli_fetch_assoc($as_site)){
?>
<option value="<?php echo $row_site['id']; ?>">
<?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?>
</option>
<?php } ?>
</select><br>

<label>From</label>
<input type="date" id="from" class="form-control" required><br>

<label>To</label>
<input type="date" id="to" class="form-control" required><br>

<label>Keyword (Optional)</label>
<input type="text" id="keyword" class="form-control"><br>

<button class="btn btn-danger btn-block" onclick="loadCategories()">Search</button>

</div>
</div>
</div>

<!-- ================= DRILL PANEL ================= -->
<div class="col-md-6">
<div class="box box-primary">
<div class="box-header"><h4>Drill Down Intelligence</h4></div>
<div class="box-body" id="drill-content">
Select filters and click Search.
</div>
</div>
</div>

<!-- ================= SUMMARY PANEL ================= -->
<div class="col-md-3">
<div class="box box-success">
<div class="box-header"><h4>Financial Summary</h4></div>
<div class="box-body" id="summary-panel">
Summary will appear here.
</div>
</div>
</div>

</div>
</section>
</div>
</div>

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

function baseFilters(){
    return {
        site: $("#site").val(),
        from: $("#from").val(),
        to: $("#to").val(),
        keyword: $("#keyword").val()
    };
}

function loadCategories(){
    var f=baseFilters();

    if(!f.site || !f.from || !f.to){
        alert("Site, From Date and To Date are required.");
        return;
    }

    $.post("category_report.php",
        {...f, action:"get_categories"},
        function(data){
            $("#drill-content").html(data);
            $("#summary-panel").html("Select a category to view financial breakdown.");
        });
}

$(document).on("click",".drill-category",function(){
    $.post("category_report.php",
        {...baseFilters(), category:$(this).data("id"), action:"get_sub"},
        function(data){
            $("#drill-content").html(data);
        });
});

$(document).on("click",".drill-expense",function(){
    $.post("category_report.php",
        {...baseFilters(), sub:$(this).data("id"), action:"get_expenses"},
        function(resp){
            var r=JSON.parse(resp);
            $("#drill-content").html(r.html);

            $("#summary-panel").html(`
                <p><b>Total:</b> &#8358;${Number(r.summary.total).toLocaleString()}</p>
                <p><b>Transactions:</b> ${r.summary.count}</p>
                <p><b>Average:</b> &#8358;${Number(r.summary.avg).toLocaleString()}</p>
                <p><b>Highest:</b> &#8358;${Number(r.summary.max).toLocaleString()}</p>
                <p><b>Lowest:</b> &#8358;${Number(r.summary.min).toLocaleString()}</p>
            `);
        });
});

</script>

<?php include_once "../layout/footer.php"; ?>
