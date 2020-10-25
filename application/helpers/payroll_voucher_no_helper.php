
<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/23/2019
 *
 * Time: 2:15 PM
 */
if (!function_exists('payroll_voucher_no')) {

    function payroll_voucher_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(salaryID) AS inserted_voucher_number FROM salary_info";
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_voucher_number'])):
            $totalPurchases = $result['inserted_voucher_number'];
        else:
            $totalPurchases = 0;
        endif;

        $purchase_invoice_no = "EPV" . date('y') . date('m') . str_pad(($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        return $purchase_invoice_no;

    }
}