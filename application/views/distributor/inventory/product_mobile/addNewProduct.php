<?php

$property_1=get_property_list_for_show_hide(1);
$property_2=get_property_list_for_show_hide(2);
$property_3=get_property_list_for_show_hide(3);
$property_4=get_property_list_for_show_hide(4);
$property_5=get_property_list_for_show_hide(5);
$ProductSubCategory=get_property_list_for_show_hide(6);
$Color=get_property_list_for_show_hide(7);
$Size=get_property_list_for_show_hide(8);


?>
<div class="row">
    <div class="col-sm-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Add Product')?></div>

            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form id="publicForm" action=""  method="post" class="form-horizontal">

                    <div class="form-group">

                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Product Code')?> <span style="color:red!important"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="form-field-1" name="product_code" readonly value="<?php echo $productid; ?>" class="form-control" placeholder="Product Code" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Product Category')?><span style="color:red!important"> *</span></label>
                        <div class="col-sm-6">
                            <select  id="productCategory"  onchange="make_product_name()" name="category_id"  class="chosen-select form-control" data-placeholder="Search product Category">
                                <option></option>
                                <?php foreach ($productCat as $each_info): ?>
                                    <option value="<?php echo $each_info->category_id; ?>"><?php echo $each_info->title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                   

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Brand')?></label>
                        <div class="col-sm-6">
                            <select  id="brandId"  onchange="make_product_name()" name="brand"  class="chosen-select form-control"  data-placeholder="Search Brand">
                                <option></option>
                                <?php foreach ($brandList as $each_info): ?>
                                    <option value="<?php echo $each_info->brandId; ?>"><?php echo $each_info->brandName; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Model')?></label>
                        <div class="col-sm-6">
                            <select  id="model"  onchange="make_product_name()" name="model"  class="chosen-select form-control"  data-placeholder="Search Model">
                                <option></option>
                                <?php foreach ($model as $each_info): ?>
                                    <option value="<?php echo $each_info->ModelID; ?>"><?php echo $each_info->Model; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="<?php echo $Color=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($Color)?></label>
                        <div class="col-sm-6">
                            <select  id="color"   name="color" onchange="make_product_name()" class="chosen-select form-control"  data-placeholder="Search Color">
                                <option></option>
                                <?php foreach ($color as $each_info): ?>
                                    <option value="<?php echo $each_info->ColorID; ?>"><?php echo $each_info->Color; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                     <div class="form-group" style="<?php echo $Size=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($Size)?></label>
                        <div class="col-sm-6">
                            <select  id="size"   name="size"  onchange="make_product_name()" class="chosen-select form-control"  data-placeholder="Search Size">
                                <option></option>
                                <?php foreach ($size as $each_info): ?>
                                    <option value="<?php echo $each_info->SizeID; ?>"><?php echo $each_info->Size; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Unit')?><span style="color:red!important"> *</span></label>
                        <div class="col-sm-6">
                            <select  id="productUnit" name="unit"  class="chosen-select form-control"  data-placeholder="Search Brand">
                                <option></option>
                                <?php foreach ($unitList as $each_info): ?>
                                    <option <?php
                                    if ($each_info->unit_id == 1) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $each_info->unit_id; ?>"><?php echo $each_info->unitTtile; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!--<div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="product_type_id"><?php /*echo get_phrase('Product Type')*/?><span style="color:red!important"> *</span></label>
                <div class="col-sm-6">
                    <select  id="product_type" name="product_type_id"  class="chosen-select form-control"  data-placeholder="Search Group Name" required="">

                        <option value="" disabled="" selected=""> Select Product Type </option>
                        <?php /*foreach ($product_type_list as $each_info): */?>
                            <option  value="<?php /*echo $each_info->product_type_id; */?>"><?php /*echo $each_info->product_type_name; */?></option>
                        <?php /*endforeach; */?>
                    </select>
                </div>
            </div>-->
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Product Name')?><span style="color:red!important"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="productName" onblur="checkDuplicateProduct()" name="productName"  value="" class="form-control" placeholder="Product  Name" />
                            <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This product name already exits!!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Purchases Price')?><span style="color:red!important"> *</span></label>
                        <div class="col-sm-6">
                            <input required="required" type="text" id="purchases_price" name="purchases_price" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="" class="form-control" placeholder="Purchases Price" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Retail_Price_Mrp')?> </label>
                        <div class="col-sm-6">
                            <input required="required" type="text" id="salesPrice" name="salesPrice"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="" class="form-control" placeholder="Sales Price" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Whole_Sale_Price')?></label>
                        <div class="col-sm-6">
                            <input required="required" type="text" id="retailPrice" name="retailPrice"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  value="" class="form-control" placeholder="Wholesale Price" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Alarm Quantity')?></label>
                        <div class="col-sm-6">
                            <input type="text" id="form-field-1" name="alarm_qty"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  value="" class="form-control" placeholder="Alert Quantity" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Vat')?></label>
                        <div class="col-sm-6">
                            <input type="text" id="form-field-1" name="vat"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  value="" class="form-control" placeholder="Vat %" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Others Description')?></label>
                        <div class="col-sm-6">
                            <input type="text" id="description"  name="description"  value="" class="form-control" placeholder="Others Description" />

                        </div>
                    </div>
                    <div class="form-group" style="<?php echo $property_1=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($property_1)?></label>
                        <div class="col-sm-6">
                            <input type="text" id="property_1"  name="property_1"  value="" class="form-control" placeholder="<?php echo $property_1?>" />
                        </div>
                    </div>
                    <div class="form-group" style="<?php echo $property_2=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($property_2)?></label>
                        <div class="col-sm-6">
                            <input type="text" id="property_2"  name="property_2"  value="" class="form-control" placeholder="<?php echo $property_2?>" />
                        </div>
                    </div>
                    <div class="form-group" style="<?php echo $property_3=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($property_3)?></label>
                        <div class="col-sm-6">
                            <input type="text" id="property_3"  name="property_3"  value="" class="form-control" placeholder="<?php echo $property_3?>" />
                        </div>
                    </div>
                    <div class="form-group" style="<?php echo $property_4=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($property_4)?></label>
                        <div class="col-sm-6">
                            <input type="text" id="property_4"  name="property_4"  value="" class="form-control" placeholder="<?php echo $property_4?>" />
                        </div>
                    </div>
                    <div class="form-group" style="<?php echo $property_5=='dont_have_this_property'?'display: none':''?>">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($property_5)?></label>
                        <div class="col-sm-6">
                            <input type="text" id="property_5"  name="property_5"  value="" class="form-control" placeholder="<?php echo $property_5?>" />
                        </div>
                    </div>


                    <div class="clearfix form-actions" >
                        <div class="col-md-offset-3 col-md-9">
                            <button onclick="return isconfirmProduct2()"   id="subBtn" class="btn btn-info" type="button">
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
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>













<script>


    function make_product_name(){
        var productCategory = $("#productCategory option:selected" ).text();
        var subcategory = $("#subcategory option:selected" ).text();
        var brandId = $("#brandId option:selected" ).text();
        var model = $("#model option:selected" ).text();
        var color = $("#color option:selected" ).text();
        var size = $("#size option:selected" ).text();
        var productName= productCategory;
        if(subcategory!=""){
            productName+=" - "+subcategory;
        }
        if(model!=""){
            productName+=" - "+model;
        }
        if(color!=""){
            productName+=" - "+color;
        }
        if(size!=""){
            productName+=" - "+size;
        }
        if(brandId!=""){
            productName+=" [ "+brandId+" ]";
        }
        //var productName= productCategory+' - '+subcategory+' - '+model+' '+color+' '+size+' [ '+brandId+' ]';
        $("#productName").val(productName);

    }

    function checkDuplicateProduct() {

        var productCategory = $("#productCategory").val();

        var brandId = $("#brandId").val();
        var model = $("#model").val();

        var productName = $("#productName").val();

        var url = '<?php echo site_url("lpg/InvProductController/checkDuplicateProduct"); ?>';


        $.ajax({
            type: 'POST',
            url: url,
            data: {'productName': productName, 'productCategory': productCategory, 'brandId': brandId,'modelID':model},
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




   function isconfirmProduct2() {

        var productName = $("#productName").val();
        var productCategory = $("#productCategory").val();
        var purchases_price = $("#purchases_price").val();
        var retailPrice = $("#retailPrice").val();


         if (productCategory == '') {
            swal("Product Category!", "Validation Error!", "error");
        }  else if (productName == '') {
            swal("Give Product  Name!", "Validation Error!", "error");
        }else if (purchases_price == '') {
            swal("Give Purchases Price!", "Validation Error!", "error");
        }
        else {
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
                        $("#publicForm").submit();
                    } else {
                        return false;
                    }
                });
        }
    }

</script>

