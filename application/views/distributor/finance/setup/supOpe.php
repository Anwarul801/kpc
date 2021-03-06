
<?php
$headlogin = $this->session->userdata('headOffice');
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                       <?php echo get_phrase('Supplier Opening Payable List')?> </div>
                      </div>
                      <div class="portlet-body">

            <div class="row">

                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('Sl')?></th>
                                <th><?php echo get_phrase('Supplier Name')?></th>
                                <th align="right"><?php echo get_phrase('Payable')?></th>
                                <th align="right"><?php echo get_phrase('User name')?></th>
                                <th><?php echo get_phrase('Action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalPayable = 0;
                            foreach ($allBalance as $key => $value):
                                $totalPayable+=$value->dr;
                                $supName = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $value->client_vendor_id)->supName;
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $supName; ?></td>
                                    <td align="right"><?php echo number_format($value->dr); ?></td>
                                    <td>
                                        <?php
                                        if (empty($openingShowHide) || $openingShowHide < 1):
                                            //if distributor login
                                            ?>
                                            <a class="red deleteOpening" href="javascript:void(0)" onclick="deleteOpening('<?php echo $value->ledger_id; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>
                                            <?php
                                        else:
                                            //head office login
                                            if (!empty($headlogin)):
                                                ?>
                                               <!-- <a class="red deleteOpening" href="javascript:void(0)" onclick="deleteOpening('<?php /*echo $value->ledger_id; */?>')">
                                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                </a>-->
                                                <?php
                                            endif;
                                        endif;
                                        ?>
                                    </td>
                                    <td><?php $user_id=  $value->updated_by;
                                            $this->db->select("admin.name");
                                            $this->db->where('admin.admin_id', $user_id);
                                            
                                            $query = $this->db->get('admin')->row_array();
                                            echo $query['name'];
                                ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <td colspan="2" align="right"><strong><?php echo get_phrase('Total Payable')?></strong></td>
                        <td align="right"><?php echo number_format($totalPayable); ?></td>
                        <td></td>
                        <td></td>
                        </tfoot>
                    </table>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
    </div><!-- /.page-content -->
</div>
<script src="<?php echo base_url('assets/setup.js'); ?>"></script>
<script>
    function deleteOpening(deleteId){
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
                window.location.href = "FinaneController/deleteSupplierOpening/" + deleteId;
            }else{
                return false;
            }
        });
    }
    function deleteAllData(){
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
                window.location.href = "FinaneController/allSupCusOpeDelete/2";
            }else{
                return false;
            }
        });
    }
</script>
