
    <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class PerformanceBonus_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }
       function getAllPerformanceBonusDateList(){
         $this->db->select("salary_info.*,salary_info.date,salary_info.voucherNo,salary_info.year,salary_info.month");
        $this->db->from("salary_info");
        $this->db->group_by("salary_info.voucherNo");
        $this->db->where('salary_info.voucherType', '3');
        
        $result = $this->db->get()->result();
        return $result;

    }
    function getpercentanceSum(){
         $this->db->select('SUM(percentance) AS perf');
        $this->db->from('salary_bonus_field');
        $this->db->where('id>=',12);
        $this->db->where('id<=',17);
        
        $this->db->where_not_in('id',16);
        

        
        $result = $this->db->get()->result();
        return $result;
    }
    function getpercentanceSubt(){
         $this->db->select('SUM(percentance) AS perf2');
        $this->db->from('salary_bonus_field');
        $this->db->where('id>=',18);
        $this->db->where('id<=',21);
        
       
        

        
        $result = $this->db->get()->result();
        return $result;
    }

    function performanceVoucherDelete($voucher){
        $condition = array(
          'voucherNo' => $voucher
        );
        $this->db->where($condition);
        return $this->db->delete('salary_info');
    }

    function getAllByVoucher($voucher){
        $this->db->select("salary_info.*,employee.id,employee.department,employee.designation,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID');
        $this->db->order_by("salary_info.departmentID", "asc");
        $this->db->where('salary_info.voucherNo', $voucher);

        $result = $this->db->get()->result();
        return $result;

    }

     function getAllVoucherReport(){
        $this->db->select("salary_info.*,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID','left');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation','left');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID','left');
        $this->db->order_by("salary_info.departmentID", "asc");
        $this->db->where('salary_info.voucherType', '3');
        $this->db->where('salary_info.isDelete', 'N');
        $result = $this->db->get()->result();
        return $result;

    }

   

}
?>

