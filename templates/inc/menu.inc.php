<a class="menu" href="?view=home" title="Hybrid Dashboard Home"><i class="glyphicon glyphicon-home">Home </i></a>
<a class="menu" href="?view=logs" title="View My Logs"><i class="glyphicon glyphicon-list-alt">ViewLogs </i></a>
<a class="menu" href="?view=addcase" title="Add Case logs"><i class="glyphicon glyphicon-plus-sign">AddCase </i></a>

<?php 
$admin_tools = "";
if(!isset($_SESSION['username'])){
	echo '<a class="menu pull-right" href="?view=login" title="Login"><i class="glyphicon glyphicon-user">Login </i></a>';
}else{
	if(isset($_SESSION['role']) && $_SESSION['role'] != 'CONSLT'){
		 $admin_tools = '<div class="dropdown pull-right">
			  <button class="btn btn-info dropdown-toggle" type="button" id="menu-tools" data-toggle="dropdown">Admin Tools
				<span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu" aria-labelledby="menu-tools">
				<li role="presentation"><a role="menuitem" tabindex="-1" href="?view=eod">EOD</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="?view=date">EOD(Previous Dates)</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="?view=export">Raw Export</a></li>
			  </ul>
			 </div>';
	}
	echo '<a class="menu pull-right" href="?view=logout" title="Logout"> <i class="glyphicon glyphicon-off">Logout </i></a>';
}
?>
<a class="menu pull-right" href="?view=monthly" title="Monthly"><i class="glyphicon glyphicon-calendar">Monthly </i></a>
<a class="menu pull-right" href="?view=weekly" title="Weekly"><i class="glyphicon glyphicon-calendar">Weekly </i></a>
<a class="menu pull-right" href="?view=rank" title="Rank"><i class="glyphicon glyphicon-signal">Rank(MTD) </i></a>
		
<div class="dropdown pull-left">
	<button class="btn btn-info dropdown-toggle" type="button" id="menu-tools" data-toggle="dropdown">Cheatsheet and other Links
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu" aria-labelledby="menu-tools">
		<li role="presentation"><a role="menuitem" tabindex="-1" href="http://fildesct3127a:8080/daily-tracker/ConnecTech_Call_Cheatsheets.htm">Cheatsheet</a></li>
		<li role="presentation"><a role="menuitem" tabindex="-1" href="http://fildesct3127a:8080/daily-tracker/save-sale/Hybrid%20Save%20Sale_Decision%20Tree.htm">Save Sale Workflow</a></li>
		<li role="presentation" class="divider"></li>
		<li role="presentation"><a role="menuitem" tabindex="-1" href="#">About Us</a></li>
	</ul>
</div>

<?php
echo $admin_tools;
if(isset($_SESSION['fullname'])){
	echo "<h4 class=\"pull-right\">Hello, <strong>" .$_SESSION['fullname'] ." &nbsp; &nbsp; &nbsp; </strong></h4>";
}
?>
