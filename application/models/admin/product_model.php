<?php
class Product_model extends CI_Model
{
	public function __construch()
	{
		parent::__construct();
	}

	public function get_item($id)
	{
		if($id != "")
		{
			$rs = $this->db->get_where("tbl_items", array("id_item" => $id));
			return $rs->row();
		}
	}

	public function delete_item($id)
	{
		$rs = $this->db->where("id_item", $id)->delete("tbl_items");
		return $rs;
	}


	public function check_barcode($barcode, $id = "")
	{
		$barcode = trim($barcode);
		if($id != "")
		{
			$rs = $this->db->where("barcode", $barcode)->where("id_item !=", $id)->get("tbl_items");
		}
		else
		{
			$rs = $this->db->get_where("tbl_items", array("barcode"=>$barcode));
		}
		return $rs->num_rows();
	}

	public function add_item($data)
	{
		$qs = $this->db->insert("tbl_items", $data);
		if($qs)
		{
			return $this->db->insert_id();
		}
		else
		{
			return 0;
		}
	}


	public function isExists($barcode)
	{
		$rs = $this->db->where("barcode", $barcode)->get("tbl_items");
		return $rs->num_rows();
	}


	public function update_import_item($barcode, $data)
	{
		return $this->db->where("barcode", $barcode)->update("tbl_items", $data);
	}


	public function update_item($id, $data)
	{
		$rs = $this->db->where("id_item", $id)->update("tbl_items", $data);
		return $rs;
	}




	public function count_row($txt)
	{
		if(!empty($txt))
		{
			$this->db->like('item_code', $txt)->or_like('item_code', $txt)->or_like('style', $txt)->or_like('barcode', $txt);
		}
		return $this->db->count_all_results('tbl_items');
	}


	public function search_count_row($txt)
	{
		$this->db->like("item_code", $txt)->or_like("item_name", $txt);
		$rs = $this->db->get("tbl_items");
		if($rs->num_rows() >0 ){
			return $rs->num_rows();
		}else{
			return false;
		}
	}
	/*************************  Product  ****************************/
	public function get_data($id="", $perpage="", $limit ="")
	{
		if($id !=""){
			$rs = $this->db->get_where("tbl_items", array("id_item"=>$id), 1);
			if($rs->num_rows() == 1){
				return $rs->result();
			}else{
				return false;
			}
		}else{
			$this->db->order_by("date_upd","desc");
			$rs = $this->db->limit($perpage, $limit)->get("tbl_items");
			if($rs->num_rows() >0 ){
				return $rs->result();
			}else{
				return false;
			}
		}
	}

	public function get_search_data($txt, $perpage="", $offset ="")
	{
		//$this->db->where('id_item >', 0);

		if(!empty($txt))
		{
			$this->db->like('item_code', $txt)->or_like('item_code', $txt)->or_like('style', $txt)->or_like('barcode', $txt);
		}

		if(!empty($perpage))
		{
			$offset = empty($offset) ? 0 : $offset;
			$this->db->limit($perpage, $offset);
		}

		$rs = $this->db->get('tbl_items');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return FALSE;

	}



	public function update($id, $data)
	{
		$rs = $this->db->where("id_item", $id)->update("tbl_items", $data);
		if($rs)
		{
			return true;
		}else{
			return false;
		}
	}

	/********************************* End Product  *********************************/

	public function get_items($barcode)
	{
		$rs = $this->db->where("barcode", $barcode)->get("tbl_items");
		if( $rs->num_rows() == 1 )
		{
			return $rs->row_array();
		}
		else
		{
			$data = array(
								'id_item' 	=> '',
								'barcode'	=> $barcode,
								'item_code'	=> '',
								'item_name'	=> '',
								'style'			=> '',
								'cost'			=> 0.00,
								'price'			=> 0.00,
								'active'		=> 1,
								'date_upd'	=> '',
								'type'			=> 0
								);
			return $data;
		}
	}


	///------------------------------------------------------------  IX sync items ------------------------//
	public function count_ix_update_list($date_add, $date_upd)
  {
		return $this->ix
		->where('date_add >', $date_add)
		->or_where('date_upd >', $date_upd)
		->count_all_results('products');
  }


	public function get_ix_list($date_add, $date_upd, $limit, $offset)
  {
		$qr = "SELECT code, name, barcode, style_code, cost, price ";
		$qr .= "FROM products ";
		$qr .= "WHERE count_stock = 1 ";
		$qr .= "AND barcode IS NOT NULL ";
		$qr .= "AND (date_add > '{$date_add}' OR date_upd > '{$date_upd}') ";
		$qr .= "LIMIT {$limit} OFFSET {$offset}";

		$rs = $this->ix->query($qr);
		// $rs = $this->ix
		// ->select('code, name, barcode, style_code, cost, price')
		// ->where('count_stock', 1)
		// ->where('barcode IS NOT NULL', NULL, FALSE)
		// ->group_start()
		// ->where('date_add >', $date_add)
		// ->or_where('date_upd >', $date_upd)
		// ->group_end()
		// ->limit($limit, $offset)
		// ->get('products');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


	public function is_exists($barcode)
	{
		$rs = $this->db->select('id_item AS id')->where('barcode', $barcode)->get('tbl_items');
		if($rs->num_rows() > 0)
		{
			return $rs->row()->id;
		}

		return FALSE;
	}


	public function get_items_last_sync()
	{
		$rs = $this->db->select_max('last_sync')->get('tbl_items');
		if($rs->num_rows() === 1)
    {
      return $rs->row()->last_sync === NULL ? date('2019-01-01 00:00:00') : date('Y-m-d 00:00:00', strtotime($rs->row()->last_sync));
    }

    return date('2019-01-01 00:00:00');
	}

}// End class

?>
