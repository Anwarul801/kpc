<?php

// echo "<pre>";
// print_r($employeefield);
// exit();
?>
<?php
if (isset($_POST['employee'])):
    $employeew = $this->input->post('employee');
    $departmentw = $this->input->post('department');
    $designationw = $this->input->post('designation');
   
endif;
?>
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
  display: inline-block;
  font-size: 5px;
}

</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                   Employee General Report
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
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Employee Name </label>
                                    <div class="col-sm-6">

                                        <select class="form-control" id="employee" name="employee">
                                            <option 
                                            <?php
                                                if ($employeew == 'all') {
                                                    echo "selected";
                                                }
                                                ?> value="all">All</option>
                                            <?php foreach ($employee as $key => $value): ?>
                                                <option <?php if (!empty($employeew) && $employeew == $value->id) {
                                                        echo "selected";
                                                    }
                                                    ?>
                                                 value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                                    <div class="col-md-3">
                                         <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Department </label>
                                    <div class="col-sm-6">

                                        <select class="form-control" id="department" name="department">
                                            <option selected value="" disabled>--Select--</option>
                                            <?php foreach ($department as $key => $value): ?>
                                                <option  <?php if (!empty($departmentw) && $departmentw == $value->DepartmentID) {
                                                        echo "selected";
                                                    }
                                                    ?>
                                                value="<?php echo $value->DepartmentID; ?>"><?php echo $value->DepartmentName; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                                    <div class="col-md-3">
                                       <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Designation </label>
                                    <div class="col-sm-6">

                                        <select class="form-control" id="designation" name="designation">
                                            <option selected value="" disabled>--Select--</option>
                                            <?php foreach ($designation as $key => $value): ?>
                                                <option  <?php if (!empty($designationw) && $designationw == $value->DesignationID) {
                                                        echo "selected";
                                                    }
                                                    ?>
                                                value="<?php echo $value->DesignationID; ?>"><?php echo $value->DesignationName; ?></option>
                                            <?php endforeach; ?>
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
                                        <div class="col-sm-6">
                                            <input type="hidden" name="is_print" value="0" id="is_print">
                                            <button type="button" class="btn btn-info btn-sm" id="PRINT"
                                                    style="cursor:pointer;">
                                                <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                                Print
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
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Employee Report
                </div>
            </div>
            <div class="portlet-body">

                <?php if(!empty($getAllEmployeewiseD)) {?>
                    <div class="row">
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                       <strong>Company Name : </strong><?php echo $companyInfo->companyName; ?><br>
                                        <strong>Address : </strong><?php echo $companyInfo->address; ?><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        
                                        <strong>Name Of Report: Employee Data Base</strong>
                                        
                                    </td>
                                    <td></td>
                                </tr>
                            </table>

                            <table id="example" style="width:100%" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th colspan="2" width="36%">Personal Details</th>
                                <th  width="5%">Designation</th>
                                <th width="10%">Employee Type</th>
                                <th width="10%">Account Details</th>
                                <th width="10%">Joining Date</th>
                                
                                <th width="6%">Current Salary</th>
                                <th width="10%">Photos & Signature</th>
                                
                                <th width="8%">HR Comments</th>
                                                           
                            </tr>
                        </thead>
                        <tbody style="font-size: 5px!important">
                            <?php $total = 0;
                            foreach ($getAllEmployeewiseD as $key => $value){
                                $total += $value->salary; ?>

                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td style="border-right: none; ">
                                         <?php 
                                     foreach ($employeefield as $key => $row) {
                                         
                                     
                                 if($row->fieldName =='name') { ?>
                                      <strong><label>Name of Employee: &nbsp; </label></strong> <?php echo $value->name; ?>   </br>
                                      <?php }  
                                     if($row->fieldName =='fathersName') { ?>
                                        <label>Father Name: &nbsp;  </label><?php echo $value->fathersName; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='mothersName') { ?>
                                        <label>Mother Name: &nbsp; </label><?php echo $value->mothersName; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='presentAddress') { ?>
                                        <label>Present Address: &nbsp; </label><?php echo $value->presentAddress; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='spouseName') { ?>
                                        <label>Spouse Name: &nbsp; </label><?php echo $value->spouseName; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='spouseNumber') { ?>
                                        <label>Spouse Number: &nbsp; </label><?php echo $value->spouseNumber; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='emargencyContact') { ?>
                                        <label>Emergency Contact: &nbsp; </label><?php echo $value->emargencyContact; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='dateOfBirth') { ?>
                                        <label>Date of Birth: &nbsp; </label><?php echo $value->dateOfBirth; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='nationalId') { ?>
                                        <label>NID: </label><?php echo $value->nationalId; ?> </br>
                                          <?php } } ?>
                                    </td>
                                    <td> <?php foreach ($employeefield as $key => $row) {   
                                     if($row->fieldName =='emailAddress') { ?>
                                        <label>Email: &nbsp; </label><?php echo $value->emailAddress; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='personalMobile') { ?>
                                        <label>Personal Mobile: &nbsp; </label><?php echo $value->personalMobile; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='res') { ?>
                                        <label>Res: &nbsp; </label><?php echo $value->res; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='officeMobile') { ?>
                                        <label>Work Mobile: &nbsp; </label><?php echo $value->officeMobile; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='gender') { ?>
                                        <label>Gender: &nbsp; </label><?php echo $value->gender; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='religion') { ?>
                                        <label>Religion: &nbsp; </label><?php echo $value->religionName; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='maritalStatus') { ?>
                                        <label>Marital Status: &nbsp; </label><?php echo $value->maritalStatus; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='bloodGroup') { ?>
                                        <label>Blood Group: &nbsp; </label><?php echo $value->bloodName; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='education') { ?>
                                        <label>Latest Education: &nbsp; </label><?php echo $value->education; ?> </br>
                                    <?php } }?>
                                </td>
                                     <td>
                                        <?php foreach ($employeefield as $key => $row) {  
                                     if($row->fieldName =='department') { ?>
                                      <label>Designation:</label> <?php echo $value->DesignationName; ?> </br>  
                                      <?php }  
                                     if($row->fieldName =='designation') { ?>
                                       <label>Department:</label><?php echo $value->DepartmentName; ?> </br> 
                                   <?php } }?>
                                    </td>
                                    <td>
                                        <label>Identification: </label></br>
                                        <?php foreach ($employeefield as $key => $row) { 
                                     if($row->fieldName =='employeeId') { ?>
                                        <label>Employee ID: &nbsp; </label><?php echo $value->employeeId; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='employeeType') { ?>
                                        <label>Employee Type: &nbsp; </label><?php echo $value->employeeType; ?> </br>
                                    <?php } }?>
                                    </td>
                                    <td>
                                        <label>Account Details: &nbsp; </label></br>
                                        <?php  foreach ($employeefield as $key => $row) {
                                     if($row->fieldName =='AccountName') { ?>
                                        <label>Account Name: &nbsp; </label> </br>
                                        <?php }  
                                     if($row->fieldName =='bankName') { ?>
                                         <label>Bank Name: &nbsp; </label></br>
                                         <?php }  
                                     if($row->fieldName =='AccountNo') { ?>
                                        <label> Account No: &nbsp; </label><?php echo $value->AccountNo; ?> </br>
                                        <?php }  } ?>
                                    </td>
                                    <td>
                                         <label>Performance: &nbsp; </label></br>
                                         <?php   foreach ($employeefield as $key => $row) {
                                     if($row->fieldName =='joiningDate') { ?>
                                        <label>Joining Date: &nbsp; </label><?php echo $value->joiningDate; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='Assessement') { ?>
                                        <label>Last Assessement: &nbsp; </label>
                                    <?php }   }?>
                                    </td>
                                    <?php foreach ($employeefield as $key => $row) { 
                                     if($row->fieldName =='salary') { ?>
                                    <td><?php echo $value->salary; ?></td>
                                    <?php }  } ?>

                                    <td><?php foreach ($employeefield as $key => $row) { 
                                     if($row->fieldName =='profile') { ?>
                                        <img src="<?php echo base_url('uploads/employee/' . $value->profile); ?>" class="thumbnail" height="100px" width="100%"> </br>
                                        <?php }  
                                     if($row->fieldName =='Signature') { ?>
                                       
                                    <?php } }?>
                                    </td>
                                    <?php foreach ($employeefield as $key => $row) { 
                                     if($row->fieldName =='others') { ?>

                                    <td><?php echo $value->others; ?></td>
                                    <?php } }?>
                                    
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
            <tr>
                <th colspan="7" style="text-align:right">Grand Total: </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
                    </table>
                        </div>
                    </div>

<?php }?>
                   
            </div>
        </div>
    </div>
</div>
<script>

        var form = $('#publicForm'); // contact form
    var submit = $('#PRINT');  // submit button
    submit.on('click', function (e) {
        e.preventDefault(); // prevent default form submit
        $("#is_print").val(1);
        form.attr('target', '_blank');
        form.submit();
        $("#is_print").val(0);
        form.attr('target', '');
    });
 $(document).ready(function() {

    $('#example').DataTable( {
        "paging": false,
        "pagingType": "full_numbers",
        "pageLength":"All",  
        "columnDefs": [{ "width": "5%" }, { "width": "30%" }, { "width": "10%" }, { "width": "10%" }, { "width": "10%" }, { "width": "10%" }, { "width": "15%" }, { "width": "10%" }], 
         "autoWidth": false,
            "ordering": false,
        //   "scrollY":        true,
        // "scrollX":        true,
         "scrollCollapse": true,
       
         
         
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
          

            // Total filtered rows on the selected column (code part added)
            var sumCol4Filtered = display.map(el => data[el][7]).reduce((a, b) => intVal(a) + intVal(b), 0 );
          
            // Update footer
            $( api.column( 7 ).footer() ).html(
                'BDT:'+pageTotal +' ( total)'
            );
        },
        
       
    } );
} );
</script>