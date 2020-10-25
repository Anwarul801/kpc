<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 10/30/2019
 * Time: 10:59 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class OverTimeConfigureController extends CI_Controller
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

   
    public function overTimeConfiquration()
    {
       
       if (isPostBack()) {

                $this->db->trans_start();
                $allAccess = array();
                $Condition = array(
                
                'isShow' => '0',
              );
                $this->db->update('salary_overtime_field', $Condition);
                 
                    foreach ($_POST['fieldName'] as $key => $value) {
                        unset($data);
                       $get_id = $this->Common_model->get_single_data_by_single_column('salary_overtime_field', 'id', $value);

                       if ($get_id) {
                           $data['id'] =$value;
                           //$data['fieldName'] = $get_id->fieldName;
                           $data['isShow'] = '1';
                           $allAccess[] = $data;                           
                       }
                     }
                $this->db->update_batch('salary_overtime_field', $allAccess,'id');
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be update";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/overTimeConfiquration'));
                } else {
                    $msg = "Your data successfully updated into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/overTimeConfiquration'));

                }
            

        }

        $data['EmployeeField'] = $this->Common_model->get_data_list('salary_overtime_field', 'id', 'ASC');
        $data['Employeevoucher'] = $this->OverTimePayemt_Model->getSalaryOverTimeField();
      

        $data['title'] = 'Over Time Configuration';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $data['pageTitle'] = 'Over Time Configuration';
        $data['mainContent'] = $this->load->view('distributor/setup/employee/overTimePayment/voucherConfiguration', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
   
    public function overTimeConfigEdit($editId){
         if (isPostBack()) {


           $this->form_validation->set_rules('field', 'field', 'required');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/overTimeConfiquration'));
            } else {
                $this->db->trans_start();
                $data['fieldName'] = $this->input->post('field');
                $data['percentance'] = $this->input->post('percentance');
                $data['isShow'] = '1';
                

                $this->Common_model->update_data('salary_overtime_field', $data, 'id', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be Updated";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/overTimeConfiquration'));
                } else {
                    $msg = "Your data successfully Updated into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/overTimeConfiquration'));

                }
            }

        }
        $data['getSingleModel'] = $this->OverTimePayemt_Model->getSingleOverTimeField($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Over Time Configuration Edit');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Confiquration');
        $data['link_page_url'] = $this->project.'/overTimeConfiquration';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/overTimePayment/voucherConfigEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    
   
    public function saveBankBookConfig()
    {

        if ($_POST['action_type'] == 'add') {
            $data['fieldName'] = $fieldName = $this->input->post('employeField');
            $data['isShow'] = '0';
            $this->db->select('count(*) as number_of_result');
            $this->db->from("salary_bonus_field");
            $this->db->where('salary_bonus_field.fieldName', $fieldName);
            $number_of_result = $this->db->get()->row();
            log_message('error', 'thsi is nahid' . print_r($number_of_result->number_of_result, true));

            if ($number_of_result->number_of_result > 0) {
                echo '0';
            } else {
                $this->db->trans_start();
                $this->db->insert('salary_bonus_field', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    echo '0';
                } else {
                    echo '1';
                }
            }


        } else if ($_POST['action_type'] == 'delete') {
            $id = $this->input->post('id');
            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->delete('salary_bonus_field');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }
    /**
     * @return mixed
     */
    public function getconfig()
    {
        $this->db->select('*');
        $this->db->from("salary_bonus_field");
        
        $this->db->limit('1');
        $users = $this->db->get()->row();



        $EmployeeField = $this->Common_model->get_data_list('salary_bonus_field', 'id', 'ASC');

        if (!empty($EmployeeField)) {
            $count = 0;
            foreach ($EmployeeField as $key => $value): $count++;
                echo '<tr>';
                echo '<td>#' . $count . '</td>';
                echo '<td>' . $value->fieldName .  '</td>';

                echo '<td><a href="javascript:void(0);" class="btn btn-danger pull-left" onclick="return confirm(\'Are you sure to delete data?\')?saveconfigaration(\'delete\',\'' . $value->id . '\'):false;"><i class="fa fa-remove"></i></a></td>';
                echo '</tr>';
            endforeach;
        } else {
            echo '<tr><td colspan="5">No Field(s) found......</td></tr>';
        }
    }
}