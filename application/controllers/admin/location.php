<?php
class Location extends CI_Controller
{
public $id_menu = 6;
public $home;
public $layout = "include/template";
public $title = "เพิ่ม/แก้ไข สถานที่";

public function __construct()
{
	parent::__construct();
	$this->load->model("admin/shop_model");
	$this->home = base_url()."admin/location";
}

public function index()
{
	$search_text	= "";
	if($this->input->post("search_text"))
	{
		$this->session->set_userdata("search_text", $this->input->post("search_text"));
		$search_text 	= $this->input->post("search_text");
	}
	$row 						= $this->shop_model->count_row($search_text);
	$config 					= pagination_config();
	$config['base_url'] 		= $this->home."/index/";
	$config['per_page'] 	= $this->input->cookie('row') ? $this->input->cookie('row') : getConfig("PER_PAGE");
	$config['total_rows'] 	=  $row != false ? $row : 0;
	if($this->session->userdata("search_text") !="" )
	{
		$txt 	= $this->session->userdata("search_text");
		$rs 	= $this->shop_model->get_search_data($txt, $config['per_page'], $this->uri->segment($config['uri_segment']));
	}
	else
	{
		$rs	= $this->shop_model->get_data("", $config['per_page'], $this->uri->segment($config['uri_segment']));
		$txt	= "";
	}
	$data['data'] 			= $rs;
	$data['row']				= $config['per_page'];
	$data['search_text']	= $txt;
	$data['id_menu'] 		= $this->id_menu;
	$data['title']				= $this->title;
	$data['view']				= "admin/shop_view";
	$data['is_admin'] = $this->input->cookie('id_profile') <= 2 ? TRUE : FALSE;
	$this->pagination->initialize($config);
	$this->load->view(	$this->layout, $data);
}

public function get_data()
{
	if( $this->input->post("code") )
	{
		$rs 	= $this->shop_model->get_data(trim($this->input->post("code")));
		$data = array(
			"shop_code" => $rs->shop_code,
			"shop_name" => $rs->shop_name,
			"aiq" => $rs->allow_input_qty
		);
		echo json_encode($data);
	}
}

public function update()
{
	if( $this->input->post("shop_code") && $this->input->post("shop_name") && $this->input->post("original_code") )
	{
		$data = array(
			"shop_code" => trim($this->input->post("shop_code")),
			"shop_name" => trim($this->input->post("shop_name")),
			"allow_input_qty" => $this->input->post('allow_input_qty')
		);
		
		$c_code = trim($this->input->post("original_code"));
		$rs 	= $this->shop_model->update_shop($c_code, $data);
		if($rs)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
}

public function delete_shop()
{
	if( $this->input->post("code") )
	{
		$rs = $this->shop_model->delete_shop($this->input->post("code"));
		if($rs)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
}

public function add_shop()
{
	if( $this->input->post("shop_code") && $this->input->post("shop_name") )
	{
		$data = array(
			"shop_code" => trim($this->input->post("shop_code")),
			"shop_name" => trim($this->input->post("shop_name")),
			"allow_input_qty" => $this->input->post("allow_input_qty")
		);

		$rs 	= $this->shop_model->add_shop($data);

		if($rs)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
}
public function valid_shop_code()
{
	if( $this->input->post("shop_code") )
	{
		echo $this->shop_model->valid_code($this->input->post("shop_code"));
	}
}

public function valid_edit_code()
{
	if( $this->input->post("code") && $this->input->post("original_code") )
	{
		echo $this->shop_model->valid_edit_code($this->input->post("code"), $this->input->post("original_code"));
	}
}

public function clear_filter()
{
	$this->session->unset_userdata("search_text");
	redirect($this->home);
}

}/// endclass

?>
