<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;


    public $db_hostname;
    public $db_username;
    public $db_password;
    public $db_name;

    public $business_type;
    public $folder;
    public $folderSub;

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
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


        if ($this->business_type != "LPG") {
            $this->folder = 'distributor/masterTemplateSmeMobile';
            $this->folderSub = 'distributor/inventory/product_mobile/';
        }
    }

    function updateImportProduct()
    {
        $updateId = $this->input->post('updateId');
        $data['catId'] = $this->input->post('category_id');
        $data['brandId'] = $this->input->post('brand');
        $data['unitId'] = $this->input->post('unit');
        $data['productName'] = $this->input->post('productName');
        $data['catName'] = $this->Common_model->tableRow('productcategory', 'category_id', $data['catId'])->title;
        $data['brandName'] = $this->Common_model->tableRow('brand', 'brandId', $data['brandId'])->brandName;
        $data['unitName'] = $this->Common_model->tableRow('unit', 'unit_id', $data['unitId'])->unitTtile;
        $data['purchasesPrice'] = $this->input->post('purchases_price');
        $data['salesPrice'] = $this->input->post('salesPrice');
        $data['retailprice'] = $this->input->post('retailPrice');
        $this->Common_model->update_data('productimport', $data, 'importId', $updateId);
        $msg = $this->config->item("save_success_message");
        $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/productImport'));
    }

    function saveImportProduct()
    {
        
        $this->db->trans_start();
        $allResult = $this->Common_model->get_data_list('productimport');
        $allData = array();
        $productOrgId = $this->db->select('*')->count_all_results('product');
        $increment = 1;
        foreach ($allResult as $key => $value) {
            $result = $this->Common_model->checkDuplicateModel($this->dist_id, $value->productName, $value->catId, $value->brandId);
            if (empty($result)) {
                $productId = "PID" . date('y') . date('m') . str_pad($productOrgId + $increment, 4, "0", STR_PAD_LEFT);
                $data['category_id'] = $value->catId;
                $data['unit_id'] = $value->unitId;
                $data['brand_id'] = $value->brandId;
                $data['productName'] = $value->productName;
                $data['purchases_price'] = $value->purchasesPrice;
                $data['salesPrice'] = $value->salesPrice;
                $data['retailPrice'] = $value->retailprice;
                $data['alarm_qty'] = 0;
                $data['product_code'] = $productId;
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $allData[] = $data;
                $increment++;
                $insertid = $this->Common_model->insert_data('product', $data);
                $category_id = $value->catId;
                $brand_id = $value->brandId;
                if ($category_id == 1) {
                    //Empty Cylinder
                    $ledger_parent_id = $this->config->item("New_Cylinder_Stock");//25,;
                } else if ($category_id == 2) {
                    $ledger_parent_id = $this->config->item("Refill_Stock");//26,;
                } else {
                    $ledger_parent_id = $this->config->item("Inventory_Finished_Goods_Stock");//21,;
                }
                $for = 1;
                $productcategory_info = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                $brand_info = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $brand_id);
                $unit_info = $this->Common_model->get_single_data_by_single_column('unit', 'unit_id', $value->unitId);
                $productName = $productcategory_info->title . ' ' . $value->productName . ' ' . $unit_info->unitTtile . ' ' . $brand_info->brandName . ' [ ' . $productId . ' ]';
                create_ledger_cus_sup_product($insertid, $productName, $ledger_parent_id, $for, $this->admin_id);
                if ($category_id == 1) {
                    $ledger_parent_id = $this->config->item("Empty_Cylinder_Transfer");
                    $RelatedIdFor = $this->config->item("EmptyCylinderTransferRelatedIdFor");
                    $productName = " Transfer " . $productName;
                    create_ledger_cus_sup_product($insertid, $productName, $ledger_parent_id, $RelatedIdFor, $this->admin_id);
                }
            }
        }
        $this->db->truncate('productimport');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/productImport'));
        else:
            $msg = $this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/productList'));
        endif;
    }
    function saveImportProduct_motorcycle()
    {
        $this->db->trans_start();
        $allResult = $this->Common_model->get_data_list('productimport');
        $allData = array();
        $productOrgId = $this->db->select('*')->count_all_results('product');
        $increment = 1;
        foreach ($allResult as $key => $value) {
            $result = $this->Common_model->checkDuplicateModel($this->dist_id, $value->productName, $value->catId, $value->brandId);
            if (empty($result)) {
                $productId = "PID" . date('y') . date('m') . str_pad($productOrgId + $increment, 4, "0", STR_PAD_LEFT);

                $brand_info = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $value->brandId);
                $product_model_info = $this->Common_model->get_single_data_by_single_column('tb_model', 'ModelID', $value->ModelID)->Model;
                $product_coler_info = $this->Common_model->get_single_data_by_single_column('tb_color', 'ColorID', $value->ColorID)->Color;
                $productName =  $value->productName;
               // $productName =  $brand_info->brandName . ' ' . $product_model_info . ' ' . $product_coler_info . '     E.NO  :' . $value->property_1. '   Ch.NO  :' .$value->property_2;


                $data['category_id'] = $value->catId;
                $data['modelID'] = $value->ModelID;
                $data['colorID'] = $value->ColorID;
                $data['property_1'] = $value->property_1;
                $data['property_2'] = $value->property_2;
                $data['unit_id'] = $value->unitId;
                $data['brand_id'] = $value->brandId;
                $data['productName'] = $productName;
                $data['purchases_price'] = $value->purchasesPrice;
                $data['salesPrice'] = $value->salesPrice;
                $data['retailPrice'] = $value->retailprice;
                $data['alarm_qty'] = 0;
                $data['product_code'] = $productId;
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $allData[] = $data;
                $increment++;
                $insertid = $this->Common_model->insert_data('product', $data);
                $category_id = $value->catId;
                $brand_id = $value->brandId;
                if ($category_id == 1) {
                    //Empty Cylinder
                    $ledger_parent_id = $this->config->item("New_Cylinder_Stock");//25,;
                } else if ($category_id == 2) {
                    $ledger_parent_id = $this->config->item("Refill_Stock");//26,;
                } else {
                    $ledger_parent_id = $this->config->item("Inventory_Finished_Goods_Stock");//21,;
                }
                $for = 1;
                $productcategory_info = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                $brand_info = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $brand_id);
                $unit_info = $this->Common_model->get_single_data_by_single_column('unit', 'unit_id', $value->unitId);
                //$productName = $productcategory_info->title . ' ' . $value->productName . ' ' . $unit_info->unitTtile . ' ' . $brand_info->brandName . ' [ ' . $productId . ' ]';
                create_ledger_cus_sup_product($insertid, $productName, $ledger_parent_id, $for, $this->admin_id);
                if ($category_id == 1) {
                    $ledger_parent_id = $this->config->item("Empty_Cylinder_Transfer");
                    $RelatedIdFor = $this->config->item("EmptyCylinderTransferRelatedIdFor");
                    $productName = " Transfer " . $productName;
                    create_ledger_cus_sup_product($insertid, $productName, $ledger_parent_id, $RelatedIdFor, $this->admin_id);
                }
            }
        }
        $this->db->truncate('productimport');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/productImport'));
        else:
            $msg = $this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/productList'));
        endif;
    }

    function productImport_Bk()
    {
        if (isPostBack()) {
            if (!empty($_FILES['proImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . '_ProductImport' . date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //if ($row != 0):
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $catId = isset($readRowData[0]) ? $readRowData[0] : ''; //get category id
                        $catId = ltrim($catId);
                        $catId = rtrim($catId);
                        $brandId = isset($readRowData[1]) ? $readRowData[1] : ''; //get brand id
                        $brandId = ltrim($brandId);
                        $brandId = rtrim($brandId);
                        $unitId = isset($readRowData[2]) ? $readRowData[2] : ''; //get unit id
                        $unitId = ltrim($unitId);
                        $unitId = rtrim($unitId);
                        $productId = isset($readRowData[3]) ? $readRowData[3] : ''; //get product id
                        $productId = ltrim($productId);
                        $productId = rtrim($productId);
                        $purchasPrice = isset($readRowData[4]) ? $readRowData[4] : ''; //get purchases price

                        $retailPrice = isset($readRowData[5]) ? $readRowData[5] : ''; //get retail price
                        $wholesalePrice = isset($readRowData[6]) ? $readRowData[6] : ''; //get whole sale price
                        /* check duplicate supplier id end */
                        //product Category
                        if ($purchasPrice != '' || $retailPrice != '' || $wholesalePrice != '') {
                            $catCondition = array('title' => $catId);
                            $catInfo = $this->Common_model->rowResult('productcategory', $catCondition, $this->dist_id);
                            $data['catId'] = $catInfo->category_id;
                            $data['catName'] = $catId;
                            //brand
                            $brandCondition = array('brandName' => $brandId);
                            $brandInfo = $this->Common_model->rowResult('brand', $brandCondition, $this->dist_id);
                            $data['brandId'] = $brandInfo->brandId;
                            $data['brandName'] = $brandId;
                            //unit
                            $unitCondition = array('unitTtile' => $unitId);
                            $unitInfo = $this->Common_model->rowResult('unit', $unitCondition, $this->dist_id);
                            $data['unitId'] = $unitInfo->unit_id;
                            $data['unitName'] = $unitId;
                            $data['productName'] = $productId;
                            $data['purchasesPrice'] = $purchasPrice;
                            $data['salesPrice'] = $wholesalePrice;
                            $data['retailprice'] = $retailPrice;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }
                        //endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->truncate('productimport');
                    $this->db->insert_batch('productimport', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/productImport'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productImport'));
                endif;
            endif;
        }
        $data['productList'] = $this->Inventory_Model->getImportProduct($this->dist_id);
        //echo $this->db->last_query();die;
        $data['title'] = 'Product Import';
        $data['mainContent'] = $this->load->view('distributor/inventory/import/productImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function productImport()
    {
        if (isPostBack()) {
            if (!empty($_FILES['proImport']['name'])) {//supplier list import operation start this block

                if (!is_dir('uploads/' . $this->project)) {
                    mkdir('./uploads/' . $this->project, 0777, TRUE);

                }

                date_default_timezone_set('Asia/Manila');
                $date = date('Y-m-d H:i:s');
                $date = str_replace(':', '', $date);
                $config['upload_path'] = './uploads/' . $this->project;
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . '_ProductImportCSV_' . $date;

                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");


                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {

                    //if ($row != 0):
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $catId = isset($readRowData[0]) ? $readRowData[0] : ''; //get category id
                        $catId = ltrim($catId);
                        $catId = rtrim($catId);
                        $brandId = isset($readRowData[1]) ? $readRowData[1] : ''; //get brand id
                        $brandId = ltrim($brandId);
                        $brandId = rtrim($brandId);
                        $unitId = isset($readRowData[2]) ? $readRowData[2] : ''; //get unit id
                        $unitId = ltrim($unitId);
                        $unitId = rtrim($unitId);
                        $productId = isset($readRowData[3]) ? $readRowData[3] : ''; //get product id
                        $productId = ltrim($productId);
                        $productId = rtrim($productId);
                        $purchasPrice = isset($readRowData[4]) ? $readRowData[4] : ''; //get purchases price

                        $retailPrice = isset($readRowData[5]) ? $readRowData[5] : ''; //get retail price
                        $wholesalePrice = isset($readRowData[6]) ? $readRowData[6] : ''; //get whole sale price
                        /* check duplicate supplier id end */
                        //product Category
                        if ($purchasPrice != '' || $retailPrice != '' || $wholesalePrice != '') {
                            $catCondition = array('title' => $catId);
                            $catInfo = $this->Common_model->rowResult('productcategory', $catCondition, $this->dist_id);
                            $data['catId'] = $catInfo->category_id;
                            $data['catName'] = $catId;
                            //brand
                            $brandCondition = array('brandName' => $brandId);
                            $brandInfo = $this->Common_model->rowResult('brand', $brandCondition, $this->dist_id);
                            $data['brandId'] = $brandInfo->brandId;
                            $data['brandName'] = $brandId;
                            //unit
                            $unitCondition = array('unitTtile' => $unitId);
                            $unitInfo = $this->Common_model->rowResult('unit', $unitCondition, $this->dist_id);
                            $data['unitId'] = $unitInfo->unit_id;
                            $data['unitName'] = $unitId;
                            $data['productName'] = $productId;
                            $data['purchasesPrice'] = $purchasPrice;
                            $data['salesPrice'] = $wholesalePrice;
                            $data['retailprice'] = $retailPrice;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }
                        //endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->truncate('productimport');
                    $this->db->insert_batch('productimport', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/productImport'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productImport'));
                endif;
            } elseif (!empty($_FILES['proImportExcel']['name'])) {
                $this->load->library('excel');
                $this->load->helper('file');
                $file = $_FILES['proImportExcel']['tmp_name'];
                $object = PHPExcel_IOFactory::load($file);
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $cusCondition = array('dist_id' => $this->dist_id);

                        $catId = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()); //get category id
                        $brandId = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //get brand id
                        $unitId = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //get unit id
                        $productId = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); //get product id
                        $purchasPrice = $worksheet->getCellByColumnAndRow(4, $row)->getValue();//get purchases price
                        $retailPrice = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); //get retail price
                        $wholesalePrice = $worksheet->getCellByColumnAndRow(6, $row)->getValue(); //get whole sale price
                        if ($purchasPrice != '' || $retailPrice != '' || $wholesalePrice != '') {
                            $catCondition = array('title' => $catId);
                            $catInfo = $this->Common_model->rowResult('productcategory', $catCondition, $this->dist_id);
                            $data['catId'] = $catInfo->category_id;
                            $data['catName'] = $catId;
                            //brand
                            $brandCondition = array('brandName' => $brandId);
                            $brandInfo = $this->Common_model->rowResult('brand', $brandCondition, $this->dist_id);
                            $data['brandId'] = $brandInfo->brandId;
                            $data['brandName'] = $brandId;
                            //unit
                            $unitCondition = array('unitTtile' => $unitId);
                            $unitInfo = $this->Common_model->rowResult('unit', $unitCondition, $this->dist_id);
                            $data['unitId'] = $unitInfo->unit_id;
                            $data['unitName'] = $unitId;
                            $data['productName'] = $productId;
                            $data['purchasesPrice'] = $purchasPrice;
                            $data['salesPrice'] = $wholesalePrice;
                            $data['retailprice'] = $retailPrice;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }

                    }

                }

                if (!empty($storeData)):
                    $this->db->truncate('productimport');
                    $this->db->insert_batch('productimport', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/productImport'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productImport'));
                endif;
            }

        }
        $data['productList'] = $this->Inventory_Model->getImportProduct($this->dist_id);
        //echo $this->db->last_query();die;
        $data['title'] = 'Product Import';
        $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2101);

        if($add == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
           
        } else{
            if ($this->business_type != "LPG") {
                $data['mainContent'] = $this->load->view('distributor/inventory/import/productImportNonLpgBusiness', $data, true);
            }else{
                $data['mainContent'] = $this->load->view('distributor/inventory/import/productImport', $data, true);
            }
        }

       

        $this->load->view('distributor/masterTemplate', $data);
    }
    function productImport_motorcycle()
    {
        if (isPostBack()) {
            if (!empty($_FILES['proImport']['name'])) {//supplier list import operation start this block

                if (!is_dir('uploads/' . $this->project)) {
                    mkdir('./uploads/' . $this->project, 0777, TRUE);

                }

                date_default_timezone_set('Asia/Manila');
                $date = date('Y-m-d H:i:s');
                $date = str_replace(':', '', $date);
                $config['upload_path'] = './uploads/' . $this->project;
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . '_ProductImportCSV_' . $date;

                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");


                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {

                    //if ($row != 0):
                    if ($row != 0):

                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $catId = isset($readRowData[0]) ? $readRowData[0] : ''; //get category id
                        $catId = ltrim($catId);
                        $catId = rtrim($catId);
                        $brandId = isset($readRowData[1]) ? $readRowData[1] : ''; //get brand id
                        $brandId = ltrim($brandId);
                        $brandId = rtrim($brandId);
                        $unitId = isset($readRowData[2]) ? $readRowData[2] : ''; //get unit id
                        $unitId = ltrim($unitId);
                        $unitId = rtrim($unitId);
                        $productId = isset($readRowData[3]) ? $readRowData[3] : ''; //get product id
                        $productId = ltrim($productId);
                        $productId = rtrim($productId);
                        $purchasPrice = isset($readRowData[4]) ? $readRowData[4] : ''; //get purchases price

                        $retailPrice = isset($readRowData[5]) ? $readRowData[5] : ''; //get retail price
                        $wholesalePrice = isset($readRowData[6]) ? $readRowData[6] : ''; //get whole sale price
                        $model_name = isset($readRowData[7]) ? $readRowData[7] : ''; //get whole sale price
                        $subcatagory_name = isset($readRowData[8]) ? $readRowData[8] : ''; //get whole sale price
                        $color_name = isset($readRowData[9]) ? $readRowData[9] : ''; //get whole sale price
                        $property_1 = isset($readRowData[11]) ? $readRowData[11] : ''; //get whole sale price
                        $property_2 = isset($readRowData[12]) ? $readRowData[12] : ''; //get whole sale price
                        /* check duplicate supplier id end */
                        //product Category
                        if ($purchasPrice != '' || $retailPrice != '' || $wholesalePrice != '') {
                            $catCondition = array('title' => $catId);
                            $catInfo = $this->Common_model->rowResult('productcategory', $catCondition, $this->dist_id);
                            $data['catId'] = $catInfo->category_id;
                            $data['catName'] = $catId;
                            //brand
                            $brandCondition = array('brandName' => $brandId);
                            $brandInfo = $this->Common_model->rowResult('brand', $brandCondition, $this->dist_id);
                            $data['brandId'] = $brandInfo->brandId;
                            $data['brandName'] = $brandId;
                            //unit
                            $unitCondition = array('unitTtile' => $unitId);
                            $unitInfo = $this->Common_model->rowResult('unit', $unitCondition, $this->dist_id);
                            $data['unitId'] = $unitInfo->unit_id;
                            $data['unitName'] = $unitId;

                            $data['purchasesPrice'] = $purchasPrice;
                            $data['salesPrice'] = $wholesalePrice;
                            $data['retailprice'] = $retailPrice;
                            $data['dist_id'] = $this->dist_id;


                            $modelCondition = array('Model' => $model_name);
                            $modelInfo = $this->Common_model->rowResult('tb_model', $modelCondition, $this->dist_id);
                            $data['ModelID'] = $modelInfo->ModelID;
                            $data['Model'] = $model_name;

                            $colorCondition = array('Color' => $color_name);
                            $colorInfo = $this->Common_model->rowResult('tb_color', $colorCondition, $this->dist_id);
                            $data['ColorID'] = $colorInfo->ColorID;
                            $data['Color'] = $color_name;


                            $data['property_1'] = $property_1;
                            $data['property_2'] = $property_2;
                            $productName="";
                            /*if($data['catName']!=""){
                                $productName.=" - ".$data['catName'];
                            }*/
                            if($data['Model']!=""){
                                $productName.=" ".$data['Model'];
                            }
                            if($data['Color']!=""){
                                $productName.=" - ".$data['Color'];
                            }
                            /*if(size!=""){
                                productName+=" - "+size;
                            }*/
                            if($data['brandName']!=""){
                                $productName.=" [ ".$data['brandName']." ]";
                            }
                            //var productName= productCategory+' - '+subcategory+' - '+model+' '+color+' '+size+' [ '+brandId+' ]';

                            $data['productName'] = $productName;

                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                            log_message('error','$storeData'.print_r($data,true));
                        }
                        //endif;
                    endif;
                    $row++;
                }
                log_message('error','$storeData'.print_r($storeData,true));
                exit();
                if (!empty($storeData)):
                    $this->db->truncate('productimport');
                    $this->db->insert_batch('productimport', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/productImport_motorcycle'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productImport_motorcycle'));
                endif;
            } elseif (!empty($_FILES['proImportExcel']['name'])) {
                $this->load->library('excel');
                $this->load->helper('file');
                $file = $_FILES['proImportExcel']['tmp_name'];
                $object = PHPExcel_IOFactory::load($file);
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $cusCondition = array('dist_id' => $this->dist_id);

                        $catId = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()); //get category id
                        $brandId = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //get brand id
                        $unitId = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //get unit id
                        $productId = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); //get product id
                        $purchasPrice = $worksheet->getCellByColumnAndRow(4, $row)->getValue();//get purchases price
                        $retailPrice = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); //get retail price
                        $wholesalePrice = $worksheet->getCellByColumnAndRow(6, $row)->getValue(); //get whole sale price
                        if ($purchasPrice != '' || $retailPrice != '' || $wholesalePrice != '') {
                            $catCondition = array('title' => $catId);
                            $catInfo = $this->Common_model->rowResult('productcategory', $catCondition, $this->dist_id);
                            $data['catId'] = $catInfo->category_id;
                            $data['catName'] = $catId;
                            //brand
                            $brandCondition = array('brandName' => $brandId);
                            $brandInfo = $this->Common_model->rowResult('brand', $brandCondition, $this->dist_id);
                            $data['brandId'] = $brandInfo->brandId;
                            $data['brandName'] = $brandId;
                            //unit
                            $unitCondition = array('unitTtile' => $unitId);
                            $unitInfo = $this->Common_model->rowResult('unit', $unitCondition, $this->dist_id);
                            $data['unitId'] = $unitInfo->unit_id;
                            $data['unitName'] = $unitId;
                            $data['productName'] = $productId;
                            $data['purchasesPrice'] = $purchasPrice;
                            $data['salesPrice'] = $wholesalePrice;
                            $data['retailprice'] = $retailPrice;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }

                    }

                }

                if (!empty($storeData)):
                    $this->db->truncate('productimport');
                    $this->db->insert_batch('productimport', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/productImport'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productImport'));
                endif;
            }

        }
        $data['productList'] = $this->Inventory_Model->getImportProduct($this->dist_id);
        //echo $this->db->last_query();die;
        $data['title'] = 'Product Import';
        if ($this->business_type != "LPG") {
            $data['mainContent'] = $this->load->view('distributor/inventory/import/productImportNonLpgBusiness', $data, true);
        }else{
            $data['mainContent'] = $this->load->view('distributor/inventory/import/productImport', $data, true);
        }

        $this->load->view('distributor/masterTemplate', $data);
    }

    function getImportProductCSVFile()
    {
        $property_1=get_property_list_for_show_hide(1);
        $property_2=get_property_list_for_show_hide(2);
        $property_3=get_property_list_for_show_hide(3);
        $property_4=get_property_list_for_show_hide(4);
        $property_5=get_property_list_for_show_hide(5);
        $ProductSubCategory=get_property_list_for_show_hide(6);
        $Color=get_property_list_for_show_hide(7);
        $Size=get_property_list_for_show_hide(8);
        $ProductSubCategory=='dont_have_this_property'?'Dont need to fill':$ProductSubCategory;
        $Color=='dont_have_this_property'?'Dont need to fill':$Color;
        $Size=='dont_have_this_property'?'Dont need to fill':$Size;
        $property_1=='dont_have_this_property'?'Dont need to fill':$property_1;
        $property_2=='dont_have_this_property'?'Dont need to fill':$property_2;
        $property_3=='dont_have_this_property'?'Dont need to fill':$property_3;
        $property_4=='dont_have_this_property'?'Dont need to fill':$property_4;
        $property_5=='dont_have_this_property'?'Dont need to fill':$property_5;

        $data['customerList'] = $supplierList = $this->Finane_Model->getImportSupplier($this->dist_id);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=product.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('*Product Category', '*product Brand', '*Product Unit', '*Product Name', 'Purchases Price', 'Retail Price', 'Whole Price','Model',$ProductSubCategory,$Color,$Size,$property_1,$property_2,$property_3,$property_4,$property_5));
        fclose($output);
    }

    function deleteImportProduct($deletedId)
    {
        $condition = array(
            'importId' => $deletedId
        );
        $this->Common_model->delete_data_with_condition('productimport', $condition);
        $msg = 'Delete  Successfull';
        $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/productImport'));
    }

    public function setupImport()
    {
        if (isPostBack()) {
            if (!empty($_FILES['supplierImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('supplierImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['supplierImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                $incrementSerial = 1;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $subCondition = array('dist_id' => $this->dist_id);
                        $dynamicId = $this->Common_model->getDynamicId('supplier', $subCondition, 'supID', 'sup_id', 7, $incrementSerial, 'SID');
                        /* check duplicate supplier id end */
                        $data['supID'] = $dynamicId;
                        $data['supName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        $data['supEmail'] = isset($readRowData[1]) ? $readRowData[1] : '';
                        $data['supPhone'] = isset($readRowData[2]) ? $readRowData[2] : '';
                        $data['supAddress'] = isset($readRowData[3]) ? $readRowData[3] : '';
                        $data['dist_id'] = $this->dist_id;
                        $data['updated_by'] = $this->admin_id;
                        if (!empty($data['supID']) && !empty($data['supName'])):
                            $storeData[] = $data; //store each single row;
                            $insertID = $this->Common_model->insert_data('supplier', $data);
                            $condtion = array(
                                'id' => $this->config->item("Supplier_Payables"),//53,
                            );
                            $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                            $condition2 = array(
                                'parent_id' => $this->config->item("Supplier_Payables"),//53,
                            );
                            $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);
                            if (!empty($totalAccount)):
                                $totalAccount = count($totalAccount);
                                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                            else:
                                $totalAdded = 0;
                                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
                            endif;
                            $level_no = $ac_account_ledger_coa_info->level_no;
                            $parent_id = $ac_account_ledger_coa_info->id;
                            $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
                            $dataCoa['code'] = $newCode;
                            $dataCoa['parent_name'] = $readRowData[0] . ' [ ' . $dynamicId . ' ]';
                            $dataCoa['status'] = 1;
                            $dataCoa['posted'] = 1;
                            $dataCoa['level_no'] = $level_no + 1;
                            $dataCoa['related_id'] = $insertID;
                            $dataCoa['related_id_for'] = 2;
                            $dataCoa['insert_by'] = $this->admin_id;
                            $dataCoa['insert_date'] = date('Y-m-d H:i:s');
                            $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
                            for ($x = 0; $x <= 7; $x++) {
                                if ($parent_id != 0) {
                                    $condtion = array(
                                        'id' => $parent_id,
                                    );
                                    $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                                    $parent_id = $parentDetails->parent_id;
                                    $b['id'] = $parentDetails->id;
                                    $b['parent_name'] = $parentDetails->parent_name;
                                    $a[] = $b;
                                }
                            }
                            $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
                            if ($PARENT_ID_DATA->posted != 0) {
                                $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
                            } else {
                                $dataac_tb_coa['CHILD_ID'] = 0;
                            }
                            $a = array_reverse($a);
                            $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
                            $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
                            $Condition = array(
                                'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
                            );
                            $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);
                        endif;
                    endif;
                    $row++;
                    $incrementSerial++;
                }
                if (!empty($storeData)):
                    //$this->db->insert_batch('supplier', $storeData);
                endif;
                if ($this->db->affected_rows() < 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                endif;
            elseif (!empty($_FILES['customerImport']['name']))://supplier list import operation start this block
                $this->db->trans_start();
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('customerImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['customerImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                $incrementSerial = 1;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):

                        $a = array();
                        $b = array();
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $customerName = trim(isset($readRowData[0]) ? $readRowData[0] : '');
                        //$customerName= $customerName ."  #( ". $readRowData[3]." )";
                        $data['root_id'] = 1;
                        $data['customerName'] = $customerName;
                        $data['customerPhone'] = isset($readRowData[1]) ? $readRowData[1] : '';
                        $data['customerEmail'] = isset($readRowData[2]) ? $readRowData[2] : '';
                        $data['customerAddress'] = isset($readRowData[3]) ? $readRowData[3] : '';
                        $data['dist_id'] = $this->dist_id;
                        $data['updated_by'] = $this->admin_id;
                        $data['customerType'] = isset($readRowData[4]) ? $readRowData[4] : 1;
                       // $data['details'] = $readRowData[3] ." -- ".$readRowData[5];

                        $condition = array(
                            'customerName' => $customerName,
                            'customerPhone' => isset($readRowData[1]) ? $readRowData[1] : '',
                        );
                        $exits = $this->Common_model->get_single_data_by_many_columns('customer', $condition);

                        if (!empty($data['customerName']) && empty($exits)):

                            $cusCondition = array('1' => 1);
                            $dynamicId = $this->Common_model->getDynamicId('customer', $cusCondition, 'customerID', 'customer_id', 7, $incrementSerial, 'CID');

                            $data['customerID'] = $dynamicId;

                            $storeData[] = $data; //store each single row;
                            $insertID = $this->Common_model->insert_data('customer', $data);
                            $condtion = array(
                                'id' => $this->config->item("Customer_Receivable"),//33,
                            );
                            $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                            $condition2 = array(
                                'parent_id' => $this->config->item("Customer_Receivable"),//33,
                            );
                            $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);
                            if (!empty($totalAccount)):
                                $totalAccount = count($totalAccount);
                                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                            else:
                                $totalAdded = 0;
                                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
                            endif;
                            $level_no = $ac_account_ledger_coa_info->level_no;
                            $parent_id = $forEmptycylinderId = $ac_account_ledger_coa_info->id;
                            unset($dataCoa);
                            $dataCoa = array();
                            $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
                            $dataCoa['code'] = $newCode;
                            //$dataCoa['parent_name'] = $readRowData[0] . ' [ ' . $dynamicId . ' ]';
                            $dataCoa['parent_name'] = $customerName . ' [ ' . $dynamicId . ' ]';
                            $dataCoa['status'] = 1;
                            $dataCoa['posted'] = 1;
                            $dataCoa['level_no'] = $level_no + 1;
                            $dataCoa['related_id'] = $insertID;
                            $dataCoa['related_id_for'] = 3;
                            $dataCoa['insert_by'] = $this->admin_id;
                            $dataCoa['insert_date'] = date('Y-m-d H:i:s');
                            $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
                            unset($a);
                            unset($b);

                            for ($x = 0; $x <= 7; $x++) {
                                unset($b);
                                if ($parent_id != 0) {
                                    $condtion = array(
                                        'id' => $parent_id,
                                    );
                                    $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                                    $parent_id = $parentDetails->parent_id;
                                    $b['id'] = $parentDetails->id;
                                    $b['parent_name'] = $parentDetails->parent_name;
                                    $a[] = $b;

                                }
                            }

                            $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
                            unset($dataac_tb_coa);
                            $dataac_tb_coa = array();
                            if ($PARENT_ID_DATA->posted != 0) {
                                $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
                            } else {
                                $dataac_tb_coa['CHILD_ID'] = 0;
                            }
                            $a = array_reverse($a);

                            $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
                            $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
                            $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
                            $Condition = array(
                                'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
                            );
                            $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);

                            if ($this->business_type == "LPG") {
                                $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);
                                if (!empty($totalAccount)):
                                    $totalAccount = count($totalAccount);
                                    $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                                else:
                                    $totalAdded = 0;
                                    $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
                                endif;
                                unset($dataCoa);
                                $dataCoa = array();
                                $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
                                $dataCoa['code'] = $newCode;
                                $dataCoa['parent_name'] = $customerName . ' [ ' . $dynamicId . ' ]  Empty Cylinder Due';
                                $dataCoa['status'] = 1;
                                $dataCoa['posted'] = 1;
                                $dataCoa['level_no'] = $level_no + 1;
                                $dataCoa['related_id'] = $insertID;
                                $dataCoa['related_id_for'] = 4;
                                $dataCoa['insert_by'] = $this->admin_id;
                                $dataCoa['insert_date'] = date('Y-m-d H:i:s');
                                $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
                                unset($a);
                                $a = array();
                                unset($b);
                                $b = array();
                                for ($x = 0; $x <= 7; $x++) {
                                    unset($b);
                                    if ($forEmptycylinderId != 0) {
                                        $condtion = array(
                                            'id' => $forEmptycylinderId,
                                        );
                                        $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                                        $forEmptycylinderId = $parentDetails->parent_id;
                                        $b['id'] = $parentDetails->id;
                                        $b['parent_name'] = $parentDetails->parent_name;
                                        $a[] = $b;

                                    }

                                }


                                unset($dataac_tb_coa);
                                $dataac_tb_coa = array();

                                $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
                                if ($PARENT_ID_DATA->posted != 0) {
                                    $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
                                } else {
                                    $dataac_tb_coa['CHILD_ID'] = 0;
                                }
                                $a = array_reverse($a);
                                $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
                                $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
                                $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
                                $Condition = array(
                                    'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
                                );
                                $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);
                            }
                        endif;
                        $incrementSerial++;
                    endif;
                    $row++;
                }

                $this->db->trans_complete();
                if (!empty($storeData)):
                    //$this->db->insert_batch('customer', $storeData);
                endif;
                if ($this->db->trans_status() === TRUE):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                else:

                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                endif;
            elseif (!empty($_FILES['proCatImport']['name']))://product cat list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proCatImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proCatImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //dumpVar($readRowData);
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'title' => $readRowData[0],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('productcategory', $condition);
                        if (empty($exitsID)):
                            $data['title'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        /* check duplicate supplier id end */
                        if (!empty($data['title'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('productcategory', $storeData);
                endif;
                if ($this->db->affected_rows() < 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                endif;
            elseif (!empty($_FILES['proImport']['name']))://product list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'product_code' => $readRowData[3],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('product', $condition);
                        if (empty($exitsID)):
                            $data['product_code'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        else:
                            $proID = $this->Common_model->getProID($this->dist_id);
                            $data['product_code'] = $this->Common_model->checkDuplicateProID($proID, $this->dist_id);
                        endif;
                        /* check duplicate supplier id end */
                        $data['brand_id'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        $data['category_id'] = isset($readRowData[1]) ? $readRowData[1] : '';
                        $data['productName'] = isset($readRowData[2]) ? $readRowData[2] : '';
                        $data['product_code'] = isset($readRowData[3]) ? $readRowData[3] : '';
                        $data['purchases_price'] = isset($readRowData[4]) ? $readRowData[4] : '';
                        $data['salesPrice'] = isset($readRowData[5]) ? $readRowData[5] : '';
                        $data['retailPrice'] = isset($readRowData[6]) ? $readRowData[6] : '';
                        $data['dist_id'] = $this->dist_id;
                        $data['updated_by'] = $this->admin_id;
                        if (!empty($data['product_code']) && !empty($data['productName'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('product', $storeData);
                endif;
                if ($this->db->affected_rows() < 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                endif;
            elseif (!empty($_FILES['BrandImport']['name']))://product cat list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('BrandImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['BrandImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //dumpVar($readRowData);
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'brandName' => $readRowData[0],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('brand', $condition);
                        if (empty($exitsID)):
                            $data['brandName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        /* check duplicate supplier id end */
                        if (!empty($data['brandName'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('brand', $storeData);
                endif;
                if ($this->db->affected_rows() < 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                endif;
            elseif (!empty($_FILES['UnitImport']['name']))://product cat list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('UnitImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['UnitImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //dumpVar($readRowData);
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'unitTtile' => $readRowData[0],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('unit', $condition);
                        if (empty($exitsID)):
                            $data['unitTtile'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        /* check duplicate supplier id end */
                        if (!empty($data['unitTtile'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('unit', $storeData);
                endif;
                if ($this->db->affected_rows() < 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                endif;
            elseif (!empty($_FILES['referenceImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('referenceImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['referenceImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'referencePhone' => $readRowData[1],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('reference', $condition);
                        if (empty($exitsID)):
                            $data['referenceName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            /* check duplicate supplier id end */
                            $data['referencePhone'] = isset($readRowData[1]) ? $readRowData[1] : '';
                            $data['referenceEmail'] = isset($readRowData[2]) ? $readRowData[2] : '';
                            $data['referenceAddress'] = isset($readRowData[3]) ? $readRowData[3] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        if (!empty($data['referenceName']) && !empty($data['referencePhone'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('reference', $storeData);
                endif;
                if ($this->db->affected_rows() < 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/setupImport/'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/setupImport'));
                endif;
            endif;
        }
        $data['title'] = 'Import-Setup';
        $data['mainContent'] = $this->load->view('distributor/inventory/import/setup', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function upload_data($param1 = '')
    {
        if ($param1 == 'do_upload') {
            $config['upload_path'] = './uploads/attendance_file/';
            $config['allowed_types'] = 'xl|txt|csv|mdb';
            $config['file_name'] = date("Y-m-d");
            $this->load->library('upload');
            $this->upload->initialize($config);
            $upload = $this->upload->do_upload('attendance_file');
            $data = $this->upload->data();
            $this->load->helper('file');
            $file = fopen($data['full_path'], "r");
            $file_date = $data['client_name'];
            $file_date = substr($file_date, 3, 8);
            $line = FALSE;
            $att_data = array();
            $file = $_FILES['attendance_file']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
//
//                echo "<pre>";
//                print_r($filesop);
                $count_value = count($filesop);
                if ($count_value == '42') {
                    $punch_card = str_replace("'", "", trim($filesop[25]));
                    $date = substr($filesop[37], 1, 10);
                    $time = str_replace("'", "", substr($filesop[22], 0, 6));
                    //$time = date('h:m', strtotime($time2));
//                    echo $time;
//                    echo "<br>";
                    if ($time and $punch_card) {
                        $att_data[] = array('date' => $date, 'time' => $time, 'punch_card' => $punch_card);
                    }
                }
            }
            $this->db->where('date', $date);
            $already_exit = $this->db->get('punch_info2')->row_array();
//
//          die;
//            echo "<pre>";
//            print_r($att_data);
//            die;
            if (empty($already_exit)) {
                $this->db->insert_batch('punch_info2', $att_data);
            }
            //$this->session->set_flashdata('success', 'File uploaded.');
            $query = $this->db->get('punch_info2');
            $all_punch_info = $query->result_array();
            if (!empty($all_punch_info)) {
                foreach ($all_punch_info as $each_punch_info):
                    $this->db->select_min('time');
                    $this->db->where('date', $each_punch_info['date']);
                    $this->db->where('punch_card', $each_punch_info['punch_card	']);
                    $intime = $this->db->get('punch_info')->row_array();
                    $this->db->select_max('time');
                    $this->db->where('date', $each_punch_info['date']);
                    $this->db->where('punch_card', $each_punch_info['punch_card	']);
                    $outtime = $this->db->get('punch_info')->row_array();
                    $date2 = $date = $each_punch_info['date'];
                    $punch_card2 = $each_punch_info['punch_card	'];
                    $intime2 = $intime['time'];
                    $outtime2 = $outtime['time'];
                    $query = $this->db->get_where('punch_info', array('punch_card' => $each_punch_info['punch_card'], 'date' => $each_punch_info['date']));
                    $already_insert = $query->result_array();
                    if (empty($already_insert)) {
                        if ($intime2 and $punch_card)
                            $att_data[] = array('date' => $date2, 'time' => $intime2, 'punch_card' => $punch_card2);
                        if ($outtime2 and $punch_card)
                            $att_data[] = array('date' => $date2, 'time' => $outtime2, 'punch_card' => $punch_card2);
                        $this->db->insert_batch('punch_info', $att_data);
                    }
                endforeach;
            }
            $this->session->set_flashdata('success', 'File uploaded.');
        }
        $this->defaults['page_title'] = translate('upload_data');
        $this->defaults['page_name'] = 'upload_data';
        $this->load->view('index', $this->defaults);
    }

    function employeeImport()
    {
        if (isPostBack()) {

            if (!empty($_FILES['proImport']['name'])) {//supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . '_ProductImport' . date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //if ($row != 0):
                    if ($row != 0) {
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $nameId = isset($readRowData[0]) ? $readRowData[0] : ''; //get name id
                        $employeeId = isset($readRowData[1]) ? $readRowData[1] : '';
                        $fathersName = isset($readRowData[2]) ? $readRowData[2] : '';
                        $mothersName = isset($readRowData[3]) ? $readRowData[3] : '';
                        $presentAddress = isset($readRowData[4]) ? $readRowData[4] : '';
                        $permanentAddress = isset($readRowData[5]) ? $readRowData[5] : '';
                        $dateOfBirth = isset($readRowData[6]) ? $readRowData[6] : '';
                        $nationalId = isset($readRowData[7]) ? $readRowData[7] : '';
                        $personalMobile = isset($readRowData[8]) ? $readRowData[8] : '';
                        $joiningDate = isset($readRowData[9]) ? $readRowData[9] : '';
                        $salary = isset($readRowData[10]) ? $readRowData[10] : '';
                        $education = isset($readRowData[11]) ? $readRowData[11] : '';
                        $department = isset($readRowData[12]) ? $readRowData[12] : ''; //get brand id
                        $designation = isset($readRowData[13]) ? $readRowData[13] : ''; //get unit id
                        /* check duplicate supplier id end */
                        //product Category
                        if ($nameId != '' || $salary != '' || $presentAddress != '') {

                            $data['name'] = $nameId;
                            $data['employeeId'] = $employeeId;
                            $data['fathersName'] = $fathersName;
                            $data['mothersName'] = $mothersName;
                            $data['presentAddress'] = $presentAddress;
                            $data['permanentAddress'] = $permanentAddress;
                            $data['dateOfBirth'] = $dateOfBirth;
                            $data['nationalId'] = $nationalId;
                            $data['joiningDate'] = $joiningDate;
                            $data['personalMobile'] = $personalMobile;
                            $data['salary'] = $salary;
                            $data['education'] = $education;
                            $deptCondition = array('DepartmentName' => $department);
                            $deptInfo = $this->Common_model->rowResult('tb_department', $deptCondition, $this->dist_id);
                            $data['department'] = $deptInfo->DepartmentID;
                            $data['deptName'] = $department;

                            $desiCondition = array('DesignationName' => $designation);
                            $desiInfo = $this->Common_model->rowResult('tb_designation', $desiCondition, $this->dist_id);
                            $data['designation'] = $desiInfo->DesignationID;
                            $data['desiName'] = $designation;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }
                        //endif;
                    }
                    $row++;
                }
                if (!empty($storeData)) {
                    $this->db->truncate('employeeimport');
                    $this->db->insert_batch('employeeimport', $storeData);
                }
                if ($this->db->affected_rows() > 0) {
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                } else {
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                }
            } elseif (!empty($_FILES['employeeImportExcel']['name'])) {//supplier list import operation sta


                $this->load->library('excel');
                $this->load->helper('file');
                $file = $_FILES['employeeImportExcel']['tmp_name'];
                $object = PHPExcel_IOFactory::load($file);
                $storeData = array();

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    //if ($row != 0):

                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {

                        $condition = array(
                            'employeeId' => trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()),
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('employeeimport', $condition);
                        if (empty($exitsID)) {

                            $nameId = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()); //get name id
                            $employeeId = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                            $fathersName = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                            $mothersName = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                            $presentAddress = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                            $permanentAddress = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                            $dateOf = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $dateOfBir = PHPExcel_Shared_Date::ExcelToPHP($dateOf);
                            $dateOfBirth = date($format = "Y-m-d", $dateOfBir);
                            $nationalId = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                            $personalMobile = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                            $joining = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $joiningDat = PHPExcel_Shared_Date::ExcelToPHP($joining);
                            $joiningDate = date($format = "Y-m-d", $joiningDat);
                            $salary = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                            $education = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                            $department = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue()); //get brand id
                            $designation = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                            //       echo "<pre>";
                            // print_r($designation);
                            // exit();
                            //get unit id
                            /* check duplicate supplier id end */
                            //product Category
                            if ($nameId != '' || $salary != '' || $presentAddress != '') {

                                $data['name'] = $nameId;
                                $data['employeeId'] = $employeeId;
                                $data['fathersName'] = $fathersName;
                                $data['mothersName'] = $mothersName;
                                $data['presentAddress'] = $presentAddress;
                                $data['permanentAddress'] = $permanentAddress;
                                $data['dateOfBirth'] = $dateOfBirth;
                                $data['nationalId'] = $nationalId;
                                $data['joiningDate'] = $joiningDate;
                                $data['personalMobile'] = $personalMobile;
                                $data['salary'] = $salary;
                                $data['education'] = $education;
                                $deptCondition = array('DepartmentName' => $department);
                                $deptInfo = $this->Common_model->rowResult('tb_department', $deptCondition, $this->dist_id);
                                $data['department'] = $deptInfo->DepartmentID;
                                $data['deptName'] = $department;

                                $desiCondition = array('DesignationName' => $designation);
                                $desiInfo = $this->Common_model->rowResult('tb_designation', $desiCondition, $this->dist_id);
                                $data['designation'] = $desiInfo->DesignationID;
                                $data['desiName'] = $designation;
                                $data['dist_id'] = $this->dist_id;
                                //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                                $storeData[] = $data; //store each single row;
                            }
                        }

                    }
                }
                //     echo "<pre>";
                // print_r($storeData);
                // exit();
                if (!empty($storeData)) {
                    $this->db->truncate('employeeimport');
                    $this->db->insert_batch('employeeimport', $storeData);
                }
                if ($this->db->affected_rows() > 0) {
                    $msg = 'Your  file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                } else {
                    $msg = 'Your  file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                }
            }

        }

        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project . '/employeeList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['emplyeeList'] = $this->Inventory_Model->getImportEmployee($this->dist_id);
        $data['emplyeeListSameId'] = $this->Inventory_Model->emplyeeListSameId($this->dist_id);
        //echo $this->db->last_query();die;
        $data['title'] = 'Employee Import';
        $data['mainContent'] = $this->load->view('distributor/inventory/import/employeeImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function saveImportEmplloyee()
    {
        $this->db->trans_start();
        $allResult = $this->Common_model->get_data_list('employeeimport');
        $allData = array();
        $productOrgId = $this->db->select('*')->count_all_results('employee');

        foreach ($allResult as $key => $value) {
            $result = $this->Common_model->checkDuplicateEmployee($this->dist_id, $value->employeeId);
            if (empty($result)) {
                $data = array();
                $data['name'] = $value->name;
                $data['employeeId'] = $value->employeeId;
                $data['fathersName'] = $value->fathersName;
                $data['mothersName'] = $value->mothersName;
                $data['presentAddress'] = $value->presentAddress;
                $data['permanentAddress'] = $value->permanentAddress;
                $data['dateOfBirth'] = $value->dateOfBirth;
                $data['nationalId'] = $value->nationalId;
                $data['personalMobile'] = $value->personalMobile;
                $data['joiningDate'] = $value->joiningDate;
                $data['salary'] = $value->salary;
                $data['education'] = $value->education;
                $data['department'] = $value->department;
                $data['designation'] = $value->designation;
                $data['createdBy'] = $this->admin_id;
                $data['isActive'] = 'Y';
                $data['isDelete'] = 'N';
                $data['empStatus'] = 'Active';
                $data['dist_id'] = $this->dist_id;
                $allData[] = $data;


            }

        }
        //     echo "<pre>";
        // print_r($storeData);
        // exit();
        if (!empty($allData)) {
            $this->db->insert_batch('employee', $allData);
        }
        $this->db->truncate('employeeimport');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/employeeImport'));
        else:
            $msg = $this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/employeeImport'));
        endif;
    }

    function deleteImportEmployee($deletedId)
    {
        $condition = array(
            'importid' => $deletedId
        );
        $this->Common_model->delete_data_with_condition('employeeimport', $condition);
        $msg = 'Delete  Successfull';
        $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/employeeImport'));
    }
}
