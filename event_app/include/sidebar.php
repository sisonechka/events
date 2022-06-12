<section class="sidebar">
  <ul class="sidebar-menu">
    <li class="treeview">
		<a href="<?php echo WEB_ROOT; ?>views/?v=DB"><i class="fa fa-calendar"></i><span>Events Calendar</span></a>
	</li>

	<?php
	$type = $_SESSION['calendar_fd_user']['type'];
	if($type == 'admin') {
	?>
	<li class="treeview"> 
        <a href="<?php echo WEB_ROOT; ?>views/?v=LIST"><i class="fa fa-newspaper-o"></i><span>Booking Details</span></a>
    </li>
      <li class="treeview">
      <a href="<?php echo WEB_ROOT; ?>views/?v=USERS"><i class="fa fa-users"></i><span>User Details</span></a>
      </li>
	<?php
	}
	?>
  </ul>
</section>
