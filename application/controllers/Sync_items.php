<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_items extends CI_Controller
{
  public $title = 'Sync Items';
	public $ix;
  public $limit = 100;
  public $date;

  public function __construct()
  {
    parent::__construct();

    //$this->ms = $this->load->database('ms', TRUE);
    $this->ix = $this->load->database('ix', TRUE); //--- IX database
    $this->date = date('Y-d-m H:i:s');
    $this->load->model('admin/product_model');
  }

  public function index()
  {
    $data['id_menu'] 		= 0;
    $this->load->view('sync_products_view', $data);
  }


  public function count_update_items()
  {
    $date_add = $this->input->get('last_sync');
    $date_upd = $this->input->get('last_sync');
    $count = $this->product_model->count_ix_update_list($date_add, $date_upd);
    echo $count;
  }


  public function get_update_items($offset)
  {
    $date_add = $this->input->get('last_sync');
    $date_upd = $this->input->get('last_sync');
    $list = $this->product_model->get_ix_list($date_add, $date_upd, $this->limit, $offset);
    $count = 0;
    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $arr = array(
          'barcode' => $rs->barcode,
          'item_code' => $rs->code,
          'item_name' => $rs->name,
          'style' => $rs->style_code,
          'cost' => $rs->cost,
          'price' => $rs->price,
          'last_sync' => date('Y-m-d H:i:s')
        );

        $id = $this->product_model->is_exists($rs->barcode);
        if($id === FALSE)
        {
          $this->product_model->add_item($arr);
        }
        else
        {
          $this->product_model->update_item($id, $arr);
        }

        $count++;
      }
    }

    echo $count;
  }


  public function get_item_last_date()
  {
    echo $this->product_model->get_items_last_sync();
  }

} //--- end class

 ?>
