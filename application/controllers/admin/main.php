<?php
class Main extends CI_Controller
{
	public $id_menu = 5;
	public $home;
	public $layout = "include/template";
	public $title = "เพิ่ม/แก้ไข การตรวจนับ";
	public $error = "";

public function __construct()
{
	parent:: __construct();
	$this->load->model("admin/main_model");
	$this->home = base_url()."admin/main";
	$this->csv_path = "images/csv";
}

public function index()
{
	$filter 			= "";
	$search_text	= "";
	$from 			= "";
	$to		 		= "";
	if($this->input->post("search_text") || ($this->input->post("from_date") && $this->input->post("to_date")))
	{
		$this->session->set_userdata("main_filter", $this->input->post("filter"));
		$this->session->set_userdata("main_search_text", $this->input->post("search_text"));
		$this->session->set_userdata("from_date", $this->input->post("from_date"));
		$this->session->set_userdata("to_date", $this->input->post("to_date"));
		$filter 			= $this->input->post("filter");
		$search_text 	= $this->input->post("search_text");
		$from 			= $this->input->post("from_date") =="" ? "" : fromDate($this->input->post("from_date"));
		$to				= $this->input->post("to_date") == "" ? "" : toDate($this->input->post("to_date"));
	}
	$row 						= $this->main_model->count_row($search_text, $filter, $from, $to);
	$config 					= pagination_config();
	$config['base_url'] 		= $this->home."/index/";
	$config['per_page'] 	= $this->input->cookie('row') ? $this->input->cookie('row') : getConfig("PER_PAGE");
	$config['total_rows'] 	=  $row != false ? $row : 0;
	if($this->session->userdata("search_text") !="" || ($this->session->userdata("from_date") != "" && $this->session->userdata("to_date") != "") )
	{
		$from = $this->session->userdata("from_date") == "" ? "" : fromDate($this->session->userdata("from_date"));
		$to 	= $this->session->userdata("to_date") == "" ? "" : toDate($this->session->userdata("to_date"));
		$filter = $this->session->userdata("main_filter") != "" ? $this->session->userdata('main_filter') : "reference";
		$txt 	= $this->session->userdata("main_search_text");
		$rs 	= $this->main_model->get_search_data($txt, $filter, $from, $to, $config['per_page'], $this->uri->segment($config['uri_segment']));
		$from	= $this->session->userdata("from_date");
		$to 	= $this->session->userdata("to_date");
	}
	else
	{
		$rs	= $this->main_model->get_data("", $config['per_page'], $this->uri->segment($config['uri_segment']));
		$txt	= "";
		$filter = "";
		$from = "";
		$to	= "";
	}
	$data['data'] 			= $rs;
	$data['id_menu'] 		= $this->id_menu;
	$data['view'] 			= "admin/main_view";
	$data['page_title'] 		= $this->title;
	$data['row']				= $config['per_page'];
	$data['search_text']	= $txt;
	$data['filter']				= $filter;
	$data['from_date']		= $from;
	$data['to_date']			= $to;
	$data['multi_check']	= getConfig("MULTI_CHECKING");
	$this->pagination->initialize($config);
	$this->load->view($this->layout, $data);
}

public function import_items()
{
	$id_check = $this->input->post("id_check");
	$location		= $this->main_model->location_code($id_check);
	$csv	= 'user_file';
	$config = array(   // initial config for upload class
			"allowed_types" => "xls|xlsx",
			"upload_path" => $this->csv_path,
			"file_name"	=> "import_stock_zone_items",
			"max_size" => 5120,
			"overwrite" => TRUE
			);
		$this->load->library("upload", $config);

		if(!$this->upload->do_upload($csv)){
			echo $this->upload->display_errors();
		}
		else
		{
			$import	 	= 0;
			$success	= 0;
			$fail			= 0;
			$update 		= 0;
			$info = $this->upload->data();

			
			$this->load->library("excel");
				/// read file
				$excel = PHPExcel_IOFactory::load($info['full_path']);
				//get only the Cell Collection
				$cell_collection = $excel->getActiveSheet()->getCellCollection();
				//extract to a PHP readable array format
				foreach ($cell_collection as $cell) {
					$column 	= $excel->getActiveSheet()->getCell($cell)->getColumn();
					$row 		= $excel->getActiveSheet()->getCell($cell)->getRow();
					$data_value = $excel->getActiveSheet()->getCell($cell)->getValue();
					//header will/should be in row 1 only. of course this can be modified to suit your need.
					if ($row == 1) {
						$header[$row][$column] = $data_value;
					} else {
						$arr_data[$row][$column] = $data_value;
					}
				}

			foreach($arr_data as $rs)
			{
				$barcode = $rs['A'];
				$id		= $this->main_model->isExists($barcode, $id_check);
				if( !$id )
				{
					$import++;
					$item = array(
								"id_check" 		=> $id_check,
								"location_code"	=> $location,
								"barcode" 		=> $rs['A'],
								"item_code" 		=> $rs['B'],
								"qty" 				=> $rs['C']
								);

					$cs = $this->main_model->import_item($item);
					if($cs){ $success++; }else{ $fail++; }
				}
				else
				{
					$item = array("qty" => $rs['C']);
					$cs = $this->main_model->update_import_item($id, $item);
					if($cs){ $update++; }else{ $fail++; }
				}
			}
			setInfo("นำเข้า ".$import." รายการ <br/> สำเร็จ ".$success." รายการ <br/> ปรับปรุง ".$update." รายการ <br/>ไม่สำเร็จ ".$fail." รายการ");
		}
		$this->main_model->set_import($id_check, 1);
		redirect($this->home);
}





public function import_checked()
{
	$id_check = $this->input->post("idCheck");

	$csv	= 'checkedFile';

	$config = array(   // initial config for upload class
			"allowed_types" => "csv",
			"upload_path" => $this->csv_path,
			"file_name" => "import_checked_data",
			"max_size" => 5120,
			"overwrite" => TRUE
			);

		$this->load->library("upload", $config);

		if(!$this->upload->do_upload($csv)){

			echo $this->upload->display_errors();
		}
		else
		{
			$import	 	= 0;
			$success	= 0;
			$fail			= 0;
			$update 		= 0;

			$info = $this->upload->data();

			$arr = array(
				'id_check' => $id_check,
				'file_name' => $info['client_name'],
				'emp_name' => getEmployeeNameByIdUser($this->session->userdata("id_user"))
			);

			$id_file = $this->main_model->add_file($arr);

			if(!empty($id_file))
			{
				$this->load->model('admin/product_model');
				$this->load->library("csvreader");
				$data = $this->csvreader->parse_file($info['full_path']);
				$import	 	= 0;
				$success	= 0;
				$fail			= 0;

				foreach($data as $rs)
				{
					$import++;
					$barcode = $rs['barcode'];
					$qty = empty($rs['qty']) ? 0 : $rs['qty'];

					if( $barcode == "")
					{
						$skip++;
					}
					else if( ! $this->product_model->isExists($barcode) )
					{
						$item = array(
							'id_check' => $id_check,
							"barcode" => $barcode,
							"qty" => $qty,
							"id_file" => $id_file
						);

						$this->main_model->add_non_item($item);
						$fail++;
					}
					else
					{
						$item = array(
							'id_check' => $id_check,
							'barcode' => $barcode,
							'qty' => $qty,
							'id_employee' => $this->session->userdata("id_employee"),
							'is_import' => 1,
							'id_file' => $id_file
						);

						$this->main_model->add_checked_detail($item);

						$success++;
					}
				}
			}
			setInfo("นำเข้า ".$import." รายการ <br/> สำเร็จ ".$success." รายการ <br/> ปรับปรุง ".$update." รายการ <br/>ไม่สำเร็จ ".$fail." รายการ");
		}

		$this->main_model->set_import_detail($id_check, 1);

		redirect($this->home);
}



public function delete_imported_items()
{
	if( $this->input->post("id_check"))
	{
		$rs = $this->main_model->delete_imported_items($this->input->post("id_check"));
		if( $rs)
		{
			$this->main_model->set_import($this->input->post("id_check"), 0);
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
}




public function get_data()
{
	$data = "fail";
	if( $this->input->post("id_check") )
	{
		$rs = $this->main_model->get_data($this->input->post("id_check"));
		if($rs)
		{
			$remark = is_null($rs->remark) ? "" : $rs->remark;
			$data = $rs->location_code." | ".$remark." | ".$rs->subject;
		}
	}
	echo $data;
}




public function add_new_check()
{
	$this->load->model('admin/shop_model');
	$res = "fail";
	if($this->input->post("location") && $this->input->post("subject"))
	{
		$shop = $this->shop_model->get($this->input->post('location'));
		$data = array(
					"reference" 		=> new_reference(),
					"subject"			=> $this->input->post("subject"),
					"location_code" => $this->input->post("location"),
					"location"			=> $this->input->post("location")." : ".get_location_name_by_code($this->input->post("location")),
					"date_open"		=> date("Y-m-d H:i:s"),
					"employee"		=> getEmployeeNameByIdUser($this->session->userdata("id_user")),
					"remark"			=> $this->input->post("remark") == "" ? NULL : $this->input->post("remark"),
					"allow_input_qty" => $shop->allow_input_qty == 'Y' ? 1 : 0
					);

		$rs = $this->main_model->add_new_check($data);
		if($rs)
		{
			$res = "success";
		}
	}
	echo $res;
}



public function update_check()
{
	$re = "fail";
	if($this->input->post("id_check"))
	{
		$id_check = $this->input->post("id_check");
		$data 		= array(
								"subject"			=> $this->input->post("subject"),
								"location_code"	=> $this->input->post("location_code"),
								"location"			=> get_location_name_by_code($this->input->post("location_code")),
								"employee"		=> getEmployeeNameByIdUser($this->session->userdata("id_user")),
								"remark"			=> $this->input->post("remark") == "" ? NULL : $this->input->post("remark")
							);
		$rs = $this->main_model->update_check($id_check, $data);
		if($rs){ $re = "success"; }
	}
	echo $re;
}




public function pause_check()
{
	$re = "fail";
	if($this->input->post("id_check"))
	{
		$data = array("pause"=>1);
		$rs = $this->main_model->pause($this->input->post("id_check"), $data);
		if($rs)
		{
			$re = "success";
		}
	}
	echo $re;
}




public function continue_check()
{
	$re = "fail";
	if($this->input->post("id_check"))
	{
		$mc = getConfig("MULTI_CHECKING");
		if($mc){ $rd = false; }else{ $rd = $this->main_model->validate_multi_check(); }
		if(!$rd)
		{
			$data = array("pause"=>0);
			$rs = $this->main_model->continue_check($this->input->post("id_check"), $data);
			if($rs)
			{
				$re = "success";
			}
		}
		else
		{
			$re = "muliticheck";
		}
	}
	echo $re;
}



public function close_check($id)
{
	$rs = $this->main_model->close_check($id);
	if($rs)
	{
		echo "success";
	}
	else
	{
		echo "fail";
	}
}




public function open_check($id)
{
	$rs = $this->main_model->open_check($id);
	if($rs)
	{
		echo "success";
	}
	else
	{
		echo "fail";
	}
}




public function delete_checked($id)
{
	$rs = $this->main_model->deleteChecked($id);
	if($rs)
	{
		echo "success";
	}
	else
	{
		echo "fail";
	}
}




public function isPause($id)
{
	echo $this->main_model->isPause($id);  /// 1 = pause 0 = not pause
}


public function clear_filter()
{
	$this->session->unset_userdata("main_search_text");
	$this->session->unset_userdata("main_filter");
	$this->session->unset_userdata("from_date");
	$this->session->unset_userdata("to_date");
	redirect($this->home);
}


public function get_import_file_list()
{
	$sc  = TRUE;
	$ds = array();
	$error = "";
	$id_check = $this->input->get('id_check');
	if(!empty($id_check))
	{
		$qr = $this->db->where('id_check', $id_check)->get('tbl_import_detail_file');
		if($qr->num_rows() > 0)
		{
			foreach($qr->result() as $rs)
			{
				$arr = array(
					'id_file' => $rs->id,
					'file_name' => $rs->file_name,
					'emp_name' => $rs->emp_name,
					'date_upd' => date('d-m-Y H:i:s', strtotime($rs->date_add))
				);

				array_push($ds, $arr);
			}
		}
		else {
			$arr = array(
				'nodata' => 'nodata'
			);

			array_push($ds, $arr);
		}
	}
	else
	{
		$sc = FALSE;
		$error = "Invalid Check Id";
	}

	echo $sc === TRUE ? json_encode($ds) : $error;
}


public function delete_selected_file()
{
	$sc = TRUE;
	$id_check = $this->input->post('id_check');
	$files = $this->input->post('select_files');

	if(!empty($id_check))
	{
		if(!empty($files))
		{
			$this->db->trans_begin();
			foreach($files as $id)
			{
				if($sc === FALSE)
				{
					break;
				}

				//--- delete check detail from file id
				if($this->main_model->delete_import_checked_detail($id_check, $id))
				{
					if(! $this->main_model->delete_imported_checked_file($id))
					{
						$sc = FALSE;
						$this->error = "Delete file failed @file_id : {$id}";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Delete Imported details failed @file_id : {$id}";
				}
			}

			if($sc === TRUE)
			{
				$this->db->trans_commit();
			}
			else
			{
				$this->db->trans_rollback();
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No files selected";
		}
	}
	else
	{
		$sc = FALSE;
		$this->error = "Check Id not found";
	}

	echo $sc === TRUE ? 'success' : $this->error;
}

}/// endclass


?>
