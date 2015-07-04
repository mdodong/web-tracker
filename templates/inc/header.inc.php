<?php 
if(!isset($page_title)){
	$page_title = 'ATT Hybrid Tracker';
}
?>
<!DOCTYPE html>
<html>

<head>
	<title><?php echo $page_title; ?></title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Twitter Bootsrap CSS -->
    <link href="<?php echo TEMPLATE_PATH; ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo TEMPLATE_PATH; ?>/assets/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    
    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<link href="<?php echo TEMPLATE_PATH; ?>/assets/chosen/chosen.min.css" rel="stylesheet">
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo TEMPLATE_PATH; ?>/assets/jquery/jquery-1.11.2.min.js"></script>
    <script src="<?php echo TEMPLATE_PATH; ?>/assets/jquery-ui/jquery-ui.min.js"></script>
    
    <link href="<?php echo TEMPLATE_PATH; ?>/style.css" rel="stylesheet">
    
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary wrapper">
				<div class="panel-heading">
					<h4 class="menu-title">ATTHybrid Dashboard</h4>
				</div>
				
				<div class="panel-body">
				<p class="list-inline">
				<?php include(TEMPLATE_PATH ."/inc/menu.inc.php"); ?>
				</p>
				<br>
				<hr>
				<br>
				
