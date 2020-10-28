<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                   <?php echo get_phrase('Supplier List ')?>
                </div>
            </div>
            <div class="portlet-body">
                    <table id="supplierdatatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><?php echo get_phrase('Si')?></th>
                            <th><?php echo get_phrase('Supplier Id')?></th>
                            <th><?php echo get_phrase('Name')?></th>
                            <th><?php echo get_phrase('Email')?></th>
                            <th><?php echo get_phrase('Phone')?></th>
                            <th><?php echo get_phrase('Address')?></th>
                            <th><?php echo get_phrase('User Name')?></th>
                            <th class="hidden-480"><?php echo get_phrase('Status')?></th>
                            <th><?php echo get_phrase('Action')?></th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                    <!--                    -->

               <!-- /.col -->
            </div>
            </div>
            </div>
            </div>
        <!-- /.row -->

    <!-- /.page-content -->


<script src="<?php echo base_url('assets/setup.js'); ?>"></script>


<script>


    function deleteSupplier(id) {


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

                type: 'warning'

            },

            function (isConfirm) {

                if (isConfirm) {

                    // var base_u = $('#baseUrl').val();

                    var main_url = '<?php echo site_url(); ?>' + 'SetupController/supplierDelete';

                    $.ajax({

                        url: main_url,

                        type: 'post',

                        data: {

                            id: id,

                        },

                        success: function (data) {

                            if (data == 1) {

                                setTimeout(function () {

                                    window.location.reload(1);

                                }, 100);

                                window.location.replace('<?php echo site_url(); ?>' + 'supplierList');

                            }

                        }

                    });

                } else {

                    return false;

                }

            });

    }


    $(document).ready(function () {

        //datatables

        var table = $('#supplierdatatable').DataTable({

            "processing": true, //Feature control the processing indicator.

            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "order": [], //Initial no order.


            // Load data for the table's content from an Ajax source

            "ajax": {


                "url": "<?php echo site_url('lpg/ServerFilterController/supplierList') ?>",

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









