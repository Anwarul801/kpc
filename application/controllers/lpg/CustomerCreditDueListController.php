<?php
/**
 * Created by PhpStorm.
 * User: AEL
 * Date: 10/9/2019
 * Time: 1:51 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class CustomerCreditDueListController extends CI_Controller
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
        $this->load->model('Inventory_Model');
        $this->load->model('CustomerCrditDue');
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
   
  
   
    public function cus_credit_due_List()
    {
        
        
        $data['title'] = 'Customer Credit Due List';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        
        $data['creditDue'] = $this->CustomerCrditDue->cusCreditDue();
        // 
        $data['mainContent'] = $this->load->view('distributor/sales/customer/customerCreditDueList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}