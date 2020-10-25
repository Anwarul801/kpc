<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);
$number_of_property = get_number_of_property_in_show_list();

?>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">

                    <span class="caption-subject font-dark sbold uppercase"> <?php echo get_phrase('Purchase Order') ?>

                    </span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                        <label><?php echo get_phrase('purchase_order_No') ?>: <span
                                    style="color:red; padding-right:5px"><?php echo $so_po_info->so_po_no; ?></span></label>

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
                                            <?php echo get_phrase('Purchase Order Info') ?>
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
                                                        : <?php echo $this->Common_model->tableRow('branch', 'branch_id', $so_po_info->branch_id)->branch_name;; ?>
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
                                                <h3><?php echo get_phrase('Supplier Info') ?></h3>
                                                <ul class="list-unstyled ">
                                                    <li>
                                                        <i></i><?php echo get_phrase('Name') ?>
                                                        : <?php echo $supplier->supName . '[' . $supplier->supID . ']' ?>
                                                    </li>
                                                    <li>
                                                        <i></i><?php echo get_phrase('Email') ?>: <?php
                                                        if (!empty($supplier->supEmail)) {
                                                            echo $supplier->supEmail;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <i></i><?php echo get_phrase('Phone') ?>: <?php
                                                        if (!empty($supplier->supPhone)) {
                                                            echo $supplier->supPhone;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <i></i><?php echo get_phrase('Address') ?>: <?php
                                                        if (!empty($supplier->supAddress)) {
                                                            echo $supplier->supAddress;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4 invoice-payment">
                                                <h3><?php echo get_phrase('Purchase Order Info') ?></h3>
                                                <ul class="list-unstyled">
                                                    <li>

                                                        <?php echo get_phrase('Order Date') ?>:
                                                            <?php
                                                            echo date('d-m-Y', strtotime($so_po_info->so_po_date));

                                                            ?>
                                                        </span>

                                                    </li>
                                                    <li>

                                                        <?php echo get_phrase('Delivery Date') ?>:
                                                            <?php
                                                            echo date('d-m-Y', strtotime($so_po_info->delivery_date));

                                                            ?>
                                                        </span>

                                                    </li> <li>

                                                        <?php echo get_phrase('Status') ?>:
                                                            <?php

                                                            if ($so_po_info->status == 0) {
                                                                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">pending</a>';
                                                            } else if ($so_po_info->status == 1) {
                                                                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Approved</a>';
                                                            } else if ($so_po_info->status == 2) {
                                                                $edit = "";
                                                                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">Canceled</a>';
                                                            } else if ($so_po_info->status == 3) {
                                                                $payment_type = '<a class=" btn btn-xs purple" href="javascript:void(0)">Partially Received</a>';
                                                            } else if ($so_po_info->status == 4) {
                                                                $delete="";
                                                                $payment_type = '<a class=" btn btn-xs blue" href="javascript:void(0)">Complete Received</a>';
                                                            } else if ($so_po_info->status == 5) {
                                                                $edit = "";
                                                                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Payment</a>';
                                                            }
                                                            echo $payment_type;
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td class="center">#</td>
                                            <td><?php echo get_phrase('Product') . ' ' . get_phrase('Category') ?> </td>
                                            <td><?php echo get_phrase('Product') ?></td>
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
                                            <td style="text-align: right">
                                                <?php echo get_settings('unit_custom_field_2'). get_phrase('Quantity') ?>
                                            </td>
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
                                        foreach ($so_po_details as $key => $each_info):
                                            $tqty += $each_info['so_po_qty'];
                                            $trate += $each_info['so_po_unit_price'];
                                            $tprice += $each_info['so_po_unit_price'] * $each_info['so_po_qty'];
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $j++; ?></td>
                                                <td>
                                                    <?php
                                                    echo $each_info['productCat'];
                                                    ?>
                                                </td>
                                                <td>
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
                                                <td align="right"><?php echo $each_info['so_po_qty']/$each_info['unit_custom_field_1']; ?> </td>
                                                <td align="right"><?php echo $each_info['so_po_qty']; ?> </td>
                                                <td align="right"><?php echo $each_info['so_po_unit_price']; ?> </td>
                                                <td align="right"><?php echo number_format($each_info['so_po_qty'] * $each_info['so_po_unit_price'], 2); ?> </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="<?php echo  $number_of_property+7?>" align="right">
                                                <strong><?php echo get_phrase('Sub_Total') ?></strong></td>

                                            <td align="right"><?php echo number_format($tprice, 2); ?></td>
                                        </tr>


                                        <?php
                                        if ($NetTotal - $so_po_info->paid_amount > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="<?php echo  $number_of_property+7?>" align="right">
                                                    <strong><?php echo get_phrase('Due Amount') ?></strong></td>
                                                <td align="right"><?php echo number_format($NetTotal - $so_po_info->paid_amount, 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="<?php echo  $number_of_property+8?>">
                                                <strong><span><?php echo get_phrase('In_Words') ?>
                                                        : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($tprice); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="<?php echo  $number_of_property+8?>">
                                                <span><?php echo get_phrase('Narration') ?>
                                                    : &nbsp;</span> <?php echo $so_po_info->narration; ?>
                                                <div class="invoice-block pull-right">
                                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                                       onclick="javascript:window.print();"> Print
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <a href=" <?php site_url('viewLpgCylinder_pdf/' . $brands->brandId) ?>">
                                                        </a>

                                                </div>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="hr hr8 hr-double hr-dotted"></div>
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p><?php echo get_phrase('Prepared By') ?>:_____________<br/>
                                            <?php echo get_phrase('Date') ?>:____________________
                                        </p>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p><?php echo get_phrase('Approved By') ?>:________________<br/>
                                            <?php echo get_phrase('Date') ?>:_________________</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script>
    var ajaxUrl = baseUrl + "lpg/SalesController/getCustomerCurrentBalance2";
    //alert(ajaxUrl);
    $.ajax({
        type: 'POST',
        url: ajaxUrl,
        data: {
            customerId: '<?php echo $so_po_info->customer_id; ?>'
        },
        success: function (data) {
            alert('OK');
            $('.currentBalance').text(parseFloat(data).toFixed(2));


        }
    });

</script>
