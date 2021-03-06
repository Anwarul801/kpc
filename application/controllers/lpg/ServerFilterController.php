<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 6/30/2019
 * Time: 10:38 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class ServerFilterController extends CI_Controller
{

    private $timestamp;
    public $admin_id;
    public $dist_id;

    public $project;

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('ServerFilterModel', 'Filter_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function purchasesReturnList()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('return_info',
            array("DATE_FORMAT(return_info.return_date, '%b %e, %Y') ", 'return_info.return_invoice_no', 'customer.customerID', 'customer.customerName', 'return_info.narration'),
            array("DATE_FORMAT(return_info.return_date, '%b %e, %Y') ", 'return_info.return_invoice_no', 'customer.customerID', 'customer.customerName', 'return_info.narration'),
            array("DATE_FORMAT(return_info.return_date, '%b %e, %Y') ", 'return_info.return_invoice_no', 'customer.customerID', 'customer.customerName', 'return_info.narration'), $this->dist_id);
        $list = $this->Filter_Model->get_purreturn_datatables();
        log_message('error', 'ReturnDamageModel ' . print_r($this->db->last_query(), true));

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $sale->invoice_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/viewPurchaseReturn/' . $sale->id) . '">' . $sale->return_invoice_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $sale->supID . ' [ ' . $sale->supName . ' ] ' . '</a>';


            // $row[] = number_format((float)$sale->amount, 2, '.', ',');

            if ($property_1 != 'dont_have_this_property') {
                $row[] = $sale->property_1;
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $sale->property_2;
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $sale->property_3;
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $sale->property_4;
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $sale->property_5;
            }


            $row[] = $sale->narration;
            if ($this->input->post('invoice_type') == 6) {
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewPurchaseReturn/' . $sale->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
     ';
            } else {
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewPurchaseReturn/' . $sale->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
     ';
            }
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/

            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_purchases(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_purchases(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function brandList()
    {
        $this->Filter_Model->filterData('brand', array('brandName'), array('brandName'), array('brandName'), $this->dist_id);
        $list = $this->Filter_Model->get_productBrand_datatables();
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2116);
        $edit  = check_parmission_by_user_role(2117);
        $delete  = check_parmission_by_user_role(2118);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $brands) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $brands->brandName;
            $row[] = $brands->name;
            if ($brands->dist_id != 1):
                if($edit == 0 && $delete == 0){
                    $row[] = '';
                } elseif($edit == 0){
                    $row[] = '<a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteBrand(' . $brands->brandId . ')">
                    <i class="fa fa-trash-o bigger-130"></i></a>';
                }
                elseif($delete == 0){
                    $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/brandEdit/' . $brands->brandId) . '">
                    <i class="fa fa-pencil bigger-130"></i></a>';
                }
    
                else{
                    $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/brandEdit/' . $brands->brandId) . '">
                <i class="fa fa-pencil bigger-130"></i></a>
                <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteBrand(' . $brands->brandId . ')">
                <i class="fa fa-trash-o bigger-130"></i></a>';
                }

                
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all(),
            "recordsFiltered" => $this->Filter_Model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function productCatList()
    {
        $this->Filter_Model->filterData('productcategory', array('title'), array('title'), array('title'), $this->dist_id);
        $list = $this->Filter_Model->get_productCat_datatables();
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2116);
        $edit  = check_parmission_by_user_role(2117);
        $delete  = check_parmission_by_user_role(2118);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $productCat) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $productCat->title;
            $row[] = $productCat->name;

            if ($productCat->category_id != 1 && $productCat->category_id != 2):
                if($edit == 0 && $delete == 0){
                    $row[] = '';
                } elseif($edit == 0){
                    $row[] = '
                    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProductCategory(' . $productCat->category_id . ')">
                    <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
                }
                elseif($delete == 0){
                    $row[] = '<a class="btn btn-icon-only red" href="' . site_url($this->project . '/updateProductCat/' . $productCat->category_id) . '">
                    <i class="fa fa-pencil"></i></a>
                    ';
                }
    
                else{
                    $row[] = '<a class="btn btn-icon-only red" href="' . site_url($this->project . '/updateProductCat/' . $productCat->category_id) . '">
                    <i class="fa fa-pencil"></i></a>
                    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProductCategory(' . $productCat->category_id . ')">
                    <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
                }

                
     
            else:
                $row[] = '';
            endif;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_productCat(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_productCat(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function unitList()
    {
        $this->Filter_Model->filterData('unit', array('unitTtile', 'code'), array('unitTtile', 'code'), array('unitTtile', 'code'), $this->dist_id);
        $list = $this->Filter_Model->get_datatables_unit();
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2116);
        $edit  = check_parmission_by_user_role(2117);
        $delete  = check_parmission_by_user_role(2118);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $unit) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $unit->unitTtile;
            $row[] = $unit->code;
            $row[] = $unit->unit_custom_field_1." ".get_settings('unit_custom_field_1')." ".$unit->unit_custom_field_2." ".get_settings('unit_custom_field_2');
            if ($unit->dist_id != 1):
                if($edit == 0 && $delete == 0){
                    $row[] = '';
                } elseif($edit == 0){
                    $row[] = '
                      <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteUnit(' . $unit->unit_id . ')">
                      <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
                }
                elseif($delete == 0){
                    $row[] = '<a class="btn btn-icon-only red" href="' . site_url($this->project . '/unitEdit/' . $unit->unit_id) . '">
                     <i class="fa fa-pencil"></i></a>
                     ';
                }
    
                else{
                    $row[] = '<a class="btn btn-icon-only red" href="' . site_url($this->project . '/unitEdit/' . $unit->unit_id) . '">
                      <i class="fa fa-pencil"></i></a>
                      <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteUnit(' . $unit->unit_id . ')">
                      <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
                }
                
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_unit(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_unit(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function supplierList()
    {
        $this->Filter_Model->filterData('supplier', array('supID', 'supName', 'supEmail', 'supPhone', 'supAddress', 'status'), array('supID', 'supName', 'supEmail', 'supPhone', 'supAddress', 'status'), array('supID', 'supName', 'supEmail', 'supPhone', 'supAddress', 'status'), $this->dist_id);
        $list = $this->Filter_Model->get_sup_datatables();
        $this->load->helper('site_helper');
        $edit  = check_parmission_by_user_role(2117);
        $delete  = check_parmission_by_user_role(2118);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $supplier) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $supplier->supID;
            $row[] = $supplier->supName;
            $row[] = $supplier->supEmail;
            $row[] = $supplier->supPhone;
            $row[] = $supplier->supAddress;
            $row[] = $supplier->name;
            if ($supplier->status == 1):
                $row[] = '<a href="javascript:void(0)" onclick="supplierStatusChange(' . $supplier->sup_id . ',2)" class="label label-danger arrowed">
                    <i class="fa fa-ban"></i>
                    Inactive</a>';
            else:
                $row[] = '<a href="javascript:void(0)" onclick="supplierStatusChange(' . $supplier->sup_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
            endif;
            if($edit == 0 && $delete == 0){
                $row[] = '';
            } elseif($edit == 0){
                $row[] = '
                <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteSupplier(' . $supplier->sup_id . ',2)">
                                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                            </a>';
            }
            elseif($delete == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/supplierUpdate/' . $supplier->sup_id) . '">
                <i class="fa fa-pencil"></i></a>
               ';
            }

            else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/supplierUpdate/' . $supplier->sup_id) . '">
                <i class="fa fa-pencil"></i></a>
                <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteSupplier(' . $supplier->sup_id . ',2)">
                                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                            </a>';
            }
           
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all(),
            "recordsFiltered" => $this->Filter_Model->count_filtered(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function customerList()
    {
        $this->Filter_Model->filterData('customer', array('customerID', 'customerName', 'customerPhone', 'customerEmail', 'customerAddress'), array('customerID', 'customerName', 'customerPhone', 'customerEmail', 'customerAddress',), array('customerID', 'customerName', 'customerPhone', 'customerEmail', 'customerAddress',), $this->dist_id);
        $list = $this->Filter_Model->get_cus_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $customer) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $this->Common_model->tableRow('customertype', 'type_id', $customer->customerType)->typeTitle;

            $row[] = $customer->customerID;
            $row[] = $customer->customerName;
            $row[] = $customer->customerPhone;
            $row[] = $customer->customerEmail;
            $row[] = $customer->customerAddress;
            $row[] = $customer->name;
            $row[] = $this->Common_model->tableRow('root_info', 'root_id', $customer->root_id)->name;

            if ($customer->status == 1):
                $row[] = '<a href="javascript:void(0)" onclick="customerStatusChange(' . $customer->customer_id . ',2)" class="label label-danger arrowed">
                    <i class="fa fa-ban"></i>
                    Inactive</a>';
            else:
                $row[] = '<a href="javascript:void(0)" onclick="customerStatusChange(' . $customer->customer_id . ',1)" class="label label-success arrowed">
                    <i class="fa fa-check"></i>
                    Active
                </a>';
            endif;
            $add  = check_parmission_by_user_role(2110);
            $edit  = check_parmission_by_user_role(2111);
            $delete  = check_parmission_by_user_role(2112);
            if($edit == 0 && $delete == 0){
                $row[] = '';
            } elseif($edit == 0){
                $row[] = '<a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteCustomer(' . $customer->customer_id . ',1)">
                                                                <i class="fa fa-trash-o bigger-130"></i>
                                                            </a>';
            }
            elseif($delete == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/editCustomer/' . $customer->customer_id) . '">
    <i class="fa fa-pencil"></i></a>
    ';
            }

            else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/editCustomer/' . $customer->customer_id) . '">
                <i class="fa fa-pencil"></i></a>
                <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteCustomer(' . $customer->customer_id . ',1)">
                                                                <i class="fa fa-trash-o bigger-130"></i>
                                                            </a>';
            }
            
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_cus(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cus(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }


    public function productList()
    {
        $this->Filter_Model->filterData('product', array('productcategory.title', 'brand.brandName', 'product.product_code', 'product.productName', 'product.purchases_price', 'product.retailPrice', 'product.salesPrice', 'product.status'), array('productcategory.title', 'brand.brandName', 'product.product_code', 'product.productName', 'product.purchases_price', 'product.retailPrice', 'product.salesPrice', 'product.status'), array('productcategory.title', 'brand.brandName', 'product.product_code', 'product.productName', 'product.purchases_price', 'product.retailPrice', 'product.salesPrice', 'product.status'), $this->dist_id);
        $list = $this->Filter_Model->get_product_datatables();
        
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2116);
        $edit  = check_parmission_by_user_role(2117);
        $delete  = check_parmission_by_user_role(2118);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $products->title;
            $row[] = $products->brandName;
            $row[] = $products->product_code;
            $row[] = $products->productName . ' ' . $products->unitTtile;
            $row[] = $products->purchases_price;
            $row[] = $products->retailPrice;
            $row[] = $products->salesPrice;
            $row[] = $products->name;
            if ($products->dist_id != 1):
                if ($products->status == 1):
                    $row[] = '<a href="javascript:void(0)" onclick="productStatusChange(' . $products->product_id . ',2)" class="label label-danger arrowed">
                    <i class="fa fa-ban"></i>
                    Inactive</a>';
                else:
                    $row[] = '<a href="javascript:void(0)" onclick="productStatusChange(' . $products->product_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
                endif;
            else:
                $row[] = '';
            endif;
            if ($products->dist_id != 1):
                if($edit == 0 && $delete == 0){
                    $row[] = '';
                } elseif($edit == 0){
                    $row[] = '<a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteProduct(' . $products->product_id . ',2)">
                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                    </a>';
                }
                elseif($delete == 0){
                    $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/updateProduct/' . $products->product_id) . '">
                    <i class="fa fa-pencil "></i></a>';
                }
    
                else{
                    $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/updateProduct/' . $products->product_id) . '">
                    <i class="fa fa-pencil "></i></a>
                    <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteProduct(' . $products->product_id . ',2)">
                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                    </a>';
                }
                
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_product(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_product(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function salesList()
    {

        $this->Filter_Model->filterData('sales_invoice_info',
            array('sales_invoice_info.invoice_date', 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'),
            array('sales_invoice_info.invoice_date', 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'),
            array('sales_invoice_info.invoice_date', 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'), $this->dist_id);
        $list = $this->Filter_Model->get_sales_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {
            if ($sale->payment_type == 4) {
                $payment_type = get_phrase('Cash');
            } else if ($sale->payment_type == 2) {
                $payment_type = get_phrase('Credit');
            } else {
                $payment_type = get_phrase('Cheque_DD_PO');
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($sale->invoice_date));
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/salesInvoice_view/' . $sale->sales_invoice_id) . '">' . $sale->invoice_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';
            $row[] = $payment_type;
            $row[] = $sale->narration;
            $row[] = number_format((float)$sale->invoice_amount, 2, '.', ',');
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/salesInvoice_view/' . $sale->sales_invoice_id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
    <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">
    <i class="fa fa-edit"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_sales(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_sales(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function salesListLpg()
    {

        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2110);
        $edit  = check_parmission_by_user_role(2111);
        $delete  = check_parmission_by_user_role(2112);

        log_message('error', 'sales delete ' . print_r($delete, true));
        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('sales_invoice_info',
            array("DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') ", 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'),
            array("DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') ", 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'),
            array("DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') ", 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'), $this->dist_id);
        $list = $this->Filter_Model->get_sales_datatables();
        log_message('error', 'ReturnDamageModel ' . print_r($this->db->last_query(), true));
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {
            if ($sale->payment_type == 4) {
                $payment_type = get_phrase('Cash');
            } else if ($sale->payment_type == 2) {
                $payment_type = get_phrase('Credit');
            } else {
                $payment_type = get_phrase('Cheque_DD_PO');
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $sale->invoice_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/viewLpgCylinder/' . $sale->sales_invoice_id) . '">' . $sale->invoice_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';
            $row[] = $payment_type;

            $row[] = number_format((float)$sale->invoice_amount, 2, '.', ',');

            if ($property_1 != 'dont_have_this_property') {
                $row[] = $sale->property_1;
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $sale->property_2;
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $sale->property_3;
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $sale->property_4;
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $sale->property_5;
            }


            $row[] = $sale->narration;
            $row[] = $sale->name;
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            if($edit == 0 && $delete == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewLpgCylinder/' . $sale->sales_invoice_id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>
                  
               ';
            } elseif($edit == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewLpgCylinder/' . $sale->sales_invoice_id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>
                <button class="btn btn-icon-only red " data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="deletesalesinvoice_' . $sale->sales_invoice_id . '" href="javascript:void(0)" onclick="deletesalesinvoice(2,' . $sale->sales_invoice_id . ')">
                            <i class="fa fa-trash-o bigger-130"></i></button>';
            }
            elseif($delete == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewLpgCylinder/' . $sale->sales_invoice_id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>
                <a class="btn btn-icon-only green" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '"><i class="fa fa-edit"></i></a>';
            }

            else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewLpgCylinder/' . $sale->sales_invoice_id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>
                  
                <a class="btn btn-icon-only green" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '"><i class="fa fa-edit"></i></a>
            
                <button class="btn btn-icon-only red " data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="deletesalesinvoice_' . $sale->sales_invoice_id . '" href="javascript:void(0)" onclick="deletesalesinvoice(2,' . $sale->sales_invoice_id . ')">
                            <i class="fa fa-trash-o bigger-130"></i></button>';
            }
            
        
            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_sales(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_sales(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }


    public function salesReturnList()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('return_info',
            array("DATE_FORMAT(return_info.return_date, '%b %e, %Y') ", 'return_info.return_invoice_no', 'customer.customerID', 'customer.customerName', 'return_info.narration'),
            array("DATE_FORMAT(return_info.return_date, '%b %e, %Y') ", 'return_info.return_invoice_no', 'customer.customerID', 'customer.customerName', 'return_info.narration'),
            array("DATE_FORMAT(return_info.return_date, '%b %e, %Y') ", 'return_info.return_invoice_no', 'customer.customerID', 'customer.customerName', 'return_info.narration'), $this->dist_id);
        $list = $this->Filter_Model->get_return_datatables();
        log_message('error', 'ReturnDamageModel ' . print_r($this->db->last_query(), true));
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $sale->invoice_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/salesReturnView/' . $sale->id) . '">' . $sale->return_invoice_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';


            // $row[] = number_format((float)$sale->amount, 2, '.', ',');

            if ($property_1 != 'dont_have_this_property') {
                $row[] = $sale->property_1;
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $sale->property_2;
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $sale->property_3;
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $sale->property_4;
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $sale->property_5;
            }


            $row[] = $sale->narration;
            $row[] = $sale->name;
            if ($this->input->post('invoice_type') == 5) {
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewSalesReturn/' . $sale->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
     ';
            } else {
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewSalesReturn/' . $sale->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
     ';
            }
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/

            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_sales(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_sales(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function warranty_claim_voucher_list()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('sales_invoice_info',
            array("DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') ", 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'),
            array("DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') ", 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'),
            array("DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') ", 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount', 'sales_invoice_info.payment_type'), $this->dist_id);
        $list = $this->Filter_Model->get_warranty_claim_voucher_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {
            if ($sale->payment_type == 4) {
                $payment_type = get_phrase('Cash');
            } else if ($sale->payment_type == 2) {
                $payment_type = get_phrase('Credit');
            } else {
                $payment_type = get_phrase('Cheque_DD_PO');
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $sale->invoice_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/viewLpgCylinder/' . $sale->sales_invoice_id) . '">' . $sale->invoice_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';
            $row[] = $payment_type;

            $row[] = number_format((float)$sale->invoice_amount, 2, '.', ',');

            if ($property_1 != 'dont_have_this_property') {
                $row[] = $sale->property_1;
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $sale->property_2;
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $sale->property_3;
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $sale->property_4;
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $sale->property_5;
            }


            $row[] = $sale->narration;
            $row[] = $sale->name;
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/warranty_claim_voucher_view/' . $sale->sales_invoice_id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
    <i class="fa fa-edit"></i></a><a class="btn btn-icon-only red" href="#">
    ';
            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_warranty_claim_voucher_list(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_warranty_claim_voucher_list(),

            /* "recordsTotal" => $this->Filter_Model->count_all_sales(),
             "recordsFiltered" => $this->Filter_Model->count_filtered_sales(),*/
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function customer_due_collection_list()
    {

        $this->Filter_Model->filterData('cus_due_collection_info',
            array('cus_due_collection_info.id', 'cus_due_collection_info.date', 'cus_due_collection_info.cus_due_coll_no', 'customer.customerID', 'customer.customerName', 'cus_due_collection_info.payment_type', 'cus_due_collection_info.total_paid_amount'),
            array('cus_due_collection_info.id', 'cus_due_collection_info.date', 'cus_due_collection_info.cus_due_coll_no', 'customer.customerID', 'customer.customerName', 'cus_due_collection_info.payment_type', 'cus_due_collection_info.total_paid_amount'),
            array('cus_due_collection_info.id', 'cus_due_collection_info.date', 'cus_due_collection_info.cus_due_coll_no', 'customer.customerID', 'customer.customerName', 'cus_due_collection_info.payment_type', 'cus_due_collection_info.total_paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_customer_due_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($sale->date));
            $row[] = '<a title="View Customer Dashboard" href="' . site_url($this->project . '/customerDashboard/' . $sale->customer_id) . '">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';
            $row[] = $sale->cus_due_coll_no;
            $row[] = $sale->payment_type == 2 ? ' Cash ' : ' Chaque ';
            $row[] = $sale->total_paid_amount;


            $row[] = '<a class="green saleEditPermission" href="' . site_url($this->project . '/customer_due_collection_inv/' . $sale->id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => 1,
            "recordsFiltered" => 1,
            "recordsTotal" => $this->Filter_Model->count_all_sales(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cus_due_payment(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function purchasesList()
    {

        $this->Filter_Model->filterData('purchase_invoice_info',
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '),
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '),
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_purchases_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $purchases) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($purchases->invoice_date));
            $row[] = '<a title="view Voucher" href="' . site_url($this->project . '/viewPurchases/' . $purchases->purchase_invoice_id) . '">' . $purchases->invoice_no . '</a></td>';
            /*$row[] = $purchases->name;*/
            $row[] = '<a title="View Supplier Dashboard" href="' . site_url($this->project . '/supplierDashboard/' . $purchases->sup_id) . '">' . $purchases->supID . ' [ ' . $purchases->supName . ' ] ' . '</a>';
            $row[] = $purchases->narration;
            $row[] = number_format((float)$purchases->invoice_amount, 2, '.', ',');
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewPurchases/' . $purchases->purchase_invoice_id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
    <a class="btn red" href="' . site_url($this->project . '/purchases_edit/' . $purchases->purchase_invoice_id) . '">
    <i class="fa fa-pencil bigger-130"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_purchases(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_purchases(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function purchasesCylinderList()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);


        $this->Filter_Model->filterData('purchase_invoice_info',
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '),
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '),
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_purchases_cylinder_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $purchases) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($purchases->invoice_date));
            $row[] = '<a title="view Voucher" href="' . site_url($this->project . '/viewPurchasesCylinder/' . $purchases->purchase_invoice_id) . '">' . $purchases->invoice_no . '</a></td>';
            /*$row[] = $purchases->name;*/
            $row[] = '<a title="View Supplier Dashboard" href="' . site_url($this->project . '/supplierDashboard/' . $purchases->sup_id) . '">' . $purchases->supID . ' [ ' . $purchases->supName . ' ] ' . '</a>';

            $row[] = number_format((float)$purchases->invoice_amount, 2, '.', ',');

            if ($property_1 != 'dont_have_this_property') {
                $row[] = $purchases->property_1;
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $purchases->property_2;
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $purchases->property_3;
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $purchases->property_4;
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $purchases->property_5;
            }
            $row[] = $purchases->narration;
            $row[] = $purchases->name;
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewPurchasesCylinder/' . $purchases->purchase_invoice_id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
    <i class="fa fa-pencil bigger-130"></i></a>
    ';
            /* <a class="btn red" href="' . site_url($this->project . '/purchases_lpg_edit/' . $purchases->purchase_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_cylinder_purchases(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cylinder_purchases(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function warranty_receipt_voucher_list()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);


        $this->Filter_Model->filterData('purchase_invoice_info',
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '),
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '),
            array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_warranty_receipt_voucher_list_query();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $purchases) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($purchases->invoice_date));
            $row[] = '<a title="view Voucher" href="' . site_url($this->project . '/warranty_receipt_voucher_view/' . $purchases->purchase_invoice_id) . '">' . $purchases->invoice_no . '</a></td>';
            /*$row[] = $purchases->name;*/
            $row[] = '<a title="View Supplier Dashboard" href="' . site_url($this->project . '/supplierDashboard/' . $purchases->sup_id) . '">' . $purchases->supID . ' [ ' . $purchases->supName . ' ] ' . '</a>';

            $row[] = number_format((float)$purchases->invoice_amount, 2, '.', ',');

            if ($property_1 != 'dont_have_this_property') {
                $row[] = $purchases->property_1;
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $purchases->property_2;
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $purchases->property_3;
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $purchases->property_4;
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $purchases->property_5;
            }
            $row[] = $purchases->narration;
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/warranty_receipt_voucher_view/' . $purchases->purchase_invoice_id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
    <i class="fa fa-pencil bigger-130"></i></a>
    ';
            /* <a class="btn red" href="' . site_url($this->project . '/purchases_lpg_edit/' . $purchases->purchase_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_warranty_receipt_voucher(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_arranty_receipt_voucher(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function paymentList()
    {
        
        $this->Filter_Model->filterData('ac_accounts_vouchermst',
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ', 'ac_accounts_vouchermst.Narration', 'ac_accounts_vouchermst.BackReferenceInvoiceNo', 'customer.customerName', 'supplier.supName', 'customer.customerID', 'supplier.supID', "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date", 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'branch.branch_name'),
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ', 'ac_accounts_vouchermst.Narration', 'ac_accounts_vouchermst.BackReferenceInvoiceNo', 'customer.customerName', 'supplier.supName', 'customer.customerID', 'supplier.supID', "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') ", 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'branch.branch_name'),
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ', 'ac_accounts_vouchermst.Narration', 'ac_accounts_vouchermst.BackReferenceInvoiceNo', 'customer.customerName', 'supplier.supName', 'customer.customerID', 'supplier.supID', "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date", 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'branch.branch_name'),
            $this->dist_id);
        /*$this->Filter_Model->filterData('ac_accounts_vouchermst',
            array('ac_accounts_vouchermst.Accounts_Voucher_Date',
                'ac_accounts_vouchermst.Accounts_Voucher_No',
                'ac_accounts_vouchermst.Narration'
            ),
            array('ac_accounts_vouchermst.Accounts_Voucher_Date',
                'ac_accounts_vouchermst.Accounts_Voucher_No',
                'ac_accounts_vouchermst.Narration'
            ),
            array('ac_accounts_vouchermst.Accounts_Voucher_Date',
                'ac_accounts_vouchermst.Accounts_Voucher_No',
                'ac_accounts_vouchermst.Narration'
            ), $this->dist_id);*/
            $this->load->helper('site_helper');
            $add  = check_parmission_by_user_role(2113);
            $edit  = check_parmission_by_user_role(2114);
            $delete  = check_parmission_by_user_role(2115);
        $list = $this->Filter_Model->get_payment_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $payment) {
            if ($payment->customerName != '') {
                $name = $payment->customerName . ' [' . $payment->customerID . " ]";
            } else if ($payment->supName != '') {
                $name = $payment->supName . ' [' . $payment->supID . " ]";
            } else {
                $name = $payment->miscellaneous;
            }

            if ($payment->for == 0) {
                if($edit == 0 && $delete == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    ';
                } elseif($edit == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    
                    <a class="btn btn-icon-only red" href="javascript:void(0)" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="delete_' . $payment->Accounts_VoucherMst_AutoID . '"onclick="deleteVoucher(2,' . $payment->Accounts_VoucherMst_AutoID . ')">
                    <i class="fa fa-trash-o bigger-130"></i></a>';
                }
                elseif($delete == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    <a class="btn btn-icon-only red green financeEditPermission" href="' . site_url($this->project . '/paymentVoucherEdit/' . $payment->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                   ';
                }
    
                else{
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">
                <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                <a class="btn btn-icon-only red green financeEditPermission" href="' . site_url($this->project . '/paymentVoucherEdit/' . $payment->Accounts_VoucherMst_AutoID) . '">
                <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                <a class="btn btn-icon-only red" href="javascript:void(0)" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="delete_' . $payment->Accounts_VoucherMst_AutoID . '"onclick="deleteVoucher(2,' . $payment->Accounts_VoucherMst_AutoID . ')">
                <i class="fa fa-trash-o bigger-130"></i></a>';
                }
                
                $Narration = $payment->Narration;
            } else {

                $Narration = $payment->Narration . "  <span style='color: #e7505a;font-style: italic;'>" . $payment->BackReferenceInvoiceNo . "</span>";
                $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">
                <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            }


            $no++;
            $row = array();
            $row[] = $no;
            //$row[] = date('M d, Y', strtotime($payment->Accounts_Voucher_Date));
            $row[] = $payment->Accounts_Voucher_Date;
            $row[] = '<a title="View Payment Voucher" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">' . $payment->Accounts_Voucher_No . '</a></td>';
            $row[] = $payment->Accounts_VoucherType;//$payment->name;
            $row[] = $payment->branch_name;
            $row[] = $name;
            $row[] = $Narration;
            
            $row[] = number_format((float)$payment->amount, 2, '.', ',');
            $row[] = $payment->name;
            //$row[] = number_format((float)$payment->debit, 2, '.', ',');
            //if (!empty($financeEditPermition)) {
            $row[] = $action;
            /*}else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/paymentVoucherView/' . $payment->Accounts_VoucherMst_AutoID) . '">
                <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            }*/
            $data[] = $row;
        }
        /**/
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_payment(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_payment(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }


    public function receiveList_new()
    {

        $this->Filter_Model->filterData('ac_accounts_vouchermst',
            array('branch.branch_name,supplier.supName,ac_accounts_vouchermst.miscellaneous,customer.customerName,ac_accounts_vouchermst.BackReferenceInvoiceNo,ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            array('branch.branch_name,supplier.supName,ac_accounts_vouchermst.miscellaneous,customer.customerName,ac_accounts_vouchermst.BackReferenceInvoiceNo,ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            array('branch.branch_name,supplier.supName,ac_accounts_vouchermst.miscellaneous,customer.customerName,ac_accounts_vouchermst.BackReferenceInvoiceNo,ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            $this->dist_id);


        $list = $this->Filter_Model->get_receive_datatables();

        // log_message('error', 'Hi mamun receiveList ' . print_r($list, true));
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2113);
        $edit  = check_parmission_by_user_role(2114);
        $delete  = check_parmission_by_user_role(2115);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $receive) {
            if ($receive->customerName != '') {
                $name = $receive->customerName . ' [' . $receive->customerID . " ]";
            } else if ($receive->supName != '') {
                $name = $receive->supName . ' [' . $receive->supID . " ]";
            } else {
                $name = $receive->miscellaneous;
            }
            if ($receive->for == 0) {
                if($edit == 0 && $delete == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    ';
                } elseif($edit == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    
                        <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteVoucher(2,' . $receive->Accounts_VoucherMst_AutoID . ')">
                                <i class="fa fa-trash-o bigger-130"></i></a>';
                }
                elseif($delete == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
                         <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                        <a class="btn btn-icon-only red financeEditPermission" href="' . site_url($this->project . '/receiveVoucherEdit/' . $receive->Accounts_VoucherMst_AutoID) . '">
                       <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                       ';
                }
    
                else{
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    <a class="btn btn-icon-only red financeEditPermission" href="' . site_url($this->project . '/receiveVoucherEdit/' . $receive->Accounts_VoucherMst_AutoID) . '">
                        <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                        <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteVoucher(2,' . $receive->Accounts_VoucherMst_AutoID . ')">
                                <i class="fa fa-trash-o bigger-130"></i></a>';
                }
               
                $Narration = $receive->Narration;
            } else {

                $Narration = $receive->Narration . "  <span style='color: #e7505a;font-style: italic;'>" . $receive->BackReferenceInvoiceNo . "</span>";
                $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
                <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a title="View Payment Voucher" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->AccouVoucherType_AutoID) . '">' . $receive->Accounts_Voucher_No . '</a></td>';
            $row[] = date('M d, Y', strtotime($receive->Accounts_Voucher_Date));
            $row[] = $receive->Accounts_VoucherType;//$payment->name;
            $row[] = $receive->branch_name;
            $row[] = $name;
            $row[] = $Narration;
            $row[] = number_format((float)$receive->amount, 2, '.', ',');
            $row[] = $action;
            $data[] = $row;
        }

        /* */

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_receive(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_receive(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }


    public function receiveList()
    {

        $this->Filter_Model->filterData('ac_accounts_vouchermst',
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ', 'ac_accounts_vouchermst.Narration', 'ac_accounts_vouchermst.BackReferenceInvoiceNo', 'customer.customerName', 'supplier.supName', 'customer.customerID', 'supplier.supID', "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date", 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'branch.branch_name'),
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ', 'ac_accounts_vouchermst.Narration', 'ac_accounts_vouchermst.BackReferenceInvoiceNo', 'customer.customerName', 'supplier.supName', 'customer.customerID', 'supplier.supID', "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') ", 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'branch.branch_name'),
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ', 'ac_accounts_vouchermst.Narration', 'ac_accounts_vouchermst.BackReferenceInvoiceNo', 'customer.customerName', 'supplier.supName', 'customer.customerID', 'supplier.supID', "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date", 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'branch.branch_name'),
            $this->dist_id);


        $list = $this->Filter_Model->get_receive_datatables();

        // log_message('error', 'Hi mamun receiveList ' . print_r($list, true));


        $data = array();
        $no = $_POST['start'];
        foreach ($list as $receive) {
            if ($receive->customerName != '') {
                $name = $receive->customerName . ' [' . $receive->customerID . " ]";
            } else if ($receive->supName != '') {
                $name = $receive->supName . ' [' . $receive->supID . " ]";
            } else {
                $name = $receive->miscellaneous;
            }
            if ($receive->for == 0) {
                $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="btn btn-icon-only green financeEditPermission" href="' . site_url($this->project . '/receiveVoucherEdit/' . $receive->Accounts_VoucherMst_AutoID) . '">
        <i class="ace-icon fa fa-pencil bigger-130"></i></a>
         <button class="btn btn-icon-only red" href="javascript:void(0)" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="delete_' . $receive->Accounts_VoucherMst_AutoID . '" onclick="deleteVoucher(2,' . $receive->Accounts_VoucherMst_AutoID . ')">
                <i class="fa fa-trash-o bigger-130"></i></button>';
                $Narration = $receive->Narration;
            } else {

                $Narration = $receive->Narration . "  <span style='color: #e7505a;font-style: italic;'>" . $receive->BackReferenceInvoiceNo . "</span>";
                $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a title="View Payment Voucher" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->AccouVoucherType_AutoID) . '">' . $receive->Accounts_Voucher_No . '</a></td>';
            //$row[] = date('M d, Y', strtotime($receive->Accounts_Voucher_Date));
            $row[] = $receive->Accounts_Voucher_Date;
            $row[] = $receive->Accounts_VoucherType;//$payment->name;
            $row[] = $receive->branch_name;
            $row[] = $name;
            $row[] = $Narration;
            $row[] = number_format((float)$receive->amount, 2, '.', ',');
            $row[] = $receive->name;
            $row[] = $action;
            $data[] = $row;
        }

        /* */

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_receive(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_receive(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function receiveList___old()
    {

        $this->Filter_Model->filterData('generals', array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), $this->dist_id);
        $list = $this->Filter_Model->get_receive_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $receive) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($receive->date));
            $row[] = '<a title="View Payment Voucher" href="' . site_url($this->project . '/paymentVoucherView/' . $receive->generals_id) . '">' . $receive->voucher_no . '</a></td>';
            $row[] = $receive->name;

            if (!empty($receive->customer_id)) {
                $row[] = '<a title="View Customer  Dashboard" href="' . site_url($this->project . '/customerDashboard/' . $receive->customer_id) . '">' . $receive->customerID . ' [ ' . $receive->customerName . ' ] ' . '</a>';
            } else if (!empty($receive->supplier_id)) {
                $row[] = '<a title="View Supplier  Dashboard" href="' . site_url($this->project . '/supplierDashboard/' . $receive->supplier_id) . '">' . $receive->supID . ' [ ' . $receive->supName . ' ] ' . '</a>';
            } else {
                $row[] = $receive->miscellaneous;
            }

            $row[] = $receive->narration;
            $row[] = number_format((float)$receive->debit, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->generals_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green financeEditPermission" href="' . site_url($this->project . '/receiveVoucherEdit/' . $receive->generals_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_receive(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_receive(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function journalList()
    {


        $this->Filter_Model->filterData('ac_accounts_vouchermst',
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ',
                'ac_accounts_vouchermst.Narration',
                'ac_accounts_vouchermst.BackReferenceInvoiceNo',
                "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date",
                'ac_accounts_vouchermst.Accounts_Voucher_No',
                'accounts_vouchertype_autoid.Accounts_VoucherType',
                'branch.branch_name'),
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ',
                'ac_accounts_vouchermst.Narration',
                'ac_accounts_vouchermst.BackReferenceInvoiceNo',
                "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') ",
                'ac_accounts_vouchermst.Accounts_Voucher_No',
                'accounts_vouchertype_autoid.Accounts_VoucherType',
                'branch.branch_name'),
            array('(SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID ) ',
                'ac_accounts_vouchermst.Narration',
                'ac_accounts_vouchermst.BackReferenceInvoiceNo',
                "DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date",
                'ac_accounts_vouchermst.Accounts_Voucher_No',
                'accounts_vouchertype_autoid.Accounts_VoucherType',
                'branch.branch_name'),
            $this->dist_id);


        /* $this->Filter_Model->filterData('ac_accounts_vouchermst',
             array('ac_accounts_vouchermst.for,ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
             array('ac_accounts_vouchermst.for,ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
             array('ac_accounts_vouchermst.for,ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
             $this->dist_id);*/
             $this->load->helper('site_helper');
             $add  = check_parmission_by_user_role(2113);
             $edit  = check_parmission_by_user_role(2114);
             $delete  = check_parmission_by_user_role(2115);

        $list = $this->Filter_Model->get_journal_datatables();
        $data = array();
        $no = $_POST['start'];


        foreach ($list as $journal) {
            if ($journal->for == 0) {
                if($edit == 0 && $delete == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    ';
                } elseif($edit == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    
                        <button class="btn btn-icon-only red" href="javascript:void(0)" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="delete_' . $journal->Accounts_VoucherMst_AutoID . '" onclick="deleteVoucher(2,' . $journal->Accounts_VoucherMst_AutoID . ')">
                                <i class="fa fa-trash-o bigger-130"></i></button>';
                }
                elseif($delete == 0){
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    <a class="btn btn-icon-only green financeEditPermission" href="' . site_url($this->project . '/journalVoucherEdit/' . $journal->Accounts_VoucherMst_AutoID) . '">
                        <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                       ';
                }
    
                else{
                    $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
                    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
                    <a class="btn btn-icon-only green financeEditPermission" href="' . site_url($this->project . '/journalVoucherEdit/' . $journal->Accounts_VoucherMst_AutoID) . '">
                        <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                        <button class="btn btn-icon-only red" href="javascript:void(0)" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="delete_' . $journal->Accounts_VoucherMst_AutoID . '" onclick="deleteVoucher(2,' . $journal->Accounts_VoucherMst_AutoID . ')">
                                <i class="fa fa-trash-o bigger-130"></i></button>';
                }
               
                $Narration = $journal->Narration;
            } else {

                $Narration = $journal->Narration . "  <span style='color: #e7505a;font-style: italic;'>" . $journal->BackReferenceInvoiceNo . "</span>";
                $action = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
                <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            }


            /* if ($journal->for==0) {
                 $view='<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
     <i class="ace-icon fa fa-search-plus bigger-130"></i></a><a class="btn btn-icon-only red green financeEditPermission" href="' . site_url($this->project . '/journalVoucherEdit/' . $journal->Accounts_VoucherMst_AutoID) . '">
     <i class="ace-icon fa fa-pencil bigger-130"></i></a>';

             }else{
                 $view='<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">
     <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
             }*/


            $no++;
            $row = array();
            $row[] = $no;

            $row[] = '<a title="View Journal Voucher" href="' . site_url($this->project . '/journalVoucherView/' . $journal->Accounts_VoucherMst_AutoID) . '">' . $journal->Accounts_Voucher_No . '</a></td>';
            //$row[] = date('M d, Y', strtotime($journal->Accounts_Voucher_Date));
            $row[] = $journal->Accounts_Voucher_Date;
            $row[] = $journal->Accounts_VoucherType;
            $row[] = $Narration;
            $row[] = $journal->total_amount;
            $row[] = $journal->name;
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_journal(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_journal(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }


    public function billVoucherList()
    {

        $this->Filter_Model->filterData('generals', array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), $this->dist_id);
        $list = $this->Filter_Model->get_bill_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $bill) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($bill->date));
            $row[] = '<a title="View Payment Voucher" href="' . site_url($this->project . '/paymentVoucherView/' . $bill->generals_id) . '">' . $bill->voucher_no . '</a></td>';
            $row[] = $bill->name;

            if (!empty($bill->customer_id)) {
                $row[] = '<a title="View Customer  Dashboard" href="' . site_url($this->project . '/customerDashboard/' . $bill->customer_id) . '">' . $bill->customerID . ' [ ' . $bill->customerName . ' ] ' . '</a>';
            } else if (!empty($bill->supplier_id)) {
                $row[] = '<a title="View Supplier  Dashboard" href="' . site_url($this->project . '/supplierDashboard/' . $bill->supplier_id) . '">' . $bill->supID . ' [ ' . $bill->supName . ' ] ' . '</a>';
            } else {
                $row[] = $bill->miscellaneous;
            }
            $row[] = $bill->narration;
            $row[] = number_format((float)$bill->debit, 2, '.', ',');
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/billInvoice_view/' . $bill->generals_id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>
    <a class="btn btn-icon-only red" href="' . site_url($this->project . '/billInvoice_edit/' . $bill->generals_id) . '">
    <i class="fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_bill(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_bill(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function cusPayList()
    {

        $this->Filter_Model->filterData('moneyreceit', array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'), $this->dist_id);
        $list = $this->Filter_Model->get_cusPay_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $cusPay) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($cusPay->date));
            $row[] = '<a title="View Money Receit" href="' . site_url($this->project . '/viewMoneryReceipt/' . $cusPay->moneyReceitid) . '">' . $cusPay->receitID . '</a></td>';

            $row[] = '<a title="View Customer  Dashboard" href="' . site_url($this->project . '/customerDashboard/' . $cusPay->customer_id) . '">' . $cusPay->customerID . ' [ ' . $cusPay->customerName . ' ] ' . '</a>';

            if ($cusPay->paymentType == 1):
                $row[] = "Cash";
            else:
                if ($cusPay->checkStatus == 1):
                    $row[] = '<p style="color:red;"> Bank &nbsp; <i class="fa fa-credit-card"></i></p>';
                else:
                    $row[] = '<p style="color:green;"> Bank &nbsp;<i class="fa fa-check"></i></p>';
                endif;
            endif;
            $row[] = number_format((float)$cusPay->totalPayment, 2, '.', ',');
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewMoneryReceipt/' . $cusPay->moneyReceitid) . '">
            <i class="fa fa-search-plus bigger-130"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_cusPay(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cusPay(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function supPayList()
    {

        $this->Filter_Model->filterData('moneyreceit', array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'supplier.supID', 'supplier.supName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'supplier.supID', 'supplier.supName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'supplier.supID', 'supplier.supName'), $this->dist_id);
        $list = $this->Filter_Model->get_supPay_datatables();
        log_message('error', 'supplier payment list ' . print_r($list, true));
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $supPay) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($supPay->date));
            $row[] = '' . $supPay->receitID . '';

            $row[] = '' . $supPay->supID . ' [ ' . $supPay->supName . ' ] ' . '';

            if ($supPay->paymentType == 1):
                $row[] = "Cash";
            else:
                if ($supPay->checkStatus == 1):
                    $row[] = '<p style="color:red;"> Bank &nbsp; <i class="fa fa-refresh fa-spin fa-fw"></i></p>';
                else:
                    $row[] = '<p style="color:green;"> Bank &nbsp;<i class="fa fa-check"></i></p>';
                endif;
            endif;
            $row[] = number_format((float)$supPay->totalPayment, 2, '.', ',');
            $row[] = $name;
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/viewMoneryReceiptSup/' . $supPay->moneyReceitid) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_supPay(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_supPay(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function productTypeList()
    {
        $this->Filter_Model->filterData('product_type', array('product_type.product_type_name'), array('product_type.product_type_name'), array('product_type.product_type_name'), $this->dist_id);
        $list = $this->Filter_Model->get_product_type_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $products->product_type_name;

            if ($products->dist_id != 1):
                if ($products->is_active == "Y"):
                    $row[] = '<a href="javascript:void(0)" onclick="productTypeStatusChange(' . $products->product_type_id . ',0)" class="label label-danger arrowed">
                    <i class="fa fa-ban"></i>
                    Inactive</a>';
                else:
                    $row[] = '<a href="javascript:void(0)" onclick="productTypeStatusChange(' . $products->product_type_id . ',1)" class="label label-success arrowed">
                    <i class="fa fa-check bigger-110"></i>
                    Active
                </a>';
                endif;
            else:
                $row[] = '';
            endif;
            if ($products->dist_id != 1):
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/editproductType/' . $products->product_type_id) . '">
                <i class="fa fa-pencil bigger-130"></i></a>
                <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteProductType(' . $products->product_type_id . ',2)">
                  <i class="fa fa-trash-o bigger-130"></i>
                </a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_productType(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_product_type(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function productPackageList()
    {
        $this->Filter_Model->filterData('package', array('package.package_name'), array('package.package_name'), array('package.package_name'), $this->dist_id);
        $list = $this->Filter_Model->get_product_package_datatables();
        //$list = $this->Filter_Model->get_product_type_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $products->package_name;

            if ($products->dist_id != 1):
                if ($products->is_active == "Y"):
                    $row[] = '<a href="javascript:void(0)" onclick="productPackageStatusChange(' . $products->package_id . ',0)" class="label label-danger arrowed">
                    <i class="ace-icon fa fa-fire bigger-110"></i>
                    Inactive</a>';
                else:
                    $row[] = '<a href="javascript:void(0)" onclick="productPackageStatusChange(' . $products->package_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
                endif;
            else:
                $row[] = '';
            endif;
            if ($products->dist_id != 1):
                $row[] = '<a class="inventoryEditPermission" href="' . site_url($this->project . '/productPackageView/' . $products->package_id) . '">
                        <i class="fa fa-search-plus bigger-130"></i> 
                    </a>
                    <a class="blue inventoryEditPermission" href="' . site_url($this->project . '/productPackageEdit/' . $products->package_id) . '">
                <i class="fa fa-pencil bigger-130"></i></a>
                <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProductpackage(' . $products->package_id . ',2)">
                  <i class="ace-icon fa fa-trash-o bigger-130"></i>
                </a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_productPackage(),
            //"recordsTotal" => $this->Filter_Model->count_all_productType(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_product_package(),
            //"recordsFiltered" => $this->Filter_Model->count_filtered_product_type(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function so_po_list()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('so_po_info',
            array("DATE_FORMAT(so_po_info.so_po_date, '%b %e, %Y') ", 'so_po_info.so_po_no', 'customer.customerID', 'customer.customerName', 'so_po_info.narration', 'so_po_info.status', "DATE_FORMAT(so_po_info.delivery_date, '%b %e, %Y') "),
            array("DATE_FORMAT(so_po_info.so_po_date, '%b %e, %Y') ", 'so_po_info.so_po_no', 'customer.customerID', 'customer.customerName', 'so_po_info.narration', 'so_po_info.status', "DATE_FORMAT(so_po_info.delivery_date, '%b %e, %Y') ", "so_po_productes.one_line_narration"),
            array("DATE_FORMAT(so_po_info.so_po_date, '%b %e, %Y') ", 'so_po_info.so_po_no', 'customer.customerID', 'customer.customerName', 'so_po_info.narration', 'so_po_info.status', "DATE_FORMAT(so_po_info.delivery_date, '%b %e, %Y') "), $this->dist_id);
        $list = $this->Filter_Model->get_so_po_datatables();
        log_message('error', 'ReturnDamageModel ' . print_r($this->db->last_query(), true));
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $so_pos_list) {
            $delete = '<button class="btn btn-icon-only red " data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="deletesalesinvoice_' . $so_pos_list->id . '" href="javascript:void(0)" onclick="delete_so_po(7,' . $so_pos_list->id . ')">
                <i class="fa fa-trash-o bigger-130"></i></button>';
            $edit = '<a class="btn btn-icon-only green" href="' . site_url($this->project . '/sales_order_edit/' . $so_pos_list->id) . '"><i class="fa fa-edit"></i></a>';
            if ($so_pos_list->status == 0) {
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">pending</a>';
            } else if ($so_pos_list->status == 1) {
                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Approved</a>';
            } else if ($so_pos_list->status == 2) {
                $edit = "";
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">Canceled</a>';
            } else if ($so_pos_list->status == 3) {
                $payment_type = '<a class=" btn btn-xs purple" href="javascript:void(0)">Partially Delivery</a>';
            } else if ($so_pos_list->status == 4) {
                $delete = "";
                $payment_type = '<a class=" btn btn-xs blue" href="javascript:void(0)">Complete Delivery</a>';
            } else if ($so_pos_list->status == 5) {
                $edit = "";
                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Payment</a>';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $so_pos_list->so_po_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/so_po_view/' . $so_pos_list->id) . '">' . $so_pos_list->so_po_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $so_pos_list->customerID . ' [ ' . $so_pos_list->customerName . ' ] ' . '</a>';
            $row[] = $payment_type;

            $row[] = $so_pos_list->delivery_date;

            /*if($property_1 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_1;
            }
            if($property_2 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_2;
            }
            if($property_3 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_3;
            }
            if($property_4 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_4;
            }
            if($property_5 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_5;
            }*/


            $row[] = $so_pos_list->narration;
            $row[] = $so_pos_list->name;
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            $this->load->helper('site_helper');
            $addP  = check_parmission_by_user_role(2110);
            $editP  = check_parmission_by_user_role(2111);
            $deleteP  = check_parmission_by_user_role(2112);
            if($editP == 0 && $deleteP == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/so_po_view/' . $so_pos_list->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>';
            } elseif($editP == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/so_po_view/' . $so_pos_list->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>'  . $delete;
            }
            elseif($deleteP == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/so_po_view/' . $so_pos_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>' . $edit ;
            }

            else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/so_po_view/' . $so_pos_list->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>' . $edit . $delete;
            }
            
            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_sales_order(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_sales_order(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function po_list()
    {
        
        $editP  = check_parmission_by_user_role(2111);
        $deleteP  = check_parmission_by_user_role(2112);

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('so_po_info',
            array("DATE_FORMAT(so_po_info.so_po_date, '%b %e, %Y') ", 'so_po_info.so_po_no', 'supplier.sup_id', 'supplier.supName', 'so_po_info.narration', 'so_po_info.status', "DATE_FORMAT(so_po_info.delivery_date, '%b %e, %Y') "),
            array("DATE_FORMAT(so_po_info.so_po_date, '%b %e, %Y') ", 'so_po_info.so_po_no', 'supplier.sup_id', 'supplier.supName', 'so_po_info.narration', 'so_po_info.status', "DATE_FORMAT(so_po_info.delivery_date, '%b %e, %Y') ", "so_po_productes.one_line_narration"),
            array("DATE_FORMAT(so_po_info.so_po_date, '%b %e, %Y') ", 'so_po_info.so_po_no', 'supplier.sup_id', 'supplier.supName', 'so_po_info.narration', 'so_po_info.status', "DATE_FORMAT(so_po_info.delivery_date, '%b %e, %Y') "), $this->dist_id);
        $list = $this->Filter_Model->get_po_datatables();
        // log_message('error','ReturnDamageModel '.print_r($this->db->last_query(),true));
        $data = array();
        $no = $_POST['start'];
        //0->pending,1->approved,2->cancel,3->partially delivery,4->complete delivery,5->payment
        foreach ($list as $so_pos_list) {
            $delete = '<button class="btn btn-icon-only red " data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="deletesalesinvoice_' . $so_pos_list->id . '" href="javascript:void(0)" onclick="deletesalesinvoice(8,' . $so_pos_list->id . ')">
                <i class="fa fa-trash-o bigger-130"></i></button>';
            $edit = '<a class="btn btn-icon-only green" href="' . site_url($this->project . '/purchase_order_edit/' . $so_pos_list->id) . '"><i class="fa fa-edit"></i></a>';
            if ($so_pos_list->status == 0) {
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">pending</a>';
            } else if ($so_pos_list->status == 1) {
                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Approved</a>';
            } else if ($so_pos_list->status == 2) {
                $edit = "";
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">Canceled</a>';
            } else if ($so_pos_list->status == 3) {
                $payment_type = '<a class=" btn btn-xs purple" href="javascript:void(0)">Partially Received</a>';
            } else if ($so_pos_list->status == 4) {
                $delete = "";
                $payment_type = '<a class=" btn btn-xs blue" href="javascript:void(0)">Complete Received</a>';
            } else if ($so_pos_list->status == 5) {
                $edit = "";
                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Payment</a>';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $so_pos_list->so_po_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/purchase_order_view/' . $so_pos_list->id) . '">' . $so_pos_list->so_po_no . '</a></td>';
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . $so_pos_list->sup_id . ' [ ' . $so_pos_list->supName . ' ] ' . '</a>';
            $row[] = $payment_type;

            $row[] = $so_pos_list->delivery_date;

            /*if($property_1 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_1;
            }
            if($property_2 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_2;
            }
            if($property_3 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_3;
            }
            if($property_4 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_4;
            }
            if($property_5 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_5;
            }*/


            $row[] = $so_pos_list->narration;
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            if($editP == 0 && $deleteP == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/purchase_order_view/' . $so_pos_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>';
            } elseif($editP == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/purchase_order_view/' . $so_pos_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>' . $delete;
            }
            elseif($deleteP == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/purchase_order_view/' . $so_pos_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>' . $edit;
            }

            else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/purchase_order_view/' . $so_pos_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>' . $edit . $delete;
            }

          

            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_purchases_order(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_purchase_order(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function mrn_list()
    {

        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);

        $this->Filter_Model->filterData('sdc_mrn_info',
            array("DATE_FORMAT(sdc_mrn_info.mrn_recived_date, '%b %e, %Y') ", 'sdc_mrn_info.sdc_mrn_no', 'supplier.sup_id', 'supplier.supName', 'sdc_mrn_no.narration', 'sdc_mrn_no.status'),
            array("DATE_FORMAT(sdc_mrn_info.mrn_recived_date, '%b %e, %Y') ", 'sdc_mrn_info.sdc_mrn_no', 'supplier.sup_id', 'supplier.supName', 'sdc_mrn_no.narration', 'sdc_mrn_no.status', "sdc_mrn_products.one_line_narration"),
            array("DATE_FORMAT(sdc_mrn_info.mrn_recived_date, '%b %e, %Y') ", 'sdc_mrn_info.sdc_mrn_no', 'supplier.sup_id', 'supplier.supName', 'sdc_mrn_no.narration', 'sdc_mrn_no.status'), $this->dist_id);
        $list = $this->Filter_Model->get_mrn_datatables();
        // log_message('error','ReturnDamageModel '.print_r($this->db->last_query(),true));
        $data = array();
        $no = $_POST['start'];
        //0->pending,1->approved,2->cancel,3->partially delivery,4->complete delivery,5->payment
        foreach ($list as $mrn_sdc_list) {


            $back_refference = "";
            $so_info_datials = explode("#@%&%@#", $mrn_sdc_list->so_po_ides);
            foreach ($so_info_datials as $key => $value) {
                $po = explode("&*", $value);
                $saparator = '&nbsp,&nbsp';
                $back_refference .= '<a title="view SO " href="' . site_url($this->project . '/purchase_order_view/' . $po[1]) . '">' . $po[0] . '</a>';
                if (count($so_info_datials)-1 != $key) {
                    $back_refference .= $saparator;
                }
            }
            $delete="";
            /*$delete = '<button class="btn btn-icon-only red " data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="deletesalesinvoice_' . $mrn_sdc_list->id . '" href="javascript:void(0)" onclick="deletesalesinvoice(8,' . $mrn_sdc_list->id . ')">
                <i class="fa fa-trash-o bigger-130"></i></button>';*/
            $edit = '<a class="btn btn-icon-only green" href="' . site_url($this->project . '/mrn_edit/' . $mrn_sdc_list->id) . '"><i class="fa fa-edit"></i></a>';

            $make_bill = '<a class="btn btn-icon-only purple "  href="' . site_url($this->project . '/prepare_bill/' . $mrn_sdc_list->id) . '" style="width: 28%" >
                <i class=\'fa fa-spinner fa-spin \'></i>Prepare Bill</a>';

            $view_bill = "";
            if ($mrn_sdc_list->status == 0) {
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">pending</a>';
            } else if ($mrn_sdc_list->status == 1) {
                $mrn_sdc_list = '<a class=" btn btn-xs green" href="javascript:void(0)">Approved</a>';
            } else if ($mrn_sdc_list->status == 2) {
                $edit = "";
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">Canceled</a>';
            } else if ($mrn_sdc_list->status == 3) {
                $payment_type = '<a class=" btn btn-xs purple" href="javascript:void(0)">Partially Received</a>';
            } else if ($mrn_sdc_list->status == 4) {
                $delete = "";
                $payment_type = '<a class=" btn btn-xs blue" href="javascript:void(0)">Complete Received</a>';
            } else if ($mrn_sdc_list->status == 5) {
                $edit = "";
                $make_bill = "";
                $view_bill = '<a class="btn btn-icon-only blue "  href="' . site_url($this->project . '/view_mrn_bill/' . $mrn_sdc_list->id) . '" style="width: 28%" >
                <i class="fa fa-search-plus bigger-130"></i>View Bill</a>';
                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Payment</a>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $mrn_sdc_list->mrn_recived_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/mrn_view/' . $mrn_sdc_list->id) . '">' . $mrn_sdc_list->sdc_mrn_no . '</a></td>';
            $row[] = $back_refference;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . ' [ ' . $mrn_sdc_list->supName . ' ] ' . '</a>';
            $row[] = $payment_type;

            // $row[] = $mrn_sdc_list->mrn_recived_date;

            /*if($property_1 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_1;
            }
            if($property_2 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_2;
            }
            if($property_3 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_3;
            }
            if($property_4 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_4;
            }
            if($property_5 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_5;
            }*/


            $row[] = $mrn_sdc_list->narration;
            $row[] = $mrn_sdc_list->name;
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            $this->load->helper('site_helper');
            
            $editPer  = check_parmission_by_user_role(2117);
            $deletePer  = check_parmission_by_user_role(2118);
            if($editPer == 0 && $deletePer == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/mrn_view/' . $mrn_sdc_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>'  . $make_bill . $view_bill;
            } elseif($editPer == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/mrn_view/' . $mrn_sdc_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>'. $delete . $make_bill . $view_bill;
            }
            elseif($deletePer == 0){
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/mrn_view/' . $mrn_sdc_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>' . $edit  . $make_bill . $view_bill;
            }

            else{
                $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/mrn_view/' . $mrn_sdc_list->id) . '">
                <i class="fa fa-search-plus bigger-130"></i></a>' . $edit . $delete . $make_bill . $view_bill;
            }

           
            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_mrn(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_mrn(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function sdc_list()
    {


        $this->Filter_Model->filterData('sdc_mrn_info',
            array("DATE_FORMAT(sdc_mrn_info.sdc_delivery_date, '%b %e, %Y') ", 'sdc_mrn_info.sdc_mrn_no', 'supplier.sup_id', 'supplier.supName', 'sdc_mrn_no.narration', 'sdc_mrn_no.status'),
            array("DATE_FORMAT(sdc_mrn_info.sdc_delivery_date, '%b %e, %Y') ", 'sdc_mrn_info.sdc_mrn_no', 'supplier.sup_id', 'supplier.supName', 'sdc_mrn_no.narration', 'sdc_mrn_no.status', "sdc_mrn_products.one_line_narration"),
            array("DATE_FORMAT(sdc_mrn_info.sdc_delivery_date, '%b %e, %Y') ", 'sdc_mrn_info.sdc_mrn_no', 'supplier.sup_id', 'supplier.supName', 'sdc_mrn_no.narration', 'sdc_mrn_no.status'), $this->dist_id);
        $list = $this->Filter_Model->get_sdc_datatables();

        $data = array();
        $no = $_POST['start'];
        //0->pending,1->approved,2->cancel,3->partially delivery,4->complete delivery,5->payment
        foreach ($list as $mrn_sdc_list) {
            $back_refference = "";
            $so_info_datials = explode("#@%&%@#", $mrn_sdc_list->so_po_ides);
            foreach ($so_info_datials as $key => $value) {
                $so = explode("&*", $value);
                $saparator = '&nbsp,&nbsp';
                $back_refference .= '<a title="view SO " href="' . site_url($this->project . '/so_po_view/' . $so[1]) . '">' . $so[0] . '</a>';
                if (count($so_info_datials)-1 != $key) {
                    $back_refference .= $saparator;
                }
            }
            $delete="";
           /* $delete = '<button class="btn btn-icon-only red " data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Processing " id="deletesalesinvoice_' . $mrn_sdc_list->id . '" href="javascript:void(0)" onclick="deletesalesinvoice(8,' . $mrn_sdc_list->id . ')">
                <i class="fa fa-trash-o bigger-130"></i></button>';*/
            $edit="";
            //$edit = '<a class="btn btn-icon-only green" href="' . site_url($this->project . '/sdc_edit/' . $mrn_sdc_list->id) . '"><i class="fa fa-edit"></i></a>';

            $make_bill = '<a class="btn btn-icon-only purple "  href="' . site_url($this->project . '/prepare_sdc_bill/' . $mrn_sdc_list->id) . '" style="width: 41%" >
                <i class=\'fa fa-spinner fa-spin \'></i>Prepare Bill</a>';

            $view_bill = "";
            if ($mrn_sdc_list->status == 0) {
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">pending</a>';
            } else if ($mrn_sdc_list->status == 1) {
                $mrn_sdc_list = '<a class=" btn btn-xs green" href="javascript:void(0)">Approved</a>';
            } else if ($mrn_sdc_list->status == 2) {
                $edit = "";
                $payment_type = '<a class=" btn btn-xs red" href="javascript:void(0)">Canceled</a>';
            } else if ($mrn_sdc_list->status == 3) {
                $payment_type = '<a class=" btn btn-xs purple" href="javascript:void(0)">Partially Received</a>';
            } else if ($mrn_sdc_list->status == 4) {
                $delete = "";
                $payment_type = '<a class=" btn btn-xs blue" href="javascript:void(0)">Complete Received</a>';
            } else if ($mrn_sdc_list->status == 5) {
                $edit = "";
                $make_bill = "";
                $view_bill = '<a class="btn btn-icon-only blue "  href="' . site_url($this->project . '/view_sdc_bill/' . $mrn_sdc_list->id) . '" style="width: 42%" >
                <i class="fa fa-search-plus bigger-130"></i>View Bill</a>';
                $payment_type = '<a class=" btn btn-xs green" href="javascript:void(0)">Payment</a>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $mrn_sdc_list->sdc_delivery_date;
            $row[] = '<a title="view invoice" href="' . site_url($this->project . '/sdc_view/' . $mrn_sdc_list->id) . '">' . $mrn_sdc_list->sdc_mrn_no . '</a>';
            $row[] = $back_refference;
            //$row[] = $sale->name;
            $row[] = '<a title="View Customer Dashboard" href="javascript:void(0)">' . ' [ ' . $mrn_sdc_list->supName . ' ] ' . '</a>';
            $row[] = $payment_type;

            // $row[] = $mrn_sdc_list->mrn_recived_date;

            /*if($property_1 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_1;
            }
            if($property_2 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_2;
            }
            if($property_3 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_3;
            }
            if($property_4 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_4;
            }
            if($property_5 !='dont_have_this_property'){
                $row[]=$so_pos_list->property_5;
            }*/


            $row[] = $mrn_sdc_list->narration;
            $row[] = $mrn_sdc_list->name;
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            $row[] = '<a class="btn btn-icon-only blue" href="' . site_url($this->project . '/sdc_view/' . $mrn_sdc_list->id) . '">
    <i class="fa fa-search-plus bigger-130"></i></a>' . $edit . $delete . $make_bill . $view_bill;
            /* <a class="btn btn-icon-only red" href="' . site_url($this->project . '/salesInvoice_edit/' . $sale->sales_invoice_id) . '">*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_sdc(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_sdc(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }


}
