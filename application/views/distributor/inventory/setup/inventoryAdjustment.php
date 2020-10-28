<?php
$headlogin = $this->session->userdata('headOffice');
?>


<!-- <div class="breadcrumbs ace-save-state" id="breadcrumbs">
<ul class="breadcrumb">
<li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="#">Inventory</a>
</li>
<li class="active">Inventory Adjustment</li>
</ul>
<?php /* if (!empty($headlogin)): */ ?>
<ul class="breadcrumb pull-right">
    <li>
        <a class="inventoryAddPermission" href="<?php /* echo site_url('inventoryAdjustmentAdd'); */ ?>">
            <i class="ace-icon fa fa-plus"></i>
            Add
        </a>
    </li>
    <li>
        <a class="inventoryAddPermission" href="<?php /* echo site_url('openigInventoryImport'); */ ?>">
            <i class="ace-icon fa fa-upload"></i>
            Import
        </a>
    </li>
</ul>

<?php /* else: */ ?>

<ul class="breadcrumb pull-right">
<?php /* if (empty($openingShowHide) || $openingShowHide < 1): */ ?>
        <li>
            <a class="inventoryAddPermission" href="<?php /* echo site_url('inventoryAdjustmentAdd'); */ ?>">
                <i class="ace-icon fa fa-plus"></i>
                Add
            </a>
        </li>
        <li>
            <a class="inventoryAddPermission" href="<?php /* echo site_url('openigInventoryImport'); */ ?>">
                <i class="ace-icon fa fa-upload"></i>
                Import
            </a>
        </li>
<?php /* endif; */ ?>
</ul>

<?php /* endif; */ ?>
</div>-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
              <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
               <?php echo get_phrase('Inventory Adjustment')?>
              </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                  
                        <table id="example" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrase('Si')?></th>
                                    <th><?php echo get_phrase('Date')?></th>
                                    <th><?php echo get_phrase('PV_No')?></th>
                                    <th><?php echo get_phrase('Type')?></th>
                                    <th><?php echo get_phrase('Amount')?></th>
                                    <th><?php echo get_phrase('Narration')?></th>
                                    <th><?php echo get_phrase('User Name')?></th>
                                    <th><?php echo get_phrase('Action')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($inventoryAdjustmentList as $key => $value):
                                    ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($value->invoice_date)); ?></td>
                                        <td><?php echo $value->invoice_no; ?></td>
                                        <td><?php //echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name;       ?></td>
                                        <td><?php echo number_format((float) $value->invoice_amount, 2, '.', ','); ?></td>
                                        <td><?php echo $value->narration; ?></td>
                                        <td><?php $user_id=  $value->insert_by;
                                            $this->db->select("admin.name");
                                            $this->db->where('admin.admin_id', $user_id);
                                            
                                            $query = $this->db->get('admin')->row_array();
                                            echo $query['name'];
                                ?></td>
                                        <td>
                                            <div class="hidden-sm hidden-xs action-buttons">
                                                <a class="btn btn-icon-only blue" href="<?php echo site_url($this->project.'/viewAdjustment/' . $value->purchase_invoice_id); ?>">
                                                    <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                </a>
                                                <?php
                                                if (empty($openingShowHide) || $openingShowHide < 1):
                                                    //distributor login
                                                    ?>
                                                    <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteOpening('<?php echo $value->purchase_invoice_id; ?>')">
                                                        <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                    </a>
                                                    <?php
                                                else:
                                                    //head office login
                                                    if (!empty($headlogin)):
                                                        ?>
                                                        <a class="red deleteOpening" href="javascript:void(0)" onclick="deleteOpening('<?php echo $value->purchase_invoice_id; ?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        <?php
                                                    endif;
                                                endif;
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div><!-- /.col -->
                <!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div>
</div>
<script>
    function deleteOpening(deleteId) {
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
                window.location.href = "deleteInventoryOpening/" + deleteId;
            } else {
                return false;
            }
        });
    }
</script>
