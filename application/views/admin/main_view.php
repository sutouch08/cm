<?php /***********************************   ระบบตรวจสอบสิทธิ์  ******************************************/ ?>
<?php $access 	= valid_access($id_menu);  ?>
<?php $view		= $access['view']; ?>
<?php $add 		= $access['add']; ?>
<?php $edit 		= $access['edit']; ?>
<?php $delete		= $access['delete']; ?>
<?php if(!$view) : ?>
<?php access_deny();  ?>
<?php else : ?>

<div class='row'>
	<div class='col-lg-12'>
    	<h3 style='margin-bottom:0px;'><?php echo $this->title; ?></h3>
    </div>
</div><!-- End Row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:5px;' />
<div class="tabbable tabs-below">
<div class="tab-content">
<div id="tab1" class="tab-pane active">
<div class="row">
    <form id="search_form" action="<?php echo $this->home; ?>" method="post">
    <div class="col-lg-2 col-md-2 col-sm-6">
        <label >ตัวกรอง</label>
        <select id="filter" name="filter" class="form-control input-sm">
            <option value="all" <?php echo isSelected("all", $filter); ?> >ทั้งหมด</option>
            <option value="reference" <?php echo isSelected("reference", $filter); ?>>เลขที่อ้างอิง</option>
            <option value="subject" <?php echo isSelected("subject", $filter); ?>>หัวข้อ</option>
            <option value="location" <?php echo isSelected("location", $filter); ?>>สถานที่</option>
            <option value="employee" <?php echo isSelected("employee", $filter); ?>>พนักงาน</option>
        </select>   
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <label >คำค้น</label>
        <input type="text" id="search_text" name="search_text" placeholder="ระบุรายการค้นหา" class="form-control input-sm" value="<?php echo $search_text; ?>" autofocus />  
    </div>
    <div class="col-lg-2 col-md-2 col-sm-4">
        <label>จากวันที่</label>
        <input type="text" id="from_date" name="from_date" class="form-control input-sm" value="<?php echo $from_date; ?>" />
    </div>
    <div class="col-lg-2 col-md-2 col-sm-4">
        <label >ถึงวันที่</label>
        <input type="text" id="to_date" name="to_date" class="form-control input-sm" value="<?php echo $to_date; ?>" />  
    </div>
	</form>
    <div class="col-lg-1 col-md-2 col-sm-4">
        <label style="display:block;">&nbsp;</label>
        <button type="button" class="btn btn-info btn-xs btn-block" id="btn_search" onclick="get_search()" ><i class="fa fa-search"></i>&nbsp; ค้นหา</button>
    </div>
    <div class="col-lg-1 col-md-2 col-sm-4">
        <label style="display:block;">&nbsp;</label>
        <a href="<?php echo $this->home; ?>/clear_filter/"><button type="button" class="btn btn-warning btn-xs btn-block" id="btn_reset" ><i class="fa fa-refresh"></i>&nbsp; Reset</button></a>
    </div>

</div><!--/ Row -->
</div><!--/ tab1 -->

<div id="tab2" class="tab-pane">
<div class="row">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <label >สถานที่ตรวจนับ</label>
        <select name="location" id="location" class="form-control input-sm">
            <option value="">เลือกสถานที่</option>
            <?php echo select_location(); ?>
        </select>
    </div>

<div class="col-lg-3 col-md-3 col-sm-6">
	<label >หัวข้อการตรวจนับ</label>
	<input type="text" id="subject" name="subject" class="form-control input-sm" placeholder="ระบุหัวข้อการตรวจนับ เช่น ครั้งที่ 1/2559" />
</div>
<div class="col-lg-4 col-md-4 col-sm-6">
	<label for="detail">หมายเหตุ</label>
	<input type="text" id="remark" name="remark" class="form-control input-sm" placeholder="ระบุหมายเหตุ (ถ้ามี)" />
</div>

<?php if($add) : ?>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="btn_save">&nbsp;</label>
	<button type="button" id="btn_save" onclick="new_check()" class="btn btn-success btn-xs btn-block"><i class="fa fa-plus"></i>&nbsp; เพิ่มใหม่</button>
</div>
<?php endif; ?>
<input type="hidden" id="active" value="1" />

</div><!--/ Row -->
</div><!-- tab2 -->
</div><!-- tab content -->

<ul class="nav nav-tabs" id="myTab">
<li class="active" id="li_tab1"><a aria-expanded="false" data-toggle="tab" href="#tab1"><i class="fa fa-search"></i>&nbsp; ค้นหา</a></li>
<li class="" id="li_tab2"><a aria-expanded="true" data-toggle="tab" href="#tab2"><i class="fa fa-plus"></i>&nbsp; เพิ่มใหม่</a></li>
</ul>
<div class="row">
    <div class="col-lg-12" style="height:1px !important">
    <p class="pull-right" style="margin-top:-25px;">
    <button type="button" class="btn btn-success btn-minier"><i class="fa fa-check"></i></button> = เปิดใช้งาน , &nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-warning btn-minier"><i class="fa fa-pause"></i></button> = หยุดชั่วคราว , &nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-minier btn-danger"><i class="fa fa-stop"></i></button> = ปิดการตรวจนับแล้ว &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
    <?php echo rows_box(); ?>
    </p>
    </div>
</div>
</div>

<hr style='border-color:#CCC; margin-top: 10px; margin-bottom:0px;' />

<div class='row'>
	<div class='col-xs-12' style="padding-bottom:20px;">
    <table class='table table-striped'>
    <thead>
    	<tr style='font-size:10px;'>
            <th style='width:8%;'>เลขที่อ้างอิง</th>
            <th style='width:15%;'>หัวข้อการตรวจนับ</th>
            <th style="width:20%;">สถานที่</th>
            <th style="width:10%">พนักงาน</th>
            <th style="width:8%;">วันที่เริ่ม</th>
            <th style="width:8%;">วันที่จบ</th>
            <th style="width:5%; text-align:center">ข้อมูล</th>
            <th style="width:5%; text-align:center">สถานะ</th>
            <th style="width:8%; text-align:center">หมายเหตุ</th>
            <th style="text-align:right"></th>
        </tr>
      </thead>
      <tbody id="rs">
<?php if($data != false) : ?>
        <?php foreach($data as $rs): ?>
        <?php 	$id = $rs->id_check; ?>
        		<tr id="row_<?php echo $id; ?>" style="font-size:11px;">
                   <td><?php echo $rs->reference; ?></td>
                   <td><?php echo $rs->subject; ?></td>
                   <td><?php echo $rs->location; ?></td>
                   <td><?php echo $rs->employee; ?></td>
                   <td><?php echo thaiDate($rs->date_open); ?></td>
                   <td id="date_close<?php echo $id; ?>"><?php if( $rs->status == 1 ){ echo thaiDate($rs->date_close); }else{ echo "กำลังตรวจนับ"; } ?></td>
                   <td align="center"><?php echo isActived($rs->import); ?></td>
                   <td align="center" id="status_<?php echo $id; ?>">
                   <?php if( $rs->status != 1 ) : ?>
					   <?php if($rs->pause == 0) : ?>
                            <button type="button" class="btn btn-minier btn-success" id="btn_check_<?php echo $id; ?>" onclick="pause(<?php echo $id; ?>)"><i class="fa fa-check"></i></button>
                            <button type="button" class="btn btn-minier btn-warning" id="btn_pause_<?php echo $id; ?>" onclick="check(<?php echo $id; ?>)" style="display:none;"><i class="fa fa-pause"></i></button>
                       <?php elseif( $rs->pause == 1 ) : ?>
                            <button type="button" class="btn btn-minier btn-success" id="btn_check_<?php echo $id; ?>" onclick="pause(<?php echo $id; ?>)" style="display:none;"><i class="fa fa-check"></i></button>
                            <button type="button" class="btn btn-minier btn-warning"  id="btn_pause_<?php echo $id; ?>" onclick="check(<?php echo $id; ?>)"><i class="fa fa-pause"></i></button>              
                       <?php endif; ?>
                  <?php else : ?>
                  			<button type="button" class="btn btn-minier btn-danger"><i class="fa fa-stop"></i></button>
                   <?php endif; ?>
                   </td>
                   <td align="center" >
                   <?php if(!is_null($rs->remark)) : ?>
                   	<span style="cursor:pointer;" tabindex="0" role="button" data-trigger="focus" aria-describedby="popover" data-original-title="<i class='ace-icon fa fa-exclamation-triangle blue'></i> หมายเหตุ" class="popover-info" data-rel="popover" data-placement="left" title="" data-content="<?php echo $rs->remark; ?>"><i class='ace-icon fa fa-exclamation-triangle fa-2x blue'></i></span>
                    <?php endif; ?>
                   </td>
                    <td align="right" style="vertical-align:middle;">
                    <div class="btn-group">
                    <button class="btn btn-primary btn-minier btn-white dropdown-toggle" aria-expanded="false" data-toggle="dropdown">คำสั่ง &nbsp;<i class="face-icon fa fa-angle-down icon-on-right"></i></button>
                    	<ul class="dropdown-menu dropdown-menu-right">
                        	<li><a href="javascript:void(0)" onclick="select_file(<?php echo $id; ?>)" ><i class="fa fa-upload"></i> &nbsp; นำเข้ายอดตั้งต้น</a></li>
                            <li><a href="javascript:void(0)" onclick="delete_imported(<?php echo $id; ?>)"><i class="fa fa-trash"></i> &nbsp; ลบรายการนำเข้า</a></li>
                            
                           <?php if($edit && !$rs->status) : ?>
                           <li id="btn_close<?php echo $id; ?>" ><a href="javascript:void(0)" onclick="close_check(<?php echo $id; ?>)"><i class="fa fa-close"></i>&nbsp; ปิดการตรวจนับ</a></li> 
						   <?php endif; ?>
                           
                           <?php if($edit && $rs->status) : ?>
                           <li id="btn_open<?php echo $id; ?>"><a href="javascript:void(0)" onclick="open_check(<?php echo $id; ?>)"><i class="fa fa-check"></i>&nbsp; เปิดการตรวจนับ</a></li>
						   <?php endif; ?>
                           
                           <li class="divider" id="divider<?php echo $id; ?>"></li>
                           
                           <?php if($edit) : ?> 
                           <li><a href="javascript:void(0)" id="btn_edit<?php echo $id; ?>" onclick="edit_row(<?php echo $id; ?>)"><i class="fa fa-pencil"></i>&nbsp; แก้ไขรายการ</a></li> 
						   <?php endif; ?>
                           
                    	   <?php if($delete) : ?> 
                           <li><a href="javascript:void(0)" onclick="confirm_delete(<?php echo $id; ?>)"><i class="fa fa-trash"></i>&nbsp; ลบการตรวจนับ</a></li> 
						   <?php endif; ?>
                        </ul>
                    </div>
                    </td>
                </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr id="nocontent"><td colspan="9" align="center" ><h4>-----  ไม่พบรายการใดๆ  -----</h4></td></tr>
    <?php endif; ?>
		</table>
        <?php echo $this->pagination->create_links(); ?>
</div><!-- End col-lg-12 -->
</div><!-- End row -->

<!------------------------------------------------- Modal  Import file ----------------------------------------------------------->
<div class='modal fade' id='import_modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' style='width:500px;'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4 class='modal-title' id='myModalLabel'>นำเข้าไฟล์รายการสินค้าตั้งต้น</h4>
		  </div>
		  <div class='modal-body' id="import_body">      
          <form id="myform" action="<?php echo $this->home; ?>/import_items" method="post" enctype="multipart/form-data">
            <div class="row">
            <div class="form-group">
            <div class="col-lg-8 col-md-8 col-sm-6">
                <!-- #section:custom/file-input -->
                    <input id="user_file" type="file" name="user_file" class="input-sm"> 
                    <input type="hidden" id="id_check" name="id_check" />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <button type="button" class="btn btn-success btn-xs btn-block" id="btn_upload" onclick="upload()" ><i class="fa fa-upload"></i>&nbsp; นำเข้า</button>
            </div>
            </div>
            </div><!--/ Row -->
            </form>
          </div><!--- modal-body -->
		</div>
	</div>
</div>
<!------------------------------------------------- END Modal  ----------------------------------------------------------->

<!------------------------------------------------- Modal  Edit ----------------------------------------------------------->
<div class='modal fade' id='edit_modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' style='width:500px;'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4 class='modal-title' id='myModalLabel'>แก้ไขรายการ</h4>
		  </div>
		  <div class='modal-body' id="edit_body">      
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                <label >สถานที่ตรวจนับ</label>
                <select name="edit_location" id="edit_location" class="form-control input-sm">
                    <option value="">เลือกสถานที่</option>
                    <?php echo select_location(); ?>
                </select>
            </div>  
            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                <label >หัวข้อการตรวจนับ</label>
                <input type="text" id="edit_subject" name="edit_subject" class="form-control input-sm" placeholder="ระบุหัวข้อการตรวจนับ เช่น ครั้งที่ 1/2559" />
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                <label for="detail">หมายเหตุ</label>
                <input type="text" id="edit_remark" class="form-control input-sm" placeholder="ระบุหมายเหตุ (ถ้ามี)" />
            </div>
            
            <?php if($edit) : ?>
            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                <label for="btn_save">&nbsp;</label>
                <button type="button" id="btn_save" onclick="edit_check()" class="btn btn-success btn-xs btn-block"><i class="fa fa-save"></i>&nbsp; บันทึก</button>
            </div>
            <?php endif; ?>    
            <input type="hidden" name="edit_id_check" id="edit_id_check" />
            </div><!--/ Row -->
          </div><!--- modal-body -->
		</div>
	</div>
</div>
<!------------------------------------------------- END Modal  ----------------------------------------------------------->

<script>
function delete_imported(id)
{
	$.ajax({
		url:"<?php echo $this->home; ?>/delete_imported_items",
		type:"POST", cache:"false", data: { "id_check" : id }, success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "success")
			{
				swal({ title: "สำเร็จ", text: "ลบข้อมูลตั้งต้นเรียบร้อยแล้ว", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.href = "<?php echo $this->home; ?>"; }, 1000);
			}
			else
			{
				swal("ไม่สำเร็จ", "ลบข้อมูลตั้งต้นไม่สำเร็จ", "error");	
			}
		}
	});
}

function close_check(id)
{
	$.ajax({
		url:"<?php echo $this->home;?>/isPause/"+id,
		type:"GET", cache:"false", success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "1")  /// 1 = pause 0 = not pause; allow to close only pause row
			{
				$.ajax({
					url:"<?php echo $this->home; ?>/close_check/"+id,
					type:"GET", cache:false, success: function(rd)
					{
						var rd = $.trim(rd);
						if(rd == "success")
						{
							swal({ title: "เรียบร้อย", text: "ปิดการตรวจนับเรียบร้อยแล้ว", timer: 1000, type : "success"});
							$("#btn_close"+id).remove();
							$('<li id="btn_open'+id+'"><a href="javascript:void(0)" onclick="open_check('+id+')"><i class="fa fa-check"></i>&nbsp; เปิดการตรวจนับ</a></li>').insertBefore($("#divider"+id));	
							$("#status_"+id).html('<button type="button" class="btn btn-minier btn-danger"><i class="fa fa-stop"></i></button>');
							var d = new Date();
							var date = d.getDate()+"-"+(d.getMonth() +1)+"-"+(d.getFullYear()+543);
							$("#date_close"+id).html(date);
						}
						else
						{
							swal("ไม่สำเร็จ", "ปิดการตรวจนับไม่สำเร็จ", "error");	
						}
					}
				});
			}
			else
			{
				swal("หยุดการตรวจนับก่อน", "กรุณาหยุดการตรวจนับก่อนแล้วค่อยปิดการตรวจนับ", "error");	
			}
		}
	});
}

function open_check(id)
{
	$.ajax({
		url:"<?php echo $this->home; ?>/open_check/"+id,
		type:"GET", cache: false, success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "success")
			{
				swal({ title: "เรียบร้อย", text: "เปิดการตรวจนับเรียบร้อยแล้ว", timer: 1000, type : "success"});
				$("#btn_open"+id).remove();
				$('<li id="btn_close'+id+'"><a href="javascript:void(0)" onclick="close_check('+id+')"><i class="fa fa-close"></i>&nbsp; ปิดการตรวจนับ</a></li>').insertBefore($("#divider"+id));
				var html = '<button type="button" class="btn btn-minier btn-success" id="btn_check_'+id+'" onclick="pause('+id+')" style="display:none;"><i class="fa fa-check"></i></button>';
					html += '<button type="button" class="btn btn-minier btn-warning"  id="btn_pause_'+id+'" onclick="check('+id+')"><i class="fa fa-pause"></i></button>';
				$("#status_"+id).html(html);
				$("#date_close"+id).html("กำลังตรวจนับ");
			}
		}
	});
}

function edit_row(id)
{
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/get_data",
		type:"POST", cache: false, data:{ "id_check" : id },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if( rs == "fail" || rs == "" )
			{
				swal("ไม่พบข้อมูลที่ต้องการแก้ไข");
			}
			else
			{
				var rs = rs.split(" | ");
				$("#edit_id_check").val(id);
				$("#edit_location").val(rs[0]);
				$("#edit_subject").val(rs[2]);
				$("#edit_remark").val(rs[1]);
				$("#edit_modal").modal("show");	
			}
		}
	});
}

function edit_check()
{
	var id_check 	= $("#edit_id_check").val();
	var location		= $("#edit_location").val();
	var subject		= $("#edit_subject").val();
	var remark		= $("#edit_remark").val();
	if( id_check == "" )	{ swal("ไม่พบ ID ของรายการ กรุณาลองใหม่อีกครั้ง"); return false; }
	if( location == "" ){ swal("กรุณาเลือกสถานที่ตรวจนับ"); return false; }
	if( subject == ""){ swal("กรุณาระบุหัวข้อการตรวจนับ"); return false; }
	$("#edit_modal").modal("hide");
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/update_check",
		type:"POST", cache:"false", data:{ "id_check" : id_check, "location_code" : location, "subject" : subject, "remark" : remark },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if( rs == "success" )
			{
				swal({ title: "สำเร็จ", text: "ปรับปรุงข้อมูลเรียบร้อยแล้ว", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.href = "<?php echo $this->home; ?>"; }, 1000);
			}
			else
			{
				swal({ title: "ไม่สำเร็จ", text: "ปรับปรุงข้อมูลไม่สำเร็จ", type: "error"}, function(){ $("#edit_modal").modal("show"); });
			}
		}
	});
}

$('[data-rel=popover]').popover({html:true});

function new_check()
{
	var location = $("#location").val();
	var subject 	= $("#subject").val();
	var remark	= $("#remark").val();
	if(location == ""){ swal("กรุณากำหนดสถานที่ตรวจนับ"); return false; }
	if(subject == ""){ swal("กรุณากำหนดหัวข้อการตรวจนับ"); return false; }
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/add_new_check",
		type:"POST", cache:"false", data:{ "location" : location, "subject" : subject, "remark" : remark },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if(rs == "success")
			{
				swal({title: "สำเร็จ", text: "เพิ่มรายการใหม่เรียบร้อยแล้ว", type: "success", timer: 1000 });	
				window.location.href = "<?php echo $this->home; ?>";
			}
			else
			{
				swal({title: "ไม่สำเร็จ", text: "เพิ่มรายการใหม่ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง", type: "error"});
			}
		}
	});
}


function select_file(id)
{
	$("#id_check").val(id);
	$("#import_modal").modal("show");	
}

function pause(id)
{
	load_in()
	$.ajax({
		url:"<?php echo $this->home; ?>/pause_check",
		type:"POST", cache:"false", data:{ "id_check" : id },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if(rs == "success")
			{
				$("#btn_check_"+id).css("display", "none");
				$("#btn_pause_"+id).css("display","");
			}
			else
			{
				swal("ไม่สำเร็จ");	
			}
		}
	});
}

function check(id)
{
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/continue_check",
		type:"POST", cache:"false", data:{ "id_check" : id },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if(rs=="success")
			{
				$("#btn_pause_"+id).css("display", "none");
				$("#btn_check_"+id).css("display", "");	
			}
			else if(rs=="muliticheck")
			{
				swal("ข้อผิดพลาด", "มีรายการอื่นเปิดอยู่ คุณต้องหยุดใช้งานรายการอื่นก่อน หากต้องการเปิดใช้งานรายการนี้", "error");	
			}
			else
			{
				swal("ไม่สำเร็จ");
			}
		}
	});
}
function upload()
{
	var file = $("#user_file").val();
	if(file == "")
	{ 
		swal("กรุณาเลือกไฟล์"); 
	}
	else
	{
		$("#import_modal").modal("hide");
		load_in();
		$("#myform").submit();
	}
}


function confirm_delete(id)
{
	swal({
		  title: "แน่ใจนะ?",
		  text: "คุณกำลังจะลบข้อมูลการตรวจนับของรายการนี้ทั้งหมด โปรดตรวจสอบให้แน่ใจว่าคุณต้องการลบจริง ๆ",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "ใช่ ลบเลย",
		  cancelButtonText: "ยกเลิก",
		  closeOnConfirm: false
		},
		function(isConfirm){
		  if (isConfirm) 
		  {
				delete_checked(id);
		  } 
		});
}

function delete_checked(id)
{
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/delete_checked/"+id,
		type: "GET", cache:"false", success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if(rs == "success")
			{
				swal({ title: "เรียบร้อย", text: "ลบรายการตรวจนับเรียบร้อยแล้ว", timer: 1000, type: "success"});
				$("#row_"+id).remove();	
			}
			else
			{
				swal("ไม่สำเร็จ", 	"ลบรายการไม่สำเร็จ", "error");
			}
		}
	});
}

$("#search_text").keyup(function(e){	if(e.keyCode == 13 ){ get_search(); } });
$("#search_text").keyup(function(e) { if( e.keyCode == 39 ){ $("#from_date").focus(); }});
$("#from_date").keyup(function(e) { if(e.keyCode == 13){ $("#to_date").focus(); }});
$("#to_date").keyup(function(e){ if(e.keyCode == 13){ get_search(); }});
function get_search()
{
	var txt 	= $("#search_text").val();
	var filter 	=	$("#filter").val();
	var from 	= $("#from_date").val();
	var to 	= $("#to_date").val();
	if(txt != "" || ( from != "" && to != "") )
	{
		if(from != "" && to !=""){ if(!isDate(from) || !isDate(to)){ swal("วันที่ไม่ถูกต้อง"); return false; }};
		$("#search_form").submit();		
	}
}

$("#from_date").datepicker({ 	format : "dd/mm/yyyy", autoclose: true, todayHighlight: true});
$("#to_date").datepicker({ format : "dd/mm/yyyy", autoclose: true, todayHighlight: true });
$("#user_file").ace_file_input({
	btn_choose : 'เลือกไฟล์',
	btn_change: 'เปลี่ยน',
	droppable: true,
	thumbnail: 'large',
	maxSize: 5000000,//bytes
	allowExt: ["csv|xls|xlsx"]
});

$("#user_file").on('file.error.ace', function(ev, info) {
	if(info.error_count['ext'] || info.error_count['mime']) swal('กรุณาเลือกไฟล์นามสกุล .csv .xls หรือ .xlsx เท่านั้น');
	if(info.error_count['size']) swal('ขนาดไฟล์สูงสุดไม่เกิน 5 MB');
});
</script>

<?php endif; ?>