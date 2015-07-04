<?php
include( TEMPLATE_PATH . "/inc/header.inc.php" );
?>
<div class="container">
			<?php
			echo $stats;
			?>
<script type="text/javascript">
$(document).ready(function(){
	$( "tbody tr:last-child" ).addClass("table-footer");
	var countTables = $("table thead tr").length;
	$("thead tr")
		.each(function(index){
			var colors = ["primary","green","yellow","light-blue","red","primary","green","yellow","light-blue","red","primary","green","yellow","light-blue","red"];
			$(this).addClass(colors[index]);
		});
	});
</script>
<?php
include( TEMPLATE_PATH . "/inc/footer.inc.php" );
?>
