<?php
/**
 * Created by PhpStorm.
 * User: AEL
 * Date: 6/18/2020
 * Time: 10:48 AM
 */
if (!function_exists('get_so_po_no')) {

    function get_so_po_no($type = '')
    {
        
        if($type=='SO'){
            $form_id=7;
            $prefix="SO";
        }
        if($type=='PO'){
            $form_id=8;
            $prefix="PO";
        }
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(id) AS inserted_so_po_number FROM so_po_info WHERE 1=1 and form_id=".$form_id;
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_so_po_number'])):
            $total_so_po = $result['inserted_so_po_number'];
        else:
            $total_so_po = 0;
        endif;

        $so_po_no = $prefix . date('y') . date('m') . str_pad(($total_so_po) + 1, 4, "0", STR_PAD_LEFT);
        return $so_po_no;

    }
}