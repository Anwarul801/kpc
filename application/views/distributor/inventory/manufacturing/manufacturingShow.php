<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */
// echo '<pre>';
// print_r($adjustment);
// exit;
?>
<script>
    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    }
</script>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase($link_page_name) ?></div>

            </div>
            <div class="portlet-body" id="pdf">
                <form id="publicForm" action="" method="post" class="form-horizontal">
                    <fieldset style="">

                        <div class="row">
                            <div class="col-md-4" style="padding-left: 0px!important;padding-right: 0px!important;">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label text-left" for="form-field-1">

                                    <?php echo get_phrase("Branch") ?> :&nbsp&nbsp <?php echo $adjustment[0]->branch_name; ?> </label>

                                    <div class="col-sm-6">
                                               

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding-left: 0px!important;padding-right: 0px!important;">
                                <div class="form-group">
                                    <label class="col-sm-7 control-label text-left" for="form-field-1" style="padding-left: 0px!important;padding-right: 0px!important;text-align: left;">

                                    <?php echo get_phrase("Reference_order_no") ?> :&nbsp&nbsp <?php echo $adjustment[0]->inv_adjustment_no; ?> </label>

                                    <div class="col-sm-5" style="padding-left: 0px!important;padding-right: 0px!important;text-align: left;">
                                               <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                <label class="col-sm-12 control-label text-left" for="form-field-1"><?php echo get_phrase('Manufactur_No') ?>: <span
                                                 style="color:red; padding-right:5px"><?php echo $adjustment[0]->order_no; ?></span></label>

                                                </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding-left: 0px!important;padding-right: 0px!important;">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label no-padding-right" for="form-field-1">
                                        <?php echo get_phrase("Date") ?> :
                                        &nbsp&nbsp <?php echo $adjustment[0]->date; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <div class="input-group">


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                        <legend style="margin-left: 20px; margin-top: 10px;">Raw Material :</legend>
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_out">
                                    <thead>
                                    <tr>
                                        <th class="text-center"><strong><?php echo get_phrase("SL") ?></strong></th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Category") ?> </strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Item") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Rate") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Qty") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Price") ?></strong>
                                        </th>

                                    </tr>

                                    </thead>
                                    <?php foreach ($adjustment as $key => $value) {
                                        if ($value->in_qty == 0) { ?>

                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?>)</td>
                                                <td class="text-center">
                                                    <?php echo $value->title; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->productName; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->unit_price; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->out_qty; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo ($value->out_qty) * ($value->unit_price); ?>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>


                                    <tbody>


                                    </tbody>
                                </table>


                                


                            </div>
                        </div>


                    </fieldset>


                    <fieldset style="">
                        

                        
                        <div class="row">
                            <legend style="margin-left: 20px; margin-top: 10px;">Finish Goods :</legend>
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_in">
                                    <thead>
                                    <tr>
                                        <th class="text-center"><strong><?php echo get_phrase("SL") ?></strong></th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Category") ?> </strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Item") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Rate") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Qty") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Price") ?></strong>
                                        </th>

                                    </tr>

                                    </thead>


                                    <tbody>
                                    <?php foreach ($adjustment as $key => $value) {
                                        if ($value->out_qty == 0) { ?>

                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?>)</td>
                                                <td class="text-center">
                                                    <?php echo $value->title; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->productName; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->unit_price; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->in_qty; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo ($value->in_qty) * ($value->unit_price); ?>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>


                                    </tbody>
                                </table>

                              

                            </div>
                        </div>



                    </fieldset>
                </form>
                <button type="button" class="btn btn-default" onclick="printContent('pdf')">Print Content</button>
                <button type="button" class="btn btn-default">Default</button>
            </div>
        </div>
    </div>
</div>


