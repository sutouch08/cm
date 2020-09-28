

<!--  ***********************************   Side menu Start ************************************** -->
	<ul class="nav nav-list">
    	<li class=""><a href="<?php echo base_url(); ?>"><i class="menu-icon fa fa-home"></i><span class="menu-text"> หน้าหลัก</span></a></li>
		<li class="<?php echo active_menu(5,$id_menu); ?>"><a href="<?php echo valid_menu(5,"admin/main"); ?>"><i class="menu-icon fa fa-tasks"></i><span class="menu-text"> เพิ่ม/แก้ไข การตรวจนับ </span></a></li>
        <li class="<?php echo active_menu(1,$id_menu); ?>"><a href="<?php echo valid_menu(1,"admin/product"); ?>"><i class="menu-icon fa fa-tags"></i><span class="menu-text"> เพิ่ม/แก้ไข รายการสินค้า </span></a></li> 
        <li class="<?php echo active_menu(2,$id_menu); ?>"><a href="<?php echo valid_menu(2, "admin/employee"); ?>"><i class="menu-icon fa fa-users"></i><span class="menu-text"> เพิ่ม/แก้ไข พนักงาน </span></a></li>
        <li class="<?php echo active_menu(3,$id_menu); ?>"><a href="<?php echo valid_menu(3, "admin/user"); ?>"><i class="menu-icon fa fa-users"></i><span class="menu-text"> เพิ่ม/แก้ไข ชื่อผู้ใช้งาน </span></a></li>
        
        <li class="<?php echo active_menu(6,$id_menu); ?>"><a href="<?php echo valid_menu(6,"admin/location"); ?>"><i class="menu-icon fa fa-map-marker"></i><span class="menu-text"> เพิ่ม/แก้ไข สถานที่ </span></a></li>
        <li class="<?php echo active_menu(7,$id_menu); ?>"><a href="<?php echo valid_menu(7,"admin/report"); ?>"><i class="menu-icon fa fa-bar-chart"></i><span class="menu-text"> รายงานการตรวจนับ </span></a></li>
        

        <!-- **********************************  เก็บไว้เป็นตัวอย่าง ***********************************
		<li class=""><a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-file-o"></i>
        	<span class="menu-text"> Other Pages 
            <!-- #section:basics/sidebar.layout.badge 
            	<span class="badge badge-primary">5</span></span> <b class="arrow fa fa-angle-down"></b></a>	<b class="arrow"></b>
			<ul class="submenu">
				<li class=""><a href="#"><i class="menu-icon fa fa-caret-right"></i>FAQ	</a><b class="arrow"></b></li>
				<li class="active"><a href="#"><i class="menu-icon fa fa-caret-right"></i>Blank Page</a></li>
			</ul>
		</li>
        ****************************************************************************************** -->
	</ul><!-- /.nav-list -->
    