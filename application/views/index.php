<?php if($this->session->userdata("id_user") == null && !$this->input->cookie("id_user")){ redirect(base_url()."authentication"); } ?>
<!DOCTYPE HTML>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../favicon.ico" />
    <title><?php if(isset($page_title)){ echo $page_title; }else{ echo "Welcome"; } ?></title>

    <!-- Core CSS - Include with every page -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/paginator.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootflat.min.css" rel="stylesheet">
     <link rel="stylesheet" href="<?php  echo base_url();?>assets/css/jquery-ui-1.10.4.custom.min.css" />
     <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    
  	<script src="<?php  echo base_url();?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/iCheck/icheck.js"></script>
     
    
    
    <!-- SB Admin CSS - Include with every page -->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/template.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/js/iCheck/skins/all.css?v=1.0.2" rel="stylesheet">
   

</head>
<body style='padding-top:0px;'>
<div class="container">
    <div class="row"> 
                <div class="col-lg-3 col-lg-offset-4">
                <div class="col-lg-12">
                	<h3 style="text-align:center">Welcome</h3>
       			 </div>
                <div class="col-lg-12">
                	<button class="btn btn-success btn-block" onClick="get_page('<?php echo base_url(); ?>shop/check')"><i class="fa fa-tags"></i>&nbsp; ตรวจนับสินค้า</button>
                </div>
                <div class="col-lg-12">&nbsp;</div>
                <div class="col-lg-12">
                	<button class="btn btn-danger btn-block" onClick="get_page('<?php echo valid_menu(1,"admin/main"); ?>')"><i class="fa fa-gears"></i>&nbsp; ควบคุมการตรวจนับ</button>
                </div>
               <div class="col-lg-12">&nbsp;</div>
               <div class="col-lg-12"><p class="pull-right"><a href="<?php echo base_url(); ?>authentication/logout"><i class='fa fa-sign-out'></i> ออกจากระบบ</a></p></div>
                </div>
      
    </div>
</div>
<script>
function get_page(page)
{
	window.location.href = page;	
}
</script>
</body>
</html>