<!-- PAGE CONTENT ENDS --> 
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">โปรแกรมตรวจนับสินค้า</span>
							&copy; Covenant Co., Ltd.
						</span>

						
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<div id="loader" style="position:absolute; padding: 15px 25px 15px 25px; background-color:#fff; opacity:0.0; box-shadow: 0px 0px 25px #CCC; top:-20px; display:none;">
        <center><i class="fa fa-spinner fa-5x fa-spin blue"></i></center><center>กำลังทำงาน....</center></div> 

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.scroller.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.colorpicker.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.fileinput.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.typeahead.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.wysiwyg.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.spinner.js"></script>
        
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.treeview.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.wizard.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/elements.aside.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.ajax-content.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.touch-drag.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.sidebar.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.submenu-hover.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.widget-box.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.settings.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.searchbox-autocomplete.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.cookie.js"></script>
        

		<!-- inline scripts related to this page -->
<script>  
function load_in(){
	var x = ($(document).innerWidth()/2)-50;
	$("#loader").css("display","");
	$("#loader").css("left",x);
	$("#loader").animate({opacity:0.8, top:300},300);		
}
function load_out(){
	$("#loader").animate({opacity:0, top:-20},300, function(){ $("#loader").css("display","none");});
}   

function isDate(txtDate){
	  var currVal = txtDate;
	  if(currVal == '')
	    return false;  
	  //Declare Regex 
	  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	  var dtArray = currVal.match(rxDatePattern); // is format OK?
	  if (dtArray == null){
		     return false;
	  }
	  //Checks for mm/dd/yyyy format.	  
	  dtDay= dtArray[1];
	  dtMonth = dtArray[3];
	  dtYear = dtArray[5];
	  if (dtMonth < 1 || dtMonth > 12){
	      return false;
	  }else if (dtDay < 1 || dtDay> 31){
	      return false;
	  }else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31){
	      return false;
	  }else if (dtMonth == 2){
	     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
	     if (dtDay> 29 || (dtDay ==29 && !isleap)){
	          return false;
		 }
	  }
	  return true;
	}

function checkerror(){
    if($("#error").length){
		var mess = $("#error").val();
		swal("เกิดข้อผิดพลาด!", mess, "error");
	}else if($("#success").length){
		var mess = $("#success").val();
		swal({ title: "สำเร็จ", text: mess, timer: 1000, type: "success"});
	}else if($("#info").length){
		var mess = $("#info").val();
		swal({ title: "สำเร็จ", text: mess, html : true, type: "success"});
	}
}  

//**************  Handlebars.js  **********************//
function render(source, data, output){
	var template = Handlebars.compile(source);
	var html = template(data);
	output.html(html);
}

function render_append(source, data, output)
{
	var template = Handlebars.compile(source);
	var html = template(data);
	output.append(html);
}

function render_prepend(source, data, output)
{
	var template = Handlebars.compile(source);
	var html = template(data);
	output.prepend(html);
}

var downloadTimer;

function download(token)
{
	load_in();
	downloadTimer = window.setInterval(function(){
		$.ajax({
			url:"<?php echo base_url(); ?>admin/tool/check_download",
			type:"POST", cache:"false", data:{ "token" : token },
			success: function(rs)
			{
				var rs = $.trim(rs);
				if(rs == "finished")
				{
					finished_download();
				}
			}
		});
	}, 1000);
}

function finished_download()
{
	window.clearInterval(downloadTimer);
	$.post("<?php echo base_url(); ?>admin/tool/finished_download");
	load_out();
}

</script>
	</body>

</html>