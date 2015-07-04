<?php
$page_title = 'Caselogs';
include( TEMPLATE_PATH . "/inc/header.inc.php" );
?>
<div class="container">
			<?php
			echo createLogsTable($caselogs->getIndividualLog($_SESSION['fullname']));
			?>
<script type="text/javascript">
	window.onload = function(){
		$(document).ready(function(){
			$(".btn").on('click', function(event){
				if(!confirm('Are you sure you want to delete this log?')){
					event.preventDefault();
				}
			});
		});
	};
</script>
<?php
include( TEMPLATE_PATH . "/inc/footer.inc.php" );
?>
