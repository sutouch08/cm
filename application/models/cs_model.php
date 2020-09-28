<?php

class Cs_model extends CI_Controller
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
	if($txt != "" && $filter !="")
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
	
}/// end class

?>