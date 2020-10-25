<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/23/2019
 * Time: 2:15 PM
 */
if (!function_exists('create_mrn_sdc_no')) {

    function create_mrn_sdc_no($option = '',$form="")
    {
        $CI =& get_instance();
        $CI->load->database();
        if($form=='SDC'){
            $form_id=31;
        }else{
            $form_id=32;
        }
        $sql = "SELECT COUNT(sdc_mrn_no) AS inserted_voucher_number FROM sdc_mrn_info WHERE is_active='Y' and form_id =".$form_id;
        $query = $CI->db->query($sql);
        $result = $query->row_array();


        if($form=='SDC'){
            $CI->db->where('s_key', "sales_delivery_chalan_prefix");
        }else{
            $CI->db->where('s_key', "purchase_order_material_receive_prefix");
        }

        $s_material_receive_prefix = $CI->db->get('settings')->row('s_value');

        if (!empty($s_material_receive_prefix)):
            $material_receive_prefix = $s_material_receive_prefix;
        else:
            $material_receive_prefix = "MRN";
        endif;

        if (!empty($result['inserted_voucher_number'])):
            $total_mrn_sdc = $result['inserted_voucher_number'];
        else:
            $total_mrn_sdc = 0;
        endif;

        $purchase_invoice_no = $material_receive_prefix . date('y') . date('m') . str_pad(($total_mrn_sdc) + 1, 4, "0", STR_PAD_LEFT);
        return $purchase_invoice_no;

    }
}