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

    .po_td {
        width: 8%;
        text-align: center;
    }
    .po_date {
        width: 5%;
        text-align: center;
    }
    .po_product {
        width: 10%;
        text-align: center;
    }
    .po_unit_price_td {
        width: 3%;
        text-align: center;
    }
    .po_total_price_td {
        width: 3%;
        text-align: center;
    }
    .po_issue_qty_td {
        width: 3%;
        text-align: center;
    }
    .po_qty_td {
        width: 3%;
        text-align: center;
    }
    .po_total_action_td {
        width: 2%;
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

                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Supplier ID <span style="color:red;"> &nbsp*</span></label>

                    <div class="col-sm-6" id="customer_div">

                        <select id="supplier_id" name="supplier_id" class="chosen-select form-control"
                                onchange="get_supplier_customer_details(this.value,1)"
                                data-placeholder="Search by Supplier ID or Name" required>

                            <option></option>

                            <?php foreach ($supplierList as $key => $each_info): ?>
                                <option
                                       <?php if ($sdc_mrn_info->supplier_id == $each_info->sup_id) {
                                    echo "selected";
                                    }
                                    ?>
                                        value="<?php echo $each_info->sup_id; ?>">
                                    <?php echo $each_info->supName . '&nbsp&nbsp[ ' . $each_info->supID . ' ] '; ?></option>
                            <?php endforeach; ?>

                        </select>

                    </div>


                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right"
                           for="form-field-1"><?php echo get_phrase("material received note no") ?> <span style="color:red;"> &nbsp*</span></label>

                    <div class="col-sm-7">

                        <input class="form-control " name="sdc_mrn_no" id="" type="text" value="<?php echo $sdc_mrn_info->sdc_mrn_no?>"
                               autocomplete="off" readonly="true" required/>

                    </div>

                </div>


            </div>
            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-4 control-label no-padding-right" for="so_id"> Purchase Order ID <span style="color:red;"> &nbsp*</span></label>

                    <div class="col-sm-6" id="poIdDiv">
                        <select id="poId" name="so_id" onchange="getSodetails(this.value)"
                                class="chosen-select form-control"
                                data-placeholder="Select  Purchase Order " multiple="multiple">
                            <option value=""></option>


                        </select>


                    </div>

                </div>

            </div>


            <div class="col-md-6">
                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Branch <span style="color:red;"> &nbsp*</span></label>

                    <div class="col-sm-7">

                        <select name="branch_id" class="chosen-select form-control" onchange="load_po_ides()"
                                id="branch_id" data-placeholder="Select Branch">
                            <option value=""></option>
                            <?php
                            echo branch_dropdown(null, $sdc_mrn_info->branch_id);
                            ?>


                        </select>

                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Recived Date <span style="color:red;"> &nbsp*</span></label>

                    <div class="col-sm-6">
                        <div class="input-group">
                            <input class="form-control date-picker-sales" name="mrn_recived_date"
                                   id="mrn_recived_date" type="text"
                                   value="<?php echo date('d-m-Y', strtotime($sdc_mrn_info->mrn_recived_date)); ?>"
                                   data-date-format="dd-mm-yyyy" autocomplete="off" required/>
                            <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>

                        </div>
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
                        <th style="width:10%;text-align: center">
                            PO Id
                        </th>
                        <th style="width:10%;text-align: center">
                            PO Date
                        </th>
                        <th nowrap style=";border-radius:10px;text-align: center">
                            <strong><?php echo get_phrase('Category') ?>
                                <span
                                        style="color:red;"> *</span></strong>
                        </th>
                        <th nowrap style="text-align: center" id="product_th">
                            <strong> <?php echo get_phrase('Product') ?>
                                <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap
                            style="text-align: center;border-radius:10px;<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_1; ?> </strong>
                        </th>
                        <th nowrap
                            style="text-align: center;border-radius:10px;<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?> ">
                            <strong><?php echo $property_2; ?> </strong>

                        </th>
                        <th nowrap
                            style="text-align: center;border-radius:10px; <?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_3; ?> </strong>
                        </th>
                        <th nowrap
                            style="text-align: center;border-radius:10px; <?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_4; ?> </strong>
                        </th>
                        <th nowrap
                            style="text-align: center;border-radius:10px;<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_5; ?> </strong>
                        </th>
                        <th style="white-space:nowrap;  vertical-align:top;">

                            <strong><?php echo get_phrase('PO_Quantity') ?> <span
                                        style="color:red;"> *</span></strong></th>

                        </th>
                        <th style="white-space:nowrap; vertical-align:top;">

                            <strong><?php echo get_phrase('Issue_Quantity') ?> <span
                                        style="color:red;"> *</span></strong></th>

                        </th>
                        <th nowrap><strong><?php echo get_phrase('Unit_Price') ?>(<?php echo get_phrase('BDT') ?>) <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap><strong><?php echo get_phrase('Total Price') ?>(<?php echo get_phrase('BDT') ?>)
                                <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap style="width: 15%">Narration</th>
                        <th style='text-align: center;'><strong><?php echo get_phrase('Action') ?></strong></th>
                    </tr>

                    </thead>
                    <tbody>


                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="<?php echo $number_of_property + 5 ?>">
                            <textarea style="border:none;" cols="120"
                                      class="form-control" name="narration"
                                      placeholder="Narration......"
                                      type="text"></textarea>
                        </td>
                        <td align="right">
                            <strong>Total Qty</strong>
                        </td>
                        <td align="right"><strong id="total_qty"> </strong></td>
                        <td align="right"><strong id="">Grand Total </strong></td>
                        <td align="right"><strong id="total_price"></strong></td>
                        <td></td>
                        <td></td>

                    </tr>

                    </tfoot>

                </table>


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

        get_supplier_customer_details("<?php echo $sdc_mrn_info->supplier_id ?>", 1);
        var tour = new Tour({
            debug: true,
            steps: [
                {
                    element: "#customer_div",
                    title: "select A Customer name",
                    /*content: "After Customer "*/
                },
                {
                    element: "#poIdDiv",
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
                null,
                null,
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
                    load_po_ides(sup_cus_id, 1);
                }
            });

        }


        $('#poId').chosen();
        $('#poId option').remove();
        $("#poId").trigger("chosen:open");
        //event.stopPropagation();
        $('#poId').append("");
        $("#poId").trigger("chosen:updated");


    }

    function load_po_ides(sup_cus_id, type) {
        $("#poIdDiv").LoadingOverlay("show");
        var sup_cus_id = sup_cus_id;
        var type = type;
        var branch_id = $("#branch_id").val();

        if (sup_cus_id == "") {
            swal("Select Customer Name!", "Validation Error!", "error");
            return false;
        } else if (branch_id == "") {
            swal("Select Branch Name!", "Validation Error!", "error");
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: baseUrl + "lpg/PurchaseOrderController/load_so_po_ides_by_supplier_customer",
                data: {
                    sup_cus_id: sup_cus_id,
                    type: type,
                    branch_id: branch_id,
                },
                success: function (data) {

                    console.log('load_po_ides');
                    console.log(data);
                    $('#poId').chosen();
                    $('#poId option').remove();
                    $("#poId").trigger("chosen:open");
                    //event.stopPropagation();
                    $('#poId').append($(data));
                    $("#poId").trigger("chosen:updated");
                }, complete: function () {
                    $("#poIdDiv").LoadingOverlay("hide", true);
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





    function getSodetails(po_ides) {
        var po_ides = [];
        $.each($("#poId option:selected"), function () {
            po_ides.push($(this).val());
        });

console.log('po_ides');
console.log(po_ides);
        $("#show_item").dataTable().fnDestroy();
        if (po_ides.length >0) {
            alert('OKKK');
            var url = baseUrl + "lpg/MaterialReceivedController/get_so_po_product_for_mrn_sdc";
            var table = $('#show_item').DataTable({
                "paging": false,
                "processing": false,
                "serverSide": false,
                "order": [],
                "ordering": false,

                "info": false,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    url: url,
                    "type": "POST",
                    "data": function (data) {
                        data.ides = JSON.stringify(po_ides);
                        data.customerid = 0;
                        data.form_id = 8;
                        data.supplier_id = $('#supplier_id').val();
                        data.branch_id = $('#branch_id').val();

                    }
                },
                /*"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],*/
                "columns": [
                    {
                        "bVisible": false, "aTargets": [0]
                    },
                    {"sWidth": "400px", "sClass": "po_td"},
                    {"sWidth": "400px", "sClass": "po_date"},

                    /* null,*/

                    null,
                    {"sWidth": "400px", "sClass": "po_product"},
                    null,




                    {"sWidth": "400px", "sClass": "po_qty_td"},//po_qty_td
                    {"sWidth": "400px", "sClass": "po_issue_qty_td"},//po_issue_qty_td
                    {"sWidth": "400px", "sClass": "po_issue_qty_td"},//po_issue_qty_td
                    {"sWidth": "400px", "sClass": "po_unit_price_td"},//po_total_price_td
                    {"sWidth": "400px", "sClass": "po_total_price_td"},//po_total_price_td
                    null,
                    {"sWidth": "400px", "sClass": "po_total_action_td"},

                    /*  {
                          // render action button
                          mRender: function (data, type, row) {
                              var bindHtml = '';
                              bindHtml += ' <input class="form-check-input checkbox_for_return" name="sales_details_id[]" type="checkbox" value="' + row[0] + '" id="defaultCheck1' + row[0] + '">';
                              return bindHtml;
                          }
                      },*/


                ], "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData[0]);
                }

            });


        }else{
            var table =$('#show_item').DataTable({
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
                    null,
                    null,
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
            table
                .clear()
                .draw();
        }


    }

    function load_so_product(so_id) {


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
            $('#so_po_issue_qty_' + id).attr("readonly", false);
            $('#one_line_narration_' + id).attr("readonly", false);
            // $('#unit_price_' + id).attr("readonly", false);
        } else {
            $('#so_po_issue_qty_' + id).attr("readonly", true);
            $('#one_line_narration_' + id).attr("readonly", true);
            //$('#unit_price_' + id).attr("readonly", true);
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
                var quantity = $('#so_po_issue_qty_' + id).val();
                var price = $('#tt_price_' + id).val();
                quantity = Number(quantity);
                total_quantity += quantity;
                price = Number(price);
                total_price += price;

            }
        });

        $('#total_price').html(parseFloat(total_price).toFixed(2));
        $('#total_qty').html(parseFloat(total_quantity).toFixed(2));
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
        var supplier_id = $("#supplier_id").val();
        var mrn_recived_date = $("#mrn_recived_date").val();
        var so_delivery_date = $("#so_delivery_date").val();
        var branchName = $("#branchName").val();

        var rowCount = $('#show_item tbody tr').length;

        var totalPrice = parseFloat($("#total_price").html());
        if (isNaN(totalPrice)) {
            totalPrice = 0;
        }


        if (supplier_id == '') {

            swal("Select Supplier Name!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (mrn_recived_date == '') {
            swal("Select Recived Date!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (totalPrice == '' || totalPrice < 0) {
            swal("Add PO Item!", "Validation Error!", "error");
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


