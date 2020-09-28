<?php if($this->session->userdata("id_user") == null){ redirect(base_url()."authentication"); } ?>
<?php date_default_timezone_set('Asia/Bangkok'); ?>
<!DOCTYPE html>
<html lang="th">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />

		<title><?php if( isset($this->title) ){ echo $this->title; } ?></title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.css" />
		<!-- page specific plugin styles -->
		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.css " />
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-ie.css" />
		<![endif]-->
		<!-- inline styles related to this page -->
		<!-- ace settings handler -->
		<script src="<?php echo base_url(); ?>assets/js/ace-extra.js"></script>
        <!-- basic scripts -->
		<!--[if !IE]>-->
		<!--<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery1.js'>"+"<"+"/script>");
		</script> -->
		<!-- <![endif]-->
		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
  	<script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/date-time/bootstrap-datepicker.js" ></script>
    <script src="<?php echo base_url(); ?>assets/js/ace/ace.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ace/elements.fileinput.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sweet-alert.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/handlebars-v3.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sweet-alert.css">
    <style> .ui-helper-hidden-accessible { display:none; } .ui-autocomplete { z-index:100000; } </style>
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!--[if lte IE 8]>
		<script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/respond.js"></script>
		<![endif]-->
	</head>
	<body class="no-skin" onLoad="checkerror();">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- /section:basics/sidebar.mobile.toggle -->

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<span class=""><?php echo $this->session->userdata("employee"); ?></span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="<?php echo base_url(); ?>authentication/logout"><i class="ace-icon fa fa-power-off"></i> ออกจากระบบ	</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script>
				var BASE_URL = "<?php echo base_url(); ?>";
			</script>
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<!--- side menu  ------.>
                <?php
					if( $this->uri->segment(1) == "admin" ) :
                		$this->load->view("include/side_menu");
                	else :
						$this->load->view("include/pos_menu");
					endif;
					?>
				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>


				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
                <script type="text/javascript">
					jQuery.fn.numberOnly = function()
						{
							return this.each(function()
							{
								$(this).keydown(function(e)
								{
									var key = e.charCode || e.keyCode || 0;
									// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
									// home, end, period, and numpad decimal
									return (
										key == 8 ||
										key == 9 ||
										key == 13 ||
										key == 46 ||
										key == 110 ||
										key == 190 ||
										(key >= 35 && key <= 40) ||
										(key >= 48 && key <= 57) ||
										(key >= 96 && key <= 105));
								});
							});
						};

						addCommas = function(input){
						  // If the regex doesn't match, `replace` returns the string unmodified
						  return (input.toString()).replace(
							// Each parentheses group (or 'capture') in this regex becomes an argument
							// to the function; in this case, every argument after 'match'
							/^([-+]?)(0?)(\d+)(.?)(\d+)$/g, function(match, sign, zeros, before, decimal, after) {

							  // Less obtrusive than adding 'reverse' method on all strings
							  var reverseString = function(string) { return string.split('').reverse().join(''); };

							  // Insert commas every three characters from the right
							  var insertCommas  = function(string) {

								// Reverse, because it's easier to do things from the left
								var reversed           = reverseString(string);

								// Add commas every three characters
								var reversedWithCommas = reversed.match(/.{1,3}/g).join(',');

								// Reverse again (back to normal)
								return reverseString(reversedWithCommas);
							  };

							  // If there was no decimal, the last capture grabs the final digit, so
							  // we have to put it back together with the 'before' substring
							  return sign + (decimal ? insertCommas(before) + decimal + after : insertCommas(before + after));
							}
						  );
						};

						$.fn.addCommas = function() {
							  $(this).each(function(){
								$(this).val(addCommas($(this).val()));
							  });
							};
				</script>
                <script type="text/javascript">
					function change_lang(){
						$.ajax({
							url:"<?php echo base_url(); ?>language",
							type:"POST",
							data:{ change : "switch" },
							cache:false,
							success: function(c){
								if(c =="success"){
									location.reload();
								}
							}
						});
					}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
                <?php if($this->session->flashdata("error") != null) :?>
					<input type="hidden" id="error" value="<?php echo $this->session->flashdata("error"); ?>">
                <?php elseif( $this->session->flashdata("success") != null ) : ?>
                	<input type="hidden" id="success" value="<?php echo $this->session->flashdata("success"); ?>">
                <?php elseif( $this->session->flashdata("info") != null ) : ?>
                	<input type="hidden" id="info" value="<?php echo $this->session->flashdata("info"); ?>">
               <?php endif; ?>
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
