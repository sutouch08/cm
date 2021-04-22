<?php /***********************************   ระบบตรวจสอบสิทธิ์  ******************************************/ ?>
<?php $access 	= valid_access($id_menu);  ?>
<?php $view		= $access['view']; ?>
<?php $add 		= $access['add']; ?>
<?php $edit 		= $access['edit']; ?>
<?php $delete		= $access['delete']; ?>
<?php $allow_input_qty = 0; ?>
<?php if(!$view) : ?>
<?php access_deny();  ?>
<?php else : ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js" type="text/javascript"></script>
<div class='row'>
	<div class='col-lg-12'>
    	<h3 style='margin-bottom:0px;'><?php echo $this->title; ?></h3>
    </div>
</div><!-- End Row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:5px;' />
<?php if( isset($id_check) ) : ?>
	<?php foreach($data as $rs) : ?>
    <div class="row">
    <div class="col-lg-3">เลขที่ : <?php echo $rs->reference; ?></div>
    <div class="col-lg-4">หัวข้อ : <?php echo $rs->subject; ?></div>
    <div class="col-lg-4">สถานที่ : <?php echo $rs->location; ?></div>
    <div class="col-lg-1"><button type="button" class="btn btn-warning btn-minier btn-block" onclick="go_back()">ออก</button></div>
    <input type="hidden" name="id_check" id="id_check" value="<?php echo $id_check; ?>" />
    <input type="hidden" name="status" id="status" value="<?php echo $rs->status; ?>" />
    <input type="hidden" name="pause" id="pause" value="<?php echo $rs->pause; ?>" />
		<?php $allow_input_qty = $rs->allow_input_qty; ?>
    </div>
    <script>
		setInterval(function(){	var id = <?php echo $id_check; ?>; $.ajax({url:"<?php echo $this->home; ?>/checkStatus/"+id, type:"GET", cache: false, success: function(rs){	var rs = $.trim(rs);	$("#status").val(rs); } }); }, 3000);
		setInterval(function(){	var id = <?php echo $id_check; ?>; $.ajax({url:"<?php echo $this->home; ?>/checkPause/"+id, type:"GET", cache: false, success: function(rs){	var rs = $.trim(rs);	$("#pause").val(rs); } }); }, 3000);
	</script>
    <?php endforeach; ?>
    <hr style='border-color:#CCC; margin-top: 5px; margin-bottom:15px;' />
    <div class="row">
    	<div class="col-lg-4">
				<?php if($allow_input_qty == 0) : ?>
        	<p class="pull-right" style="font-size:14px;">ยิงบาร์โค้ดสินค้าในช่องนี้ &nbsp;&nbsp;<i class="fa fa-arrow-right" style="color: red;"></i></p>
				<?php endif; ?>
      </div>
			<?php if($allow_input_qty == 1) : ?>
			<div class="col-lg-2">
				<input type="number" class="form-control input-sm text-center" step="1" name="qty" id="qty" value="1" placeholder="ใส่จำนวน">
			</div>
		<?php endif; ?>

    	<div class="col-lg-4">
        	<div class="input-group">
            <input type="text" class="form-control input-sm" id="barcode" placeholder="ยิงบาร์โค้ดเพื่อตรวจนับ" autofocus />
            <span class="input-group-btn">
            <button type="button" class="btn btn-primary btn-xs" id="btn_check" onclick="do_checking()">ตรวจนับ</button>
            </span>
            </div>
        </div>
    </div>
    <hr style='border-color:#CCC; margin-top: 15px; margin-bottom:15px;' />
    <div class="row">
    <div class="col-lg-6">
    <div  id="sc">
    <table class="table table-striped">
    <tr>
    <th colspan="4" style="text-align:center;">
    	<span>กำลังตรวจนับ </span>
        <button class="btn btn-success btn-minier pull-right" onclick="clearSheet()">เคลีร์ยพื้นที่ (F2)</button>
        <button class="btn btn-warning btn-minier pull-right" onclick="input_view()">เรียกดูรายการ</button>
    </th>
    </tr>
    <tr>
			<th style="width:25%;">บาร์โค้ด</th>
			<th>รหัสสินค้า</th>
			<th style="width:10%;" class="text-right">จำนวน</th>
			<th style="width:15%; text-align:center;">เวลา</th>
			<th style="width:10%; text-align:right;">ลบ</th>
		</tr>
    <tbody id="rs">

    </tbody>
    </table>
    </div>
    </div>
    <div class="col-lg-6">
    <div id="cs">
    <table class="table table-striped">
    	<tr><th colspan="3" style="text-align:center;">ตรวจนับแล้ว</th></tr>
        <tbody id="res">
        <tr id="head"><th style="width:30%;">บาร์โค้ด</th><th>รหัสสินค้า</th><th style="width:15%; text-align:right;">จำนวน</th></tr>
    <?php if( isset($checked) && $checked != false ) : ?>
    <?php foreach($checked as $rs) : ?>
    	<tr id="row_<?php echo $rs->barcode; ?>"><td><?php echo $rs->barcode; ?></td><td><?php echo $rs->item_code; ?></td><td align="right" id="<?php echo $rs->barcode; ?>"><?php echo $rs->qty; ?></td></tr>
    <?php endforeach; ?>
    <?php endif; ?>
      		</tbody>
    </table>
    </div>
    </div>
    </div>

<?php else : ?>
<div class="row" >
	<div class="col-lg-12">
	<?php if( isset($data) && $data != false ) : ?>
        <table class="table table-striped">
        <thead>
        <th style="width:15%;">เลขที่อ้างอิง</th>
        <th style="width:25%;">หัวข้อ</th>
        <th style="width:35%;">สถานที่</th>
        <th style="width:10%;">วันที่เริ่ม</th>
        <th style="width:10%; text-align:center;">หมายเหตุ</th>
        <th style="text-align:right;">&nbsp;</th>
        </thead>
            <?php foreach($data as $rs) : ?>
            <tr style="font-size:14px;">
                <td style="vertical-align:middle;"><?php echo $rs->reference; ?></td>
                <td style="vertical-align:middle;"><?php echo $rs->subject; ?></td>
                <td style="vertical-align:middle;"><?php echo $rs->location; ?></td>
                <td style="vertical-align:middle;"><?php echo thaiDate($rs->date_open); ?></td>
                <td style="vertical-align:middle;" align="center">
                <?php if(!is_null($rs->remark)) : ?>
                <span style="cursor:pointer;" tabindex="0" role="button" data-trigger="focus" aria-describedby="popover" data-original-title="<i class='ace-icon fa fa-exclamation-triangle blue'></i> หมายเหตุ" class="popover-info" data-rel="popover" data-placement="left" title="" data-content="<?php echo $rs->remark; ?>"><i class='ace-icon fa fa-exclamation-triangle fa-2x blue'></i></span>
                 <?php endif; ?>
                </td>
                <td style="vertical-align:middle;" align="right"><button class="btn btn-info" type="button" onclick="go_checking(<?php echo $rs->id_check; ?>)">ตรวจนับสินค้า</button></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
    <center><h4 style="margin-top:50px;">----- ไม่ได้เปิดการตรวจนับ กรุณาติดต่อผู้ควบคุมการตรวจนับ  -----</h4></center>
    <?php endif; ?>

    </div>
</div>
<script> setInterval( function(){ window.location.reload(); }, 300000); </script>
<?php endif; ?>

<!------------------------------------------------- Modal  ----------------------------------------------------------->
<div class='modal fade' id='item_modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' style='width:500px;'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4 class='modal-title' id='myModalLabel'>เพิ่มฐานข้อมูล</h4>
		  </div>
		  <div class='modal-body' id="edit_modal">
          	<div class="row">
            <div class="col-lg-12">
                <label >บาร์โค้ดสินค้า</label>
                <input type="text" id="barcode_label"  class="form-control input-sm" disabled  />
                <input type="hidden" id="barcode_item" value="" />
            </div>

            <div class="col-lg-12">
                <label >รหัสสินค้า</label>
                <input type="text" id="item_code" class="form-control input-sm" value="" />
            </div>

            <div class="col-lg-12">
                <label >ชื่อสินค้า</label>
                <input type="text" id="item_name"class="form-control input-sm" value="" />
            </div>
            <div class="col-lg-12">
                <label >รุ่นสินค้า</label>
                <input type="text" id="style" class="form-control input-sm" value="" />
            </div>
            <div class="col-lg-12">
                <label >กลุ่มสินค้า</label>
                <select id="item_group" class="form-control input-sm">
                	<option value="0">เลือกกลุ่มสินค้า</option>
                <?php echo selectItemGroup(); ?>
                </select>
            </div>
            <div class="col-lg-12">
                <label for="cash_out">ราคาทุน</label>
                <input type="text" id="cost" class="form-control input-sm" value="" />
            </div>

            <div class="col-lg-12">
                <label for="move_type">ราคาขาย</label>
                <input type="text" id="price" class="form-control input-sm" value="" />
            </div>
            <div class="col-lg-12">
            	<label style="display:block; visibility:hidden;">update</label>
            	<button type="button" class="btn btn-success btn-sm btn-block" onclick="add_item()"><i class="fa fa-save"></i> บันทึก</button>
            </div>
            </div>
          </div><!--- modal-body -->
		</div>
	</div>
</div>
<!------------------------------------------------- END Modal  ----------------------------------------------------------->
<!------------------------------------------------- Modal  ----------------------------------------------------------->
<div class='modal fade' id='view_modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' style='width:200px;'>
		<div class='modal-content'>
		  <div class='modal-header' style="border-bottom:0px;">

			<button type='button' class='bootbox-close-button close' style="margin-top:-15px;" data-dismiss='modal' aria-hidden='true'>&times;</button>
		  </div>
		  <div class='modal-body'>
          	<div class="row">
            <div class="col-lg-12" style="margin-bottom:15px;">
            	<label>ระบุจำนวนรายการล่าสุด</label>
            	<input type="text" class="form-control input-sm" id="view_qty" />
            </div>
            <div class="col-lg-12">
            	<button type="button" class="btn btn-xs btn-info btn-block" onclick="view_history()">แสดงรายการ</button>
            </div>
            </div>
          </div><!--- modal-body -->
		</div>
	</div>
</div>
<!------------------------------------------------- END Modal  ----------------------------------------------------------->

<script id="check_template" type="text/x-handlebars-template">
<tr id="rs_{{id}}">
	<td>{{ barcode }}</td>
	<td>{{ product }}</td>
	<td class="text-right" id="qty_{{id}}">{{qty}}</td>
	<td align="center">{{ timestamp }}</td>
	<td align="right">
		<button type="button" class="btn btn-minier btn-danger" onclick="delete_check({{id}}, '{{barcode}}')">ลบ</button>
	</td>
</tr>
</script>

<script id="history_template" type="text/x-handlebars-template">
{{#each this}}
{{#if no_content}}
<tr >
	<td colspan="4" align="center">----- ไม่พบประวัติ  -----</td>
</tr>
{{else}}
<tr id="rs_{{id}}">
	<td>{{ barcode }}</td>
	<td>{{ product }}</td>
	<td class="text-right" id="qty_{{id}}">{{qty}}</td>
	<td align="center">{{ timestamp }}</td>
	<td align="right">
		<button type="button" class="btn btn-minier btn-danger" onclick="delete_check({{id}}, '{{barcode}}')">ลบ</button>
	</td>
</tr>
{{/if}}
{{/each}}
</script>

<script id="checked_template" type="text/x-handlebars-template">
<tr id="row_{{barcode}}">
	<td>{{ barcode }}</td>
	<td>{{ product }}</td>
	<td align="right" id="{{barcode}}">{{ qty }}</td>
</tr>
</script>


<script src="<?php echo base_url(); ?>scripts/beep.js"></script>
<script>
$("#sc").slimScroll({ position: 'left', height : '500px', railVisible: false, alwaysVisible: true});
$("#cs").slimScroll({ position: 'left', height : '500px', railVisible: false, alwaysVisible: true});
function view_history()
{
	var qty = $("#view_qty").val();
	$("#view_qty").val("");
	$("#view_modal").modal("hide");
	if(qty != "" && qty != 0)
	{
		var id_check = $("#id_check").val();
		load_in();
		$.ajax({
			url:"<?php echo $this->home; ?>/get_history",
			type:"POST",
			cache:"false",
			data:{
				"id_check" : id_check,
				"qty" : qty
			},
			success: function(rs)
			{
				load_out();
				var source = $("#history_template").html();
				var data 		= $.parseJSON(rs);
				var output	= $("#rs");
				render(source, data, output);
			}
		});
	}
}


function input_view()
{
	$("#view_modal").modal("show");
}


$("#view_modal").on("shown.bs.modal", function(){
	$("#view_qty").focus();
});

$("#view_qty").numberOnly();

$("#view_qty").keyup(function(e) {
    if(e.keyCode == 13)
	{
		if(!isNaN($(this).val()))
		{
			view_history();
		}
	}
});


function do_checking()
{
	var id_check	= $("#id_check").val();
	var barcode 	= $.trim($("#barcode").val());
	$("#barcode").val("");
	if(id_check == "")
	{
		swal("พบข้อผิดพลาด !!", "ไม่พบ ID ของการตรวจนับ ลองออกจากการตรวจนับแล้วกลับเข้ามาใหม่", "error");
		return false;
	}

	if($("#pause").val() == 1)
	{
		window.location.href = "<?php echo $this->home; ?>/just_pause";
		return false;
	}

	if(barcode != "" && id_check != "")
	{
		<?php
		if($allow_input_qty == 1)
		{
			echo 'check_with_qty(id_check, barcode);';
		}
		else
		{
			echo 'check_without_qty(id_check, barcode);';
		}
			?>
	}
}


function check_with_qty(id_check, barcode)
{
	var	input_qty = isNaN(parseInt($('#qty').val())) ? 1 : parseInt($('#qty').val());
	$("#barcode").attr("disabled", "disabled");
	$("#btn_check").attr("disabled", "disabled");
	$('#qty').attr('disabled', 'disabled');
	$.ajax({
		url:"<?php echo $this->home; ?>/do_checking"	,
		type:"POST",
		cache:"false",
		data:{
			"id_check" : id_check,
			"barcode" : barcode,
			"qty" : input_qty
		},
		success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "")
			{
				swal("การส่งข้อมูลล้มเหลว","", "error");
			}
			else if(rs =="fail")
			{
				swal("ไม่สำเร็จ", "บันทึกข้อมูลไม่สำเร็จ", "error");
			}
			else if(rs == "NoItem")
			{
				$("#barcode_label").val(barcode);
				$("#barcode_item").val(barcode);
				$("#barcode").removeAttr("disabled");
				$("#btn_check").removeAttr("disabled");
				$('#qty').reomveAttr('disabled');
				$('#qty').val('');
				beep();
				swal({
					title : "ไม่พบสินค้า",
					text : "ไม่พบบาร์โค้ด "+barcode+" ในฐานข้อมูล คุณต้องการเพิ่ม บาร์โค้ดนี้ในฐานข้อมูลหรือไม่ ? ",
					type : "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "ใช่ เพิ่มฐานข้อมูล",
						cancelButtonText: "ยกเลิก",
						closeOnConfirm: true
					},
				function(isConfirm){
					if (isConfirm)
					{
						$("#item_modal").modal("show");
					}
				});
			}
			else
			{
				var source 	= $("#check_template").html();
				var data 		= $.parseJSON(rs);
				var output 	= $("#rs");
				render_prepend(source, data, output);
				if($("#"+barcode).length)
				{
					var qty = parseInt($("#"+barcode).html());
					$("#"+barcode).html(qty + parseInt(data.qty));
					$("#row_"+barcode).insertAfter($("#head"));
				}
				else
				{
					$.ajax({
						url:"<?php echo $this->home; ?>/get_item_code/"+barcode,
						type:"GET", cache:"false", success: function(rs)
						{
							var sc = $("#checked_template").html();
							var da = $.parseJSON(rs);
							var op = $("#res");
							render_append(sc, da, op);
						}
					});
					var qty = parseInt($("#"+barcode).html());
					$("#"+barcode).html(qty + parseInt(data.qty));
					$("#row_"+barcode).insertAfter($("#head"));
				}
				$("#barcode").removeAttr("disabled");
				$("#btn_check").removeAttr("disabled");
				$('#qty').val('1');
				$('#qty').removeAttr('disabled');
				$("#barcode").focus();
			}
		}
	});
}


function check_without_qty(id_check, barcode)
{
	$("#barcode").attr("disabled", "disabled");
	$("#btn_check").attr("disabled", "disabled");
	$.ajax({
		url:"<?php echo $this->home; ?>/do_checking"	,
		type:"POST",
		cache:"false",
		data:{
			"id_check" : id_check,
			"barcode" : barcode
		},
		success: function(rs)
		{
			var rs = $.trim(rs);
			if(rs == "")
			{
				swal("การส่งข้อมูลล้มเหลว","", "error");
			}
			else if(rs =="fail")
			{
				swal("ไม่สำเร็จ", "บันทึกข้อมูลไม่สำเร็จ", "error");
			}
			else if(rs == "NoItem")
			{
				$("#barcode_label").val(barcode);
				$("#barcode_item").val(barcode);
				$("#barcode").removeAttr("disabled");
				$("#btn_check").removeAttr("disabled");
				beep();
				swal({
					title : "ไม่พบสินค้า",
					text : "ไม่พบบาร์โค้ด "+barcode+" ในฐานข้อมูล คุณต้องการเพิ่ม บาร์โค้ดนี้ในฐานข้อมูลหรือไม่ ? ",
					type : "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "ใช่ เพิ่มฐานข้อมูล",
						cancelButtonText: "ยกเลิก",
						closeOnConfirm: true
					},
				function(isConfirm){
					if (isConfirm)
					{
						$("#item_modal").modal("show");
					}
				});
			}
			else
			{
				var source 	= $("#check_template").html();
				var data 		= $.parseJSON(rs);
				var output 	= $("#rs");
				render_prepend(source, data, output);
				if($("#"+barcode).length)
				{
					var qty = parseInt($("#"+barcode).html());
					$("#"+barcode).html(qty+1);
					$("#row_"+barcode).insertAfter($("#head"));
				}
				else
				{
					$.ajax({
						url:"<?php echo $this->home; ?>/get_item_code/"+barcode,
						type:"GET", cache:"false", success: function(rs)
						{
							var sc = $("#checked_template").html();
							var da = $.parseJSON(rs);
							var op = $("#res");
							render_append(sc, da, op);
						}
					});
					var qty = parseInt($("#"+barcode).html());
					$("#"+barcode).html(qty+1);
					$("#row_"+barcode).insertAfter($("#head"));
				}
				$("#barcode").removeAttr("disabled");
				$("#btn_check").removeAttr("disabled");
				$("#barcode").focus();
			}
		}
	});
}


function add_item()
{
	var barcode 	= $("#barcode_item").val();
	var item_code 	= $("#item_code").val();
	var item_name 	= $("#item_name").val();
	var style			= $("#style").val();
	var type			= $("#item_group").val();
	var cost			= $("#cost").val();
	var price 		= $("#price").val();
	var active		= 1;
	$("#barcode").removeAttr("disabled");
	$("#barcode").val("");
	$("#btn_check").removeAttr("disabled");
	if( barcode == ""){ swal("กรุณากำหนดบาร์โค้ด"); return false; }
	if( item_code == ""){ swal("กรุณากำหนดรหัสสินค้า"); return false; }
	if( item_name == ""){ swal("กรุณากำหนดชื่อสินค้า"); return false; }
	if( style == ""){ swal("กรุณากำหนดรุ่น"); return false; }
	if(isNaN(parseFloat(cost))){ swal("ราคาทุนไม่ถูกต้อง"); return false; }
	if(isNaN(parseFloat(price))){ swal("ราคาขายไม่ถูกต้อง"); return false; }
	$("#btn_save").attr("disabled", "disabled");
	$("#item_modal").modal("hide");
	load_in();
	$.ajax({
		url:"<?php echo base_url(); ?>admin/product/add_item",
		type: "POST", cache: "false", data: { "barcode" : barcode, "item_code" : item_code, "item_name" : item_name, "style" : style, "type" : type, "cost" : cost, "price" : price, "active" : active },
		success: function(rs)
		{
			load_out();
			$("#btn_save").attr("disabled", "disabled");
			var rs = $.trim(rs);
			if(rs == "fail" || rs == "duplicate_barcode" || rs == "")
			{
				swal("Error !!", "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ภายหลัง", "error");
				clear_field();
				$("#btn_save").removeAttr("disabled");
			}else{
				swal("สำเร็จ", "เพิ่มฐานข้อมูลสำเร็จ ยิงบาร์โค้ดเดิมอีกทีเพื่อตรวจนับ", "success");
				clear_field();
				$("#btn_save").removeAttr("disabled");
			}
		}
	});
}

function delete_check(id, barcode)
{
	swal({
		title : "ลบรายการตรวจนับ",
		text : "คุณต้องการลบรายการตรวจนับนี้ใช่หรือไม่? ",
		type : "warning",
	  	showCancelButton: true,
	  	confirmButtonColor: "#DD6B55",
	  	confirmButtonText: "ใช่ ลบเลย",
	  	cancelButtonText: "ไม่ใช่",
	  	closeOnConfirm: false
		},
		function(isConfirm){
		  if (isConfirm)
		  {
			$.ajax({
				url:"<?php echo $this->home; ?>/delete_check_detail/"+id,
				type:"GET",
				cache:"false",
				success: function(rs)
				{
					var rs = $.trim(rs);
					if(rs == "success")
					{
						var qty = parseInt($("#"+barcode).html());
						var del_qty = parseInt($('#qty_'+id).html());
						$("#"+barcode).html(qty - del_qty);
						$("#rs_"+id).remove();
						swal({ title: "สำเร็จ", text: "ลบรายการเรียบร้อยแล้ว", timer: 1000, type: "success" });
					}
					else
					{
						swal("ลบรายการไม่สำเร็จ");
					}
				}
			});
		  }
	});
}
function clear_field()
{
	$("#barcode_item").val("");
	$("#barcode_label").val("");
	$("#item_code").val("");
	$("#item_name").val("");
	$("#style").val(0);
	$("#item_group").val("");
	$("#cost").val("");
	$("#price").val("");;
}
function go_checking(id)
{
	window.location.href = "<?php echo $this->home; ?>/index/"+id;
}

function clearSheet()
{
	$("#rs").html("");
}


$("#barcode").keyup(function(e) {
    if(e.keyCode == 13 )
	{
		do_checking();
	}
});

$(".popover-info").popover({ "html":true});
$(document).keyup(function(e) {
    if(e.keyCode == 113)
	{
		clearSheet();
	}
});

function go_back()
{
	window.location.href = "<?php echo $this->home; ?>";
}
</script>
<?php endif; ?>
