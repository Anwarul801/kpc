<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);

?>
<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    MRN List
                </div>

            </div>

            <div class="portlet-body">

                <?php
                if ($this->business_type != "LPG") {
                    ?>
<!--                    <div class="row">-->
<!---->
<!--                        <div class="col-md-6"-->
<!--                             style=" --><?php //echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                            <div class="form-group">-->
<!--                                <label class="col-sm-3 control-label no-padding-right"-->
<!--                                       for="credit_limit"> --><?php //echo get_phrase($property_1) ?><!-- </label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input type="text" class="form-control column_filter" id="property_1"-->
<!--                                           data-custom_column="1" placeholder="--><?php //echo get_phrase($property_1) ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6"-->
<!--                             style=" --><?php //echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                            <div class="form-group">-->
<!--                                <label class="col-sm-3 control-label no-padding-right"-->
<!--                                       for="credit_limit"> --><?php //echo get_phrase($property_2) ?><!-- </label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input type="text" class="form-control column_filter" id="property_2"-->
<!--                                           data-custom_column="1" placeholder="--><?php //echo get_phrase($property_2) ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6"-->
<!--                             style=" --><?php //echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                            <div class="form-group">-->
<!--                                <label class="col-sm-3 control-label no-padding-right"-->
<!--                                       for="credit_limit"> --><?php //echo get_phrase($property_3) ?><!-- </label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input type="text" class="form-control column_filter" id="property_3"-->
<!--                                           data-custom_column="1" placeholder="--><?php //echo get_phrase($property_3) ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6"-->
<!--                             style=" --><?php //echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                            <div class="form-group">-->
<!--                                <label class="col-sm-3 control-label no-padding-right"-->
<!--                                       for="credit_limit"> --><?php //echo get_phrase($property_4) ?><!-- </label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input type="text" class="form-control column_filter" id="property_4"-->
<!--                                           data-custom_column="1" placeholder="--><?php //echo get_phrase($property_4) ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6"-->
<!--                             style=" --><?php //echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                            <div class="form-group">-->
<!--                                <label class="col-sm-3 control-label no-padding-right"-->
<!--                                       for="credit_limit"> --><?php //echo get_phrase($property_5) ?><!-- </label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input type="text" class="form-control column_filter" id="property_5"-->
<!--                                           data-custom_column="1" placeholder="--><?php //echo get_phrase($property_5) ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->
                <?php } ?>
                <table id="salesDatatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th><?php echo get_phrase('Sl') ?></th>
                        <th><?php echo get_phrase('Date') ?></th>
                        <th><?php echo get_phrase('MRN_ID') ?></th>
                        <th><?php echo get_phrase('PO') ?></th>
                        <!--<th>Type</th>-->
                        <th><?php echo get_phrase('Supplier') ?></th>
                        <th><?php echo get_phrase('Status') ?></th>

                     <!--   <th><?php /*echo get_phrase('delivery_date') */?></th>-->
<!--                        --><?php
//                        if ($property_1 != "dont_have_this_property") {
//                            ?>
<!---->
<!--                            <th nowrap-->
<!--                                style="width:17%;border-radius:10px;--><?php //echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                                <strong>--><?php //echo $property_1; ?><!-- </strong>-->
<!--                            </th>-->
<!--                            --><?php
//
//                        }
//                        ?>
<!--                        --><?php
//                        if ($property_2 != "dont_have_this_property") {
//                            ?>
<!--                            <th nowrap-->
<!--                                style="width:10%;border-radius:10px;--><?php //echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?><!-- ">-->
<!--                                <strong>--><?php //echo $property_2; ?><!-- </strong>-->
<!---->
<!--                            </th>-->
<!--                            --><?php
//
//                        }
//                        ?>
<!--                        --><?php
//                        if ($property_3 != "dont_have_this_property") {
//                            ?>
<!--                            <th nowrap-->
<!--                                style="width:10%;border-radius:10px; --><?php //echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                                <strong>--><?php //echo $property_3; ?><!-- </strong>-->
<!--                            </th>-->
<!--                            --><?php
//
//                        }
//                        ?>
<!--                        --><?php
//                        if ($property_4 != "dont_have_this_property") {
//                            ?>
<!--                            <th nowrap-->
<!--                                style="width:10%;border-radius:10px; --><?php //echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                                <strong>--><?php //echo $property_4; ?><!-- </strong>-->
<!--                            </th>-->
<!--                            --><?php
//
//                        }
//                        ?>
<!--                        --><?php
//                        if ($property_5 != "dont_have_this_property") {
//                            ?>
<!--                            <th nowrap-->
<!--                                style="width:10%;border-radius:10px;--><?php //echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?><!--">-->
<!--                                <strong>--><?php //echo $property_5; ?><!-- </strong>-->
<!--                            </th>-->
<!--                            --><?php
//
//                        }
//                        ?>
                        <th><?php echo get_phrase('Narration') ?></th>
                        <th><?php echo get_phrase('User Name') ?></th>
                        <!-- <th>GP Amount</th>-->
                        <th><?php echo get_phrase('Action') ?></th>
                    </tr>
                    </thead>

                </table>


            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url('assets/setup.js'); ?>"></script>
<script>
    $(document).ready(function () {
        //datatables
        var table = $('#salesDatatable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            //"order": [],
            //   "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('lpg/ServerFilterController/mrn_list'); ?>",
                "type": "POST",
                "data": function (data) {
                    data.property_1 = $('#property_1').val();
                    data.property_2 = $('#property_2').val();
                    data.property_3 = $('#property_3').val();
                    data.property_4 = $('#property_4').val();
                    data.property_5 = $('#property_5').val();

                }
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
    jQuery('input.column_filter').on('keyup click', function () {
        jQuery('#salesDatatable').DataTable().ajax.reload();
    });


    function deletesalesinvoice(voucherType, id) {
        $('#deletesalesinvoice_'+id).prop('disabled', true);
        $('#deletesalesinvoice_'+id).button('loading');
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
                    var main_url = baseUrl + "lpg/SalesOrderController/so_po_delete";
                    $.ajax({
                        url: main_url,
                        type: 'post',
                        data: {
                            'redirect_page': 'purchase_order_list',
                            'voucherType': voucherType,
                            'id': id,
                        },
                        success: function (data) {
                            if (data == 1) {
                                setTimeout(function () {
                                    window.location.reload(1);
                                }, 100);
                                //window.location.replace('<?php echo site_url(); ?>'+'customerList');
                            }
                        }
                    });
                } else {
                    $('#deletesalesinvoice_'+id).prop('disabled', false);
                    $('#deletesalesinvoice_'+id).button('reset');
                    return false;
                }
            });
    }

</script>
