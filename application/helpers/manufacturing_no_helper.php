<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/15/2019
 * Time: 10:07 AM
 */

if (!function_exists('create_manufacturing_no')) {

    function create_manufacturing_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(id) AS inserted_number FROM inventory_adjustment_info WHERE form_id!='2'";//WHERE is_active='Y' AND is_delete='N'
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_number'])):
            $totalSale = $result['inserted_number'];
        else:
            $totalSale = 0;
        endif;

        $sales_invoice_no = "MNO" . date("y") . date("m") . str_pad(($totalSale) + 1, 4, "0", STR_PAD_LEFT);
        return $sales_invoice_no;

    }
}
