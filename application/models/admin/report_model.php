<?php
class Report_model extends CI_Model
{
	public function __construct()
	{
		parent:: __construct();
	}

	public function get_data($id="", $perpage = 20, $offset = 0)
	{
		if(!empty($id))
		{
			$rs = $this->db->where('id_check', $id)->where('status', 1)->get('tbl_check');

			if($rs->num_rows() == 1)
			{
				return $rs->row();
			}

			return FALSE;
		}
		else
		{
			$rs = $this->db
			->where('status', 1)
			->order_by('date_open', 'DESC')
			->limit($perpage, $offset)
			->get('tbl_check');

			if($rs->num_rows() > 0 )
			{
				return $rs->result();
			}

			return FALSE;
		}
	}



	public function get_search_data($txt, $filter, $from ="", $to ="", $perpage ="", $limit ="")
	{
		$this->db->where("status", 1);
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
		$this->db->where("status", 1);
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


	public function get_summary_report($id)
	{
		$qr  = "SELECT ckd.barcode, SUM(ckd.qty) AS qty, ";
		$qr .= "pd.item_code, pd.item_name, pd.cost, pd.price, im.qty AS instock ";
		$qr .= "FROM tbl_check_detail AS ckd ";
		$qr .= "LEFT JOIN tbl_items AS pd ON ckd.barcode = pd.barcode ";
		$qr .= "LEFT JOIN tbl_items_import AS im ON ckd.barcode = im.barcode AND ckd.id_check = im.id_check ";
		$qr .= "WHERE ckd.id_check = {$id} ";
		$qr .= "GROUP BY ckd.barcode";

		$rs = $this->db->query($qr);


		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return false;
		}
	}

	public function get_detail_report($id)
	{
		$rs = $this->db->where("id_check", $id)->get("tbl_check_detail");
		return $rs->result();
	}

	public function get_employee($id) //// พนักงานที่ตรวจเช็คสินค้า
	{
		$rs = $this->db->select("first_name")->from("tbl_check_detail")->join("tbl_employee", "tbl_employee.id_employee = tbl_check_detail.id_employee")->where("id_check", $id)->group_by("tbl_check_detail.id_employee")->get();
		if($rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return false;
		}
	}

	public function get_imported_qty($id, $barcode)
	{
		$rs = $this->db->select("qty")->where("id_check", $id)->where("barcode", $barcode)->get("tbl_items_import");
		if($rs->num_rows() == 1 )
		{
			return $rs->row()->qty;
		}
		else
		{
			return 0;
		}
	}


	//--- รายการ ที่มีการนำเข้ายอดตั้งต้นไว้แต่ว่าไมมีรายการตรวจนับ
	public function get_instock_but_not_check($id)
	{
		$qr  = "SELECT im.barcode, im.qty, pd.item_code, pd.item_name, pd.cost, pd.price ";
		$qr .= "FROM tbl_items_import AS im ";
		$qr .= "LEFT JOIN tbl_items AS pd ON im.barcode = pd.barcode ";
		$qr .= "LEFT JOIN tbl_check_detail AS ckd ON im.barcode = ckd.barcode AND im.id_check = ckd.id_check ";
		$qr .= "WHERE ckd.barcode IS NULL ";
		$qr .= "AND im.id_check = {$id}";

		$rs = $this->db->query($qr);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return FALSE;
	}



	public function get_imported_item($id)
	{
		$rs = $this->db->select("barcode, item_code, qty")->where("id_check", $id)->get("tbl_items_import");
		if($rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return false;
		}
	}

	public function isExists($id, $barcode)
	{
		$this->db->where("id_check", $id)->where("barcode", $barcode)->from("tbl_check_detail");
		return $this->db->count_all_results();
	}

	public function get_group_type($barcode)
	{
		$rs = $this->db->select("group_name")->from("tbl_items")->join("tbl_item_group", "tbl_item_group.group_type = tbl_items.type")->where("barcode", $barcode)->get();
		if($rs->num_rows() == 1 )
		{
			return $rs->row()->group_name;
		}
		else
		{
			return "";
		}
	}
}// end class

?>
