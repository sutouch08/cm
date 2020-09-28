<?php
class User extends CI_Controller
{
	public $id_menu = 3;
	public $home;
	public $layout = "include/template";
	public $title = "เพิ่ม/แก้ไข ชื่อผู้ใช้งาน";
		
	public function __construct()
	{
		parent:: __construct();
		$this->home = base_url()."admin/user";
		$this->load->model("admin/user_model");
	}
	
	public function index()
	{	
		$user_search	= "";
		if($this->input->post("user_search") != "")
		{
			$this->session->set_userdata("user_search", $this->input->post("user_search"));
			$user_search 	= $this->input->post("user_search");
		}
		$row 						= $this->user_model->count_row($user_search);
		$config 					= pagination_config();
		$config['base_url'] 		= $this->home."/index/";
		$config['per_page'] 	= $this->input->cookie('row') ? $this->input->cookie('row') : getConfig("PER_PAGE");
		$config['total_rows'] 	=  $row != false ? $row : 0;
		if($this->session->userdata("user_search"))
		{
			$rs 	= $this->user_model->get_search_data($this->session->userdata("user_search"), $config['per_page'], $this->uri->segment($config['uri_segment']));
			$txt 	= $this->session->userdata("user_search");
		}
		else
		{
			$rs	= $this->user_model->get_data("", $config['per_page'], $this->uri->segment($config['uri_segment']));
			$txt	= "";
		}
		$data['data'] 			= $rs;
		$data['emp_list']		= $this->user_model->get_employee();
		$data['id_menu'] 		= $this->id_menu;
		$data['view'] 			= "admin/user_view";
		$data['page_title'] 		= $this->title;
		$data['row']				= $config['per_page'];
		$data['user_search']	= $txt;
		$this->pagination->initialize($config);	
		$this->load->view($this->layout, $data);
	}
	
	public function get_user($id)
	{
		$rs = $this->user_model->get_user($id);
		if($rs)
		{
			$data = array(
						"id"  => $rs->id_user,
						"id_employee" => $rs->id_employee,
						"employee" => employee_name($rs->id_employee),
						"user_name" => $rs->user_name,
						"profile" => select_profile($rs->id_profile),
						"active" => $rs->active,
						"enable" => $rs->active == 1 ? "btn-success" : "",
						"disable" => $rs->active == 0 ? "btn-danger" : ""
						);
			echo json_encode($data);
		}
		else
		{
			echo "fail";
		}							
	}
	
	
	public function add_user()
	{
		$res = "fail";
		$err = 0;
		if($this->input->post("id_employee"))
		{
			$data = array(
						"id_employee" => $this->input->post("id_employee"),
						"id_profile" => $this->input->post("profile"),
						"user_name" => $this->input->post("user_name"),
						"password" => md5($this->input->post("password")),
						"date_add" => NOW(),
						"active" => $this->input->post("active")
						);
			$ra = $this->user_model->check_user($data['user_name']);
			$ro = $this->user_model->check_employee($data['id_employee']);
			if($ra)
			{
				$err = 1;
				$res = "duplicate_user";
			}
			
			if($ro)
			{
				$err = 1;
				$res = "duplicate_employee";
			}
			if( !$err )
			{
				$rs = $this->user_model->add_user($data);
				if($rs)
				{
					$result = array(
								"id" => $rs,
								"user_name" => $data['user_name'],
								"employee" => employee_name($data['id_employee']),
								"last_login" => "ยังไม่เคยเข้าระบบ",
								"active"	=> isActived($data['active'])
								);
					$res = json_encode($result);
				}
			}
		}
		else
		{
			$res = "fail";	
		}
		echo $res;
	}
	
	public function update()
	{
		if( $this->input->post("id_user") )
		{
			if( $this->input->post("password") != "")
			{
				$data['password'] = md5($this->input->post("password"));
			}
			$data['id_employee'] 	= $this->input->post("id_employee");
			$data['id_profile']		= $this->input->post("profile");
			$data['user_name']		= $this->input->post("user_name");
			$data['active']			= $this->input->post("active");
			
			$rs = $this->user_model->update($this->input->post("id_user"), $data);
			if($rs)
			{
				echo "success";
			}
			else
			{
				echo "fail";
			}
		}
		else
		{
			echo "missing_data";
		}
	}
	
	public function delete($id)
	{
		$rs = $this->user_model->delete($id);
		if($rs)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
	
	public function valid_data()
	{
		if( $this->input->post("id_user") )
		{
			$res = "ok";
			$id = $this->input->post("id_user");
			$ru = $this->user_model->check_user($this->input->post("user_name"), $id);
			$re = $this->user_model->check_employee($this->input->post("id_employee"), $id);
			if( $ru )
			{
				$res = "duplicate_user";
			}
			if( $re )
			{
				$res = "duplicate_employee";
			}
			echo $res;
		}
		else
		{
			echo "fail";
		}		
	}
	
	public function clear_filter()
	{
		$this->session->unset_userdata("user_search");
		$this->index();	
	}
	
}// End class


?>