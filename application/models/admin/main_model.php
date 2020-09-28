<?php
class Main_model extends CI_Model
{
	
public function __construct()
{
	parent:: __construct();
}

public function get_data($id="", $perpage="", $limit ="")
{
	if($id !=""){
		$rs = $this->db->get_where("tbl_check", array("id_check"=>$id), 1);
		if($rs->num_rows() == 1){
			return $rs->row();
		}else{
			return false;
		}
	}else{
		$this->db->order_by("date_open","desc");
		$rs = $this->db->limit($perpage, $limit)->get("tbl_check");
		if($rs->num_rows() >0 ){
			return $rs->result();
		}else{
			return false;
		}
	}
}

public function get_search_data($txt, $filter, $from ="", $to ="", $perpage ="", $limit ="")
{
	if($from !="" && $to != "")
	{
		$this->db->where("date_open >=", fromDate($from))->where("date_open <=", toDate($to));
	}
	if($filter == "all")
	{
		$this->db->like("reference", $txt)->or_like("subject", $txt)->or_like("location", $txt)->or_like("employee", $txt);
	}
	else
	{
		$this->db->like($filter, $txt);
	}
	$rs = $this->db->order_by("date_open", "desc")->limit($perpage, $limit)->get("tbl_check");
	if($rs->num_rows() >0 ){
		return $rs->result();
	}else{
		return false;
	}
}

public function count_row($txt = "", $filter="", $from = "", $to = "")
{
	if($txt != "")
	{
		if($from !="" && $to != "")
		{
			$this->db->where("date_open >=", fromDate($from))->where("date_open <=", toDate($to));
		}
		if($filter == "all")
		{
			$rs = $this->db->like("reference", $txt)->or_like("subject", $txt)->or_like("location", $txt)->or_like("employee", $txt)->get("tbl_check");
		}
		else
		{
			$rs = $this->db->like($filter, $txt)->get("tbl_check");
		}
	}
	else
	{
		$rs = $this->db->get("tbl_check");	
	}
	return $rs->num_rows();
}

public function add_new_check($data)
{
	return $this->db->insert("tbl_check", $data);	
}

public function set_import($id, $val)
{
	return $this->db->set("import", $val)->where("id_check", $id)->update("tbl_check");	
}

public function validate_multi_check()
{
	$rs = $this->db->select("id_check")->where("pause", 0)->get("tbl_check");
	return $rs->num_rows();	
}

public function pause($id, $data)
{
	return $this->db->where("id_check", $id)->update("tbl_check", $data);	
}

public function continue_check($id, $data)
{
	return $this->db->where("id_check", $id)->update("tbl_check", $data);	
}
public function location_code($id)
{
	$rs = $this->db->select("location_code")	->where("id_check", $id)->get("tbl_check");
	if($rs->num_rows() == 1 )
	{
		return $rs->row()->location_code;
	}
	else
	{
		return false;
	}
}

public function import_item($data)
{
	return $this->db->insert("tbl_items_import", $data);	
}

public function delete_imported_items($id)
{
	return $this->db->where("id_check", $id)->delete("tbl_items_import");
}

public function update_import_item($id, $data)
{
	return $this->db->where("id", $id)->update("tbl_items_import", $data);	
}

public function update_check($id, $data)
{
	return $this->db->where("id_check", $id)->update("tbl_check", $data);	
}

public function isExists($barcode, $id)
{
	$rs = $this->db->select("id")->where("barcode", $barcode)->where("id_check", $id)->get("tbl_items_import");
	if($rs->num_rows() == 1)
	{
		return $rs->row()->id;
	}
	else
	{
		return false;
	}
}

public function isPause($id)
{
	$rs = $this->db->select("pause")->where("id_check", $id)->get("tbl_check");	
	if($rs->num_rows() == 1)
	{
		return $rs->row()->pause;
	}
	else
	{
		return 0;	
	}
}

public function close_check($id)
{
	return $this->db->where("id_check", $id)->update("tbl_check", array("status" =>'1', "date_close"=>NOW() ) );	
}

public function open_check($id)
{
	return $this->db->where("id_check", $id)->update("tbl_check", array("status"=>'0', "date_close" => NULL));	
}

public function deleteChecked($id)
{
	$this->db->trans_start();
	$this->db->where("id_check", $id)->delete("tbl_check_detail");
	$this->db->where("id_check", $id)->delete("tbl_items_import");
	$this->db->where("id_check", $id)->delete("tbl_check");
	$this->db->trans_complete();
	if($this->db->trans_status() === false)
	{
		return false;	
	}
	else
	{
		return true;
	}		
}
}/// end class
?>