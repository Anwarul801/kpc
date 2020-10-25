<?phpif (isset($_POST['categoryId'])):    $categoryId = $this->input->post('categoryId');    $quantity1 = $this->input->post('quantity');    $sortBy = $this->input->post('sortBy');endif;?><div class="row">    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                       Low Inventory Report </div>                      </div>                      <div class="portlet-body"><div class="row">    <div class="col-md-12">        <form id="publicForm" action=""  method="post" class="form-horizontal">            <div class="col-sm-12">                <div style="background-color: grey!important;">                    <div class="col-md-4">                        <div class="form-group">                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Category</label>                            <div class="col-sm-8">                                <select  name="categoryId" class="chosen-select form-control supplierid" id="form-field-select-3" data-placeholder="Search by Category">                                    <option <?php                                if (!empty($categoryId) && $categoryId == 'All') {                                    echo "selected";                                }                                ?> value="All">All</option>                                    <?php foreach ($productCat as $eachInfo): ?>                                        <option <?php                                            if (!empty($categoryId) && $categoryId == $eachInfo->category_id) {                                                echo "selected";                                            }                                            ?> value="<?php echo $eachInfo->category_id; ?>"><?php echo $eachInfo->title; ?></option>                                    <?php endforeach; ?>                                </select>                            </div>                        </div>                    </div>                    <div class="col-md-3">                        <div class="form-group">                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Quantity</label>                            <div class="col-sm-8">                                <select  name="quantity" class="chosen-select form-control supplierid" id="form-field-select-3" data-placeholder="Search by Category"><?php$quantity = array(5, 10, 15, 20, 25, 30, 40, 50, 100, 500);foreach ($quantity as $eachInfo):    ?>                                        <option <?php    if (!empty($quantity1) && $quantity1 == $eachInfo) {        echo "selected";    }    ?> value="<?php echo $eachInfo; ?>"><?php echo $eachInfo; ?></option>                                    <?php endforeach; ?>                                </select>                            </div>                        </div>                    </div>                    <div class="col-md-3">                        <div class="form-group">                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Sort By</label>                            <div class="col-sm-8">                                <select  name="sortBy" class="chosen-select form-control supplierid" id="form-field-select-3" data-placeholder="Search by Category">                                    <option <?php                                    if ($sortBy == 1) {                                        echo "selected";                                    }                                    ?> value="1">Upper</option>                                    <option <?php                                    if ($sortBy == 2) {                                        echo "selected";                                    }                                    ?> value="2">Lower</option>                                </select>                            </div>                        </div>                    </div>                    <div class="col-md-2">                        <div class="form-group">                            <div class="col-sm-6">                                <button type="submit" class="btn btn-success btn-sm">                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>                                    Search                                </button>                            </div>                            <div class="col-sm-6">                                <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>                                    Print                                </button>                            </div>                        </div>                    </div>                </div>            </div>            <div class="clearfix"></div>        </form>    </div></div><!-- /.col --><?phpif (isset($_POST['categoryId'])):    $categoryId = $this->input->post('categoryId');    $quantity = $this->input->post('quantity');    $sortBy = $this->input->post('sortBy');    $dr = 0;    $cr = 0;    if ($categoryId == 'All'):        ?>        <div class="row">            <div class="col-xs-12">                                <table class="table table-striped table-bordered table-hover">                    <thead>                        <tr>                            <th width="10%" style="text-align:center;">SL</th>                            <th width="30%"  style="text-align:center;">Category</th>                            <th  width="30%"   style="text-align:center;">Products</th>                            <th width="30%"    style="text-align:center;">Quantity</th>                        </tr>                    </thead>                    <tbody>        <?php        $totalQty = 0;        $result = $this->Common_model->getProductLowAndHighReport($this->dist_id, $categoryId, $quantity, $sortBy);        foreach ($result as $key => $each_product):            if (!empty($each_product['productDetails']->title)):                $brandName = $this->Common_model->tableRow('brand', 'brandId', $each_product['productDetails']->brand_id)->brandName;                $totalQty+=$each_product['quantity'];                ?>                                <tr>                                    <td style="text-align:center;"><?php echo $key + 1; ?></td>                                    <td style="text-align:left;"><?php echo $each_product['productDetails']->title ?></td>                                    <td style="text-align:left;"><?php echo $each_product['productDetails']->productName . ' [ ' . $brandName . ' ] '; ?></td>                                    <td   style="text-align:right;"><?php echo $each_product['quantity']; ?></td>                                </tr>                                <?php                            endif;                        endforeach;                        ?>                    </tbody>                    <tfoot>                                                     <tr>                            <td colspan="3" align="right">Total</td>                            <td align="right"><?php echo $totalQty; ?></td>                        </tr>                    </tfoot>                    </table>             </div>        </div>    <?php else: ?>    <?php endif; ?><?php endif; ?>    </div>    </div>    </div>    </div>                             <script>                        $(document).ready(function () {            $('.date-picker').datepicker({                autoclose: true,                todayHighlight: true            })        });         </script>