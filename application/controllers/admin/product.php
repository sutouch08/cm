<?php
class Product extends CI_Controller
{
	public $id_menu = 1;
	public $home;
	public $layout = "include/template";
	public $title = "เพิ่ม/แก้ไข รายการสินค้า";
	public $csv_path;

	public function __construct()
	{
		parent:: __construct();
		$this->home = base_url()."admin/product";
		$this->load->model("admin/product_model");
		$this->csv_path = "images/csv";
	}

	public function index()
	{
		$filter	= get_filter('search_text', 'search_text', '');

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 3; //-- url segment

		$rows 	= $this->product_model->count_row($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);

		$rs 	= $this->product_model->get_search_data($filter, $perpage, $this->uri->segment($segment));


		$data['data'] 			= $rs;
		$data['id_menu'] 		= $this->id_menu;
		$data['view'] 			= "admin/product_view";
		$data['page_title'] 		= $this->title;
		$data['row']				= $perpage;
		$data['search_text']	= $filter;
		$this->pagination->initialize($init);
		$this->load->view($this->layout, $data);
	}



	public function sync_item()
	{
		$this->load->view('admin/sync_item');
	}



	public function clear_filter()
	{
		clear_filter('search_text');
		redirect($this->home);
	}

	public function import_items()
	{
		$csv	= 'user_file';
		$config = array(   // initial config for upload class
			"allowed_types" => "xls|xlsx",
			"upload_path" => $this->csv_path,
			"file_name"	=> "import_items",
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
				$skip			= 0;
				$update		= 0;
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
					$import++;
					$barcode = $rs['A'];
					if( $barcode == "")
					{
						$skip++;
					}
					else if( !$this->product_model->isExists($barcode) )
					{
						$item = array(
									"barcode" 	=> $rs['A'],
									"item_code" 	=> $rs['B'],
									"item_name" 	=> $rs['C'],
									"style" 		=> $rs['D'],
									"cost" 		=> $rs['E'],
									"price" 		=> $rs['F'],
									"type"			=> $rs['G']
									);

						$cs = $this->product_model->add_item($item);
						if($cs){ $success++; }else{ $fail++; }
					}
					else
					{
						$item = array(
									"item_code" 	=> $rs['B'],
									"item_name" 	=> $rs['C'],
									"style" 		=> $rs['D'],
									"cost" 		=> $rs['E'],
									"price" 		=> $rs['F'],
									"type"			=> $rs['G']
									);
						$cs = $this->product_model->update_import_item($barcode, $item);
						if($cs){ $update++; }else{ $fail++; }
					}
				}
				setInfo("นำเข้า ".$import." รายการ <br/> เพิ่มใหม่ ".$success." รายการ <br/> อัพเดต ".$update." รายการ <br/> ไม่สำเร็จ ".$fail." รายการ <br/>ข้าม(ไม่มีบาร์โค้ด) ".$skip." รายการ");
			}
			redirect($this->home);
	}

	/*public function import_items()
	{
		$csv	= 'user_file';
		$config = array(   // initial config for upload class
			"allowed_types" => "csv",
			"upload_path" => $this->csv_path,
			"file_name"	=> "import_items",
			"max_size" => 5120,
			"overwrite" => TRUE
			);
			$this->load->library("upload", $config);
			if(!$this->upload->do_upload($csv)){
				echo $this->upload->display_errors();
			}
			else
			{
				$info = $this->upload->data();
				$this->load->library("csvreader");
				$data = $this->csvreader->parse_file($info['full_path']);
				$import	 	= 0;
				$success	= 0;
				$fail			= 0;
				$skip			= 0;
				$update		= 0;
				foreach($data as $rs)
				{
					$import++;
					$barcode = $rs['barcode'];
					if( $barcode == "")
					{
						$skip++;
					}
					else if( !$this->product_model->isExists($barcode) )
					{
						$item = array(
									"barcode" 	=> $rs['barcode'],
									"item_code" 	=> $rs['item_code'],
									"item_name" 	=> $rs['item_name'],
									"style" 		=> $rs['style'],
									"cost" 		=> $rs['cost'],
									"price" 		=> $rs['price'],
									"type"			=> $rs['items_group']
									);

						$cs = $this->product_model->add_item($item);
						if($cs){ $success++; }else{ $fail++; }
					}
					else
					{
						$item = array(
									"item_code" 	=> $rs['item_code'],
									"item_name" 	=> $rs['item_name'],
									"style" 		=> $rs['style'],
									"cost" 		=> $rs['cost'],
									"price" 		=> $rs['price'],
									"type"			=> $rs['items_group']
									);
						$cs = $this->product_model->update_import_item($barcode, $item);
						if($cs){ $update++; }else{ $fail++; }
					}
				}
				setInfo("นำเข้า ".$import." รายการ <br/> เพิ่มใหม่ ".$success." รายการ <br/> อัพเดต ".$update." รายการ <br/> ไม่สำเร็จ ".$fail." รายการ <br/>ข้าม(ไม่มีบาร์โค้ด) ".$skip." รายการ");
			}
			redirect($this->home);
	}*/

	public function delete_item($id)
	{
		$rs = $this->product_model->delete_item($id);
		if($rs)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}

	public function update_item()
	{
		if($this->input->post("barcode"))
		{
			$data = array(
							"barcode" 	=> $this->input->post("barcode"),
							"item_code" 	=> $this->input->post("item_code"),
							"item_name" 	=> $this->input->post("item_name"),
							"style" 		=> $this->input->post("style"),
							"type"			=> $this->input->post("type"),
							"cost" 		=> $this->input->post("cost"),
							"price" 		=> $this->input->post("price"),
							"active" 		=> $this->input->post("active")
							);
				$rs = $this->product_model->update_item($this->input->post("id"), $data);
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

	public function add_item()
	{
		if( $this->input->post("barcode") )
		{
			$rd = $this->product_model->check_barcode($this->input->post("barcode"));
			if(!$rd)
			{
				$data = array(
							"barcode" 	=> trim($this->input->post("barcode")),
							"item_code" 	=> trim($this->input->post("item_code")),
							"item_name" 	=> trim($this->input->post("item_name")),
							"style" 		=> trim($this->input->post("style")),
							"cost" 		=> $this->input->post("cost"),
							"price" 		=> $this->input->post("price"),
							"active" 		=> $this->input->post("active"),
							"type"			=> $this->input->post("type")
							);
				$rs = $this->product_model->add_item($data);
				if($rs)
				{
					$data['id'] = $rs;
					$data['date_upd'] = date("Y-m-d H:i:s");
					$data['active'] = isActived($this->input->post("active"));
					echo json_encode($data);
				}
				else
				{
					echo "fail";
				}
			}
			else
			{
				echo "duplicate_barcode";
			}
		}
		else
		{
			echo "fail";
		}
	}

	public function get_item($id = "")
	{
		$rs = $this->product_model->get_item($id);
		if($rs)
		{
			$data = array(
							"id_item" 		=> $rs->id_item,
							"barcode" 	=> $rs->barcode,
							"item_code" 	=> $rs->item_code,
							"item_name" 	=> $rs->item_name,
							"style" 		=> $rs->style,
							"item_group"	=> selectItemGroup($rs->type),
							"cost" 		=> $rs->cost,
							"price" 		=> $rs->price,
							"active" 		=> $rs->active,
							"enable"		=> $rs->active == 1 ? "btn-success" : "",
							"disable"		=> $rs->active == 0 ? "btn-danger" : ""
							);
			echo json_encode($data);
		}
		else
		{
			echo "fail";
		}
	}


	public function valid_barcode($barcode, $id = "")
	{
		if($id != "")
		{
			$rs = $this->product_model->check_barcode(urldecode($barcode), $id);
			if(!$rs)  /// ถ้าไม่ซ้ำ
			{
				echo "ok";
			}
			else
			{
				echo "fail";
			}
		}
	}

}// End class


?>
