<?php
$ProductSubCategory=get_property_list_for_show_hide(6);
$Color=get_property_list_for_show_hide(7);
$Size=get_property_list_for_show_hide(8);


?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Update Product')?> </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action=""  method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Product Code')?><span style="color:red!important"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="product_code" readonly value="<?php echo $productEdit->product_code; ?>" class="form-control" placeholder="Product Code" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Product Category')?><span style="color:red!important"> *</span></label>
                                <div class="col-sm-6">
                                    <select  name="category_id" id="productCategory"  onchange="checkDuplicateProduct()"  class="chosen-select form-control" data-placeholder="Search Product Category">
                                        <option></option>
                                        <?php foreach ($productCat as $each_info): ?>
                                            <option <?php
                                            if ($productEdit->category_id == $each_info->category_id) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $each_info->category_id; ?>"><?php echo $each_info->title; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Brand')?> </label>
                                <div class="col-sm-6">
                                    <select  name="brand"  id="brandId"  onchange="checkDuplicateProduct()"  class="chosen-select form-control" data-placeholder="Search Brand">
                                        <option></option>
                                        <?php foreach ($brandList as $each_info): ?>
                                            <option <?php
                                            if ($productEdit->brand_id == $each_info->brandId) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $each_info->brandId; ?>"><?php echo $each_info->brandName; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Model')?></label>
                                <div class="col-sm-6">
                                    <select  id="model"  onchange="checkDuplicateProduct()" name="model"  class="chosen-select form-control"  data-placeholder="Search Model">
                                        <option></option>
                                        <?php foreach ($model as $each_info): ?>
                                            <option value="<?php echo $each_info->ModelID; ?>" <?php
                                            if ($productEdit->modelID == $each_info->ModelID) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $each_info->Model; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="<?php echo $Color=='dont_have_this_property'?'display: none':''?>">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($Color)?></label>
                                <div class="col-sm-6">
                                    <select  id="color"   name="color"  class="chosen-select form-control"  data-placeholder="Search Color">
                                        <option></option>
                                        <?php foreach ($color as $each_info): ?>
                                            <option value="<?php echo $each_info->ColorID; ?>" <?php
                                            if ($productEdit->colorID == $each_info->ColorID) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $each_info->Color; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="<?php echo $Size=='dont_have_this_property'?'display: none':''?>">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase($Size)?></label>
                                <div class="col-sm-6">
                                    <select  id="size"   name="size"  class="chosen-select form-control"  data-placeholder="Search Size">
                                        <option></option>
                                        <?php foreach ($size as $each_info): ?>
                                            <option value="<?php echo $each_info->SizeID; ?>" <?php
                                            if ($productEdit->sizeID == $each_info->SizeID) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $each_info->Size; ?></option>
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
                                            if ($productEdit->unit_id == $each_info->unit_id) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $each_info->unit_id; ?>"><?php echo $each_info->unitTtile; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Product Name')?> <span style="color:red!important"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="productName" onblur="checkDuplicateProduct(this.value)" name="productName"   value="<?php echo $productEdit->productName; ?>"  class="form-control" placeholder="Product Model Name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Purchases Price')?></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="purchases_price"  value="<?php echo $productEdit->purchases_price; ?>"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="" class="form-control" placeholder="Purchases Price" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Retail Price')?> (MRP)</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="salesPrice"   value="<?php echo $productEdit->salesPrice; ?>"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="" class="form-control" placeholder="Sales Price" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Wholesale Price')?></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="retailPrice"  value="<?php echo $productEdit->retailPrice; ?>"   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  value="" class="form-control" placeholder="Wholesale Price" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Alarm Quantity')?></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="alarm_qty" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  value="<?php echo $productEdit->alarm_qty;?> " class="form-control" placeholder="Alert Quantity" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Vat')?></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="vat"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $productEdit->vat;?> " class="form-control" placeholder="Vat %" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Others Description')?></label>
                                <div class="col-sm-6">
                                    <input type="text" id="description"  name="description"  value="<?php echo $productEdit->description;?> " class="form-control" placeholder="Others Description" />

                                </div>
                            </div>

                            <!--<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="product_type_id"><?php /*echo get_phrase('Product Type')*/?><span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <select  id="product_type" name="product_type_id"  class="chosen-select form-control"  data-placeholder="Search Group Name" required="">
                                    
                                    <option value="" disabled="" selected=""> Select Product Type </option>
                                    <?php /*foreach ($product_type_list as $each_info): */?>
                                    <option  value="<?php /*echo $each_info->product_type_id; */?>" <?php /*echo  $each_info->product_type_id==$productEdit->product_type_id?'selected':'' */?>><?php /*echo $each_info->product_type_name; */?></option>
                                        <?php /*endforeach; */?>
                                </select>
                            </div>
                        </div>-->

                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-3 col-md-9">
                                    <button  onclick="return confirmSwat()"  id="subBtn" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        <?php echo  get_phrase('Update')?>
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        <?php echo get_phrase('Reset')?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<script>
    function checkDuplicateProduct(productName){
        var productCategory = $("#productCategory").val();
        var brandId = $("#brandId").val();
        var productName = $("#productName").val();
        var url = '<?php echo site_url("SetupController/checkDuplicateProduct") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{'productCategory': productCategory,'brandId': brandId,'productName': productName,'productId':'<?php echo $productEdit->product_id; ?>'},
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn").attr('disabled',true);
                    $("#errorMsg").show();
                }else{
                    $("#subBtn").attr('disabled',false);
                    $("#errorMsg").hide();
                }
            }
        });
    }
</script>