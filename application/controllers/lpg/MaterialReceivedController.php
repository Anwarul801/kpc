<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 8/17/2020
 * Time: 10:12 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MaterialReceivedController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $project;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Accounts_model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'Inventory';
        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function material_received_note_add()
    {

        $this->load->helper('create_mrn_sdc_no');
        $this->load->helper('branch_dropdown_helper');
        $this->load->helper('purchase_invoice_no_helper');
        if (isPostBack()) {
            /* echo "<pre>";
             print_r($_POST);
             exit();*/

            $this->form_validation->set_rules('mrn_recived_date', 'So Date', 'required');
            $this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('so_po_product_row_id[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('sdc_mrn_no', 'Voucehr ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/material_received_note_add/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $supplier_id = $this->input->post('supplier_id');
                $mrn_recived_date = $this->input->post('mrn_recived_date') != '' ? date('Y-m-d', strtotime($this->input->post('mrn_recived_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shipping_address = $this->input->post('shipping_address');
                $invoice_no = $this->input->post('po_id');

                $all_sdc_mrn_product = array();


                $invoice_no = create_mrn_bill_no();
                $purchase_inv['invoice_no'] = $invoice_no;
                /* this invoice no is comming  from purchase_invoice_no_helper */

                $purchase_inv['supplier_invoice_no'] = $this->input->post('sdc_mrn_no');
                $purchase_inv['supplier_id'] = $supplier_id;
                $purchase_inv['payment_type'] = 2;
                $purchase_inv['invoice_amount'] = $this->input->post('total_price');
                $purchase_inv['vat_amount'] = 0;
                $purchase_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $purchase_inv['paid_amount'] = 0;
                $purchase_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $purchase_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $purchase_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $purchase_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $purchase_inv['refference_person'] = $this->input->post('reference');
                $purchase_inv['narration'] = $this->input->post('narration');
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = $branch_id;
                $purchase_inv['invoice_date'] = $mrn_recived_date;
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';
                $purchase_inv['for'] = 4;

                $sales_purchase_invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);


                $sdc_mrn_info = array();
                $sdc_mrn_info['form_id'] = 32;
                $sdc_mrn_info['sdc_mrn_no'] = $sdc_mrn_no = create_mrn_sdc_no('MRN');
                $sdc_mrn_info['customer_id'] = 0;
                $sdc_mrn_info['supplier_id'] = $supplier_id;
                $sdc_mrn_info['shipping_address_mrn_sdc'] = $shipping_address;
                $sdc_mrn_info['mrn_recived_date'] = $mrn_recived_date;
                $sdc_mrn_info['narration'] = $narration;
                $sdc_mrn_info['status'] = 0;//1->complete
                $sdc_mrn_info['company_id'] = $this->dist_id;
                $sdc_mrn_info['branch_id'] = $branch_id;
                $sdc_mrn_info['insert_date'] = $this->timestamp;
                $sdc_mrn_info['insert_by'] = $this->admin_id;
                $sdc_mrn_info['update_by'] = '';
                $sdc_mrn_info['update_date'] = '';
                $sdc_mrn_info['delete_by'] = '';
                $sdc_mrn_info['delete_date'] = '';
                $sdc_mrn_info['is_active'] = 'Y';
                $sdc_mrn_info['is_delete'] = 'N';
                $sdc_mrn_info['purchase_sales_invoice_id'] = $sales_purchase_invoice_id;
                $sdc_mrn_info_id = $this->Common_model->insert_data('sdc_mrn_info', $sdc_mrn_info);

                $voucher_no = create_journal_voucher_no();
                $AccouVoucherType_AutoID = 3;

                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $mrn_recived_date;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $sdc_mrn_no . "/" . $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $sales_purchase_invoice_id;
                $accountingMasterTable['Narration'] = 'Material Received Note';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['supplier_id'] = $this->input->post('supplierID');
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingMasterTable['for'] = 9;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);


                $so_po_ides = array();

                foreach ($_POST['so_po_product_row_id'] as $key => $value) {
                    $sdc_mrn_product = array();
                    $product_id = $_POST['so_po_product_id_' . $value];
                    $quantity = $_POST['so_po_issue_qty_' . $value];
                    $unit_price = $_POST['unit_price_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];
                    $so_po_id = $_POST['so_po_id_' . $value];
                    $product_category_id = $_POST['so_po_product_category_id_' . $value];
                    $so_po_ides[] = $so_po_id;


                    $lastPurchasepriceArray = $this->db->where('product_id', $product_id)
                        ->where('branch_id', $branch_id)
                        ->order_by('purchase_details_id', "desc")
                        ->limit(1)
                        ->get('purchase_details')
                        ->row();
                    $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;

                    unset($stock);
                    $stock['purchase_invoice_id'] = $sales_purchase_invoice_id;
                    $stock['product_id'] = $product_id;
                    $stock['package_id'] = 0;
                    $stock['is_package '] = 0;
                    $stock['returnable_quantity '] = 0;
                    $stock['return_quentity '] = 0;
                    $stock['supplier_due'] = 0;
                    $stock['supplier_advance'] = 0;
                    $stock['quantity'] = $quantity;
                    $stock['unit_price'] = $unit_price;
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $stock['branch_id'] = $branch_id;
                    $stock['supplier_id '] = $supplier_id;
                    $stock['property_1'] = $_POST['property_1_' . $value];
                    $stock['property_2'] = $_POST['property_2_' . $value];
                    $stock['property_3'] = $_POST['property_3_' . $value];
                    $stock['property_4'] = $_POST['property_4_' . $value];
                    $stock['property_5'] = $_POST['property_5_' . $value];
                    $purchase_details_id = $this->Common_model->insert_data('purchase_details', $stock);


                    $sdc_mrn_product['sdc_mrn_info_id'] = $sdc_mrn_info_id;
                    $sdc_mrn_product['so_po_id'] = $so_po_id;
                    $sdc_mrn_product['so_po_productes_row_id'] = $value;
                    $sdc_mrn_product['customer_id'] = 0;
                    $sdc_mrn_product['supplier_id'] = $supplier_id;
                    $sdc_mrn_product['form_id'] = 32;
                    $sdc_mrn_product['sdc_mrn_delivery_date'] = $mrn_recived_date;
                    $sdc_mrn_product['one_line_narration'] = $_POST['one_line_narration_' . $value];
                    $sdc_mrn_product['product_id'] = $product_id;
                    $sdc_mrn_product['mrn_sdc_qty'] = $quantity;
                    $sdc_mrn_product['mrn_sdc_unit_price'] = $unit_price;
                    $sdc_mrn_product['status'] = 0;//1->complete
                    $sdc_mrn_product['branch_id'] = $branch_id;
                    $sdc_mrn_product['property_1'] = $property_1;
                    $sdc_mrn_product['property_2'] = $property_2;
                    $sdc_mrn_product['property_3'] = $property_3;
                    $sdc_mrn_product['property_4'] = $property_4;
                    $sdc_mrn_product['property_5'] = $property_5;
                    $sdc_mrn_product['insert_date'] = $this->timestamp;
                    $sdc_mrn_product['insert_by'] = $this->admin_id;
                    $sdc_mrn_product['update_by'] = '';
                    $sdc_mrn_product['update_date'] = '';
                    $sdc_mrn_product['delete_by'] = '';
                    $sdc_mrn_product['delete_date'] = '';
                    $sdc_mrn_product['is_active'] = 'Y';
                    $sdc_mrn_product['is_delete'] = 'N';


                    $all_sdc_mrn_product[] = $sdc_mrn_product;

                    //update so_po_product qty


                    $query = 'UPDATE so_po_productes
                        SET so_po_approve_qty=so_po_approve_qty +' . $quantity . '
                        WHERE id =' . $value;
                    $this->db->query($query);

                    $this->db->select('so_po_approve_qty,so_po_qty');
                    $this->db->from('so_po_productes');
                    $this->db->where('id', $value);
                    $so_po_qty_details = $this->db->get()->row_array();


                    if ($so_po_qty_details['so_po_approve_qty'] == $so_po_qty_details['so_po_qty']) {
                        $this->db->set('status', '4');
                        $this->db->where('id', $value);
                        $this->db->update('so_po_productes');
                    } else {
                        $this->db->set('status', '3');
                        $this->db->where('id', $value);
                        $this->db->update('so_po_productes');
                    }
                    $stockNewTable = array();
                    $stockNewTable['parent_stock_id'] = 0;
                    $stockNewTable['invoice_id'] = $sdc_mrn_info_id;
                    $stockNewTable['form_id'] = 32;
                    $stockNewTable['Accounts_VoucherMst_AutoID'] = 0;
                    $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                    $stockNewTable['customer_id'] = 0;
                    $stockNewTable['supplier_id'] = $supplier_id;
                    $stockNewTable['branch_id'] = $branch_id;
                    $stockNewTable['invoice_date'] = $mrn_recived_date;
                    $stockNewTable['category_id'] = $product_category_id;
                    $stockNewTable['product_id'] = $product_id;
                    $stockNewTable['empty_cylinder_id'] = 0;
                    $stockNewTable['is_package'] = 0;
                    $stockNewTable['show_in_invoice'] = 1;
                    $stockNewTable['unit'] = getProductUnit($product_id);
                    $stockNewTable['type'] = 1;
                    $stockNewTable['quantity'] = $quantity;
                    $stockNewTable['quantity_out'] = 0;
                    $stockNewTable['quantity_in'] = $quantity;
                    $stockNewTable['returnable_quantity'] = 0;
                    $stockNewTable['return_quentity'] = 0;
                    $stockNewTable['due_quentity'] = 0;
                    $stockNewTable['advance_quantity'] = 0;
                    $stockNewTable['price'] = $unit_price;
                    $stockNewTable['price_in'] = 0;
                    $stockNewTable['price_out'] = $unit_price;
                    $stockNewTable['last_purchase_price'] = $unit_price;
                    $stockNewTable['product_details'] = "";
                    $stockNewTable['property_1'] = $property_1;
                    $stockNewTable['property_2'] = $property_2;
                    $stockNewTable['property_3'] = $property_3;
                    $stockNewTable['property_4'] = $property_4;
                    $stockNewTable['property_5'] = $property_5;
                    $stockNewTable['openingStatus'] = 0;
                    $stockNewTable['insert_by'] = $this->admin_id;
                    $stockNewTable['insert_date'] = $this->timestamp;
                    $stockNewTable['update_by'] = '';
                    $stockNewTable['update_date'] = '';
                    $stock_id = $this->Common_model->insert_data('stock', $stockNewTable);


                    $condition = array(
                        'related_id' => $product_id,
                        'related_id_for' => 1,
                        'is_active' => "Y",
                    );
                    $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                    $accountingDetailsTable['GR_DEBIT'] = $unit_price * $quantity;
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'General Product ';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $mrn_recived_date;
                    //$finalDetailsArray[] = $accountingDetailsTable;

                    $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);


                }
                $accountingDetailsTable = array();
                $condtion = array(
                    'parent_name' => "Item Received But Bill Pending",
                    //'related_id_for' => 2,
                    'is_active' => "Y",
                );
                $Item_received_but_bill_pending = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                if (empty($Item_received_but_bill_pending)) {
                    $this->db->trans_rollback();
                    $msg = 'Please Create A ledger Under Supplier Payable Named Item Received But Bill Pending';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/material_received_note'));
                }

                /*Supplier Payable =======>34*/
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable['TypeID'] = '2';//Cr
                $accountingDetailsTable['CHILD_ID'] = $Item_received_but_bill_pending->id;//Supplier_Payables'34';
                $accountingDetailsTable['GR_DEBIT'] = '0.00';
                $accountingDetailsTable['GR_CREDIT'] = $this->input->post('total_price');
                $accountingDetailsTable['Reference'] = 'Item Received But Bill Pending';
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Created_By'] = $this->admin_id;
                $accountingDetailsTable['Created_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = $branch_id;
                $accountingDetailsTable['date'] = $mrn_recived_date;
                $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);


                $so_po_ides = array_unique($so_po_ides);
                if (!empty($so_po_ides)) {
                    foreach ($so_po_ides as $key => $value) {
                        $query = "SELECT
                                SUM(spp.so_po_qty)so_po_qty,
                                SUM(spp.so_po_approve_qty)so_po_approve_qty
                            FROM
                                so_po_productes spp
                            WHERE
                                spp.so_po_id =" . $value;
                        $query = $this->db->query($query);
                        $so_po_qty = $query->row();

                        if ($so_po_qty->so_po_qty == $so_po_qty->so_po_approve_qty) {
                            $this->db->set('status', '4');
                            $this->db->where('id', $value);
                            $this->db->update('so_po_info ');
                        } else {
                            $this->db->set('status', '3');
                            $this->db->where('id', $value);
                            $this->db->update('so_po_info ');
                        }
                    }

                }

                if (!empty($all_sdc_mrn_product)) {
                    $this->Common_model->insert_batch_save(' sdc_mrn_products', $all_sdc_mrn_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));
                } else {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));

                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('material received note');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('material received note_list');
        $data['second_link_page_url'] = $this->project . '/material_received_note_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2116);
        $menu  = check_parmission_by_user_role(131);

        if($add == 0 || $menu == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        } else{
            $data['mainContent'] = $this->load->view('distributor/purchase_order/material_received_note_add', $data, true);
            $this->load->view("distributor/masterTemplateSmeMobile", $data);
        }

        
    }

    function get_so_po_product_for_mrn_sdc()
    {
        $json = array();


//add custom filter here
        $so_ides = json_decode($this->input->post('ides'));

        if (!empty($so_ides)) {
            $this->db->where_in('so.id', $so_ides);
        }
        if (!empty($this->input->post('form_id'))) {
            $this->db->where('so.form_id', $this->input->post('form_id'));
        }
        if (!empty($this->input->post('customerid'))) {
            $this->db->where('so.customer_id', $this->input->post('customerid'));
        }
        if (!empty($this->input->post('supplier_id'))) {
            $this->db->where('so.supplier_id', $this->input->post('supplier_id'));
        }
        if (!empty($this->input->post('BranchAutoId'))) {
            $this->db->where('so.branch_id', $this->input->post('BranchAutoId'));
        }

        $this->db->select("so.id,
    so.so_po_no,
    so.so_po_date,
    sop.id as so_po_product_row_id,
    sop.product_id,
    p.productName,
    p.product_code,
    pc.category_id  AS category_id ,
    pc.title AS productcategory,
    b.brandName,
    sop.so_po_qty,
    sop.so_po_qty,
    sop.so_po_unit_price,
    sop.so_po_approve_qty,
    unit.unitTtile,
    unit.unit_custom_field_1,
    unit.unit_custom_field_2,
    unit.unit_id,
    
    sop.one_line_narration,
    sop.property_1,
    sop.property_2,
    sop.property_3,
    sop.property_4,
    sop.property_5");

        $this->db->from('so_po_productes sop');
        $this->db->join('so_po_info so ', 'so.id = sop.so_po_id', 'left');
        $this->db->join('product p ', 'p.product_id = sop.product_id ', 'left');
        $this->db->join('unit', 'unit.unit_id = p.unit_id', 'left');
        $this->db->join('productcategory pc   ', 'pc.category_id = p.category_id', 'left');
        $this->db->join('brand b  ', 'b.brandId = p.brand_id', 'left');
        $this->db->order_by("so.id");

        $i = 0;


        //$list = $this->ReturnDamageModel->get_sales_invoice_details_for_return();


        $query = $this->db->get();
        $list = $query->result_array();
        log_message('error', "this is so po query " . print_r($this->db->last_query(), true));

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);


        $data = array();
        foreach ($list as $element) {
            /*$condition = array(
                'form_id' => 5,
                'type' => 1,
                'parent_stock_id' => $element['so_po_product_row_id'],
                'product_id' => $element['product_id'],
            );
            $sales_return_qty = $this->ReturnDamageModel->get_product_total_return_qty($condition);*/
            $sales_return_qty = 0;
            $quantity = $element['so_po_qty'] - $element['so_po_approve_qty'];
            
            if($element['unit_custom_field_1'] == 0){
                $carton =0;
            }else{
                $carton = $quantity / $element['unit_custom_field_1'];
            }
            

            if ($element['so_po_qty'] - $element['so_po_approve_qty'] == 0) {
                $check_box = "";
            } else {
                $check_box = "<input class='form-check-input checkbox_so_po_product' name='so_po_product_row_id[]' type='checkbox' value='" . $element['so_po_product_row_id'] . "' id='defaultCheck_" . $element['so_po_product_row_id'] . "'>";

            }

            $row = array();
            $row[] = $element['so_po_product_row_id'];

            $row[] = $element['so_po_no'];
            $row[] = $element['so_po_date'];
            $row[] = $element['productcategory'];
            $row[] = $element['productName'] . "<input type='hidden'  id='so_po_id_" . $element['id'] . "' name='so_po_id' value='" . $element['id'] . "'/>";;
            //  if ($property_1 != 'dont_have_this_property') {

            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_1_" . $element['so_po_product_row_id'] . "' name='property_1_" . $element['so_po_product_row_id'] . "' value='" . $element['property_1'] . "' placeholder='" . $element['property_1'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            //  }
            //if ($property_2 != 'dont_have_this_property') {

            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_2_" . $element['so_po_product_row_id'] . "' name='property_2_" . $element['so_po_product_row_id'] . "' value='" . $element['property_2'] . "' placeholder='" . $element['property_2'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            // }
            // if ($property_3 != 'dont_have_this_property') {

            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_3_" . $element['so_po_product_row_id'] . "' name='property_3_" . $element['so_po_product_row_id'] . "' value='" . $element['property_3'] . "' placeholder='" . $element['property_3'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            //}
            //if ($property_4 != 'dont_have_this_property') {

            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_4_" . $element['so_po_product_row_id'] . "' name='property_4_" . $element['so_po_product_row_id'] . "' value='" . $element['property_4'] . "' placeholder='" . $element['property_4'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            // }
            // if ($property_5 != 'dont_have_this_property') {

            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_5_" . $element['so_po_product_row_id'] . "' name='property_5_" . $element['so_po_product_row_id'] . "' value='" . $element['property_5'] . "' placeholder='" . $element['property_5'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            //}

            $row[] = "<input type='hidden' name='so_po_id_" . $element['so_po_product_row_id'] . "' value='" . $element['id'] . "'  /><input type='hidden' class='' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_po_product_id_" . $element['so_po_product_row_id'] . "' name='so_po_product_id_" . $element['so_po_product_row_id'] . "' value='" . $element['product_id'] . "'  />
                      <input type='hidden' class='' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_po_product_category_id_" . $element['so_po_product_row_id'] . "' name='so_po_product_category_id_" . $element['so_po_product_row_id'] . "' value='" . $element['category_id'] . "'  />
                    <input type='text' class='form-control so_po_qty decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_quantity_" . $element['so_po_product_row_id'] . "' name='so_po_quantity_" . $element['so_po_product_row_id'] . "' value='" . $quantity . "' placeholder='" . $element['quantity'] . "' attr-actual-quantity='" . $element['quantity'] . "' readonly='true'  onclick='this.select();' style='text-align: center;'/>";
            $row[] = "<input type='text' class='form-control carton decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='carton_" . $element['so_po_product_row_id'] . "' name='carton_" . $element['so_po_product_row_id'] . "' value='" . $carton . "' placeholder='" . $element['carton'] . "' attr-actual-carton='" . $element['carton'] . "' readonly='true'  onclick='this.select();' style='text-align: right;'/>";
            $row[] = "<input type='text' class='form-control so_po_issue_qty decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_po_issue_qty_" . $element['so_po_product_row_id'] . "' name='so_po_issue_qty_" . $element['so_po_product_row_id'] . "' arrt-box-qty='" . $element['unit_custom_field_1'] . "' value='" . $quantity . "' placeholder='" . $element['quantity'] . "' attr-actual-quantity='" . $element['quantity'] . "' readonly='true'  onclick='this.select();' style='text-align: right;'/>";

            $row[] = "<input type='text' class='form-control unit_price decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "' id='unit_price_" . $element['so_po_product_row_id'] . "' name='unit_price_" . $element['so_po_product_row_id'] . "' value='" . $element['so_po_unit_price'] . "' placeholder='" . $element['so_po_unit_price'] . "' arrt-actual-unit-price='" . $element['so_po_unit_price'] . "' readonly='true' onclick='this.select();' style='text-align: right;'/>";;
            $row[] = "<input type='text' class='form-control tt_price decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "' id='tt_price_" . $element['so_po_product_row_id'] . "' name='tt_price[" . $element['so_po_product_row_id'] . "]' value='" . $quantity * $element['so_po_unit_price'] . "' placeholder='" . $element['quantity'] * $element['so_po_unit_price'] . "' readonly='true' onclick='this.select();' style='text-align: right;'/>";
            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "' id='one_line_narration_" . $element['so_po_product_row_id'] . "' name='one_line_narration_" . $element['so_po_product_row_id'] . "' value='" . "" /*$element['one_line_narration']*/ . "' placeholder='" . $element['one_line_narration'] . "' readonly='true' onclick='this.select();' style='text-align: right;'/>";
            $row[] = $check_box;
            $data[] = $row;
        };

        $json['data'] = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => $data,
        );
        //output to json format
        $this->output->set_header('Content-Type: application/json');
        echo json_encode($json['data']);
    }

    public function mrn_list()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('material_received_note_list');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('add_material_received_note');
        $data['link_page_url'] = $this->project . '/material_received_note';
        $data['link_icon'] = "<i class='ace-icon fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/purchase_order/material_received_note_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function mrn_view($id)
    {
        $data['title'] = get_phrase('MRN View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('add_material_received_note');
        $data['link_page_url'] = $this->project . '/material_received_note';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('material_received_note_list');
        $data['second_link_page_url'] = $this->project . '/material_received_note_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('material_received_note_Edit');
        $data['third_link_page_url'] = $this->project . '/material_received_note_edit/' . $id;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';

        $data['sdc_mrn_info'] = $this->Common_model->get_single_data_by_single_column('sdc_mrn_info', 'id', $id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['so_po_info']->customer_id);
        $data['supplier'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['sdc_mrn_info']->supplier_id);


        $this->db->select('
         so_po_info.so_po_no,
        sdc_mrn_products.mrn_sdc_qty,
        sdc_mrn_products.mrn_sdc_unit_price,
        sdc_mrn_products.status,
        product.product_id,productcategory.title as productCat,
        product.brand_id,product.category_id,product.productName,
        product.dist_id,product.status,
        brand.brandName,
        unit.unitTtile,
        unit.unit_id,
        tb_subcategory.SubCatName,
        tb_model.Model,
        tb_color.Color,
        sdc_mrn_products.property_1,
        sdc_mrn_products.property_2,
        sdc_mrn_products.property_3,
        sdc_mrn_products.property_4,
        sdc_mrn_products.property_5,
        sdc_mrn_products.one_line_narration,
        product.salesPrice,
        tb_size.Size');
        $this->db->from('sdc_mrn_products');
        $this->db->join('so_po_info', 'so_po_info.id = sdc_mrn_products.so_po_id', 'left');
        $this->db->join('product', 'sdc_mrn_products.product_id = product.product_id', 'left');
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->join('tb_subcategory', 'tb_subcategory.SubCatID = product.subcategoryID', 'left');
        $this->db->join('tb_model', 'tb_model.ModelID = product.modelID', 'left');
        $this->db->join('tb_color', 'tb_color.ColorID = product.colorID', 'left');
        $this->db->join('tb_size', 'tb_size.SizeID = product.SizeID', 'left');
        $this->db->where('sdc_mrn_products.sdc_mrn_info_id', $id);
        $data['sdc_mrn_products'] = $this->db->get()->result_array();









        /*echo "<pre>";
        print_r($data['sdc_mrn_products']);exit;*/


        $data['mainContent'] = $this->load->view('distributor/purchase_order/material_received_note_view', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function mrn_edit($id)
    {
        $this->load->helper('create_mrn_sdc_no');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {
            /* echo "<pre>";
             print_r($_POST);
             exit();*/

            $this->form_validation->set_rules('mrn_recived_date', 'So Date', 'required');
            $this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('so_po_product_row_id[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('sdc_mrn_no', 'Voucehr ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/material_received_note_add/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $supplier_id = $this->input->post('supplier_id');
                $mrn_recived_date = $this->input->post('mrn_recived_date') != '' ? date('Y-m-d', strtotime($this->input->post('mrn_recived_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shipping_address = $this->input->post('shipping_address');
                $invoice_no = $this->input->post('po_id');

                $all_sdc_mrn_product = array();

                $sdc_mrn_info = array();

                $sdc_mrn_info['form_id'] = 32;
                $sdc_mrn_info['sdc_mrn_no'] = $this->input->post('sdc_mrn_no');
                $sdc_mrn_info['customer_id'] = 0;
                $sdc_mrn_info['supplier_id'] = $supplier_id;
                $sdc_mrn_info['shipping_address_mrn_sdc'] = $shipping_address;

                $sdc_mrn_info['mrn_recived_date'] = $mrn_recived_date;
                $sdc_mrn_info['narration'] = $narration;
                $sdc_mrn_info['status'] = 0;//1->complete
                $sdc_mrn_info['company_id'] = $this->dist_id;
                $sdc_mrn_info['branch_id'] = $branch_id;
                $sdc_mrn_info['insert_date'] = $this->timestamp;
                $sdc_mrn_info['insert_by'] = $this->admin_id;
                $sdc_mrn_info['update_by'] = '';
                $sdc_mrn_info['update_date'] = '';
                $sdc_mrn_info['delete_by'] = '';
                $sdc_mrn_info['delete_date'] = '';
                $sdc_mrn_info['is_active'] = 'Y';
                $sdc_mrn_info['is_delete'] = 'N';
                $sdc_mrn_info_id = $this->Common_model->insert_data('sdc_mrn_info', $sdc_mrn_info);

                $so_po_ides = array();

                foreach ($_POST['so_po_product_row_id'] as $key => $value) {
                    $sdc_mrn_product = array();
                    $product_id = $_POST['so_po_product_id_' . $value];
                    $quantity = $_POST['so_po_issue_qty_' . $value];
                    $unit_price = $_POST['unit_price_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];
                    $so_po_id = $_POST['so_po_id_' . $value];
                    $product_category_id = $_POST['so_po_product_category_id_' . $value];
                    $so_po_ides[] = $so_po_id;

                    $sdc_mrn_product['sdc_mrn_info_id'] = $sdc_mrn_info_id;
                    $sdc_mrn_product['so_po_id'] = $so_po_id;
                    $sdc_mrn_product['so_po_productes_row_id'] = $value;
                    $sdc_mrn_product['customer_id'] = 0;
                    $sdc_mrn_product['supplier_id'] = $supplier_id;
                    $sdc_mrn_product['form_id'] = 32;
                    $sdc_mrn_product['sdc_mrn_delivery_date'] = $mrn_recived_date;
                    $sdc_mrn_product['one_line_narration'] = $_POST['one_line_narration_' . $value];
                    $sdc_mrn_product['product_id'] = $product_id;
                    $sdc_mrn_product['mrn_sdc_qty'] = $quantity;
                    $sdc_mrn_product['mrn_sdc_unit_price'] = $unit_price;
                    $sdc_mrn_product['status'] = 0;//1->complete
                    $sdc_mrn_product['branch_id'] = $branch_id;
                    $sdc_mrn_product['property_1'] = $property_1;
                    $sdc_mrn_product['property_2'] = $property_2;
                    $sdc_mrn_product['property_3'] = $property_3;
                    $sdc_mrn_product['property_4'] = $property_4;
                    $sdc_mrn_product['property_5'] = $property_5;
                    $sdc_mrn_product['insert_date'] = $this->timestamp;
                    $sdc_mrn_product['insert_by'] = $this->admin_id;
                    $sdc_mrn_product['update_by'] = '';
                    $sdc_mrn_product['update_date'] = '';
                    $sdc_mrn_product['delete_by'] = '';
                    $sdc_mrn_product['delete_date'] = '';
                    $sdc_mrn_product['is_active'] = 'Y';
                    $sdc_mrn_product['is_delete'] = 'N';


                    $all_sdc_mrn_product[] = $sdc_mrn_product;

                    //update so_po_product qty


                    $query = 'UPDATE so_po_productes
                        SET so_po_approve_qty=so_po_approve_qty +' . $quantity . '
                        WHERE id =' . $value;
                    $this->db->query($query);

                    $this->db->select('so_po_approve_qty,so_po_qty');
                    $this->db->from('so_po_productes');
                    $this->db->where('id', $value);
                    $so_po_qty_details = $this->db->get()->row_array();


                    if ($so_po_qty_details['so_po_approve_qty'] == $so_po_qty_details['so_po_qty']) {
                        $this->db->set('status', '4');
                        $this->db->where('id', $value);
                        $this->db->update('so_po_productes');
                    } else {
                        $this->db->set('status', '3');
                        $this->db->where('id', $value);
                        $this->db->update('so_po_productes');
                    }
                    $stockNewTable = array();

                    $stockNewTable['parent_stock_id'] = 0;
                    $stockNewTable['invoice_id'] = $sdc_mrn_info_id;
                    $stockNewTable['form_id'] = 32;
                    $stockNewTable['Accounts_VoucherMst_AutoID'] = 0;
                    $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                    $stockNewTable['customer_id'] = 0;
                    $stockNewTable['supplier_id'] = $supplier_id;
                    $stockNewTable['branch_id'] = $branch_id;
                    $stockNewTable['invoice_date'] = $mrn_recived_date;
                    $stockNewTable['category_id'] = $product_category_id;
                    $stockNewTable['product_id'] = $product_id;
                    $stockNewTable['empty_cylinder_id'] = 0;
                    $stockNewTable['is_package'] = 0;
                    $stockNewTable['show_in_invoice'] = 1;
                    $stockNewTable['unit'] = getProductUnit($product_id);
                    $stockNewTable['type'] = 1;
                    $stockNewTable['quantity'] = $quantity;
                    $stockNewTable['quantity_out'] = 0;
                    $stockNewTable['quantity_in'] = $quantity;
                    $stockNewTable['returnable_quantity'] = 0;
                    $stockNewTable['return_quentity'] = 0;
                    $stockNewTable['due_quentity'] = 0;
                    $stockNewTable['advance_quantity'] = 0;
                    $stockNewTable['price'] = $unit_price;
                    $stockNewTable['price_in'] = 0;
                    $stockNewTable['price_out'] = $unit_price;
                    $stockNewTable['last_purchase_price'] = $unit_price;
                    $stockNewTable['product_details'] = "";
                    $stockNewTable['property_1'] = $property_1;
                    $stockNewTable['property_2'] = $property_2;
                    $stockNewTable['property_3'] = $property_3;
                    $stockNewTable['property_4'] = $property_4;
                    $stockNewTable['property_5'] = $property_5;
                    $stockNewTable['openingStatus'] = 0;
                    $stockNewTable['insert_by'] = $this->admin_id;
                    $stockNewTable['insert_date'] = $this->timestamp;
                    $stockNewTable['update_by'] = '';
                    $stockNewTable['update_date'] = '';
                    $stock_id = $this->Common_model->insert_data('stock', $stockNewTable);

                }

                $so_po_ides = array_unique($so_po_ides);
                if (!empty($so_po_ides)) {
                    foreach ($so_po_ides as $key => $value) {

                        $query = "SELECT
                                SUM(spp.so_po_qty)so_po_qty,
                                SUM(spp.so_po_approve_qty)so_po_approve_qty
                            FROM
                                so_po_productes spp
                            WHERE
                                spp.so_po_id =" . $value;

                        $query = $this->db->query($query);
                        $so_po_qty = $query->row();


                        if ($so_po_qty->so_po_qty == $so_po_qty->so_po_approve_qty) {
                            $this->db->set('status', '4');
                            $this->db->where('id', $value);
                            $this->db->update('so_po_info ');
                        } else {
                            $this->db->set('status', '3');
                            $this->db->where('id', $value);
                            $this->db->update('so_po_info ');
                        }

                    }

                }

                if (!empty($all_sdc_mrn_product)) {
                    $this->Common_model->insert_batch_save(' sdc_mrn_products', $all_sdc_mrn_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));
                } else {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $sales_purchase_invoice_id));
                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('material received note');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('material received note_list');
        $data['second_link_page_url'] = $this->project . '/material_received_note_list';
        $data['second_link_icon'] = $this->link_icon_list;

        $data['sdc_mrn_info'] = $this->Common_model->get_single_data_by_single_column('sdc_mrn_info', 'id', $id);
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/purchase_order/material_received_note_edit', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);


    }

    function purchase_invoice_against_mrn()
    {
        $this->load->helper('create_mrn_sdc_no');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {
            /*  echo "<pre>";
              print_r($_POST);
              exit();*/

            $this->form_validation->set_rules('mrn_recived_date', 'So Date', 'required');
            $this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('so_po_product_row_id[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('sdc_mrn_no', 'Voucehr ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/material_received_note_add/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $supplier_id = $this->input->post('supplier_id');
                $mrn_recived_date = $this->input->post('mrn_recived_date') != '' ? date('Y-m-d', strtotime($this->input->post('mrn_recived_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shipping_address = $this->input->post('shipping_address');
                $invoice_no = $this->input->post('po_id');

                $all_sdc_mrn_product = array();

                $sdc_mrn_info = array();

                $sdc_mrn_info['form_id'] = 32;
                $sdc_mrn_info['sdc_mrn_no'] = $this->input->post('sdc_mrn_no');
                $sdc_mrn_info['customer_id'] = 0;
                $sdc_mrn_info['supplier_id'] = $supplier_id;
                $sdc_mrn_info['shipping_address_mrn_sdc'] = $shipping_address;

                $sdc_mrn_info['mrn_recived_date'] = $mrn_recived_date;
                $sdc_mrn_info['narration'] = $narration;
                $sdc_mrn_info['status'] = 0;//1->complete
                $sdc_mrn_info['company_id'] = $this->dist_id;
                $sdc_mrn_info['branch_id'] = $branch_id;
                $sdc_mrn_info['insert_date'] = $this->timestamp;
                $sdc_mrn_info['insert_by'] = $this->admin_id;
                $sdc_mrn_info['update_by'] = '';
                $sdc_mrn_info['update_date'] = '';
                $sdc_mrn_info['delete_by'] = '';
                $sdc_mrn_info['delete_date'] = '';
                $sdc_mrn_info['is_active'] = 'Y';
                $sdc_mrn_info['is_delete'] = 'N';
                $sdc_mrn_info_id = $this->Common_model->insert_data('sdc_mrn_info', $sdc_mrn_info);

                $so_po_ides = array();

                foreach ($_POST['so_po_product_row_id'] as $key => $value) {
                    $sdc_mrn_product = array();
                    $product_id = $_POST['so_po_product_id_' . $value];
                    $quantity = $_POST['so_po_issue_qty_' . $value];
                    $unit_price = $_POST['unit_price_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];
                    $so_po_id = $_POST['so_po_id_' . $value];
                    $product_category_id = $_POST['so_po_product_category_id_' . $value];
                    $so_po_ides[] = $so_po_id;

                    $sdc_mrn_product['sdc_mrn_info_id'] = $sdc_mrn_info_id;
                    $sdc_mrn_product['so_po_id'] = $so_po_id;
                    $sdc_mrn_product['so_po_productes_row_id'] = $value;
                    $sdc_mrn_product['customer_id'] = 0;
                    $sdc_mrn_product['supplier_id'] = $supplier_id;
                    $sdc_mrn_product['form_id'] = 32;
                    $sdc_mrn_product['sdc_mrn_delivery_date'] = $mrn_recived_date;
                    $sdc_mrn_product['one_line_narration'] = $_POST['one_line_narration_' . $value];
                    $sdc_mrn_product['product_id'] = $product_id;
                    $sdc_mrn_product['mrn_sdc_qty'] = $quantity;
                    $sdc_mrn_product['mrn_sdc_unit_price'] = $unit_price;
                    $sdc_mrn_product['status'] = 0;//1->complete
                    $sdc_mrn_product['branch_id'] = $branch_id;
                    $sdc_mrn_product['property_1'] = $property_1;
                    $sdc_mrn_product['property_2'] = $property_2;
                    $sdc_mrn_product['property_3'] = $property_3;
                    $sdc_mrn_product['property_4'] = $property_4;
                    $sdc_mrn_product['property_5'] = $property_5;
                    $sdc_mrn_product['insert_date'] = $this->timestamp;
                    $sdc_mrn_product['insert_by'] = $this->admin_id;
                    $sdc_mrn_product['update_by'] = '';
                    $sdc_mrn_product['update_date'] = '';
                    $sdc_mrn_product['delete_by'] = '';
                    $sdc_mrn_product['delete_date'] = '';
                    $sdc_mrn_product['is_active'] = 'Y';
                    $sdc_mrn_product['is_delete'] = 'N';


                    $all_sdc_mrn_product[] = $sdc_mrn_product;

                    //update so_po_product qty


                    $query = 'UPDATE so_po_productes
                        SET so_po_approve_qty=so_po_approve_qty +' . $quantity . '
                        WHERE id =' . $value;
                    $this->db->query($query);

                    $this->db->select('so_po_approve_qty,so_po_qty');
                    $this->db->from('so_po_productes');
                    $this->db->where('id', $value);
                    $so_po_qty_details = $this->db->get()->row_array();


                    if ($so_po_qty_details['so_po_approve_qty'] == $so_po_qty_details['so_po_qty']) {
                        $this->db->set('status', '4');
                        $this->db->where('id', $value);
                        $this->db->update('so_po_productes');
                    } else {
                        $this->db->set('status', '3');
                        $this->db->where('id', $value);
                        $this->db->update('so_po_productes');
                    }
                    $stockNewTable = array();

                    $stockNewTable['parent_stock_id'] = 0;
                    $stockNewTable['invoice_id'] = $sdc_mrn_info_id;
                    $stockNewTable['form_id'] = 32;
                    $stockNewTable['Accounts_VoucherMst_AutoID'] = 0;
                    $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                    $stockNewTable['customer_id'] = 0;
                    $stockNewTable['supplier_id'] = $supplier_id;
                    $stockNewTable['branch_id'] = $branch_id;
                    $stockNewTable['invoice_date'] = $mrn_recived_date;
                    $stockNewTable['category_id'] = $product_category_id;
                    $stockNewTable['product_id'] = $product_id;
                    $stockNewTable['empty_cylinder_id'] = 0;
                    $stockNewTable['is_package'] = 0;
                    $stockNewTable['show_in_invoice'] = 1;
                    $stockNewTable['unit'] = getProductUnit($product_id);
                    $stockNewTable['type'] = 1;
                    $stockNewTable['quantity'] = $quantity;
                    $stockNewTable['quantity_out'] = 0;
                    $stockNewTable['quantity_in'] = $quantity;
                    $stockNewTable['returnable_quantity'] = 0;
                    $stockNewTable['return_quentity'] = 0;
                    $stockNewTable['due_quentity'] = 0;
                    $stockNewTable['advance_quantity'] = 0;
                    $stockNewTable['price'] = $unit_price;
                    $stockNewTable['price_in'] = 0;
                    $stockNewTable['price_out'] = $unit_price;
                    $stockNewTable['last_purchase_price'] = $unit_price;
                    $stockNewTable['product_details'] = "";
                    $stockNewTable['property_1'] = $property_1;
                    $stockNewTable['property_2'] = $property_2;
                    $stockNewTable['property_3'] = $property_3;
                    $stockNewTable['property_4'] = $property_4;
                    $stockNewTable['property_5'] = $property_5;
                    $stockNewTable['openingStatus'] = 0;
                    $stockNewTable['insert_by'] = $this->admin_id;
                    $stockNewTable['insert_date'] = $this->timestamp;
                    $stockNewTable['update_by'] = '';
                    $stockNewTable['update_date'] = '';
                    $stock_id = $this->Common_model->insert_data('stock', $stockNewTable);

                }

                $so_po_ides = array_unique($so_po_ides);
                if (!empty($so_po_ides)) {
                    foreach ($so_po_ides as $key => $value) {

                        $query = "SELECT
                                SUM(spp.so_po_qty)so_po_qty,
                                SUM(spp.so_po_approve_qty)so_po_approve_qty
                            FROM
                                so_po_productes spp
                            WHERE
                                spp.so_po_id =" . $value;

                        $query = $this->db->query($query);
                        $so_po_qty = $query->row();


                        if ($so_po_qty->so_po_qty == $so_po_qty->so_po_approve_qty) {
                            $this->db->set('status', '4');
                            $this->db->where('id', $value);
                            $this->db->update('so_po_info ');
                        } else {
                            $this->db->set('status', '3');
                            $this->db->where('id', $value);
                            $this->db->update('so_po_info ');
                        }

                    }

                }

                if (!empty($all_sdc_mrn_product)) {
                    $this->Common_model->insert_batch_save(' sdc_mrn_products', $all_sdc_mrn_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));
                } else {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $sales_purchase_invoice_id));
                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('material received note');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('material received note_list');
        $data['second_link_page_url'] = $this->project . '/material_received_note_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();

        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);

        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/purchase_order/purchase_invoice_against_mrn', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }


    public function load_mrn_sdc_ides_by_supplier_customer()
    {


        $sup_cus_id = $this->input->post('sup_cus_id');
        $branch_id = $this->input->post('branch_id');
        $type = $this->input->post('type');

        if ($sup_cus_id != null) {

            $this->db->select("id ,sdc_mrn_no");
            $this->db->from("sdc_mrn_info");
            if ($type == 1) {
                $this->db->where('supplier_id', $sup_cus_id);
                $this->db->where('form_id', 32);
            } else {
                $this->db->where('customer_id', $sup_cus_id);
                $this->db->where('form_id', 7);
            }

            //0->pending,1->approved,2->cancel,3->partially delivery,4->complete delivery,5->payment
            $this->db->group_start();
            $this->db->where_in('status', array(0, 1, 3));
            //$this->db->or_where('status', 2);
            $this->db->group_end();
            $this->db->where('is_active', "Y");
            $this->db->where('is_delete', "N");
            $customer_so = $this->db->get()->result();

            $add = '';
            if (!empty($customer_so)):
                $add .= "<option value=''></option>";
                foreach ($customer_so as $key => $value):
                    $add .= "<option   value='" . $value->id . "' >$value->sdc_mrn_no   </option>";
                endforeach;
                echo $add;
                DIE;
            else:

                /*  if($type==1){
                      echo "<option value='' selected disabled>Purchase Order Not Available</option>";
                  }else{
                      echo "<option value='' selected disabled>Sales Order Not Available</option>";

                  }*/

                echo "<option value='' selected disabled>MRN Not Available</option>";
                DIE;
            endif;

        }
    }

    function get_mrn_sdc_info()
    {
        $json = array();


//add custom filter here
        $mrn_sdc_ides = json_decode($this->input->post('ides'));

        if (!empty($mrn_sdc_ides)) {
            $this->db->where_in('sdc_mrn_info.id', $mrn_sdc_ides);
        }
        if (!empty($this->input->post('form_id'))) {
            $this->db->where('sdc_mrn_info.form_id', $this->input->post('form_id'));
        }
        if (!empty($this->input->post('customerid'))) {
            $this->db->where('sdc_mrn_info.customer_id', $this->input->post('customerid'));
        }
        if (!empty($this->input->post('supplier_id'))) {
            $this->db->where('sdc_mrn_info.supplier_id', $this->input->post('supplier_id'));
        }
        if (!empty($this->input->post('BranchAutoId'))) {
            $this->db->where('sdc_mrn_info.branch_id', $this->input->post('BranchAutoId'));
        }

        $this->db->select("sdc_mrn_info.id as mrn_sdc_info_id,
    sdc_mrn_info.sdc_mrn_no,
    sdc_mrn_info.mrn_recived_date,
    sdc_mrn_info.sdc_delivery_date,
    (SELECT sum(sdc_mrn_products.mrn_sdc_qty*sdc_mrn_products.mrn_sdc_unit_price) as sdc_mrn_amount from sdc_mrn_products WHERE sdc_mrn_info_id=sdc_mrn_info.id) as sdm_mrn_amount
    ");

        $this->db->from('sdc_mrn_info ');
        //$this->db->join(' sdc_mrn_products ', 'sdc_mrn_info.id = sdc_mrn_products.product_id', 'left');
        $this->db->group_by('sdc_mrn_info.id');
        $this->db->order_by("sdc_mrn_info.id");

        $i = 0;


        //$list = $this->ReturnDamageModel->get_sales_invoice_details_for_return();


        $query = $this->db->get();
        $list = $query->result_array();
        log_message("error", " query " . print_r($this->db->last_query(), true));

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);


        $data = array();
        foreach ($list as $element) {

            $check_box = "<input class='form-check-input checkbox_so_po_product' name='mrn_sdc_info_id[]' type='checkbox' value='" . $element['mrn_sdc_info_id'] . "' id='defaultCheck_" . $element['mrn_sdc_info_id'] . "'>";


            $row = array();
            $row[] = $element['id'];

            $row[] = $element['sdc_mrn_no'];
            $row[] = $element['mrn_recived_date'];


            $row[] = "<input type='text' class='form-control unit_price decimal' attr-so-po-product-row-id='" . $element['mrn_sdc_info_id'] . "' id='unit_price_" . $element['mrn_sdc_info_id'] . "' name='sdm_mrn_amount_" . $element['mrn_sdc_info_id'] . "' value='" . $element['sdm_mrn_amount'] . "' placeholder='" . $element['sdm_mrn_amount'] . "' arrt-actual-unit-price='" . $element['sdm_mrn_amount'] . "' readonly='true' onclick='this.select();' style='text-align: right;'/>";;

            $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['mrn_sdc_info_id'] . "' id='one_line_narration_" . $element['mrn_sdc_info_id'] . "' name='one_line_narration_" . $element['mrn_sdc_info_id'] . "' value='" . "" /*$element['one_line_narration']*/ . "' placeholder='" . $element['one_line_narration'] . "' readonly='true' onclick='this.select();' style='text-align: left;'/>";
            $row[] = $check_box;
            $data[] = $row;
        }

        $json['data'] = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => $data,
        );
        //output to json format
        $this->output->set_header('Content-Type: application/json');
        echo json_encode($json['data']);
    }

    public function material_received_note_prepare_bill($id)
    {

        if (isPostBack()) {

            $this->form_validation->set_rules('partialPayment', 'Payment Type', 'required');


            if ($this->form_validation->run() == FALSE && $id == '') {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/prepare_bill/'));
            } else {

                $this->db->trans_start();

                $sdc_mrn_info = $this->Common_model->get_single_data_by_single_column('sdc_mrn_info', 'id', $id);
                $supplier_id = $sdc_mrn_info->supplier_id;
                $purchase_sales_invoice_id = $sdc_mrn_info->purchase_sales_invoice_id;


                $condition = array(
                    'for' => 9,
                    'BackReferenceInvoiceID' => $purchase_sales_invoice_id
                );
                $ac_accounts_vouchermst = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $condition);
                $Accounts_VoucherMst_AutoID = $ac_accounts_vouchermst->Accounts_VoucherMst_AutoID;


                $item_received_but_bill_pending_condtion = array(
                    'parent_name' => "Item Received But Bill Pending",
                    'is_active' => "Y",
                );
                $Item_received_but_bill_pending = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $item_received_but_bill_pending_condtion);


                $ac_tb_accounts_voucherdtl_condition = array(
                    'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID,
                    'CHILD_ID' => $Item_received_but_bill_pending->id,

                );

                $supplier_ledger_condition = array(
                    'related_id' => $supplier_id,
                    'related_id_for' => 2,
                );
                $supplier_ledger = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $supplier_ledger_condition);

                $ac_tb_accounts_voucherdtl = array();
                $ac_tb_accounts_voucherdtl['CHILD_ID'] = $supplier_ledger->id;
                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $ac_tb_accounts_voucherdtl, $ac_tb_accounts_voucherdtl_condition);

                if ($this->input->post('partialPayment') > 0) {


                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $Accounts_VoucherMst_AutoID;
                    $accountingDetailsTable['TypeID'] = '1';
                    $accountingDetailsTable['CHILD_ID'] = $supplier_ledger->id;
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('partialPayment');
                    $accountingDetailsTable['GR_CREDIT'] = 0;
                    $accountingDetailsTable['Reference'] = 'Supplier Paid';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $sdc_mrn_info->branch_id;
                    $accountingDetailsTable['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';;
                    $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);


                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $Accounts_VoucherMst_AutoID;
                    $accountingDetailsTable['TypeID'] = '2';
                    $accountingDetailsTable['CHILD_ID'] = $this->input->post('accountCrPartial');
                    $accountingDetailsTable['GR_DEBIT'] = 0;
                    $accountingDetailsTable['GR_CREDIT'] = $this->input->post('partialPayment');
                    $accountingDetailsTable['Reference'] = '';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $sdc_mrn_info->branch_id;
                    $accountingDetailsTable['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';;
                    $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);


                    $purchase_invoice_info_condition = array(
                        'purchase_invoice_id' => $purchase_sales_invoice_id,
                    );
                    $purchase_invoice_info = array();
                    $purchase_invoice_info['paid_amount'] = $this->input->post('partialPayment');
                    $this->Common_model->save_and_check('purchase_invoice_info', $purchase_invoice_info, $purchase_invoice_info_condition);

                    $sdc_mrn_info_satatus_condition = array(
                        'id' => $id,
                    );
                    $sdc_mrn_info_satatus = array();
                    $sdc_mrn_info_satatus['status'] = 5;
                    $this->Common_model->save_and_check('sdc_mrn_info', $sdc_mrn_info_satatus, $sdc_mrn_info_satatus_condition);

                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                        'invoiceID' => $purchase_sales_invoice_id,
                        'totalPayment' => $this->input->post('partialPayment'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $Accounts_VoucherMst_AutoID,
                        'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID,
                        'customerid' => $supplier_id,
                        'narration' => "money recived against mrn",
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $sdc_mrn_info->branch_id,
                        'paymentType' => 1,
                        'receiveType' => 2,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/material_received_note/'));
                } else {
                    $msg = 'Material Received Note ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/view_mrn_bill/' . $id));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $sales_purchase_invoice_id));
                }
            }
        }

        $data = array();

        $data['title'] = get_phrase('material received note prepare_bill');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('material received note_list');
        $data['second_link_page_url'] = $this->project . '/material_received_note_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $data['sdc_mrn_info'] = $this->Common_model->get_single_data_by_single_column('sdc_mrn_info', 'id', $id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['so_po_info']->customer_id);
        $data['supplier'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['sdc_mrn_info']->supplier_id);

        $mrCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
        );
        $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
        $data['mrid'] = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);


        $this->db->select('
        sdc_mrn_products.mrn_sdc_qty,
        sdc_mrn_products.mrn_sdc_unit_price,
        sdc_mrn_products.status,
        product.product_id,productcategory.title as productCat,
        product.brand_id,product.category_id,product.productName,
        product.dist_id,product.status,
        brand.brandName,
        unit.unitTtile,
        unit.unit_id,
        tb_subcategory.SubCatName,
        tb_model.Model,
        tb_color.Color,
        sdc_mrn_products.property_1,
        sdc_mrn_products.property_2,
        sdc_mrn_products.property_3,
        sdc_mrn_products.property_4,
        sdc_mrn_products.property_5,
        sdc_mrn_products.one_line_narration,
        product.salesPrice,
        tb_size.Size');
        $this->db->from('sdc_mrn_products');
        $this->db->join('product', 'sdc_mrn_products.product_id = product.product_id', 'left');
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->join('tb_subcategory', 'tb_subcategory.SubCatID = product.subcategoryID', 'left');
        $this->db->join('tb_model', 'tb_model.ModelID = product.modelID', 'left');
        $this->db->join('tb_color', 'tb_color.ColorID = product.colorID', 'left');
        $this->db->join('tb_size', 'tb_size.SizeID = product.SizeID', 'left');


        $this->db->where('sdc_mrn_products.sdc_mrn_info_id', $id);

        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        $data['sdc_mrn_products'] = $this->db->get()->result_array();

        $data['mainContent'] = $this->load->view('distributor/purchase_order/material_received_note_prepare_bill', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }

    function view_mrn_bill($id)
    {

        $data['title'] = get_phrase('material received note prepare_bill');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('material received note_list');
        $data['second_link_page_url'] = $this->project . '/material_received_note_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $data['sdc_mrn_info'] = $this->Common_model->get_single_data_by_single_column('sdc_mrn_info', 'id', $id);

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        //$data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['so_po_info']->customer_id);
        $data['suplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['sdc_mrn_info']->supplier_id);
        $data['purchase_invoice_info'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $data['sdc_mrn_info']->purchase_sales_invoice_id);
        //$data['purchase_invoice_info'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $data['sdc_mrn_info']->purchase_sales_invoice_id);

        $moneyreceit_condtion = array(
            'customerid' => $data['sdc_mrn_info']->supplier_id,
            'receiveType' => "2",
            'invoiceID' => $data['sdc_mrn_info']->purchase_sales_invoice_id,
        );
        $data['moneyReceitInfo'] = $this->Common_model->get_single_data_by_many_columns('moneyreceit', $moneyreceit_condtion);


        $data['mainContent'] = $this->load->view('distributor/purchase_order/view_mrn_bill', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);

    }

}