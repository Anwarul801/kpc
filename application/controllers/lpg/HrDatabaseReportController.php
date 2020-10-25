<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HrDatabaseReportController extends CI_Controller {

    private $timestamp;
    public $admin_id;
    private $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }


 public function hrDatabaseReport(){
         if (isPostBack()) {


            //echo '<pre>';
            //print_r($_POST);exit;
            $dist_id = $this->dist_id;
            $department = $this->input->post('department');
            $designation = $this->input->post('designation');
            $employee = $this->input->post('employee');
             $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
            $data['getAllEmployeewiseD'] = $this->Common_model->getAllEmployee($employee,$department,$designation);
            $data['employeefield'] = $this->db->where('isShow', '1')->get('employeelabelfield')->result();
            // echo "<pre>";
            // print_r($data['getAllEmployeewiseD']);
            // print_r($data['getAllEmployeewiseD']);
            // echo $this->db->last_query();exit;

            if ($this->input->post('is_print') == 1) {
                $footer = '';
                $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
                $footer1 = '<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>

<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>

<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>

<td width="33%" style="text-align: right; ">My document</td>

</tr></table>';
                $output_type = '';
                $header = '<table class="table table-responsive">
                    <tr>
                    <td style="text-align:center;"><h3>' . $companyInfo->companyName . '</h3><span>' . $companyInfo->address . '</span><br><strong>' . get_phrase('Phone') . ': </strong>' . $companyInfo->phone . '<br><strong>' . get_phrase('Email') . ': </strong>' . $companyInfo->email . '<br><strong>' . get_phrase('Website') . ': </strong>' . $companyInfo->website . '<br><strong>' . 'Employee Database Report
' . '</strong><strong> ' . get_phrase('') . ' :</strong>From ' . $from_date . ' To ' . $to_date . '</td></tr></table>';
                $this->load->library('tec_mpdf', '', 'pdf');
                //$header="This is hadder";
                $content = $this->load->view('distributor/setup/employee/report/employeeReportPdf', $data, true);
                $this->pdf->generate($content, $name = 'download.pdf', $output_type, $footer, $margin_bottom = null, $header, $margin_top = '40', $orientation = 'l');
            }


          
        }
        $data['title'] = get_phrase('HR Darabase Report');
        $data['page_type'] = get_phrase($this->page_type);
       // $data['link_page_name'] = get_phrase('HR Darabase Report');
      //  $data['getAllEmployeewiseD'] = $this->Common_model->getAllEmployeewiseD();
        $data['third_link_page_name'] = get_phrase('Configuration');
        $data['third_link_page_url'] = $this->project.'/employeeConfiquration';
        $data['third_link_icon'] = "<i class='fa fa-plus'></i>";
        $data['employee'] = $this->Common_model->get_data_list('employee', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
       $data['employee'] = $this->Common_model->get_employee();
      
       
        $data['mainContent'] = $this->load->view('distributor/setup/employee/report/employeeReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
   

    public function employeeAdd() {


        if (isPostBack()) {

         $this->form_validation->set_rules('employeeId', 'employeeId', 'trim|required|is_unique[employee.employeeId]');
         if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Employee Already Exsist ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeAdd/'));
            } else {
            $this->db->trans_start();
            $profileName = $_FILES['profile']['name'];
            if (!empty($profileName)) {
                $profilename1 = explode(".", $profileName);
                $extension = end($profilename1);
                $base_name = $profilename1[0];
                if (file_exists("uploads/employee/" . $_FILES['profile']['name'])) {
                    $profileName = $base_name . "_" . time() . "." . $extension;
                }
                move_uploaded_file($_FILES['profile']['tmp_name'], "uploads/employee/" . $profileName);
                $data['profile'] = $profileName;
            } else {
                $data['profile'] = '';
            }
            $cvName = $_FILES['cv']['name'];
            if (!empty($cvName)) {
                $cvname1 = explode(".", $cvName);
                $extension = end($cvname1);
                $base_name = $cvname1[0];
                if (file_exists("uploads/employee/cv/" . $_FILES['cv']['name'])) {
                    $cvName = $base_name . "_" . time() . "." . $extension;
                }
                move_uploaded_file($_FILES['cv']['tmp_name'], "uploads/employee/cv/" . $cvName);
                $data['cv'] = $cvName;
            } else {
                $data['cv'] = '';
            }
            $data['name'] = $this->input->post('name');
            $data['employeeId'] = $this->input->post('employeeId');
            $data['fathersName'] = $this->input->post('fathersName');
            $data['mothersName'] = $this->input->post('mothersName');
            $data['presentAddress'] = $this->input->post('presentAddress');
            $data['permanentAddress'] = $this->input->post('permanentAddress');
            $data['dateOfBirth'] = date('d-m-Y',  strtotime($this->input->post('dateOfBirth')));
            $data['gender'] = $this->input->post('gender');
            $data['nationalId'] = $this->input->post('nationalId');
            $data['religion'] = $this->input->post('religion');
            $data['emailAddress'] = $this->input->post('emailAddress');
            $data['homeDistrict'] = $this->input->post('homeDistrict');
            $data['personalMobile'] = $this->input->post('personalMobile');
            $data['maritalStatus'] = $this->input->post('maritalStatus');
            $data['officeMobile'] = $this->input->post('officeMobile');
            $data['bloodGroup'] = $this->input->post('bloodGroup');
            $data['joiningDate'] =date('d-m-Y',  strtotime($this->input->post('joiningDate')));
            $data['employeeType'] = $this->input->post('employeeType');
            $data['salary'] = $this->input->post('salary');
            $data['empStatus'] = $this->input->post('empStatus');
            $data['spouseName'] = $this->input->post('spouseName');
            $data['spouseNumber'] = $this->input->post('spouseNumber');
            $data['res'] = $this->input->post('res');
            $data['education'] = $this->input->post('education');
            $data['department'] = $this->input->post('department');
            $data['designation'] = $this->input->post('designation');
            $data['emargencyContact'] = $this->input->post('emargencyContact');
            $data['others'] = $this->input->post('others');
            $data['AccountNo'] = $this->input->post('AccountNo');
            $data['salaryType'] = $this->input->post('salaryType');
            $data['createdBy'] = $this->admin_id;
            $data['isActive'] = 'Y';
            $data['isDelete'] = 'N';
            $data['dist_id'] = $this->dist_id;
            $this->Common_model->insert_data('employee', $data);

            $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Employee ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeList'));
                } else {
                    $msg = 'Employee ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeList'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Add';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project.'/employeeList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['bloodGroup'] = $this->Common_model->get_data_list('blood_group', 'bloodName', 'ASC');
        $data['mainContent'] = $this->load->view('distributor/setup/employee/addNewEmployee', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

     public function salaryVoucherReport(){
        $data['title'] = get_phrase('Salary Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Salary Voucher');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Field Configuration');
        $data['second_link_page_url'] = $this->project.'/employeeConfiquration/';
        $data['second_link_icon'] = "<i class='fa fa-list'></i>";
        
        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryVoucherReport();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryVoucherReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
   



}
