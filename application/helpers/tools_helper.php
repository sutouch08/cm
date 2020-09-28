<?php
function get_rows()
{
  $CI =& get_instance();
	$rows = $CI->input->cookie('row') ? $CI->input->cookie('row') : getConfig('PER_PAGE');
  return $rows;
}


function get_filter($postName, $cookieName, $defaultValue = "")
{
  $CI =& get_instance();
  $sc = '';

  if($CI->input->post($postName) !== NULL)
  {
    $sc = $CI->input->post($postName);
    $CI->input->set_cookie(array('name' => $cookieName, 'value' => $sc, 'expire' => 3600 , 'path' => '/'));
  }
  else if($CI->input->cookie($cookieName))
  {
    $sc = $CI->input->cookie($cookieName);
  }
  else
  {
    $sc = $defaultValue;
  }

	return $sc;
}




function clear_filter($cookies)
{
  if(is_array($cookies))
  {
    foreach($cookies as $cookie)
    {
      delete_cookie($cookie);
    }
  }
  else
  {
    delete_cookie($cookies);
  }
}


function setError($message)
{
	$c =& get_instance();
	$c->session->set_flashdata("error", $message);
}

function setMessage($message)
{
	$c =& get_instance();
	$c->session->set_flashdata("success", $message);
}

function setInfo($message)
{
	$c =& get_instance();
	$c->session->set_flashdata("info", $message);
}

function isActived($value)
{
	$icon = "<i class='fa fa-remove' style='color:red'></i>";
	if($value == "1")
	{
		$icon = "<i class='fa fa-check' style='color:green'></i>";
	}
	return $icon;
}

function is_active_btn($value, $active)
{
	if($value == 1 && $active == 1)
	{
		return "btn-success";
	}else if( $value == 1 && $active == 0 ){
		return "";
	}else if( $value == 0 && $active == 0 ){
		return "btn-danger";
	}else if( $value == 0 && $active == 1){
		return "";
	}
}

function isChecked($val1, $val2)
{
	$value = "";
	if( $val1 == $val2 )
	{
		$value = "checked='checked'";
	}
	return $value;
}

function isSelected($val1, $val2)
{
	$value = "";
	if($val1 == $val2)
	{
		$value = "selected='selected'";
	}
	return $value;
}

function selectItemGroup($id=0)
{
	$c 		=& get_instance();
	$options 	= "";
	$rs	= $c->db->get("tbl_item_group");
	if($rs->num_rows() > 0 )
	{
		foreach($rs->result() as $rd)
		{
			$options .= "<option value='".$rd->group_type."' ".isSelected($id, $rd->group_type).">".$rd->group_name."</option>";
		}
	}
	return $options;
}


function employee_name($id_employee)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("first_name, last_name")->where("id_employee", $id_employee)->get("tbl_employee");
	if($rs->num_rows() == 1 )
	{
		$name = $rs->row()->first_name." ".$rs->row()->last_name;
	}
	return $name;
}

function employee_first_name($id_employee)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("first_name")->where("id_employee", $id_employee)->get("tbl_employee");
	if($rs->num_rows() == 1 )
	{
		$name = $rs->row()->first_name;
	}
	return $name;
}

function getEmployeeNameByIdUser($id_user)
{
	$c =& get_instance();
	$name = "";
	$rs = $c->db->select("first_name")->join("tbl_employee","tbl_employee.id_employee = tbl_user.id_employee")->get_where("tbl_user", array("id_user"=>$id_user),1);
	if($rs->num_rows() == 1)
	{
		$name = $rs->row()->first_name;
	}
	return $name;
}


function new_reference()
{
	$c 			=& get_instance();
	$prefix 		= getConfig("COM_CODE");
	$reference 	= $prefix.date("ymdhis");
	return $reference;
}
function get_location_name_by_code($code)
{
	$c =& get_instance();
	$rs = $c->db->select("shop_name")->where("shop_code", $code)->get("tbl_shop");
	if($rs->num_rows() == 1 )
	{
		return $rs->row()->shop_name;
	}
	else
	{
		return "";
	}
}

function item_group_name($id)
{
	$c =& get_instance();
	$rs = $c->db->select("group_name")->where("group_type", $id)->get("tbl_item_group");
	if( $rs->num_rows() == 1 )
	{
		return $rs->row()->group_name;
	}
	else
	{
		return "";
	}
}

?>
