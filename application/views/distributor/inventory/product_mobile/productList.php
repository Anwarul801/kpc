<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Product List')?> </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                       <table id="productDatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrase('Sl')?></th>
                                    <th><?php echo get_phrase('Product Category')?></th>
                                    <th><?php echo get_phrase('Brand')?></th>
                                    <th><?php echo get_phrase('Product Code')?></th>
                                    <th><?php echo get_phrase('Model Name')?></th>
                                    <th><?php echo get_phrase('Purchases Price')?></th>

                                    <th><?php echo get_phrase('Whole Price')?></th>
                                    <th><?php echo get_phrase('Retail Price')?>(MRP)</th>
                                    <th><?php echo get_phrase('User name')?></th>
                                    <th><?php echo get_phrase('Status')?></th>
                                    <th><?php echo get_phrase('Action')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
    </div>
    <script src="<?php echo base_url('assets/setup.js'); ?>"></script>
    <script>
        function deleteProduct(deleteId) {
            var url1='<?php echo site_url()?>lpg/InventoryController/deleteProduct/';
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
                    window.location.href =  url1+ deleteId;
                } else {
                    return false;
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            //datatables
            var table = $('#productDatatable').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "ordering": false,
                //"order": [],
                //   "order": [], //Initial no order.
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('lpg/ServerFilterController/productList') ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],
                //            "columns": [
                //                {data: 'brandName'},
                //                ]
            });
        });

    </script>
