<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceController extends CI_Controller {

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
        $this->load->model('Attendance_Model');
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


 public function empAttendance(){
         
        
  if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');
            //$this->form_validation->set_rules('voucherid', 'voucherid', 'required');
            $date=$this->input->post('date');
            $department_id=$this->input->post('department_id');
       
       $data['all_leave_category_info'] = $this->Common_model->get_data_list('tbl_leave_category', 'leave_category_id', 'ASC');
        
        $data['all_department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');

        $data['department_id'] = $this->input->post('department_id');
        $data['date'] = $this->input->post('date', TRUE);
        $sbtnType = $this->input->post('sbtn');
        $flag = $this->session->userdata('flag');
        if ($sbtnType == 1 || $flag == 1) {
            if ($flag) {
                $date = $this->session->userdata('date');
                $department_id = $this->session->userdata('department_id');
                $this->session->unset_userdata('date');
                $this->session->unset_userdata('flag');
                $this->session->unset_userdata('department_id');
            } else {

                $date=$this->input->post('date');
            $department_id=$this->input->post('department_id');
            }
        }

        $data['employee_info'] = $this->Attendance_Model->get_employee_id_by_dept_id($department_id);
   
        foreach ($data['employee_info'] as $v_employee) {
            $where = array('employee_id' => $v_employee->id, 'date' => $date);
            $data['atndnce'][] = $this->Attendance_Model->check_by($where, 'tbl_attendance');
        }
     
// echo "<pre>";
//       $tt = $this->db->last_query();
//        print_r($tt);
//      print_r($data['atndnce']);

//      exit();

        


            
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );

        
        
        
        
        $data['title'] = 'Attendance';

        /*page navbar details*/
        $data['title'] = get_phrase('Add Attendance');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Attendance Report');
        $data['link_page_url'] = $this->project.'/attendance_report';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        // $data['third_link_page_name'] = get_phrase('Configuration');
        // $data['third_link_page_url'] = $this->project.'/voucherConfiquration';
        // $data['third_link_icon'] = "<i class='fa fa-plus'></i>";
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
       // $data['employeewisedep'] = $this->Common_model->get_employee();
        $data['percentanceSum'] = $this->LeavePayment_Model->getpercentanceSum();
        $data['percentanceSubt'] = $this->LeavePayment_Model->getpercentanceSubt();
    //      echo "<pre>";
    //    echo $this->db->last_query();
    
    // exit();
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->Common_model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/attendance/empAttendance', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }
    
     public function save_attendance() {
     //    echo "<pre>";
      
     // print_r($_POST);

     // exit();
        $this->Attendance_Model->_table_name = "tbl_attendance"; // table name
        $this->Attendance_Model->_primary_key = "attendance_id"; // $id                    
        $attendance_status = $this->input->post('attendance', TRUE);

        $leave_category_id = $this->input->post('leave_category_id', TRUE);

        $employee_id = $this->input->post('employee_id', TRUE);

        $attendance_id = $this->input->post('attendance_id', TRUE);
        if (!empty($attendance_id)) {
            $key = 0;
            foreach ($employee_id as $empID) {
                $data['date'] = $this->input->post('date', TRUE);
                $data['attendance_status'] = 0;
                $data['employee_id'] = $empID;
                if (!empty($leave_category_id[$key])) {
                    $data['leave_category_id'] = $leave_category_id[$key];
                } else {
                    $data['leave_category_id'] = NULL;
                }
                if (!empty($attendance_status)) {
                    foreach ($attendance_status as $v_status) {
                        if ($empID == $v_status) {
                            $data['attendance_status'] = 1;
                            $data['leave_category_id'] = NULL;
                        }
                    }
                }
                $id = $attendance_id[$key];
                if (!empty($id)) {
                    $this->Attendance_Model->save($data, $id);
                } else {
                    $this->Attendance_Model->save($data, $id);
                }

                $key++;
            }
        } else {
            $key = 0;

            foreach ($employee_id as $empID) {
                $data['date'] = $this->input->post('date', TRUE);
                $data['attendance_status'] = 0;
                $data['employee_id'] = $empID;
                if (!empty($leave_category_id[$key])) {
                    $data['leave_category_id'] = $leave_category_id[$key];
                } else {
                    $data['leave_category_id'] = NULL;
                }
                if (!empty($attendance_status)) {
                    foreach ($attendance_status as $v_status) {
                        if ($empID == $v_status) {
                            $data['attendance_status'] = 1;
                            $data['leave_category_id'] = NULL;
                        }
                    }
                }
                $this->Attendance_Model->save($data);
                $key++;
            }
        }
        $fdata['department_id'] = $this->input->post('department_id', TRUE);
        $fdata['date'] = $this->input->post('date');
        $fdata['flag'] = 1;
        $this->session->set_userdata($fdata);
        // messages for user 
        $msg = 'Attendance' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/empAttendance'));

       
    }

    public function attendance_report() {
        $data['title'] = "Attendance Report";
        $this->Attendance_Model->_table_name = "tb_department"; //table name
        $this->Attendance_Model->_order_by = "DepartmentName";
        $data['all_department'] = $this->Attendance_Model->get();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/attendance/attendance_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
     public function get_report() {
        $dist_id = $this->dist_id;
         $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $department_id = $this->input->post('department_id', TRUE);
        $date = $this->input->post('date', TRUE);
        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $data['employee'] = $this->Attendance_Model->get_employee_id_by_dept_id($department_id);
        $day = date('d', strtotime($date));
        for ($i = 1; $i <= $num; $i++) {
            $data['dateSl'][] = $i;
        }
        $holidays = $this->Attendance_Model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->Attendance_Model->get_public_holidays($yymm);


        //tbl a_calendar Days Holiday        
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                for ($k = 1; $k <= $num; $k++) {

                    if ($k >= 1 && $k <= 9) {
                        $sdate = $yymm . '-' . '0' . $k;
                    } else {
                        $sdate = $yymm . '-' . $k;
                    }

                    if ($p_holiday->start_date == $sdate && $p_holiday->end_date == $sdate) {
                        $p_hday[] = $sdate;
                    }
                    if ($p_holiday->start_date == $sdate) {
                        for ($j = $p_holiday->start_date; $j <= $p_holiday->end_date; $j++) {
                            $p_hday[] = $j;
                        }
                    }
                }
            }
        }
        foreach ($data['employee'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {

                if ($i >= 1 && $i <= 9) {

                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));



                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {

                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($flag)) {
                    $data['attendance'][$sl][] = $this->Attendance_Model->attendance_report_by_empid($v_employee->id, $sdate, $flag);
                } else {
                    $data['attendance'][$sl][] = $this->Attendance_Model->attendance_report_by_empid($v_employee->id, $sdate);
                }

                $key++;
                $flag = '';
            }
        } 

         //  echo "<pre>";
         // $tt = $this->db->last_query();
         // print_r($tt);
         // print_r($data['attendance']);
         // exit();       
        $data['title'] = "Attendance Report";
        $this->Attendance_Model->_table_name = "tb_department"; //table name
        $this->Attendance_Model->_order_by = "DepartmentName";
        $data['all_department'] = $this->Attendance_Model->get();
        $data['department_id'] = $this->input->post('department_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('DepartmentID' => $department_id);
        $data['dept_name'] = $this->Attendance_Model->check_by($where, 'tb_department');

        $data['month'] = date('F-Y', strtotime($yymm));
         
        $data['mainContent'] = $this->load->view('distributor/setup/employee/attendance/attendance_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function set_working_days() {


        $data['title'] = "Set Working Days";
        // get all days
        $this->Attendance_Model->_table_name = "tbl_days"; //table name
        $this->Attendance_Model->_order_by = "day_id";
        $data['days'] = $this->Attendance_Model->get();
        // get all working days
        $data['working_days'] = $this->Attendance_Model->get_working_days();

        $data['mainContent'] = $this->load->view('distributor/setup/employee/attendance/set_working_days', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
       public function save_working_days() {
        // delete all working days after save and again save 
        $this->Attendance_Model->delete_all('tbl_working_days');
        $day_id = $this->input->post('day', TRUE);
        $day = $this->input->post('day_id', TRUE);
        $this->Attendance_Model->_table_name = "tbl_working_days"; // table name
        $this->Attendance_Model->_primary_key = "working_days_id"; // $id     
        if (!empty($day)) {
            foreach ($day as $value) {
                $data['flag'] = 0;
                $data['day_id'] = $value;
                if (!empty($day_id)) {
                    foreach ($day_id as $days) {
                        if ($value == $days) {
                            $data['flag'] = 1;
                        }
                    }
                }
                $this->Attendance_Model->save($data);
            }
        }
        //To display confimation message.
        
        $msg = 'Attendance Working Days' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/set_working_days'));

    }

     public function holiday_list($flag = NULL, $id = NULL) {
        $data['title'] = "Holiday List";
        $this->Attendance_Model->_table_name = "tbl_holiday"; //table name
        $this->Attendance_Model->_order_by = "holiday_id";

        // get holiday list by id
        if (!empty($id)) {
            $data['holiday_list'] = $this->Attendance_Model->get_by(array('holiday_id' => $id,), TRUE);

            if (empty($data['holiday_list'])) {
                $msg = 'Attendance Working Days' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
        redirect(site_url($this->project . '/holiday_list'));
            }
        }// click add holiday theb show
        if (!empty($flag)) {
            $data['active_add_holiday'] = $flag;
        }
        // active check with current month
        $data['current_month'] = date('m');
        if ($this->input->post('year', TRUE)) { // if input year 
            $data['year'] = $this->input->post('year', TRUE);
        } else { // else current year
            $data['year'] = date('Y'); // get current year
        }
        // get all holiday list by year and month
        $data['all_holiday_list'] = $this->get_holiday_list($data['year']);  // get current year
        // retrive all data from db   
        $data['mainContent'] = $this->load->view('distributor/setup/employee/attendance/holiday_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
     
    }

     public function get_holiday_list($year) {// this function is to create get monthy recap report 
        for ($i = 1; $i <= 12; $i++) { // query for months
            if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $start_date = $year . "-" . '0' . $i . '-' . '01';
                $end_date = $year . "-" . '0' . $i . '-' . '31';
            } else {
                $start_date = $year . "-" . $i . '-' . '01';
                $end_date = $year . "-" . $i . '-' . '31';
            }
            $get_holiday_list[$i] = $this->Attendance_Model->get_holiday_list_by_date($start_date, $end_date); // get all report by start date and in date 
        }
        return $get_holiday_list; // return the result
    }

        public function save_holiday($id = NULL) {

        $this->Attendance_Model->_table_name = "tbl_holiday"; //table name        
        $this->Attendance_Model->_primary_key = "holiday_id";    //id
        // input data
        $data = $this->Attendance_Model->array_from_post(array('event_name', 'description', 'start_date', 'end_date')); //input post
        // dublicacy check into database
        if (!empty($id)) {
            $holiday_id = array('holiday_id !=' => $id);
        } else {
            $holiday_id = null;
        }
        $where = array('event_name' => $data['event_name'], 'start_date' => $data['start_date']); // where
        // check holiday by where
        // if not empty show alert message else save data
        $check_holiday = $this->Attendance_Model->check_update('tbl_holiday', $where, $holiday_id);

        if (!empty($check_holiday)) {

               $msg = 'Holiday' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
    
        } else {
        $this->Attendance_Model->save($data, $id);
            $msg = 'Holiday' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
        
        }

        redirect(site_url($this->project . '/holiday_list')); //redirect page
    }

    public function delete_holiday_list($id) { // delete holiday list by id
        $this->Attendance_Model->_table_name = "tbl_holiday"; //table name        
        $this->Attendance_Model->_primary_key = "holiday_id";    //id
        $this->Attendance_Model->delete($id);
        $msg = 'Attendance Working Days' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/holiday_list'));//redirect page
    }


    public function leave_category($id = NULL) {
        $data['title'] = "Set Leave Category";


        $this->Attendance_Model->_table_name = "tbl_leave_category"; //table name
        $this->Attendance_Model->_order_by = "leave_category_id";

        // retrive data from db by id
        if (!empty($id)) {
            $data['leave_category'] = $this->Attendance_Model->get_by(array('leave_category_id' => $id,), TRUE);

            if (empty($data['leave_category'])) {
                $msg = 'leave Day' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/leave_category')); 
            }
        }
        // retrive all data from db
        $data['all_leave_category_info'] = $this->Attendance_Model->get();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/attendance/leave_category', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function save_leave_category($id = NULL) {

        $this->Attendance_Model->_table_name = "tbl_leave_category"; //table name        
        $this->Attendance_Model->_primary_key = "leave_category_id";    //id
        // input data
        $data = $this->Attendance_Model->array_from_post(array('category')); //input post
        // dublicacy check 
        if (!empty($id)) {
            $leave_category_id = array('leave_category_id !=' => $id);
        } else {
            $leave_category_id = null;
        }
        // check check_leave_category by where        
        // if not empty show alert message else save data
        $check_leave_category = $this->Attendance_Model->check_update('tbl_leave_category', $where = array('category' => $data['category']), $leave_category_id);

        if (!empty($check_leave_category)) {
            $msg = 'Leave Day' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
        } else {
            $this->Attendance_Model->save($data, $id);
            // messages for user
            $msg = 'Attendance Leave Day' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                            }

       redirect(site_url($this->project . '/leave_category')); //redirect page
    }

    public function delete_leave_category($id) {
        // check into application list
        $where = array('leave_category_id' => $id);
        // check existing leave category into tbl_application_list
       // $check_existing_ctgry = $this->Attendance_Model->check_by($where, 'tbl_application_list');
        // check existing leave category into tbl_attendance
        $check_into_attendace = $this->Attendance_Model->check_by($where, ' tbl_attendance');
        if (!empty($check_into_attendace)) { // if not empty do not delete this else delete
            // messages for user
            $msg = 'Leave Day' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
        } else {
            $this->Attendance_Model->_table_name = "tbl_leave_category"; //table name        
            $this->Attendance_Model->_primary_key = "leave_category_id";    //id
            $this->Attendance_Model->delete($id);
            $msg = 'Attendance Leave Days' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
        }
        redirect(site_url($this->project . '/leave_category')); //redirect page
    }

    
    
   



}
