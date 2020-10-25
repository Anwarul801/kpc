<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 10/30/2019
 * Time: 10:59 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class DepartmentSummaryReport extends CI_Controller
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
        $this->load->model('MyModel');
        $this->load->model('OverTimePayemt_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('AccountReport_model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'Accounts';
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


    public function departmentWiseSummeryReport()
    {
        if (isPostBack()) {


            //echo '<pre>';
            //print_r($_POST);exit;
            $dist_id = $this->dist_id;
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
            $data['getAllEmployeewiseD'] = $this->Common_model->getMonthlySummery($month, $year);
            $all_view = array();
            foreach ($data['getAllEmployeewiseD'] as $key => $value) {
                //$data['getAllEmployeewiseD'][$key];
                $month_mane = "month_" . $value->month;
                $value->$month_mane = $value->month;
                $all_view[$value->DepartmentName][$value->month] = $value->month;
                $all_view[$value->DepartmentName][$value->month . "_amount"] = $value->dep;

                if ($value->month == 'January') {
                    $data['JanuaryTotal'] += $value->dep;
                }
                if ($value->month == 'February') {
                    $data['FebruaryTotal'] += $value->dep;
                }
                if ($value->month == 'March') {
                    $data['MarchTotal'] += $value->dep;
                }
                if ($value->month == 'April') {
                    $data['AprilTotal'] += $value->dep;
                }
                if ($value->month == 'May') {
                    $data['MayTotal'] += $value->dep;
                }
                if ($value->month == 'June') {
                    $data['JuneTotal'] += $value->dep;
                }
                if ($value->month == 'July') {
                    $data['JulyTotal'] += $value->dep;
                }
                if ($value->month == 'August') {
                    $data['AugustTotal'] += $value->dep;
                }
                if ($value->month == 'September') {
                    $data['SeptemberTotal'] += $value->dep;
                }
                if ($value->month == 'October') {
                    $data['OctoberTotal'] += $value->dep;
                }
                if ($value->month == 'November') {
                    $data['NovemberTotal'] += $value->dep;
                }
                if ($value->month == 'December') {
                    $data['DecemberTotal'] += $value->dep;
                }

            }
            $data['all_view'] = $all_view;
            /* echo "<pre>";
             print_r($all_view);
             print_r($data);

             exit;*/


        }
        $data['title'] = get_phrase('Department Wise Monthly Summery');
        $data['page_type'] = get_phrase($this->page_type);
        // $data['link_page_name'] = get_phrase('HR Darabase Report');
        //  $data['getAllEmployeewiseD'] = $this->Common_model->getAllEmployeewiseD();
        // $data['third_link_page_name'] = get_phrase('Configuration');
        // $data['third_link_page_url'] = $this->project.'/employeeConfiquration';
        // $data['third_link_icon'] = "<i class='fa fa-plus'></i>";
        $data['employee'] = $this->Common_model->get_data_list('employee', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/report/employeeSummeryReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


}