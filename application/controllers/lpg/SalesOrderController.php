<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 6/13/2020
 * Time: 10:02 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesOrderController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $link_icon_add;
    public $link_icon_list;
    public $link_icon_view;
    public $TypeDR;
    public $TypeCR;
    public $salesEmptyCylinderWithRefill;
    public $CostOfEmptyCylinderWithRefill;
    public $discountOnSales;


    public $business_type;
    public $folder;
    public $folderSub;

    public $db_hostname;
    public $db_username;
    public $db_password;
    public $db_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $this->page_type = 'Sales';

        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_view = "<i class='fa fa-view'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
        $this->TypeDR = 1;
        $this->TypeCR = 2;
        $this->discountOnSales = $this->config->item("Discount");;

        if ($this->project == 'farabitraders') {
            $this->salesEmptyCylinderWithRefill = 618;
            $this->CostOfEmptyCylinderWithRefill = 617;
        } else if ($this->project == 'rftraders') {
            $this->salesEmptyCylinderWithRefill = 508;
            $this->CostOfEmptyCylinderWithRefill = 509;
        } else if ($this->project == 'tuhinEnterprise') {
            $this->discountOnSales = 338;
        } else if ($this->project == 'msak_enterprise') {
            $this->discountOnSales = 478;
        } else if ($this->project == 'rajTraders') {
            $this->discountOnSales = 762;
        } else {
            $this->salesEmptyCylinderWithRefill = $this->config->item("salesEmptyCylinderWithRefill");
            $this->CostOfEmptyCylinderWithRefill = $this->config->item("CostOfEmptyCylinderWithRefill");
        }


        if ($this->business_type != "LPG") {
            $this->folder = 'distributor/masterTemplateSmeMobile';

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        } else {
            $this->folder = 'distributor/masterTemplate';
        }


        $this->folderSub = 'distributor/inventory/brand/';
    }

    public function sales_order_add()
    {

        $this->load->helper('get_so_po_no_helper');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {
            $this->form_validation->set_rules('so_date', 'So Date', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('slNo[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/sales_order/'));
            } else {


                $this->db->trans_start();
                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');
                $all_so_product = array();
                $invoice_no = get_so_po_no('SO');
                $sales_so['form_id'] = 7;
                $sales_so['so_po_no'] = $invoice_no;
                $sales_so['customer_id'] = $customer_id;
                $sales_so['supplier_id'] = 0;
                $sales_so['refference_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_so['shipping_address'] = $shippingAddress;
                $sales_so['delivery_date'] = $delivery_date;
                $sales_so['so_po_date'] = $so_date;
                $sales_so['refference_person_id'] = $this->input->post('reference');
                $sales_so['narration'] = $narration;
                $sales_so['status'] = 0;//1->complete
                $sales_so['company_id'] = $this->dist_id;
                $sales_so['branch_id'] = $branch_id;
                $sales_so['insert_date'] = $this->timestamp;
                $sales_so['insert_by'] = $this->admin_id;
                $sales_so['update_by'] = '';
                $sales_so['update_date'] = '';
                $sales_so['delete_by'] = '';
                $sales_so['delete_date'] = '';
                $sales_so['is_active'] = 'Y';
                $sales_so['is_delete'] = 'N';
                $sales_so['extra_field_1'] = $this->input->post('sales_order_extra_field_1');
                $sales_so['extra_field_2'] = $this->input->post('sales_order_extra_field_2');
                $sales_so['extra_field_3'] = $this->input->post('sales_order_extra_field_3');
                $sales_so['extra_field_4'] = $this->input->post('sales_order_extra_field_4');
                $sales_so['extra_field_5'] = $this->input->post('sales_order_extra_field_5');
                $sales_so['lc_field_1'] = $this->input->post('sales_order_lc_field_1');
                $sales_so['lc_field_2'] = $this->input->post('sales_order_lc_field_2');
                $sales_so['lc_field_3'] = $this->input->post('sales_order_lc_field_3');
                $so_id = $this->Common_model->insert_data('so_po_info', $sales_so);


                foreach ($_POST['slNo'] as $key => $value) {
                    $so_product = array();
                    $product_id = $_POST['product_id_' . $value];
                    $quantity = $_POST['quantity_' . $value];
                    $unit_price = $_POST['rate_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];
                    $one_line_narration = $_POST['one_line_narration_' . $value];

                    $so_product['so_po_id'] = $so_id;
                    $so_product['customer_id'] = $customer_id;
                    $so_product['supplier_id'] = 0;
                    $so_product['form_id'] = 7;
                    $so_product['delivery_date'] = $delivery_date;
                    $so_product['so_po_date'] = $so_date;
                    $so_product['product_id'] = $product_id;
                    $so_product['so_po_qty'] = $quantity;
                    $so_product['so_po_unit_price'] = $unit_price;
                    $so_product['so_po_approve_qty'] = 0;
                    $so_product['status'] = 0;//1->complete
                    $so_product['branch_id'] = $branch_id;
                    $so_product['property_1'] = $property_1;
                    $so_product['property_2'] = $property_2;
                    $so_product['property_3'] = $property_3;
                    $so_product['property_4'] = $property_4;
                    $so_product['property_5'] = $property_5;
                    $so_product['one_line_narration'] = $one_line_narration;
                    $so_product['insert_date'] = $this->timestamp;
                    $so_product['insert_by'] = $this->admin_id;
                    $so_product['update_by'] = '';
                    $so_product['update_date'] = '';
                    $so_product['delete_by'] = '';
                    $so_product['delete_date'] = '';
                    $so_product['is_active'] = 'Y';
                    $so_product['is_delete'] = 'N';
                    $all_so_product[] = $so_product;
                }
                if (!empty($all_so_product)) {
                    $this->db->insert_batch('so_po_productes', $all_so_product);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/sales_order/'));
                } else {
                    $msg = 'Sales Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/sales_order/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }

        $data = array();
        $data['title'] = get_phrase('slaes_order_add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('sales_order_list');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2110);
        $menu  = check_parmission_by_user_role(26);
        
        if($add == 0 || $$menu == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        } else{
            $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_add', $data, true);
            $this->load->view("distributor/masterTemplateSmeMobile", $data);
        }
       
    }
    public function sales_delivery_challan()
    {

        $this->load->helper('get_so_po_no_helper');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {

            $this->form_validation->set_rules('so_date', 'So Date', 'required');
            $this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('slNo[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/sales_delivery_challan/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $supplier_id = $this->input->post('supplier_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');
                $invoice_no = $this->input->post('po_id');

                $all_so_product = array();


                $sales_so['form_id'] = 8;
                $sales_so['so_po_no'] = $this->input->post('po_id');
                $sales_so['customer_id'] = 0;
                $sales_so['supplier_id'] = $supplier_id;

                $sales_so['refference_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_so['shipping_address'] = $shippingAddress;
                $sales_so['delivery_date'] = $delivery_date;
                $sales_so['so_po_date'] = $so_date;
                $sales_so['refference_person_id'] = $this->input->post('reference');
                $sales_so['narration'] = $narration;
                $sales_so['status'] = 0;//1->complete
                $sales_so['company_id'] = $this->dist_id;

                $sales_so['branch_id'] = $branch_id;
                $sales_so['insert_date'] = $this->timestamp;
                $sales_so['insert_by'] = $this->admin_id;
                $sales_so['update_by'] = '';
                $sales_so['update_date'] = '';
                $sales_so['delete_by'] = '';
                $sales_so['delete_date'] = '';
                $sales_so['is_active'] = 'Y';
                $sales_so['is_delete'] = 'N';


                $po_id = $this->Common_model->insert_data('so_po_info', $sales_so);


                foreach ($_POST['slNo'] as $key => $value) {
                    $so_product = array();
                    $product_id = $_POST['product_id_' . $value];
                    $quantity = $_POST['quantity_' . $value];
                    $unit_price = $_POST['rate_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];

                    $so_product['so_po_id'] = $po_id;
                    $so_product['customer_id'] = 0;
                    $so_product['supplier_id'] = $supplier_id;
                    $so_product['form_id'] = 8;
                    $so_product['delivery_date'] = $delivery_date;
                    $so_product['so_po_date'] = $so_date;
                    $so_product['product_id'] = $product_id;
                    $so_product['so_po_qty'] = $quantity;
                    $so_product['so_po_unit_price'] = $unit_price;
                    $so_product['so_po_approve_qty'] = 0;
                    $so_product['status'] = 0;//1->complete
                    $so_product['branch_id'] = $branch_id;
                    $so_product['property_1'] = $property_1;
                    $so_product['property_2'] = $property_2;
                    $so_product['property_3'] = $property_3;
                    $so_product['property_4'] = $property_4;
                    $so_product['property_5'] = $property_5;
                    $so_product['insert_date'] = $this->timestamp;
                    $so_product['insert_by'] = $this->admin_id;
                    $so_product['update_by'] = '';
                    $so_product['update_date'] = '';
                    $so_product['delete_by'] = '';
                    $so_product['delete_date'] = '';
                    $so_product['is_active'] = 'Y';
                    $so_product['is_delete'] = 'N';


                    $all_so_product[] = $so_product;


                }
                if (!empty($all_so_product)) {
                    $this->Common_model->insert_batch_save('so_po_productes', $all_so_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Purchase Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/purchase_order/'));
                } else {
                    $msg = 'Purchase Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/purchase_order/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('sales_delivery_challan');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('sales_delivery_challan');
        $data['second_link_page_url'] = $this->project . '/purchase_order_list';
        $data['second_link_icon'] = $this->link_icon_list;




        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_delivery_challan', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }
    public function sales_order_list()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('sales_order_list');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Order');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = "<i class='ace-icon fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function get_product_list_by_dist_id()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            if (isset($_GET['receiveStatus'])) {
                $status = strtolower($_GET['receiveStatus']);
            } else {
                $status = 0;
            }
            echo json_encode($this->Common_model->get_product_list_for_auto_complete($this->dist_id, $q, $status));
        }
    }

    public function so_po_view($id)
    {
        $data['title'] = get_phrase('Sale Order View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sales_Order');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Sales Order List');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('Sale_Order_Edit');
        $data['third_link_page_url'] = $this->project . '/sales_order_edit/' . $id;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';

        $data['so_po_info'] = $this->Common_model->get_single_data_by_single_column('so_po_info', 'id', $id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['so_po_info']->customer_id);


        $this->db->select('
        so_po_productes.so_po_qty,
        so_po_productes.so_po_unit_price,
        so_po_productes.so_po_approve_qty,
        so_po_productes.status,
        product.product_id,productcategory.title as productCat,
        product.brand_id,product.category_id,product.productName,
        product.dist_id,product.status,
        brand.brandName,
        unit.unitTtile,
        unit.unit_custom_field_1,
        unit.unit_custom_field_2,
        unit.unit_id,
        tb_subcategory.SubCatName,
        tb_model.Model,
        tb_color.Color,
        so_po_productes.property_1,
        so_po_productes.property_2,
        so_po_productes.property_3,
        so_po_productes.property_4,
        so_po_productes.property_5,
        so_po_productes.one_line_narration,
        product.salesPrice,
        tb_size.Size');
        $this->db->from('so_po_productes');
        $this->db->join('product', 'so_po_productes.product_id = product.product_id', 'left');
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->join('tb_subcategory', 'tb_subcategory.SubCatID = product.subcategoryID', 'left');
        $this->db->join('tb_model', 'tb_model.ModelID = product.modelID', 'left');
        $this->db->join('tb_color', 'tb_color.ColorID = product.colorID', 'left');
        $this->db->join('tb_size', 'tb_size.SizeID = product.SizeID', 'left');


        $this->db->where('so_po_productes.so_po_id', $id);


        $data['so_po_details'] = $this->db->get()->result_array();



        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_view', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }


    public function sales_order_edit($id)
    {

        $this->load->helper('sales_invoice_no_helper');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {
        // echo "<pre>";
        // print_r($_POST);
        // exit();

            $this->form_validation->set_rules('so_date', 'So Date', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('slNo[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/sales_order/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');


                $all_so_product = array();
                $invoice_no = $this->input->post('so_id');

                $sales_so = array();

                $sales_so['customer_id'] = $customer_id;
                $sales_so['supplier_id'] = 0;
                $sales_so['refference_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_so['shipping_address'] = $shippingAddress;
                $sales_so['delivery_date'] = $delivery_date;
                $sales_so['so_po_date'] = $so_date;
                $sales_so['refference_person_id'] = $this->input->post('reference');
                $sales_so['narration'] = $narration;
                $sales_so['status'] = 0;//1->complete
                $sales_so['company_id'] = $this->dist_id;
                $sales_so['branch_id'] = $branch_id;
                $sales_so['insert_date'] = $this->timestamp;
                $sales_so['insert_by'] = $this->admin_id;
                $sales_so['update_by'] = '';
                $sales_so['update_date'] = '';
                $sales_so['delete_by'] = '';
                $sales_so['delete_date'] = '';
                $sales_so['is_active'] = 'Y';
                $sales_so['is_delete'] = 'N';
                $so_po_info_update_condition = array(
                    'id' => $id,
                    'form_id' => 7,
                );
                $this->Common_model->save_and_check('so_po_info', $sales_so, $so_po_info_update_condition);


                $this->_save_data_to_so_po_history_table($id, $voucherType=7, $action = 'edit', $redirect_page='sales_order_edit', $name);


                $DeleteCondition_so_po_productes = array(
                    'so_po_id' => $id
                );
                $this->Common_model->delete_data_with_condition('so_po_productes', $DeleteCondition_so_po_productes);




                foreach ($_POST['slNo'] as $key => $value) {
                    $so_product = array();
                    $product_id = $_POST['product_id_' . $value];
                    $quantity = $_POST['quantity_' . $value];
                    $unit_price = $_POST['rate_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];

                    $so_product['so_po_id'] = $id;
                    $so_product['customer_id'] = $customer_id;
                    $so_product['supplier_id'] = 0;
                    $so_product['form_id'] = 7;
                    $so_product['delivery_date'] = $delivery_date;
                    $so_product['so_po_date'] = $so_date;
                    $so_product['product_id'] = $product_id;
                    $so_product['so_po_qty'] = $quantity;
                    $so_product['so_po_unit_price'] = $unit_price;
                    $so_product['so_po_approve_qty'] = 0;
                    $so_product['status'] = 0;//1->complete
                    $so_product['branch_id'] = $branch_id;
                    $so_product['property_1'] = $property_1;
                    $so_product['property_2'] = $property_2;
                    $so_product['property_3'] = $property_3;
                    $so_product['property_4'] = $property_4;
                    $so_product['property_5'] = $property_5;
                    $so_product['insert_date'] = $this->timestamp;
                    $so_product['insert_by'] = $this->admin_id;
                    $so_product['update_by'] = '';
                    $so_product['update_date'] = '';
                    $so_product['delete_by'] = '';
                    $so_product['delete_date'] = '';
                    $so_product['is_active'] = 'Y';
                    $so_product['is_delete'] = 'N';


                    $all_so_product[] = $so_product;


                }
                if (!empty($all_so_product)) {
                    $this->db->insert_batch('so_po_productes', $all_so_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/sales_order_edit/'. $id));
                } else {
                    $msg = 'Sales Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/sales_order_edit/'. $id));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('slaes_order_edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sales_Order');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('sales_order_list');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['so_po_info'] = $this->Common_model->get_single_data_by_single_column('so_po_info', 'id', $id);

        $this->db->select('
        
        so_po_productes.so_po_qty,
        so_po_productes.so_po_unit_price,
        so_po_productes.so_po_approve_qty,
        so_po_productes.status,
        product.product_id,productcategory.title as productCat,
        product.brand_id,product.category_id,product.productName,
        product.dist_id,product.status,
        brand.brandName,
       
        unit.unitTtile,
        unit.unit_custom_field_1,
        unit.unit_custom_field_2,
        unit.unit_id,
        tb_subcategory.SubCatName,
        tb_model.Model,
        tb_color.Color,
        so_po_productes.property_1,
        so_po_productes.property_2,
        so_po_productes.property_3,
        so_po_productes.property_4,
        so_po_productes.property_5,
        product.salesPrice,
        tb_size.Size');
        $this->db->from('so_po_productes');
        $this->db->join('product', 'so_po_productes.product_id = product.product_id', 'left');
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->join('tb_subcategory', 'tb_subcategory.SubCatID = product.subcategoryID', 'left');
        $this->db->join('tb_model', 'tb_model.ModelID = product.modelID', 'left');
        $this->db->join('tb_color', 'tb_color.ColorID = product.colorID', 'left');
        $this->db->join('tb_size', 'tb_size.SizeID = product.SizeID', 'left');


        $this->db->where('so_po_productes.so_po_id', $id);


        $data['so_po_details'] = $this->db->get()->result_array();
       /* echo "<pre>";
        print_r($data['so_po_details']);
        exit;*/

        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $this->load->helper('site_helper');
        $edit  = check_parmission_by_user_role(2111);
        if($edit == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
            $this->load->view("distributor/masterTemplateSmeMobile", $data);
           
        } else{
            if ($this->business_type != "LPG") {
                $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceMobile/editInvoiceNew', $data, true);
                $this->load->view("distributor/masterTemplateSmeMobile", $data);
            } else {
                $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_edit', $data, true);
                $this->load->view("distributor/masterTemplateSmeMobile", $data);
            }
        }

        
    }


    public function sales_invoice_against_sales_order_add()
    {
        $this->load->helper('sales_invoice_no_helper');

        if (isPostBack()) {


            $this->form_validation->set_rules('so_id', 'So Date', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('so_po_product_row_id[]', 'Voucehr ID', 'required');
         
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/salesInvoiceAgainstSO/'));
            } else {


                $this->db->trans_start();

                $so_id = $this->input->post('so_id');
                $payType = $this->input->post('paymentType');
                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $saleDate = $this->input->post('saleDate') != '' ? date('Y-m-d', strtotime($this->input->post('saleDate'))) : '';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');
                $bankName = $this->input->post('bankName');

                $so_po_data['status'] = 0;
                $so_po_data['branch_id'] = $branch_id;
                $so_po_data['update_by'] = $this->admin_id;
                $so_po_data['update_date'] = $this->timestamp;
                $so_po_data['is_delete'] = 'N';
                $so_po_UpdateCondition = array(
                    'id' => $so_id,

                );
                $this->Common_model->save_and_check('so_po_info', $so_po_data, $so_po_UpdateCondition);


                $invoice_no = create_sales_invoice_no();
                $sales_inv['invoice_no'] = $invoice_no;

                $sales_inv['customer_invoice_no'] = $so_id;
                $sales_inv['customer_id'] = $customer_id;
                $sales_inv['payment_type'] = $payType;

                $sales_inv['invoice_amount'] = $this->input->post('netTotal');
                $sales_inv['vat_amount'] = 0;
                $sales_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $sales_inv['paid_amount'] = $this->input->post('partialPayment') != '' ? $this->input->post('partialPayment') : 0;
                $sales_inv['delivery_address'] = $this->input->post('shippingAddress');
                $sales_inv['delivery_date'] = $this->input->post('delivery_date') != '' ? date($this->input->post('delivery_date')) : 'NULL';
                $sales_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $sales_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $sales_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $sales_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $sales_inv['refference_person_id'] = $this->input->post('reference');
                $sales_inv['narration'] = $this->input->post('narration');
                $sales_inv['company_id'] = $this->dist_id;
                $sales_inv['dist_id'] = $this->dist_id;
                $sales_inv['branch_id'] = $branch_id;
                if ($this->input->post('creditDueDate') != '') {


                    $sales_inv['due_date'] = $this->input->post('creditDueDate') != '' ? date('Y-m-d', strtotime($this->input->post('creditDueDate'))) : 'NULL';
                }
                $sales_inv['invoice_date'] = $saleDate;
                $sales_inv['insert_date'] = $this->timestamp;
                $sales_inv['insert_by'] = $this->admin_id;
                $sales_inv['invoice_for'] = 2;
                $sales_inv['is_active'] = 'Y';
                $sales_inv['is_delete'] = 'N';

                if ($payType == 3) {
                    $sales_inv['bank_id'] = $bankName;
                    //$sales_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $sales_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $sales_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }
                $cash_ledger_id = 0;
                if ($payType == 4) {
                    $cash_ledger_id = $this->input->post('accountCrPartial');
                }
                $sales_inv['cash_ledger_id'] = $cash_ledger_id;


                $invoice_id = $this->Common_model->insert_data('sales_invoice_info', $sales_inv);
                if ($payType == 2) {
                    //for due invoice  Journal Voucher
                    $voucher_no = create_journal_voucher_no();
                    $AccouVoucherType_AutoID = 3;
                } else {
                    //Payment Voucher
                    $this->load->helper('create_receive_voucher_no_helper');
                    $voucher_no = create_receive_voucher_no();
                    $AccouVoucherType_AutoID = 1;
                }
                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $saleDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $invoice_id;
                $accountingMasterTable['Narration'] = 'Sales Order Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['customer_id'] = $customer_id;
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['for'] = 2;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);

                $all_so_product = array();


                $totalProductCost = 0;
                $sales_price = 0;
                $totalGR_DEBIT =  0;
                $totalGR_CREDIT = 0;
                foreach ($_POST['so_po_product_row_id'] as $key => $value) {
                    $so_product = array();
                    $so_po_product_row_id = $value;
                    $quantity = $_POST['so_po_issue_qty_' . $value];
                    $unit_price = $_POST['unit_price_' . $value];
                    $so_po_product_id = $_POST['so_po_product_id_' . $value];
                    $so_po_product_category_id = $_POST['so_po_product_category_id_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];
                    $sales_price = $sales_price + ($quantity * $unit_price);
                    $query = 'UPDATE so_po_productes
                        SET so_po_approve_qty=so_po_approve_qty+' . $quantity . '
                        WHERE id =' . $so_po_product_row_id;
                    $this->db->query($query);

                    unset($stock);

                    $productCost = $this->Sales_Model->productCostNew($so_po_product_id, $branch_id);


                    $totalProductCost += ($quantity * $productCost);

                    $lastPurchasepriceArray = $this->db->where('product_id', $so_po_product_id)
                        ->where('branch_id', $branch_id)
                        ->order_by('purchase_details_id', "desc")
                        ->limit(1)
                        ->get('purchase_details')
                        ->row();
                    $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;
                    $stock['sales_invoice_id'] = $invoice_id;
                    $stock['customer_id'] = $customer_id;
                    $stock['product_id'] = $so_po_product_id;
                    $stock['is_package '] = 0;
                    $stock['returnable_quantity '] = 0;
                    $stock['return_quentity '] = 0;

                    $stock['customer_due'] = 0;
                    $stock['customer_advance'] = 0;
                    $stock['quantity'] = $quantity;
                    $stock['unit_price'] = $unit_price;
                    $stock['last_purchase_price '] = $lastPurchaseprice;
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $stock['branch_id'] = $branch_id;
                    $stock['property_1'] = $property_1;
                    $stock['property_2'] = $property_2;
                    $stock['property_3'] = $property_3;
                    $stock['property_4'] = $property_4;
                    $stock['property_5'] = $property_5;
                    $sales_details_id = $this->Common_model->insert_data('sales_details', $stock);



                    $stockNewTable['parent_stock_id'] = 0;
                    $stockNewTable['invoice_id'] = $invoice_id;
                    $stockNewTable['form_id'] = 3;
                    $stockNewTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                    $stockNewTable['customer_id'] = $customer_id;
                    $stockNewTable['supplier_id'] = 0;
                    $stockNewTable['branch_id'] = $branch_id;
                    $stockNewTable['invoice_date'] = $saleDate;
                    $stockNewTable['category_id'] = $so_po_product_category_id;
                    $stockNewTable['product_id'] = $so_po_product_id;
                    $stockNewTable['empty_cylinder_id'] = 0;
                    $stockNewTable['is_package'] = 0;
                    $stockNewTable['show_in_invoice'] = 1;
                    $stockNewTable['unit'] = getProductUnit($so_po_product_id);
                    $stockNewTable['type'] = 2;
                    $stockNewTable['quantity'] = $quantity;
                    $stockNewTable['quantity_out'] = $quantity;
                    $stockNewTable['quantity_in'] = 0;
                    $stockNewTable['returnable_quantity'] = 0;
                    $stockNewTable['return_quentity'] = 0;
                    $stockNewTable['due_quentity'] = 0;
                    $stockNewTable['advance_quantity'] = 0;
                    $stockNewTable['price'] = $unit_price;
                    $stockNewTable['price_in'] = 0;
                    $stockNewTable['price_out'] = $unit_price;
                    $stockNewTable['last_purchase_price'] = $lastPurchaseprice;
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


                    $otherProduct['product_id'] = $so_po_product_id;
                    $otherProduct['price'] = ($quantity * $productCost);
                    $otherProduct['quantity'] = ($quantity);
                    $otherProduct['unit_price'] = ($unit_price);
                    $otherProduct['stock_id'] = $stock_id;

                    $allOtherProductArray[] = $otherProduct;
                    $all_so_product[] = $so_product;


                }


                if (!empty($allOtherProductArray)) {
                    /*Inventory stock Refill=*/
                    foreach ($allOtherProductArray as $keyOtherProduct => $valueOtherProduct) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueOtherProduct['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        /*Inventory stock Refill=>95*/
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableOtherProductCost['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableOtherProductCost['TypeID'] = $this->TypeCR;//'2';//Cr
                        $accountingDetailsTableOtherProductCost['CHILD_ID'] = $ac_account_ledger_coa_info->id;// $this->config->item("Inventory_Stock");//'20';
                        $accountingDetailsTableOtherProductCost['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableOtherProductCost['GR_CREDIT'] = $valueOtherProduct['price'];// $OtherProductCost;
                        $accountingDetailsTableOtherProductCost['Reference'] = 'Inventory stock Out Of ' . $ac_account_ledger_coa_info->parent_name . ' (' . $valueOtherProduct['quantity'] . '*' . $valueOtherProduct['unit_price'] . ')';
                        $accountingDetailsTableOtherProductCost['IsActive'] = 1;
                        $accountingDetailsTableOtherProductCost['Created_By'] = $this->admin_id;
                        $accountingDetailsTableOtherProductCost['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableOtherProductCost['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableOtherProductCost['date'] = $saleDate;
                        //$finalDetailsArray[] = $accountingDetailsTableOtherProductCost;

                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableOtherProductCost);
                        $accountingDetailsTableOtherProductCost=array();

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueOtherProduct['stock_id']);

                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueOtherProduct['price'];
                    }
                }
                $condtion = array(
                    'related_id' => $customer_id,
                    'related_id_for' => 3,
                    'is_active' => "Y",
                );
                $CustomerReceivable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                /*Customer Receivable   =>>33*/
                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivable->id;//$this->config->item("Customer_Receivable");
                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = $this->input->post('netTotal');
                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer Receivable';
                $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('netTotal');
                $totalGR_CREDIT = $totalGR_CREDIT + 0;

                /*Cost of Goods Product =>>45*/
                $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCostofGoodsProduct['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = $this->config->item("Cost_of_Goods_Product");//'45';
                $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = $totalProductCost;
                $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Cost of Goods Product';
                $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
                $accountingDetailsTableCostofGoodsProduct['Created_By'] = $this->admin_id;
                $accountingDetailsTableCostofGoodsProduct['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCostofGoodsProduct['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;

                $totalGR_DEBIT = $totalGR_DEBIT + $totalProductCost;
                $totalGR_CREDIT = $totalGR_CREDIT + 0;
                $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableSales['TypeID'] = $this->TypeCR;//'2';//Cr
                $accountingDetailsTableSales['CHILD_ID'] = $this->config->item("Sales");
                $accountingDetailsTableSales['GR_DEBIT'] = '0.00';
                $accountingDetailsTableSales['GR_CREDIT'] = $sales_price;
                $accountingDetailsTableSales['Reference'] = 'Sales Amount';
                $accountingDetailsTableSales['IsActive'] = 1;
                $accountingDetailsTableSales['Created_By'] = $this->admin_id;
                $accountingDetailsTableSales['Created_Date'] = $this->timestamp;
                $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
                $accountingDetailsTableSales['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableSales;

                $totalGR_DEBIT = $totalGR_DEBIT + 0;
                $totalGR_CREDIT = $totalGR_CREDIT + $sales_price;

                if ($this->input->post('loaderAmount') > 0) {
                    $accountingDetailsTableloaderAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableloaderAmount['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableloaderAmount['CHILD_ID'] = $this->config->item("LoaderPayableSales");//'47';
                    $accountingDetailsTableloaderAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableloaderAmount['GR_CREDIT'] = $this->input->post('loaderAmount');
                    $accountingDetailsTableloaderAmount['Reference'] = 'Loading and wages';
                    $accountingDetailsTableloaderAmount['IsActive'] = 1;
                    $accountingDetailsTableloaderAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableloaderAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableloaderAmount['BranchAutoId'] = $branch_id;;
                    $accountingDetailsTableloaderAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableloaderAmount;
                    $accountingDetailsTableloaderAmount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('loaderAmount');
                }
                /*Loading and wages*/
                /*Transportation*/
                if ($this->input->post('transportationAmount') > 0) {
                    $accountingDetailsTableTransportationAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableTransportationAmount['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableTransportationAmount['CHILD_ID'] = $this->config->item("TransportationPayableSales");//'42';
                    $accountingDetailsTableTransportationAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableTransportationAmount['GR_CREDIT'] = $this->input->post('transportationAmount');
                    $accountingDetailsTableTransportationAmount['Reference'] = 'Transportation Payable Sales';
                    $accountingDetailsTableTransportationAmount['IsActive'] = 1;
                    $accountingDetailsTableTransportationAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableTransportationAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableTransportationAmount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableTransportationAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableTransportationAmount;
                    $accountingDetailsTableTransportationAmount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('transportationAmount');
                }


                $totalGR_DEBIT = number_format($totalGR_DEBIT, 2, '.', '');

                $totalGR_CREDIT = number_format($totalGR_CREDIT, 2, '.', '');


                if ($totalGR_DEBIT != $totalGR_CREDIT) {
                    $this->db->trans_rollback();
                    $msg = 'Sales Invoice ' . ' ' . $this->config->item("save_error_message") . ' There is something wrong please try again .contact with Customer Care';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/salesLpgInvoice_add'));
                }

                log_message('error', 'finalDetailsArray   ' . print_r($finalDetailsArray, true));
                if (!empty($finalDetailsArray)) {

                    //$this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/salesInvoiceAgainstSO/'));
                } else {
                    $msg = 'Sales Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/salesInvoiceAgainstSO/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }
        $data = array();

        $data['title'] = get_phrase('sales_invoice_against_SO');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('sales_invoice_against_SO');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('sales_invoice_against_SO_list');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['voucherID'] = create_sales_invoice_no();
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition);
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        
        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_invoice_against_sales_order_add', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }

    public function sales_invoice_against_sales_delivery_chalan()
    {
        $this->load->helper('sales_invoice_no_helper');

        if (isPostBack()) {


            $this->form_validation->set_rules('so_id', 'So Date', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('so_po_product_row_id[]', 'Voucehr ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/salesInvoiceAgainstSO/'));
            } else {


                $this->db->trans_start();

                $so_id = $this->input->post('so_id');
                $payType = $this->input->post('paymentType');
                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $saleDate = $this->input->post('saleDate') != '' ? date('Y-m-d', strtotime($this->input->post('saleDate'))) : '';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');
                $bankName = $this->input->post('bankName');

                $so_po_data['status'] = 0;
                $so_po_data['branch_id'] = $branch_id;
                $so_po_data['update_by'] = $this->admin_id;
                $so_po_data['update_date'] = $this->timestamp;
                $so_po_data['is_delete'] = 'N';
                $so_po_UpdateCondition = array(
                    'id' => $so_id,

                );
                $this->Common_model->save_and_check('so_po_info', $so_po_data, $so_po_UpdateCondition);


                $invoice_no = create_sales_invoice_no();
                $sales_inv['invoice_no'] = $invoice_no;

                $sales_inv['customer_invoice_no'] = $so_id;
                $sales_inv['customer_id'] = $customer_id;
                $sales_inv['payment_type'] = $payType;

                $sales_inv['invoice_amount'] = $this->input->post('netTotal');
                $sales_inv['vat_amount'] = 0;
                $sales_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $sales_inv['paid_amount'] = $this->input->post('partialPayment') != '' ? $this->input->post('partialPayment') : 0;
                $sales_inv['delivery_address'] = $this->input->post('shippingAddress');
                $sales_inv['delivery_date'] = $this->input->post('delivery_date') != '' ? date($this->input->post('delivery_date')) : 'NULL';
                $sales_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $sales_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $sales_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $sales_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $sales_inv['refference_person_id'] = $this->input->post('reference');
                $sales_inv['narration'] = $this->input->post('narration');
                $sales_inv['company_id'] = $this->dist_id;
                $sales_inv['dist_id'] = $this->dist_id;
                $sales_inv['branch_id'] = $branch_id;
                if ($this->input->post('creditDueDate') != '') {


                    $sales_inv['due_date'] = $this->input->post('creditDueDate') != '' ? date('Y-m-d', strtotime($this->input->post('creditDueDate'))) : 'NULL';
                }
                $sales_inv['invoice_date'] = $saleDate;
                $sales_inv['insert_date'] = $this->timestamp;
                $sales_inv['insert_by'] = $this->admin_id;
                $sales_inv['invoice_for'] = 2;
                $sales_inv['is_active'] = 'Y';
                $sales_inv['is_delete'] = 'N';

                if ($payType == 3) {
                    $sales_inv['bank_id'] = $bankName;
                    //$sales_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $sales_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $sales_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }
                $cash_ledger_id = 0;
                if ($payType == 4) {
                    $cash_ledger_id = $this->input->post('accountCrPartial');
                }
                $sales_inv['cash_ledger_id'] = $cash_ledger_id;


                $invoice_id = $this->Common_model->insert_data('sales_invoice_info', $sales_inv);
                if ($payType == 2) {
                    //for due invoice  Journal Voucher
                    $voucher_no = create_journal_voucher_no();
                    $AccouVoucherType_AutoID = 3;
                } else {
                    //Payment Voucher
                    $this->load->helper('create_receive_voucher_no_helper');
                    $voucher_no = create_receive_voucher_no();
                    $AccouVoucherType_AutoID = 1;
                }
                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $saleDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $invoice_id;
                $accountingMasterTable['Narration'] = 'Sales Order Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['customer_id'] = $customer_id;
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['for'] = 2;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);

                $all_so_product = array();


                $totalProductCost = 0;
                $sales_price = 0;
                $totalGR_DEBIT =  0;
                $totalGR_CREDIT = 0;
                foreach ($_POST['so_po_product_row_id'] as $key => $value) {
                    $so_product = array();
                    $so_po_product_row_id = $value;
                    $quantity = $_POST['so_po_issue_qty_' . $value];
                    $unit_price = $_POST['unit_price_' . $value];
                    $so_po_product_id = $_POST['so_po_product_id_' . $value];
                    $so_po_product_category_id = $_POST['so_po_product_category_id_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];
                    $sales_price = $sales_price + ($quantity * $unit_price);
                    $query = 'UPDATE so_po_productes
                        SET so_po_approve_qty=so_po_approve_qty+' . $quantity . '
                        WHERE id =' . $so_po_product_row_id;
                    $this->db->query($query);

                    unset($stock);

                    $productCost = $this->Sales_Model->productCostNew($so_po_product_id, $branch_id);


                    $totalProductCost += ($quantity * $productCost);

                    $lastPurchasepriceArray = $this->db->where('product_id', $so_po_product_id)
                        ->where('branch_id', $branch_id)
                        ->order_by('purchase_details_id', "desc")
                        ->limit(1)
                        ->get('purchase_details')
                        ->row();
                    $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;
                    $stock['sales_invoice_id'] = $invoice_id;
                    $stock['customer_id'] = $customer_id;
                    $stock['product_id'] = $so_po_product_id;
                    $stock['is_package '] = 0;
                    $stock['returnable_quantity '] = 0;
                    $stock['return_quentity '] = 0;

                    $stock['customer_due'] = 0;
                    $stock['customer_advance'] = 0;
                    $stock['quantity'] = $quantity;
                    $stock['unit_price'] = $unit_price;
                    $stock['last_purchase_price '] = $lastPurchaseprice;
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $stock['branch_id'] = $branch_id;
                    $stock['property_1'] = $property_1;
                    $stock['property_2'] = $property_2;
                    $stock['property_3'] = $property_3;
                    $stock['property_4'] = $property_4;
                    $stock['property_5'] = $property_5;
                    $sales_details_id = $this->Common_model->insert_data('sales_details', $stock);



                    $stockNewTable['parent_stock_id'] = 0;
                    $stockNewTable['invoice_id'] = $invoice_id;
                    $stockNewTable['form_id'] = 3;
                    $stockNewTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                    $stockNewTable['customer_id'] = $customer_id;
                    $stockNewTable['supplier_id'] = 0;
                    $stockNewTable['branch_id'] = $branch_id;
                    $stockNewTable['invoice_date'] = $saleDate;
                    $stockNewTable['category_id'] = $so_po_product_category_id;
                    $stockNewTable['product_id'] = $so_po_product_id;
                    $stockNewTable['empty_cylinder_id'] = 0;
                    $stockNewTable['is_package'] = 0;
                    $stockNewTable['show_in_invoice'] = 1;
                    $stockNewTable['unit'] = getProductUnit($so_po_product_id);
                    $stockNewTable['type'] = 2;
                    $stockNewTable['quantity'] = $quantity;
                    $stockNewTable['quantity_out'] = $quantity;
                    $stockNewTable['quantity_in'] = 0;
                    $stockNewTable['returnable_quantity'] = 0;
                    $stockNewTable['return_quentity'] = 0;
                    $stockNewTable['due_quentity'] = 0;
                    $stockNewTable['advance_quantity'] = 0;
                    $stockNewTable['price'] = $unit_price;
                    $stockNewTable['price_in'] = 0;
                    $stockNewTable['price_out'] = $unit_price;
                    $stockNewTable['last_purchase_price'] = $lastPurchaseprice;
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


                    $otherProduct['product_id'] = $so_po_product_id;
                    $otherProduct['price'] = ($quantity * $productCost);
                    $otherProduct['quantity'] = ($quantity);
                    $otherProduct['unit_price'] = ($unit_price);
                    $otherProduct['stock_id'] = $stock_id;

                    $allOtherProductArray[] = $otherProduct;
                    $all_so_product[] = $so_product;


                }


                if (!empty($allOtherProductArray)) {
                    /*Inventory stock Refill=*/
                    foreach ($allOtherProductArray as $keyOtherProduct => $valueOtherProduct) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueOtherProduct['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        /*Inventory stock Refill=>95*/
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableOtherProductCost['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableOtherProductCost['TypeID'] = $this->TypeCR;//'2';//Cr
                        $accountingDetailsTableOtherProductCost['CHILD_ID'] = $ac_account_ledger_coa_info->id;// $this->config->item("Inventory_Stock");//'20';
                        $accountingDetailsTableOtherProductCost['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableOtherProductCost['GR_CREDIT'] = $valueOtherProduct['price'];// $OtherProductCost;
                        $accountingDetailsTableOtherProductCost['Reference'] = 'Inventory stock Out Of ' . $ac_account_ledger_coa_info->parent_name . ' (' . $valueOtherProduct['quantity'] . '*' . $valueOtherProduct['unit_price'] . ')';
                        $accountingDetailsTableOtherProductCost['IsActive'] = 1;
                        $accountingDetailsTableOtherProductCost['Created_By'] = $this->admin_id;
                        $accountingDetailsTableOtherProductCost['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableOtherProductCost['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableOtherProductCost['date'] = $saleDate;
                        //$finalDetailsArray[] = $accountingDetailsTableOtherProductCost;

                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableOtherProductCost);
                        $accountingDetailsTableOtherProductCost=array();

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueOtherProduct['stock_id']);

                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueOtherProduct['price'];
                    }
                }
                $condtion = array(
                    'related_id' => $customer_id,
                    'related_id_for' => 3,
                    'is_active' => "Y",
                );
                $CustomerReceivable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                /*Customer Receivable   =>>33*/
                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivable->id;//$this->config->item("Customer_Receivable");
                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = $this->input->post('netTotal');
                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer Receivable';
                $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('netTotal');
                $totalGR_CREDIT = $totalGR_CREDIT + 0;

                /*Cost of Goods Product =>>45*/
                $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCostofGoodsProduct['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = $this->config->item("Cost_of_Goods_Product");//'45';
                $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = $totalProductCost;
                $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Cost of Goods Product';
                $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
                $accountingDetailsTableCostofGoodsProduct['Created_By'] = $this->admin_id;
                $accountingDetailsTableCostofGoodsProduct['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCostofGoodsProduct['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;

                $totalGR_DEBIT = $totalGR_DEBIT + $totalProductCost;
                $totalGR_CREDIT = $totalGR_CREDIT + 0;
                $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableSales['TypeID'] = $this->TypeCR;//'2';//Cr
                $accountingDetailsTableSales['CHILD_ID'] = $this->config->item("Sales");
                $accountingDetailsTableSales['GR_DEBIT'] = '0.00';
                $accountingDetailsTableSales['GR_CREDIT'] = $sales_price;
                $accountingDetailsTableSales['Reference'] = 'Sales Amount';
                $accountingDetailsTableSales['IsActive'] = 1;
                $accountingDetailsTableSales['Created_By'] = $this->admin_id;
                $accountingDetailsTableSales['Created_Date'] = $this->timestamp;
                $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
                $accountingDetailsTableSales['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableSales;

                $totalGR_DEBIT = $totalGR_DEBIT + 0;
                $totalGR_CREDIT = $totalGR_CREDIT + $sales_price;

                if ($this->input->post('loaderAmount') > 0) {
                    $accountingDetailsTableloaderAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableloaderAmount['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableloaderAmount['CHILD_ID'] = $this->config->item("LoaderPayableSales");//'47';
                    $accountingDetailsTableloaderAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableloaderAmount['GR_CREDIT'] = $this->input->post('loaderAmount');
                    $accountingDetailsTableloaderAmount['Reference'] = 'Loading and wages';
                    $accountingDetailsTableloaderAmount['IsActive'] = 1;
                    $accountingDetailsTableloaderAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableloaderAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableloaderAmount['BranchAutoId'] = $branch_id;;
                    $accountingDetailsTableloaderAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableloaderAmount;
                    $accountingDetailsTableloaderAmount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('loaderAmount');
                }
                /*Loading and wages*/
                /*Transportation*/
                if ($this->input->post('transportationAmount') > 0) {
                    $accountingDetailsTableTransportationAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableTransportationAmount['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableTransportationAmount['CHILD_ID'] = $this->config->item("TransportationPayableSales");//'42';
                    $accountingDetailsTableTransportationAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableTransportationAmount['GR_CREDIT'] = $this->input->post('transportationAmount');
                    $accountingDetailsTableTransportationAmount['Reference'] = 'Transportation Payable Sales';
                    $accountingDetailsTableTransportationAmount['IsActive'] = 1;
                    $accountingDetailsTableTransportationAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableTransportationAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableTransportationAmount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableTransportationAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableTransportationAmount;
                    $accountingDetailsTableTransportationAmount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('transportationAmount');
                }


                $totalGR_DEBIT = number_format($totalGR_DEBIT, 2, '.', '');

                $totalGR_CREDIT = number_format($totalGR_CREDIT, 2, '.', '');


                if ($totalGR_DEBIT != $totalGR_CREDIT) {
                    $this->db->trans_rollback();
                    $msg = 'Sales Invoice ' . ' ' . $this->config->item("save_error_message") . ' There is something wrong please try again .contact with Customer Care';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/salesLpgInvoice_add'));
                }

                log_message('error', 'finalDetailsArray   ' . print_r($finalDetailsArray, true));
                if (!empty($finalDetailsArray)) {

                    //$this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/salesInvoiceAgainstSO/'));
                } else {
                    $msg = 'Sales Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/salesInvoiceAgainstSO/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }
        $data = array();

        $data['title'] = get_phrase('sales_invoice_against_SDC');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('sales_invoice_against_SDC');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('sales_invoice_against_SDC_list');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['voucherID'] = create_sales_invoice_no();
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition);
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);

        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_invoice_against_sdc', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }

    function getCustomerdetails_and_so_details()
    {

        $sup_cus_id = $this->input->post('customerId');
        $branch_id = $this->input->post('branch_id');
        $type = $this->input->post('type');
        if ($sup_cus_id != null) {
            $this->db->select("id ,so_po_no");
            $this->db->from("so_po_info");
            if($type==1){
                $this->db->where('supplier_id', $sup_cus_id);
                $this->db->where('form_id', 8);
            }else{
                $this->db->where('customer_id', $sup_cus_id);
                $this->db->where('form_id', 7);
            }



            $this->db->group_start();
            $this->db->where('status', 0);
            $this->db->or_where('status', 2);
            $this->db->group_end();
            $this->db->where('is_active', "Y");
            $this->db->where('is_delete', "N");
            $customer_so = $this->db->get()->result();

            $add = '';
            if (!empty($customer_so)):
                $add .= "<option value=''></option>";
                foreach ($customer_so as $key => $value):
                    $add .= "<option   value='" . $value->id . "' >$value->so_po_no   </option>";
                endforeach;
                echo $add;
                DIE;
            else:

                if($type==1){
                    echo "<option value='' selected disabled>Purchase Order Not Available</option>";
                }else{
                    echo "<option value='' selected disabled>Sales Order Not Available</option>";

                }


                DIE;
            endif;

        }
    }

    function getSoInfo($so_id = null)
    {
        if (!empty($so_id)):
            $so_id = $so_id;
        else:
            $so_id = $this->input->post('so_id');
        endif;
        $so_info = $data['so_po_info'] = $this->Common_model->get_single_data_by_single_column('so_po_info', 'id', $so_id);
        echo json_encode($so_info);
    }


    function get_so_po_products()
    {
        $json = array();


//add custom filter here

        if (!empty($this->input->post('so_id'))) {
            $this->db->where('so.id', $this->input->post('so_id'));
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
	so.delivery_date,
	so.so_po_date,
	so.refference_person_id,
	sop.id as so_po_product_row_id,
	sop.product_id,
	p.productName,
	p.product_code,
	pc.category_id  AS category_id ,
	pc.title AS productcategory,
	b.brandName,
	sop.so_po_qty,
	sop.so_po_unit_price,
	sop.so_po_approve_qty,
	
	sop.property_1,
	sop.property_2,
	sop.property_3,
	sop.property_4,
	sop.property_5");

        $this->db->from('so_po_productes sop');
        $this->db->join('so_po_info so ', 'so.id = sop.so_po_id', 'left');
        $this->db->join('product p ', 'p.product_id = sop.product_id ', 'left');
        $this->db->join('productcategory pc   ', 'pc.category_id = p.category_id', 'left');
        $this->db->join('brand b  ', 'b.brandId = p.brand_id', 'left');

        $i = 0;


        //$list = $this->ReturnDamageModel->get_sales_invoice_details_for_return();


        $query = $this->db->get();
        $list = $query->result_array();
        log_message("error", 'product so' . print_r($list, true));

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
            if ($property_1 != 'dont_have_this_property') {

                $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_1_" . $element['so_po_product_row_id'] . "' name='property_1_" . $element['so_po_product_row_id'] . "' value='" . $element['property_1'] . "' placeholder='" . $element['property_1'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            }
            if ($property_2 != 'dont_have_this_property') {

                $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_2_" . $element['so_po_product_row_id'] . "' name='property_2_" . $element['so_po_product_row_id'] . "' value='" . $element['property_2'] . "' placeholder='" . $element['property_2'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            }
            if ($property_3 != 'dont_have_this_property') {

                $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_3_" . $element['so_po_product_row_id'] . "' name='property_3_" . $element['so_po_product_row_id'] . "' value='" . $element['property_3'] . "' placeholder='" . $element['property_3'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            }
            if ($property_4 != 'dont_have_this_property') {

                $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_4_" . $element['so_po_product_row_id'] . "' name='property_4_" . $element['so_po_product_row_id'] . "' value='" . $element['property_4'] . "' placeholder='" . $element['property_4'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            }
            if ($property_5 != 'dont_have_this_property') {

                $row[] = "<input type='text' class='form-control  ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='property_5_" . $element['so_po_product_row_id'] . "' name='property_5_" . $element['so_po_product_row_id'] . "' value='" . $element['property_5'] . "' placeholder='" . $element['property_5'] . "'  readonly='true'  onclick='this.select();' style='text-align: center;'/>";

            }

            $row[] = "<input type='hidden' class='' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_po_product_id_" . $element['so_po_product_row_id'] . "' name='so_po_product_id_" . $element['so_po_product_row_id'] . "' value='" . $element['product_id'] . "'  />
                      <input type='hidden' class='' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_po_product_category_id_" . $element['so_po_product_row_id'] . "' name='so_po_product_category_id_" . $element['so_po_product_row_id'] . "' value='" . $element['category_id'] . "'  />
                    <input type='text' class='form-control so_po_qty decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_quantity_" . $element['so_po_product_row_id'] . "' name='so_quantity[" . $element['so_po_product_row_id'] . "]' value='" . $quantity . "' placeholder='" . $element['quantity'] . "' attr-actual-quantity='" . $element['quantity'] . "' readonly='true'  onclick='this.select();' style='text-align: center;'/>";
            $row[] = "<input type='text' class='form-control so_po_issue_qty decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "'  id='so_po_issue_qty_" . $element['so_po_product_row_id'] . "' name='so_po_issue_qty_" . $element['so_po_product_row_id'] . "' value='" . $quantity . "' placeholder='" . $element['quantity'] . "' attr-actual-quantity='" . $element['quantity'] . "' readonly='true'  onclick='this.select();' style='text-align: right;'/>";
            $row[] = "<input type='text' class='form-control unit_price decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "' id='unit_price_" . $element['so_po_product_row_id'] . "' name='unit_price_" . $element['so_po_product_row_id'] . "' value='" . $element['so_po_unit_price'] . "' placeholder='" . $element['so_po_unit_price'] . "' arrt-actual-unit-price='" . $element['so_po_unit_price'] . "' readonly='true' onclick='this.select();' style='text-align: right;'/>";;
            $row[] = "<input type='text' class='form-control tt_price decimal' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "' id='tt_price_" . $element['so_po_product_row_id'] . "' name='tt_price[" . $element['so_po_product_row_id'] . "]' value='" . $quantity * $element['so_po_unit_price'] . "' placeholder='" . $element['quantity'] * $element['so_po_unit_price'] . "' readonly='true' onclick='this.select();' style='text-align: right;'/>";
            $row[] = "<input type='text' class='form-control one_line_naration ' attr-so-po-product-row-id='" . $element['so_po_product_row_id'] . "' id='one_line_naration_" . $element['so_po_product_row_id'] . "' name='one_line_naration[" . $element['so_po_product_row_id'] . "]' value='' placeholder='Narration' readonly='true' onclick='this.select();' style='text-align: center;'/>";

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

    public function so_po_delete()
    {
        $invoiceId = $this->input->post('id');
        $redirect_page = $this->input->post('redirect_page');
        $voucherType = $this->input->post('voucherType');

        if ($voucherType ==7) {
            $name = "Sales Order";
        } else {
            $name = "Purchase Order";
        }

//        $customer_money_revcive = $this->_check_the_invoice_have_bill_to_bill_transction($invoiceId);
//        if(!empty($customer_money_revcive)){
//            $msg = 'Sales Invoice Cannot Delete .This Sales Invoice have accounting transaction  ' ;
//            $this->session->set_flashdata('error', $msg);
//            redirect(site_url($this->project . '/salesInvoiceLpg'));
//        }

        $this->db->trans_begin();
        $so_po_info = array();
        $so_po_info['is_active'] = 'N';
        $so_po_info['is_delete'] = 'Y';
        $so_po_info_update_condition = array(
            'id' => $invoiceId,
            'form_id' => $voucherType,
        );
        $this->Common_model->save_and_check('so_po_info', $so_po_info, $so_po_info_update_condition);


        $this->_save_data_to_so_po_history_table($invoice_id = $invoiceId, $voucherType, $action = 'delete', $redirect_page, $name);


        $DeleteCondition_so_po_productes = array(
            'so_po_id' => $invoiceId
        );
        $this->Common_model->delete_data_with_condition('so_po_productes', $DeleteCondition_so_po_productes);


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = $name . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            echo 2;
        } else {
            $this->db->trans_commit();
            $msg = $name . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
            echo 1;
        }

    }


    public function _save_data_to_so_po_history_table($invoice_id, $voucherType, $action = array('edit', 'delete'), $redirect_page, $name)
    {
        // echo "<pre>";
        // print_r($invoice_id);
        // exit();
        $so_po_info_old_condition = array(
            'id' => $invoice_id,
            'form_id' => $voucherType,
        );
        $so_po_info_array = $this->Common_model->get_data_list_by_many_columns_array('so_po_info', $so_po_info_old_condition);


        if ($action == 'edit') {
            $so_po_info_array[0]['is_active'] = 'Y';
            $so_po_info_array[0]['is_delete'] = 'N';
            $so_po_info_array[0]['update_by'] = $this->admin_id;
            $so_po_info_array[0]['update_date'] = $this->timestamp;;
            $so_po_info_array[0]['delete_by'] = '';
            $so_po_info_array[0]['delete_date'] = NULL;
        } elseif ($action == 'delete') {
            $so_po_info_array[0]['is_active'] = 'N';
            $so_po_info_array[0]['is_delete'] = 'Y';
            $so_po_info_array[0]['update_by'] = "";
            $so_po_info_array[0]['update_date'] = NULL;
            $so_po_info_array[0]['delete_by'] = $this->admin_id;
            $so_po_info_array[0]['delete_date'] = $this->timestamp;
        }
        $so_po_info_audit_id = $this->Common_model->insert_data('so_po_info_audit', $so_po_info_array[0]);


        $so_po_productes_array = array();
        $so_po_productes_old_condition = array(
            'so_po_id' => $invoice_id,
        );

        $so_po_productes_old_array = $this->Common_model->get_data_list_by_many_columns_array('so_po_productes', $so_po_productes_old_condition);

        foreach ($so_po_productes_old_array as $key => $csm) {
            if ($action == 'edit') {
                $so_po_productes_old_array[$key]['so_po_audit_id'] = $so_po_info_audit_id;
                $so_po_productes_old_array[$key]['is_active'] = 'Y';
                $so_po_productes_old_array[$key]['is_delete'] = 'N';
                $so_po_productes_old_array[$key]['update_by'] = $this->admin_id;
                $so_po_productes_old_array[$key]['update_date'] = $this->timestamp;;
                $so_po_productes_old_array[$key]['delete_by'] = '';
                $so_po_productes_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $so_po_productes_old_array[$key]['so_po_audit_id'] = $so_po_info_audit_id;
                $so_po_productes_old_array[$key]['is_active'] = 'N';
                $so_po_productes_old_array[$key]['is_delete'] = 'Y';
                $so_po_productes_old_array[$key]['update_by'] = "";
                $so_po_productes_old_array[$key]['update_date'] = NULL;
                $so_po_productes_old_array[$key]['delete_by'] = $this->admin_id;
                $so_po_productes_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('so_po_productes_audit', $so_po_productes_old_array);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = $name . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/' . $redirect_page . '/' . $invoice_id));
        }
    }

}