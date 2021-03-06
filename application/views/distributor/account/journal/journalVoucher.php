
   <div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                     <?php echo get_phrase('Journal Voucher')?> </div>
                      </div>
                      <div class="portlet-body">
                      <div class="row">
                     <table id="journalDatatable" class="table table-striped table-bordered table-hover">

                        <thead>

                            <tr>

                                <th><?php echo get_phrase('Si')?></th>

                                <th><?php echo get_phrase('JV_No')?></th>

                                <th><?php echo get_phrase('Date')?></th>

                                <th><?php echo get_phrase('Type')?></th>

                                <th><?php echo get_phrase('Memo')?></th>

                                <th><?php echo get_phrase('Amount')?></th>
                                <th><?php echo get_phrase('user name')?></th>

                                <th><?php echo get_phrase('Action')?></th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>



                       </div><!-- /.col -->
                      </div>
                      </div>
                      </div>
                      </div>




<script>
    function deleteVoucher(voucherType,id){
        $('#delete_'+id).prop('disabled', true);
        $('#delete_'+id).button('loading');
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
                    var main_url = baseUrl + "lpg/VoucherController/delete_voucher";
                    $.ajax({
                        url: main_url,
                        type: 'post',
                        data: {
                            'voucherName':'Journal Voucher',
                            'voucherType':'journalVoucher',
                            'id': id,
                        },
                        success: function(data) {
                            if(data == 1){
                                setTimeout(function(){
                                    window.location.reload(1);
                                }, 100);
                                //window.location.replace('<?php echo site_url(); ?>'+'customerList');
                            }
                        }
                    });
                }else{
                    $('#delete_'+id).prop('disabled', false);
                    $('#delete_'+id).button('reset');
                    return false;
                }
            });
    }
    $(document).ready(function() {

        //datatables

        var table = $('#journalDatatable').DataTable({

            "processing": true, //Feature control the processing indicator.

            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "ordering" : false,

            //"order": [],

            //   "order": [], //Initial no order.



            // Load data for the table's content from an Ajax source

            "ajax": {

                "url": "<?php echo site_url('lpg/ServerFilterController/journalList') ?>",

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















