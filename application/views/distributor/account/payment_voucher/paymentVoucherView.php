<div class="main-content">    <div class="main-content-inner">        <div class="page-content">            <div class="row">                <div class="col-sm-10 col-sm-offset-1">                    <div class="widget-box transparent">                        <div class="widget-header widget-header-large">                            <h3 class="widget-title grey lighter">                                Payment Voucher                            </h3>                            <div class="widget-toolbar no-border invoice-info">                                <span class="invoice-info-label">Voucher ID:</span>                                <span class="red"><?php echo $paymentVoucher->Accounts_Voucher_No; ?></span>                                <br/>                                <span class="invoice-info-label">Date:</span>                                <span class="blue"><?php echo date('M d, Y', strtotime($paymentVoucher->Accounts_Voucher_Date)); ?></span>                            </div>                            <div class="widget-toolbar hidden-480" class="hidden-xs">                                <a onclick="window.print();" style="cursor:pointer;">                                    <i class="ace-icon fa fa-print"></i>                                </a>                            </div>                        </div>                        <div class="widget-body">                            <div class="widget-main padding-24">                                <div class="row">                                    <div class="col-xs-6">                                        <div class="row">                                            <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">                                                Company Info                                            </div>                                        </div>                                        <div>                                            <ul class="list-unstyled spaced">                                                <li>                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->companyName; ?>                                                </li>                                                <li>                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $this->Common_model->tableRow('branch', 'branch_id', $paymentVoucher->BranchAutoId)->branch_name; ?>                                                </li>                                                <li>                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->email; ?>                                                </li>                                                <li>                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->phone; ?>                                                </li>                                                <li>                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->address; ?>                                                </li>                                            </ul>                                        </div>                                    </div><!-- /.col -->                                    <div class="col-xs-6">                                        <div class="row">                                            <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">                                                <?php if (!empty($customerInfo)): ?>                                                    Customer Info                                                <?php elseif (!empty($supplierInfo)): ?>                                                    Supplier Info                                                <?php else: ?>                                                    Miscellaneous Info                                                <?php endif; ?>                                            </div>                                        </div>                                        <?php if (!empty($customerInfo)): ?>                                            <div>                                                <ul class="list-unstyled  spaced">                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerID . '[' . $customerInfo->customerName . ']' ?>                                                    </li>                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerEmail; ?>                                                    </li>                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerPhone; ?>                                                    </li>                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerAddress; ?>                                                    </li>                                                </ul>                                            </div>                                        <?php elseif ($supplierInfo): ?>                                            <div>                                                <ul class="list-unstyled  spaced">                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supID . '[' . $supplierInfo->supName . ']' ?>                                                    </li>                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supEmail; ?>                                                    </li>                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supPhone; ?>                                                    </li>                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supAddress; ?>                                                    </li>                                                </ul>                                            </div>                                        <?php else: ?>                                            <div>                                                <ul class="list-unstyled  spaced">                                                    <li>                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $paymentVoucher->miscellaneous; ?>                                                    </li>                                                </ul>                                            </div>                                        <?php endif; ?>                                    </div><!-- /.col -->                                </div><!-- /.row -->                                <div class="space"></div>                                <div style="min-height:400px;">                                    <div class="table-header">                                        Payment Voucher View                                    </div>                                    <table class="table table-striped table-bordered">                                        <thead>                                        <tr>                                            <td class="center">#</td>                                            <td>Account Code</td>                                            <td>Account Name</td>                                            <td>Debit(In.BDT)</td>                                            <td>Credit(In.BDT)</td>                                            <td>Memo</td>                                        </tr>                                        </thead>                                        <tbody>                                        <?php                                        $ttDreit = 0;                                        $ttCredit = 0;                                        foreach ($paymentJournal as $key => $each_info):                                            $ttDreit += $each_info->GR_DEBIT;                                            $ttCredit += $each_info->GR_CREDIT;                                            ?>                                            <tr>                                                <td class="center"><?php echo $key + 1; ?></td>                                                <td>                                                    <?php                                                    echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $each_info->CHILD_ID)->code;                                                    ?>                                                </td>                                                <td>                                                    <?php                                                    echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $each_info->CHILD_ID)->parent_name;                                                    ?>                                                </td>                                                <td align="right"><?php echo $each_info->GR_DEBIT; ?> </td>                                                <td align="right"><?php echo $each_info->GR_CREDIT; ?> </td>                                                <td><?php echo $each_info->Reference; ?> </td>                                            </tr>                                        <?php endforeach; ?>                                        </tbody>                                        <tfoot>                                        <tr>                                            <td colspan="3" align="right">Total (In BDT.)</td>                                            <td align="right"><?php echo number_format($ttDreit, 2); ?></td>                                            <td align="right"><?php echo number_format($ttCredit, 2); ?></td>                                            <td></td>                                        </tr>                                        <tr>                                            <td colspan="6">                                                <span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($ttCredit); ?>                                            </td>                                        </tr>                                        <tr>                                            <td colspan="6">                                                <span>Narration : &nbsp;</span> <?php echo $paymentVoucher->Narration; ?>                                            </td>                                        </tr>                                        </tfoot>                                    </table>                                </div>                                <div class="hr hr8 hr-double hr-dotted"></div>                                <div class="row">                                    <div class="col-xs-4 text-center">                                        <p>Prepared By:_____________<br/>                                            Date:____________________                                        </p>                                    </div>                                    <div class="col-xs-4 text-center">                                    </div>                                    <div class="col-xs-4 text-center">                                        <p>Approved By:________________<br/>                                            Date:_________________</p>                                    </div>                                </div>                                <hr/>                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                                ?></p>                                <!--                                <div class="space-6"></div>                                                                <div class="well">                                                                    Thank you for choosing Ace Company products.                                                                    We believe you will be satisfied by our services.                                                                </div>-->                            </div>                        </div>                    </div>                </div>            </div>        </div><!-- /.row -->    </div><!-- /.page-content --></div><script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>