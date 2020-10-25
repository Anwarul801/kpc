<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/22/2020
 * Time: 11:31 AM
 */?>

<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);

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
<style xmlns="http://www.w3.org/1999/html">
    .page {
        height: 210mm;
        width: 148mm;
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
        height: 210mm;
        width: 148mm;
        outline: 2cm #FFEAEA solid;
        margin-top: 20px;
    }

    @page {
        size: A5 !important;
        size: portrait !important;
        margin: 0;

    }

    @media print {
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            zoom: 76% !important;
            size: A5 !important;
            size: portrait !important;

        }

        .page .subpage .col-md-12, .col-lg-12, .col-xl-12 {
            float: left;
            height: 210mm !important;
            width: 148mm !important;

        }

        .page .subpage {
            padding: 1cm;
            border: 5px black solid;
            height: 210mm;
            width: 148mm;
            outline: 2cm #FFEAEA solid;
            position: absolute;
        }

        .page {
            visibility: visible;

        }

    }
</style>
<style>
    .page {
        height: 210mm;
        width: 148mm;
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
        height: 210mm;
        width: 148mm;
        outline: 2cm #FFEAEA solid;
        margin-top: 20px;
    }

    @page {
        size: A5 !important;
        size: portrait !important;
        margin: 0;

    }

    @media print {
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            zoom: 76% !important;
            size: A5 !important;
            size: portrait !important;

        }

        .page .subpage .col-md-12, .col-lg-12, .col-xl-12 {
            float: left;
            height: 210mm !important;
            width: 148mm !important;

        }

        .page .subpage {
            padding: 1cm;
            border: 5px black solid;
            height: 210mm;
            width: 148mm;
            outline: 2cm #FFEAEA solid;
            position: absolute;
        }

        .page {
            visibility: visible;

        }

    }
</style>
<style>
    .page {
        height: 210mm;
        width: 148mm;
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
        height: 210mm;
        width: 148mm;
        outline: 2cm #FFEAEA solid;
        margin-top: 20px;
    }

    @page {
        size: A5 !important;
        size: portrait !important;
        margin: 0;

    }

    @media print {
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            zoom: 76% !important;
            size: A5 !important;
            size: portrait !important;

        }

        .page .subpage .col-md-12, .col-lg-12, .col-xl-12 {
            float: left;
            height: 210mm !important;
            width: 148mm !important;

        }

        .page .subpage {
            padding: 1cm;
            border: 5px black solid;
            height: 210mm;
            width: 148mm;
            outline: 2cm #FFEAEA solid;
            position: absolute;
        }

        .page {
            visibility: visible;

        }

    }
</style>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">

                    <span class="caption-subject font-dark sbold uppercase"> <?php echo get_phrase('Sales_delivery_challan') ?>

                    </span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                        <label> <?php echo get_phrase('MRN NO') ?>: <span
                                style="color:red; padding-right:5px"><?php echo $sdc_mrn_info->sdc_mrn_no; ?></span></label>

                    </div>

                </div>
            </div>
            <div class="portlet-body">

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet green-meadow box" style="border: 1px solid #3598dc;">
                                    <div class="portlet-title" style="background-color: #3598dc;">
                                        <div class="caption">
                                            <?php echo get_phrase('SDC Info') ?>
                                        </div>

                                    </div>

                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <h3><?php echo get_phrase('Company Info') ?></h3>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <?php echo get_phrase('Name') ?>
                                                        :<?php echo $companyInfo->companyName; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Branch') ?>
                                                        : <?php echo $this->Common_model->tableRow('branch', 'branch_id', $sdc_mrn_info->branch_id)->branch_name;; ?>
                                                    </li>
                                                    <li style="<?php echo $companyInfo->email != "" ? "" : "display: none" ?>">
                                                        <?php echo get_phrase('Email') ?>
                                                        : <?php echo $companyInfo->email; ?>
                                                    </li>
                                                    <li style="<?php echo $companyInfo->phone != "" ? "" : "display: none" ?>">
                                                        <?php echo get_phrase('Phone') ?>
                                                        : <?php echo $companyInfo->phone; ?>
                                                    </li>
                                                    <!--<li>
                                                        Account Name: FoodMaster Ltd
                                                    </li>-->
                                                    <li style="<?php echo $companyInfo->address != "" ? "" : "display: none" ?>">
                                                        <?php echo get_phrase('Address') ?>
                                                        : <?php echo wordwrap($companyInfo->address, 40, "<br>\n"); ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4">
                                                <h3><?php echo get_phrase('Customer Info') ?></h3>
                                                <ul class="list-unstyled ">
                                                    <li>
                                                        <i></i><?php echo get_phrase('Name') ?>
                                                        : <?php echo $customerInfo->customerName . ' [' . $customerInfo->customerID . ']' ?>
                                                    </li>
                                                    <li>
                                                        <i></i><?php echo get_phrase('Email') ?>: <?php
                                                        if (!empty($customerInfo->customerEmail)) {
                                                            echo $customerInfo->customerEmail;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <i></i><?php echo get_phrase('Phone') ?>: <?php
                                                        if (!empty($customerInfo->customerPhone)) {
                                                            echo $customerInfo->customerPhone;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <i></i><?php echo get_phrase('Address') ?>: <?php
                                                        if (!empty($customerInfo->customerAddress)) {
                                                            echo $customerInfo->customerAddress;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4 invoice-payment">
                                                <h3><?php echo get_phrase('SDC Details') ?></h3>
                                                <ul class="list-unstyled">
                                                    <li>

                                                        <?php echo get_phrase('sdc_delivery_date') ?>:
                                                        <?php
                                                        echo date('d-m-Y', strtotime($sdc_mrn_info->sdc_delivery_date));

                                                        ?>
                                                        </span>

                                                    </li>
                                                    <li>

                                                        <?php echo get_phrase('Status') ?>:
                                                        <?php
                                                        if ($sdc_mrn_info->status == 0) {
                                                            $mrn_status = '<span class=" btn btn-xs red" href="javascript:void(0)">pending</span>';
                                                        } else if ($sdc_mrn_info->status == 1) {
                                                            $mrn_status = '<span class=" btn btn-xs green" href="javascript:void(0)">Approved</span>';
                                                        } else if ($sdc_mrn_info->status == 2) {
                                                            $edit = "";
                                                            $mrn_status = '<span class=" btn btn-xs red" href="javascript:void(0)">Canceled</span>';
                                                        } else if ($sdc_mrn_info->status == 3) {
                                                            $mrn_status = '<span class=" btn btn-xs purple" href="javascript:void(0)">Partially Received</span>';
                                                        } else if ($sdc_mrn_info->status == 4) {
                                                            $delete = "";
                                                            $mrn_status = '<span class=" btn btn-xs blue" href="javascript:void(0)">Complete Received</span>';
                                                        } else if ($sdc_mrn_info->status == 5) {
                                                            $edit = "";
                                                            $mrn_status = '<span class=" btn btn-xs green" href="javascript:void(0)">Payment</span>';
                                                        }
                                                        echo $mrn_status;

                                                        ?>
                                                        </span>

                                                    </li>


                                                </ul>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <td class="center">#</td>
                                                <td style="text-align: center;"><?php echo  get_phrase('SO')?></td>
                                                <td style="text-align: center;"><?php echo get_phrase('Product') . ' ' . get_phrase('Category') ?> </td>
                                                <td style="text-align: center;"><?php echo get_phrase('Product') ?></td>
                                                <th nowrap
                                                    style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_1; ?> </strong>
                                                </th>
                                                <th nowrap
                                                    style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?> ">
                                                    <strong><?php echo $property_2; ?> </strong>

                                                </th>
                                                <th nowrap
                                                    style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_3; ?> </strong>
                                                </th>
                                                <th nowrap
                                                    style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_4; ?> </strong>
                                                </th>
                                                <th nowrap
                                                    style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_5; ?> </strong>
                                                </th>
                                                <td style="text-align: right"><?php echo get_phrase('Narration') ?></td>
                                                <td style="text-align: right"><?php echo get_phrase('Quantity') ?></td>
                                                <td style="text-align: right"><?php echo get_phrase('Unit Price') ?> </td>
                                                <td style="text-align: right"><?php echo get_phrase('Total Price') ?></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $tqty = 0;
                                            $trate = 0;
                                            $tprice = 0;
                                            $j = 1;
                                            foreach ($sdc_mrn_products as $key => $each_info):
                                                $tqty += $each_info['mrn_sdc_qty'];
                                                $trate += $each_info['mrn_sdc_unit_price'];
                                                $tprice += $each_info['mrn_sdc_qty'] * $each_info['mrn_sdc_unit_price'];
                                                ?>
                                                <tr>
                                                    <td style="text-align: center"><?php echo $j++; ?></td>
                                                    <td style="text-align: center"><?php echo $each_info['so_po_no']; ?></td>
                                                    <td style="text-align: center">
                                                        <?php
                                                        echo $each_info['productCat'];
                                                        ?>
                                                    </td>
                                                    <td style="text-align: center">
                                                        <?php
                                                        echo $each_info['productName'] . '' . $each_info['unitTtile'] . '[' . $each_info['brandName'] . ']';
                                                        ?>
                                                    </td>
                                                    <th nowrap
                                                        style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                        <strong><?php echo $each_info['property_1']; ?> </strong>
                                                    </th>
                                                    <th nowrap
                                                        style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?> ">
                                                        <strong><?php echo $each_info['property_2']; ?> </strong>

                                                    </th>
                                                    <th nowrap
                                                        style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                        <strong><?php echo $each_info['property_3']; ?> </strong>
                                                    </th>
                                                    <th nowrap
                                                        style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                        <strong><?php echo $each_info['property_4']; ?> </strong>
                                                    </th>
                                                    <th nowrap
                                                        style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                        <strong><?php echo $each_info['property_5'];; ?> </strong>
                                                    </th>

                                                    <td align="right"><?php echo $each_info['one_line_narration']; ?> </td>
                                                    <td align="right"><?php echo $each_info['mrn_sdc_qty']; ?> </td>
                                                    <td align="right"><?php echo $each_info['mrn_sdc_unit_price']; ?> </td>
                                                    <td align="right"><?php echo number_format($each_info['mrn_sdc_qty'] * $each_info['mrn_sdc_unit_price'], 2); ?> </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="<?php echo $number_of_property + 7 ?>" align="right">
                                                    <strong><?php echo get_phrase('Sub_Total') ?></strong></td>

                                                <td align="right"><input type="hidden" value="<?php echo $tprice?>" id="tt_payable_amount"/> <?php echo number_format($tprice, 2); ?></td>
                                            </tr>


                                            </tfoot>
                                        </table>

                                    </div>
                                    <div class="hr hr8 hr-double hr-dotted"></div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label no-padding-right"
                                                               for="so_date"> Bill
                                                            No</label>
                                                        <div class="col-sm-6">

                                                            <input type="text" id="" name="voucherid" class="form-control " readonly
                                                                   value="<?php echo $mrid ?>"/>



                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="col-md-12">


                                                    <div class="form-group">

                                                        <label class="col-sm-4 control-label formfonterp"
                                                               for="form-field-1"> <span
                                                                style="color:red;">*</span><strong><?php echo get_phrase('Date') ?></strong></label>

                                                        <div class="col-sm-6" id="">

                                                            <div class="input-group">
                                                                <input class="form-control date-picker-sales"
                                                                       name="purchasesDate"
                                                                       id="purchasesDate" type="text"
                                                                       value="<?php echo date('d-m-Y'); ?>"
                                                                       data-date-format="dd-mm-yyyy"
                                                                       autocomplete="off"/>
                                                                <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                                                            </div>

                                                        </div>

                                                    </div>


                                                </div>
                                                <!--<div class="col-md-12">

                                                <div class="form-group">

                                                    <label class="col-sm-4 control-label formfonterp"
                                                           for="form-field-1"> <span
                                                                style="color:red;">*</span><strong><strong><?php /*echo get_phrase('Payment_Type') */ ?></strong></strong></label>

                                                    <div class="col-sm-6">

                                                        <div class="input-group">
                                                            <select onchange="showBankinfo(this.value)"
                                                                    name="paymentType"
                                                                    class="chosen-select form-control"
                                                                    id="paymentType"
                                                                    data-placeholder="Select Payment Type">
                                                                <option></option>

                                                                <option selected
                                                                        value="4"><?php /*echo get_phrase('Cash') */ ?></option>
                                                                <option value="2"><?php /*echo get_phrase('Credit') */ ?></option>
                                                                <option value="3"><?php /*echo get_phrase('Cheque_DD_PO') */ ?></option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>-->
                                                <div class="col-md-12">

                                                    <div class="form-group partisals">

                                                        <label class="col-sm-4 control-label formfonterp"
                                                               for="form-field-1"> <span
                                                                style="color:red;">*</span><strong><strong><?php echo get_phrase('Account') ?></strong></strong></label>

                                                        <div class="col-sm-6">

                                                            <div class="input-group">
                                                                <select style="width:100%!important;"
                                                                        name="accountCrPartial"
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
                                                <div class="col-md-12 showBankInfo" style="display:none;">

                                                    <div class="form-group" style="">
                                                        <label class="col-sm-4 control-label formfonterp"
                                                               for="bankName">
                                                            <strong><?php echo get_phrase('Bank_A/C') ?></strong></label>
                                                        <div class="col-sm-6">
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
                                                <div class="col-md-12 showBankInfo" style="display:none;">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 formfonterp"
                                                               style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check_No') ?></strong></label>
                                                        <div class="col-sm-6">
                                                            <input type="text" value="" class="form-control"
                                                                   id="checkNo"
                                                                   name="checkNo"
                                                                   placeholder="Check NO" autocomplete="off"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 showBankInfo" style="display:none;">
                                                    <div class="form-group">
                                                        <label class="col-sm-4  formfonterp"
                                                               style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check_Date') ?></strong></label>
                                                        <div class="col-sm-6">
                                                            <input class="form-control date-picker" name="checkDate"
                                                                   name="purchasesDate" id="checkDate"
                                                                   type="text" value="<?php echo date('d-m-Y'); ?>"
                                                                   data-date-format="dd-mm-yyyy"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">

                                                    <div class="col-md-4">


                                                    </div>


                                                </div>
                                            </div>
                                            <div class="col-md-6">




                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label " for="">
                                                            <strong><?php echo get_phrase('Payment') ?>
                                                                :</strong>
                                                        </label>
                                                        <div class="col-sm-6">
                                                            <input type="text" id="payment"
                                                                   onkeyup="calculatePartialPayment()"
                                                                   onclick="this.select();"
                                                                   style="text-align: right"
                                                                   autocomplete="off"
                                                                   name="partialPayment" value="" class="form-control"
                                                                   autocomplete="off"
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
                                                                   name="" value="" class="form-control" autocomplete="off"
                                                                   readonly
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
                        </form>
                    </div>

                </div>


            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>
</div>


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script type="text/javascript">

    function calculatePartialPayment(){
        var payment=$('#payment').val();
        var tt_payable_amount=$('#tt_payable_amount').val();
        $('#due_amount').val(tt_payable_amount-payment);

    }

    function showBankinfo(id) {
        $("#payment").val('');


        $(".showBankInfo").hide(10);
        $(".partisals").hide(10);
        $("#payment").prop("readonly", true);

        if (id == 3) {
            $(".showBankInfo").show(10);
        } else if (id == 4) {
            $("#payment").prop("readonly", false);
            $(".partisals").show(10);
        } else if (id == 2) {

        } else {

        }


    }

    function isconfirm2() {
        $('#subBtn').prop('disabled', true);
        $('#subBtn').button('loading');


        if (1 == 2) {

            swal("Select Customer Name!", "Validation Error!", "error");
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


