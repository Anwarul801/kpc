<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 7/10/2020
 * Time: 10:28 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseEditLpgController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;
    public $link_icon_add;
    public $link_icon_list;
    public $link_icon_edit;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Purchases_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->invoice_type = 1;
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/cylinderInOut/';
        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_edit = "<i class='fa fa-edit'></i>";
        $this->lang->load('content', 'english');
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function purchase_delete()
    {
        // $invoiceId = 6;
        $invoiceId = $this->input->post('id');
        /*$customer_money_revcive = $this->_check_the_invoice_have_bill_to_bill_transction($invoiceId);
        if(!empty($customer_money_revcive)){
            $msg = 'Sales Invoice Cannot Delete .This Sales Invoice have accounting transaction  ' ;
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/salesInvoiceLpg'));
        }*/
        $this->db->trans_begin();
        $this->_save_data_to_purchase_history_table($invoice_id = $invoiceId, $action = 'delete');

        $sales_inv = array();
        $sales_inv['is_active'] = 'N';
        $sales_inv['is_delete'] = 'Y';
        $sales_invUpdateCondition = array(
            'purchase_invoice_id' => $invoiceId,
            //'invoice_for' => 2,
        );
        $this->Common_model->save_and_check('purchase_invoice_info', $sales_inv, $sales_invUpdateCondition);

        $UpdateAccountsMasterCondition = array(
            'for' => 1,
            //'BackReferenceInvoiceNo' => $this->input->post('voucherid'),
            'BackReferenceInvoiceID' => $invoiceId,
        );


        $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $UpdateAccountsMasterCondition);

        $accountingVoucherId = $checkArray->Accounts_VoucherMst_AutoID;


        $DeleteCondition_purchase_details = array(
            'purchase_invoice_id' => $invoiceId
        );
        $this->Common_model->delete_data_with_condition('purchase_details', $DeleteCondition_purchase_details);
        $this->Common_model->delete_data_with_condition('purchase_return_details', $DeleteCondition_purchase_details);
        $DeleteConditionStock = array(
            'invoice_id' => $invoiceId,
            'form_id' => 2
        );
        $this->Common_model->delete_data_with_condition('stock', $DeleteConditionStock);

        $DeleteConditionMoneyreceit = array(
            'invoiceID' => $invoiceId,
            'receiveType' => 2,
        );
        $this->Common_model->delete_data_with_condition('moneyreceit', $DeleteConditionMoneyreceit);

        $accountingMasterTable['IsActive'] = 0;
        $accountingMasterTable['Created_By'] = $this->admin_id;
        $accountingMasterTable['Created_Date'] = $this->timestamp;


        $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable, $UpdateAccountsMasterCondition);

        $DeleteCondition_ac_tb_accounts_voucherdtl = array(
            'Accounts_VoucherMst_AutoID' => $accountingVoucherId
        );
        $this->Common_model->delete_data_with_condition('ac_tb_accounts_voucherdtl', $DeleteCondition_ac_tb_accounts_voucherdtl);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = 'sales Invoice ' . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            echo 2;
        } else {
            $this->db->trans_commit();
            $msg = 'sales Invoice ' . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
            echo 1;
        }

    }

    public function _save_data_to_purchase_history_table($invoice_id, $action = array('edit', 'delete'))
    {


        // $this->db->trans_begin();
        $sql_purchase_invoice_info_audit = "create table IF NOT EXISTS purchase_invoice_info_audit(purchase_invoice_info_history_id int not null auto_increment, PRIMARY KEY (purchase_invoice_info_history_id)) as select * from purchase_invoice_info where 1=3";
        $this->db->query($sql_purchase_invoice_info_audit);
        $sql_purchase_details_audit = "create table IF NOT EXISTS purchase_details_audit(purchase_invoice_info_history_id int not null ) as select * from purchase_details where 1=3";
        $this->db->query($sql_purchase_details_audit);
        $sql_purchase_return_details_audit = "create table IF NOT EXISTS purchase_return_details_audit(purchase_invoice_info_history_id int not null ) as select * from purchase_return_details where 1=3";
        $this->db->query($sql_purchase_return_details_audit);

        $sql_stock_audit = "create table IF NOT EXISTS stock_audit(invoice_info_history_id int not null ) as select * from stock where 1=3";
        $this->db->query($sql_stock_audit);
        $sql_moneyreceit_audit = "create table IF NOT EXISTS moneyreceit_audit(invoice_info_history_id int not null ) as select * from moneyreceit where 1=3";
        $this->db->query($sql_moneyreceit_audit);


        $query = $this->db->field_exists('is_active', 'moneyreceit_audit');
        if ($query != TRUE) {
            $query_moneyreceit_audit_audit = "ALTER TABLE `moneyreceit_audit` ADD `is_active` ENUM('Y','N') NULL , ADD `is_delete` ENUM('Y','N') NULL , ADD `update_by` INT(11) NULL , ADD `update_date` DATETIME NULL , ADD `delete_by` INT(11) NULL , ADD `delete_date` DATETIME NULL ";
            $this->db->query($query_moneyreceit_audit_audit);
        }

        //create table student_2(ID int not null auto_increment, PRIMARY KEY (ID)) as select * from student

        $purchase_invoice_info_old_condition = array(
            'purchase_invoice_id' => $invoice_id,
        );
        $purchase_invoice_info_old_array = $this->Common_model->get_data_list_by_many_columns_array('purchase_invoice_info', $purchase_invoice_info_old_condition);
        if ($action == 'edit') {
            $purchase_invoice_info_old_array[0]['is_active'] = 'Y';
            $purchase_invoice_info_old_array[0]['is_delete'] = 'N';
            $purchase_invoice_info_old_array[0]['update_by'] = $this->admin_id;
            $purchase_invoice_info_old_array[0]['update_date'] = $this->timestamp;;
            $purchase_invoice_info_old_array[0]['delete_by'] = '';
            $purchase_invoice_info_old_array[0]['delete_date'] = NULL;
        } elseif ($action == 'delete') {
            $purchase_invoice_info_old_array[0]['is_active'] = 'N';
            $purchase_invoice_info_old_array[0]['is_delete'] = 'Y';
            $purchase_invoice_info_old_array[0]['update_by'] = "";
            $purchase_invoice_info_old_array[0]['update_date'] = NULL;
            $purchase_invoice_info_old_array[0]['delete_by'] = $this->admin_id;
            $purchase_invoice_info_old_array[0]['delete_date'] = $this->timestamp;
        }
        $purchase_invoice_info_history_id = $this->Common_model->insert_data('purchase_invoice_info_audit', $purchase_invoice_info_old_array[0]);


        $purchase_details_old_array = array();
        $purchase_details_old_condition = array(
            'purchase_invoice_id' => $invoice_id,
        );

        $purchase_details_old_array = $this->Common_model->get_data_list_by_many_columns_array('purchase_details', $purchase_details_old_condition);

        foreach ($purchase_details_old_array as $key => $csm) {
            if ($action == 'edit') {
                $purchase_details_old_array[$key]['purchase_invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $purchase_details_old_array[$key]['is_active'] = 'Y';
                $purchase_details_old_array[$key]['is_delete'] = 'N';
                $purchase_details_old_array[$key]['update_by'] = $this->admin_id;
                $purchase_details_old_array[$key]['update_date'] = $this->timestamp;;
                $purchase_details_old_array[$key]['delete_by'] = '';
                $purchase_details_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $purchase_details_old_array[$key]['purchase_invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $purchase_details_old_array[$key]['is_active'] = 'N';
                $purchase_details_old_array[$key]['is_delete'] = 'Y';
                $purchase_details_old_array[$key]['update_by'] = "";
                $purchase_details_old_array[$key]['update_date'] = NULL;
                $purchase_details_old_array[$key]['delete_by'] = $this->admin_id;
                $purchase_details_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('purchase_details_audit', $purchase_details_old_array);


        $purchase_return_details_old_array = array();
        $purchase_return_details_old_condition = array(
            'purchase_invoice_id' => $invoice_id,
        );

        $purchase_return_details_old_array = $this->Common_model->get_data_list_by_many_columns_array('purchase_return_details', $purchase_return_details_old_condition);

        foreach ($purchase_return_details_old_array as $key => $csm) {
            if ($action == 'edit') {
                $purchase_return_details_old_array[$key]['purchase_invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $purchase_return_details_old_array[$key]['is_active'] = 'Y';
                $purchase_return_details_old_array[$key]['is_delete'] = 'N';
                $purchase_return_details_old_array[$key]['update_by'] = $this->admin_id;
                $purchase_return_details_old_array[$key]['update_date'] = $this->timestamp;;
                $purchase_return_details_old_array[$key]['delete_by'] = '';
                $purchase_return_details_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $purchase_return_details_old_array[$key]['purchase_invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $purchase_return_details_old_array[$key]['is_active'] = 'N';
                $purchase_return_details_old_array[$key]['is_delete'] = 'Y';
                $purchase_return_details_old_array[$key]['update_by'] = "";
                $purchase_return_details_old_array[$key]['update_date'] = NULL;
                $purchase_return_details_old_array[$key]['delete_by'] = $this->admin_id;
                $purchase_return_details_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('purchase_return_details_audit', $purchase_return_details_old_array);


        $stock_old_array = array();
        $stock_old_old_condition = array(
            'invoice_id' => $invoice_id,
            'form_id' => 2,
        );

        $stock_old_array = $this->Common_model->get_data_list_by_many_columns_array('stock', $stock_old_old_condition);

        foreach ($stock_old_array as $key => $csm) {
            if ($action == 'edit') {
                $stock_old_array[$key]['invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $stock_old_array[$key]['is_active'] = 'Y';
                $stock_old_array[$key]['is_delete'] = 'N';
                $stock_old_array[$key]['update_by'] = $this->admin_id;
                $stock_old_array[$key]['update_date'] = $this->timestamp;;
                $stock_old_array[$key]['delete_by'] = '';
                $stock_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $stock_old_array[$key]['invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $stock_old_array[$key]['is_active'] = 'N';
                $stock_old_array[$key]['is_delete'] = 'Y';
                $stock_old_array[$key]['update_by'] = "";
                $stock_old_array[$key]['update_date'] = NULL;
                $stock_old_array[$key]['delete_by'] = $this->admin_id;
                $stock_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('stock_audit', $stock_old_array);


        $moneyreceit_old_array = array();
        $moneyreceit_oldcondition = array(
            'invoiceID' => $invoice_id,
            'receiveType' => 2,
        );
        $moneyreceit_old_array = $this->Common_model->get_data_list_by_many_columns_array('moneyreceit', $moneyreceit_oldcondition);

        foreach ($moneyreceit_old_array as $key => $csm) {
            if ($action == 'edit') {
                $moneyreceit_old_array[$key]['invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $moneyreceit_old_array[$key]['is_active'] = 'Y';
                $moneyreceit_old_array[$key]['is_delete'] = 'N';
                $moneyreceit_old_array[$key]['update_by'] = $this->admin_id;
                $moneyreceit_old_array[$key]['update_date'] = $this->timestamp;;
                $moneyreceit_old_array[$key]['delete_by'] = '';
                $moneyreceit_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $moneyreceit_old_array[$key]['invoice_info_history_id'] = $purchase_invoice_info_history_id;
                $moneyreceit_old_array[$key]['is_active'] = 'N';
                $moneyreceit_old_array[$key]['is_delete'] = 'Y';
                $moneyreceit_old_array[$key]['update_by'] = "";
                $moneyreceit_old_array[$key]['update_date'] = NULL;
                $moneyreceit_old_array[$key]['delete_by'] = $this->admin_id;
                $moneyreceit_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('moneyreceit_audit', $moneyreceit_old_array);


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = 'sales Invoice ' . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/viewLpgCylinder/' . $invoice_id));
        } else {
            // $this->db->trans_commit();
            /*$msg = 'sales Invoice ' . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/viewLpgCylinder/' . $invoice_id));*/
        }
        //$this->db->trans_begin();


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = 'sales Invoice ' . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/viewLpgCylinder/' . $invoice_id));
        } else {
            //$this->db->trans_commit();
            /*$msg = 'sales Invoice ' . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/viewLpgCylinder/' . $invoice_id));*/
        }

        $UpdateAccountsMasterCondition = array(
            'for' => 1,
            'BackReferenceInvoiceNo' => $purchase_invoice_info_old_array[0]['invoice_no'],
            'BackReferenceInvoiceID' => $invoice_id,
        );


        $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $UpdateAccountsMasterCondition);
        $Accounts_VoucherMst_AutoID = $checkArray->Accounts_VoucherMst_AutoID;
        $this->_save_data_to_accounting_history_table($Accounts_VoucherMst_AutoID, $action = array('edit', 'delete'), $voucher_name = 'sales Invoice', $redrict_page = 'salesInvoice_edit', $invoice_id);


    }

    public function _save_data_to_accounting_history_table($Accounts_VoucherMst_AutoID, $action = array('edit', 'delete'), $voucher_name = array('sales Invoice', 'Payment Voucher', "Receive Voucher", 'Journal Voucher'), $redrict_page, $invoice_id = 'null')
    {


        if ($action == "edit") {
            $error_mes = $this->config->item("update_error_message");
            $success_mes = $this->config->item("update_success_message");
        } else {
            $error_mes = $this->config->item("delete_error_message");
            $success_mes = $this->config->item("delete_success_message");
        }


        //$this->db->trans_begin();
        $ac_accounts_vouchermst_audit = "create table IF NOT EXISTS ac_accounts_vouchermst_audit(ac_accounts_vouchermst_audit_id int not null auto_increment, PRIMARY KEY (ac_accounts_vouchermst_audit_id)) as select * from ac_accounts_vouchermst where 1=3";
        $this->db->query($ac_accounts_vouchermst_audit);
        $ac_tb_accounts_voucherdtl_audit = "create table IF NOT EXISTS ac_tb_accounts_voucherdtl_audit(ac_accounts_vouchermst_audit_id int not null ) as select * from ac_tb_accounts_voucherdtl where 1=3";
        $this->db->query($ac_tb_accounts_voucherdtl_audit);

        $query = $this->db->field_exists('is_active', 'ac_accounts_vouchermst_audit');
        if ($query != TRUE) {
            $query_ac_accounts_vouchermst_audit = "ALTER TABLE `ac_accounts_vouchermst_audit` ADD `is_active` ENUM('Y','N') NULL , ADD `is_delete` ENUM('Y','N') NULL , ADD `update_by` INT(11) NULL , ADD `update_date` DATETIME NULL , ADD `delete_by` INT(11) NULL , ADD `delete_date` DATETIME NULL ";
            $this->db->query($query_ac_accounts_vouchermst_audit);
            $ac_tb_accounts_voucherdtl_audit = "ALTER TABLE `ac_tb_accounts_voucherdtl_audit` ADD `is_active` ENUM('Y','N') NULL , ADD `is_delete` ENUM('Y','N') NULL , ADD `update_by` INT(11) NULL , ADD `update_date` DATETIME NULL , ADD `delete_by` INT(11) NULL , ADD `delete_date` DATETIME NULL ";
            $this->db->query($ac_tb_accounts_voucherdtl_audit);
        }

        $query = $this->db->field_exists('due_collection_details_id', 'ac_accounts_vouchermst_audit');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'due_collection_details_id' => array(
                    'type' => 'INT',
                    'null' => TRUE,
                    'default' => '0',
                    //'unsigned' => TRUE,
                    'after' => 'for')
            );
            $this->dbforge->add_column('ac_accounts_vouchermst_audit', $fields);
        }

        $ac_accounts_vouchermst_old_array = array();
        $stock_old_old_condition = array(
            'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID,

        );
        $ac_accounts_vouchermst_old_array = $this->Common_model->get_data_list_by_many_columns_array('ac_accounts_vouchermst', $stock_old_old_condition);
        foreach ($ac_accounts_vouchermst_old_array as $key => $csm) {
            if ($action == 'edit') {
                $ac_accounts_vouchermst_old_array[$key]['is_active'] = 'Y';
                $ac_accounts_vouchermst_old_array[$key]['is_delete'] = 'N';
                $ac_accounts_vouchermst_old_array[$key]['update_by'] = $this->admin_id;
                $ac_accounts_vouchermst_old_array[$key]['update_date'] = $this->timestamp;;
                $ac_accounts_vouchermst_old_array[$key]['delete_by'] = '';
                $ac_accounts_vouchermst_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $ac_accounts_vouchermst_old_array[$key]['is_active'] = 'N';
                $ac_accounts_vouchermst_old_array[$key]['is_delete'] = 'Y';
                $ac_accounts_vouchermst_old_array[$key]['update_by'] = "";
                $ac_accounts_vouchermst_old_array[$key]['update_date'] = NULL;
                $ac_accounts_vouchermst_old_array[$key]['delete_by'] = $this->admin_id;
                $ac_accounts_vouchermst_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $ac_accounts_vouchermst_audit_id = $this->Common_model->insert_data('ac_accounts_vouchermst_audit', $ac_accounts_vouchermst_old_array[0]);

        $ac_tb_accounts_voucherdtl_old_array = array();
        $ac_tb_accounts_voucherdtl_old_condition = array(
            'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID,
        );
        $ac_tb_accounts_voucherdtl_old_array = $this->Common_model->get_data_list_by_many_columns_array('ac_tb_accounts_voucherdtl', $ac_tb_accounts_voucherdtl_old_condition);
        foreach ($ac_tb_accounts_voucherdtl_old_array as $key => $csm) {
            if ($action == 'edit') {
                $ac_tb_accounts_voucherdtl_old_array[$key]['ac_accounts_vouchermst_audit_id'] = $ac_accounts_vouchermst_audit_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_active'] = 'Y';
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_delete'] = 'N';
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_by'] = $this->admin_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_date'] = $this->timestamp;;
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_by'] = '';
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $ac_tb_accounts_voucherdtl_old_array[$key]['ac_accounts_vouchermst_audit_id'] = $ac_accounts_vouchermst_audit_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_active'] = 'N';
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_delete'] = 'Y';
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_by'] = "";
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_date'] = NULL;
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_by'] = $this->admin_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl_audit', $ac_tb_accounts_voucherdtl_old_array);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = $voucher_name . ' ' . $error_mes;
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/' . $redrict_page . '/' . $Accounts_VoucherMst_AutoID));
        } else {
            //$this->db->trans_commit();
            // $msg = $voucher_name . ' ' . $success_mes;
            // $this->session->set_flashdata('success', $msg);
            // redirect(site_url($this->project . '/'.$redrict_page.'/' . $Accounts_VoucherMst_AutoID));
        }

        //$this->db->trans_begin();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = $voucher_name . ' ' . $error_mes;
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/' . $redrict_page . '/' . $Accounts_VoucherMst_AutoID));
        } else {
            // $this->db->trans_commit();
            // $msg = $voucher_name . ' ' . $success_mes;
            // $this->session->set_flashdata('success', $msg);
            // redirect(site_url($this->project . '/'.$redrict_page.'/' . $Accounts_VoucherMst_AutoID));
        }


    }

    public function purchases_lpg_edit($purchases_id = null)
    {


        if (isPostBack()) {


           //form validation rules
            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            $this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            $this->form_validation->set_rules('slNo[]', 'Payment Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/purchases_lpg_add'));
            } else {
                $totalGR_DEBIT = 0;
                $totalGR_CREDIT = 0;
                $bankName = $this->input->post('bankName');
                $purchasesDate = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $paymentType = $this->input->post('paymentType');
                $branch_id = $this->input->post('branch_id');
                $supplierID = $this->input->post('supplierID');
                $invoice_no = $this->input->post('voucherid');
                $this->db->trans_start();
                $this->_check_the_product_stock_for_edit_delete($purchases_id);

                $UpdateAccountsMasterCondition = array(
                    'for' => 1,
                    'BackReferenceInvoiceNo' => $this->input->post('voucherid'),
                    'BackReferenceInvoiceID' => $purchases_id,
                );
                $this->_save_data_to_purchase_history_table($invoice_id = $purchases_id, $action = 'edit');


                $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $UpdateAccountsMasterCondition);

                $accountingVoucherId = $checkArray->Accounts_VoucherMst_AutoID;


                $DeleteCondition_sales_details = array(
                    'purchase_invoice_id' => $purchases_id
                );
                $this->Common_model->delete_data_with_condition('purchase_details', $DeleteCondition_sales_details);
                $this->Common_model->delete_data_with_condition('purchase_return_details', $DeleteCondition_sales_details);
                $DeleteConditionStock = array(
                    'invoice_id' => $purchases_id,
                    'form_id' => 2
                );
                $this->Common_model->delete_data_with_condition('stock', $DeleteConditionStock);

                $DeleteConditionMoneyreceit = array(
                    'invoiceID' => $purchases_id,
                    'receiveType' => 2,
                );
                $this->Common_model->delete_data_with_condition('moneyreceit', $DeleteConditionMoneyreceit);


                $DeleteCondition_ac_tb_accounts_voucherdtl = array(
                    'Accounts_VoucherMst_AutoID' => $accountingVoucherId
                );
                $this->Common_model->delete_data_with_condition('ac_tb_accounts_voucherdtl', $DeleteCondition_ac_tb_accounts_voucherdtl);


                $newCylinderProductCost = 0;

                /* this invoice no is comming  from purchase_invoice_no_helper */
                if ($paymentType == 3) {
                    $paid_amount = $this->input->post('netTotal');
                } else {
                    $paid_amount = $this->input->post('thisAllotment') != '' ? $this->input->post('thisAllotment') : 0;
                }
                $purchase_inv['supplier_invoice_no'] = $this->input->post('userInvoiceId');
                $purchase_inv['supplier_id'] = $supplierID;
                $purchase_inv['payment_type'] = $paymentType;
                $purchase_inv['invoice_amount'] = $this->input->post('netTotal');
                $purchase_inv['vat_amount'] = 0;
                $purchase_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $purchase_inv['paid_amount'] = $paid_amount;
                $purchase_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $purchase_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $purchase_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $purchase_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $purchase_inv['refference_person'] = $this->input->post('reference');
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = $branch_id;
                $purchase_inv['due_date'] = $this->input->post('dueDate') != '' ? date('Y-m-d', strtotime($this->input->post('dueDate'))) : '';//date('Y-m-d', strtotime($this->input->post('')));
                $purchase_inv['invoice_date'] = $purchasesDate; //date('Y-m-d', strtotime($this->input->post('')));
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';
                $purchase_inv['for'] = 2;
                if ($paymentType == 3) {
                    $purchase_inv['bank_id'] = $bankName;
                    //$purchase_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $purchase_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $purchase_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }

                $purchase_invUpdateCondition = array(
                    'purchase_invoice_id ' => $purchases_id,

                );


                $this->Common_model->save_and_check('purchase_invoice_info', $purchase_inv, $purchase_invUpdateCondition);

                $allStock = array();


                if ($paymentType == 2) {
                    //for due invoice  Journal Voucher
                    $voucher_no = create_journal_voucher_no();
                    $AccouVoucherType_AutoID = 3;
                } else {
                    //Payment Voucher
                    $this->load->helper('create_payment_voucher_no_helper');
                    $voucher_no = create_payment_voucher_no();
                    $AccouVoucherType_AutoID = 2;
                }
                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;

                $accountingMasterTable['Accounts_Voucher_Date'] = $purchasesDate;

                $accountingMasterTable['Narration'] = 'Purchase Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['supplier_id'] = $this->input->post('supplierID');
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingMasterTable['for'] = 1;

                $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable, $UpdateAccountsMasterCondition);


                $EmptyCylinderProductCost = 0;
                $RefillProductCost = 0;
                $otherProductCost = 0;
                $allEmptyCylinderArray = array();
                $allEmptyCylinderReturnArray = array();
                $allEmptyCylinderWithRefillArray = array();
                $allRefillCylinderArray = array();
                $allOtherProductArray = array();
                foreach ($_POST['slNo'] as $key => $value) {
                    $supplier_advance = 0;
                    $supplier_due = 0;
                    $emptyCylindet = array();
                    $emptyCylindetReturn = array();
                    $emptyCylindetWithRefill = array();
                    $refillCylindet = array();
                    $otherProduct = array();

                    unset($stock);
                    $returnable_quantity = $_POST['add_returnAble'][$value] != '' ? $_POST['add_returnAble'][$value] : 0;
                    $return_quentity = empty($_POST['returnQuentity_' . $value]) ? 0 : array_sum($_POST['returnQuentity_' . $value]);
                    if ($returnable_quantity < $return_quentity) {
                        $supplier_advance = $return_quentity - $returnable_quantity;
                    } else {
                        $supplier_due = $returnable_quantity - $return_quentity;
                    }
                    $stock['purchase_invoice_id'] = $purchases_id;
                    $stock['product_id'] = $product_id = $_POST['product_id_' . $value];
                    $stock['package_id'] = $_POST['package_id_' . $value];
                    $stock['is_package '] = $_POST['is_package_' . $value];
                    $stock['returnable_quantity '] = $returnable_quantity;
                    $stock['return_quentity '] = $return_quentity;
                    $stock['supplier_due'] = $supplier_due;
                    $stock['supplier_advance'] = $supplier_advance;
                    $stock['quantity'] = $_POST['quantity_' . $value];
                    $stock['unit_price'] = $_POST['rate_' . $value];
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $stock['branch_id'] = $branch_id;
                    $stock['supplier_id '] = $supplierID;
                    $stock['property_1'] = $_POST['property_1_' . $value];
                    $stock['property_2'] = $_POST['property_2_' . $value];
                    $stock['property_3'] = $_POST['property_3_' . $value];
                    $stock['property_4'] = $_POST['property_4_' . $value];
                    $stock['property_5'] = $_POST['property_5_' . $value];
                    $purchase_details_id = $this->Common_model->insert_data('purchase_details', $stock);

                    $category_id = $this->Common_model->tableRow('product', 'product_id', $product_id)->category_id;
                    //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                    if ($category_id == 1) {
                        //Empty Cylinder
                        $EmptyCylinderProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $emptyCylindet['product_id'] = $_POST['product_id_' . $value];
                        $emptyCylindet['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allEmptyCylinderArray[] = $emptyCylindet;
                    } elseif ($category_id == 2) {
                        //Refill
                        $RefillProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $refillCylindet['product_id'] = $_POST['product_id_' . $value];
                        $refillCylindet['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allRefillCylinderArray[] = $refillCylindet;
                    } else {
                        $otherProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $otherProduct['product_id'] = $_POST['product_id_' . $value];
                        $otherProduct['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allOtherProductArray[] = $otherProduct;
                    }
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        $packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);


                        if ($packageEmptyProductId == "") {
                            $this->db->trans_rollback();
                            $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message") . ' There is something wrong please try again .contact with Customer Care';
                            $this->session->set_flashdata('error', $msg);
                            redirect(site_url($this->project . '/purchases_lpg_add'));
                        }


                        //sitehelper
                        //$product_last_purchase_price=get_product_last_purchase_price($packageEmptyProductId);
                        //$product_last_purchase_price=$this->Sales_Model->emptyCylinderPurchasePrice($packageEmptyProductId, $this->dist_id);;
                        $product_last_purchase_price = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $packageEmptyProductId)->purchases_price;

                        $emptyCylindetWithRefill['product_id'] = $packageEmptyProductId;
                        $emptyCylindetWithRefill['price'] = ($product_last_purchase_price * $_POST['quantity_' . $value]);
                        $allEmptyCylinderWithRefillArray[] = $emptyCylindetWithRefill;
                        $productCost = $this->Sales_Model->emptyCylinderPurchasePrice($packageEmptyProductId, $this->dist_id);


                        unset($stock);

                        $stock['purchase_invoice_id'] = $purchases_id;
                        $stock['product_id'] = $packageEmptyProductId;
                        $stock['package_id'] = 0;
                        $stock['is_package '] = 0;
                        $stock['returnable_quantity '] = 0;
                        $stock['return_quentity '] = 0;
                        $stock['supplier_due'] = 0;
                        $stock['supplier_advance'] = 0;
                        $stock['quantity'] = $_POST['quantity_' . $value];
                        $stock['unit_price'] = ($product_last_purchase_price);
                        $stock['insert_by'] = $this->admin_id;
                        $stock['insert_date'] = $this->timestamp;
                        $stock['branch_id'] = $branch_id;
                        $stock['supplier_id '] = $supplierID;
                        $stock['show_in_invoice'] = 0;
                        
                        $this->Common_model->insert_data('purchase_details', $stock);


                    }
                    if (isset($_POST['returnproduct_' . $value])) {
                        foreach ($_POST['returnproduct_' . $value] as $key1 => $value1) {
                            unset($stock2);

                            //$product_last_purchase_price=$_POST['returnQuentity_Price_' . $value][$key1];
                            $product_last_purchase_price = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $value1)->purchases_price;

                            $emptyCylindetReturn['product_id'] = $value1;
                            $emptyCylindetReturn['price'] = ($product_last_purchase_price * $_POST['returnQuentity_' . $value][$key1]);
                            $allEmptyCylinderReturnArray[] = $emptyCylindetReturn;
                            $stock2['purchase_details_id'] = $purchase_details_id;
                            $stock2['product_id'] = $value1;
                            $stock2['returnable_quantity'] = $_POST['returnQuentity_' . $value][$key1];
                            $stock2['purchase_invoice_id'] = $purchases_id;
                            $stock2['supplier_id '] = $supplierID;
                            $stock2['return_quantity'] = $_POST['returnQuentity_' . $value][$key1];
                            $stock2['insert_by'] = $this->admin_id;
                            $stock2['insert_date'] = $this->timestamp;
                            $stock2['branch_id'] = $branch_id;
                            //$stock2['unit_price'] = get_product_purchase_price($value1);;
                            $stock2['unit_price'] = $product_last_purchase_price;

                            $allStock[] = $stock2;
                        }
                    }
                }

                $this->db->insert_batch('purchase_return_details', $allStock);


                log_message('error',"allEmptyCylinderWithRefillArray".print_r($allEmptyCylinderWithRefillArray,true));
                log_message('error',"allEmptyCylinderReturnArray".print_r($allEmptyCylinderReturnArray,true));

                $accountingDetailsTable = array();


                if (!empty($allEmptyCylinderArray)) {
                    foreach ($allEmptyCylinderArray as $keyEmpCly => $valueEmpCly) {
                        $condition = array(
                            'related_id' => $valueEmpCly['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                        $accountingDetailsTable['GR_DEBIT'] = $valueEmpCly['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'New Cylinder Stock';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + $valueEmpCly['price'];
                        $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    }
                }


                if (!empty($allRefillCylinderArray)) {
                    foreach ($allRefillCylinderArray as $keyRefill => $valueRefill) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueRefill['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                        $accountingDetailsTable['GR_DEBIT'] = $valueRefill['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'Refill  Cylinder Purchase';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + $valueRefill['price'];
                        $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    }
                }

                if (!empty($allEmptyCylinderWithRefillArray)) {
                    foreach ($allEmptyCylinderWithRefillArray as $keyEmptyWithRefill => $valueEmptyWithRefill) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueEmptyWithRefill['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                        $accountingDetailsTable['GR_DEBIT'] = $valueEmptyWithRefill['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'Empty Cylinder With Refill Cylinder Purchase';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();

                        $totalGR_DEBIT = $totalGR_DEBIT + $valueEmptyWithRefill['price'];
                        $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    }
                }

                if (!empty($allEmptyCylinderReturnArray)) {
                    foreach ($allEmptyCylinderReturnArray as $keyEmpClyReturn => $valueEmpClyReturn) {
                        $condition = array(
                            'related_id' => $valueEmpClyReturn['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '2';//Cr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                        $accountingDetailsTable['GR_DEBIT'] = '0.00';
                        $accountingDetailsTable['GR_CREDIT'] = $valueEmpClyReturn['price'];
                        $accountingDetailsTable['Reference'] = 'Empty Cylinder Return';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();

                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueEmpClyReturn['price'];
                    }
                }
                if (!empty($allOtherProductArray)) {
                    foreach ($allOtherProductArray as $keyOtherProduct => $valueOtherProduct) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueOtherProduct['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        /*Inventory stock=>20*/
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                        $accountingDetailsTable['GR_DEBIT'] = $valueOtherProduct['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'General Product ';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();

                        $totalGR_DEBIT = $totalGR_DEBIT + $valueOtherProduct['price'];
                        $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    }
                }
                /*Inventory stock Refill=>95*/
                /*Inventory Stock New Cylinder Stock*/
                /*Loading and wages*/
                if ($this->input->post('loaderAmount') > 0) {
                    //Loading & Wages====>47  Loading_Wages
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $this->config->item("Common_Loading_Wages");//47';
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('loaderAmount');
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'Loading and wages Cost Of Goods';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;
                    $accountingDetailsTable = array();

                    $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('loaderAmount');
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                }
                /*Loading and wages*/
                /*Transportation =====>42*/
                if ($this->input->post('transportationAmount') > 0) {
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $this->config->item("Common_Transportation");//Transportation'42';
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('transportationAmount');
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'Transportation Cost Of Goods';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;
                    $accountingDetailsTable = array();

                    $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('transportationAmount');
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                }
                /*Transportation*/
                $condtion = array(
                    'related_id' => $supplierID,
                    'related_id_for' => 2,
                    'is_active' => "Y",
                );
                $supplier_payable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                /*Supplier Payable =======>34*/
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable['TypeID'] = '2';//Cr
                $accountingDetailsTable['CHILD_ID'] = $supplier_payable->id;//Supplier_Payables'34';
                $accountingDetailsTable['GR_DEBIT'] = '0.00';
                $accountingDetailsTable['GR_CREDIT'] = $this->input->post('netTotal');
                $accountingDetailsTable['Reference'] = 'Supplier Payable';
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Created_By'] = $this->admin_id;
                $accountingDetailsTable['Created_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = $branch_id;
                $accountingDetailsTable['date'] = $purchasesDate;
                $finalDetailsArray[] = $accountingDetailsTable;
                $accountingDetailsTable = array();
                $totalGR_DEBIT = $totalGR_DEBIT + 0;
                $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('netTotal');
                // }
                /*Supplier Payable*/
                log_message('error', 'This is form purchase invoice nahid' . print_r($totalGR_DEBIT, true));
                log_message('error', 'This is form purchase invoice nahid' . print_r($totalGR_CREDIT, true));
                log_message('error', '$allEmptyCylinderReturnArray $allEmptyCylinderReturnArray' . print_r($allEmptyCylinderReturnArray, true));


                if ($paymentType == 4) {
                    //cash payment
                    /*Supplier Payable =======>34*/
                    //supplier paid amount
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $supplier_payable->id;//Supplier_Payables'34';
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('thisAllotment');
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'Supplier paid amount';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;
                    $accountingDetailsTable = array();

                    $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('thisAllotment');
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    //supplier paid amount
                    /*Cash in hand*/
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '2';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $this->input->post('accountCrPartial');
                    $accountingDetailsTable['GR_DEBIT'] = '0.00';
                    $accountingDetailsTable['GR_CREDIT'] = $this->input->post('thisAllotment');
                    $accountingDetailsTable['Reference'] = 'Supplier Paid Amount in Cash In Hand Group';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;


                    $accountingDetailsTable = array();

                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('thisAllotment');
                }
                $totalGR_DEBIT = number_format($totalGR_DEBIT, 2, '.', '');

                $totalGR_CREDIT = number_format($totalGR_CREDIT, 2, '.', '');
                log_message('error', 'This is form purchase invoice ' . print_r($totalGR_DEBIT, true));
                log_message('error', 'This is form purchase invoice ' . print_r($totalGR_CREDIT, true));
                if ($totalGR_DEBIT != $totalGR_CREDIT) {
                    $this->db->trans_rollback();
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message") . ' There is something wrong please try again .contact with Customer Care';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/purchases_lpg_add'));
                }


                if (!empty($finalDetailsArray)) {

                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                }
                /*client_vendor_ledger table data insert*/
                $supp = array(
                    'ledger_type' => 2,
                    'trans_type' => 'purchase',
                    'history_id' => 0,
                    'trans_type' => $this->input->post('voucherid'),
                    'client_vendor_id' => $this->input->post('supplierID'),
                    'invoice_id' => $purchases_id,
                    'invoice_type' => '1',
                    'Accounts_VoucherType_AutoID' => '5',
                    'updated_by' => $this->admin_id,
                    'dist_id' => $this->dist_id,
                    'amount' => $this->input->post('netTotal'),
                    'dr' => $this->input->post('netTotal'),
                    'date' => $purchasesDate,
                    'paymentType' => 'Purchase Voucher',
                    'BranchAutoId' => $branch_id
                );
                $this->db->insert('client_vendor_ledger', $supp);
                if ($paymentType == 4) {
                    //cash payment
                    $supp1 = array(
                        'ledger_type' => 2,
                        'dist_id' => $this->dist_id,
                        'trans_type' => $invoice_no,
                        'client_vendor_id' => $this->input->post('supplierID'),
                        'amount' => $this->input->post('thisAllotment'),
                        'cr' => $this->input->post('thisAllotment'),
                        'invoice_id' => $purchases_id,
                        'invoice_type' => '1',
                        'Accounts_VoucherType_AutoID' => '5',
                        'date' => $purchasesDate,
                        'updated_by' => $this->admin_id,
                        'history_id' => $accountingVoucherId,
                        'paymentType' => 'Supplier Payment',
                        'BranchAutoId' => $branch_id
                    );
                    $this->db->insert('client_vendor_ledger', $supp1);
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $purchasesDate,
                        'invoiceID' => $purchases_id,
                        'totalPayment' => $this->input->post('thisAllotment'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $accountingVoucherId,
                        'Accounts_VoucherMst_AutoID' => $accountingVoucherId,
                        'customerid' => $this->input->post('supplierID'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 1,
                        'receiveType' => 2,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }
                if ($paymentType == 3) {
                    //check payment
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $purchasesDate,
                        'invoiceID' => $purchases_id,
                        //'invoiceID' => $invoice_no,
                        'totalPayment' => $this->input->post('netTotal'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $purchases_id,
                        'Accounts_VoucherMst_AutoID' => $accountingVoucherId,
                        'customerid' => $this->input->post('supplierID'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 2,
                        'receiveType' => 2,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }
                /*client_vendor_ledger table data insert*/
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/purchases_lpg_add'));
                } else {
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/viewPurchasesCylinder/' . $purchases_id));
                }
            }
        }
        //$data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        /*echo "<pre>";
        print_r($data['accountHeadList']);
        exit;*/
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        $data['title'] = 'Purchases Add';
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $costCondition = array(
            'dist_id' => $this->dist_id,
            'parentId' => 62,
        );
        $data['costList'] = $this->Common_model->get_data_list_by_many_columns('generaldata', $costCondition);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['packageList'] = $this->Common_model->getPublicPackageList($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['cylinderProduct_jason'] = json_encode($this->Common_model->getPublicProduct($this->dist_id, 1));
        //echo "<pre>";
        //print_r($data['cylinderProduct_jason']);exit;
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);
        /* page navbar details */

        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $purchases_id);

        if ($data['purchasesList']->payment_type == 4) {

            $data['purchase_invoice_cash_ledger_id'] = cash_ledger_of_sales_purchase_invoice($purchases_id, 'purchase');



        }

        $stockList = $this->Common_model->get_purchase_product_detaild2($purchases_id);


        foreach ($stockList as $ind => $element) {
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_invoice_id'] = $element->purchase_invoice_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_details_id'] = $element->purchase_details_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['is_package'] = $element->is_package;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_id'] = $element->product_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['category_id'] = $element->category_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brand_id'] = $element->brand_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['show_in_invoice'] = $element->show_in_invoice;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['productName'] = $element->productName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_code'] = $element->product_code;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['title'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brandName'] = $element->brandName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['quantity'] = $element->quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unit_price'] = $element->unit_price;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['returnable_quantity'] = $element->returnable_quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_1'] = $element->property_1;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_2'] = $element->property_2;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_3'] = $element->property_3;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_4'] = $element->property_4;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_5'] = $element->property_5;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'return_product_cat' => $element->return_product_cat,
                    'return_product_name' => $element->return_product_name,
                    'return_product_unit' => $element->return_product_unit,
                    'return_product_brand' => $element->return_product_brand,
                    'returnable_quantity' => $element->returnable_quantity,
                );
            } else {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id] = '';
            }
        }
        
        $data['stockList'] = $result;
        

        $data['title'] = get_phrase('New Purchase Invoice');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Invoice List');
        $data['link_page_url'] = $this->project . '/purchases_cylinder_list';
        $data['link_icon'] = $this->link_icon_list;
        /* page navbar details */
        $condition2 = array(
            //'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_account_info', $condition2);
        

        $this->load->helper('site_helper');
        $edit  = check_parmission_by_user_role(2117);
        if($edit == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
           
        } else{
            $data['mainContent'] = $this->load->view('distributor/inventory/purchases_lpg/purchasesEdit', $data, true);
        }
        /* this invoice no is comming  from purchase_invoice_no_helper */
        
        $this->load->view('distributor/masterTemplate', $data);
    }

    function viewPurchasesCylinder($purchases_id = NULL)
    {
        /*page navbar details*/
        $data['title'] = get_phrase(' Purchases View');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Purchases_Add');
        $data['link_page_url'] = $this->project . '/purchases_lpg_add';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Purchases_List');
        $data['second_link_page_url'] = $this->project . '/purchases_cylinder_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Edit_Invoice');
        $data['third_link_page_url'] = $this->project . '/purchases_lpg_edit/' . $purchases_id;
        $data['third_link_icon'] = $this->link_icon_edit;
        //$data['title'] = 'Purchases View';
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $purchases_id);
        $stockList = $this->Common_model->get_purchase_product_detaild2($purchases_id);
        foreach ($stockList as $ind => $element) {
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_invoice_id'] = $element->purchase_invoice_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_details_id'] = $element->purchase_details_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['is_package'] = $element->is_package;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_id'] = $element->product_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['productName'] = $element->productName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_code'] = $element->product_code;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['title'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brandName'] = $element->brandName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['quantity'] = $element->quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unit_price'] = $element->unit_price;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'return_product_cat' => $element->return_product_cat,
                    'return_product_name' => $element->return_product_name,
                    'return_product_unit' => $element->return_product_unit,
                    'return_product_brand' => $element->return_product_brand,
                    'returnable_quantity' => $element->returnable_quantity,
                );
            } else {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id] = '';
            }
        }

        $data['stockList'] = $result;
        $data['creditAmount'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($purchases_id);
        $data['supplier_due'] = $paymentInfo = $this->Inventory_Model->getCustomerBalance('', $data['purchasesList']->supplier_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases_lpg/purchases_view', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function getPackageEmptyProductId($RefillproductId)
    {
        $this->db->select("package_products.*");
        $this->db->from("package_products");
        $this->db->where('package_products.product_id', $RefillproductId);
        $package = $this->db->get()->row();

        $this->db->select("package_products.*");
        $this->db->from("package_products");
        $this->db->join('product', 'product.product_id = package_products.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->where('package_products.package_id', $package->package_id);
        $this->db->where('productcategory.category_id', 1);
        $package_empty_product = $this->db->get()->row();
        return $package_empty_product->product_id;
    }


    public function _check_the_product_stock_for_edit_delete($purchases_id)
    {



        //product purchase stock
        //total purchase stock -previous stock(before edit)+edit product stock
        $branch_id = $this->input->post('branch_id');

        foreach ($_POST['slNo'] as $key => $value) {
            if (isset($_POST['purchase_details_id_' . $value])) {
                $product_id = $_POST['product_id_' . $value];
                $stock['product_id'] = $product_id;
                $stock['branch_id'] = $branch_id;
                $stock['is_package'] = $_POST['is_package_' . $value];
                $purchase_details_id = $_POST['purchase_details_id_' . $value];
                $quantity=$_POST['quantity_' . $value];


                $this->db->select('sum(quantity) as total_purchase_qty');
                $this->db->from("purchase_details");
                $this->db->where('branch_id', $branch_id);
                $this->db->where('product_id',$product_id);
                $this->db->where('purchase_details_id !=',$purchase_details_id);
                $this->db->group_by('product_id');
                $result_purchase = $this->db->get()->row();
                $total_purchase_qty=$result_purchase->total_purchase_qty;


                $this->db->select('sum(quantity) as total_sales_qty');
                $this->db->from("sales_details");
                $this->db->where('branch_id', $branch_id);
                $this->db->where('product_id',$product_id);
                //$this->db->where('purchase_details_id !=',$purchase_details_id);
                $this->db->group_by('product_id');
                $result_sales = $this->db->get()->row();
                $total_sales_qty=$result_sales->total_sales_qty;

                $product_info = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $product_id);
                $productcategory_info = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $product_info->category_id)->title;
                $productbrand_info = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $product_info->brand_id)->brandName;

                if(($total_purchase_qty + $quantity) < $total_sales_qty){
                    $this->db->trans_rollback();
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message") . ' There is something wrong please try again .contact with Customer Care';
                    $msg2=$productcategory_info. ' '.$product_info->productName.' '.$productbrand_info. " purchase quentity can not edit.Because this quentity already soled out";
                    $this->session->set_flashdata('error', $msg);
                    $this->session->set_flashdata('error_in_stock', $msg2);
                    redirect(site_url($this->project . '/purchases_lpg_edit/'.$purchases_id));

                }
            }
        }
    }
}