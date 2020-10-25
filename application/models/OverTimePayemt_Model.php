
    <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class OverTimePayemt_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }
       function getAllOverTimeList(){
         $this->db->select("salary_info.*,salary_info.date,salary_info.voucherNo,salary_info.year,salary_info.month");
        $this->db->from("salary_info");
        $this->db->group_by("salary_info.voucherNo");
        $this->db->where('salary_info.voucherType', '5');
        
        $result = $this->db->get()->result();
        return $result;

    }
    function getpercentanceSum(){
         $this->db->select('SUM(percentance) AS perf');
        $this->db->from('salary_overtime_field');
        $this->db->where('id>=',12);
        $this->db->where('id<=',17);
        
        $this->db->where_not_in('id',16);
        

        
        $result = $this->db->get()->result();
        return $result;
    }
    function getpercentanceSubt(){
         $this->db->select('SUM(percentance) AS perf2');
        $this->db->from('salary_overtime_field');
        $this->db->where('id>=',20);
        $this->db->where('id<=',23);
        $result = $this->db->get()->result();
        return $result;
    }

     function getpercentanceForOverTimeHour(){
         $this->db->select('percentance AS overTimeHour');
        $this->db->from('salary_overtime_field');
        $this->db->where('id',18);
        
        $result = $this->db->get()->result();
        return $result;
    }

    function getpercentanceForOverTimeRate(){
         $this->db->select('percentance AS overTimeRate');
        $this->db->from('salary_overtime_field');
        $this->db->where('id',19);
        
        $result = $this->db->get()->result();
        return $result;
    }

     public function getSalaryOverTimeField(){
        $this->db->select('*');
        $this->db->from('salary_overtime_field');
        $this->db->where('id>=',12);
        $this->db->where('id<=',24);
        
        $this->db->where_not_in('id',16);
        $this->db->where_not_in('id',24);

        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }
     public function getSingleOverTimeField($editId){
        $this->db->select('*');
        $this->db->from('salary_overtime_field');
        $this->db->where('id',$editId);
        
        $result = $this->db->get()->result();
        return $result;
    }
    function overTimePaymentDelete($voucher){
        $condition = array(
          'voucherNo' => $voucher
        );
        $this->db->where($condition);
        return $this->db->delete('salary_info');
    }

    function getAllByVoucher($voucher){
        $this->db->select("salary_info.*,employee.id,employee.department,employee.designation,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID','left');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation','left');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID','left');
        $this->db->order_by("salary_info.departmentID", "asc");
        $this->db->where('salary_info.voucherNo', $voucher);
        $this->db->where('salary_info.voucherType', '5');
        $result = $this->db->get()->result();
        return $result;

    }

     function getAllVoucherReport(){
        $this->db->select("salary_info.*,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID','left');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation','left');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID','left');
        $this->db->where('salary_info.voucherType', '5');
        $this->db->order_by("salary_info.departmentID", "asc");
        $result = $this->db->get()->result();
        return $result;

    }

   

}
?>

