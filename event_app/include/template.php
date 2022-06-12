<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$self = WEB_ROOT . 'admin/index.php';
?>
<!DOCTYPE html>
<html>
<head>
<?php include('head.php'); ?>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <nav class="navbar navbar-static-top" role="navigation">
      <?php include('nav.php'); ?>
    </nav>
  </header>
  <aside class="main-sidebar">
   <?php include('sidebar.php'); ?>
  </aside>
  <div class="content-wrapper">
    <section class="content">
      
	  <div class="row">
	  	<div class="col-md-12">
		<?php include('messages.php'); ?>
		</div>
	  </div>
	  
	  <div class="row">
	  	<?php
			require_once $content;	 
		?>
      </div>
    </section>
  </div>

</div>
</body>
</html>
