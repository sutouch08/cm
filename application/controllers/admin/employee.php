<?php
class Employee extends CI_Controller
{
	public $id_menu = 2;
	public $home;
	public $layout = "include/template";
	public $title = "เพิ่ม/แก้ไข พนักงาน";
		
	public function __construct()
	{
		parent:: __construct();
		$this->home = base_url()."admin/employee";
		$this->load->model("admin/employee_model");
	}
	
	public function index()
	{	
		$emp_search	= "";
		if($this->input->post("emp_search") != "")
		{
			$this->session->set_userdata("emp_search", $this->input->post("emp_search"));
			$emp_search 	= $this->input->post("emp_search");
		}
		$row 						= $this->employee_model->count_row($emp_search);
		$config 					= pagination_config();
		$config['base_url'] 		= $this->home."/index/";
		$config['per_page'] 	= $this->input->cookie('row') ? $this->input->cookie('row') : getConfig("PER_PAGE");
		$config['total_rows'] 	=  $row != false ? $row : 0;
		if($this->session->userdata("emp_search"))
		{
			$rs 	= $this->employee_model->get_search_data($this->session->userdata("emp_search"), $config['per_page'], $this->uri->segment($config['uri_segment']));
			$txt 	= $this->session->userdata("emp_search");
		}
		else
		{
			$rs	= $this->employee_model->get_data("", $config['per_page'], $this->uri->segment($config['uri_segment']));
			$txt	= "";
		}
		$data['data'] 			= $rs;
		$data['id_menu'] 		= $this->id_menu;
		$data['view'] 			= "admin/employee_view";
		$data['page_title'] 		= $this->title;
		$data['row']				= $config['per_page'];
		$data['emp_search']	= $txt;
		$this->pagination->initialize($config);	
		$this->load->view($this->layout, $data);
	}
	
	public function get_employee($id)
	{
		$rs = $this->employee_model->get_employee($id);
		if($rs)
		{
			$data = array(
						"id" => $rs->id_employee,
						"code" => $rs->code,
						"first_name" => $rs->first_name,
						"last_name" => $rs->last_name,
						"phone" => $rs->phone,
						"address" => $rs->address,
						"province" => $rs->province,
						"post_code" => $rs->post_code,
						"email" => $rs->email,
						"start_date" => thaiDate($rs->start_date	),
						"birthday" => thaiDate($rs->birthday),
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
	public function add_employee()
	{
		$err = 0;
		$res = "fail";
		if($this->input->post("code"))
		{
			$start_date 	= $this->input->post("start_date") == "" ? "" : dbDate($this->input->post("start_date"));
			$birthday 	= $this->input->post("birthday") == "" ? "" : dbDate($this->input->post("birthday"));
			$data = array(
							"code" => $this->input->post("code"),
							"first_name" => $this->input->post("first_name"),
							"last_name" => $this->input->post("last_name"),
							"phone" => $this->input->post("phone"),
							"address" => $this->input->post("address"),
							"province" => $this->input->post("province"),
							"post_code" => $this->input->post("post_code"),
							"email" => $this->input->post("email"),
							"start_date" => $start_date,
							"birthday" => $birthday,
							"active" => $this->input->post("active")
							);
			$rd = $this->employee_model->check_code($data['code']);
			$rn = $this->employee_model->check_name($data['first_name'], $data['last_name']);
			if( $rd )
			{
				$res = "duplicate_code";
				$err = 1;
			}
			if( $rn )
			{
				$res = "duplicate_name";
				$err = 1;
			}
			if( !$err )
			{				
				$rs = $this->employee_model->add_employee($data);
				if($rs)
				{
					$ro = $this->employee_model->get_employee($rs);
					if($ro)
					{
						$result = array(
									"id" => $ro->id_employee,
									"code" => $ro->code,
									"name" => $ro->first_name." ".$ro->last_name,
									"email" => $ro->email,
									"phone" => $ro->phone,
									"start_date" => thaiDate($ro->start_date),
									"active" => isActived($ro->active)
									);
						$res = json_encode($result);
					}
				}
				else
				{
					$res = "fail";
					$err = 1;
				}
			}		
		}
		echo $res;	
	}
	
	public function update()
	{
		if($this->input->post("id_employee"))
		{
			$id = $this->input->post("id_employee");
			$start_date 	= $this->input->post("start_date") == "" ? "" : dbDate($this->input->post("start_date"));
			$birthday 	= $this->input->post("birthday") == "" ? "" : dbDate($this->input->post("birthday"));
			$data = array(
							"code" => $this->input->post("code"),
							"first_name" => $this->input->post("first_name"),
							"last_name" => $this->input->post("last_name"),
							"phone" => $this->input->post("phone"),
							"address" => $this->input->post("address"),
							"province" => $this->input->post("province"),
							"post_code" => $this->input->post("post_code"),
							"email" => $this->input->post("email"),
							"start_date" => $start_date,
							"birthday" => $birthday,
							"active" => $this->input->post("active")
							);
			$rs = $this->employee_model->update($id, $data);
			if( $rs )
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
	
	public function delete_employee($id)
	{
		$rs = $this->employee_model->delete_employee($id);
		if($rs)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
	
	public function valid_data($code, $first_name, $last_name, $id)
	{
		$res = "ok";
		$rs = $this->employee_model->check_code($code, $id);
		$rd = $this->employee_model->check_name($first_name, $last_name, $id);
		if($rd)
		{
			$res = "duplicate_name";
		}
		if($rs)
		{
			$res = "duplicate_code";
		}
		echo $res;
	}
	
	public function clear_filter()
	{
		$this->session->unset_userdata("emp_search");
		$this->index();	
	}
	
}// End class


?>