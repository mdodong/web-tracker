<?php
include( TEMPLATE_PATH . "/inc/header.inc.php" );
?>
<div class="container">
	<div class="row"><!-- exported -->
		<div class="col-xs-2"></div><!-- filler left -->
		<div class="col-xs-8"><!-- exported -->
		<form method="POST" action="?view=export&submit">
			<br>
			<h4>&nbsp;&nbsp;&nbsp; <span class="label label-success">Export Raw Caselogs </span></h4>
			<div class="input-group">
				<label for="st_date">
					Start Date: &nbsp;
				</label>
				<input id="st_date" type="text" name="start_date">
			</div>
			<br>
			<div>
				<label for="ed_date">
					Start Date: &nbsp;
				</label>
				<input id="ed_date" type="text" name="end_date">
			</div>
			<br>
				<input class="btn btn-xs btn-info" type="submit" value="download" id="dl">
		</form>
		<div class="col-xs-2"></div><!-- filler right -->
		</div><!-- colum -->
		</div><!-- row -->
</div>
<script type="text/javascript">
	$("#st_date").datepicker({
		dateFormat: "yy-mm-dd",
		onSelect: function(selected) {
          $("#ed_date").datepicker("option","minDate", selected)
        }

		});
	$("#ed_date").datepicker({
		dateFormat: "yy-mm-dd",
		onSelect: function(selected) {
           $("#st_date").datepicker("option","maxDate", selected)
        }

		});
		
	$("#dl").click(function(event){
		var st = $("#st_date").val();
		var ed = $("#ed_date").val();
		if(st === "" || ed === ""){
			event.preventDefault();
		}
	});
	
</script>
			
<?php
include( TEMPLATE_PATH . "/inc/footer.inc.php" );
?>
