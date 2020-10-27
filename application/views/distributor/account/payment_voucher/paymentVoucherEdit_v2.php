<style>

    table tbody tr td {

        margin: 1px !important;

        padding: 1px !important;

    }

    table tfoot tr td {

        margin: 1px !important;

        padding: 1px !important;

    }

    table tbody tr td {

        margin: 1px !important;

        padding: 1px !important;

    }

</style>

<div class="main-content">

    <div class="main-content-inner">


        <div class="page-content">

            <div class="row">

                <div class="col-md-12">

                    <form id="publicForm" action="" method="post" class="form-horizontal">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date<span
                                            style="color:red;"> *</span></label>

                                <div class="col-sm-6">

                                    <div class="input-group">

                                        <input class="form-control date-picker" name="date" id="paymentDate" type="text"
                                               value="<?php echo date('d-m-Y', strtotime($editVoucher->Accounts_Voucher_Date)); ?>"
                                               data-date-format="dd-mm-yyyy"/>

                                        <span class="input-group-addon">

                                            <i class="fa fa-calendar bigger-110"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher
                                    ID<span style="color:red;"> *</span></label>

                                <div class="col-sm-6">

                                    <input type="text" id="form-field-1" name="voucherid" readonly
                                           value="<?php echo $editVoucher->Accounts_Voucher_No; ?>" class="form-control"
                                           placeholder="Voucher ID"/>

                                </div>

                            </div>

                        </div>

                        <div class="clearfix"></div>



                        <div class="clearfix"></div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Pay. From (
                                    CR) <span style="color:red;">*</span></label>

                                <div class="col-sm-6">

                                    <select name="accountCr" class="chosen-select form-control  checkAccountBalance"
                                            id="payForm" data-placeholder="Search by Account Head">

                                        <option value=""></option>

                                        <?php

                                        foreach ($accountHeadList as $key => $head) {

                                            ?>

                                            <optgroup label="<?php echo $head['parentName']; ?>">

                                                <?php

                                                foreach ($head['Accountledger'] as $eachLedger) :

                                                    //if (($eachLedger->chartId == '54' && $eachLedger->parentId == '42') || $eachLedger->parentId == '55'):

                                                    ?>

                                                    <option <?php

                                                    if ($getCreditAccountId == $eachLedger['id']) {

                                                        echo "selected";

                                                    }

                                                    ?> value="<?php echo $eachLedger['id']; ?>"><?php echo $eachLedger['parent_name'] . " ( " . $eachLedger['code'] . " ) "; ?></option>

                                                    <?php

                                                    //endif;

                                                endforeach;

                                                ?>

                                            </optgroup>

                                            <?php

                                        }

                                        ?>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <span id="accountBalance"></span>

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"
                                       for="form-field-1">Brance </label>
                                <div class="col-sm-6">


                                    <select name="BranchAutoId" class="chosen-select form-control"
                                            id="" data-placeholder="Search by payee">
                                        <option value=""></option>
                                        <?php
                                        echo branch_dropdown(null, $editVoucher->BranchAutoId);
                                        ?>



                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-10 col-md-offset-1">

                            <br>

                            <div class="table-header">

                                Select Account Head

                            </div>

                            <table class="table table-bordered table-hover" id="show_item">

                                <thead>

                                <tr>

                                    <td style="width:40%" align="center"><strong>Account Head<span
                                                    style="color:red;"> *</span></strong></td>

                                    <td style="width:20%" align="center"><strong>Amount<span
                                                    style="color:red;"> *</span></strong></td>

                                    <td style="width:20%" align="center"><strong>Memo</strong></td>

                                    <td style="width:20%" align="center"><strong>Action</strong></td>

                                </tr>
                                <tr id="loding_icon">

                                    <td id="account_ledger">

                                        <!--<select class="chosen-select form-control paytoAccount" id="form-field-select-3"
                                                data-placeholder="Search by Account Head"
                                                onchange="check_pretty_cash(this.value)">

                                            <option value=""></option>

                                            <?php
/*

                                            foreach ($accountHeadList as $key => $head) {

                                                */?>

                                                <optgroup label="<?php /*echo $head['parentName']; */?>">

                                                    <?php
/*
                                                    foreach ($head['Accountledger'] as $eachLedger) :


                                                        //if (($eachLedger->chartId == '54' && $eachLedger->parentId == '42') || $eachLedger->parentId == '55'):

                                                        */?>

                                                        <option paytoAccountCode="<?php /*echo $eachLedger->code; */?>"
                                                                paytoAccountName="<?php /*echo $eachLedger->parent_name; */?>"
                                                                value="<?php /*echo $eachLedger->id; */?>"><?php /*echo $eachLedger->parent_name . " ( " . $eachLedger->code . " ) "; */?></option>

                                                        <?php
/*
                                                        //  endif;

                                                    endforeach;

                                                    */?>

                                                </optgroup>

                                                <?php
/*
                                            }

                                            */?>

                                        </select>-->

                                    </td>

                                    <td><input type="text" class="form-control text-right amountt amount3"
                                               onkeyup="checkOverAmount(this.value)"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               placeholder="0.00"></td>

                                    <td><input type="text" class="form-control text-right memo" placeholder="Memo"></td>

                                    <td><a id="add_item" class="btn btn-info form-control" href="javascript:;"
                                           title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>

                                </tr>
                                </thead>

                                <tbody>

                                <?php


                                $totalAmount = 0;
                                // echo '<pre>';
                                // print_r($getDebitAccountId);


                                foreach ($getDebitAccountId as $key => $eachAccount):

                                    $chartInfo = $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $eachAccount->CHILD_ID);

                                    $totalAmount += $eachAccount->GR_DEBIT;

                                    ?>


                                    <tr class="new_item<?php echo $key + 611 ?>">

                                        <td style="padding-left:15px;"><?php echo $chartInfo->parent_name . ' [ ' . $chartInfo->code . ' ]'; ?>
                                            <input type="hidden" name="accountDr[]"
                                                   value="<?php echo $eachAccount->CHILD_ID; ?>"></td>

                                        <td style="padding-left:15px;" align="right"><input
                                                    class="amount amount3 text-right form-control" type="text"
                                                    name="amountDr[]" value="<?php echo $eachAccount->GR_DEBIT; ?>">
                                        </td>

                                        <td align="right"><input type="text"
                                                                 class="add_quantity form-control text-right"
                                                                 name="memoDr[]"
                                                                 value="<?php echo $eachAccount->Reference; ?>"></td>

                                        <td><a del_id="<?php echo $key + 611 ?>"
                                               class="delete_item btn form-control btn-danger" href="javascript:;"
                                               title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>

                                    </tr>

                                    <?php

                                endforeach;

                                ?>

                                </tbody>

                                <tfoot>



                                <tr>

                                    <td align="right"><strong>Sub-Total(BDT)</strong></td>

                                    <td align="right"><strong
                                                class="tttotal_amount"><?php echo $totalAmount; ?></strong></td>


                                    <td align="right"><strong class=""></strong></td>

                                    <td></td>

                                </tr>

                                </tfoot>

                            </table>

                        </div>

                        <div class="col-md-10">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                    Narration</label>

                                <div class="col-sm-9">

                                    <div class="input-group">

                                        <textarea cols="100" rows="2" name="narration" placeholder="Narration"
                                                  type="text"><?php echo $editVoucher->Narration?></textarea>


                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="clearfix form-actions">

                            <div class="col-md-offset-3 col-md-9">

                                <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">

                                    <i class="ace-icon fa fa-check bigger-110"></i>

                                    Update

                                </button>


                                <button class="btn" type="reset">

                                    <i class="ace-icon fa fa-undo bigger-110"></i>

                                    Reset

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.page-content -->

</div>


<script>
    function selectledgerid(ele) {
        var ledger_id = $('.chosenRefesh option:selected').attr('attr-ledger-id');
        $('.paytoAccount ').val(ledger_id);
        $('.paytoAccount').trigger("chosen:updated");


    }
    var url = '<?php echo site_url("FinaneController/getPayUserListForUpdate") ?>';

    $.ajax({

        type: 'POST',

        url: url,

        data: {
            'payid': $('#payType').val(), payUserId: '<?php

                if (!empty($editVoucher->miscellaneous)) {
                    echo $editVoucher->miscellaneous;
                } elseif (!empty($editVoucher->customer_id)) {
                    echo $editVoucher->customer_id;
                } else {
                    echo $editVoucher->supplier_id;
                }

                ?>'
        },

        success: function (data) {

            $("#searchValue").html(data);

            $("#oldValue").hide(1000);

            $('.chosenRefesh').chosen();

            $(".chosenRefesh").trigger("chosen:updated");

        }

    });

    function isconfirm2() {


        var paymentDate = $("#paymentDate").val();

        var payType = $("#payType").val();


        var miscellaneous = $("#miscellaneous").val();

        var customerId = $("#customerId").val();

        var supplierId = $("#supplierId").val();

        var payForm = $("#payForm").val();


        var totalPrice = parseFloat($(".tttotal_amount").text());

        if (isNaN(totalPrice)) {

            totalPrice = 0;

        }


        if (payType == '') {

            swal("Select Payment Type!", "Validation Error!", "error");


        } else if (paymentDate == '') {

            swal("Select Payment Date!", "Validation Error!", "error");

        } else if (payType == 1 && miscellaneous == '') {

            swal("Please Type Account Name", "Validation Error!", "error");

        } else if (payType == 2 && customerId == '') {

            swal("Select Customer Name", "Validation Error!", "error");

        } else if (payType == 3 && supplierId == '') {

            swal("Please Select Supplier!", "Validation Error!", "error");

        } else if (payForm == '') {

            swal("Please Select Payfrom", "Validation Error!", "error");

        } else if (totalPrice == '') {

            swal("Please Select Account Head!", "Validation Error!", "error");

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

                        return false;

                    }

                });


        }


    }


    $(document).on("keyup", ".amount", function () {


        findAmount();

    });


</script>


<script>

    $("#subBtn").attr('disabled', false);


    $(document).ready(function () {

       var  payUserId= '<?php

            if (!empty($editVoucher->miscellaneous)) {

                echo $editVoucher->miscellaneous;

            } elseif (!empty($editVoucher->customer_id)) {

                echo $editVoucher->customer_id;

            } else {

                echo $editVoucher->supplier_id;

            }

            ?>'

        selectPayType(payUserId);
        $('.checkAccountBalance').change(function () {

            var accountId = $(this).val();

            // alert(accountId);

            $.ajax({

                type: 'POST',

                data: {account: accountId},

                url: '<?php echo site_url('FinaneController/checkBalanceForPayment'); ?>',

                success: function (result) {


                    $('#accountBalance').html('');

                    $('#accountBalance').html(result);

                }

            });

        });

    });


    function checkOverAmount(amount) {

        var total_amount = 0;

        $.each($('.amount3'), function () {

            quantity = $(this).val();

            quantity = Number(quantity);

            total_amount += quantity;

        });

        if (closeAmount < total_amount) {

            $("#subBtn").attr('disabled', true);

            productItemValidation("Account Balance Not Available!");

            this.value = '';

            $(this).val(0);

        } else {

            $("#subBtn").attr('disabled', false);

        }

    }


    function selectPayType(payid) {
        //alert('OK');

        var url2 = '<?php echo site_url("FinaneController/getPayUserList") ?>';
        /*$.ajax({
            type: 'POST',
            url: url,
            data: {'payid': payid},
            success: function (data) {
                $("#searchValue").html(data);
                $("#oldValue").hide(1000);
                $('.chosenRefesh').chosen();
                $(".chosenRefesh").trigger("chosen:updated");
            }
        });*/

        /*$("#loding_icon").LoadingOverlay("show", {
            background  : "rgba(230, 227, 227, 0.8) !important"
        });*/
        $("#loding_icon").LoadingOverlay("show");
        var url = baseUrl + "lpg/VoucherController/load_account_ledgers";
        var group;
        var selectedLedgerId="";
        var ledgerFor="paymentVoucherAdd";
        if (payid == "3") {
            //supplier
            group = "<?php echo $this->config->item("Supplier_Payables");?>";

        } else if (payid == "2") {
            //customer
            group = "<?php echo $this->config->item("Customer_Receivable");?>";
        } else {
            //account
            group = "all";

        }


        $.ajax({
            type: 'POST',
            url: url,
            data: {'ledgerFor':ledgerFor,'group': group, 'ledgerId': selectedLedgerId},
            success: function (data) {

                $("#account_ledger").html(data);

                $('.chosenRefesh').chosen();
                $(".chosenRefesh").trigger("chosen:updated");
            }, complete: function () {
                //setTimeout(function(){
                $("#loding_icon").LoadingOverlay("hide");
                // }, 3000);
            }

        });

    }


    function saveNewSupplier() {

        var url = '<?php echo site_url("SetupController/saveNewSupplier") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: $("#publicForm2").serializeArray(),

            success: function (data) {

                $('#myModal').modal('toggle');

                $('#hideNewSup').hide(1000);

                $('#supplierid').chosen();

                //$('#customerid option').remove();

                $('#supplierid').append($(data));

                $("#supplierid").trigger("chosen:updated");

            }

        });

    }


    function checkDuplicatePhone(phone) {

        var url = '<?php echo site_url("SetupController/checkDuplicateEmail") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: {'phone': phone},

            success: function (data) {

                if (data == 1) {

                    $("#subBtn2").attr('disabled', true);

                    $("#errorMsg").show(1000);

                } else {

                    $("#subBtn2").attr('disabled', false);

                    $("#errorMsg").hide(1000);

                }

            }

        });


    }


</script>


<script type="text/javascript">


    $('.amountt').change(function () {

        $(this).val(parseFloat($(this).val()).toFixed(2));

    });

    var findAmount = function () {

        var ttotal_amount = 0;

        $.each($('.amount'), function () {

            amount = $(this).val();

            if (isNaN(amount)) {

                amount = 0;

            }

            amount = Number(amount);

            ttotal_amount += amount;

        });


        if (ttotal_amount > 0) {

            $("#subBtn").attr('disabled', false);

        }


        $('.tttotal_amount').html(parseFloat(ttotal_amount).toFixed(2));

    };


    $(document).ready(function () {


        $("#add_item").click(function () {

            var accountId = $('.paytoAccount').val();

            var accountName = $(".paytoAccount").find('option:selected').attr('paytoAccountName');

            var accountCode = $(".paytoAccount").find('option:selected').attr('paytoAccountCode');

            var amount = $('.amountt').val();

            var memo = $('.memo').val();


            if (accountId == '' || accountId == null) {

                productItemValidation("Account Head can't be empty.");

                return false;

            } else if (amount == '') {

                productItemValidation("Amount can't be empty.");

                return false;

            } else {

                $("#show_item tbody").append('<tr class="new_item' + accountId + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountDr[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amount amount3 text-right form-control decimal" type="text"  name="amountDr[]" value="' + amount + '"></td><td align="right"><input type="text" class="add_quantity text-right form-control" name="memoDr[]" value="' + memo + '"></td><td><a del_id="' + accountId + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');

            }

            $('.amountt').val('');

            $('.memo').val('');

            $('.paytoAccount').val('').trigger('chosen:updated');

            // $(".paytoAccount").trigger("chosen:updated");

            findAmount();


        });

        $(document).on('click', '.delete_item', function () {


            var id = $(this).attr("del_id");

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

                        $('.new_item' + id).remove();

                        findAmount();

                    } else {

                        return false;

                    }

                });


        });

    });

</script>

<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>





