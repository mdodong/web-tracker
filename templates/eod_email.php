<?php
$html = <<<HTML
<!DOCTYPE html>
<html>

<head>
	<title>ATT Hybrid Tracker</title>
	<meta HTTP-EQUIV="Content-Type" CONTENT="text/html"; charset="utf-8">
    <style type="text/css">
    table{
		width: 600px;
	}
    table, td, th{
		border: 1px solid black;
		border-collapse: collapse;
		padding: 5px;
	}
	
	thead > tr{
		background-color: #337AB7;
	}
	th{
		color: #FFF;
	}
	.eod{
		padding-left: 50px;
	}
	
	tr > td+td{
		text-align: center;
	}
	tbody > tr:last-child{
		background-color: #337AB7;
		color: #FFF;
	}
	
    </style>
</head>
<body>
<div class="eod">
	$stats
</div>
</body>
</html>

HTML;
echo $html;		
	?>
