<?php
/**
 * Created by PhpStorm.
 * User: AEL
 * Date: 6/23/2020
 * Time: 12:18 PM
 */
?>

<style type="text/css">
    table tr td {
        margin: 0px !important;
        padding: 1px !important;
    }

    table tr td tfoot .form-control {
        width: 100%;
        height: 34px;
    }

    .table-fixed thead {
        width: 90%;

    }

    .table-fixed tbody {
        height: 120px;
        overflow-y: auto;
        width: 100%;
    }

    .table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
        display: block;
    }

    .table-fixed tbody td, .table-fixed thead > tr > th {
        float: left;
        border-bottom-width: 0;
    }
    .mrn_price {
        width: 3%;
        text-align: center;
    }
    .action_td {
        width: 3%;
        text-align: center;
    }
    .table_td_style{
        text-align: center !important;
        vertical-align: inherit !important;
    }
    .mrn_oneline_narration{
        width: 40%;
        text-align: center;
    }
</style>

<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);
$salesInvoiceCreditLimitBlock = 4000;

$number_of_property = 0;

if ($property_1 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_2 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_3 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_4 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_5 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}

?>


<div class="row">

    <form id="publicForm" action="" method="post" class="form-horizontal">
        <button id="initiate_tour" type="button" class="btn btn-xs green">Show suggation</button>
        <div class="col-md-12">


            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Supplier ID</label>
                    <div class="col-sm-6" id="customer_div">
                        <select id="supplier_id" name="supplier_id" class="chosen-select form-control"
                                onchange="get_supplier_customer_details(this.value,1)"
                                data-placeholder="Search by Supplier ID or Name">
                            <option></option>
                            <?php foreach ($supplierList as $key => $each_info): ?>
                                <option value="<?php echo $each_info->sup_id; ?>">
                                    <?php echo $each_info->supName . '&nbsp&nbsp[ ' . $each_info->supID . ' ] '; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Branch</label>

                    <div class="col-sm-7">

                        <select name="branch_id" class="chosen-select form-control" onchange="load_so_ides()"
                                id="branch_id" data-placeholder="Select Branch">
                            <option value=""></option>
                            <?php
                            echo branch_dropdown(null, null);
                            ?>


                        </select>

                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"
                           for="so_id"> <?php echo get_phrase("material received note no") ?></label>
                    <div class="col-sm-6" id="soIdDiv">
                        <select id="soId" name="so_id" onchange="load_sdc_mrn_product(this.value)"
                                class="chosen-select form-control"
                                data-placeholder="Select  Purchase Order " multiple="multiple">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>


        </div>
        <div class="clearfix"></div>

        <div class="col-md-12">
            <div class="clearfix"></div>
            <div class="col-md-12 ">
                <div class="table-header">
                    PO Item
                </div>


                <table class="table table-bordered table-hover tableAddItem" id="show_item">
                    <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th style="width:6%;text-align: center">
                            MRN Id
                        </th>
                        <th style="width:6%;text-align: center">
                            MRN Date
                        </th>


                        <th nowrap><strong><?php echo get_phrase('Total Price') ?>(<?php /*echo get_phrase('BDT') */ ?>)
                                <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap>Narration</th>
                        <th style='text-align: center;'><strong><?php echo get_phrase('Action') ?></strong></th>
                    </tr>

                    </thead>
                    <tbody>


                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="<?php /*echo $number_of_property + 5 */ ?>">
                            <textarea style="border:none;" cols="120"
                                      class="form-control" name="narration"
                                      placeholder="Narration......"
                                      type="text"></textarea>
                        </td>
                        <td align="right">

                        </td>
                        <td align="right"><strong id="">Grand Total </strong></td>
                        <td align="right"><strong id="total_price"> </strong></td>
                        <td align="right"><strong id=""></strong></td>
                        <td></td>

                    </tr>

                    </tfoot>

                </table>
            </div>

            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label no-padding-right" for="so_date"> PO
                                    No</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" name="userInvoiceId" value=""
                                               class="form-control"
                                               placeholder="Invoice No"
                                               autocomplete="off"/>
                                        <span class="input-group-addon" style="background-color:#fff">
                            <?php echo $voucherID ?>
                                            <input type="hidden" id="" name="voucherid" readonly
                                                   value="<?php echo $voucherID ?>"/>
                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-4 control-label formfonterp" for="form-field-1"> <span
                                            style="color:red;">*</span><strong><?php echo get_phrase('Purchases Invoice Date') ?></strong></label>

                                <div class="col-sm-6" id="">

                                    <div class="input-group">
                                        <input class="form-control date-picker-sales" name="purchasesDate"
                                               id="purchasesDate" type="text"
                                               value="<?php echo date('d-m-Y'); ?>"
                                               data-date-format="dd-mm-yyyy" autocomplete="off"/>
                                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>
                  <!--  <div class="col-md-12">
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-4 control-label formfonterp" for="form-field-1"> <span
                                            style="color:red;">*</span><strong><strong><?php /*echo get_phrase('Delivery_Date') */?></strong></strong></label>

                                <div class="col-sm-6">

                                    <div class="input-group">
                                        <input class="form-control date-picker"
                                               value="<?php /*echo date('d-m-Y'); */?>"
                                               data-date-format="dd-mm-yyyy" autocomplete="off"
                                               placeholder="Delivery Date"
                                               name="delivery_date"/>
                                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-4 control-label formfonterp" for="form-field-1"> <span
                                            style="color:red;">*</span><strong><strong><?php /*echo get_phrase('Due_Date') */?>
                                            :</strong></strong></label>

                                <div class="col-sm-6">

                                    <div class="input-group">
                                        <input class="form-control date-picker" name="creditDueDate"
                                               style=""
                                               type="text" value="<?php /*echo date('d-m-Y'); */?>"
                                               data-date-format="dd-mm-yyyy"
                                               autocomplete="off"/>
                                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>-->
                    <div class="col-md-12">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-4 control-label formfonterp" for="form-field-1"> <span
                                            style="color:red;">*</span><strong><strong><?php echo get_phrase('Payment_Type') ?></strong></strong></label>

                                <div class="col-sm-6">

                                    <div class="input-group">
                                        <select onchange="showBankinfo(this.value)" name="paymentType"
                                                class="chosen-select form-control"
                                                id="paymentType" data-placeholder="Select Payment Type">
                                            <option></option>
                                            <!--<option value="1">Full Cash</option>-->
                                            <option selected
                                                    value="4"><?php echo get_phrase('Cash') ?></option>
                                            <option value="2"><?php echo get_phrase('Credit') ?></option>
                                            <option value="3"><?php echo get_phrase('Cheque_DD_PO') ?></option>
                                        </select>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group partisals">

                                <label class="col-sm-4 control-label formfonterp" for="form-field-1"> <span
                                            style="color:red;">*</span><strong><strong><?php echo get_phrase('Account') ?></strong></strong></label>

                                <div class="col-sm-6">

                                    <div class="input-group">
                                        <select style="width:100%!important;" name="accountCrPartial"
                                                class="chosen-select   checkAccountBalance chosenRefesh"
                                                id="partialHead"
                                                data-placeholder="Select Account Head">
                                            <option value=""></option>
                                            <?php
                                            foreach ($accountHeadList as $key => $head) {

                                                ?>
                                                <optgroup
                                                        label="<?php echo get_phrase($head['parentName']); ?>">
                                                    <?php
                                                    foreach ($head['Accountledger'] as $key2 => $eachLedger) :
                                                        /*log_message('error','this is the account hade list'.print_r($eachLedger["parent_name"],true));*/
                                                        ?>
                                                        <option <?php
                                                        if ($head['parent_id'] == '28') {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $eachLedger["id"]; ?>"><?php echo get_phrase($eachLedger["parent_name"]) . " ( " . $eachLedger["code"] . " ) "; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                                <?php

                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-md-12" id="showBankInfo" style="display:none;">

                        <div class="col-md-4">
                            <div class="form-group" style="">
                                <label class="col-sm-3 control-label formfonterp" for="bankName">
                                    <strong><?php echo get_phrase('Bank_A/C') ?></strong></label>
                                <div class="col-sm-7">
                                    <!--onchange="getBankBranch(this.value)"-->
                                    <select name="bankName" class=" form-control" id="bankName"
                                            data-placeholder="Bank Account  Name">
                                        <?php
                                        echo bank_account_info_dropdown();
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!--<div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-3 control-label formfonterp"
                           style="white-space: nowrap;"><strong><?php /*echo get_phrase('Branch_Name') */ ?></strong></label>
                    <div class="col-sm-7">
                        <select name="branchName" class=" form-control" id="branchName"
                                data-placeholder="Bank Name">
                            <option value=""></option>
                            <?php /*foreach ($bankList as $key => $value): */ ?>
                                <option value="<?php /*echo $value->bank_info_id; */ ?>"><?php /*echo $value->bank_name; */ ?></option>
                            <?php /*endforeach; */ ?>
                        </select>

                    </div>
                </div>
            </div>-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 formfonterp"
                                       style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check_No') ?></strong></label>
                                <div class="col-sm-8">
                                    <input type="text" value="" class="form-control" id="checkNo"
                                           name="checkNo"
                                           placeholder="Check NO" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-4  formfonterp"
                                       style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check_Date') ?></strong></label>
                                <div class="col-sm-8">
                                    <input class="form-control date-picker" name="checkDate"
                                           name="purchasesDate" id="checkDate"
                                           type="text" value="<?php echo date('d-m-Y'); ?>"
                                           data-date-format="dd-mm-yyyy"/>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for=""> <strong><?php echo get_phrase('Discount') ?>
                                    (-)
                                    :</strong></label>
                            <div class="col-sm-6">
                                <input type="text" onkeyup="calDiscount()" id="disCount" name="discount" value=""
                                       onclick="this.select();"
                                       autocomplete="off"
                                       style="text-align: right" class="form-control" placeholder="0.00"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong>
                                    <?php echo get_phrase('Grand_Total') ?>
                                    :
                                </strong>
                            </label>
                            <div class="col-sm-6">
                                <input readonly id="grandTotal" type="text" name="grandtotal" value=""
                                       style="text-align: right"
                                       class="form-control" placeholder="0.00"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong><?php echo get_phrase('Loader') ?>
                                    (+):</strong>
                            </label>
                            <div class="col-sm-6">
                                <select name="loader" class="chosen-select form-control" id="form-field-select-3"
                                        data-placeholder="Select  Loader">
                                    <option></option>
                                    <?php foreach ($employeeList as $key => $eachEmp): ?>
                                        <option value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->name . '   &nbsp&nbsp[' . $eachEmp->personalMobile . ']'; ?></option>
                                        <!--<option value="<?php /* echo $eachEmp->id; */ ?>"><?php /* echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; */ ?></option>-->
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong><?php echo get_phrase('Loader Amount') ?>
                                    (+):</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" id="loader" onkeyup="calcutateFinal()" style="text-align: right"
                                       onclick="this.select();"
                                       name="loaderAmount" value="" class="form-control" placeholder="0.00"
                                       autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong data-toggle="tooltip"
                                        title="<?php echo get_phrase('Transportation') ?> (+) :"><?php echo get_phrase('Trans') ?>
                                    ..(+) :</strong>
                            </label>
                            <div class="col-sm-6">
                                <select name="transportation" class="chosen-select form-control"
                                        id="form-field-select-3"
                                        data-placeholder="Select Transportation">
                                    <option></option>
                                    <?php foreach ($vehicleList as $key => $eachVehicle): ?>
                                        <option value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong data-toggle="tooltip"
                                ><?php echo get_phrase('Transportation Amount') ?>
                                    ..(+) :</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" id="transportation" onkeyup="calcutateFinal()"
                                       style="text-align: right"
                                       onclick="this.select();"
                                       name="transportationAmount" value="" class="form-control" placeholder="0.00"
                                       autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong data-toggle="tooltip"
                                ><?php echo get_phrase('Net_Total') ?>
                                    :</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" id="net_total" onkeyup="calcutateFinal()" style="text-align: right"
                                       onclick="this.select();"
                                       name="netTotal" value="" class="form-control" placeholder="0.00"
                                       autocomplete="off"/>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong><?php echo get_phrase('Payment') ?>
                                    :</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" id="payment" onkeyup="calculatePartialPayment()"
                                       onclick="this.select();"
                                       style="text-align: right"
                                       autocomplete="off"
                                       name="partialPayment" value="" class="form-control" autocomplete="off"
                                       placeholder="0.00"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label " for="">
                                <strong><?php echo get_phrase('Due') ?>
                                    :</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" id="due_amount" onclick="this.select();"
                                       style="text-align: right"
                                       autocomplete="off"
                                       name="" value="" class="form-control" autocomplete="off" readonly
                                       placeholder="0.00"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-12">
                    </div>
                </div>


            </div>
            <div class="clearfix"></div>
            <div class="clearfix form-actions">
                <div class="col-md-6 col-md-offset-5">
                    <button onclick="return isconfirm2()" id="subBtn" class="btn blue" type="button"
                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        <?php echo get_phrase('Save') ?>
                    </button>

                </div>
            </div>

        </div>


    </form>
</div>


<script>


    $(document).ready(function () {


        var tour = new Tour({
            debug: true,
            steps: [
                {
                    element: "#customer_div",
                    title: "select A Customer name",
                    /*content: "After Customer "*/
                },
                {
                    element: "#soIdDiv",
                    title: "Select a SO",
                    /*content: "Content of my step"*/
                }, {
                    element: "#show_item",
                    title: "select SO Product ",
                    /*content: "Content of my step"*/
                }, {
                    element: "#sales_details",
                    title: "Give sales Details ",
                    /*content: "Content of my step"*/
                }
            ]
        });

// Initialize the tour
        tour.init();

// Start the tour
        tour.start();


        $("#initiate_tour").click(function () {
            tour.init();
            tour.restart();
        });

        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

        var table;
        window.table = $('#show_item').DataTable({
            "ordering": false,
            "paging": false,
            "processing": false,
            "info": false,
            "columns": [
                {
                    "bVisible": false, "aTargets": [0]
                },
                null,
                null,
                null,
                null,
                null,


                /*  {
                      // render action button
                      mRender: function (data, type, row) {
                          var bindHtml = '';
                          bindHtml += ' <input class="form-check-input checkbox_for_return" name="sales_details_id[]" type="checkbox" value="' + row[0] + '" id="defaultCheck1' + row[0] + '">';
                          return bindHtml;
                      }
                  },*/


            ],
        });

    });


    function get_supplier_customer_details(sup_cus_id, type) {
        var sup_cus_id = sup_cus_id;
        var type = type;
        $("#customerid").closest('form').find("input[type=text], textarea").val("");

        $("#show_item tbody").html('');
        $('#total_qty').html('');
        $('#total_price').html('');
        $('#partialHead').val('').trigger('chosen:updated');


        if (sup_cus_id != "") {

            var url = baseUrl + "lpg/SalesController/getCustomerCurrentBalance";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    customerId: sup_cus_id
                },
                success: function (data) {

                    var data = JSON.parse(data);
                    console.log(data);


                }, complete: function () {
                    load_so_ides(sup_cus_id, type);
                }
            });

        }


        $('#soId').chosen();
        $('#soId option').remove();
        $("#soId").trigger("chosen:open");
        //event.stopPropagation();
        $('#soId').append("");
        $("#soId").trigger("chosen:updated");


    }

    function load_so_ides(sup_cus_id, type) {
        $("#soIdDiv").LoadingOverlay("show");
        var sup_cus_id = sup_cus_id;
        var type = type;
        var branch_id = $("#branch_id").val();

        if (sup_cus_id == "") {
            swal("Select Supplier Name!", "Validation Error!", "error");
            return false;
        } else if (branch_id == "") {
            swal("Select Branch Name!", "Validation Error!", "error");
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: baseUrl + "lpg/MaterialReceivedController/load_mrn_sdc_ides_by_supplier_customer",
                data: {
                    sup_cus_id: sup_cus_id,
                    type: type,
                    branch_id: branch_id,
                },
                success: function (data) {

                    console.log('load_so_ides');
                    console.log(data);
                    $('#soId').chosen();
                    $('#soId option').remove();
                    $("#soId").trigger("chosen:open");
                    //event.stopPropagation();
                    $('#soId').append($(data));
                    $("#soId").trigger("chosen:updated");
                }, complete: function () {
                    $("#soIdDiv").LoadingOverlay("hide", true);
                }

            });
        }

    }


    var incriment = 1;
    var salesRateLock;
    window.salesRateLock = '<?php echo check_parmission_by_user_role(3002)?>';


    function checkSalesRateLockPermission() {

        if (salesRateLock != 0) {

            $(".rate").attr("readonly", true);
            $(".add_rate").attr("readonly", true);
        } else {
            $(".rate").attr("readonly", false);
            $(".add_rate").attr("readonly", false);
        }
    }

    function getSodetails(so_id) {
        console.log('nahid');


    }

    function load_sdc_mrn_product(so_id) {

        var mrn_sdc = [];
        $.each($("#soId option:selected"), function () {
            mrn_sdc.push($(this).val());
        });


        $("#show_item").dataTable().fnDestroy();
        if (mrn_sdc.length > 0) {

            var table = $('#show_item').DataTable({
                "paging": false,
                "processing": false,
                "serverSide": false,
                "order": [],
                "ordering": false,
                "info": false,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    url: "<?php echo site_url('lpg/MaterialReceivedController/get_mrn_sdc_info'); ?>",
                    "type": "POST",
                    "data": function (data) {

                        data.ides = JSON.stringify(mrn_sdc);
                        data.customerid = 0;
                        data.form_id = 32;
                        data.supplier_id = $('#supplier_id').val();
                        data.branch_id = $('#branch_id').val();
                    }
                },
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "columns": [
                    {
                        "bVisible": false, "aTargets": [0]
                    },

                    {"sWidth": "400px", "sClass": "table_td_style"},
                    {"sWidth": "400px", "sClass": "table_td_style"},
                    {"sWidth": "400px", "sClass": "mrn_price"},
                    {"sWidth": "400px", "sClass": "mrn_oneline_narration"},



                    {"sWidth": "400px", "sClass": "action_td"},

                ], "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData[0]);
                }

            });


        }

    }


    function productItemValidation(msg) {


        swal({

                title: msg,

                //text: "You won't be able to revert this!",

                showCancelButton: true,

                confirmButtonColor: '#73AE28',

                //cancelButtonColor: '#d33',

                confirmButtonText: 'Yes',

                //cancelButtonText: "No",

                closeOnConfirm: true,

                //closeOnCancel: false,

                type: 'warning'

            },

            function (isConfirm) {

                if (isConfirm) {

                    //$("#publicForm").submit();

                } else {

                    // return false;

                }

            });

    }


    $(document).on('click', '.checkbox_so_po_product', function () {
        var id = $(this).val();
        if ($(this).prop("checked") == true) {
            $('#one_line_narration_' + id).attr("readonly", false);
        } else {
            $('#one_line_narration_' + id).attr("readonly", true);
        }

        calculateTotal();

    });
    $(document).on('keyup', '.so_po_issue_qty', function () {
        var quantity = parseFloat($(this).val());

        var id = $(this).attr('attr-so-po-product-row-id');
        var actual_quantity = parseFloat($('#so_quantity_' + id).val());

        if (actual_quantity < quantity) {
            $(this).val(actual_quantity);
        }
        calculateRowTotal(id);

    });

    function calculateRowTotal(id) {
        var unit_price = parseFloat($('#unit_price_' + id).val());
        var quantity = parseFloat($('#so_po_issue_qty_' + id).val());
        var tt_price = unit_price * quantity;
        console.log('LLLL');
        console.log(unit_price);
        console.log(quantity);

        $('#tt_price_' + id).val(tt_price);

        setTimeout(function () {
            calculateTotal();

        }, 100);


    };

    function calculateTotal() {
        var total_quantity = 0;
        var total_price = 0;


        $.each($('.checkbox_so_po_product '), function () {
            var id = $(this).val();

            if ($(this).prop("checked") == true) {

                var price = $('#unit_price_' + id).val();

                price = Number(price);
                total_price += price;

            }
        });

        $('#total_price').html(parseFloat(total_price).toFixed(2));

        setTimeout(function () {
            calcutateFinal();

        }, 120);

    }

    function calcutateFinal() {

        console.log('from calcutateFinal');

        var total_price = parseFloat($("#total_price").html());
        if (isNaN(total_price)) {
            total_price = 0;
        }
        var loader = parseFloat($("#loader").val());
        if (isNaN(loader)) {
            loader = 0;
        }
        var transportation = parseFloat($("#transportation").val());
        if (isNaN(transportation)) {
            transportation = 0;
        }

        var discount = parseFloat($("#disCount").val());
        if (isNaN(discount)) {
            discount = 0;
        }
        var Net_total_price = (total_price + transportation + loader) - discount;

        var payment = parseFloat($("#payment").val());
        if (isNaN(payment)) {
            payment = 0;
        }


        $("#grandTotal").val(parseFloat((total_price - discount)).toFixed(2));
        $("#net_total").val(parseFloat((Net_total_price)).toFixed(2));


        calculatePartialPayment();
        //checkCustomerCreditLimit();

    }

    function calculatePartialPayment() {
        var net_total = parseFloat($("#net_total").val());
        var payment = parseFloat($("#payment").val());
        if (isNaN(net_total)) {
            net_total = 0;
        }
        if (isNaN(payment)) {
            payment = 0;
        }
        $("#due_amount").val(parseFloat((net_total - payment)).toFixed(2));


    }

    function isconfirm2() {
        $('#subBtn').prop('disabled', true);
        $('#subBtn').button('loading');
        var customerid = $("#customerid").val();
        var so_date = $("#so_date").val();
        var so_delivery_date = $("#so_delivery_date").val();
        var branchName = $("#branchName").val();

        var rowCount = $('#show_item tbody tr').length;

        var totalPrice = parseFloat($("#total_price").html());
        if (isNaN(totalPrice)) {
            totalPrice = 0;
        }


        if (customerid == '') {

            swal("Select Customer Name!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (so_date == '') {
            swal("Select SO Date!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (totalPrice == '' || totalPrice < 0) {
            swal("Add SO Item!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (rowCount < 1) {
            swal("Add Product!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else {
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
                    } else {
                        $('#subBtn').prop('disabled', false);
                        $('#subBtn').button('reset');
                        return false;
                    }
                });
        }
    }
</script>


