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
	<div class='col-lg-6'>
    	<h3 style='margin-bottom:0px;'><i class='fa fa-tint'></i>&nbsp; <?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-6">
		<p class="pull-right">
			<button type="button" class="btn btn-sm btn-primary" onclick="sync_item()"><i class="fa fa-refresh"></i> Sync</button>
		</button>
	</div>
</div><!-- End Row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:20px;' />
<div class="tabbable tabs-below">
<div class="tab-content">
<div id="tab1" class="tab-pane active">
<div class="row">
<div class="col-lg-4 col-md-4 col-sm-6">
	<form id="search_form" action="<?php echo $this->home; ?>" method="post">
	<label for="search_detail">ค้นหาสินค้า</label>
	<input type="text" id="search_text" name="search_text" placeholder="ระบุรายการค้นหา" class="form-control input-sm" value="<?php echo $search_text; ?>" />
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

<div class="col-lg-2 col-md-4 col-sm-6">
    <label for="due_date">บาร์โค้ดสินค้า</label>
	<input type="text" id="barcode" name="barcode" class="form-control input-sm" autofocus="autofocus" />
</div>

<div class="col-lg-2 col-md-4 col-sm-6">
	<label for="detail">รหัสสินค้า</label>
	<input type="text" id="item_code" name="item_code" class="form-control input-sm" />
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
	<label for="reference">ชื่อสินค้า</label>
	<input type="text" id="item_name" name="item_name" class="form-control input-sm" />
</div>
<div class="col-lg-3 col-md-4 col-sm-6">
	<label for="cash_in">รุ่นสินค้า</label>
	<input type="text" id="style" name="style" class="form-control input-sm" />
</div>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="cash_in">กลุ่มสินค้า</label>
	<select name="item_group" id="item_group" class="form-control input-sm">
    	<option value="">เลือกกลุ่มสินค้า</option>
    <?php echo selectItemGroup(); ?>
    </select>
</div>
<div class="col-lg-1 col-md-3 col-sm-4">
	<label for="cash_out">ราคาทุน</label>
	<input type="text" id="cost" name="cost" class="form-control input-sm" />
</div>

<div class="col-lg-1 col-md-3 col-sm-4">
	<label for="move_type">ราคาขาย</label>
	<input type="text" id="price" name="price" class="form-control input-sm" />
</div>
<div class="col-lg-2 col-md-4 col-sm-6">
	<label style="display:block;">สถานะ</label>
    <div class="btn-group">
    	<button type="button" class="btn btn-sm btn-success" id="btn_active" onclick="enable()"><i class="fa fa-check"></i></button>
        <button type="button" class="btn btn-sm" id="btn_disactive" onclick="disable()"><i class="fa fa-times"></i></button>
    </div>
</div>

<?php if($add) : ?>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="btn_save">&nbsp;</label>
	<button type="button" id="btn_save" onclick="save()" class="btn btn-success btn-sm btn-block"><i class="fa fa-save"></i>&nbsp; บันทึก</button>
</div>
<?php endif; ?>
<input type="hidden" id="active" value="1" />

</div><!--/ Row -->
</div><!-- tab2 -->

<div id="tab3" class="tab-pane">
<form id="myform" action="<?php echo $this->home; ?>/import_items" method="post" enctype="multipart/form-data">
<div class="row">
<div class="form-group">
<div class="col-lg-4 col-md-4 col-sm-6">
	<label for="search_detail">ไฟล์นำเข้ารายการสินค้า</label>
	<!-- #section:custom/file-input -->
    	<input id="user_file" type="file" name="user_file" class="input-sm">

</div>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="btn_search" style="display:block;">&nbsp;</label>
	<button type="button" class="btn btn-success btn-xs btn-block" id="btn_upload" onclick="upload()" ><i class="fa fa-upload"></i>&nbsp; นำเข้า</button>
</div>
</div>
</div><!--/ Row -->
</form>
</div><!--/ tab3 -->
</div><!-- tab content -->
<ul class="nav nav-tabs" id="myTab">
<li class="active"><a aria-expanded="false" data-toggle="tab" href="#tab1"><i class="fa fa-search"></i>&nbsp; ค้นหาสินค้า</a></li>
<li class=""><a aria-expanded="true" data-toggle="tab" href="#tab2"><i class="fa fa-plus"></i>&nbsp; เพิ่มสินค้าใหม่</a></li>
<li class=""><a aria-expanded="true" data-toggle="tab" href="#tab3"><i class="fa fa-upload"></i>&nbsp; เพิ่มสินค้าจากไฟล์นำเข้า</a></li>
</ul>
<div class="row">
<div class="col-lg-12" style="height:1px !important">
<p class="pull-right" style="margin-top:-25px;">
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
    	<tr style='font-size:12px;'>
        	<th style='width:5%; text-align:center'>ไอดี</th>
            <th style='width:10%;'>บาร์โค้ด</th>
             <th style="width:10%;">รหัสสินค้า</th>
             <th style="width:20%;">ชื่อสินค้า</th>
            <th style="width:10%; text-align:center">รุ่น</th>
            <th style="width:5%; text-align:center">ทุน</th>
            <th style="width:5%; text-align:center">ราคา</th>
            <th style="width:10%; text-align:center">กลุ่ม</th>
            <th style="width:3%; text-align:center">สถานะ</th>
            <th style="width:8%; text-align:center">ปรับปรุงล่าสุด</th>
            <th style="text-align:right">การกระทำ</th>
           </tr>
      </thead>
      <tbody id="rs">
<?php if($data != false) : ?>
        <?php foreach($data as $rs): ?>
        <?php 	$id = $rs->id_item; ?>
        		<tr id="row_<?php echo $id; ?>" style="font-size:10px;">
                    <td style="vertical-align:middle;" align="center"><?php echo $id; ?></td>
                    <td style="vertical-align:middle;"><span id="barcode_<?php echo $id; ?>"><?php echo $rs->barcode; ?></span></td>
                    <td style="vertical-align:middle;"><span id="item_code_<?php echo $id; ?>"><?php echo $rs->item_code; ?></span></td>
                    <td style="vertical-align:middle;"><span id="item_name_<?php echo $id; ?>"><?php echo $rs->item_name; ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="style_<?php echo $id; ?>"><?php echo $rs->style; ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="cost_<?php echo $id; ?>"><?php echo number_format($rs->cost,2); ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="price_<?php echo $id; ?>"><?php echo number_format($rs->price,2); ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="type_<?php echo $id; ?>"><?php echo item_group_name($rs->type); ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="active_<?php echo $id; ?>"><?php echo isActived($rs->active); ?></span></td>
                    <td style="vertical-align:middle;" align="center"><span id="date_upd_<?php echo $id; ?>"><?php echo thaiDate($rs->date_upd, false); ?></span></td>
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
	<div class='modal-dialog' style='width:500px;'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4 class='modal-title' id='myModalLabel'>แก้ไขสินค้า</h4>
		  </div>
		  <div class='modal-body' id="edit_modal">
          	<div class="row">
            <div class="col-lg-12">
                <label >บาร์โค้ดสินค้า</label>
                <input type="text" id="edit_barcode"  class="form-control input-sm" value="" autofocus="autofocus" />
            </div>

            <div class="col-lg-12">
                <label >รหัสสินค้า</label>
                <input type="text" id="edit_item_code" class="form-control input-sm" value="" />
            </div>

            <div class="col-lg-12">
                <label >ชื่อสินค้า</label>
                <input type="text" id="edit_item_name"class="form-control input-sm" value="" />
            </div>
            <div class="col-lg-12">
                <label >รุ่นสินค้า</label>
                <input type="text" id="edit_style" class="form-control input-sm" value="" />
            </div>
            <div class="col-lg-12">
                <label >กลุ่มสินค้า</label>
                <select id="edit_item_group" class="form-control input-sm">
                	<option value="0">เลือกกลุ่มสินค้า</option>
                <?php echo selectItemGroup(); ?>
                </select>
            </div>
            <div class="col-lg-12">
                <label for="cash_out">ราคาทุน</label>
                <input type="text" id="edit_cost" class="form-control input-sm" value="" />
            </div>

            <div class="col-lg-12">
                <label for="move_type">ราคาขาย</label>
                <input type="text" id="edit_price" class="form-control input-sm" value="" />
            </div>
            <div class="col-lg-12">
                <label style="display:block;">สถานะ</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm" id="btn_edit_active" onclick="edit_enable()"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm" id="btn_edit_disactive" onclick="edit_disable()"><i class="fa fa-times"></i></button>
                    <input type="hidden" id="edit_active" value="" />
                </div>
            </div>
            <div class="col-lg-12">
            	<label style="display:block; visibility:hidden;">update</label>
            	<button type="button" class="btn btn-success btn-sm btn-block" onclick="update_row()"><i class="fa fa-save"></i> บันทึก</button>
                <input type="hidden" id="id_item" value="" />
            </div>
          </div><!--- modal-body -->
		</div>
	</div>
</div>
<!------------------------------------------------- END Modal  ----------------------------------------------------------->
<script id="edit_field" type="tex/x-handlebars-template">
<div class="row">
            <div class="col-lg-12">
                <label for="due_date">บาร์โค้ดสินค้า</label>
                <input type="text" id="edit_barcode"  class="form-control input-sm" value="{{ barcode }}" onkeydown="next_field($(this), $('#edit_item_code'))" autofocus="autofocus" />
            </div>

            <div class="col-lg-12">
                <label for="detail">รหัสสินค้า</label>
                <input type="text" id="edit_item_code" class="form-control input-sm" value="{{ item_code }}" onkeydown="next_field($(this), $('#edit_item_name'))"  />
            </div>

            <div class="col-lg-12">
                <label for="reference">ชื่อสินค้า</label>
                <input type="text" id="edit_item_name"class="form-control input-sm" value="{{ item_name }}" onkeydown="next_field($(this), $('#edit_style'))"  />
            </div>
            <div class="col-lg-12">
                <label for="cash_in">รุ่นสินค้า</label>
                <input type="text" id="edit_style" class="form-control input-sm" value="{{ style }}" onkeydown="next_field($(this), $('#edit_cost'))"  />
            </div>
			<div class="col-lg-12">
                <label >กลุ่มสินค้า</label>
                <select id="edit_item_group" class="form-control input-sm">
                	<option value="0">เลือกกลุ่มสินค้า</option>
                	{{{ item_group }}}
                </select>
            </div>
            <div class="col-lg-12">
                <label for="cash_out">ราคาทุน</label>
                <input type="text" id="edit_cost" class="form-control input-sm" value="{{ cost }}" onkeydown="next_field($(this), $('#edit_price'))"  />
            </div>

            <div class="col-lg-12">
                <label for="move_type">ราคาขาย</label>
                <input type="text" id="edit_price" class="form-control input-sm" value="{{ price }}" onkeydown="next_field($(this), $('#btn_edit_save'))"  />
            </div>
            <div class="col-lg-12">
                <label style="display:block;">สถานะ</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm {{ enable }}" id="btn_edit_active" onclick="edit_enable()"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm {{ disable }}" id="btn_edit_disactive" onclick="edit_disable()"><i class="fa fa-times"></i></button>
                    <input type="hidden" id="edit_active" value="{{ active }}" />
                </div>
            </div>
            <div class="col-lg-12">
            	<label style="display:block; visibility:hidden;">update</label>
            	<button type="button" id="btn_edit_save" class="btn btn-success btn-sm btn-block" onclick="update_row()"><i class="fa fa-save"></i> บันทึก</button>
                <input type="hidden" id="id_item" value="{{ id_item }}" />
            </div>
</script>
<script id="template" type="text/x-handlebars-template">
	<tr id="row_{{ id }}">
    	<td style="vertical-align:middle;" align="center">{{ id }}</td>
        <td style="vertical-align:middle;"><span id="barcode_{{ id }}">{{ barcode }}</span></td>
        <td style="vertical-align:middle;"><span id="item_code_{{ id }}">{{ item_code }}</span></td>
        <td style="vertical-align:middle;"><span id="item_name_{{ id }}">{{ item_name }}</span></td>
        <td style="vertical-align:middle;" align="center"><span id="style_{{ id }}">{{ style }}</span></td>
		<td style="vertical-align:middle;" align="center"><span id="type_{{ id }}">{{ group_name }}</span></td>
        <td style="vertical-align:middle;" align="center"><span id="cost_{{ id }}">{{ cost }}</span></td>
        <td style="vertical-align:middle;" align="center"><span id="price_{{ id }}">{{ price }}</span></td>
        <td style="vertical-align:middle;" align="center"><span id="active_{{ id }}">{{{ active }}}</td>
        <td style="vertical-align:middle;" align="center"><span id="date_upd_{{ id }}">{{ date_upd }}</span></td>
        <td align="right" style="vertical-align:middle;">
		<?php if($edit) : ?><button type="button" class="btn btn-warning btn-minier" onclick="edit_row({{id}})"><i class="fa fa-pencil"></i></button><?php endif; ?>
        <?php if($delete) : ?> <button type="button" class="btn btn-danger btn-minier" onclick="confirm_delete({{id}})"><i class="fa fa-trash"></i></button> <?php endif; ?>
		</td>
    </tr>
</script>

<script>
var BASE_URL = "<?php echo base_url(); ?>";

function upload()
{
	var file = $("#user_file").val();
	if(file == "")
	{
		swal("กรุณาเลือกไฟล์");
	}
	else
	{
		load_in();
		$("#myform").submit();
	}
}


function confirm_delete(id)
{
	swal({
		  title: "แน่ใจนะ?",
		  text: "คุณกำลังจะลบสินค้า โปรดตรวจสอบให้แน่ใจว่าคุณต้องการลบจริง ๆ",
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
		url:"<?php echo $this->home; ?>/delete_item/"+id,
		type:"GET", cache:"false", success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "success")
			{
				$("#row_"+id).remove();
				swal({ title: "สำเร็จ", text: "ลบสินค้าเรียบร้อยแล้ว", type : "success", timer: 1000 });
			}
			else
			{
				swal("ไม่สำเร็จ", "ลบสินค้าไม่สำเร็จ", "error");
			}
		}
	});
}

function edit_row(id)
{
	$.ajax({
		url:"<?php echo $this->home; ?>/get_item/"+id,
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

				$("#editer_modal").modal("show");
				$('#editer_modal').on('shown.bs.modal', function (e) { $("#edit_barcode").focus(); });

			}else{
				swal("ไม่พบข้อมูล");
			}
		}
	});
}

function update_item(id, barcode, item_code, item_name, style, type, cost, price, active)
{
	$("#editer_modal").modal("hide");
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/update_item", type: "POST", cache:"false",
		data: { "id" : id, "barcode" : barcode, "item_code" : item_code, "item_name" : item_name, "style" : style, "type" : type, "cost" : cost, "price" : price, "active" : active },
		success: function(rs)
		{
			load_out();
			var rs = $.trim(rs);
			if(rs == "success")
			{
				$("#barcode_"+id).text(barcode);
				$("#item_code_"+id).text(item_code);
				$("#item_name_"+id).text(item_name);
				$("#style_"+id).text(style);
				$("#type_"+id).text($("#edit_item_group option:selected").text());
				$("#cost_"+id).text(cost);
				$("#price_"+id).text(price);
				if(active == 1 ){ var icon = "<i class='fa fa-check' style='color:green;'></i>"; }else{ var icon = "<i class='fa fa-times' style='color:red;'></i>"; }
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
	var id				= $("#id_item").val();
	var barcode	= $.trim($("#edit_barcode").val());
	var item_code 	= $.trim($("#edit_item_code").val());
	var item_name	= $.trim($("#edit_item_name").val());
	var style 		= $.trim($("#edit_style").val());
	var type			= $("#edit_item_group").val();
	var cost			= $.trim($("#edit_cost").val());
	var price			= $.trim($("#edit_price").val());
	var active		= $("#edit_active").val();
	if(id == ""){ swal("ไม่พบ ID สินค้า"); return false; }
	if(barcode == ""){ swal("กรุณากำหนดบาร์โค้ด"); return false; }
	if(item_code == ""){ swal("กรุณากำหนดรหัสสินค้า"); return false; }
	if(item_name == ""){ swal("กรุณากำหนดชื่อสินค้า"); return false; }
	if(style == ""){ swal("กรุณากำหนดรุ่น"); return false; }
	if(isNaN(parseFloat(cost))){ swal("ราคาทุนไม่ถูกต้อง"); return false; }
	if(isNaN(parseFloat(price))){ swal("ราคาขายไม่ถูกต้อง"); return false; }
	$.ajax({
		url:"<?php echo $this->home; ?>/valid_barcode/"+barcode+"/"+id,
		type: "GET", cache: "false",
		success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "ok")
			{
				update_item(id, barcode, item_code, item_name, style, type, cost, price, active);
			}
			else
			{
				swal("บาร็โค้ดซ้ำ");
			}
		}
	});
}

function save()
{
	var barcode 	= $("#barcode").val();
	var item_code 	= $("#item_code").val();
	var item_name 	= $("#item_name").val();
	var style			= $("#style").val();
	var type			= $("#item_group").val();
	var cost			= $("#cost").val();
	var price 		= $("#price").val();
	var active		= $("#active").val();
	if( barcode == ""){ swal("กรุณากำหนดบาร์โค้ด"); return false; }
	if( item_code == ""){ swal("กรุณากำหนดรหัสสินค้า"); return false; }
	if( item_name == ""){ swal("กรุณากำหนดชื่อสินค้า"); return false; }
	if( style == ""){ swal("กรุณากำหนดรุ่น"); return false; }
	if(isNaN(parseFloat(cost))){ swal("ราคาทุนไม่ถูกต้อง"); return false; }
	if(isNaN(parseFloat(price))){ swal("ราคาขายไม่ถูกต้อง"); return false; }
	$("#btn_save").attr("disabled", "disabled");
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/add_item",
		type: "POST", cache: "false", data: { "barcode" : barcode, "item_code" : item_code, "item_name" : item_name, "style" : style, "type" : type, "cost" : cost, "price" : price, "active" : active },
		success: function(rs)
		{
			load_out();
			$("#btn_save").attr("disabled", "disabled");
			var rs = $.trim(rs);
			if(rs == "fail")
			{
				swal("Error !!", "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ภายหลัง", "error");
				$("#btn_save").removeAttr("disabled");
			}else if( rs == "duplicate_barcode"){
				swal("บาร์โค้ดซ้ำ !!", "ไม่อนุญาติให้บาร์โค้ดซ้ำกัน", "error");
				$("#btn_save").removeAttr("disabled");
			}else{
				var source 		= $("#template").html();
				var data 			= $.parseJSON(rs);
				var template 	= Handlebars.compile(source);
				var row 			= template(data);
				$("#rs").prepend(row);
				clear_field();
				$("#btn_save").removeAttr("disabled");
			}
		}
	});
}

function clear_field()
{
	$("#barcode").val("");
	$("#item_code").val("");
	$("#item_name").val("");
	$("#style").val(0);
	$("#item_group").val("");
	$("#cost").val("");
	$("#price").val("");
	enable();
	$("#barcode").focus();
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
	$("#btn_edit_active").removeClass("btn-success");
	$("#btn_edit_disactive").addClass("btn-danger");
	$("#edit_active").val(0);
}

function edit_enable()
{
	$("#btn_edit_disactive").removeClass("btn-danger");
	$("#btn_edit_active").addClass("btn-success");
	$("#edit_active").val(1);
}
$("#barcode").keyup(function(e) {
    if(e.keyCode == 13){ $("#item_code").focus(); }
});
$("#item_code").keyup(function(e) {
    if(e.keyCode == 13){ $("#item_name").focus(); }
});
$("#item_name").keyup(function(e) {
    if(e.keyCode == 13){ $("#style").focus(); }
});
$("#style").keyup(function(e) {
    if(e.keyCode == 13){ $("#cost").focus(); }
});
$("#cost").keyup(function(e) {
    if(e.keyCode == 13){ $("#price").focus(); }
});
$("#price").keyup(function(e) {
    if(e.keyCode == 13){ $("#btn_save").focus(); }
});

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


$("#user_file").ace_file_input({
	btn_choose : 'เลือกไฟล์',
	btn_change: 'เปลี่ยน',
	droppable: true,
	thumbnail: 'large',
	maxSize: 5000000,//bytes
	allowExt: ["csv|xls|xlsx"]
});

$("#user_file").on('file.error.ace', function(ev, info) {
	if(info.error_count['ext'] || info.error_count['mime']) alert('กรุณาเลือกไฟล์นามสกุล .csv เท่านั้น');
	if(info.error_count['size']) alert('ขนาดไฟล์สูงสุดไม่เกิน 5 MB');
});


function sync_item()
{
	//--- properties for print
	var prop 			= "width=800, height=900, left="+center+", scrollbars=yes";
	var center    = ($(document).width() - 800)/2;
	var target  = BASE_URL + 'sync_items';
  window.open(target, '_blank', prop);
}
</script>

<?php endif; ?>
