<?php
class Check_model extends CI_Model
{
public function __construct()
{
	parent::__construct();	
}
	
public function get_data($id="")
{
	if( $id != "")
	{
		$rs = $this->db->where("id_check", $id)->where("status", 0)->where("pause", 0)->get("tbl_check");
	}
	else
	{
		$rs = $this->db->where("status", 0)->where("pause", 0)->get("tbl_check");
	}
	if($rs->num_rows() > 0 )
	{
		return $rs->result();	
	}
	else
	{
		return false;
	}
}

public function get_checked($id)
{
	
	$this->db->select("tbl_check_detail.barcode");
	$this->db->select("item_code");
	$this->db->select_sum("qty");
	$this->db->from("tbl_check_detail");
	$this->db->join("tbl_items", "tbl_items.barcode=tbl_check_detail.barcode");
	$this->db->group_by("tbl_check_detail.barcode");
	$this->db->where("id_check", $id);
	$rs = $this->db->get();
	if( $rs->num_rows() > 0 )
	{
		return $rs->result();
	}
	else
	{
		return false;	
	}
}

public function add_checked($data)
{
	$rs = $this->db->insert("tbl_check_detail", $data);	
	if($rs)
	{
		return $this->db->insert_id();
	}
	else
	{
		return false;
	}
}

public function check_barcode($barcode)
{
	$rs = $this->db->select("item_code")->where("barcode", $barcode)->get("tbl_items");
	if($rs->num_rows() == 1 )
	{
		return $rs->row()->item_code;
	}
	else
	{
		return false;	
	}
}
	
public function get_item_code($barcode)
{
	$rs = $this->db->select("item_code")->where("barcode", $barcode)->get("tbl_items");
	if($rs->num_rows() == 1 )
	{
		return $rs->row()->item_code;
	}
	else
	{
		return "";	
	}
}

public function delete_check_detail($id)
{
	return $this->db->where("id_check_detail", $id)->delete("tbl_check_detail");	
}

public function get_history($id_check, $id_employee, $limit)
{
	$this->db->where("id_check", $id_check)->where("id_employee", $id_employee);
	$this->db->order_by("date_add", "desc")->limit($limit);
	$rs = $this->db->get("tbl_check_detail");
	if( $rs->num_rows() > 0 )
	{ 
		return $rs->result();
	}
	else
	{
		return false;
	}		
}

public function checkStatus($id)
{
	$rs = $this->db->select("status")->where("id_check", $id)->get("tbl_check");
	return $rs->row()->status;	
}

public function checkPause($id)
{
	return $this->db->select("pause")->where("id_check", $id)->get("tbl_check")->row()->pause;	
}
}/// end class

?>