<?php 
class Report extends CI_Controller
{
public $id_menu 	= 7;
public $layout 		= "include/template";
public $home;
public $title			= "รายงานการตรวจนับ";	

public function __construct()
{
	parent:: __construct();
	$this->home 	= base_url()."admin/report";
	$this->load->model("admin/report_model");
}

public function index()
{
	$filter 			= "";
	$search_text	= "";
	$from 			= "";
	$to		 		= "";
	if($this->input->post("search_text") || ($this->input->post("from_date") && $this->input->post("to_date")))
	{
		$this->session->set_userdata("report_filter", $this->input->post("filter"));
		$this->session->set_userdata("report_search_text", $this->input->post("search_text"));
		$this->session->set_userdata("from_date", $this->input->post("from_date")); 
		$this->session->set_userdata("to_date", $this->input->post("to_date"));
		$filter 			= $this->input->post("filter");
		$search_text 	= $this->input->post("search_text");
		$from 			= $this->input->post("from_date") =="" ? "" : fromDate($this->input->post("from_date"));
		$to				= $this->input->post("to_date") == "" ? "" : toDate($this->input->post("to_date"));
	}
	$row 						= $this->report_model->count_row($search_text, $filter, $from, $to);
	$config 					= pagination_config();
	$config['base_url'] 		= $this->home."/index/";
	$config['per_page'] 	= $this->input->cookie('row') ? $this->input->cookie('row') : getConfig("PER_PAGE");
	$config['total_rows'] 	=  $row != false ? $row : 0;
	if($this->session->userdata("report_search_text") !="" || ($this->session->userdata("from_date") != "" && $this->session->userdata("to_date") != "") )
	{
		$from = $this->session->userdata("from_date") == "" ? "" : fromDate($this->session->userdata("from_date"));
		$to 	= $this->session->userdata("to_date") == "" ? "" : toDate($this->session->userdata("to_date"));
		$filter = $this->session->userdata("report_filter");
		$txt 	= $this->session->userdata("report_search_text");
		$rs 	= $this->report_model->get_search_data($txt, $filter, $from, $to, $config['per_page'], $this->uri->segment($config['uri_segment']));
		$from	= $this->session->userdata("from_date");
		$to 	= $this->session->userdata("to_date");
	}
	else
	{
		$rs	= $this->report_model->get_data("", $config['per_page'], $this->uri->segment($config['uri_segment']));
		$txt	= "";
		$filter = "";
		$from = "";
		$to	= "";
	}
	$data['data'] 			= $rs;
	$data['id_menu'] 		= $this->id_menu;
	$data['view'] 			= "admin/report_view";
	$data['page_title'] 		= $this->title;
	$data['row']				= $config['per_page'];
	$data['search_text']	= $txt;
	$data['filter']				= $filter;
	$data['from_date']		= $from;
	$data['to_date']			= $to;
	$this->pagination->initialize($config);	
	$this->load->view($this->layout, $data);		
}	

public function clear_filter()
{
	$this->session->unset_userdata("report_search_text");
	$this->session->unset_userdata("report_filter");
	$this->session->unset_userdata("from_date");
	$this->session->unset_userdata("to_date");
	redirect($this->home);
}

public function export_summary($id, $token)
{
	$this->load->model("admin/product_model");
	$this->load->library("excel");
	$rh 	= $this->report_model->get_data($id);
	if($rh) //// ถ้ามีข้อมูลการตรวจนับ
	{
		///  หัวข้อใหญ่รายงาน
		$this->excel->setActiveSheetIndex(0);/// สร้าง sheet
		$this->excel->getActiveSheet()->setTitle('สรุปยอดตรวจนับ');  /// ตั้งชื่อ Sheet
		$this->excel->getActiveSheet()->setCellValue("A1", "รายงานสรุปผลการตรวจนับสินค้า"); /// เพิ่มข้อมูลลง เซล A1
		$this->excel->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);/// ตั้ง ขนาดตัวอักษร
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);/// ทำตัวหนา
		$this->excel->getActiveSheet()->mergeCells('A1:K1'); /// รวมเซล
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //// จัด กึ่งกลาง
		
		/// จัดความกว้างของ cell
		
		$this->excel->getActiveSheet()->getColumnDimension("B")->setWidth(17);  /// กำหนดความกว้างคอลัมภ์ B = 17 ตัวอักษร สำหรับแสดงบาร์โค้ด
		$this->excel->getActiveSheet()->getColumnDimension("C")->setWidth(25);  /// สำหรับ รหัสสินค้า
		$this->excel->getActiveSheet()->getColumnDimension("D")->setWidth(40); /// ชื่อสินค้า
		$this->excel->getActiveSheet()->getColumnDimension("E")->setWidth(13); /// ทุน
		$this->excel->getActiveSheet()->getColumnDimension("F")->setWidth(13);  /// ขาย
		$this->excel->getActiveSheet()->getColumnDimension("G")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("H")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("I")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("J")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("K")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("L")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("M")->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension("O")->setWidth(20);
		
		
		
		/// อ้างอิง และ หัวข้อ
		$this->excel->getActiveSheet()->setCellValue("A2", "อ้างอิง");
		$this->excel->getActiveSheet()->setCellValue("B2", $rh->reference);
		$this->excel->getActiveSheet()->setCellValue("C2", "หัวข้อ");
		$this->excel->getActiveSheet()->setCellValue("D2", $rh->subject);
		$this->excel->getActiveSheet()->mergeCells("D2:K2");
		
		/// สถานที่
		$this->excel->getActiveSheet()->setCellValue("A3", "สถานที่");
		$this->excel->getActiveSheet()->setCellValue("B3", $rh->location_code." : ".$rh->location);
		$this->excel->getActiveSheet()->mergeCells("B3:K3");
		
		/// วันที่
		$this->excel->getActiveSheet()->setCellValue("A4", "เริ่มต้น");
		$this->excel->getActiveSheet()->setCellValue("B4", thaiDate($rh->date_open));
		$this->excel->getActiveSheet()->setCellValue("D4", "สิ้นสุด");
		$this->excel->getActiveSheet()->setCellValue("E4", thaiDate($rh->date_close));
		
		/// จัดแนว
		$this->excel->getActiveSheet()->getStyle("B2")->getAlignment()->setHorizontal("center");  //// จัดกึ่งกลาง เลขที่อ้างอิง
		$this->excel->getActiveSheet()->getStyle("C2")->getAlignment()->setHorizontal("right");  //// จัดชิดขาว  หัวข้อ
		$this->excel->getActiveSheet()->getStyle("B4")->getAlignment()->setHorizontal("center");  //// วันที่เริ่มต้น
		$this->excel->getActiveSheet()->getStyle("E4")->getAlignment()->setHorizontal("center");  //// วันที่สิ้นสุด
		
		$this->excel->getActiveSheet()->getStyle("A2")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE); /// ตัวอักษรสีน้ำเงิน
		$this->excel->getActiveSheet()->getStyle("C2")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE); /// ตัวอักษรสีน้ำเงิน
		$this->excel->getActiveSheet()->getStyle("A3")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE); /// ตัวอักษรสีน้ำเงิน
		$this->excel->getActiveSheet()->getStyle("A4")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE); /// ตัวอักษรสีน้ำเงิน
		$this->excel->getActiveSheet()->getStyle("D4")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE); /// ตัวอักษรสีน้ำเงิน
		
		
		/// พนักงานตรวจนับ
		$this->excel->getActiveSheet()->setCellValue("O2", "พนักงานตรวจนับ");
		$this->excel->getActiveSheet()->getStyle("O2")->getAlignment()->setHorizontal("center"); 
		
		$row = 3; $col = 14;  /// Note: In PHPExcel column index is 0-based while row index is 1-based. That means 'A1' ~ (0,1) เริ่มที่  M3
		$re 	= $this->report_model->get_employee($id);
		if( $re )
		{
			foreach($re as $rd)
			{
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rd->first_name);
				$row++;
			}
		}
		
		/// header_row
		$this->excel->getActiveSheet()->setCellValue("A6", "ลำดับ");
		$this->excel->getActiveSheet()->setCellValue("B6", "บาร์โค้ด");
		$this->excel->getActiveSheet()->setCellValue("C6", "รหัสสินค้า");
		$this->excel->getActiveSheet()->setCellValue("D6", "สินค้า");
		$this->excel->getActiveSheet()->setCellValue("E6", "ราคาทุน");
		$this->excel->getActiveSheet()->setCellValue("F6", "ราคาขาย");
		$this->excel->getActiveSheet()->setCellValue("G6", "จำนวนตั้งต้น");
		$this->excel->getActiveSheet()->setCellValue("H6", "มูลค่าตั้งต้น");
		$this->excel->getActiveSheet()->setCellValue("I6", "จำนวนตรวจนับ");
		$this->excel->getActiveSheet()->setCellValue("J6", "มูลค่าตรวจนับ");
		$this->excel->getActiveSheet()->setCellValue("K6", "ยอดต่าง");
		$this->excel->getActiveSheet()->setCellValue("L6", "มูลค่ายอดต่าง(ทุน)");
		$this->excel->getActiveSheet()->setCellValue("M6", "มูลค่ายอดต่าง(ขาย)");
		$this->excel->getActiveSheet()->setCellValue("N6", "กลุ่มสินค้า");
		
		/// จัดกึ่งกลาง
		$this->excel->getActiveSheet()->getStyle("A6:N6")->getAlignment()->setHorizontal("center");	
		
		/// ผลการตรวจนับ
		$re 	= $this->report_model->get_summary_report($id);
		$n = 1;
		$row = 7; 
		foreach($re as $rs)
		{
			$barcode 		= $rs->barcode;
			$ds				= $this->product_model->get_items($barcode);
			$imported_qty	= $this->report_model->get_imported_qty($id, $barcode);
			
			$this->excel->getActiveSheet()->setCellValue("A".$row, $n);
			$this->excel->getActiveSheet()->setCellValue("B".$row, $barcode);
			$this->excel->getActiveSheet()->setCellValue("C".$row, $ds['item_code']);
			$this->excel->getActiveSheet()->setCellValue("D".$row, $ds['item_name']);
			$this->excel->getActiveSheet()->setCellValue("E".$row, $ds['cost']);
			$this->excel->getActiveSheet()->setCellValue("F".$row, $ds['price']);
			$this->excel->getActiveSheet()->setCellValue("G".$row, $imported_qty);
			$this->excel->getActiveSheet()->setCellValue("H".$row, '=E'.$row.' * G'.$row);
			$this->excel->getActiveSheet()->setCellValue("I".$row, $rs->qty);
			$this->excel->getActiveSheet()->setCellValue("J".$row, '=E'.$row.' * I'.$row);
			$this->excel->getActiveSheet()->setCellValue("K".$row, '=I'.$row.' - G'.$row);
			$this->excel->getActiveSheet()->setCellValue("L".$row, '=E'.$row.' * K'.$row);
			$this->excel->getActiveSheet()->setCellValue("M".$row, '=F'.$row.' * K'.$row);
			$this->excel->getActiveSheet()->setCellValue("N".$row, group_type($ds['type']));
			
			/// ใส่ คอมม่า ให้หลักพัน และเติม ทศนิยม 2 ตำแหน่ง
			$this->excel->getActiveSheet()->getStyle("E".$row)->getNumberFormat()->setFormatCode("#,##0.00"); 
			$this->excel->getActiveSheet()->getStyle("F".$row)->getNumberFormat()->setFormatCode("#,##0.00"); 
			$this->excel->getActiveSheet()->getStyle("G".$row)->getNumberFormat()->setFormatCode("#,##0"); 
			$this->excel->getActiveSheet()->getStyle("H".$row)->getNumberFormat()->setFormatCode("#,##0.00"); 
			$this->excel->getActiveSheet()->getStyle("I".$row)->getNumberFormat()->setFormatCode("#,##0");
			$this->excel->getActiveSheet()->getStyle("J".$row)->getNumberFormat()->setFormatCode("#,##0.00"); 
			$this->excel->getActiveSheet()->getStyle("K".$row)->getNumberFormat()->setFormatCode("#,##0");
			$this->excel->getActiveSheet()->getStyle("L".$row)->getNumberFormat()->setFormatCode("#,##0.00"); 
			$this->excel->getActiveSheet()->getStyle("M".$row)->getNumberFormat()->setFormatCode("#,##0.00"); 
			
			$n++; $row++;	
		}
		//// เพิ่มส่วนที่มียอดตั้งต้นแต่ไม่มียอดตรวจนับ
		
		$imported = $this->report_model->get_imported_item($id);
		if($imported)  /// ถ้ามีการนำเข้ายอดตั้งต้น
		{
			foreach($imported as $rs)
			{				
				$barcode 		= $rs->barcode;
				$ra 				= $this->report_model->isExists($id, $barcode);  /// ตรวจสอบว่ามียอดตรวจนับหรือไม่
				if(!$ra) 																		 /// ถ้าไม่มียอดตรวจนับ เพิ่มรายการเข้าในไฟล์
				{
					$ds				= $this->product_model->get_items($barcode);
					$imported_qty	= $rs->qty;
					
					$this->excel->getActiveSheet()->setCellValue("A".$row, $n);
					$this->excel->getActiveSheet()->setCellValue("B".$row, $barcode);
					$this->excel->getActiveSheet()->setCellValue("C".$row, $ds['item_code']);
					$this->excel->getActiveSheet()->setCellValue("D".$row, $ds['item_name']);
					$this->excel->getActiveSheet()->setCellValue("E".$row, $ds['cost']);
					$this->excel->getActiveSheet()->setCellValue("F".$row, $ds['price']);
					$this->excel->getActiveSheet()->setCellValue("G".$row, $imported_qty);
					$this->excel->getActiveSheet()->setCellValue("H".$row, '=E'.$row.' * G'.$row);
					$this->excel->getActiveSheet()->setCellValue("I".$row, 0);
					$this->excel->getActiveSheet()->setCellValue("J".$row, '=E'.$row.' * I'.$row);
					$this->excel->getActiveSheet()->setCellValue("K".$row, '=I'.$row.' - G'.$row);
					$this->excel->getActiveSheet()->setCellValue("L".$row, '=E'.$row.' * K'.$row);
					$this->excel->getActiveSheet()->setCellValue("M".$row, '=F'.$row.' * K'.$row);
					$this->excel->getActiveSheet()->setCellValue("N".$row, group_type($ds['type']));			
					$n++; $row++;		
				} /// end if
			} /// end foreach
		}// end if
		///  ใส่ยอดรวมในบรรทัดสุดท้าย
		$this->excel->getActiveSheet()->setCellValue("A".$row, "รวม");
		$this->excel->getActiveSheet()->getStyle("A".$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->mergeCells("A".$row.":F".$row);	
		$this->excel->getActiveSheet()->setCellValue("G".$row, "=SUM(G7:G".($row-1).")");  /// จำนวนตั้งต้น
		$this->excel->getActiveSheet()->setCellValue("H".$row, "=SUM(H7:H".($row-1).")"); /// มูลค่าตั้งต้น
		$this->excel->getActiveSheet()->setCellValue("I".$row, "=SUM(I7:I".($row-1).")"); /// จำนวนตรวจนับ
		$this->excel->getActiveSheet()->setCellValue("J".$row, "=SUM(J7:J".($row-1).")"); /// มูลค่าตรวจนับ
		$this->excel->getActiveSheet()->setCellValue("K".$row, "=SUM(K7:K".($row-1).")"); /// ยอดต่าง
		$this->excel->getActiveSheet()->setCellValue("L".$row, "=SUM(L7:L".($row-1).")");  /// มูลค่ายอดต่าง
		$this->excel->getActiveSheet()->setCellValue("M".$row, "=SUM(M7:M".($row-1).")");  /// มูลค่ายอดต่าง
		
		/// กำหนดความสูงของแถว และ เติมเส้นของด้านล่าง
		$this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(30);
		$this->excel->getActiveSheet()->getStyle("A".$row.":N".$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		
		///  กำหนดรูปแบบ column  บาร์โค้ด ให้เป็น number ไม่มีจุดทศนิยม ไม่มีลูกน้ำขั้นหลักพัน
		$this->excel->getActiveSheet()->getStyle("B7:B".$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		$this->excel->getActiveSheet()->getStyle("B7:B".($row -1))->getAlignment()->setHorizontal("center");
		
		/// ใส่ คอมม่า ให้หลักพัน และเติมทศนิยม
		$this->excel->getActiveSheet()->getStyle("E7:F".$row)->getNumberFormat()->setFormatCode("#,##0.00"); /// ทุน และ ขาย
		$this->excel->getActiveSheet()->getStyle("H7:H".$row)->getNumberFormat()->setFormatCode("#,##0.00"); /// มูลค่าตั้งต้น
		$this->excel->getActiveSheet()->getStyle("J7:J".$row)->getNumberFormat()->setFormatCode("#,##0.00"); ///  มูลค่าตรวจนับ
		$this->excel->getActiveSheet()->getStyle("L7:L".$row)->getNumberFormat()->setFormatCode("#,##0.00"); /// มูลค่ายอดต่าง
		$this->excel->getActiveSheet()->getStyle("M7:M".$row)->getNumberFormat()->setFormatCode("#,##0.00"); /// มูลค่ายอดต่าง
		
		/// ใส่ คอมม่า ให้หลักพัน ไม่เติมทศนิยม
		$this->excel->getActiveSheet()->getStyle("G7:G".$row)->getNumberFormat()->setFormatCode("#,##0"); /// จำนวนตั้งต้น
		$this->excel->getActiveSheet()->getStyle("I7:I".$row)->getNumberFormat()->setFormatCode("#,##0"); /// จำนวนตรวจนับ
		$this->excel->getActiveSheet()->getStyle("K7:K".$row)->getNumberFormat()->setFormatCode("#,##0"); /// ยอดต่าง
		
		
		/// 
		$this->session->set_userdata("token", $token);
		$file_name = "check_stock_summary_report.xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
		header('Content-Disposition: attachment;filename="'.$file_name.'"');
		$writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$writer->save('php://output');
		
	}
}

public function export_detail($id, $token)
{
	$this->load->library("excel");
	$rh 	= $this->report_model->get_data($id);
	if($rh) //// ถ้ามีข้อมูลการตรวจนับ
	{
		$re 	= $this->report_model->get_employee($id);
		///  หัวข้อใหญ่รายงาน
		$this->excel->setActiveSheetIndex(0);/// สร้าง sheet
		$this->excel->getActiveSheet()->setTitle('รายละเอียดการตรวจนับ');  /// ตั้งชื่อ Sheet
		$this->excel->getActiveSheet()->setCellValue("A1", "รายงานรายละเอียดการตรวจนับสินค้า"); /// เพิ่มข้อมูลลง เซล A1
		$this->excel->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);/// ตั้ง ขนาดตัวอักษร
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);/// ทำตัวหนา
		$this->excel->getActiveSheet()->mergeCells('A1:F1'); /// รวมเซล
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //// จัด กึ่งกลาง
		
		
		
		/// อ้างอิง และ หัวข้อ
		$this->excel->getActiveSheet()->setCellValue("A2", "อ้างอิง");
		$this->excel->getActiveSheet()->getStyle("A2")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);  /// font colot to blue
		$this->excel->getActiveSheet()->setCellValue("B2", $rh->reference);
		$this->excel->getActiveSheet()->getStyle("B2")->getAlignment()->setHorizontal("center"); /// จัดกึ่งกลางแบบนี้ก็ได้
		
		$this->excel->getActiveSheet()->setCellValue("C2", "หัวข้อ");
		$this->excel->getActiveSheet()->getStyle("C2")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);  /// font colot to blue
		$this->excel->getActiveSheet()->getStyle("C2")->getAlignment()->setHorizontal("right"); /// จัดชิดขาว
		$this->excel->getActiveSheet()->setCellValue("D2", $rh->subject);
		$this->excel->getActiveSheet()->mergeCells("D2:F2");
		
		
		
		/// สถานที่
		$this->excel->getActiveSheet()->setCellValue("A3", "สถานที่");
		$this->excel->getActiveSheet()->getStyle("A3")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);  /// font colot to blue
		$this->excel->getActiveSheet()->setCellValue("B3", $rh->location_code." : ".$rh->location);
		$this->excel->getActiveSheet()->mergeCells("B3:F3");
		
		/// วันที่
		$this->excel->getActiveSheet()->setCellValue("A4", "เริ่มต้น");
		$this->excel->getActiveSheet()->getStyle("A4")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);  /// font colot to blue
		$this->excel->getActiveSheet()->setCellValue("B4", thaiDate($rh->date_open));
		$this->excel->getActiveSheet()->getStyle("B4")->getAlignment()->setHorizontal("center");
		$this->excel->getActiveSheet()->setCellValue("D4", "สิ้นสุด");
		$this->excel->getActiveSheet()->getStyle("D4")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);  /// font colot to blue
		$this->excel->getActiveSheet()->setCellValue("E4", thaiDate($rh->date_close));
		$this->excel->getActiveSheet()->getStyle("E4")->getAlignment()->setHorizontal("center");
		
		/// พนักงานตรวจนับ
		$this->excel->getActiveSheet()->setCellValue("H2", "พนักงาน");
		$this->excel->getActiveSheet()->getStyle("H2")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);  /// font colot to blue
		$row = 3; $col = 7;  /// Note: In PHPExcel column index is 0-based while row index is 1-based. That means 'A1' ~ (0,1) เริ่มที่  H3
		foreach($re as $rd)
		{
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rd->first_name);
			$row++;
		}
		
		/// header_row
		$this->excel->getActiveSheet()->setCellValue("A6", "ลำดับ");
		$this->excel->getActiveSheet()->setCellValue("B6", "บาร์โค้ด");
		$this->excel->getActiveSheet()->setCellValue("C6", "สินค้า");
		$this->excel->getActiveSheet()->setCellValue("D6", "จำนวน");
		$this->excel->getActiveSheet()->setCellValue("E6", "พนักงาน");
		$this->excel->getActiveSheet()->setCellValue("F6", "เวลา");
		
		/// รายการตรวจนับ
		$re 	= $this->report_model->get_detail_report($id);
		$n = 1;
		$row = 7; 
		foreach($re as $rs)
		{
			$barcode 		= $rs->barcode;
			$item_code		= get_item_code($barcode);
			
			$this->excel->getActiveSheet()->setCellValue("A".$row, $n);
			$this->excel->getActiveSheet()->setCellValue("B".$row, $barcode);
			$this->excel->getActiveSheet()->setCellValue("C".$row, $item_code);
			$this->excel->getActiveSheet()->setCellValue("D".$row, $rs->qty);
			$this->excel->getActiveSheet()->setCellValue("E".$row, employee_first_name($rs->id_employee));
			$this->excel->getActiveSheet()->setCellValue("F".$row, thaiDate($rs->date_add, true, "/"));
			
			$n++; $row++;	
		}
		
		/// กำหนดความกว้างของ column
		$this->excel->getActiveSheet()->getColumnDimension("B")->setWidth(17); /// กว้าง 17 ตัวอักษร สำหรับบาร์โค้ด
		$this->excel->getActiveSheet()->getColumnDimension("C")->setWidth(25); /// รหัสสินค้า
		$this->excel->getActiveSheet()->getColumnDimension("E")->setWidth(15); /// ชื่อพนักงาน
		$this->excel->getActiveSheet()->getColumnDimension("F")->setWidth(18); /// เวลา
		
		/// format cell barcode as number only
		$this->excel->getActiveSheet()->getStyle("B7:B".$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		$this->excel->getActiveSheet()->getStyle("B7:B".$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		/// format cell date time as date time by custom code
		$this->excel->getActiveSheet()->getStyle("F7:F".$row)->getNumberFormat()->setFormatCode("dd/mm/yyy h:mm:s");
		
		
		$this->session->set_userdata("token", $token);
		$file_name = "check_stock_detail_report.xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
		header('Content-Disposition: attachment;filename="'.$file_name.'"');
		$writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$writer->save('php://output');
	
	}// endif first
}

}/// end class
?>