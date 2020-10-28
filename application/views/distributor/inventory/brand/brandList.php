<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Brand List')?> </div>

            </div>

            <div class="portlet-body">

                    <table id="brand_datatable" class="table table-striped table-bordered table-hover">

                        <thead>

                        <tr>

                            <th><?php echo get_phrase('Si')?></th>

                            <th><?php echo get_phrase('Brand Title')?></th>
                            <th><?php echo get_phrase('User name')?></th>

                            <th><?php echo get_phrase('Action')?></th>

                        </tr>

                        </thead>

                        <tbody>


                        </tbody>

                    </table>

            </div>
            </div>
            </div>
            </div>

<script>

    function deleteBrand(deleteId) {
        var url1='<?php echo site_url("lpg/BrandController/deleteBrand/")?>';

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


<script>

    $(document).ready(function () {

        //datatables

        var table = $('#brand_datatable').DataTable({

            "processing": true, //Feature control the processing indicator.

            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "order": [], //Initial no order.


            // Load data for the table's content from an Ajax source

            "ajax": {

                "url": "<?php echo site_url('lpg/ServerFilterController/brandList') ?>",

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









