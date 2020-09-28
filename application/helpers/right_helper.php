<?php
function isOpen($id_group, $id_menu)
{
	$group[0] = array("1"=>1, "2"=>1, "3"=>1, "4"=>1, "5"=>1, "6"=>1, "7"=>1, "8"=>1, "9"=>1, "11"=>1);
	$group[1] = array("12"=>1, "13"=>1);
	if( isset($group[$id_group][$id_menu]) )
	{
		return "open";
	}else{
		return "";
	}
}

function active_menu($value, $id_menu)
{
	if($value == $id_menu)
	{
		return "active";
	}else{
		return "";
	}
}
function valid_menu($id_menu, $url)
{	
	$c =& get_instance();
	$id_profile = $c->session->userdata("id_profile");
	if($id_profile == 0)
	{
		$url = base_url().$url;
	}else{
		$c->db->select("view");
		$ro = $c->db->get_where("tbl_access", array("id_profile"=>$id_profile, "id_menu"=>$id_menu), 1);
		if($ro->num_rows() ==1)
		{
			$rs = $ro->row();
			if($rs->view == 1)
			{ 
				$url = base_url().$url;
			}else{
				$url = "#";
			}
		}else{
			$url = "#";
		}
	}
	return $url;
}

function valid_access($id_menu)
{
	$c =& get_instance();
	$id_profile = $c->session->userdata("id_profile");
	$result = null;
	if($id_profile ==0)
	{
		$result['view'] 	= 1;
		$result['add'] 	= 1;
		$result['edit'] 	= 1;
		$result['delete'] 	= 1;
		$result['print'] 	= 1;
	}else{
		$limit = 1; // Limit 1 row
		$ro = $c->db->get_where("tbl_access", array("id_profile"=>$id_profile, "id_menu"=>$id_menu), $limit);
		if($ro->num_rows() ==1)
		{
			$rs = $ro->row();
			$result['view'] 	= $rs->view;
			$result['add'] 	= $rs->add;
			$result['edit'] 	= $rs->edit;
			$result['delete'] 	= $rs->delete;
			$result['print'] 	= $rs->print;
		}
	}
	return $result;
}



function action_deny()
{
	$deny_acton = array( "page_title"=>"Action deny");
	$c =& get_instance();
	return $c->load->view("deny_action", $deny_acton);
}

function access_deny()
{
	$page = array("page_title"=>"Access deny");
	$c =& get_instance();
	return $c->load->view("deny_page", $page );	
}

?>