<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Product Unit') ?> </div>

            </div>
            <div class="portlet-body">
                <form id="publicForm" action="" method="post" class="form-horizontal">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right"
                                       for="form-field-1"> <?php echo get_phrase('Unit Name') ?><span style="color:red"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" onblur="checkDuplicateUnit(this.value)" id="form-field-1"
                                           name="unitTtile" value="" class="form-control" placeholder="Unit Name"
                                           required=""/>
                                    <span id="errorMsg" style="color:red;display: none;"><i
                                                class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This Unit name already exits!!</span>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right"
                                           for="form-field-1"> <?php echo get_settings('unit_custom_field_1') ?></label>
                                    <div class="col-sm-3">
                                        <input type="text" id="form-field-1" name="unit_custom_field_1" value="" class="form-control"
                                               placeholder="<?php echo get_settings('unit_custom_field_1') ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="from-group">
                                    <label class="col-sm-3 control-label no-padding-right"
                                           for="form-field-1"> <?php echo get_settings('unit_custom_field_2') ?></label>
                                    <div class="col-sm-3">
                                        <input type="text" id="form-field-1" name="unit_custom_field_2" value="" class="form-control"
                                               placeholder="<?php echo get_settings('unit_custom_field_2') ?>"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="clearfix"></div>

                    <div class="clearfix form-actions">

                        <div class="col-md-offset-3 col-md-9">

                            <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info" type="button">

                                <i class="ace-icon fa fa-check bigger-110"></i>

                                <?php echo get_phrase('Save') ?>

                            </button>

                            &nbsp; &nbsp; &nbsp;

                            <button class="btn" type="reset">

                                <i class="ace-icon fa fa-undo bigger-110"></i>

                                <?php echo get_phrase('Reset') ?>

                            </button>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>

    function checkDuplicateUnit(unitName) {

        var url = '<?php echo site_url("SetupController/checkDuplicateUnit") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: {'unitName': unitName},

            success: function (data) {

                if (data == 1) {

                    $("#subBtn").attr('disabled', true);

                    $("#errorMsg").show();

                } else {

                    $("#subBtn").attr('disabled', false);

                    $("#errorMsg").hide();

                }

            }

        });


    }


</script>











