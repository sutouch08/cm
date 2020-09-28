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
<div class="col-lg-3 col-md-3 col-sm-6">
	<label >ค้นหาสถานที่</label>
	<input type="text" id="search_text" name="search_text" placeholder="ค้นหา ชื่อสถานที่ หรือ รหัสสถานที่" class="form-control input-sm" value="<?php echo $search_text; ?>" autofocus />
</div>
<input type="text" style="display:none" />
</form>
<div class="col-lg-1 col-md-2 col-sm-4">
	<label style="display:block;">&nbsp;</label>
	<button type="button" class="btn btn-info btn-xs btn-block" id="btn_search" onclick="get_search()" ><i class="fa fa-search"></i>&nbsp; ค้นหา</button>
</div>
<div class="col-lg-1 col-md-2 col-sm-4">
	<label style="display:block;">&nbsp;</label>
	<a href="<?php echo $this->home; ?>/clear_filter/"><button type="button" class="btn btn-warning btn-xs btn-block" id="btn_reset" ><i class="fa fa-refresh"></i>&nbsp; Reset</button></a>
</div>
<input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>" />
<input type="hidden" name="delete" id="delete" value="<?php echo $delete; ?>" />
</div><!--/ Row -->
</div><!--/ tab1 -->

<div id="tab2" class="tab-pane">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-6">
	<label >รหัสสถานที่</label>
	<input type="text" id="shop_code" name="shop_name" class="form-control input-sm" placeholder="กำหนดรหัสถสานที่" />
</div>
<div class="col-lg-4 col-md-4 col-sm-6">
	<label>ชื่อสถานที่</label>
	<input type="text" id="shop_name" name="shop_name" class="form-control input-sm" placeholder="กำหนดชื่อสถานที่ (ต้องการ)" />
</div>

<div class="col-sm-3">
	<label style="display:block; visibility:hidden;">ระบุจำนวนได้</label>
	<label>
		<input type="checkbox" name="allow_input_qty" id="allow_input_qty" class="ace" value="Y" >
		<span class="lbl"> ระบุจำนวนได้</span>
	</label>
</div>


<?php if($add) : ?>
<div class="col-lg-2 col-md-2 col-sm-6">
	<label for="btn_save">&nbsp;</label>
	<button type="button" id="btn_save" onclick="add_shop()" class="btn btn-success btn-xs btn-block"><i class="fa fa-plus"></i>&nbsp; เพิ่มใหม่</button>
</div>
<?php endif; ?>

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
<?php echo rows_box(); ?>
</p>
</div>
</div>
</div>

<hr style='border-color:#CCC; margin-top: 10px; margin-bottom:0px;' />
<div class="row">
	<div class="col-lg-12">
    	<table class="table table-striped">
        	<thead>
                <th style="width:25%;">รหัสสถานที่</th>
                <th style="width:40%;">ชื่อสถานที่</th>
								<th style="width:10%;">ใส่จำนวนได้</th>
                <th></th>
            </thead>
            <tbody id="rs">
		<?php if( isset($data) && $data != false ) : ?>
        <?php	foreach($data as $rs) : ?>
        	<tr id="<?php echo $rs->shop_code; ?>">
                <td><?php echo $rs->shop_code; ?></td>
                <td><?php echo $rs->shop_name; ?></td>
								<td class="text-center"><?php echo $rs->allow_input_qty; ?></td>
                <td align="right">
                	<button type="button" class="btn btn-warning btn-xs" onclick="edit_row('<?php echo $rs->shop_code; ?>')"><i class="fa fa-pencil"></i>&nbsp; แก้ไข</button>
					<button type="button" class="btn btn-danger btn-xs" onclick="delete_row('<?php echo $rs->shop_code; ?>')"><i class="fa fa-trash"></i>&nbsp; ลบ</button>
                </td>
            </tr>
        <?php 	endforeach; ?>
        <?php endif; ?>
            </tbody>
        </table>
    </div>
</div><!-- Row -->

<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
	<div class="modal-dialog" style="width:500px;">
    	<div class="modal-content">
        	<div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">แก้ไขสถานที่</h4>
            </div>
            <div class="modal-body" id="edit_body">
                <div class="row">
                	<div class="col-lg-12">
                    	<label>รหัสสถานที่</label>
                        <input type="text" class="form-control input-sm" id="edit_code" />
                    </div>
                    <div class="col-lg-12">&nbsp;</div>
                    <div class="col-lg-12">
                    	<label>ชื่อสถานที่</label>
                        <input type="text" class="form-control input-sm" id="edit_name" />
                    </div>
										<div class="col-sm-12">
											<label style="display:block; visibility:hidden;">ระบุจำนวนได้</label>
											<label>
												<input type="checkbox" id="edit_allow_input_qty" class="ace" value="Y" >
												<span class="lbl"> ระบุจำนวนได้</span>
											</label>
										</div>


                    <div class="col-lg-12">&nbsp;<input type="hidden" name="original_code" id="original_code" value="" /></div>
                    <div class="col-lg-12">
                    	<button type="button" class="btn btn-success btn-sm btn-block" id="btn_edit" onclick="save_edit()"><i class="fa fa-save"></i>&nbsp; บันทึก</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script id="add_template" type="text/x-handlebars-template">
<tr id="{{ shop_code }}">
<td>{{ shop_code }}</td>
<td>{{ shop_name }}</td>
<td class="text-center">{{aiq}}</td>
<td align="right">
{{#if can_edit}}
	<button type="button" class="btn btn-warning btn-xs" onclick="edit_row('{{ shop_code }}')"><i class="fa fa-pencil"></i>&nbsp; แก้ไข</button>
{{/if}}
{{#if can_delete}}
<button type="button" class="btn btn-danger btn-xs" onclick="delete_row('{{ shop_code }}')"><i class="fa fa-trash"></i>&nbsp; ลบ</button>
{{/if}}
</td>
</tr>
</script>
<script>

function add_shop()
{
	var code = $("#shop_code").val();
	var name = $("#shop_name").val();
	var aiq = $('#allow_input_qty').is(':checked') === true ? 'Y' : 'N';
	if( code == "" ){ swal("กรุณากำหนดรหัสสถานที่"); return false; }
	if( name == "" ){ swal("กรุณากำหนดชื่อสถานที่"); return false; }
	$.ajax({
		url:"<?php echo $this->home; ?>/valid_shop_code",
		type:"POST",
		cache:"false",
		data:{ "shop_code" : code },
		success: function(rs)
		{
			var rs = $.trim(rs);
			var rs = parseInt(rs);
			if(rs > 0 )
			{
				swal("รหัสสถานที่ซ้ำ");
			}
			else
			{
				//load_in();
				var data = [
					{"name": "shop_code", "value" : code},
					{"name" : "shop_name", "value" : name},
					{"name" : "allow_input_qty", "value" : aiq}
				];

				$.ajax({
					url:"<?php echo $this->home; ?>/add_shop",
					type: "POST",
					cache: false,
					data: data,
					success: function(rd)
					{
						load_out();
						var rd = $.trim(rd);
						if(rd == "success")
						{
							add_row(code, name, aiq);
							swal({ title: "สำเร็จ", text: "เพิ่มสถานที่เรียบร้อยแล้ว", timer: 1000, type: "success" });
						}
						else
						{
							swal("ไม่สำเร็จ", "เพิ่มสถานที่ใหม่ไม่สำเร็จ ลองใหม่อีกครั้งภายหลัง", "error");
						}
					}
				});
			}
		}
	});
}

function delete_row(code)
{
	swal({
		title : "คุณแน่ใจ ?",
		text: "คุณต้องการลบรายการนี้ใช่หรือไม่ โปรดจำไว้ว่าเมื่อลบแล้วไม่สามารถกู้คืนได้",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: 'red',
		confirmButtonText: "ใช่ ฉันต้องการลบ",
		cancelButtonText: "ยกเลิก",
		closeOnConfirm: false
	}, function(isConfirm){
		if( isConfirm )
		{
			load_in();
			$.ajax({
				url:"<?php echo $this->home; ?>/delete_shop"	,
				type:"POST", cache: false, data:{ "code" : code },
				success: function(rs)
				{
					load_out();
					var rs = $.trim(rs);
					if(rs == "success")
					{
						remove_row(code);
						swal({ title: "สำเร็จ", text: "ลบสถานที่เรียบร้อยแล้ว", timer: 1000, type: "success" });
					}
					else
					{
						swal({ title: "ไม่สำเร็จ", text: "ไม่สามารถลบสถานที่ได้ กรุณาลองใหม่อีกครั้ง", type: "error" });
					}
				}
			});
		}
	});
}

function add_row(shop_code, shop_name, aiq)
{
	var source 	= $("#add_template").html();
	var data 		= { "shop_code" : shop_code, "shop_name" : shop_name, "aiq" : aiq };
	var ed 		= $("#edit").val();
	var de		= $("#delete").val();
	if( ed == 1){ data.can_edit = "1"; }
	if( de == 1 ){ data.can_delete = "1"; }
	var output 	= $("#rs");
	render_prepend(source, data, output);
}

function remove_row(shop_code)
{
	$("#"+shop_code).remove();
}


function edit_row(code)
{
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/get_data",
		type:"POST",
		cache:"false",
		data:{ "code" : code },
		success: function(rs)
		{
			load_out();
			var data = $.parseJSON(rs);
			$("#edit_code").val(data.shop_code);
			$("#edit_name").val(data.shop_name);
			if(data.aiq == 'Y'){
				$('#edit_allow_input_qty').prop('checked', true);
			}
			else if(data.aiq == 'N'){
				$('#edit_allow_input_qty').prop('checked', false);
			}

			$("#original_code").val(data.shop_code);
			$("#edit_modal").modal("show");
		}
	});
}

function save_edit()
{
	var original_code 	= $("#original_code").val();
	var code				= $("#edit_code").val();
	var name				= $("#edit_name").val();
	var aiq = $('#edit_allow_input_qty').is(':checked') === true ? 'Y' : 'N';
	if( code == ""){ swal("กรุณากำหนดรหัสสถานที่"); return false; }
	if( name == ""){ swal("กรุณากำหนดชื่อสถานที่"); return false; }
	load_in();
	$.ajax({
		url:"<?php echo $this->home; ?>/valid_edit_code"	,
		type:"POST",
		cache:"false",
		data:{
			"code" : code,
			"original_code" : original_code
		 },
		success: function(rs)
		{
			var rs = $.trim(rs);
			if( rs > 0 )
			{
				load_out();
				swal("รหัสซ้ำ", "รหัสนี้ถูกใช้ไปแล้ว", "error");
			}
			else
			{
				$.ajax({
					url:"<?php echo $this->home; ?>/update",
					type:"POST",
					cache: false, data:{
						"shop_code" : code,
						"shop_name" : name,
						"allow_input_qty" : aiq,
						"original_code" : original_code
					 },
					success: function(rs)
					{
						load_out();
						var rs = $.trim(rs);
						if(rs == "success")
						{
							remove_row(original_code);
							add_row(code, name, aiq);
							$("#edit_modal").modal("hide");
							$("#edit_code").val('');
							$("#edit_name").val('');
							$("#original_code").val('');
							swal({ title: "สำเร็จ", text: "แก้ไขสถานที่เรียบร้อยแล้ว", timer: 1000, type: "success" });
						}
						else
						{
							swal({ title: "ไม่สำเร็จ", text: "ไม่สามารถแก้ไขข้อมูลได้", type: "error" });
						}
					}
				});
			}
		}
	});
}

function get_search()
{
	var txt = $("#search_text").val();
	if( txt != "")
	{
		$("#search_form").submit();
	}
}
</script>

<?php endif; ?>
