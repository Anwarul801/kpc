<?php

?>
<style>
    table {
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Employee Data Base Report
                </div>
            </div>
            <div class="portlet-body">

               
                    <div class="row">
                        <div class="col-xs-12">
                          <?php if(!empty($getAllEmployeewiseD)) {?>
                    <div class="row">
                        <div class="col-xs-12">

                           

                            <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th colspan="2" width="30%">Personal Details</th>
                                <th  width="5%">Designation</th>
                                <th width="10%">Employee Type</th>
                                <th width="10%">Account Details</th>
                                <th width="10%">Joining Date</th>
                                <th width="10%">Current Salary</th>
                                <th width="10%">Photos & Signature</th>
                                <th width="10%">HR Comments</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0;
                            foreach ($getAllEmployeewiseD as $key => $value){
                                $total += $value->salary; ?>

                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td style="border-left: none; ">
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
                                        <label>Religion: &nbsp; </label><?php echo $value->religion; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='maritalStatus') { ?>
                                        <label>Marital Status: &nbsp; </label><?php echo $value->maritalStatus; ?> </br>
                                        <?php }  
                                     if($row->fieldName =='bloodGroup') { ?>
                                        <label>Blood Group: &nbsp; </label><?php echo $value->bloodGroup; ?> </br>
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
                                       <label>Department:</label><?php echo $value->departmentName; ?> </br> 
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
                                        <img src="<?php echo base_url('uploads/employee/' . $value->profile); ?>" class="thumbnail" height="100px" width=""> </br>
                                        <?php }  
                                     if($row->fieldName =='Signature') { ?>
                                        <label>Signature</label>
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
                <th colspan="7" style="text-align:right">Total: </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
                    </table>

                            
                        </div>
                    </div>


                    <?php
                } ?>
            </div>
        </div>
    </div>
</div>
<script>


</script>