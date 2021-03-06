<div class="row">
    <div class="col-sm-12">
    <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Product Category')?></div>

            </div>
         <div class="portlet-body">

            <div class="row">

                <div>

                    <table id="productCatDatatable" class="table table-striped table-bordered table-hover">

                        <thead>

                            <tr>

                                <th><?php echo get_phrase('Si')?></th>

                                <th><?php echo get_phrase('Product Cat Name')?></th>
                                <th><?php echo get_phrase('User Name')?></th>

                                <th><?php echo get_phrase('Action')?></th>

                            </tr>

                        </thead>

                        <tbody>

                            

                        </tbody>

                    </table>

                </div>

            </div><!-- /.col -->

        <!-- /.row -->

    <!-- /.page-content -->

</div>   
</div>   
</div>   
</div>   
        
        <script src="<?php echo base_url('assets/setup.js'); ?>"></script>
  
        <script src="<?php echo base_url('assets/setup.js'); ?>"></script>
 
    <script src="<?php echo base_url('assets/setup.js'); ?>"></script>

<script src="<?php echo base_url('assets/setup.js'); ?>"></script>



<script>

    $(document).ready(function() {

        //datatables

        var table = $('#productCatDatatable').DataTable({

            "processing": true, //Feature control the processing indicator.

            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "order": [], //Initial no order.



            // Load data for the table's content from an Ajax source

            "ajax": {

                "url": "<?php echo site_url('lpg/ServerFilterController/productCatList') ?>",

                "type": "POST"

            },

            //Set column definition initialisation properties.

            "columnDefs": [

                {

                    "targets": [ 0 ], //first column / numbering column

                    "orderable": false, //set not orderable

                },

            ],

            //            "columns": [

            //                {data: 'brandName'},

            //                ]

        });

    });

   

</script>





<script>

    function deleteProductCategory(deleteId){
        var url1='<?php echo site_url("lpg/ProductCategoryController/deleteProductCategory/")?>';
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

            }else{

                return false;

            }

        });

    } 

</script>









