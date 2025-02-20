<?php

// Retrieve diesel count from the row_oil_count
$num = $row_oil_count['diesel'];

// Switch statement for diesel percentage bar and color
switch($num) {
    case 0:
        $barD = "red";  // If diesel is 0, set bar color to red
        $wd = 1;        // Set width to 1%
        break;
    case ($num >= 0 && $num <= 5000):
        $barD = "red";  // If diesel is between 0 and 5000, set bar color to red
        $wd = 15;       // Set width to 15%
        break;
    case ($num >= 5001 && $num <= 10000):
        $barD = "yellow";  // If diesel is between 5001 and 10000, set bar color to yellow
        $wd = 25;          // Set width to 25%
        break;
    case ($num >= 10001 && $num <= 15000):
        $barD = "aqua";    // If diesel is between 10001 and 15000, set bar color to aqua
        $wd = 75;          // Set width to 75%
        break;
    case ($num >= 15001 && $num <= 20000):
        $barD = "green";   // If diesel is between 15001 and 20000, set bar color to green
        $wd = 100;         // Set width to 100%
        break;
    default:
        $barD = "green";   // For all other values, set bar color to green
        $wd = 100;         // Set width to 100%
}

// Retrieve petrol count from the row_oil_count
$numP = $row_oil_count['petrol'];

// Switch statement for petrol percentage bar and color
switch($numP) {
    case 0:
        $barP = "red";  // If petrol is 0, set bar color to red
        $wp = 1;        // Set width to 1%
        break;
    case ($numP >= 0 && $numP <= 5000):
        $barP = "red";  // If petrol is between 0 and 5000, set bar color to red
        $wp = 15;       // Set width to 15%
        break;
    case ($numP >= 5001 && $numP <= 10000):
        $barP = "yellow";  // If petrol is between 5001 and 10000, set bar color to yellow
        $wp = 25;          // Set width to 25%
        break;
    case ($numP >= 10001 && $numP <= 15000):
        $barP = "aqua";    // If petrol is between 10001 and 15000, set bar color to aqua
        $wp = 75;          // Set width to 75%
        break;
    case ($numP >= 15001 && $numP <= 20000):
        $barP = "green";   // If petrol is between 15001 and 20000, set bar color to green
        $wp = 100;         // Set width to 100%
        break;
    default:
        $barP = "green";   // For all other values, set bar color to green
        $wp = 100;         // Set width to 100%
}

// Retrieve engine oil count from the row_oil_count
$numE = $row_oil_count['engineoil'];

// Switch statement for engine oil percentage bar and color
switch($numE) {
    case 0:
        $barE = "red";  // If engine oil is 0, set bar color to red
        $we = 1;        // Set width to 1%
        break;
    case ($numE >= 0 && $numE <= 5000):
        $barE = "red";  // If engine oil is between 0 and 5000, set bar color to red
        $we = 15;       // Set width to 15%
        break;
    case ($numE >= 5001 && $numE <= 10000):
        $barE = "yellow";  // If engine oil is between 5001 and 10000, set bar color to yellow
        $we = 25;          // Set width to 25%
        break;
    case ($numE >= 10001 && $numE <= 15000):
        $barE = "aqua";    // If engine oil is between 10001 and 15000, set bar color to aqua
        $we = 75;          // Set width to 75%
        break;
    case ($numE >= 15001 && $numE <= 20000):
        $barE = "green";   // If engine oil is between 15001 and 20000, set bar color to green
        $we = 100;         // Set width to 100%
        break;
    default:
        $barE = "green";   // For all other values, set bar color to green
        $we = 100;         // Set width to 100%
}

// Retrieve hydraulic oil count from the row_oil_count
$numH = $row_oil_count['hydraulicoil'];

// Switch statement for hydraulic oil percentage bar and color
switch($numH) {
    case 0:
        $barH = "red";  // If hydraulic oil is 0, set bar color to red
        $wh = 1;        // Set width to 1%
        break;
    case ($numH >= 0 && $numH <= 5000):
        $barH = "red";  // If hydraulic oil is between 0 and 5000, set bar color to red
        $wh = 15;       // Set width to 15%
        break;
    case ($numH >= 5001 && $numH <= 10000):
        $barH = "yellow";  // If hydraulic oil is between 5001 and 10000, set bar color to yellow
        $wh = 25;          // Set width to 25%
        break;
    case ($numH >= 10001 && $numH <= 15000):
        $barH = "aqua";    // If hydraulic oil is between 10001 and 15000, set bar color to aqua
        $wh = 75;          // Set width to 75%
        break;
    case ($numH >= 15001 && $numH <= 20000):
        $barH = "green";   // If hydraulic oil is between 15001 and 20000, set bar color to green
        $wh = 100;         // Set width to 100%
        break;
    default:
        $barH = "green";   // For all other values, set bar color to green
        $wh = 100;         // Set width to 100%
}

// Query to get the last 7 invoices
$qryasset = "SELECT * FROM invoices ORDER BY uploaded_on DESC LIMIT 7";
$Invoices = mysqli_query($config, $qryasset) or die(mysqli_error($config));

?>

      <!-- Info boxes -->
<div class="row">
    <!-- Plant Info Box -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <!-- Icon for Plants, directs to the equipment page -->
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-truck" style="cursor:pointer" onclick="window.location='/rocad_admin/pages/sections/equipments.php'"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Plants</span>
                <span class="info-box-number"><small>#</small><?php echo $Cassets; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- Expenses Report Info Box -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <!-- Icon for Expenses Report, directs to the general expense report page -->
            <span class="info-box-icon bg-red">
                <i class="fa fa-road" style="cursor:pointer" onclick="window.location='/rocad_admin/pages/sections/general_expense_report.php'"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Expenses Report</span>
                <span class="info-box-number"><small>#</small><?php echo $C1assets; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- Responsive fix for small devices -->
    <div class="clearfix visible-sm-block"></div>

    <!-- Staff Info Box -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <!-- Icon for Staffs, directs to the staffs page -->
            <span class="info-box-icon bg-green">
                <i class="fa fa-users" style="cursor:pointer" onclick="window.location='/rocad_admin/pages/sections/staffs.php'"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Staffs</span>
                <span class="info-box-number"><small>#</small><?php echo $C2assets; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- Active Sites Info Box -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <!-- Icon for Active Sites, directs to the sites page -->
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-globe" style="cursor:pointer" onclick="window.location='/rocad_admin/pages/sections/sites.php'"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Active Sites</span>
                <span class="info-box-number"><small>#</small><?php echo $C3assets; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i>
                </button>
                 
                <button data-widget="remove" class="btn btn-box-tool" type="button"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <p class="text-center">
                    <strong></strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas style="height: 180px; width: 703px;" id="salesChart" height="180" width="703"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4" <?php allow_access(1,1,0,1,1,0,$usergroup); ?>>
                  <p class="text-center" >
                    <strong><u style="cursor:pointer;" onclick="window.location='../sections/diesel_consumption.php';">Remains in Stock!</u></strong>
                  </p>
 
                  <div class="progress-group">
                    <span class="progress-text">Diesel</span>
                    <span class="progress-number"><b><?php echo number_format($row_oil_count['diesel'])."LTR"; ?></b>/<?php echo $row_oil['diesel']; ?></span>

                    <div class="progress sm">
                      <div style="width: <?php echo "$wd"; ?>%" class="progress-bar progress-bar-<?php echo $barD;?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Petrol</span>
                    <span class="progress-number"><b><?php echo number_format($row_oil_count['petrol'])."LTR"; ?></b>/<?php echo $row_oil['petrol']; ?></span>

                    <div class="progress sm">
                      <div style="width: <?php echo $wp; ?>%" class="progress-bar progress-bar-<?php echo $barP;?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Engine Oil</span>
                    <span class="progress-number"><b><?php echo $row_oil_count['engineoil']; ?></b>/<?php echo number_format($row_oil['engineoil'])."LTR"; ?></span>

                    <div class="progress sm">
                      <div style="width: <?php echo $we; ?>%" class="progress-bar progress-bar-<?php echo $barE;?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Hydraulic oil</span>
                    <span class="progress-number"><b><?php echo number_format($row_oil_count['hydraulicoil'])."LTR"; ?></b>/<?php echo $row_oil['hydraulicoil']; ?></span>

                    <div class="progress sm">
                      <div style="width: <?php echo $wh; ?>%" class="progress-bar progress-bar-<?php echo $barH;?>"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  
                  <!-- /.progress-group -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer">
              <div class="row">
                
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                   
                  <!-- /.description-block -->
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->
          <div claboxss="box -sesuccs">
             
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="row">
                <div class="col-md-9 col-sm-8">
                   
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-4">
                   
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->          

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Invoices</h3>

              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i>
                </button>
                <button data-widget="remove" class="btn btn-box-tool" type="button"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Plant No</th>
                    <th>Title</th>                     
                    <th>Uploaded on</th>
                    <th>Preview</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php while($row_invoices=mysqli_fetch_assoc($Invoices)){ $fname=$row_invoices['file_name'];$ref=$row_invoices['reference'];?>
                  <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" onclick="window.location='/rocad_admin/pages/sections/invoices_more.php?v=<?php echo $ref ?>'" >
                    <td><?php echo $row_invoices["uploaded_on"]; ?></td>
                    <td><?php if($row_invoices["PlantNo"]){echo $row_invoices["PlantNo"];}else{echo "N/A";} ?></td>
                    <td><?php echo $row_invoices["title"]; ?></td>
                    <td><?php echo $row_invoices["uploaded_on"]; ?></td>
                    <td <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><i class="fa fa-eye" aria-hidden="true" style="cursor:pointer" onClick="window.open('/rocad_admin/pages/sections/uploads/<?php echo $fname; ?>')">
                  </i></td>                     
                  </tr>
                <?php }?>
                   
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a class="btn btn-sm btn-info btn-flat pull-right" href="/rocad_admin/pages/sections/invoices.php">View All Invoices</a>
               
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4" style="cursor:pointer">
          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-yellow" onclick="window.open('/rocad_admin/pages/sections/requisition_report.php')">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>

            <div class="info-box-content" >
              <span class="info-box-text">Requisition Report</span>
          
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-green" onclick="window.open('/rocad_admin/pages/sections/daily_report.php')">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>

            <div class="info-box-content" >
              <span class="info-box-text">Daily Expenses</span>
                            </div>
                  
            
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-red" onclick="window.open('/rocad_admin/pages/sections/expenses.php')">
            <span class="info-box-icon"><i class="fa fa-dollar"></i></span>

            <div class="info-box-content" >
              <span class="info-box-text">Site Expenses</span>
               
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-aqua" onclick="window.open('/rocad_admin/pages/sections/advance_voucher.php?w=true')">
            <span class="info-box-icon"><i class="fa fa-handshake-o"></i></span>

            <div class="info-box-content" >
              <span class="info-box-text">Advance Voucher</span>
              
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->

          <div class="box box-default">
             
            <!-- /.footer -->
          </div>
          <!-- /.box -->

          
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->