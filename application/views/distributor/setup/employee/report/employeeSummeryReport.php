<?php

// echo "<pre>";
// print_r($JanuaryTotal);
// exit();
?>
<?php
if (isset($_POST['month'])):
    $monthw = $this->input->post('month');
    $yearw = $this->input->post('year');


endif;
?>

<style>
    @page {
        size: A4;
        margin: 0;
        margin-bottom: 0.5cm;
        margin-top: 1cm;
        display: block;
        box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);

    }

    @media print {
        html, body {
            width: 210mm;

        }

        /* ... the rest of the rules ... */
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Department Wise Salary Summery Report
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal noPrint">
                            <div class="col-sm-12">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Month </label>
                                            <div class="col-sm-6">

                                                <select class="form-control" id="month" name="month">
                                                    <option
                                                        <?php
                                                        if ($monthw == 'all') {
                                                            echo "selected";
                                                        }
                                                        ?> value="all">All
                                                    <option <?php
                                                    if ($monthw == 'January') {
                                                        echo "selected";
                                                    }
                                                    ?> value="January">January
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'February') {
                                                        echo "selected";
                                                    }
                                                    ?> value="February">February
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'March') {
                                                        echo "selected";
                                                    }
                                                    ?> value="March">March
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'April') {
                                                        echo "selected";
                                                    }
                                                    ?> value="April">April
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'May') {
                                                        echo "selected";
                                                    }
                                                    ?> value="May">May
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'June') {
                                                        echo "selected";
                                                    }
                                                    ?> value="June">June
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'July') {
                                                        echo "selected";
                                                    }
                                                    ?> value="July">July
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'August') {
                                                        echo "selected";
                                                    }
                                                    ?> value="August">August
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'September') {
                                                        echo "selected";
                                                    }
                                                    ?> value="September">September
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'October') {
                                                        echo "selected";
                                                    }
                                                    ?> value="October">October
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'November') {
                                                        echo "selected";
                                                    }
                                                    ?> value="November">November
                                                    </option>
                                                    <option <?php
                                                    if ($monthw == 'December') {
                                                        echo "selected";
                                                    }
                                                    ?> value="December">December
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Year </label>
                                            <div class="col-sm-6">

                                                <select class="form-control" id="year" name="year">
                                                    <option selected value="" disabled>--Select--</option>
                                                    <option
                                                        <?php
                                                        if ($yearw == '2019') {
                                                            echo "selected";
                                                        }
                                                        ?> value="2019">2019
                                                    </option>

                                                    <option <?php
                                                    if ($yearw == '2020') {
                                                        echo "selected";
                                                    }
                                                    ?> value="2020">2020
                                                    </option>
                                                    <option <?php
                                                    if ($yearw == '2021') {
                                                        echo "selected";
                                                    }
                                                    ?> value="2021">2021
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->

            </div>
        </div>
    </div>
</div>
<div class="row" id="EmpprintReport">
    <div class="col-md-12 std_print">
        <div class="portlet ">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Summery Report
                </div>
            </div>
            <div class="panel-heading">
                            <h4 class="panel-title"><strong>Attendance List </strong>
                                <div class="pull-right hidden-print" >
                                    <!--<a href="<?php echo base_url() ?>admin/attendance/create_pdf/<?php echo $DepartmentID . '/' . $date ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Pdf"><span><i class="fa fa-file-pdf-o"></i></span></a>-->
                                    <!--<a href="<?php echo base_url() ?>admin/attendance/create_excel/<?php echo $DepartmentID . '/' . $date ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a>                                                -->
                                    <button  class="btn-print" title="Print" data-toggle="tooltip" type="button" onclick="printEmp_report('EmpprintReport')">Print</button>                                                              
                                </div>
                            </h4>
                        </div>
            <div class="portlet-body">


                <?php
                if ($monthw == 'all') {


                ?>


                <div class="row">
                    <table class="col-xs-12">

                        <table class="table table-responsive">
                            <tr>
                                <td style="text-align:center;">
                                     <strong>Company Name : </strong><?php echo $companyInfo->companyName; ?><br>
                                        <strong>Address : </strong><?php echo $companyInfo->address; ?><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>

                                </td>
                            </tr>
                        </table>
                        <table id="example" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>

                                <th width="5%">Department</th>
                                <th width="5%">January</th>
                                <th width="5%">February</th>
                                <th width="5%">March</th>
                                <th width="5%">April</th>
                                <th width="5%">May</th>
                                <th width="5%">June</th>
                                <th width="5%">July</th>
                                <th width="5%">August</th>
                                <th width="5%">September</th>
                                <th width="5%">October</th>
                                <th width="5%">November</th>
                                <th width="5%">December</th>


                            </tr>
                            </thead>
                            <?php
                            if (!empty($all_view)) {
                                foreach ($all_view as $key => $value) {
                                    ?>
                                    <tr>

                                        <th width="5%"><?php echo $key ?></th>
                                        <th width="5%">
                                            <?php
                                            if(isset($value['January'])){
                                                echo $value['January_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?>

                                        </th>
                                        <th width="5%">
                                            <?php
                                            if(isset($value['February'])){
                                                echo $value['February_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?>
                                        </th>
                                        <th width="5%"><?php
                                            if(isset($value['March'])){
                                                echo $value['March_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['April'])){
                                                echo $value['April_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['May'])){
                                                echo $value['May_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['June'])){
                                                echo $value['June_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['July'])){
                                                echo $value['July_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['August'])){
                                                echo $value['August_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['September'])){
                                                echo $value['September_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['October'])){
                                                echo $value['October_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['November'])){
                                                echo $value['November_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>
                                        <th width="5%"><?php
                                            if(isset($value['December'])){
                                                echo $value['December_amount'];
                                            }else{
                                                echo 0;
                                            }

                                            ?></th>


                                    </tr>

                                <?php }
                            } ?>
                        </table>
                </div>
            </div>


            <?php } else { ?>

                <?php if (!empty($getAllEmployeewiseD)) { ?>
                    <div class="row">
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>

                                    </td>
                                </tr>
                            </table>

                            <table id="example" class="table table-striped table-bordered table-hover">
                                <?php if ($getAllEmployeewiseD[0]->month == 'January' || $getAllEmployeewiseD[1]->month == 'January' || $getAllEmployeewiseD[2]->month == 'January' || $getAllEmployeewiseD[3]->month == 'January' || $getAllEmployeewiseD[4]->month == 'January' || $getAllEmployeewiseD[5]->month == 'January' || $getAllEmployeewiseD[6]->month == 'January' || $getAllEmployeewiseD[7]->month == 'January' || $getAllEmployeewiseD[8]->month == 'January' || $getAllEmployeewiseD[9]->month == 'January' || $getAllEmployeewiseD[10]->month == 'January' || $getAllEmployeewiseD[11]->month == 'January'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'January') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>


                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>

                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $JanuaryTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'February' || $getAllEmployeewiseD[1]->month == 'February' || $getAllEmployeewiseD[2]->month == 'February' || $getAllEmployeewiseD[3]->month == 'February' || $getAllEmployeewiseD[4]->month == 'February' || $getAllEmployeewiseD[5]->month == 'February' || $getAllEmployeewiseD[6]->month == 'February' || $getAllEmployeewiseD[7]->month == 'February' || $getAllEmployeewiseD[8]->month == 'February' || $getAllEmployeewiseD[9]->month == 'February' || $getAllEmployeewiseD[10]->month == 'February' || $getAllEmployeewiseD[11]->month == 'February'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                            <?php $grandTotal = 0;
                            foreach ($getAllEmployeewiseD as $key => $value) {
                                $grandTotal += $value->dep; ?>
                                <?php if ($value->month == 'February') { ?>
                                    <tr>

                                        <td><?php echo $value->DepartmentName; ?></td>
                                        <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                        <td><?php echo $value->dep; ?></td>


                                    </tr>
                                <?php } ?>
                            <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $FebruaryTotal; ?></td>
                                </tr>
                            <?php } ?>

                                <?php if ($getAllEmployeewiseD[0]->month == 'March' || $getAllEmployeewiseD[1]->month == 'March' || $getAllEmployeewiseD[2]->month == 'March' || $getAllEmployeewiseD[3]->month == 'March' || $getAllEmployeewiseD[4]->month == 'March' || $getAllEmployeewiseD[5]->month == 'March' || $getAllEmployeewiseD[6]->month == 'March' || $getAllEmployeewiseD[7]->month == 'March' || $getAllEmployeewiseD[8]->month == 'March' || $getAllEmployeewiseD[9]->month == 'March' || $getAllEmployeewiseD[10]->month == 'March' || $getAllEmployeewiseD[11]->month == 'March') { ?>
                                    <thead>
                                    <tr>

                                        <th width="5%">Salary as per Department</th>
                                        <th width="5%">Month & Year</th>

                                        <th width="10%">Total</th>


                                    </tr>
                                    </thead>
                                    <?php $grandTotal = 0;
                                    foreach ($getAllEmployeewiseD as $key => $value) {
                                        $grandTotal += $value->dep; ?>
                                        <?php if ($value->month == 'March') { ?>
                                            <tr>

                                                <td><?php echo $value->DepartmentName; ?></td>
                                                <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                                <td><?php echo $value->dep; ?></td>


                                            </tr>

                                        <?php } ?>

                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">Sub-Total</td>
                                        <td><?php echo $MarchTotal; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'April' || $getAllEmployeewiseD[1]->month == 'April' || $getAllEmployeewiseD[2]->month == 'April' || $getAllEmployeewiseD[3]->month == 'April' || $getAllEmployeewiseD[4]->month == 'April' || $getAllEmployeewiseD[5]->month == 'April' || $getAllEmployeewiseD[6]->month == 'April' || $getAllEmployeewiseD[7]->month == 'April' || $getAllEmployeewiseD[8]->month == 'April' || $getAllEmployeewiseD[9]->month == 'April' || $getAllEmployeewiseD[10]->month == 'April' || $getAllEmployeewiseD[11]->month == 'April'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'April') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>


                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $AprilTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'May' || $getAllEmployeewiseD[1]->month == 'May' || $getAllEmployeewiseD[2]->month == 'May' || $getAllEmployeewiseD[3]->month == 'May' || $getAllEmployeewiseD[4]->month == 'May' || $getAllEmployeewiseD[5]->month == 'May' || $getAllEmployeewiseD[6]->month == 'May' || $getAllEmployeewiseD[7]->month == 'May' || $getAllEmployeewiseD[8]->month == 'May' || $getAllEmployeewiseD[9]->month == 'May' || $getAllEmployeewiseD[10]->month == 'May' || $getAllEmployeewiseD[11]->month == 'May'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'May') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>


                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $MayTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'June' || $getAllEmployeewiseD[1]->month == 'June' || $getAllEmployeewiseD[2]->month == 'June' || $getAllEmployeewiseD[3]->month == 'June' || $getAllEmployeewiseD[4]->month == 'June' || $getAllEmployeewiseD[5]->month == 'June' || $getAllEmployeewiseD[6]->month == 'June' || $getAllEmployeewiseD[7]->month == 'June' || $getAllEmployeewiseD[8]->month == 'June' || $getAllEmployeewiseD[9]->month == 'June' || $getAllEmployeewiseD[10]->month == 'June' || $getAllEmployeewiseD[11]->month == 'June'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'June') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>


                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $JuneTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'July' || $getAllEmployeewiseD[1]->month == 'July' || $getAllEmployeewiseD[2]->month == 'July' || $getAllEmployeewiseD[3]->month == 'July' || $getAllEmployeewiseD[4]->month == 'July' || $getAllEmployeewiseD[5]->month == 'July' || $getAllEmployeewiseD[6]->month == 'July' || $getAllEmployeewiseD[7]->month == 'July' || $getAllEmployeewiseD[8]->month == 'July' || $getAllEmployeewiseD[9]->month == 'July' || $getAllEmployeewiseD[10]->month == 'July' || $getAllEmployeewiseD[11]->month == 'July'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'July') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>

                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $JulyTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'August' || $getAllEmployeewiseD[1]->month == 'August' || $getAllEmployeewiseD[2]->month == 'August' || $getAllEmployeewiseD[3]->month == 'August' || $getAllEmployeewiseD[4]->month == 'August' || $getAllEmployeewiseD[5]->month == 'August' || $getAllEmployeewiseD[6]->month == 'August' || $getAllEmployeewiseD[7]->month == 'August' || $getAllEmployeewiseD[8]->month == 'August' || $getAllEmployeewiseD[9]->month == 'August' || $getAllEmployeewiseD[10]->month == 'August' || $getAllEmployeewiseD[11]->month == 'August'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'August') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>

                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $AugustTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'September' || $getAllEmployeewiseD[1]->month == 'September' || $getAllEmployeewiseD[2]->month == 'September' || $getAllEmployeewiseD[3]->month == 'September' || $getAllEmployeewiseD[4]->month == 'September' || $getAllEmployeewiseD[5]->month == 'September' || $getAllEmployeewiseD[6]->month == 'September' || $getAllEmployeewiseD[7]->month == 'September' || $getAllEmployeewiseD[8]->month == 'September' || $getAllEmployeewiseD[9]->month == 'September' || $getAllEmployeewiseD[10]->month == 'September' || $getAllEmployeewiseD[11]->month == 'September'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'September') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>


                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $SeptemberTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'October' || $getAllEmployeewiseD[1]->month == 'October' || $getAllEmployeewiseD[2]->month == 'October' || $getAllEmployeewiseD[3]->month == 'October' || $getAllEmployeewiseD[4]->month == 'October' || $getAllEmployeewiseD[5]->month == 'October' || $getAllEmployeewiseD[6]->month == 'October' || $getAllEmployeewiseD[7]->month == 'October' || $getAllEmployeewiseD[8]->month == 'October' || $getAllEmployeewiseD[9]->month == 'October' || $getAllEmployeewiseD[10]->month == 'October' || $getAllEmployeewiseD[11]->month == 'October'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'October') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>


                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $OctoberTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'November' || $getAllEmployeewiseD[1]->month == 'November' || $getAllEmployeewiseD[2]->month == 'November' || $getAllEmployeewiseD[3]->month == 'November' || $getAllEmployeewiseD[4]->month == 'November' || $getAllEmployeewiseD[5]->month == 'November' || $getAllEmployeewiseD[6]->month == 'November' || $getAllEmployeewiseD[7]->month == 'November' || $getAllEmployeewiseD[8]->month == 'November' || $getAllEmployeewiseD[9]->month == 'November' || $getAllEmployeewiseD[10]->month == 'November' || $getAllEmployeewiseD[11]->month == 'November'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'November') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>

                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $NovemberTotal; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($getAllEmployeewiseD[0]->month == 'December' || $getAllEmployeewiseD[1]->month == 'December' || $getAllEmployeewiseD[2]->month == 'December' || $getAllEmployeewiseD[3]->month == 'December' || $getAllEmployeewiseD[4]->month == 'December' || $getAllEmployeewiseD[5]->month == 'December' || $getAllEmployeewiseD[6]->month == 'December' || $getAllEmployeewiseD[7]->month == 'December' || $getAllEmployeewiseD[8]->month == 'December' || $getAllEmployeewiseD[9]->month == 'December' || $getAllEmployeewiseD[10]->month == 'December' || $getAllEmployeewiseD[11]->month == 'December'){ ?>
                                <thead>
                                <tr>

                                    <th width="5%">Salary as per Department</th>
                                    <th width="5%">Month & Year</th>

                                    <th width="10%">Total</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $grandTotal = 0;
                                foreach ($getAllEmployeewiseD as $key => $value) {
                                    ?>
                                    <?php if ($value->month == 'December') {
                                        $grandTotal += $value->dep; ?>
                                        <tr>

                                            <td><?php echo $value->DepartmentName; ?></td>
                                            <td><?php echo $value->month; ?>-<?php echo $value->year; ?></td>

                                            <td><?php echo $value->dep; ?></td>


                                        </tr>

                                    <?php } ?>


                                <?php } ?>
                                <tr>
                                    <td colspan="2">Sub-Total</td>
                                    <td><?php echo $DecemberTotal; ?></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                            <div class="invoice-block pull-right">
                                <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                   onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                    <i class="fa fa-print"></i>
                                </a>


                            </div>

                        </div>
                    </div>
                    <?php if (empty($getAllEmployeewiseD)) { ?>
                        <h2>Result Not Found</h2>
                    <?php } ?>

                <?php }
            } ?>


        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function() {
    $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
            "sPaginationType": "full_numbers",
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ]
    });
});
</script>
<script type="text/javascript">
    function printEmp_report(EmpprintReport) {
        var printContents = document.getElementById(EmpprintReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
