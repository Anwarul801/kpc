

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Edit Employee</li>
            </ul>

        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Name <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                         <input type="text" id="name" name="name" value="<?php echo $editEmp->name; ?>" class="form-control required" placeholder="Name" />
                                    </div>
                                </div>
                                <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee ID </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="employeeId" name="employeeId"  value="<?php echo $editEmp->employeeId; ?>" class="form-control required" placeholder="Employee ID" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Father's Name </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="form-field-1" name="fathersName"  value="<?php echo $editEmp->fathersName; ?>" class="form-control required" placeholder="Father's Name" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mother's Name </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="form-field-1" name="mothersName"  value="<?php echo $editEmp->mothersName; ?>" class="form-control required" placeholder="Mother's Name"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                               <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Profile</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1"   name="profile" placeholder="Vehicle Model" class="form-control" />
                                        <?php if (!empty($editEmp->profile)): ?>
                                        <input type="hidden" name="oldImage" value="<?php echo $editEmp->profile;?>"/>
                                            <span><img src="<?php echo base_url('uploads/employee/' . $editEmp->profile); ?>" class="thumbnail" height="100px"></span>
                                        <?php else: ?>
                                            <span><i class="fa fa-user" style="font-size:100px"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Present Address <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <textarea type="text" id="presentAddress" name="presentAddress" class="form-control required" placeholder="Present adddress" /><?php echo $editEmp->presentAddress; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Permanent Address </label>
                                    <div class="col-sm-6">
                                       <textarea type="text" id="form-field-1" name="permanentAddress" class="form-control required" placeholder="Permanent adddress" /><?php echo $editEmp->permanentAddress; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                          <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Spouse Name </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="spouseName" name="spouseName" value="<?php echo $editEmp->spouseName; ?>" class="form-control" placeholder="Spouse Name" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Gender </label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="gender">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                        if ($editEmp->gender == 'Male') {
                                            echo "selected";
                                        }
                                        ?> value="Male">Male</option>
                                            <option <?php
                                                if ($editEmp->gender == 'Female') {
                                                    echo "selected";
                                                }
                                        ?> value="Female">FeMale</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Spouse Number </label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="spouseNumber" value="<?php echo $editEmp->spouseNumber; ?>" name="spouseNumber" class="form-control" placeholder="Spouse Number" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Religion</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="religion">
                                            <option selected disabled>--Select--</option>
                                            <?php foreach ($religion as $key => $value): ?>
                                                <option  <?php
                                            if ($editEmp->religion == $value->id) {
                                                echo "selected";
                                            }
                                                ?>  value="<?php echo $value->id; ?>"><?php echo $value->religionName; ?></option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Emergency Contact  <br> <span> (Out site own family)</span></label>

                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="emergencyContact" name="emargencyContact" value="<?php echo $editEmp->emargencyContact; ?>" class="form-control" placeholder="Emergency Contact" />

                                    </div>
                                </div>
                            </div>
                           <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Home District</label>
                                    <div class="col-sm-6">
                                        <select  name="homeDistrict" class="chosen-select form-control"  data-placeholder="Search District" required>
                                            <option></option>
                                            <?php foreach ($districts as $key => $value): ?>
                                                <option  <?php
                                            if ($editEmp->homeDistrict == $value->id) {
                                                echo "selected";
                                            }
                                                ?>   value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date of Birth <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input class="form-control date-picker" name="dateOfBirth" id="dateOfBirth"  type="text"  value="<?php echo $editEmp->dateOfBirth; ?>" data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Marital Status</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="maritalStatus">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                                if ($editEmp->maritalStatus == 'Married') {
                                                    echo "selected";
                                                }
                                                ?> value="Married">Married</option>
                                            <option <?php
                                                if ($editEmp->maritalStatus == 'Unmarried') {
                                                    echo "selected";
                                                }
                                                ?> value="Unmarried">Unmarried</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> National ID <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="20" id="nationalId" value="<?php echo $editEmp->nationalId; ?>"   name="nationalId" placeholder="National ID" class="form-control" />
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Blood Group</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="bloodGroup">
                                            <option selected disabled>--Select--</option>
                                            <?php foreach ($bloodGroup as $key => $value): ?>
                                                <option <?php if($editEmp->bloodGroup == $value->id ){ echo "selected";}?> value="<?php echo $value->id; ?>"><?php echo $value->bloodName; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                    <div class="col-sm-6">
                                        <input type="email" id="form-field-1"   name="emailAddress" value="<?php echo $editEmp->emailAddress; ?>" placeholder="Email" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Type</label>
                                     <div class="col-sm-6">
                                        <select class="form-control" name="employeeType">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                            if ($editEmp->employeeType == 'Staff') {
                                                echo "selected";
                                            }
                                            ?> value="Staff">Staff</option>
                                            <option <?php
                                                if ($editEmp->employeeType == 'Loader') {
                                                    echo "selected";
                                                }
                                            ?> value="Loader">Loader</option>
                                            <option <?php
                                                if ($editEmp->employeeType == 'Driver') {
                                                    echo "selected";
                                                }
                                            ?> value="Driver">Driver</option>

                                        </select>
                                    </div>
                                </div>
                            </div>




                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Personal Mobile <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="personalMobile"   name="personalMobile" value="<?php echo $editEmp->personalMobile; ?>" placeholder="Personal Mobile" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Status</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="empStatus">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                                if ($editEmp->empStatus == 'Active') {
                                                    echo "selected";
                                                }
                                            ?>  value="Active">Active</option>
                                            <option <?php
                                                if ($editEmp->empStatus == 'Inactive') {
                                                    echo "selected";
                                                }
                                            ?>  value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                         <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Res </label>
                                    <div class="col-sm-6">
                                        <input type="text"  id="res"   name="res" value="<?php echo $editEmp->res; ?>" placeholder="res" class="form-control" />
                                    </div>
                                </div>
                            </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Education </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="education" name="education" value="<?php echo $editEmp->education; ?>" class="form-control" placeholder="Education" />
                                    </div>
                                </div>
                            </div>

                        </div>
                         <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Department <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">

                                        <select class="form-control" id="department" name="department">
                                            <option selected disabled>--Select--</option>
                                             <?php foreach ($department as $key => $value): ?>
                                                <option  <?php
                                            if ($editEmp->department == $value->DepartmentID) {
                                                echo "selected";
                                            }
                                                ?>   value="<?php echo $value->DepartmentID; ?>"><?php echo $value->DepartmentName; ?></option>
                                                <?php endforeach; ?>


                                        </select>
                                    </div>
                                </div>
                            </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Others </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="others" name="others" value="<?php echo $editEmp->others; ?>" class="form-control" placeholder="Others" />
                                    </div>
                                </div>
                            </div>

                        </div>
                         <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Designation <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">

                                        <select class="form-control" id="designation" name="designation">
                                            <option selected disabled>--Select--</option>
                                            <?php foreach ($designation as $key => $value): ?>
                                                <option  <?php
                                            if ($editEmp->designation == $value->DesignationID) {
                                                echo "selected";
                                            }
                                                ?>   value="<?php echo $value->DesignationID; ?>"><?php echo $value->DesignationName; ?></option>
                                                <?php endforeach; ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-6">
                               <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">CV Add</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1"   name="cv"  placeholder="Vehicle Model" class="form-control" />
                                        <?php if (!empty($editEmp->cv)): ?>
                                        <input type="hidden" name="oldcv" value="<?php echo $editEmp->cv;?>"/>
                                        <span><?php echo $editEmp->cv;?></span>
                                        <?php else: ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Work Mobile</label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="form-field-1"   name="officeMobile" value="<?php echo $editEmp->officeMobile; ?>" placeholder="work Mobile" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Salary Cash/Bank</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="salaryType">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                                if ($editEmp->salaryType == 'Cash') {
                                                    echo "selected";
                                                }
                                                ?> value="Cash">Cash</option>
                                            <option <?php
                                                if ($editEmp->salaryType == 'Bank') {
                                                    echo "selected";
                                                }
                                                ?> value="Bank">Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Joining Date</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input class="form-control date-picker" value="<?php echo $editEmp->joiningDate; ?>" name="joiningDate"  type="text"  data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank Account No</label>
                                    <div class="col-sm-6">
                                        <input type="text"  id="form-field-1"   name="AccountNo" value="<?php echo $editEmp->AccountNo; ?>" placeholder="Accunt No" class="form-control" />
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Salary <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="salary"   name="salary" value="<?php echo $editEmp->salary; ?>" placeholder="Salary" class="form-control" />
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-sm-12">
                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-5 col-md-10">
                                    <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
 function isconfirm2(){
         var personalMobile=$("#personalMobile").val();
        var nationalId=$("#nationalId").val();
        var presentAddress=$("#presentAddress").val();
        var name=$("#name").val();
        var dateOfBirth=$("#dateOfBirth").val();
        var employeeId=$("#employeeId").val();
        var department=$("#department").val();
        var designation=$("#designation").val();
        var salary=$("#salary").val();

        if(name == ''){
            swal("Name Can't be empty!", "Validation Error!", "error");
        }else if(employeeId == ''){
            swal("Employee Id  can't be empty!", "Validation Error!", "error");
        }else if(presentAddress == ''){
            swal("Present Address can't be empty!", "Validation Error!", "error");
        }else if(dateOfBirth == ''){
            swal("Date of Birth  can't be empty!", "Validation Error!", "error");
        }else if(nationalId == ''){
            swal("National ID Can't be empty", "Validation Error!", "error");
        }else if(personalMobile == ''){
            swal("Personal Mobile number can't be empty", "Validation Error!", "error");
        }else if(department == ''){
            swal("Department  can't be empty!", "Validation Error!", "error");
        }else if(designation == ''){
            swal("Designation  can't be empty!", "Validation Error!", "error");
        }else if(salary == ''){
            swal("salary  can't be empty!", "Validation Error!", "error");
        }else{
            swal({
                title: "Are you sure ?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#73AE28',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
                type: 'success'
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#publicForm").submit();
                }else{
                    return false;
                }
            });
        }
    }
</script>



<script>
     $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
    function checkDuplicateEmail(email){
        var url = '<?php echo site_url("SetupController/checkDuplicateEmailForUser") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'email': email},
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn").attr('disabled',true);
                    $("#errorMsg").show();
                }else{
                    $("#subBtn").attr('disabled',false);
                    $("#errorMsg").hide();
                }
            }
        });

    }
</script>
