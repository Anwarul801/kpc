<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Brand Add')?> </div>

            </div>

            <div class="portlet-body">
            <?php 
                        $add  = check_parmission_by_user_role(2116);
                        if($add == 0){?>
                          <h2>You Don't have permission to add</h2>
                        <?php }else{?>
                <form id="publicForm" action=""  method="post" class="form-horizontal">

                    <div class="col-md-12">

                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Brand Name')?><span style="color:red"> *</span></label>

                            <div class="col-sm-6">

                                <input type="text" id="form-field-1" name="brandName" onblur="checkDuplicateBranch(this.value)"  value="" class="form-control" placeholder="Brand Name" required/>

                                <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This brand name already exits!!</span>

                            </div>

                        </div>

                    </div>



                    <div class="clearfix"></div>

                    <div class="clearfix form-actions" >

                        <div class="col-md-offset-3 col-md-9">

                            <button onclick="return confirmSwat()"   id="subBtn" class="btn btn-info" type="button">

                                <i class="ace-icon fa fa-check bigger-110"></i>

                                <?php echo get_phrase('Save')?>

                            </button>

                            &nbsp; &nbsp; &nbsp;

                            <button class="btn" type="reset">

                                <i class="ace-icon fa fa-undo bigger-110"></i>

                                <?php echo get_phrase('Reset')?>

                            </button>

                        </div>

                    </div>

                </form>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script>

    function checkDuplicateBranch(brandName) {

        var url = '<?php echo site_url("SetupController/checkDuplicateBrand") ?>';

        $.ajax({
            type: 'POST',
            url: url,
            data: {'brandName': brandName},
            success: function (data)

            {

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









