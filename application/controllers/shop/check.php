<?php
class Check extends CI_Controller
{
	public $id_menu 	= 4;
	public $home;
	public $layout 		= "include/template";
	public $title 			= "ตรวจนับสินค้า";

public function __construct()
{
	parent:: __construct();
	$this->load->model("shop/check_model");
	$this->home = base_url()."shop/check";
}

public function index($id="")
{
	if($id !="")
	{
		$data['data']			= $this->check_model->get_data($id);
		$data['id_check'] 	= $id;
		$data['checked']	= $this->check_model->get_checked($id);
	}
	else
	{
		$data['data'] 	= $this->check_model->get_data();
	}

	$data['id_menu'] 	= $this->id_menu;
	$data['view'] 		= "shop/check_view";
	$data['page_title'] 	= $this->title;
	$this->load->view($this->layout, $data);
}

public function pause()
{
	seterror("การตรวจนับถูกระงับ กรุณาติดต่อผู้ควบคุมการตรวจนับ");
	redirect($this->home);
}
public function do_checking()
{
	if( $this->input->post("id_check") && $this->input->post("barcode") )
	{
		$barcode				= trim($this->input->post("barcode"));
		$rd 						= $this->check_model->check_barcode($barcode);
		if($rd)
		{
			$qty = $this->input->post('qty') ? $this->input->post('qty') : 1;
			$data['id_check']		= $this->input->post("id_check");
			$data['barcode'] 		= $barcode;
			$data['qty'] 				= $qty;
			$data['id_employee']	= $this->session->userdata("id_employee");
			$rs 						= $this->check_model->add_checked($data);
			if($rs)
			{
				$re = array(
					"id" => $rs,
					"barcode" => $barcode,
					"product" => $rd,
					"qty" => $qty,
					"timestamp" => date("H:i:s") );
				$re = json_encode($re);
			}
			else
			{
				$re = "fail";
			}
		}
		else
		{
			$re = "NoItem";
		}
		echo $re;
	}
}

public function get_item_code($barcode)
{
	$item_code = $this->check_model->get_item_code($barcode);
	$rs = array("barcode" => $barcode, "product" => $item_code, "qty" => 1);
	echo json_encode($rs);
}

public function item_code($barcode)
{
	return $this->check_model->get_item_code($barcode);
}
public function delete_check_detail($id)
{
	$rs = $this->check_model->delete_check_detail($id);
	if($rs)
	{
		echo "success";
	}
	else
	{
		echo "fail";
	}
}

public function get_history()
{
	$id 	= $this->input->post("id_check");
	$qty	= $this->input->post("qty");
	$emp	= $this->session->userdata("id_employee");
	$ds 	= array();
	$data	= $this->check_model->get_history($id, $emp, $qty);
	if($data)
	{
		foreach($data as $rs)
		{
			$arr = array(
				"id" => $rs->id_check_detail,
				"barcode" => $rs->barcode,
				"product" => $this->item_code($rs->barcode),
				"qty" => $rs->qty,
				"timestamp" => date("H:i:s", strtotime($rs->date_add)));
			array_push($ds, $arr);
		}
	}
	else
	{
		$arr = array("no_content"=>"nocontent");
		array_push($ds, $arr);
	}
	echo json_encode($ds);
}

public function checkStatus($id)
{
	echo $this->check_model->checkStatus($id);
}

public function checkPause($id)
{
	echo $this->check_model->checkPause($id);
}

}//// end class


?>
