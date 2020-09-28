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

<div class="row">
    <div class="col-lg-12">
    	<p class="pull-right" style="margin-top:5px;">
   	 	<?php echo rows_box(); ?>
    	</p>
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
            <th style="width:10%;">พนักงาน</th>
            <th style="width:8%;">วันที่เริ่ม</th>
            <th style="width:8%;">วันที่จบ</th>
            <th style="width:5%; text-align:center">ข้อมูล</th>
            <th style="width:5%; text-align:center">หมายเหตุ</th>
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
                   <td align="center" >
                   <?php if(!is_null($rs->remark)) : ?>
                   	<span style="cursor:pointer;" tabindex="0" role="button" data-trigger="focus" aria-describedby="popover" data-original-title="<i class='ace-icon fa fa-exclamation-triangle blue'></i> หมายเหตุ" class="popover-info" data-rel="popover" data-placement="left" title="" data-content="<?php echo $rs->remark; ?>"><i class='ace-icon fa fa-exclamation-triangle fa-2x blue'></i></span>
                    <?php endif; ?>
                   </td>
                    <td align="right" style="vertical-align:middle;">
                    <button type="button" id="btn_summary_report" class="btn btn-success btn-minier" onclick="export_summary(<?php echo $id; ?>)"><i class="fa fa-file-excel-o"></i>&nbsp; รายงานสรุป</button>
                    <button type="button" id="btn_detail_report" class="btn btn-info btn-minier" onclick="export_detail(<?php echo $id; ?>)"><i class="fa fa-file-excel-o"></i>&nbsp; รายละเอียด</button>
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

<script>
function export_summary(id)
{
	var token	= new Date().getTime();	
	download(token);
	window.location.href = "<?php echo $this->home; ?>/export_summary/"+id+"/"+token;
}

function export_detail(id)
{
	var token	= new Date().getTime();
	download(token);
	window.location.href = "<?php echo $this->home; ?>/export_detail/"+id+"/"+token;	
}

$('[data-rel=popover]').popover({html:true});
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
</script>

<?php endif; ?>