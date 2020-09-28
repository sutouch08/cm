<?php

function pagination_config()
{		
		$config['full_tag_open'] 		= "<nav><ul class='pagination'>";
		$config['full_tag_close'] 		= "</ul></nav>";
		$config['first_link'] 				= 'First';
		$config['first_tag_open'] 		= "<li>";
		$config['first_tag_close'] 		= "</li>";
		$config['next_link'] 				= 'Next';
		$config['next_tag_open'] 		= "<li>";
		$config['next_tag_close'] 	= "</li>";
		$config['prev_link'] 			= 'prev';
		$config['prev_tag_open'] 	= "<li>";
		$config['prev_tag_close'] 	= "</li>";
		$config['last_link'] 				= 'Last';
		$config['last_tag_open'] 		= "<li>";
		$config['last_tag_close'] 		= "</li>";
		$config['cur_tag_open'] 		= "<li class='active'><a href='#'>";
		$config['cur_tag_close'] 		= "</a></li>";
		$config['num_tag_open'] 		= '<li>';
		$config['num_tag_close'] 		= "</li>";
		$config['uri_segment'] 		= 4;
		return $config;
}

function getConfig($name)
{
	$c =& get_instance();
	$rs = $c->db->select("value")->get_where("tbl_config", array("config_name"=>$name),1);	
	if($rs->num_rows() == 1 )
	{
		return $rs->row()->value;
	}else{
		return false;
	}
}

function rows_box()
{
	$c 	=& get_instance();
	$rows = $c->input->cookie('row') ? $c->input->cookie('row') : getConfig("PER_PAGE");
	$rs = "แสดงจำนวนแถวต่อหน้า ";
	$rs .= "<input type='text' class='input-sm' id='set_rows' value='".$rows."' style='width:50px; text-align:center; margin-left:15px; margin-right:15px;' />";
	$rs .= "<button class='btn btn-success btn-mini' onclick='set_rows()'>บันทึก</button>";
	$rs .= "<script>";
	$rs .= "$('#set_rows').keyup(function(e){	if(e.keyCode == 13 ){ set_rows(); } }); ";
	$rs .= "function set_rows(){ 
				load_in(); 
				var rows = $('#set_rows').val();	
				if(rows == ''){
					load_out();
					swal('จำนวนแถวต้องเป็นตัวเลขเท่านั้น');
					return false;
				}else{
					$.ajax({
						url: '".base_url()."admin/tool/set_rows', type: 'POST', 
						cache: false, data:{ 'rows' : rows },
						success: function(rs)
						{
							load_out();
							var rs = $.trim(rs);
							if(rs == 'success')
							{
								window.location.reload();
							}else{
								swal('ไม่สามารถเปลี่ยนจำนวนแถวต่อหน้าได้ กรุณาลองใหม่อีกครั้งภายหลัง');
							}
						}
					});
				}
			}";
	$rs .= "</script>";
		return $rs;
}

function set_session($name, $value)
{
	$c =& get_instance();
	$c->session->set_userdata($name, $value);
}

function select_profile($id = "")
{
	$res = "<option value='0'> เลือกโปรไฟล์ </option>";
	$c =& get_instance();
	$rs = $c->db->get("tbl_profile");
	foreach($rs->result() as $rd)
	{
		if($rd->id_profile == $id){ $se = " selected"; }else{ $se = ""; }
		$res .= "<option value='".$rd->id_profile."'".$se.">".$rd->profile_name."</option>";
	}
	return $res;
}

function id_employee()
{
	$c =& get_instance();
	if($c->session->userdata("id_employee") != null )
	{
		return $c->session->userdata("id_employee");
	}
	else
	{
		redirect(base_url()."authentication");
	}
}

function select_location($code = "")
{
	$rs = "";
	$c =& get_instance();
	$ra = $c->db->order_by("shop_code", "ASC")->get("tbl_shop");
	if($ra->num_rows() > 0 )
	{
		foreach($ra->result() as $rd)
		{
			$rs .= "<option value='".$rd->shop_code."' ".isSelected($code, $rd->shop_code).">".$rd->shop_name."</option>";
		}
	}
	return $rs;
}

function discount($percent, $amount)
{
	$discount = "0.00";
	if($percent != 0.00)
	{
		$discount = number_format($percent,2)." %";
	}
	else if($amount != 0.00)
	{
		$discount = number_format($amount,2)." ฿";
	}
	return $discount;		
}

function get_item_code($barcode)
{
	$code = "";
	$c =& get_instance();
	$rs = $c->db->select("item_code")->where("barcode", $barcode)->get("tbl_items");
	if($rs->num_rows() == 1)
	{
		$code = $rs->row()->item_code;
	}
	return $code;
}

function get_item_name($barcode)
{
	$name = "";
	$c =& get_instance();
	$rs = $c->db->select("item_name")->where("barcode", $barcode)->get("tbl_items");
	if($rs->num_rows() == 1 )
	{
		$name = $rs->row()->item_name;
	}
	return $name;
}

function get_item_cost($barcode)
{
	$c =& get_instance();
	$rs = $c->db->where("barcode", $barcode)->get("tbl_items");
	if($rs->num_rows() == 1)
	{
		return $rs->row()->cost;
	}
	else
	{
		return 0;
	}		
}

function get_item_price($barcode)
{
	$c =& get_instance();
	$rs = $c->db->where("barcode", $barcode)->get("tbl_items");
	if( $rs->num_rows() == 1 )
	{
		return $rs->row()->price;
	}
	else
	{
		return 0;
	}
}

function group_type($type)
{
	$group = "";
	$c =& get_instance();
	$rs = $c->db->select("group_name")->where("group_type", $type)->get("tbl_item_group");
	if( $rs->num_rows() == 1 )
	{
		$group = $rs->row()->group_name;
	}
	return $group;
}

?>