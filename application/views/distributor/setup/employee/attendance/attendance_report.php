<style type="text/css" media="print">
    @media print{@page {size: landscape}}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="wrap-fpanel">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong>Attendance Report</strong>
                    </div>                
                </div>

                <div class="panel-body">
                    <form id="attendance-form" role="form" enctype="multipart/form-data" action="<?php echo site_url($this->project . '/get_report/') ?>" method="post" class="form-horizontal form-groups-bordered">                    
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Department<span class="required">*</span></label>

                            <div class="col-sm-5 input-group">
                                <select name="department_id" class="form-control" >
                                    <option value="" >Select Department...</option>                                  
                                    <?php if (!empty($all_department)): foreach ($all_department as $department): ?>
                                            <option value="<?php echo $department->DepartmentID; ?>"
                                            <?php if (!empty($DepartmentID)): ?>
                                                <?php echo $department->DepartmentID == $DepartmentID ? 'selected ' : '' ?>
                                                    <?php endif; ?>>
                                                        <?php echo $department->DepartmentName; ?>
                                            </option>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?> 
                                </select>                            
                            </div>
                        </div>   
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Date <span class="required">*</span></label>
                            <div class="input-group col-sm-5">
                                <input type="text" autocomplete="off" class="form-control  monthyears" value="<?php
                                if (!empty($date)) {
                                    echo date('Y-n', strtotime($date));
                                }
                                ?>" name="date" >
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div>
                        </div>                     
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5 pull-right">
                                <button type="submit" id="sbtn" class="btn btn-primary">Search</button>                            
                            </div>
                        </div>   
                    </form>
                </div>                        
            </div>                        
        </div>                
    </div>   
</div>
<?php if (!empty($attendance)): ?>
<div id="EmpprintReport">
    <div class="show_print" style="width: 100%; border-bottom: 2px solid black;">
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
    </div>
    <br/>
    <br/>
        <?php endif; ?>
    <?php if (!empty($attendance)): ?>
        <div class="std_heading"  style="background-color: rgb(224, 224, 224);margin-bottom: 5px;padding: 5px;">           
            <table style="margin: 3px 10px 0px 24px; width:100%;">                    
                <tr>

                    <td style="font-size: 15px"><strong>Department: </strong><?php echo $dept_name->DepartmentName ?></td>                    
                    <td style="font-size: 15px"><strong>Date:</strong><?php echo $month ?></td>
                </tr>                                      
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12 std_print"> 
                <div class="wrap-fpanel">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><strong>Attendance List </strong>
                                <div class="pull-right hidden-print" >
                                    <!--<a href="<?php echo base_url() ?>admin/attendance/create_pdf/<?php echo $DepartmentID . '/' . $date ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Pdf"><span><i class="fa fa-file-pdf-o"></i></span></a>-->
                                    <!--<a href="<?php echo base_url() ?>admin/attendance/create_excel/<?php echo $DepartmentID . '/' . $date ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a>                                                -->
                                    <!--<button  class="btn-print" title="Print" data-toggle="tooltip" type="button" onclick="printEmp_report('EmpprintReport')">btn</button>                                                              -->
                                </div>
                            </h4>
                        </div>                                                  
                        <table id="example" class="table table-bordered std_table">
                            <thead>
                                <tr>
                                    <th style="width: 100%" class="col-sm-3">Name</th>                

                                    <?php foreach ($dateSl as $edate) : ?>                                
                                        <th class="std_p"><?php echo $edate ?></th>
                                    <?php endforeach; ?>                        

                                </tr>  

                            </thead>      

                            <tbody>

                                <?php foreach ($attendance as $key => $v_employee): ?>
                                    <tr>  

                                        <td style="width: 100%" class="col-sm-2"><?php echo $employee[$key]->name;?></td>   
                                        <?php foreach ($v_employee as $v_result): ?>
                                            <?php foreach ($v_result as $emp_attendance): ?>
                                                <td>
                                                    <?php
                                                    if ($emp_attendance->attendance_status == 1) {
                                                        echo '<span  style="padding:2px; 4px" class="label label-success std_p">P</span>';
                                                    }if ($emp_attendance->attendance_status == '0') {
                                                        echo '<span style="padding:2px; 4px" class="label label-danger std_p">A</span>';
                                                    }if ($emp_attendance->attendance_status == 'H') {
                                                        echo '<span style="padding:2px; 4px" class="label label-info std_p">H</span>';
                                                    }
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>   


                                        <?php endforeach; ?>        
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>
                </div>    
            </div>
        </div>    
    <?php endif; ?>
</div>

<script type="text/javascript">
    function printEmp_report(EmpprintReport) {
        var printContents = document.getElementById(EmpprintReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<script>
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'print','excel'
        ],
         "searching": false,
            "bFiltered": false,

            "filter": false,
        
    } );
} );
</script>

<script>
    $(function() {
        $('.monthyear').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
        });
    });

</script>
<script>
    $(function() {
        $('.monthyears').datepicker({
            autoclose: true,
            format: "yyyy-mm",
             startView: "months",
            minViewMode: "months"

        });
    });

</script>
