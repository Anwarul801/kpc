<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Reference List 
                </div>

            </div>

            <div class="portlet-body">

                <table id="customerDatatable" class="table table-striped table-bordered table-hover">

                    <thead>

                        <tr>

                            <th><strong><?php echo get_phrase('Sl')?></strong></th>

                            <th><strong><?php echo get_phrase('Referencen_Id')?></strong></th>

                            <th><strong><?php echo get_phrase('Name')?></strong></th>

                            <th><strong><?php echo get_phrase('Phone')?></strong></th>

                            <th><strong><?php echo get_phrase('Email')?></strong></th>

                            <th><strong><?php echo get_phrase('Address')?></strong></th>
                            <th><strong><?php echo get_phrase('User Name')?></strong></th>
                            <th><strong><?php echo get_phrase('Action')?></strong></th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($referenceList as $key => $value): ?>

                            <tr>

                                <td><?php echo $key + 1; ?></td>

                                <td><?php echo $value->refCode; ?></td>

                                <td><?php echo $value->referenceName; ?></td>

                                <td><?php echo $value->referenceEmail; ?></td>

                                <td><?php echo $value->referencePhone; ?></td>

                                <td><?php echo $value->referenceAddress; ?></td>
                                <td><?php $user_id=  $value->updated_by;
                                            $this->db->select("admin.name");
                                            $this->db->where('admin.admin_id', $user_id);
                                            
                                            $query = $this->db->get('admin')->row_array();
                                            echo $query['name'];
                                ?></td>



                                <td>

                                    <div class="hidden-sm hidden-xs action-buttons">

                                        <!--                                            <a class="blue" href="#">

                                                                                        <i class="ace-icon fa fa-search-plus bigger-130"></i>

                                                                                    </a>-->
                                        <?php 
                                        
                                        $this->load->helper('site_helper'); 
                                         $add  = check_parmission_by_user_role(2110);
                                          $edit  = check_parmission_by_user_role(2111);
                                           $delete  = check_parmission_by_user_role(2112);
                                        ?>
                                        <?php if($edit == 2111) {?>
                                        <a class="btn btn-icon-only blue" href="<?php echo site_url($this->project.'/editReference/' . $value->reference_id); ?>">

                                            <i class="ace-icon fa fa-pencil bigger-130"></i>

                                        </a>
                                        <?php }  if($delete == 2112) {?>


                                        <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteReference('<?php echo $value->reference_id; ?>')">

                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>

                                        </a>
                                        <?php }?>




                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>


<!-- /.col -->

<!-- /.row -->


<script>

    function deleteReference(deleteId) {
        var url1='<?php echo site_url()?>lpg/SalesController/deleteReference/';

        console.log(url1);
        exit;
        //var url1='<?php echo site_url(); ?>' + '';


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
                window.location.href = url1 + deleteId;
            } else {

                return false;

            }

        });

    }

</script>







