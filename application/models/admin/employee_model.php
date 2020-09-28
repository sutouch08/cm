<?php 
class Employee_model extends CI_Model
{
	public function __construch()
	{
		parent::__construct();
	}
	
	public function get_employee($id)
	{
		if($id != "")
		{
			$rs = $this->db->get_where("tbl_employee", array("id_employee" => $id));
			return $rs->row();	
		}
	}
	


	public function add_employee($data)
	{
		$qs = $this->db->insert("tbl_employee", $data);
		if($qs)
		{
			return $this->db->insert_id();
		}
		else
		{
			return 0;
		}
	}
	
	public function isExistsEmployee($first_name, $last_name)
	{
		$rs = $this->db->where("first_name", $first_name)->where("last_name", $last_name)->get("tbl_employee");
		return $rs->num_rows();
	}
	
	public function isExistsCode($code)
	{
		$rs = $this->db->where("code", $code)->get("tbl_employee");
		return $rs->num_rows();
	}
	
	public function update($id, $data)
	{
		$rs = $this->db->where("id_employee", $id)->update("tbl_employee", $data);
		return $rs;		
	}
	
	public function delete_employee($id)
	{
		return $this->db->where("id_employee", $id)->delete("tbl_employee");	
	}
	
	public function count_row($txt = "")
	{
		if($txt != "")
		{
			$rs = $this->db->like("code", $txt)->or_like("first_name", $txt)->or_like("last_name", $txt)->get("tbl_employee");
		}
		else
		{
			$rs = $this->db->get("tbl_employee");
		}
		if($rs->num_rows() >0 ){
			return $rs->num_rows();
		}else{
			return false;
		}
	}

	/*************************  employee  ****************************/
	public function get_data($id="", $perpage="", $limit ="")
	{
		if($id !=""){
			$rs = $this->db->get_where("tbl_employee", array("id_employee"=>$id), 1);
			if($rs->num_rows() == 1){
				return $rs->result();
			}else{
				return false;
			}
		}else{
			$this->db->order_by("date_upd","desc");
			$rs = $this->db->limit($perpage, $limit)->get("tbl_employee");
			if($rs->num_rows() >0 ){
				return $rs->result();
			}else{
				return false;
			}
		}
	}
	
	public function get_search_data($txt, $perpage="", $limit ="")
	{
		$this->db->like("code", $txt)->or_like("first_name", $txt)->or_like("last_name", $txt);
			$rs = $this->db->limit($perpage, $limit)->get("tbl_employee");
			if($rs->num_rows() >0 ){
				return $rs->result();
			}else{
				return false;
			}
	}

	public function check_code($code, $id = "")
	{
		if($id != "")
		{
			$rs = $this->db->where("code", $code)->where("id_employee !=", $id)->get("tbl_employee");
		}
		else
		{
			$rs = $this->db->where("code", $code)->get("tbl_employee");
		}
		return $rs->num_rows();		
	}
	
	public function check_name($first_name, $last_name, $id = "")
	{
		if($id != "")
		{
			$rs = $this->db->where("first_name", $first_name)->where("last_name", $last_name)->where("id_employee !=", $id)->get("tbl_employee");	
		}
		else
		{
			$rs = $this->db->where("first_name", $first_name)->where("last_name", $last_name)->get("tbl_employee");	
		}
		return $rs->num_rows();
	}
	
	

	
	
}// End class

?>