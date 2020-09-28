<?php
class Main extends CI_Controller
{
	public $id_menu 	= 0;
	public $home;
	public $layout 		= "include/template";
	public $title 			= "Check stock";
	
	public function __construct()
	{
		parent:: __construct();
		$this->load->model("cs_model");
		$this->home = base_url()."main";
	}
	
	public function index()
	{	
		$search_text	= "";
		if($this->input->post("search_text") != "")
		{
			$this->session->set_userdata("search_text", $this->input->post("search_text"));
			$search_text 	= $this->input->post("search_text");
		}
		$row 						= $this->cs_model->count_row($search_text);
		$config 					= pagination_config();
		$config['base_url'] 		= $this->home."/index/";
		$config['per_page'] 	= $this->input->cookie('row') ? $this->input->cookie('row') : getConfig("PER_PAGE");
		$config['total_rows'] 	=  $row != false ? $row : 0;
		if($this->session->userdata("search_text"))
		{
			$rs 	= $this->cs_model->get_search_data($this->session->userdata("search_text"), $config['per_page'], $this->uri->segment($config['uri_segment']));
			$txt 	= $this->session->userdata("search_text");
		}
		else
		{
			$rs	= $this->cs_model->get_data("", $config['per_page'], $this->uri->segment($config['uri_segment']));
			$txt	= "";
		}
		$data['data'] 			= $rs;
		$data['id_menu'] 		= $this->id_menu;
		$data['view'] 			= "cs_view";
		$data['page_title'] 		= $this->title;
		$data['row']				= $config['per_page'];
		$data['search_text']	= $txt;
		$this->pagination->initialize($config);	
		$this->load->view($this->layout, $data);
		
	}
	
}/// End class

?>