<div class="row">    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                    <?php echo get_phrase('Brand Edit')?> </div>            </div>            <div class="portlet-body">                <div class="row">                    <div class="col-md-12">                        <form id="publicForm" action=""  method="post" class="form-horizontal">                            <div class="col-md-12">                                <div class="form-group">                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Brand Name')?><span style="color:red"> *</span></label>                                    <div class="col-sm-6">                                        <input type="text" onblur="checkDuplicateBranch(this.value)" id="form-field-1" name="brandName"  value="<?php echo $brandList->brandName; ?>" class="form-control" placeholder="Brand Name" required/>                                        <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This brand name already exits!!</span>                                    </div>                                </div>                            </div>                            <div class="clearfix"></div>                            <div class="clearfix form-actions" >                                <div class="col-md-offset-3 col-md-9">                                    <button onclick="return isconfirm()" disabled id="subBtn" class="btn btn-info" type="submit">                                        <i class="ace-icon fa fa-check bigger-110"></i>                                        <?php echo get_phrase('Update')?>                                    </button>                                    &nbsp; &nbsp; &nbsp;                                    <button class="btn" type="reset">                                        <i class="ace-icon fa fa-undo bigger-110"></i>                                        <?php echo get_phrase('Reset')?>                                    </button>                                </div>                            </div>                        </form>                    </div>                </div><!-- /.col -->            </div><!-- /.row -->        </div><!-- /.page-content -->    </div>    </div>    <script>        function checkDuplicateBranch(brandName) {            var url = '<?php echo site_url("SetupController/checkDuplicateBrandForUpdate") ?>';            $.ajax({                type: 'POST',                url: url,                data: {'brandName': brandName, 'brandId': '<?php echo $brandList->brandId; ?>'},                success: function (data)                {                    if (data == 1) {                        $("#subBtn").attr('disabled', true);                        $("#errorMsg").show();                    } else {                        $("#subBtn").attr('disabled', false);                        $("#errorMsg").hide();                    }                }            });        }    </script>