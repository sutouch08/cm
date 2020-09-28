<?php
class Shop_model extends CI_Model
{
public function __construct()
{
	parent::__construct();
}

public function get_data($code="", $perpage="", $limit ="")
{
	if($code !="")
	{
		$rs = $this->db->get_where("tbl_shop", array("shop_code"=>$code), 1);
		if($rs->num_rows() == 1){
			return $rs->row();
		}
		else
		{
			return false;
		}
	}
	else
	{
		$rs = $this->db->limit($perpage, $limit)->get("tbl_shop");
		if($rs->num_rows() >0 )
		{
			return $rs->result();
		}
		else
		{
			return false;
		}
	}
}



public function get($code)
{
	$rs = $this->db->where('shop_code', $code)->get('tbl_shop');
	if($rs->num_rows() === 1)
	{
		return $rs->row();
	}

	return FALSE;
}



public function get_search_data($txt, $perpage ="", $limit ="")
{
	$rs = $this->db->like("shop_code", $txt)->or_like("shop_name", $txt)->get("tbl_shop");
	if($rs->num_rows() >0 )
	{
		return $rs->result();
	}
	else
	{
		return false;
	}
}

public function count_row($txt = "")
{
	if($txt != "")
	{
		$rs = $this->db->like("shop_code", $txt)->or_like("shop_name", $txt)->get("tbl_shop");
	}
	else
	{
		$rs = $this->db->get("tbl_shop");
	}
	return $rs->num_rows();
}

public function add_shop($data)
{
	return $this->db->insert("tbl_shop", $data);
}

public function update_shop($code, $data)
{
	return $this->db->where("shop_code", $code)->update("tbl_shop", $data);
}

public function delete_shop($code)
{
	return $this->db->where("shop_code", $code)->delete("tbl_shop");
}

public function valid_code($code)
{
	$rs = $this->db->where("shop_code", $code)->get("tbl_shop");
	return $rs->num_rows();
}

public function valid_edit_code($new, $curent)
{
	$rs = $this->db->where("shop_code", $new)->where("shop_code !="	, $curent)->get("tbl_shop");
	return $rs->num_rows();
}


}/// end class

?>
