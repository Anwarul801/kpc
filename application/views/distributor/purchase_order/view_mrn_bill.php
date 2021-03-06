<style>

    table tr td {

        margin: 0px !important;

        padding: 0px !important;

    }

    #receiveby {

        border-bottom: 1px dashed black;

    }

</style>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">

                    <span class="caption-subject font-dark sbold uppercase">Supplier Payment Money Receipt

                    </span>
                </div>
                <div class="actions ">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                        <label><?php echo get_phrase('Payment_Type') ?>: <span style="color:red; padding-right:5px">
                            <?php
                            if ($moneyReceitInfo->paymentType == 1) {

                                echo "Cash";
                            } elseif ($moneyReceitInfo->paymentType == 2) {

                                echo "Bank";
                            }
                            ?></span><br>

                            <?php if ($moneyReceitInfo->paymentType == 2): ?>

                                <span class="invoice-info-label">Check Status:</span>

                                <span class="red"><?php
                                    if ($moneyReceitInfo->checkStatus == 1) {

                                        echo "Pending";
                                    } else {

                                        echo "Received";
                                    }
                                    ?></span>

                            <?php endif; ?>

                            </span></label>


                        <label><span><?php echo get_phrase('Receipt_ID:') ?>:<span style="color:red;">
                                    <span class="red">
                                   <?php echo $moneyReceitInfo->receitID.'/'.$sdc_mrn_info->sdc_mrn_no; ?>

                                </span>
                        </label>
                        <label>
                            <span><?php echo get_phrase('Date') ?>:
                                <span style="color:red; padding-right:5px"> <?php echo $moneyReceitInfo->date ?></span>
                            </span>
                        </label>
                        <label>

                                <span style="color:red; padding-right:5px"><a
                                            class="btn btn-md blue hidden-print margin-bottom-5"
                                            onclick="javascript:window.print();"> Print
                                                            <i class="fa fa-print"></i>
                                                        </a></span>

                        </label>

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
                                            <?php echo get_phrase('Invoice Info') ?>
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
                                                        <?php echo get_phrase('Email') ?>
                                                        : <?php echo $companyInfo->email; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Phone') ?>
                                                        : <?php echo $companyInfo->phone; ?>
                                                    </li>
                                                    <!--<li>
                                                        Account Name: FoodMaster Ltd
                                                    </li>-->
                                                    <li>
                                                        <?php echo get_phrase('Address') ?>
                                                        : <?php echo $companyInfo->address; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4">
                                                <h3> Supplier Info</h3>
                                                <ul class="list-unstyled ">
                                                    <li>
                                                        <?php echo get_phrase('Name') ?>
                                                        : <?php echo $suplierInfo->supID . '[' . $suplierInfo->supName . ']' ?>
                                                    </li>
                                                    <li>

                                                        <?php echo get_phrase('Email') ?>
                                                        :<?php echo $suplierInfo->supEmail; ?>

                                                    </li>


                                                    <li>

                                                        <?php echo get_phrase('Phone') ?>
                                                        :<?php echo $suplierInfo->supPhone; ?>

                                                    </li>

                                                    <li>

                                                        <?php echo get_phrase('Address') ?>
                                                        :<?php echo $suplierInfo->supAddress; ?>

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
                                <div style="min-height:400px;">

                                    <table class="table ">

                                        <tr>

                                            <td nowrap width="15%"><strong>Received with thanks from</strong></td>

                                            <td width="1%">:</td>

                                            <td id="receiveby"><?php echo $suplierInfo->supID . '[' . $suplierInfo->supName . ']'; ?></td>

                                        </tr>

                                    </table>

                                    <table class="table ">

                                        <tr>

                                            <td nowrap width="10%"><strong>Amount in TK</strong></td>

                                            <td width="1%">:</td>

                                            <td class="receiveby" id="receiveby"><?php
                                                echo $moneyReceitInfo->totalPayment;
                                                ?>

                                            </td>

                                        </tr>

                                    </table>

                                    <table class="table ">

                                        <tr>

                                            <td nowrap width="17%"><strong>The Sum of Taka in words</strong></td>

                                            <td width="1%">:</td>

                                            <td id="receiveby" class="receiveby"><?php
                                                echo $this->Common_model->get_bd_amount_in_text($moneyReceitInfo->totalPayment);
                                                ?>

                                            </td>

                                        </tr>

                                    </table>

                                    <?php
                                    $invoiceinfo = json_decode($moneyReceitInfo->invoiceID);


                                    //dumpVar();
                                    //dumpVar($invoiceinfo);
                                    ?>


                                    <table class="table ">

                                        <tr>

                                            <td nowrap width="10%"><strong>On Account of</strong></td>

                                            <td nowrap width="1%">:</td>

                                            <td nowrap class="receiveby" id="receiveby">

                                                <?php
                                                echo $sdc_mrn_info->sdc_mrn_no;
                                                ?>

                                            </td>

                                        </tr>

                                    </table>


                                    <?php
                                    if ($moneyReceitInfo->paymentType == 1):
                                        ?>

                                        <table class="table ">

                                            <tr>

                                                <td><strong>By Cash</strong></td>


                                            </tr>

                                        </table>

                                    <?php else: ?>

                                        <table class="table ">

                                            <tr>

                                                <td nowrap width="5%"><strong>By Bank:</strong></td>

                                                <td nowrap id="receiveby"
                                                    width="20%"><?php echo $moneyReceitInfo->bankName; ?></td>

                                                <td nowrap width="5%"><strong>Cheque No:</strong></td>

                                                <td id="receiveby"
                                                    width="20%"><?php echo $moneyReceitInfo->checkNo; ?></td>

                                                <td nowrap width="5%"><strong>Branch Name: </strong></td>

                                                <td id="receiveby"
                                                    width="20%"><?php echo $moneyReceitInfo->branchName; ?></td>

                                                <td nowrap width="5%"><strong>Date: </strong></td>

                                                <td id="receiveby"
                                                    width="20%"><?php echo $moneyReceitInfo->checkDate; ?></td>

                                            </tr>

                                        </table>

                                        <br>

                                        <p align="left;">&nbsp;&nbsp;NB: Payment by cheque will be valid subject to
                                            realization of the cheque.</p>

                                    <?php endif; ?>

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





