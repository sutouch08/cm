<?php
class Authentication extends CI_Controller
{
	public $home;
	public function __construct()
	{
		parent::__construct();
		$this->home = base_url()."authentication";
	}
	
	public function index()
	{
		$this->load->view("login");
	}
	
	public function validate_credentials()
	{
		$this->load->model("login_model");
		$rs = $this->login_model->validate();
		if($rs == 8)
		{
			$data = array(
				"id_user"=>-1,
				"id_employee" => 0,
				"employee"	=> "Supper Admin",
				"user_name"=>"super admin",
				"id_profile"=>0
			);
			$this->session->set_userdata($data);
			redirect(base_url()."index");
		}else if($rs == true)
		{
			$ro = $this->login_model->get_profile($rs->id_user);
			$ra = $this->db->select("first_name")->where("id_employee", $rs->id_employee)->get("tbl_employee");
			$data = array(
				"id_user"			=>$rs->id_user,
				"id_employee" 	=> $rs->id_employee,
				"employee"		=> $ra->row()->first_name,
				"user_name"		=>$rs->user_name,
				"id_profile"		=>$ro->id_profile
			);
			$this->session->set_userdata($data);
			redirect(base_url());
		}else{
			redirect($this->home);
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata("id_user");
		$this->session->unset_userdata("id_employee");
		$this->session->unset_userdata("employee");
		$this->session->unset_userdata("user_name");
		$this->session->unset_userdata("id_profile");
		redirect($this->home);	
	}
	
}

?>