<?php 
class User_model extends CI_Model
{
	public function __construch()
	{
		parent::__construct();
	}
	
	public function get_user($id)
	{
		if($id != "")
		{
			$rs = $this->db->get_where("tbl_user", array("id_user" => $id));
			return $rs->row();	
		}
	}
	


	public function add_user($data)
	{
		$qs = $this->db->insert("tbl_user", $data);
		if($qs)
		{
			return $this->db->insert_id();
		}
		else
		{
			return 0;
		}
	}
	
	public function update($id, $data)
	{
		$rs = $this->db->where("id_user", $id)->update("tbl_user", $data);
		return $rs;		
	}
	
	public function delete($id)
	{
		return $this->db->where("id_user", $id)->delete("tbl_user");	
	}
	
	public function count_row($txt = "")
	{
		if($txt != "")
		{
			$rs = $this->db->like("code", $txt)->or_like("first_name", $txt)->or_like("last_name", $txt)->get("tbl_user");
		}
		else
		{
			$rs = $this->db->get("tbl_user");
		}
		if($rs->num_rows() >0 ){
			return $rs->num_rows();
		}else{
			return false;
		}
	}

	/*************************  user  ****************************/
	public function get_data($id="", $perpage="", $limit ="")
	{
		if($id !=""){
			$rs = $this->db->get_where("tbl_user", array("id_user"=>$id), 1);
			if($rs->num_rows() == 1){
				return $rs->result();
			}else{
				return false;
			}
		}else{
			$this->db->order_by("date_upd","desc");
			$rs = $this->db->limit($perpage, $limit)->get("tbl_user");
			if($rs->num_rows() >0 ){
				return $rs->result();
			}else{
				return false;
			}
		}
	}
	
	public function get_search_data($txt, $perpage="", $limit ="")
	{
		$this->db->select("id_user, tbl_user.id_employee, id_profile, user_name, date_add, date_upd, last_login");
		$this->db->from("tbl_user");
		$this->db->join("tbl_employee", "tbl_employee.id_employee = tbl_user.id_employee");
		$this->db->like("user_name", $txt)->or_like("first_name", $txt)->or_like("last_name", $txt);
		$rs = $this->db->limit($perpage, $limit)->get("tbl_user");
		if($rs->num_rows() >0 ){
			return $rs->result();
		}else{
			return false;
		}
	}

	public function get_employee()
	{
		$data = "";
		$rs = $this->db->get("tbl_employee");	
		if($rs->num_rows() > 0 )
		{
			$i = 1;
			$n = $rs->num_rows();
			foreach($rs->result() as $ro)
			{
				$da = $ro->id_employee." | ".$ro->first_name." ".$ro->last_name;
				$data .= "'".$da."'";
				if($i < $n){ $data .= ", "; }
				$i++;	
			}
		}
		return $data;
	}

	public function check_user($user_name, $id="")
	{
		if($id != "")
		{
			$rs = $this->db->where("user_name", $user_name)->where("id_user !=", $id)->get("tbl_user");	
		}
		else
		{
			$rs = $this->db->where("user_name", $user_name)->get("tbl_user");
		}
		return $rs->num_rows();
	}
	
	public function check_employee($id_employee, $id_user = "")
	{
		if($id_user != "")
		{
			$rs = $this->db->where("id_employee", $id_employee)->where("id_user !=", $id_user)->get("tbl_user");
		}
		else
		{
			$rs = $this->db->where("id_employee", $id_employee)->get("tbl_user");
		}
		return $rs->num_rows();
	}
	
	
}// End class

?>