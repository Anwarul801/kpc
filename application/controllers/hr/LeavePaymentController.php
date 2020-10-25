<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LeavePaymentController extends CI_Controller {

    private $timestamp;
    public $admin_id;
    private $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('LeavePayment_Model');
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


 public function addLeavePayment(){
         
        $this->load->helper('payroll_voucher_no_helper');
  if (isPostBack()) {
   

            $this->form_validation->set_rules('date', 'Date', 'required');
            //$this->form_validation->set_rules('voucherid', 'voucherid', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');



        


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/addLeavePayment'));
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
                            $data['voucherNo'] = $this->input->post('voucherid');
                            $data['voucherType'] = '4';
                            $employeeSalary_details[] = $data;

                        }
                    }


                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Add Leave Payment ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/addLeavePayment'));
                } else {
                    $msg = 'Add Leave Payment ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/addLeavePayment'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );

        
        
        $data['voucherID'] = payroll_voucher_no();
        
        $data['title'] = 'Add Leave Payment';

        /*page navbar details*/
        $data['title'] = get_phrase('Add Leave Payment');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Leave Payment List');
        $data['link_page_url'] = $this->project.'/LeavePaymentList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['third_link_page_name'] = get_phrase('Configuration');
        $data['third_link_page_url'] = $this->project.'/voucherConfiquration';
        $data['third_link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/

         $data['employeefield'] = $this->db->where('isShow', '1')->get('salary_bonus_field')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->Common_model->get_employee();
        $data['percentanceSum'] = $this->LeavePayment_Model->getpercentanceSum();
        $data['percentanceSubt'] = $this->LeavePayment_Model->getpercentanceSubt();
    //      echo "<pre>";
    //    echo $this->db->last_query();
    
    // exit();
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/leavePayment/addLeavePayment', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }
    public function LeavePaymentEdit($voucher) {

  if (isPostBack()) {
          // echo "<pre>";
          // print_r($_POST);
          // exit();

            $this->form_validation->set_rules('date', 'Date', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');


        
           $this->form_validation->set_rules('month', 'Month Name', 'required');
        


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/LeavePaymentList'));
            } else {

                $this->db->trans_start();

                   $this->LeavePayment_Model->leavePaymentDelete($voucher);
                

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
                            $data['voucherType'] = '4';
                            $employeeSalary_details[] = $data;

                        }
                    }

                

                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Leave Payment ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/LeavePaymentList'));
                } else {
                    $msg = 'Leave Payment ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/LeavePaymentList'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Leave Payment';

        /*page navbar details*/
        $data['title'] = get_phrase('Leave Payment');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Leave Payment  List');
        $data['link_page_url'] = $this->project.'/LeavePaymentList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/
         $data['getAllsalaryByDateListView'] = $this->LeavePayment_Model->getAllByVoucher($voucher);
       //         echo '<pre>';
       // echo $this->db->last_query();

         $data['employeefield'] = $this->db->where('isShow', '1')->get('salary_bonus_field')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->Common_model->get_employee();

        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/leavePayment/LeavePaymentEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
     public function LeavePaymentView($voucher){
        $data['title'] = get_phrase('Leave Payment');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Leave Payment');
        $data['link_page_url'] = $this->project.'/addLeavePayment';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['third_link_page_name'] = get_phrase('Configuration');
        $data['third_link_page_url'] = $this->project.'/voucherConfiquration';
        $data['third_link_icon'] = "<i class='fa fa-plus'></i>";
        $data['employeefield'] = $this->db->where('isShow', '1')->get('salary_bonus_field')->result();
        $data['getAllsalaryByDateListView'] = $this->LeavePayment_Model->getAllByVoucher($voucher);
        // echo "<pre>";
        //   print_r($data['getAllsalaryByDateListView']);
        //   exit();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/leavePayment/LeavePaymentView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

     public function LeavePaymentList() {
        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );


        /*page navbar details*/
        $data['title'] = get_phrase('Leave Payment List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Leave Payment');
        $data['link_page_url'] = $this->project.'/addLeavePayment';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
       /* $data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/

        $data['salaryList'] = $this->Common_model->getAllsalaryList();
        $data['getAllfestivalBonusDateList'] = $this->LeavePayment_Model->getAllLeavePaymentDateList();

        $data['employeewisedep'] = $this->Common_model->getAllEmployeewiseD();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/leavePayment/LeavePaymentList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
   
    public function LeavePaymentDelete($voucher){




         $result= $this->LeavePayment_Model->leavePaymentDelete($voucher);
      // $result= $this->Common_model->delete_data('salary_info',$condition);

        if ($result) {
            $msg = 'Leave Payment ' . ' ' . $this->config->item("delete_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/LeavePaymentList'));

        } else {
           $msg = 'Leave Payment  ' . ' ' . $this->config->item("delete_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/LeavePaymentList'));
        }

    }



     public function LeavePaymentReport(){
        $data['title'] = get_phrase('Leave Payment Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Leave Payment');
        $data['link_page_url'] = $this->project.'/LeavePaymentList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Field Configuration');
        $data['second_link_page_url'] = $this->project.'/voucherConfiquration/';
        $data['second_link_icon'] = "<i class='fa fa-list'></i>";
        $data['employeefield'] = $this->db->where('isShow', '1')->get('salary_bonus_field')->result();
        $data['getAllsalaryByDateListView'] = $this->LeavePayment_Model->getAllVoucherReport();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/report/leavePaymentVoucherReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
   



}
