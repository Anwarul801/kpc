<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of attendance_model
 *
 * @author NaYeM
 */
class Attendance_Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    
    public $_table_name = '';
    public $_primary_key = 'id';
    public $_primary_filter = 'intval';
    public $_order_by = '';
    public $rules = array();
    public $_timestamps = FALSE;

    public function get_employee_id_by_dept_id($department_id) {
        $this->db->select('employee.*', FALSE);
        $this->db->select('tb_designation.*', FALSE);
        $this->db->select('tb_department.*', FALSE);
        $this->db->from('employee');
        $this->db->join('tb_department', 'tb_department.DepartmentID=employee.department','left');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation','left');
        $this->db->where('tb_department.DepartmentID', $department_id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

      public function get_public_holidays($yymm) {
        $this->db->select('tbl_holiday.*', FALSE);
        $this->db->from('tbl_holiday');
        $this->db->like('start_date', $yymm);        
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_holidays() {
        $this->db->select('tbl_working_days.day_id,tbl_working_days.flag', FALSE);
        $this->db->select('tbl_days.day', FALSE);
        $this->db->from('tbl_working_days');
        $this->db->join('tbl_days', 'tbl_days.day_id = tbl_working_days.day_id', 'left');
        $this->db->where('flag', 0);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    function get_employee(){
         $this->db->select("employee.*,employee.salaryType,employee.name,tb_department.DepartmentName,tb_designation.DesignationName,employee.salary,religion.religionName,blood_group.bloodName");
        $this->db->from("employee");
        $this->db->join('tb_department', 'tb_department.DepartmentID=employee.department','left');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation','left');
        $this->db->join('blood_group', 'blood_group.id=employee.bloodGroup','left');
         $this->db->join('religion', 'religion.id=employee.religion','left');
         
        $this->db->where('employee.empStatus', 'Active');
        $this->db->order_by('employee.department','ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    public function save($data, $id = NULL) {

        // Set timestamps
        if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }

        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    public function array_from_post($fields) {
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }

    public function get($id = NULL, $single = FALSE) {

        if ($id != NULL) {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row';
        } elseif ($single == TRUE) {
            $method = 'row';
        } else {
            $method = 'result';
        }

        if (!count($this->db->ar_orderby)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
    }

    public function check_by($where, $tbl_name) {
        $this->db->select('*');
        $this->db->from($tbl_name);
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    public function attendance_report_by_empid($employee_id = null, $sdate = null, $flag = NULL) {

        $this->db->select('tbl_attendance.date,tbl_attendance.attendance_status', FALSE);
        $this->db->select('employee.name', FALSE);
        $this->db->from('tbl_attendance');
        $this->db->join('employee', 'tbl_attendance.employee_id  = employee.id', 'left');
        $this->db->where('tbl_attendance.employee_id', $employee_id);
        $this->db->where('tbl_attendance.date', $sdate);
        $query_result = $this->db->get();
        $result = $query_result->result();

        if (empty($result)) {
            $val['attendance_status'] = $flag;
            $val['date'] = $sdate;
            $result[] = (object) $val;
        } else {
            if ($result[0]->attendance_status == 0) {
                if ($flag == 'H') {
                    $result[0]->attendance_status = 'H';
                }
            }
        }


        return $result;
    }
    
     public function get_working_days() {
        $this->db->select('tbl_working_days.*', FALSE);
        $this->db->select('tbl_days.day', FALSE);
        $this->db->from('tbl_working_days');
        $this->db->join('tbl_days', 'tbl_working_days.day_id = tbl_days.day_id', 'left');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    public function delete_all($table) {
        $this->db->empty_table($table);
    }
    public function delete($id) {
        $filter = $this->_primary_filter;
        $id = $filter($id);

        if (!$id) {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }
 public function check_update($table, $where, $id = Null) {
        $this->db->select('*', FALSE);
        $this->db->from($table);
        if ($id != null) {
            $this->db->where($id);
        }
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_holiday_list_by_date($start_date, $end_date) {
        $this->db->select('tbl_holiday.*', FALSE);
        $this->db->from('tbl_holiday');
        $this->db->where('start_date >=', $start_date);
        $this->db->where('end_date <=', $end_date);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
     public function get_by($where, $single = FALSE) {
        $this->db->where($where);
        return $this->get(NULL, $single);
    }


}
