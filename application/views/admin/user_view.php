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
    	<h3 style='margin-bottom:0px;'><i class='fa fa-tint'></i>&nbsp; <?php echo $this->title; ?></h3>
    </div>
</div><!-- End Row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:20px;' />
<div class="tabbable tabs-below">
<div class="tab-content">
<div id="tab1" class="tab-pane active">
<div class="row">
<div class="col-lg-4 col-md-4 col-sm-6">
	<form id="search_form" action="<?php echo $this->home; ?>" method="post">
	<label for="search_detail">ค้นหาพนักงาน</label>
	<input type="text" id="search_text" name="emp_search" placeholder="ระบุรายการค้นหา" class="form-control input-sm" value="<?php echo $user_search; ?>" />
    </form>
</div>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="btn_search" style="display:block;">&nbsp;</label>
	<button type="button" class="btn btn-info btn-xs btn-block" id="btn_search" onclick="get_search()" ><i class="fa fa-search"></i>&nbsp; ค้นหา</button>
</div>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="btn_reset" style="display:block;">&nbsp;</label>
	<a href="<?php echo $this->home; ?>/clear_filter/"><button type="button" class="btn btn-warning btn-xs btn-block" id="btn_reset" ><i class="fa fa-refresh"></i>&nbsp; เคลีร์ยตัวกรอง</button></a>
</div>
</div><!--/ Row -->
</div><!--/ tab1 -->

<div id="tab2" class="tab-pane">
<div class="row">
<form id="add_form">
<div class="col-lg-3 col-md-3 col-sm-4">
    <label for="due_date">พนักงาน</label>
	<input type="text" id="employee" name="employee" class="form-control input-sm" autofocus="autofocus" onkeydown="next_field($(this), $('#user_name'))" />
</div>

<div class="col-lg-2 col-md-4 col-sm-6">
	<label for="detail">User name</label>
	<input type="text" id="user_name" name="user_name" class="form-control input-sm" onkeydown="next_field($(this), $('#password'))" />
</div>

<div class="col-lg-2 col-md-4 col-sm-6">
	<label for="reference">รหัสผ่าน</label>
	<input type="password" id="password" name="password" autocomplete="off" class="form-control input-sm" onkeydown="next_field($(this), $('#profile'))" />
</div>
<div class="col-lg-2 col-md-3 col-sm-4">
	<label for="cash_out">โปรไฟล์</label>
	<select name="profile" id="profile" class="form-control input-sm"><?php echo select_profile(); ?></select>
</div>
<div class="col-lg-2 col-md-4 col-sm-6">
	<label style="display:block;">สถานะ</label>
    <div class="btn-group">
    	<button type="button" class="btn btn-sm btn-success" id="btn_active" onclick="enable()"><i class="fa fa-check"></i></button>
        <button type="button" class="btn btn-sm" id="btn_disactive" onclick="disable()"><i class="fa fa-times"></i></button>
    </div>
</div>

<?php if($add) : ?>
<div class="col-lg-1 col-md-2 col-sm-6">
	<label for="btn_save">&nbsp;</label>
	<button type="button" id="btn_save" onclick="save()" class="btn btn-success btn-xs btn-block"><i class="fa fa-save"></i>&nbsp; บันทึก</button>
</div>
<?php endif; ?>
<input type="hidden" id="active" name="active" value="1" />
<input type="hidden" id="id_employee" name="id_employee" value="" />
</form>
</div><!--/ Row -->
</div><!-- tab2 -->

</div><!-- tab content -->
<ul class="nav nav-tabs" id="myTab">
<li class="active"><a aria-expanded="false" data-toggle="tab" href="#tab1"><i class="fa fa-search"></i>&nbsp; ค้นหาพนักงาน</a></li>
<li class=""><a aria-expanded="true" data-toggle="tab" href="#tab2"><i class="fa fa-plus"></i>&nbsp; เพื่มผู้ใช้งานใหม่</a></li>
</ul>
<div class="row">
<div class="col-lg-12" style="height:1px !important">
<p class="pull-right" style="margin-top:-25px;">
จำนวนแถวต่อหน้า 
<input type="text" class="input-sm" id="set_rows" value="<?php echo $row; ?>" style="width:50px; text-align:center; margin-left:15px; margin-right:15px;" />
<button class="btn btn-success btn-mini" onclick="set_rows()">เปลี่ยน</button>
</p>
</div>
</div>
</div>

<hr style='border-color:#CCC; margin-top: 10px; margin-bottom:0px;' />

<div class='row'>
	<div class='col-xs-12'>
    <table class='table table-striped'>
    <thead>
    	<tr style='font-size:12px;'>
        	<th style='width:10%; text-align:center'>ไอดี</th>
            <th style='width:15%;'>ชื่อผู้ใช้งาน</th>
             <th style="width:30%;">ชื่อ - สกุล</th>
             <th style="width:15%;">เข้าระบบล่าสุด</th>
            <th style="width:10%; text-align:center">สถานะ</th>
            <th style="text-align:right">การกระทำ</th>
           </tr>
      </thead>
      <tbody id="rs">
<?php if($data != false) : ?>
        <?php foreach($data as $rs): ?>
        <?php 	$id = $rs->id_user; ?>
        		<tr id="row_<?php echo $id; ?>">
                    <td style="vertical-align:middle;" align="center"><?php echo $id; ?></td>
                    <td style="vertical-align:middle;"><span id="user_<?php echo $id; ?>"><?php echo $rs->user_name; ?></span></td>
                    <td style="vertical-align:middle;"><span id="name_<?php echo $id; ?>"><?php echo employee_name($rs->id_employee); ?></span></td>
                    <td style="vertical-align:middle;"><span id="login_<?php echo $id; ?>"><?php echo thaiDate($rs->last_login, true); ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="active_<?php echo $id; ?>"><?php echo isActived($rs->active); ?></span></td>  
                    <td align="right" style="vertical-align:middle;">
                    <?php if($edit) : ?> <button type="button" class="btn btn-warning btn-minier" onclick="edit_row(<?php echo $id; ?>)"><i class="fa fa-pencil"></i></button> <?php endif; ?>
                    <?php if($delete) : ?> <button type="button" class="btn btn-danger btn-minier" onclick="confirm_delete(<?php echo $id; ?>)"><i class="fa fa-trash"></i></button> <?php endif; ?>
                    </td>
                </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr id="nocontent"><td colspan="11" align="center" ><h1><?php echo label("empty_content"); ?></h1></td></tr>
    <?php endif; ?>
		</table>
        <?php echo $this->pagination->create_links(); ?>
</div><!-- End col-lg-12 -->
</div><!-- End row -->

<!------------------------------------------------- Modal  ----------------------------------------------------------->
<div class='modal fade' id='editer_modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' style='width:400px;'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4 class='modal-title' id='myModalLabel'>แก้ไขชื่อผู้ใช้งาน</h4>
		  </div>
		  <div class='modal-body' id="edit_modal">      
          	<div class="row">
                <form id="edit_form">
                <div class="col-lg-3 col-md-3 col-sm-4">
                    <label for="due_date">พนักงาน</label>
                    <input type="text" id="e_employee" name="employee" class="form-control input-sm" autofocus="autofocus" onkeydown="next_field($(this), $('#user_name'))" />
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="detail">User name</label>
                    <input type="text" id="e_user_name" name="user_name" class="form-control input-sm" onkeydown="next_field($(this), $('#password'))" />
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="reference">รหัสผ่าน</label>
                    <input type="password" id="e_password" name="password" class="form-control input-sm" autocomplete="off" onkeydown="next_field($(this), $('#profile'))" />
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label for="cash_out">โปรไฟล์</label>
                    <select name="profile" id="e_profile" class="form-control input-sm"><?php echo select_profile(); ?></select>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label style="display:block;">สถานะ</label>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-success" id="btn_e_active" onclick="enable()"><i class="fa fa-check"></i></button>
                        <button type="button" class="btn btn-sm" id="btn_e_disactive" onclick="disable()"><i class="fa fa-times"></i></button>
                    </div>
                </div> 
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <label for="btn_save">&nbsp;</label>
                    <button type="button" id="btn_e_save" onclick="update_row()" class="btn btn-success btn-xs btn-block"><i class="fa fa-save"></i>&nbsp; บันทึก</button>
                </div>
                <input type="hidden" id="e_active" name="active" value="1" />
                <input type="hidden" id="e_id_employee" name="id_employee" value="" />
                </form>
                </div><!--/ Row -->
          </div><!--- modal-body -->
		</div>
	</div>
</div>
<!------------------------------------------------- END Modal  ----------------------------------------------------------->
<script id="edit_field" type="tex/x-handlebars-template">
			<div class="row">
                <form id="edit_form">
                <div class="col-lg-12 col-md-3 col-sm-4">
                    <label for="due_date">พนักงาน</label>
                    <input type="text" id="e_employee" name="employee" class="form-control input-sm" value="{{ employee }}" autofocus="autofocus" onkeydown="next_field($(this), $('#e_user_name'))" />
                </div>
                
                <div class="col-lg-12 col-md-4 col-sm-6">
                    <label for="detail">User name</label>
                    <input type="text" id="e_user_name" name="user_name" class="form-control input-sm" value="{{ user_name }}" onkeydown="next_field($(this), $('#e_password'))" />
                </div>
                
                <div class="col-lg-12 col-md-4 col-sm-6">
                    <label for="reference">รหัสผ่าน</label>
                    <input type="password" id="e_password" name="password" class="form-control input-sm" autocomplete="off" onkeydown="next_field($(this), $('#e_profile'))" />
                </div>
                <div class="col-lg-12 col-md-3 col-sm-4">
                    <label for="cash_out">โปรไฟล์</label>
                    <select name="profile" id="e_profile" class="form-control input-sm">{{{ profile }}}</select>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-6">
                    <label style="display:block;">สถานะ</label>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm {{ enable }}" id="btn_e_active" onclick="edit_enable()"><i class="fa fa-check"></i></button>
                        <button type="button" class="btn btn-sm {{ disable }}" id="btn_e_disactive" onclick="edit_disable()"><i class="fa fa-times"></i></button>
                    </div>
                </div> 
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <label for="btn_save">&nbsp;</label>
                    <button type="button" id="btn_e_save" onclick="update_row()" class="btn btn-success btn-xs btn-block"><i class="fa fa-save"></i>&nbsp; บันทึก</button>
                </div>
                <input type="hidden" id="e_active" name="active" value="{{ active }}" />
                <input type="hidden" id="e_id_employee" name="id_employee" value="{{ id_employee }}" />
				<input type="hidden" id="id_user" name="id_user" value="{{ id }}" />
                </form>
                </div><!--/ Row -->
</script>
<script id="template" type="text/x-handlebars-template">
	<tr id="row_{{ id }}">
    	<td style="vertical-align:middle;" align="center">{{ id }}</td>
        <td style="vertical-align:middle;"><span id="user_{{ id }}">{{ user_name }}</span></td>
        <td style="vertical-align:middle;"><span id="name_{{ id }}">{{ employee }}</span></td>
        <td style="vertical-align:middle;"><span id="login_{{ id }}">{{ last_login }}</span></td>        
        <td style="vertical-align:middle;" align="center"><span id="active_{{ id }}">{{{ active }}}</td>                   
        <td align="right" style="vertical-align:middle;">
		<?php if($edit) : ?><button type="button" class="btn btn-warning btn-minier" onclick="edit_row({{id}})"><i class="fa fa-pencil"></i></button><?php endif; ?>
        <?php if($delete) : ?> <button type="button" class="btn btn-danger btn-minier" onclick="confirm_delete({{id}})"><i class="fa fa-trash"></i></button> <?php endif; ?>
		</td>
    </tr>
</script>

<script>
var empList = [<?php echo $emp_list; ?>];
$("#employee").autocomplete({
	source: empList,
	autoFocus: false,
	close: function(event, ui){
		var rs = $(this).val();
		var arr = rs.split(" | ");
		$("#id_employee").val(arr[0]);
		$(this).val(arr[1]);	
		console.log(arr[1]);
	}		
});

function auto_complete_on()
{
	$("#e_employee").autocomplete({
		source: empList,
		autoFocus: false,
		close: function(event, ui){
			var rs = $(this).val();
			var arr = rs.split(" | ");
			$("#e_id_employee").val(arr[0]);
			$(this).val(arr[1]);	
		}		
	});
}
function confirm_delete(id)
{
	swal({
		  title: "แน่ใจนะ?",
		  text: "คุณกำลังจะลบผู้ใช้งาน โปรดตรวจสอบให้แน่ใจว่าคุณต้องการลบจริง ๆ",
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
				delete_row(id);
		  } 
		});
}

function delete_row(id)
{
	$.ajax({
		url:"<?php echo $this->home; ?>/delete/"+id,
		type:"GET", cache:"false", success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "success")
			{
				$("#row_"+id).remove();
				swal({ title: "สำเร็จ", text: "ลบผู้ใช้งานเรียบร้อยแล้ว", type : "success", timer: 1000 });
			}
			else
			{
				swal("ไม่สำเร็จ", "ลบผู้ใช้งานไม่สำเร็จ", "error");
			}
		}
	});
}

function edit_row(id)
{
	$.ajax({
		url:"<?php echo $this->home; ?>/get_user/"+id,
		type:"GET", cache: "false", 
		success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs != "fail")
			{
				var source 		= $("#edit_field").html();
				var data 			= $.parseJSON(rs);
				var template 	= Handlebars.compile(source);
				var row 			= template(data);
				var output		= $("#edit_modal");
				render(source, data, output);
				auto_complete_on();
				$("#editer_modal").modal("show");				
			}else{
				swal("ไม่พบข้อมูล");	
			}
		}
	});
}

function update_user(id)
{
	$("#editer_modal").modal("hide");
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/update", type: "POST", cache:"false",
		data: $("#edit_form").serialize(),
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if(rs == "success")
			{
				$("#user_"+id).text($("#e_user_name").val());
				$("#name_"+id).text($("#e_employee").val());
				if($("#e_active").val() == 1 ){ var icon = "<i class='fa fa-check' style='color:green;'></i>"; }else{ var icon = "<i class='fa fa-times' style='color:red;'></i>"; }
				$("#active_"+id).html(icon);
				swal({ title: "สำเร็จ", text : "ปรับปรุงข้อมูลเรียบร้อยแล้ว", type : "success", timer : 1000 });
			}else if(rs == "missing_data"){
				swal({ title : "Error !!", text : "เกิดความผิดพลาดระหว่างการส่งข้อมูล", type: "error", showCancelButton: false}, function(){ $("#editer_modal").modal("show"); });
			}else{
				swal({ title : "Error !!", text : "ไม่สามารถแก้ไขรายการได้ กรุณาตรวจสอบความถูกต้องของข้อมูล", type: "error", showCancelButton: false}, function(){ $("#editer_modal").modal("show"); });	
			}
		}
	});
}


function update_row()
{
	var id_user			= $("#id_user").val();
	var user_name		= $("#e_user_name").val();
	var id_employee	= $("#e_id_employee").val();
	var employee 		= $("#e_employee").val();
	var profile			= $("#e_profile").val();
	if( user_name == ""){ swal("กรุณากำหนดชื่อผู้ใช้งาน");  return false; }
	if( employee == ""){ swal("กรุณากำหนดพนักงาน"); return false; }
	if( profile == "0" ){ swal("กรุณาเลือกโปรไฟล์"); return false; }
	$.ajax({
		url:"<?php echo $this->home; ?>/valid_data",
		type: "POST", cache: "false", data:{ "id_user" : id_user, "user_name" : user_name, "id_employee" : id_employee },
		success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "ok")
			{
				update_user(id_user);
			}
			else if( rs == "duplicate_user")
			{
				swal("ชื่อผู้ใช้งานซ้ำ");
			}
			else if( rs == "duplicate_employee" )
			{
				swal("พนักงานซ้ำ");
			}
			else
			{
				swal("ไม่สามารถแก้ไขข้อมูลพนักงานได้");
			}
		}
	});
}

function save()
{
	var employee			= $("#employee").val();
	var id_employee 		= $("#id_employee").val();
	var user_name		 	= $("#user_name").val();
	var password			= $("#password").val();
	var profile			= $("#profile").val();
	if(employee == ""){ swal({ title : "ข้อมูลไม่ครบ", text :"กรุณาระบุพนักงาน", type: "error" }); return false; }
	if(user_name == ""){ swal({ title : "ข้อมูลไม่ครบ", text :"กรุณากำหนดชื่อผู้ใช้งาน", type: "error" }); return false; }
	if(password == ""){ swal({ title : "ข้อมูลไม่ครบ", text :"กรุณากำหนดรหัสผ่าน", type: "error" }); return false; }
	if(profile == '0'){ swal({ title : "ข้อมูลไม่ครบ", text :"กรุณาเลือกโปรไฟล์", type: "error" }); return false; }
	if(id_employee == ""){ swal({ title : "ข้อมูลไม่ครบ", text :"ไม่พบ ID พนักงาน กรุณาเลือกพนักงานใหม่อีกครั้ง", type: "error" }); return false; }
	$("#btn_save").attr("disabled", "disabled");
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/add_user",
		type: "POST", cache: "false", data: $("#add_form").serialize(),
		success: function(rs)
		{
			load_out();
			$("#btn_save").attr("disabled", "disabled");
			var rs = $.trim(rs);
			if(rs == "fail")
			{
				swal("Error !!", "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ภายหลัง", "error");
				$("#btn_save").removeAttr("disabled");
			}else if( rs == "duplicate_user"){
				swal("ชื่อผู้ใชงานซ้ำ !!", "ไม่สามารถใช้ชื่อผู้ใช้งานที่ซ้ำกับคนอื่นได้", "error");	
				$("#btn_save").removeAttr("disabled");
			}else if( rs == "duplicate_employee"){
				swal("พนักงานซ้ำ !!", "ชื่อพนักงานมีอยู่ในระบบแล้ว", "error");	
				$("#btn_save").removeAttr("disabled");
			}else{
				var source 		= $("#template").html();
				var data 			= $.parseJSON(rs);
				var template 	= Handlebars.compile(source);
				var row 			= template(data);
				$("#rs").prepend(row);
				clear_field();
				$("#btn_save").removeAttr("disabled");
				swal({ title: "สำเร็จ", text: "เพิ่มผู้ใช้งานเรียบร้อยแล้ว", type: "success", timer: 1000 });
			}
		}
	});
}

function clear_field()
{
	$("#employee").val("");
	$("#id_employee").val("");
	$("#user_name").val("");
	$("#password").val("");
	$("#profile").val(0);
	enable();
	$("#employee").focus();	
}

function disable()
{
	$("#btn_active").removeClass("btn-success");
	$("#btn_disactive").addClass("btn-danger");
	$("#active").val(0);
}

function enable()
{
	$("#btn_disactive").removeClass("btn-danger");
	$("#btn_active").addClass("btn-success");
	$("#active").val(1);
}
function edit_disable()
{
	$("#btn_e_active").removeClass("btn-success");
	$("#btn_e_disactive").addClass("btn-danger");
	$("#e_active").val(0);
}

function edit_enable()
{
	$("#btn_e_disactive").removeClass("btn-danger");
	$("#btn_e_active").addClass("btn-success");
	$("#e_active").val(1);
}

function next_field(em, el)
{
	em.keyup(function(e){ if(e.keyCode == 13){ el.focus(); } }); 
}

function get_search()
{
	var txt = $("#search_text").val();
	if(txt != "")
	{
		$("#search_form").submit();
	}
}

$("#set_rows").keyup(function(e){
	if(e.keyCode == 13 ){ set_rows(); }
});

function set_rows()
{
	load_in();
	var rows =$("#set_rows").val();
	if(rows == "")
	{
		load_out();
		swal("จำนวนแถวต้องเป็นตัวเลขเท่านั้น");
		return false;
	}else{
		$.ajax({
			url:"<?php echo base_url(); ?>admin/tool/set_rows",type:"POST",cache:false,
			data:{ "rows" : rows },
			success: function(rs)
			{
				var rs = $.trim(rs);
				if(rs == "success")
				{
					load_out();
					window.location.reload();
				}else{
					load_out();
					swal("ไม่สามารถเปลี่ยนจำนวนแถวต่อหน้าได้ กรุณาลองใหม่อีกครั้งภายหลัง");
				}
			}
		});
	}
}

function set_focus(name)
{
	$("#"+name).focus();
}
</script>

<?php endif; ?>