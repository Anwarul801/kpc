<div class="row">
<div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Customer Payment List </div>

            </div>

            <div class="portlet-body">
                <table id="customerPaymentDatatable" class="table table-striped table-bordered table-hover">

                        <thead>

                            <tr>

                                <th><?php echo get_phrase('Sl')?></th>

                                <th><?php echo get_phrase('Date')?></th>

                                <th><?php echo get_phrase('Pv_No')?></th>

                                <th><?php echo get_phrase('Customer')?></th>

                                <th><?php echo get_phrase('Payment_Type')?></th>

                                <th><?php echo get_phrase('Amount')?></th>
                                <th><?php echo get_phrase('user Name')?></th>

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

    <!-- /.row -->

    

<script>

    $(document).ready(function() {

        //datatables

        var table = $('#customerPaymentDatatable').DataTable({

            "processing": true, //Feature control the processing indicator.

            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "ordering" : false,

            //"order": [], 

            //   "order": [], //Initial no order.



            // Load data for the table's content from an Ajax source

            "ajax": {

                "url": "<?php echo site_url('lpg/ServerFilterController/cusPayList') ?>",

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













