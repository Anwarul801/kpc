<?php
// echo '<pre>';
// print_r($getAllsalaryByDateListView);
// exit();
?>
<style>
    body {
    color: #333;
    padding: 0!important;
    margin: 0!important;
    direction: "ltr";
    font-size: 11px;
}
table td{
     text-align: right;
}
tfoot tr, thead tr {
    background: lightblue;
}
tfoot td {
    font-weight:bold;
}
div.dataTables_wrapper  div.dataTables_filter {
  width: 100% !important;
  float: none;
  text-align: left;
}
.page {
    width:100%;
    min-height: 100%;
    padding: 20mm;
    margin: 10mm auto;
    border: 1px black solid;
    border-radius: 5px;
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subpage {
    padding: 1cm;
    border: 5px black solid;
    height: 257mm;
    outline: 2cm #FFEAEA solid;
}

@page {
    size: A4;
    size: landscape;
    margin: 0;



}

@media print {
    html, body {
        margin:0 !important;
        padding:0 !important;
        
        zoom: 80%;


    }


    .page .subpage .col-md-12,.col-lg-12, .col-xl-12{
        float:left;
        width:100%;

    }
    .page .subpage {
        padding: 1cm;
        border: 5px black solid;
        height: 257mm;
        width: : 210mm;
        outline: 2cm #FFEAEA solid;
        position:absolute;
    }
    .page {
        visibility: visible;


    }

}

</style>
<div class="main-content">
    <div class="main-content-inner">
<form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <div class="row">
                 <div class="col-md-12">
                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right"
                                           for="form-field-1"> <?php echo get_phrase('Date') ?>
                                    </label>
                                    <div class="col-sm-6">

                                        <div class="input-group">
                                            <input class="form-control date-picker" name="date"
                                                   id="Date" type="text" value="<?php echo $getAllsalaryByDateListView[0]->date; ?>"
                                                   data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                     <div class="col-sm-4">
                         <div class="form-group">

                         <label class="col-sm-4 control-label text-right" for="form-field-1"> Voucher No </label>
                         <div class="col-sm-6">                         <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $getAllsalaryByDateListView[0]->voucherNo; ?>"
                               class="form-control" />
                            </div>
                        </div>
                     </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1"> Month</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="month" name="month" >
                                            <option selected value='' disabled>--Select--</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'January') {
                                            echo "selected";
                                        }
                                        ?> value="January">January</option>
                                            <option
                                            <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'February') {
                                            echo "selected";
                                        }
                                        ?> value="February">February</option>
                                            <option
                                            <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'March') {
                                            echo "selected";
                                        }
                                        ?> value="March">March</option>
                                            <option
                                            <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'April') {
                                            echo "selected";
                                        }
                                        ?> value="April">April</option>
                                            <option
                                            <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'May') {
                                            echo "selected";
                                        }
                                        ?> value="May">May</option>
                                            <option
                                            <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'June') {
                                            echo "selected";
                                        }
                                        ?> value="June">June</option>
                                            <option
                                             <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'July') {
                                            echo "selected";
                                        }
                                        ?> value="July">July</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'August') {
                                            echo "selected";
                                        }
                                        ?> value="August">August</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'September') {
                                            echo "selected";
                                        }
                                        ?> value="September">September</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'October') {
                                            echo "selected";
                                        }
                                        ?> value="October">October</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'November') {
                                            echo "selected";
                                        }
                                        ?> value="November">November</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->month == 'December') {
                                            echo "selected";
                                        }
                                        ?> value="December">December</option>


                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1"> year </label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="year" name="year">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->year == '2020') {
                                            echo "selected";
                                        }
                                        ?> value="2020">2020</option>
                                            <option <?php
                                            if ($getAllsalaryByDateListView[0]->year == '2021') {
                                            echo "selected";
                                        }
                                        ?> value="2021">2021</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
            </div>

        </div>
        <div class="clearfix"></div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">

                       <div class="col-md-12 " >
 <style type="text/css">
    .form-control
    {
        padding: 0px!important;
    }
table{
    font-size: 11px;
}
    
 </style>
<link href="<?php echo base_url('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>

                    <table id="" width="100%"  class="table table-responsive table-striped table-bordered table-hover">
                        <thead class="table-header">
                            <tr>
                                <th >SL No </th>
                                <th > All<input class="float:left" type="checkbox" id="select_all" /> </th>

                                <?php 
                                     foreach ($employeefield as $key => $value) {
                                         
                                if($value->fieldName =='Employee Name') { ?>
                                <th> Name of Employee </th>
                                <?php }  
                                if($value->fieldName =='Designation') { ?>
                                <th> Designation </th>
                                <?php }  
                                if($value->fieldName =='Department/ Section') { ?>
                                <th> Department/ Section </th>
                                <?php }  
                                if($value->fieldName =='Payment Mode') { ?>
                                <th>Payment Mode</th>
                                <?php }  
                                if($value->fieldName =='Basic Salary') { ?>
                                <th> Basic Salary </th>
                                <?php }  
                                if($value->id =='12') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php } 
                                if($value->id =='13') { ?>
                                <th> <?php echo $value->fieldName; ?></th>
                                <?php }  
                                if($value->id =='14') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php }  
                                if($value->id =='15') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php } 
                                if($value->fieldName =='Gross Salary') { ?>
                                <th> Gross Salary </th>
                                <?php } 
                                if($value->id =='17') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php } 
                                 if($value->id =='18') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php } 
                                if($value->id =='19') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php } 
                                if($value->id =='20') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php }  
                                if($value->id =='21') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php }  
                                if($value->id =='22') { ?>
                                <th> <?php echo $value->fieldName; ?> </th>
                                <?php }  
                                if($value->id =='23') { ?>
                                <th><?php echo $value->fieldName; ?> </th>
                               
                                <?php }  
                                if($value->fieldName =='Net Pay Amount') { ?>
                                <th> Net Pay Amount </th>
                                 <?php }  
                                if($value->id =='25') { ?>
                                <th><?php echo $value->fieldName; ?> </th>
                                 <?php }  }?>


                            </tr>
                        </thead>
                        <tbody>
                                     <?php $basic=0;
                                           $house =0;
                                     foreach ($getAllsalaryByDateListView as $key => $value): ?>
                                     <?php
                                          $salary = $value->salary;
                                          $basic =  $value->salary*0.6;
                                          $house = $value->salary*0.6*0.5;
                                          $convenyence = $value->salary*0.6*0.5*0.1;
                                          $medical = 500;
                                          $totalper = ($basic+$house+$convenyence+ $medical);
                                          $others = ($salary - $totalper );

                                     ?>

                                <tr>
                                    <td style="width: 2%">
                                        <?php echo $key + 1; ?>

                                    </td>
                                    <td style="width: 2%">
                                       <input type="checkbox" name="employeeCheckBox[]" id="checkbox" checked="checked" class="checkbox float:left" />

                                    </td>
                                     <?php 
                                     foreach ($employeefield as $key => $row) {
                                        if($row->fieldName =='Employee Name') { ?>
                                    <td style="width: 8%">
                                           <?php echo $value->name; ?>
                                            <input type="hidden" id="employeeID" name="employeeID[]" value="<?php echo $value->id; ?>" />

                                    </td>
                                <?php }  
                                if($row->fieldName =='Designation') { ?>
                                    <td style="width: 8%">
                                       <input type="hidden" id="designation" name="designation[]" value="<?php echo $value->designation; ?>" class="form-control"placeholder="" style="text-align: right;"/>
                                           <?php echo $value->DesignationName; ?>
                                    </td>
                                    <?php }  
                                if($row->fieldName =='Department/ Section') { ?>
                                    <td style="width: 5%">
                                       <input type="hidden" id="departmentID" name="departmentID[]" value="<?php echo $value->department; ?>" class="form-control"placeholder="" style="text-align: right;"/>
                                           <?php echo $value->DepartmentName; ?>
                                    </td>
                                     <?php }  
                                if($row->fieldName =='Payment Mode') { ?>

                                    <td style="width: 5%">

                                        <input type="hidden" id="paymentMode" name="paymentMode[]" value="<?php echo $value->paymentMode; ?>" class="form-control"
                                               placeholder="" style="text-align: right;"/>
                                           <?php echo $value->paymentMode; ?>

                                    </td>
                                    <?php }  
                                if($row->fieldName =='Basic Salary') { ?>
                                    <td style="width: 8%">
                                        <input type="text" readonly id="basicSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="basicSalary[]" value="<?php echo $value->basicSalary; ?>" class="form-control basicSalary "placeholder=""
                                               onclick="this.select();" style="text-align: right;"/>
                                    </td>
                                    <?php }  
                                if($row->id =='12') { ?>
                                    <td style="width: 5%">
                                        <input type="text" readonly id="houseRant_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>"  name="houseRant[]" value="<?php echo $value->houseRant; ?>" class="form-control houseRant"placeholder=""
                                               onclick="this.select();" style="text-align: right;"/>
                                    </td>
                                     <?php } 
                                if($row->id =='13') { ?>
                                    <td style="width: 5%">
                                        <input type="text" readonly id="conveyanceAllowance_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="conveyanceAllowance[]" value="<?php echo $value->conveyanceAllowance; ?>" class="form-control conveyanceAllowance" placeholder="" style="text-align: right;"/>
                                    </td>

                                     <?php }  
                                if($row->id =='14') { ?>
                                    <td style="width: 5%"> <input readonly type="text" id="medicalAllowance_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="medicalAllowance[]" value="<?php echo $value->medicalAllowance; ?>" class="form-control medicalAllowance"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->id =='15') { ?>
                                    <td style="width: 8%"><input readonly type="text" id="others_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="others[]" value="<?php echo  $value->others;  ?>" class="form-control others"placeholder="" style="text-align: right;"/></td>
                                    <?php } 
                                if($row->fieldName =='Gross Salary') { ?>
                                    <td style="width: 8%"> <input type="text" id="grossSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="grossSalary[]" value="<?php echo  $value->grossSalary;  ?>" class="form-control grossSalary"placeholder="" style="text-align: right;"/></td>
                                    <?php } 
                                if($row->id =='17') { ?>
                                    <td style="width: 5%"><input type="text" id="arrearSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="arrearSalary[]" value="<?php echo $value->arrearSalary; ?>" class="form-control arrearSalary"placeholder="" style="text-align: right;"/></td>
                                    <?php } 
                                if($row->id =='18') { ?>
                                    <td style="width: 5%"><input type="text" id="overTimeHour_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="overTimeHour[]" value="<?php echo $value->overTimeHour; ?>" class="form-control overTimeHour"placeholder="" style="text-align: right;"/></td>
                                        <?php } 
                                if($row->id =='19') { ?>
                                    <td style="width: 5%"><input type="text" id="overTimeRate_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="overTimeRate[]" value="<?php echo $value->overTimeRate; ?>" class="form-control overTimeRate"placeholder="" style="text-align: right;"/></td>
                                     <?php }  
                                if($row->id =='20') { ?>
                                    <td style="width: 5%"><input type="text" id="wPFDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="wPFDeduction[]" value="<?php echo $value->wPFDeduction; ?>" class="form-control wPFDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->id =='21') { ?>
                                    <td style="width: 5%"><input type="text" id="absentDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="absentDeduction[]" value="<?php echo $value->absentDeduction; ?>" class="form-control absentDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->id =='22') { ?>

                                    <td style="width: 5%"><input type="text" id="loanDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="loanDeduction[]" value="<?php echo $value->loanDeduction; ?>" class="form-control loanDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->id =='23') { ?>
                                    <td style="width: 5%"><input type="text" id="aITDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="aITDeduction[]" value="<?php echo $value->aITDeduction; ?>" class="form-control aITDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->fieldName =='Net Pay Amount') { ?>
                                    <td style="width: 8%"><input type="text" id="netPayAmount_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="netPayAmount[]" value="<?php echo  $value->netPayAmount;  ?>" class="form-control netPayAmount"placeholder=""
                                               onclick="this.select();" style="text-align: right;"/></td>
                                               <?php } if($row->id =='25') { ?>
                                    <td style="width: 5%"><input type="text"  name="comment[]" value="<?php echo  $value->comment;  ?>" class="form-control comment"placeholder="comment" style="text-align: right;"/></td>
                                    <?php }  }?>

                                </tr>

                                <?php endforeach; ?>
                                


                        </tbody>
                    </table>


                        </div>


                        <div class="col-sm-12">
                            <div class="clearfix form-actions" >
                                <div class="col-md-2">


                                </div>
                                <div class="col-md-2">

                                          Narration: <input type="text" id="" name="narration" value="<?php echo $getAllsalaryByDateListView[0]->narration ?>" class="form-control"placeholder="" style="text-align: right;"/>
                                </div>
                                <div class="col-md-offset-5 col-md-8">
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
                        <div class="invoice-block pull-right">
                                     <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                       onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                        <i class="fa fa-print"></i>
                                    </a>
                         </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </form>
    </div><!-- /.page-content -->
</div>




<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });

    $("#datepicker").datepicker( {
    format: "mm-yyyy",
    viewMode: "months",
    minViewMode: "months"
});




</script>

<script type="text/javascript">
$(document).ready(function(){
    $( ".basicSalary" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});

$( ".houseRant" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".conveyanceAllowance" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".medicalAllowance" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".others" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".wPFDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".grossSalary" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".arrearSalary" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".absentDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".loanDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".aITDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".overTimeHour" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".overTimeRate" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});




function calCUlation(id){

    var basicSalary=parseFloat($('#basicSalary_'+id).val()).toFixed(2);
    basicSalary = isNaN(basicSalary) ? 0 : basicSalary;
    var houseRant=parseFloat($('#houseRant_'+id).val()).toFixed(2);
     houseRant = isNaN(houseRant) ? 0 : houseRant;
    var conveyanceAllowance=parseFloat($('#conveyanceAllowance_'+id).val()).toFixed(2);
    conveyanceAllowance = isNaN(conveyanceAllowance) ? 0 : conveyanceAllowance;
    var medicalAllowance=parseFloat($('#medicalAllowance_'+id).val()).toFixed(2);
    medicalAllowance = isNaN(medicalAllowance) ? 0 : medicalAllowance;
    var wPFDeduction=parseFloat($('#wPFDeduction_'+id).val()).toFixed(2);
    wPFDeduction = isNaN(wPFDeduction) ? 0 : wPFDeduction;
    var grossSalary=parseFloat($('#grossSalary_'+id).val()).toFixed(2);
    grossSalary = isNaN(grossSalary) ? 0 : grossSalary;
    var overTimeHour=parseFloat($('#overTimeHour_'+id).val()).toFixed(2);
    overTimeHour = isNaN(overTimeHour) ? 0 : overTimeHour;
    var overTimeRate=parseFloat($('#overTimeRate_'+id).val()).toFixed(2);
    overTimeRate = isNaN(overTimeRate) ? 0 : overTimeRate;
    var arrearSalary=parseFloat($('#arrearSalary_'+id).val()).toFixed(2);
    arrearSalary = isNaN(arrearSalary) ? 0 : arrearSalary;
    var absentDeduction=parseFloat($('#absentDeduction_'+id).val()).toFixed(2);
    absentDeduction = isNaN(absentDeduction) ? 0 : absentDeduction;
    var loanDeduction=parseFloat($('#loanDeduction_'+id).val()).toFixed(2);
    loanDeduction = isNaN(loanDeduction) ? 0 : loanDeduction;
    var aITDeduction=parseFloat($('#aITDeduction_'+id).val()).toFixed(2);
    aITDeduction = isNaN(aITDeduction) ? 0 : aITDeduction;
    var others=parseFloat($('#others_'+id).val()).toFixed(2);
    others = isNaN(others) ? 0 : others;


    var overCostTotal = parseFloat(( (overTimeHour*1)*(overTimeRate*1) )).toFixed(2);
      overCostTotal = isNaN(overCostTotal) ? 0 : overCostTotal;
    var total = parseFloat(( (overCostTotal*1) + (houseRant*1)+(conveyanceAllowance*1)+ (medicalAllowance*1)+ (others*1)+ (arrearSalary*1)- (absentDeduction*1) - (loanDeduction*1) - (aITDeduction*1) -(wPFDeduction*1) )).toFixed(2);
    
    $('#netPayAmount_'+id).val(total);

//     var basicSalaryr = 0;
//    for(i=0;i<basicSalary.length; i++){
//        basicSalaryr += basicSalary[i];
//    }
//    $('#salid').val(basicSalaryr);

}



    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });

    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
<script>
 function isconfirm2(){

        var month=$("#month").val();
        //alert(month);
        var year=$("#year").val();
        var employeeCheckBox=$("#checkbox").val();

         var basicSalary=parseFloat($('#basicSalary_').val()).toFixed(2);

        if(month == null){
            swal("Month Can't be empty!", "Validation Error!", "error");
        }else if(year == null){
            swal("year  can't be empty!", "Validation Error!", "error");
        }else if(employeeCheckBox == null){
            swal("Employee CheckBox  can't be empty!", "Validation Error!", "error");
        }else if(basicSalary == ''){
            swal("basicSalary  can't be empty!", "Validation Error!", "error");
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
<script type="text/javascript">

	$(document).ready(function() {
    $('#example').DataTable();
});

//  $(document).ready(function () {
//
//        $('#basicSalary').blur(function () {
//            var basicSalary = parseFloat($(this).val());
//
//            if (isNaN(basicSalary)) {
//                basicSalary = 0;
//            }
//            $(this).val(parseFloat(basicSalary.toFixed(2)));
//
//             $('#basicSalary').keyup(function () {
//            basicSalaryCalIn($h = 0, $bs = basicSalary);
//        });
//
//        });
//
//        $('#houseRant').keyup(function () {
//            basicSalaryCalIn($h = 4, $bs = 0);
//        });
//
//    });
//
//    function basicSalaryCalIn($h, $bs ) {
//
//
//        var basicSalary = $bs;
//        var houseRant = $h;
//
//        var total = parseFloat((basicSalary + houseRant).toFixed(2));
//        console.log(netPayAmount);
//        $('#netPayAmount').val(total);
//    }



</script>

</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>




