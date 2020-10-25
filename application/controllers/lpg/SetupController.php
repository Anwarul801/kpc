<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SetupController extends CI_Controller
{

    private $timestamp;
    public $admin_id;
    private $dist_id;
    public $project;


    public $business_type;
    public $folder;
    public $folderSub;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('EmployeeSalary_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->business_type = $this->session->userdata('business_type');
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);


        if ($this->business_type == "MOBILE") {
            $this->folder = 'distributor/masterTemplateSmeMobile';

            $this->folderSub = 'distributor/setup_mobile/';
        } else {
            $this->folder = 'distributor/setup';
        }

    }

    public function employeeList() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );


        /*page navbar details*/
        $data['title'] = get_phrase('Employee List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Add');
        $data['link_page_url'] = $this->project.'/employeeAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/
        $data['getAllEmployeewiseD'] = $this->Common_model->getAllEmployeewiseD();
        //echo $this->db->last_query();exit;
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeLisst', $data, true);
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

    function employeeEdit($editId) {

        if (isPostBack()) {

            $this->form_validation->set_rules('employeeId', 'employeeId', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
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
                    $data['profile'] = $this->input->post('oldImage');
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
                    $data['cv'] = $this->input->post('oldcv');
                }
                $data['name'] = $this->input->post('name');
                $data['employeeId'] = $this->input->post('employeeId');
                $data['fathersName'] = $this->input->post('fathersName');
                $data['mothersName'] = $this->input->post('mothersName');
                $data['presentAddress'] = $this->input->post('presentAddress');
                $data['permanentAddress'] = $this->input->post('permanentAddress');
                $data['dateOfBirth'] = date('d-m-Y',strtotime($this->input->post('dateOfBirth')));
                $data['gender'] = $this->input->post('gender');
                $data['nationalId'] = $this->input->post('nationalId');
                $data['religion'] = $this->input->post('religion');
                $data['emailAddress'] = $this->input->post('emailAddress');
                $data['homeDistrict'] = $this->input->post('homeDistrict');
                $data['personalMobile'] = $this->input->post('personalMobile');
                $data['maritalStatus'] = $this->input->post('maritalStatus');
                $data['officeMobile'] = $this->input->post('officeMobile');
                $data['bloodGroup'] = $this->input->post('bloodGroup');
                $data['joiningDate'] = date('d-m-Y',  strtotime($this->input->post('joiningDate')));
                $data['employeeType'] = $this->input->post('employeeType');
                $data['salary'] = $this->input->post('salary');
                $data['empStatus'] = $this->input->post('empStatus');
                $data['createdBy'] = $this->admin_id;
                $data['isActive'] = 'Y';
                $data['isDelete'] = 'N';
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
                $data['dist_id'] = $this->dist_id;
                $this->Common_model->update_data('employee', $data,'id',$editId);
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

        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project.'/employeeList';
        $data['title'] = 'Employee Edit';
        $data['title'] = get_phrase('Employee Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['editEmp'] = $this->Common_model->get_single_data_by_single_column('employee', 'id',$editId);
        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['bloodGroup'] = $this->Common_model->get_data_list('blood_group', 'bloodName', 'ASC');
        $data['mainContent'] = $this->load->view('distributor/setup/employee/editEmployee', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function employeeSalaryList() {
        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );


        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/

        $data['salaryList'] = $this->Common_model->getAllsalaryList();
        $data['getAllsalaryByDateList'] = $this->Common_model->getAllsalaryByDateList();
        $data['employeewisedep'] = $this->Common_model->getAllEmployeewiseD();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeSalaryList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function employeeSalaryAdd() {
        $this->load->helper('payroll_voucher_no_helper');
        if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('voucherid', 'voucherid', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');

            $this->form_validation->set_rules('month', 'Month Name', 'required');


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
            } else {


                $this->db->trans_start();
                $employeeSalary_details = array();
                if (!empty($_POST['employeeCheckBox'])) {
                    foreach ($_POST['employeeCheckBox'] as $key => $value) {
                        $data = array();
                        $data['date'] = date('d-m-Y',  strtotime($this->input->post('date')));
                        $data['month'] = $_POST['month'];
                        $data['year'] = $_POST['year'];
                        $data['employeeID'] = $_POST['employeeID'][$key];
                        $data['designation'] = $_POST['designation'][$key];
                        $data['departmentID'] = $_POST['departmentID'][$key];
                        $data['paymentMode'] = $_POST['paymentMode'][$key];
                        $data['basicSalary'] = $_POST['basicSalary'][$key];
                        $data['houseRant'] = $_POST['houseRant'][$key];
                        $data['conveyanceAllowance'] = $_POST['conveyanceAllowance'][$key];
                        $data['medicalAllowance'] = $_POST['medicalAllowance'][$key];
                        $data['others'] = $_POST['others'][$key];
                        $data['wPFDeduction'] = $_POST['wPFDeduction'][$key];
                        $data['grossSalary'] = $_POST['grossSalary'][$key];
                        $data['arrearSalary'] = $_POST['arrearSalary'][$key];
                        $data['absentDeduction'] = $_POST['absentDeduction'][$key];
                        $data['loanDeduction'] = $_POST['loanDeduction'][$key];
                        $data['aITDeduction'] = $_POST['aITDeduction'][$key];
                        $data['netPayAmount'] = $_POST['netPayAmount'][$key];
                        $data['comment'] = $_POST['comment'][$key];
                        $data['createdBy'] = $this->admin_id;
                        $data['isActive'] = 'Y';
                        $data['isDelete'] = 'N';
                        $data['narration'] = $this->input->post('narration');
                        $data['voucherType'] = '1';
                        $data['voucherNo'] = $this->input->post('voucherid');
                        $employeeSalary_details[] = $data;

                    }
                }
                // echo "<pre>";
                // print_r($_POST);
                // exit();


                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Salary ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                } else {
                    $msg = 'Payroll Voucher Save ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );



        $data['voucherID'] = payroll_voucher_no();

        $data['title'] = 'Payroll Voucher';

        /*page navbar details*/
        $data['title'] = get_phrase('Payroll Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary List');
        $data['link_page_url'] = $this->project.'/employeeSalaryList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/

        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->Common_model->get_employee();

        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/addEmployeeSalary', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function salaryVoucherReport(){
        $data['title'] = get_phrase('Salary Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Salary Voucher');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Field Configuration');
        $data['second_link_page_url'] = $this->project.'/employeeSalaryConfiquration/';
        $data['second_link_icon'] = "<i class='fa fa-list'></i>";
        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();
        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryVoucherReport();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryVoucherReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    public function attendenceReport(){
        $data['title'] = get_phrase('Attendence Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Attendence Report');

        $data['mainContent'] = $this->load->view('distributor/setup/employee/report/attendenceReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    public function hrPerformanceReport(){
        $data['title'] = get_phrase(' Performance Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Attendence Report');

        $data['mainContent'] = $this->load->view('distributor/setup/employee/report/attendenceReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    public function salaryEdit($date,$month,$year) {

        if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');


            $this->form_validation->set_rules('month', 'Month Name', 'required');
            $this->Common_model->salaryInfoDelete($date,$month,$year);


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
            } else {

                $this->Common_model->salaryInfoDelete($date,$month,$year);
                $this->db->trans_start();
                $employeeSalary_details = array();
                if (!empty($_POST['employeeCheckBox'])) {
                    foreach ($_POST['employeeCheckBox'] as $key => $value) {
                        $data = array();
                        $data['date'] = date('d-m-Y',  strtotime($this->input->post('date')));
                        $data['month'] = $_POST['month'];
                        $data['year'] = $_POST['year'];
                        $data['employeeID'] = $_POST['employeeID'][$key];
                        $data['designation'] = $_POST['designation'][$key];
                        $data['departmentID'] = $_POST['departmentID'][$key];
                        $data['paymentMode'] = $_POST['paymentMode'][$key];
                        $data['basicSalary'] = $_POST['basicSalary'][$key];
                        $data['houseRant'] = $_POST['houseRant'][$key];
                        $data['conveyanceAllowance'] = $_POST['conveyanceAllowance'][$key];
                        $data['medicalAllowance'] = $_POST['medicalAllowance'][$key];
                        $data['others'] = $_POST['others'][$key];
                        $data['wPFDeduction'] = $_POST['wPFDeduction'][$key];
                        $data['grossSalary'] = $_POST['grossSalary'][$key];
                        $data['arrearSalary'] = $_POST['arrearSalary'][$key];
                        $data['absentDeduction'] = $_POST['absentDeduction'][$key];
                        $data['loanDeduction'] = $_POST['loanDeduction'][$key];
                        $data['aITDeduction'] = $_POST['aITDeduction'][$key];
                        $data['netPayAmount'] = $_POST['netPayAmount'][$key];
                        $data['comment'] = $_POST['comment'][$key];
                        $data['createdBy'] = $this->admin_id;
                        $data['isActive'] = 'Y';
                        $data['isDelete'] = 'N';
                        $data['narration'] = $this->input->post('narration');
                        $data['voucherNo'] = $voucher;
                        $data['voucherType'] = '1';
                        $employeeSalary_details[] = $data;

                    }
                }


                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Salary ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                } else {
                    $msg = 'Salary Save ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Salary Edit';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary Preview');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary List');
        $data['link_page_url'] = $this->project.'/employeeSalaryList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/
        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListView($date,$month,$year);
        //$data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByVoucher($voucher);
        //         echo '<pre>';
        // echo $this->db->last_query();

        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->Common_model->get_employee();

        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/SalaryEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function salaryEditByVoucher($voucher) {

        if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');


            $this->form_validation->set_rules('month', 'Month Name', 'required');
            $this->EmployeeSalary_Model->getAllsalaryByVoucher($voucher);


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryList/'));
            } else {

                $this->EmployeeSalary_Model->festivalVoucherDelete($voucher);
                $this->db->trans_start();
                $employeeSalary_details = array();
                if (!empty($_POST['employeeCheckBox'])) {
                    foreach ($_POST['employeeCheckBox'] as $key => $value) {
                        $data = array();
                        $data['date'] = date('d-m-Y',  strtotime($this->input->post('date')));
                        $data['month'] = $_POST['month'];
                        $data['year'] = $_POST['year'];
                        $data['employeeID'] = $_POST['employeeID'][$key];
                        $data['designation'] = $_POST['designation'][$key];
                        $data['departmentID'] = $_POST['departmentID'][$key];
                        $data['paymentMode'] = $_POST['paymentMode'][$key];
                        $data['basicSalary'] = $_POST['basicSalary'][$key];
                        $data['houseRant'] = $_POST['houseRant'][$key];
                        $data['conveyanceAllowance'] = $_POST['conveyanceAllowance'][$key];
                        $data['medicalAllowance'] = $_POST['medicalAllowance'][$key];
                        $data['others'] = $_POST['others'][$key];
                        $data['wPFDeduction'] = $_POST['wPFDeduction'][$key];
                        $data['grossSalary'] = $_POST['grossSalary'][$key];
                        $data['arrearSalary'] = $_POST['arrearSalary'][$key];
                        $data['absentDeduction'] = $_POST['absentDeduction'][$key];
                        $data['loanDeduction'] = $_POST['loanDeduction'][$key];
                        $data['aITDeduction'] = $_POST['aITDeduction'][$key];
                        $data['netPayAmount'] = $_POST['netPayAmount'][$key];
                        $data['comment'] = $_POST['comment'][$key];
                        $data['createdBy'] = $this->admin_id;
                        $data['isActive'] = 'Y';
                        $data['isDelete'] = 'N';
                        $data['narration'] = $this->input->post('narration');
                        $data['voucherNo'] = $voucher;
                        $data['voucherType'] = '1';
                        $employeeSalary_details[] = $data;

                    }
                }


                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Salary ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                } else {
                    $msg = 'Salary Save ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Salary Edit';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary Preview');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary List');
        $data['link_page_url'] = $this->project.'/employeeSalaryList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/
        //$data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListView($date,$month,$year);
        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByVoucher($voucher);
        //         echo '<pre>';
        // echo $this->db->last_query();

        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->Common_model->get_employee();

        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/SalaryEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function salaryViewVoucher($voucher){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();
        //$data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListView($date,$month,$year);
        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByVoucher($voucher);
        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryDetailsView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    
     public function salaryView($date,$month,$year){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();
        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListView($date,$month,$year);
        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryDetailsView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    
     public function salaryDeleteVoucher($voucher){


//        $condition = array(
//          'date' => $date,
//          'month'   => $month,
//          'year'   => $year
//        );
         $result=  $this->EmployeeSalary_Model->festivalVoucherDelete($voucher);
         //$result= $this->Common_model->salaryInfoDelete($date,$month,$year);
      // $result= $this->Common_model->delete_data('salary_info',$condition);

        if ($result) {
            $msg = 'Salary  ' . ' ' . $this->config->item("delete_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/employeeSalaryList'));

        } else {
           $msg = 'Salary  ' . ' ' . $this->config->item("delete_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/employeeSalaryList'));
        }

    }
    public function salaryViewPdf($date,$month,$year){

        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListView($date,$month,$year);
        $html = $this->load->view('distributor/setup/employee/salaryViewPdf', $data, true);
        // Load pdf library
        $this->load->library('pdf');

        //$html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("salarySheet.pdf", array("Attachment" => 0));

        // echo "<pre>";
        // print_r($dataArray);
        // exit();



    }
    public function salaryViewCashPdf($date,$month,$year,$cash){

        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListViewCash($date,$month,$year,$cash);
        $html = $this->load->view('distributor/setup/employee/salaryViewPdf', $data, true);
        // Load pdf library
        $this->load->library('pdf');

        //$html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("salarySheet.pdf", array("Attachment" => 0));

        // echo "<pre>";
        // print_r($dataArray);
        // exit();



    }
    public function salaryViewBankPdf($date,$month,$year,$bank){

        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListViewBank($date,$month,$year,$bank);
        $html = $this->load->view('distributor/setup/employee/salaryViewPdf', $data, true);
        // Load pdf library
        $this->load->library('pdf');

        //$html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("salarySheet.pdf", array("Attachment" => 0));

        // echo "<pre>";
        // print_r($dataArray);
        // exit();



    }
    public function salaryViewByCash($date,$month,$year,$cash){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListViewCash($date,$month,$year,$cash);
//        echo '<pre>';
//        echo $this->db->last_query();

        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryViewByCash', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function salaryViewByBank($date,$month,$year,$bank){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['getAllsalaryByDateListView'] = $this->Common_model->getAllsalaryByDateListViewBank($date,$month,$year,$bank);
//        echo '<pre>';
//        echo $this->db->last_query();

        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryViewByBank', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function employeeDelete($deleteId)
    {
        $data['isDelete'] = 'Y';
        $data['deletedAt'] = $this->timestamp;
        $data['deletedBy'] = $this->admin_id;
        $this->Common_model->update_data('employee', $data, 'id', $deleteId);
        message("Your data successfully deleted from database");
        redirect(site_url($this->project . '/employeeList'));
    }
    public function salaryDelete($date,$month,$year){


        $result= $this->Common_model->salaryInfoDelete($date,$month,$year);

        if ($result) {
            $msg = 'Salary  ' . ' ' . $this->config->item("delete_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/employeeSalaryList'));

        } else {
            $msg = 'Salary  ' . ' ' . $this->config->item("delete_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/employeeSalaryList'));
        }

    }

    public function vehicleList()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        /*page navbar details*/
        $data['title'] = 'Vehicle List';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Vehicle Add';
        $data['link_page_url'] = $this->project . '/vehicleAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition);
        $data['mainContent'] = $this->load->view('distributor/setup/vehicle/vehicleList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function vehicleAdd()
    {

        if (isPostBack()) {
            $data['vehicleName'] = $this->input->post('vehicleName');
            $data['vehicleModel'] = $this->input->post('vehicleModel');
            $data['chassisNumber'] = $this->input->post('chassisNumber');
            $data['numberPlate'] = $this->input->post('numberPlate');
            $data['createdBy'] = $this->admin_id;
            $data['dist_id'] = $this->dist_id;
            $data['isActive'] = 'Y';
            $data['isDelete'] = 'N';
            $this->Common_model->insert_data('vehicle', $data);
            message("Your data successfully inserted into database.");
            redirect(site_url($this->project . '/vehicleList'));
        }

        /*page navbar details*/
        $data['title'] = 'Add New Vehicle';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Vehicle List';
        $data['link_page_url'] = $this->project . '/vehicleList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        
       $this->load->helper('site_helper');
        $add  = check_parmission_by_user_role(2101);

        if($add == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        } else{
            $data['mainContent'] = $this->load->view('distributor/setup/vehicle/addNew', $data, true);
          $this->load->view('distributor/masterTemplate', $data);
        }
        
    }

    public function vehicleEdit($editId)
    {
        if (isPostBack()) {
            $data['vehicleName'] = $this->input->post('vehicleName');
            $data['vehicleModel'] = $this->input->post('vehicleModel');
            $data['chassisNumber'] = $this->input->post('chassisNumber');
            $data['numberPlate'] = $this->input->post('numberPlate');
            $this->Common_model->update_data('vehicle', $data, 'id', $editId);
            message("Your data successfully Updated into database.");
            redirect(site_url($this->project . '/vehicleList'));
        }

        $data['title'] = 'Vehicle Update';
        /*page navbar details*/
        $data['title'] = 'Vehicle Update';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Vehicle List';
        $data['link_page_url'] = $this->project . '/vehicleList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['vehicleList'] = $this->Common_model->get_single_data_by_single_column('vehicle', 'id', $editId);
        $this->load->helper('site_helper');
        $edit  = check_parmission_by_user_role(2102);

        if($edit == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
           
        } else{
            $data['mainContent'] = $this->load->view('distributor/setup/vehicle/editVehicle', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        }
       
    }

    function vehicleDelete($vehicleId)
    {
        $data['isDelete'] = 'Y';
        $data['deletedBy'] = $this->admin_id;
        $data['deletedAt'] = $this->timestamp;
        $this->Common_model->update_data('vehicle', $data, 'id', $vehicleId);
        message("Your data successfylly deleted from database.");
        redirect(site_url($this->project . '/vehicleList'));
    }

    public function incentiveList()
    {
        if (isPostBack()) {
            $data['company'] = $this->input->post('company');
            $data['targetQty'] = $this->input->post('targetQty');
            $data['start'] = date('Y-m-d', strtotime($this->input->post('start')));
            $data['end'] = date('Y-m-d', strtotime($this->input->post('end')));
            $data['incentive'] = $this->input->post('incentive');
            $data['dist_id'] = $this->dist_id;
            $data['status'] = 1;
            $data['updatedBy'] = $this->admin_id;
            $this->Common_model->insert_data('incentive', $data);
        }
        $data['companyList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['incentiveList'] = $this->Common_model->get_data_list_by_single_column('incentive', 'dist_id', $this->dist_id);
        $data['title'] = 'Incentive List';
        $data['mainContent'] = $this->load->view('distributor/setup/incentive', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    /* public function incentiveList() {
         if (isPostBack()) {
             $data['company'] = $this->input->post('company');
             $data['targetQty'] = $this->input->post('targetQty');
             $data['start'] = date('Y-m-d', strtotime($this->input->post('start')));
             $data['end'] = date('Y-m-d', strtotime($this->input->post('end')));
             $data['incentive'] = $this->input->post('incentive');
             $data['dist_id'] = $this->dist_id;
             $data['status'] = 1;
             $data['updatedBy'] = $this->admin_id;
             $this->Common_model->insert_data('incentive', $data);
         }
         $data['companyList'] = $this->Common_model->getPublicBrand($this->dist_id);
         $data['incentiveList'] = $this->Common_model->get_data_list_by_single_column('incentive', 'dist_id', $this->dist_id);
         $data['title'] = 'Incentive List';
         $data['mainContent'] = $this->load->view('distributor/setup/incentive', $data, true);
         $this->load->view('distributor/masterTemplate', $data);
     }*/

    function checkDuplicateBrandForUpdate()
    {
        $brandId = $this->input->post('brandId');
        $brandName = $this->input->post('brandName');
        $brandList = $this->db->get_where('brand', array('brandId !=' => $brandId, 'brandName' => $brandName))->row();
        if (!empty($brandList)) {
            echo 1;
        }
    }


    function userMessageList()
    {
        $this->db->select("*");
        $this->db->from("messageuser");
        $this->db->join("message", "message.msgId=messageuser.msgid");
        $this->db->where('messageuser.userid', $this->dist_id);
        $this->db->order_by('message.msgId', 'DESC');
        $data['allMessage'] = $this->db->get()->result();
        $data['title'] = 'Message List';
        $data['mainContent'] = $this->load->view('distributor/message/messageList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function userAllOfferList()
    {
        $this->db->select("*");
        $this->db->from("offer");
        $this->db->order_by('offerId', 'DESC');
        $data['allOffer'] = $this->db->get()->result();
        $data['title'] = 'Offer List';
        $data['mainContent'] = $this->load->view('distributor/offer/offerList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function newDecision()
    {
        if (isPostBack()) {
            $insertId = $this->Common_model->insert_data('newDecision', $_POST);
            if (!empty($insertId)) {
                message("Your data successfully inserted into database.");
                redirect(site_url('newDecisionList'));
            }
        }
        $data = array();
        $data['title'] = 'Decision Tools';
        $dist_id = $this->session->userdata('dist_id');
        $data['login'] = $this->session->userdata('login');
        $data['s_info'] = $this->Common_model->get_data_list_by_single_column('tbl_stock', 'dist_id', $this->dist_id);
        $data['dist_name'] = $this->session->userdata('dist_name');
        $data['dist_id'] = $this->session->userdata('dist_id');
        $data['mainContent'] = $this->load->view('distributor/setup/decision/newDecision', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function newDecisionList()
    {
        $data['newDecisionList'] = $this->Common_model->get_data_list('newDecision', 'percentage', 'DESC');
        $data['title'] = 'Decision Compare';
        $data['mainContent'] = $this->load->view('distributor/setup/decision/newDecisionList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function updatePassword($dist_id = null)
    {
        $data = array();
        $data['title'] = 'Change Password';
        $data['dist_id'] = $this->dist_id;
        $data['login'] = $this->session->userdata('login');
        $data['dist_name'] = $this->session->userdata('dist_name');
        //$dist_id = $this->session->userdata('dist_id');
        $data['dist_info'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $this->admin_id);
        $data['mainContent'] = $this->load->view('distributor/setup/profile/chng_pass', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function change_password()
    {
        $this->form_validation->set_rules('currentPassword', 'Current Password', 'required');
        $this->form_validation->set_rules('admin_password1', 'New Password', 'required');
        $this->form_validation->set_rules('admin_password2', 'Confirm Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            exception("Required field can't be empty.");
            redirect(site_url('updatePassword/' . $this->admin_id));
        } else {
            $data = array();
            $current_password1 = md5($this->input->post('currentPassword'));
            $admin_password1 = md5($this->input->post('admin_password1'));
            $admin_password2 = md5($this->input->post('admin_password2'));
            $condition = array(
                'admin_id' => $this->admin_id,
                'password' => $current_password1,
            );
            //checkCurrent password
            $adminInfo = $this->Common_model->get_single_data_by_many_columns('admin', $condition);
            if (empty($adminInfo)) {
                //current password not match
                exception("Current password does't match!!");
                redirect(site_url('updatePassword/' . $this->admin_id));
            }
            if ($admin_password1 != $admin_password2) {
                //password not match confirm password
                exception("Given password does't match with confirm password.");
                redirect('updatePassword/' . $this->admin_id);
            } else {
                //every thing is ok.password change successfully
                $data['lastUpdate'] = $this->timestamp;
                $data['password'] = md5($this->input->post('admin_password1'));
                $updated = $this->Common_model->update_data('admin', $data, 'admin_id', $this->admin_id);
                if (!empty($updated)):
                    message("Password successfully change.");
                    redirect('distributor_profile');
                else:
                    exception("Password does't updated.");
                    redirect('distributor_profile');
                endif;
            }
        }
    }

    public function distributor_profile()
    {
        $data = array();
        $data['title'] = 'Profile';
        $data['dist_id'] = $this->dist_id;
        $data['dist_name'] = $this->session->userdata('dist_name');
        $data['login'] = $this->session->userdata('login');
        $dist_id = $this->dist_id;
        $data['adminInfo'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $this->admin_id);
        $data['mainContent'] = $this->load->view('distributor/setup/profile/dist_profile', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function decision_tools()
    {
        $data = array();
        $data['title'] = 'Decision Tools';
        $dist_id = $this->session->userdata('dist_id');
        $data['login'] = $this->session->userdata('login');
        //$data['s_info'] = $this->Common_model->get_data_list_by_single_column('tbl_stock', 'dist_id', $this->dist_id);
        $data['dist_name'] = $this->session->userdata('dist_name');
        $data['dist_id'] = $this->session->userdata('dist_id');
        $data['mainContent'] = $this->load->view('distributor/setup/decision/decision_form', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    //new
    public function save_decision_tools()
    {
        $data['dec_title'] = $this->input->post('dec_title', TRUE);
        $data['asset_amount'] = $this->input->post('asset_amount', TRUE);
        $data['invest_type'] = $this->input->post('invest_type', TRUE);
        $data['note'] = $this->input->post('note', TRUE);
        $data['dist_id'] = $this->dist_id;
        $data['bank_saving_type'] = $this->input->post('bank_saving_type', TRUE);
        $data['amount_of_saving'] = $this->input->post('amount_of_saving', TRUE);
        $data['period_time'] = $this->input->post('period_time', TRUE);
        $data['interest_per_month'] = $this->input->post('interest_per_month', TRUE);
        $data['per_month_interest_amount'] = $this->input->post('per_month_interest_amount', TRUE);
        $data['company_name'] = $this->input->post('company_name', TRUE);
        $data['invest_amount'] = $this->input->post('invest_amount', TRUE);
        $data['per_month_profit_amount'] = $this->input->post('per_month_profit_amount', TRUE);
        $data['return_of_invest_time'] = $this->input->post('return_of_invest_time', TRUE);
        $this->Common_model->insert_data('tbl_decision', $data);
        redirect(site_url('compare_decision'));
    }

    //new
    public function compare_decision()
    {
        $data = array();
        $data['title'] = 'Compare Decision';
        $dist_id = $this->dist_id;
        $data['login'] = $this->session->userdata('login');
        $data['offer_info'] = $this->Common_model->compare_decision_info($this->dist_id);
        $data['dist_name'] = $this->session->userdata('dist_name');
        $data['dist_id'] = $this->session->userdata('dist_id');
        //$data['s_info'] = $this->Common_model->get_data_list_by_single_column('tbl_stock', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/setup/decision/compare_decision', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function SystemConfig()
    {
        if (isPostBack()) {
            $data['companyName'] = $this->input->post('companyName');
            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['address'] = $this->input->post('address');
            $data['website'] = $this->input->post('website');
            $data['dist_id'] = $this->dist_id;
            $data['vat'] = $this->input->post('vat');
            if (!empty($this->input->post('image')[0])):
                $data['logo'] = $this->input->post('image')[0];
            endif;
            $data['updated_at'] = $this->timestamp;
            $data['updated_by'] = $this->admin_id;
            $exits = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
            if (!empty($exits)):
                $msg = "Your System Configuration successfully updated into database.";
                $this->session->set_flashdata('success', $msg);

                $this->Common_model->update_data('system_config', $data, 'dist_id', $this->dist_id);
            else:
                $msg = "Your System Configuration successfully inserted into database.";
                $this->session->set_flashdata('error', $msg);
                $this->Common_model->insert_data('system_config', $data, 'dist_id', $this->dist_id);
            endif;
            redirect(site_url($this->project . '/SystemConfig'));
        }
        $data['title'] = get_phrase('System_Config');
        $data['page_type'] = get_phrase('Configuration');
        $data['configInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->helper('site_helper');
        $add     = check_parmission_by_user_role(3);
        $edit    = check_parmission_by_user_role(2102);
        $delete  = check_parmission_by_user_role(2103);
        if($add == 0){
            $data['mainContent'] = $this->load->view('distributor/not_permisson_page', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        } else{
            $data['mainContent'] = $this->load->view('distributor/setup/systemConfg', $data, true);
             $this->load->view('distributor/masterTemplate', $data);
        }
        
    }

    function getAllMessage()
    {
        $this->db->select("*");
        $this->db->from("messageuser");
        $this->db->join("message", "message.msgId=messageuser.msgid");
        $this->db->where("messageuser.userid", $this->dist_id);
        $data['allMessage'] = $this->db->get()->result();
        // echo $this->db->last_query();die;
        $data['title'] = 'All Message';
        $data['mainContent'] = $this->load->view('distributor/message/messageList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function saveNewSupplier()
    {
        $data['supID'] = $this->input->post('supplierId');
        $data['supName'] = $this->input->post('supName');
        $data['supEmail'] = $this->input->post('supEmail');
        $data['supPhone'] = $this->input->post('supPhone');
        $data['supAddress'] = $this->input->post('supAddress');
        $data['dist_id'] = $this->dist_id;
        $data['updated_by'] = $this->admin_id;
        $insertID = $this->Common_model->insert_data('supplier', $data);
        if (!empty($insertID)):
            echo '<option value="' . $insertID . '" selected="selected">' . $data['supID'] . ' [ ' . $data['supName'] . ' ] ' . '</option>';
        endif;
    }

    function userStatusChange()
    {
        $userId = $this->input->post('userId');
        $data['status'] = $this->input->post('status');
        $this->Common_model->update_data('admin', $data, 'admin_id', $userId);
        message("User Status successfully change");
        echo 1;
    }


    function deletedata()
    {
        dumpVar($_POST);
        $table = $this->input->post('table');
        $column = $this->input->post('column');
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $condition = array(
            'dist_id' => $this->dist_id,
            'ledger_type' => $type,
            'client_vendor_id' => $id,
        );
        $transactionExit = $this->Common_model->get_single_data_by_many_columns('client_vendor_ledger', $condition);
        if (empty($transactionExit)) {
            $this->Common_model->delete_data($table, $column, $id);
            message("Your data successfully deleted from database.");
            echo 1;
        } else {
            if ($type == 1) {
                exception("This customer can't be deleted.already have a transaction  by this customer!");
                echo 1;
            } else {
                exception("This Supplier can't be deleted.already have a transaction  by this supplier!");
                echo 1;
            }
        }
    }

    function supplierDelete()
    {
        $id = $this->input->post('id');
        $condition = array(
            'dist_id' => $this->dist_id,
            'ledger_type' => 2,
            'client_vendor_id' => $id,
        );
        $transactionExit = $this->Common_model->get_single_data_by_many_columns('client_vendor_ledger', $condition);
        if (empty($transactionExit)) {
            $this->Common_model->delete_data('supplier', 'sup_id', $id);
            message("Your data successfully deleted from database.");
            echo 1;
        } else {
            exception("This supplier can't be deleted.already have a transaction  by this supplier!");
            echo 1;
        }
    }


    function checkDuplicateBrand()
    {
        $brandName = $this->input->post('brandName');
        if (!empty($brandName)):
            $duplicateBrandCondition = array(
                'brandName' => $brandName,
                'dist_id' => $this->dist_id,
            );
            $exitsBrandName = $this->Common_model->get_single_data_by_many_columns('brand', $duplicateBrandCondition);
            if (!empty($exitsBrandName)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }

    function checkDuplicateUnit()
    {
        $unitName = $this->input->post('unitName');
        if (!empty($unitName)):
            $exitsUnitName = $this->Common_model->cehckDuplicateUnit($unitName, $this->dist_id);
            if (!empty($exitsUnitName)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }

    function checkDuplicateEmailForUser()
    {
        $email = $this->input->post('email');
        if (!empty($email)):
            $condition = array(
                'email' => $email,
                'distributor_id' => $this->dist_id,
            );
            $exitsSup = $this->Common_model->get_single_data_by_many_columns('admin', $condition);
            if (!empty($exitsSup)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }

    function checkDuplicateEmailForUserEdit()
    {
        $email = $this->input->post('email');
        $adminId = $this->input->post('adminId');
        if (!empty($email)):
            $condition = array(
                'email' => $email,
                'distributor_id' => $this->dist_id,
                'admin_id !=' => $adminId,
            );
            $exitsSup = $this->Common_model->get_single_data_by_many_columns('admin', $condition);
            if (!empty($exitsSup)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }


    function checkDuplicateCategory__old_al()
    {
        $catName = $this->input->post('catName');
        $condition = array(
            'dist_id' => $this->dist_id,
            'title' => $catName
        );
        $exitsData = $this->Common_model->checkPublicProductCat($catName);
        if (!empty($exitsData)) {
            echo 1;
        } else {
            echo 2;
        }
    }

    function checkDuplicateCategory()
    {
        $brandName = $this->input->post('catName');

        //$catName = $this->input->post('catName');

        if (!empty($brandName)):
            $duplicateBrandCondition = array(
                'title' => $brandName,

            );
            $exitsBrandName = $this->Common_model->get_single_data_by_many_columns('productcategory', $duplicateBrandCondition);
            if (!empty($exitsBrandName)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }


    function checkDuplicateCategoryforUpdate()
    {
        $catName = $this->input->post('catName');
        $updatedID = $this->input->post('updatedID');
        $condition = array(
            'dist_id' => $this->dist_id,
            'title' => $catName,
            'category_id !=' => $updatedID
        );
        $this->db->select("*");
        $this->db->from("productcategory");
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('title', $catName);
        $this->db->where('category_id', $updatedID);
        $exitsData = $this->Common_model->get_single_data_by_many_columns('productcategory', $condition);
        //echo $this->db->last_query();die;
        if (!empty($exitsData)) {
            echo 1;
        } else {
            echo 2;
        }
    }

    function purchase_order_config()
    {



        $this->db->trans_start();
        $purchase_order_status = isset($_POST['purchase_order']) && $_POST['purchase_order'] == "on" ? "1" : "0";
        $purchase_order_lc_status = isset($_POST['purchase_order_lc']) && $_POST['purchase_order_lc'] == "on" ? "1" : "0";

        if ($purchase_order_status != 0) {
            //purchase_order only change status
            $purchase_order['s_value'] = "Purchase Order";
            $purchase_order['is_active'] = $purchase_order_status;
            $this->Common_model->update_data('settings', $purchase_order, "s_key", "purchase_order");

            //purchase_order_prefix
            $purchase_order_prefix['s_value'] = $this->input->post('purchase_order_prefix');
            $purchase_order_prefix['is_active'] = 1;
            $this->Common_model->update_data('settings', $purchase_order_prefix, "s_key", "purchase_order_prefix");
            //purchase_order_supplier_id
            $purchase_order_supplier_id['s_value'] = $this->input->post('purchase_order_supplier_id');
            $purchase_order_supplier_id['is_active'] = 1;
            $this->Common_model->update_data('settings', $purchase_order_supplier_id, "s_key", "purchase_order_supplier_id");
            //purchase_order_date
            $purchase_order_date['s_value'] = $this->input->post('purchase_order_date');
            $purchase_order_date['is_active'] = 1;
            $this->Common_model->update_data('settings', $purchase_order_date, "s_key", "purchase_order_date");

            //purchase_order_id
            $purchase_order_id['s_value'] = $this->input->post('purchase_order_id');
            $purchase_order_id['is_active'] = 1;
            $this->Common_model->update_data('settings', $purchase_order_id, "s_key", "purchase_order_id");

            //purchase_order_delivery_date
            $purchase_order_delivery_date['s_value'] = $this->input->post('purchase_order_delivery_date');
            $purchase_order_delivery_date['is_active'] = 1;
            $this->Common_model->update_data('settings', $purchase_order_delivery_date, "s_key", "purchase_order_delivery_date");
            //purchase_order_shipping_address
            $purchase_order_shipping_address['s_value'] = $this->input->post('purchase_order_shipping_address');
            $purchase_order_shipping_address['is_active'] = 1;
            $this->Common_model->update_data('settings', $purchase_order_shipping_address, "s_key", "purchase_order_shipping_address");

            //purchase_order_lc

            /*$purchase_order['s_value'] = "Purchase Order";*/
            $purchase_order_lc['is_active'] = $purchase_order_lc_status;
            $this->Common_model->update_data('settings', $purchase_order_lc, "s_key", "purchase_order_lc");
            if ($purchase_order_lc_status == 1) {
                //purchase_order_lc_field_1
                $purchase_order_lc_field_1['s_value'] = $this->input->post('txtpurchase_order_lc_field_1');;//
                $purchase_order_lc_field_1['is_active'] = $purchase_order_lc_status;
                $this->Common_model->update_data('settings', $purchase_order_lc_field_1, "s_key", "purchase_order_lc_field_1");


                //purchase_order_lc_field_2
                $purchase_order_lc_field_2['s_value'] = $this->input->post('txtpurchase_order_lc_field_2');;//
                $purchase_order_lc_field_2['is_active'] = $purchase_order_lc_status;
                $this->Common_model->update_data('settings', $purchase_order_lc_field_2, "s_key", "purchase_order_lc_field_2");

                //purchase_order_lc_field_3
                $purchase_order_lc_field_3['s_value'] = $this->input->post('txtpurchase_order_lc_field_3');;//
                $purchase_order_lc_field_3['is_active'] = $purchase_order_lc_status;
                $this->Common_model->update_data('settings', $purchase_order_lc_field_3, "s_key", "purchase_order_lc_field_3");
            }else{
                //purchase_order_lc_field_1
                $purchase_order_lc_field_1['s_value'] = "";//
                $purchase_order_lc_field_1['is_active'] = $purchase_order_lc_status;
                $this->Common_model->update_data('settings', $purchase_order_lc_field_1, "s_key", "purchase_order_lc_field_1");


                //purchase_order_lc_field_2
                $purchase_order_lc_field_2['s_value'] = "";//
                $purchase_order_lc_field_2['is_active'] = $purchase_order_lc_status;
                $this->Common_model->update_data('settings', $purchase_order_lc_field_2, "s_key", "purchase_order_lc_field_2");

                //purchase_order_lc_field_3
                $purchase_order_lc_field_3['s_value'] = "";//
                $purchase_order_lc_field_3['is_active'] = $purchase_order_lc_status;
                $this->Common_model->update_data('settings', $purchase_order_lc_field_3, "s_key", "purchase_order_lc_field_3");
            }
            //purchase_order_extra_field_1
            $extra_field_1_status = isset($_POST['purchase_order_extra_field_1']) && $_POST['purchase_order_extra_field_1'] == "on" ? "1" : "0";
            $purchase_order_extra_field_1['s_value'] = $this->input->post('txtpurchase_order_extra_field_1');
            $purchase_order_extra_field_1['is_active'] = $extra_field_1_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_1, "s_key", "purchase_order_extra_field_1");


            //purchase_order_extra_field_2
            $extra_field_2_status = isset($_POST['purchase_order_extra_field_2']) && $_POST['purchase_order_extra_field_2'] == "on" ? "1" : "0";
            $purchase_order_extra_field_2['s_value'] = $this->input->post('txtpurchase_order_extra_field_2');
            $purchase_order_extra_field_2['is_active'] = $extra_field_2_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_2, "s_key", "purchase_order_extra_field_2");

            //purchase_order_extra_field_3
            $extra_field_3_status = isset($_POST['purchase_order_extra_field_3']) && $_POST['purchase_order_extra_field_3'] == "on" ? "1" : "0";
            $purchase_order_extra_field_3['s_value'] = $this->input->post('txtpurchase_order_extra_field_3');
            $purchase_order_extra_field_3['is_active'] = $extra_field_3_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_3, "s_key", "purchase_order_extra_field_3");


            //purchase_order_extra_field_4
            $extra_field_4_status = isset($_POST['purchase_order_extra_field_4']) && $_POST['purchase_order_extra_field_4'] == "on" ? "1" : "0";
            $purchase_order_extra_field_4['s_value'] = $this->input->post('txtpurchase_order_extra_field_4');
            $purchase_order_extra_field_4['is_active'] = $extra_field_4_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_4, "s_key", "purchase_order_extra_field_4");

            //purchase_order_extra_field_5
            $extra_field_5_status = isset($_POST['purchase_order_extra_field_5']) && $_POST['purchase_order_extra_field_5'] == "on" ? "1" : "0";
            $purchase_order_extra_field_5['s_value'] = $this->input->post('txtpurchase_order_extra_field_5');
            $purchase_order_extra_field_5['is_active'] = $extra_field_5_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_5, "s_key", "purchase_order_extra_field_5");

            //purchase_order_material_receive
            $purchase_order_material_receive_status = isset($_POST['purchase_order_material_receive']) && $_POST['purchase_order_material_receive'] == "on" ? "1" : "0";
            $purchase_order_material_receive['is_active'] = $purchase_order_material_receive_status;
            $this->Common_model->update_data('settings', $purchase_order_material_receive, "s_key", "purchase_order_material_receive");

            //purchase_order_material_receive_prefix
            $purchase_order_material_receive_prefix['s_value'] = $this->input->post('purchase_order_material_receive_prefix');
            $purchase_order_material_receive_prefix['is_active'] = $purchase_order_material_receive_status;
            $this->Common_model->update_data('settings', $purchase_order_material_receive_prefix, "s_key", "purchase_order_material_receive_prefix");

        } else {
            //$purchase_order['s_value'] = "Purchase Order";
            $purchase_order['is_active'] = $purchase_order_status;
            $this->Common_model->update_data('settings', $purchase_order, "form_name", "purchase_order");
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg= 'PO configuration '.' '.$this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project.'/SystemConfig' ));
        else:
            $msg= 'PO configuration '.' '.$this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project.'/SystemConfig' ));
        endif;
    }

    function sales_order_config()
    {
    /*    INSERT INTO `settings` (`id`, `s_key`, `s_value`, `form_name`, `is_active`) VALUES
    (1, 'purchase_order', 'Purchase Order', 'purchase_order', 1),
(2, 'purchase_order_prefix', 'PO ', 'purchase_order', 1),
(3, 'purchase_order_date', 'Purchase Order Date ', 'purchase_order', 1),
(4, 'purchase_order_delivery_date', 'PO Delivery Date ', 'purchase_order', 1),
(5, 'purchase_order_supplier_id', 'Supplier ID ', 'purchase_order', 1),
(6, 'purchase_order_id', 'Purchase Order ID ', 'purchase_order', 1),
(7, 'purchase_order_shipping_address', 'Shipping Address ', 'purchase_order', 1),
(8, 'purchase_order_extra_field_1', NULL, 'purchase_order', 0),
(9, 'purchase_order_extra_field_2', NULL, 'purchase_order', 0),
(10, 'purchase_order_extra_field_3', NULL, 'purchase_order', 0),
(11, 'purchase_order_extra_field_4', NULL, 'purchase_order', 0),
(12, 'purchase_order_extra_field_5', NULL, 'purchase_order', 0),
(13, 'purchase_order_extra_field_6', '', 'purchase_order', 0),
(14, 'purchase_order_material_receive', 'purchase_order_material_recive', 'purchase_order', 0),
(15, 'purchase_order_material_receive_prefix', 'MRN', 'purchase_order', 0),
(16, 'purchase_order_lc', 'Purchase Order LC', 'purchase_order', 0),
(17, 'purchase_order_lc_field_1', '', 'purchase_order', 0),
(18, 'purchase_order_lc_field_2', '', 'purchase_order', 0),
(19, 'purchase_order_lc_field_3', '', 'purchase_order', 0),
(20, 'purchase_order_lc_field_4', '', 'purchase_order', 0);





(21, 'sles_order', 'Sales Order', 'sales_order', 1),
(22, 'sales_order_prefix', 'SO', 'sales_order', 1),
(23, 'sales_order_date', 'SO Order Date ', 'sales_order', 1),
(24, 'sales_order_delivery_date', 'SO Delivery Date ', 'sales_order', 1),
(25, 'sales_order_customer_id', 'Customer ID ', 'sales_order', 1),
(26, 'sales_order_id', 'Sales Order ID ', 'sales_order', 1),
(27, 'sales_order_delivery_address', 'Delivery Address ', 'sales_order', 1),
(28, 'sales_order_extra_field_1', NULL, 'sales_order', 0),
(29, 'sales_order_extra_field_2', NULL, 'sales_order', 0),
(30, 'sales_order_extra_field_3', NULL, 'sales_order', 0),
(31, 'sales_order_extra_field_4', NULL, 'sales_order', 0),
(32, 'sales_order_extra_field_5', NULL, 'sales_order', 0),
(33, 'sale_order_extra_field_6', '', 'sales_order', 0),
(34, 'sales_delivery_chalan', 'sales_delivery_chalan', 'sales_order', 0),
(35, 'sales_delivery_chalan_prefix', 'SDC', 'sales_order', 0),
(36, 'sales_order_lc', 'Sales Order LC', 'sales_order', 0),
(37, 'sales_order_lc_field_1', '', 'sales_order', 0),
(38, 'sales_order_lc_field_2', '', 'sales_order', 0),
(39, 'sales_order_lc_field_3', '', 'sales_order', 0),
(40, 'sales_order_lc_field_4', '', 'sales_order', 0);*/

       /* echo "<pre>";
        print_r($_POST);
        exit();*/



        $this->db->trans_start();
        $sles_order_status = isset($_POST['sles_order']) && $_POST['sles_order'] == "on" ? "1" : "0";
        $sales_order_lc_status = isset($_POST['sales_order_lc']) && $_POST['sales_order_lc'] == "on" ? "1" : "0";

        if ($sles_order_status != 0) {
            //sles_order only change status
            $sales_order['s_value'] = "Sales Order";
            $sales_order['is_active'] = $sles_order_status;
            $this->Common_model->update_data('settings', $sales_order, "s_key", "sles_order");

            //sales_order_prefix
            $sales_order_prefix['s_value'] = $this->input->post('sales_order_prefix');
            $sales_order_prefix['is_active'] = 1;
            $this->Common_model->update_data('settings', $sales_order_prefix, "s_key", "sales_order_prefix");
            //sales_order_customer_id
            $sales_order_customer_id['s_value'] = $this->input->post('sales_order_customer_id');
            $sales_order_customer_id['is_active'] = 1;
            $this->Common_model->update_data('settings', $sales_order_customer_id, "s_key", "sales_order_customer_id");
            //sales_order_date
            $sales_order_date['s_value'] = $this->input->post('sales_order_date');
            $sales_order_date['is_active'] = 1;
            $this->Common_model->update_data('settings', $sales_order_date, "s_key", "sales_order_date");

            //sales_order_id
            $sales_order_id['s_value'] = $this->input->post('sales_order_id');
            $sales_order_id['is_active'] = 1;
            $this->Common_model->update_data('settings', $sales_order_id, "s_key", "sales_order_id");

            //sales_order_delivery_date
            $sales_order_delivery_date['s_value'] = $this->input->post('sales_order_delivery_date');
            $sales_order_delivery_date['is_active'] = 1;
            $this->Common_model->update_data('settings', $sales_order_delivery_date, "s_key", "sales_order_delivery_date");
            //sales_order_delivery_address
            $sales_order_delivery_address['s_value'] = $this->input->post('sales_order_delivery_address');
            $sales_order_delivery_address['is_active'] = 1;
            $this->Common_model->update_data('settings', $sales_order_delivery_address, "s_key", "sales_order_delivery_address");

            //sales_order_lc

            /*$purchase_order['s_value'] = "Purchase Order";*/
            $sales_order_lc['is_active'] = $sales_order_lc_status;
            $this->Common_model->update_data('settings', $sales_order_lc, "s_key", "sales_order_lc");
            if ($sales_order_lc_status == 1) {
                //sales_order_lc_field_1
                $sales_order_lc_field_1['s_value'] = $this->input->post('txtsales_order_lc_field_1');;//
                $sales_order_lc_field_1['is_active'] = $sales_order_lc_status;
                $this->Common_model->update_data('settings', $sales_order_lc_field_1, "s_key", "sales_order_lc_field_1");


                //sales_order_lc_field_2
                $sales_order_lc_field_2['s_value'] = $this->input->post('txtsales_order_lc_field_2');;//
                $sales_order_lc_field_2['is_active'] = $sales_order_lc_status;
                $this->Common_model->update_data('settings', $sales_order_lc_field_2, "s_key", "sales_order_lc_field_2");

                //sales_order_lc_field_3
                $sales_order_lc_field_3['s_value'] = $this->input->post('txtsales_order_lc_field_3');;//
                $sales_order_lc_field_3['is_active'] = $sales_order_lc_status;
                $this->Common_model->update_data('settings', $sales_order_lc_field_3, "s_key", "sales_order_lc_field_3");
            }else{

                //sales_order_lc_field_1
                $sales_order_lc_field_1['s_value'] = "";//
                $sales_order_lc_field_1['is_active'] = $sales_order_lc_status;
                $this->Common_model->update_data('settings', $sales_order_lc_field_1, "s_key", "sales_order_lc_field_1");


                //sales_order_lc_field_2
                $sales_order_lc_field_2['s_value'] = "";;//
                $sales_order_lc_field_2['is_active'] = $sales_order_lc_status;
                $this->Common_model->update_data('settings', $sales_order_lc_field_2, "s_key", "sales_order_lc_field_2");

                //sales_order_lc_field_3
                $sales_order_lc_field_3['s_value'] = "";;//
                $sales_order_lc_field_3['is_active'] = $sales_order_lc_status;
                $this->Common_model->update_data('settings', $sales_order_lc_field_3, "s_key", "sales_order_lc_field_3");
            }
            //purchase_order_extra_field_1
            $extra_field_1_status = isset($_POST['sales_order_extra_field_1']) && $_POST['sales_order_extra_field_1'] == "on" ? "1" : "0";
            $purchase_order_extra_field_1['s_value'] = $this->input->post('txtsales_order_extra_field_1');
            $purchase_order_extra_field_1['is_active'] = $extra_field_1_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_1, "s_key", "sales_order_extra_field_1");


            //purchase_order_extra_field_2
            $extra_field_2_status = isset($_POST['sales_order_extra_field_2']) && $_POST['sales_order_extra_field_2'] == "on" ? "1" : "0";
            $purchase_order_extra_field_2['s_value'] = $this->input->post('txtsales_order_extra_field_2');
            $purchase_order_extra_field_2['is_active'] = $extra_field_2_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_2, "s_key", "sales_order_extra_field_2");

            //purchase_order_extra_field_3
            $extra_field_3_status = isset($_POST['sales_order_extra_field_3']) && $_POST['sales_order_extra_field_3'] == "on" ? "1" : "0";
            $purchase_order_extra_field_3['s_value'] = $this->input->post('txtsales_order_extra_field_3');
            $purchase_order_extra_field_3['is_active'] = $extra_field_3_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_3, "s_key", "sales_order_extra_field_3");


            //purchase_order_extra_field_4
            $extra_field_4_status = isset($_POST['sales_order_extra_field_4']) && $_POST['sales_order_extra_field_4'] == "on" ? "1" : "0";
            $purchase_order_extra_field_4['s_value'] = $this->input->post('txtsales_order_extra_field_4');
            $purchase_order_extra_field_4['is_active'] = $extra_field_4_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_4, "s_key", "sales_order_extra_field_4");

            //purchase_order_extra_field_5
            $extra_field_5_status = isset($_POST['sales_order_extra_field_5']) && $_POST['sales_order_extra_field_5'] == "on" ? "1" : "0";
            $purchase_order_extra_field_5['s_value'] = $this->input->post('txtsales_order_extra_field_5');
            $purchase_order_extra_field_5['is_active'] = $extra_field_5_status;
            $this->Common_model->update_data('settings', $purchase_order_extra_field_5, "s_key", "sales_order_extra_field_5");

            //purchase_order_material_receive
            $purchase_order_material_receive_status = isset($_POST['sales_delivery_chalan']) && $_POST['sales_delivery_chalan'] == "on" ? "1" : "0";
            $purchase_order_material_receive['is_active'] = $purchase_order_material_receive_status;
            $this->Common_model->update_data('settings', $purchase_order_material_receive, "s_key", "sales_delivery_chalan");

            //purchase_order_material_receive_prefix
            $purchase_order_material_receive_prefix['s_value'] = $this->input->post('sales_delivery_chalan_prefix');
            $purchase_order_material_receive_prefix['is_active'] = $purchase_order_material_receive_status;
            $this->Common_model->update_data('settings', $purchase_order_material_receive_prefix, "s_key", "sales_delivery_chalan_prefix");

        } else {
            //$purchase_order['s_value'] = "Purchase Order";
            $sales_order['is_active'] = $sles_order_status;
            $this->Common_model->update_data('settings', $sales_order, "form_name", "sles_order");
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg= 'PO configuration '.' '.$this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project.'/SystemConfig' ));
        else:
            $msg= 'PO configuration '.' '.$this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project.'/SystemConfig' ));
        endif;
    }

    function editUser($editId) {
        if (isPostBack()) {
            $data['name'] = $this->input->post('name');
            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['distributor_id'] = $this->session->userdata('m_distributorid');
            $data['updated_by'] = $this->admin_id;
            $this->Common_model->update_data('admin', $data, 'admin_id', $editId);
            $msg="User update successfully";
            $this->session->set_flashdata('success', $msg);
            //message("User update successfully.");
            redirect(site_url($this->project.'/userList'));
        }
        /*page navbar details*/
        $data['title'] = 'Update User';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='User List';
        $data['link_page_url']=$this->project.'/userList';
        $data['link_icon']="<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['editInfo'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $editId);
        $data['userList'] = $this->Common_model->get_data_list_by_single_column('admin', 'distributor_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/setup/user/editUser', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

}
