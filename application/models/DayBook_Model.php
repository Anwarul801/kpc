<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DayBook_Model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function getalldayBook(){
        $this->db->select("*");
        $this->db->from("day_book_report_config");
        $result = $this->db->get()->result();
        return $result;
    }

     public function checkdayBook($id){
        $this->db->select("*");
        $this->db->from("day_book_report_config");
        $this->db->where('acc_group_id', $id);
        $result = $this->db->get()->result();
        return $result;
    }
     public function delete_daybook($id){
        $this->db->where('id', $id);
        return $this->db->delete('day_book_report_config');
    }

}

?>