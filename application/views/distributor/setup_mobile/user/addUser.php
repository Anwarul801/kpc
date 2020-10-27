<div class="row">    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                    Add New User                </div>            </div>            <div class="portlet-body">                <div class="row">                    <div class="col-md-12">                        <form id="publicForm" action="" method="post" class="form-horizontal">                            <div class="form-group">                                <label class="col-sm-3 control-label no-padding-right" for="name"> User                                    Name </label>                                <div class="col-sm-6">                                    <input type="text" id="name" name="name" class="form-control required"                                           placeholder="User Name" autocomplete="off"/>                                </div>                            </div>                            <div class="form-group">                                <label class="col-sm-3 control-label no-padding-right" for="user_role"> User                                    Role </label>                                <div class="col-sm-6">                                    <select name="user_role" class="chosen-select form-control" required                                            id="user_role" data-placeholder="Select User Role" >                                        <?php                                        echo user_role_dropdown(null,null);                                        ?>                                    </select>                                </div>                            </div>                            <div class="form-group">                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>                                <div class="col-sm-6">                                    <input type="text" maxlength="11" id="form-field-1"                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"                                           name="phone" placeholder="Phone" class="form-control" autocomplete="off"/>                                </div>                            </div>                            <div class="form-group">                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>                                <div class="col-sm-6">                                    <input type="email" onblur="checkDuplicateEmail(this.value)" id="form-field-1"                                           name="email" placeholder="Email" class="form-control" autocomplete="off"/>                                    <span id="errorMsg" style="color:red;display: none;"><i                                                class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Email already Exits!!</span>                                </div>                            </div>                            <div class="form-group">                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">                                    Password</label>                                <div class="col-sm-6">                                    <input type="password" id="form-field-1" name="password" placeholder="Password"                                           class="form-control" autocomplete="off"/>                                </div>                            </div>                            <div class="clearfix form-actions">                                <div class="col-md-offset-3 col-md-9">                                    <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info"                                            type="button">                                        <i class="ace-icon fa fa-check bigger-110"></i>                                        Save                                    </button>                                    &nbsp; &nbsp; &nbsp;                                    <button class="btn" type="reset">                                        <i class="ace-icon fa fa-undo bigger-110"></i>                                        Reset                                    </button>                                </div>                            </div>                        </form>                    </div>                </div><!-- /.col -->            </div><!-- /.row -->        </div><!-- /.page-content -->    </div></div><script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script><script>    function checkDuplicateEmail(email) {        var url = '<?php echo site_url("SetupController/checkDuplicateEmailForUser") ?>';        $.ajax({            type: 'POST',            url: url,            data: {'email': email},            success: function (data) {                if (data == 1) {                    $("#subBtn").attr('disabled', true);                    $("#errorMsg").show();                } else {                    $("#subBtn").attr('disabled', false);                    $("#errorMsg").hide();                }            }        });    }</script>