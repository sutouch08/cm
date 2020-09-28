<?php
class Tool extends CI_Controller
{
	public function __construct()
	{
		parent:: __construct();
	}
	
		
	public function set_rows()
	{
		$cookie = array("name"=>"row", "value"=>$this->input->post('rows'), "expire"=>"865000000");
		$this->input->set_cookie($cookie);
		echo "success";		
	}
	
	public function check_download()
	{
		$token = $this->input->post("token");
		$co = $this->session->userdata("token");
		if( $co == $token)
		{
			echo "finished";
		}
		else
		{
			echo "still download";
		}
	}
	
	public function finished_download()
	{
		$this->session->unset_userdata("token");
	}

	
}/// endclass


?>