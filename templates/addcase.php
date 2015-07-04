<?php
include( TEMPLATE_PATH . "/inc/header.inc.php" );
?>
<div class="container">
	<form method="POST" action="?view=addcase&addcase=submit">
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-10">
					<p class="text-center">
						<?php echo $msg; ?>
						<strong class="h3">Add Case Log</strong>
					</p>
					<hr>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>Production Date:</strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<strong><?php echo date('Y-m-d'); ?></strong>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>BTN: </strong></p></div>
				<div class="col-md-4"><p class="text-left"><input type="text" class="form-control" name="btn"></p></div>
			</div>
			
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>CallType: </strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<select class="form-control chosen-select" name="calltype" id="calltype">
							<option value="sales">Sales Call</option>
							<option value="tech">Tech Call</option>
							<option value="misrouted">Misrouted</option>
							<option value="ghost call">Ghost Call</option>
						</select> 
					</p>
				</div>
			</div>
			
			<div class="row" id="soldservices">
				<div class="col-md-3"><p class="text-right"><strong>Service Sold: </strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<select class="form-control chosen-select" name="soldservice" id="soldservice"> 
							<option value="none">No Sale</option>
							<option value="spp">S+ ($15)</option>
							<option value="spp">S+ no ETF ($9.99)</option>
							<option value="ea">EA ($49)</option>
							<option value="eae">EAE($149)</option>
							<option value="spphwp">S+ ($15) + HWP</option>
							<option value="eahwp">EA ($49) + HWP</option>
							<option value="eaehwp">EAE($149) + HWP</option>
							<option value="hwp">HWP</option>
						</select>
					</p>
				</div>
			</div>
			
			
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>Reason for NoSale: </strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<select class="form-control chosen-select" id="nosale" name="nosale"> 
							<OPTION selected value="Price is too High">Price is too High</OPTION>
							<OPTION value="Customer Will try Resolving The issue">Customer Will try Resolving The issue</OPTION>
							<OPTION value="Customer Will Contact other Support Group">Customer Will Contact other Support Group</OPTION>
							<OPTION value="Caller Not the Account Holder">Caller Not the Account Holder</OPTION>
							<OPTION value="No Money">No Money / Fixed Income</OPTION>
							<OPTION value="Technical Issue - IVR, CTSF">Technical Issue - IVR, CTSF</OPTION>
							<OPTION value="Customer will Callback">Customer will Callback</OPTION>
							<OPTION value="Customer thought Service Is Free">Customer thought Service Is Free</OPTION>
							<OPTION value="Inquiry Call">Inquiry Call</OPTION>
							<OPTION value="Call Disconnected">Call Disconnected</OPTION>
							<OPTION value="Issue not Supported">Issue not Supported(Non AT&T Issue)</OPTION>
						</select>
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>Issue: </strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<select class="form-control chosen-select" name="issue" id="issue"> 
							<option>Connectivity</option>
							<option>Application Support</option>
							<option>Operating System</option>
							<option>Hardware</option>
							<option>Virus</option>
						</select>
					</p>
				</div>
			</div>
			
			<div class="row" id="ticket">
				<div class="col-md-3"><p class="text-right"><strong>Ticket #: </strong></p></div>
				<div class="col-md-4"><p class="text-left"><input type="text" class="form-control" name="ticket"></p></div>
			</div>
			
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>Resolved: </strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<input class="radio-btn" type="radio" name="Resolved" value="Yes" checked="checked"> Yes
						<input class="radio-btn" type="radio" name="Resolved" value="No"> No
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3"><p class="text-right"><strong>Transfered to IVR Survey: </strong></p></div>
				<div class="col-md-4">
					<p class="text-left">
						<select class="form-control chosen-select" name="ivr" id="ivr">
							<option>No</option>
							<option>Yes - Neutral</option>
							<option>Yes - Possible CSAT</option>
							<option>Yes - Possible DSAT</option>
						</select>
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-4">
					<p class="text-left">
						<input type="submit" class="form-control btn btn-success" name="addcase" value="save case">
					</p>
				</div>
			</div>
			
			
			
			
			<br>
			<br>
			<br>
			<br>
			
		</div>
		<div class="col-md-1"></div>
	</div>
	</form>
</div>		
<script type="text/javascript">
	$(document).ready(function(){ 
			 var nosaleReasons = '<OPTION selected value="Price is too High">Price is too High</OPTION>' +
								'<OPTION value="Customer Will try Resolving The issue">Customer Will try Resolving The issue</OPTION>'+
								'<OPTION value="Customer Will Contact other Support Group">Customer Will Contact other Support Group</OPTION>'+
								'<OPTION value="Caller Not the Account Holder">Caller Not the Account Holder</OPTION>'+
								'<OPTION value="No Money">No Money / Fixed Income</OPTION>'+
								'<OPTION value="Technical Issue - IVR, CTSF">Technical Issue - IVR, CTSF</OPTION>'+
								'<OPTION value="Customer will Callback">Customer will Callback</OPTION>'+
								'<OPTION value="Customer thought Service Is Free">Customer thought Service Is Free</OPTION>'+
								'<OPTION value="Inquiry Call">Inquiry Call</OPTION>'+
								'<OPTION value="Call Disconnected">Call Disconnected</OPTION>'+
								'<OPTION value="Issue not Supported">Issue not Supported(Non AT&T Issue)</OPTION>';
			var default_issues = '<option>Connectivity</option>'+
								 '<option>Application Support</option>'+
								 '<option>Operating System</option>'+
								 '<option>Hardware</option>'+
								 '<option>Virus</option>';
								 
			var nosaleopt = '<option value="none">No Sale</option>';					 
			var ghostopt = '<option>Ghost Call</option>';
			
			var ctt_services = '<option value="none">No Sale</option>'+
							   '<option value="spp">S+ ($15)</option>'+
							   '<option value="ea">EA ($49)</option>'+
							   '<option value="eae">EAE($149)</option>'+
							   '<option value="spphwp">S+ ($15) + HWP</option>'+
							   '<option value="eahwp">EA ($49) + HWP</option>'+
							   '<option value="eaehwp">EAE($149) + HWP</option>'+
							   '<option value="hwp">HWP</option>';
							   
			var misrouted_issues = '<OPTION selected value="Connectech Billing">Connectech Billing</OPTION>'+
								   '<OPTION value="Uverse Billing">Uverse Billing</OPTION>'+
								   '<OPTION value="UVerse Tech Support">UVerse Tech Support</OPTION>'+
								   '<OPTION value="DSL Billing">DSL Billing</OPTION>'+
								   '<OPTION value="DSL Tech Support">DSL Tech Support</OPTION>'+
								   '<OPTION value="In-Home Support">In-Home Support</OPTION>'+
								   '<OPTION value="Inquiry Call">Inquiry Call</OPTION>'+
								   '<OPTION value="ATT Mobility">ATT Mobility</OPTION>'+
								   '<OPTION value="New ATT Customer">New ATT Customer</OPTION>';
								   
								   
			var sppdeclineReason  = '<div class="row" id="spdecline">' +
			'<div class="col-md-3" id="sppdecline2"><p class="text-right"><strong>S+ Decline Reason: </strong></p></div>' +
								  '	<div class="col-md-4" id="spdeclinelist"><p class="text-left">' +
								  '		<select name="sppdecline" id="sppdecline" class="form-control">' + 
								  '			<option class="text-info">Price is too High ($180 per year)</option>' +
								  '			<option class="text-info">Not interested w/ 12mo. Contract</option>' +
								  '			<option class="text-info">Needs 1 time support Only</option>' +
								  '			<option class="text-info">Waived by Uverse</option>' +
								  '		</select></p>' +
								  '</div></div>';					   
								   
			var misroute_details = '<div class="row" id="m_transfer">'+
								   '	<div class="col-md-3"><p class="text-right"><strong>Misroute Transfer: </strong></p></div>'+
								   '		<div class="col-md-4"><p class="text-left">'+
								   '				<select class="form-control" name="misroute_transfer" id="misroute_transfer"> '+
								   '					<OPTION selected>Customer Dialled</OPTION>' +
								   '					<OPTION>Transfer from other Dept</OPTION>' +
								   '				</select>'+
								   '			</p>'+
								   '		</div>'+
								   '	</div>'+
								   '</div>'+
								   '<div class="row" id="m_num">'+
								   '	<div class="col-md-3"><p class="text-right"><strong>Dialed Num/Transfered From: </strong></p></div>'+
								   '		<div class="col-md-4"><p class="text-left">'+
								   '				<input type="text" class="form-control" name="misrouted_num">'+
								   '			</p>'+
								   '		</div>'+
								   '	</div>'+
								   '</div>';					   
			
			$('#calltype').on('change', function(){
					var calltype = $('#calltype').val();
					
					switch(calltype){
						case 'misrouted':{
							$("#spdecline").remove();
							$("#ticket").after(misroute_details);
							misrouted_option();
						}break;
						case 'tech':{
							$("#spdecline").remove();
							tech_option();
						}break;
						case 'ghost call':{
							$("#spdecline").remove();
							$("#m_transfer").remove();
							$("#m_num").remove();
							ghostcall();
						}break;
						case 'sales':
						default: {
							$("#spdecline").remove();
							sales_option();
						}break;
					}
				});
			
			$('#soldservice').on('change', function(){
					var soldservice = $('#soldservice').val();
					
					switch(soldservice){
						case 'hwp':
						case 'spphwp':
						case 'spp':{
							$('#nosale').html('<OPTION selected value="none">None</OPTION>');
							$("#spdecline").remove();
						}break;
						case "ea":
						case "eae":
						case "eahwp":
						case "eaehwp":{
							showSPdecline();
						}break;
						case 'none':
						default: {
							$('#nosale').html(nosaleReasons);
							$("#spdecline").remove();
						}break;
					}
				});
					
				
			function misrouted_option(){
				$('#issue').html(misrouted_issues);
				$('#soldservice').html(nosaleopt);
				$('#nosale').html('<option>Misrouted</option>');
			}	
			function tech_option(){
				$('#issue').html(default_issues);
				$('#soldservice').html(nosaleopt);
				$('#nosale').html('<option>CT Subscriber</option>');
			}
				
			function sales_option(){
				$('#issue').html(default_issues);
				$('#soldservice').html(ctt_services);
				$('#nosale').html(nosaleReasons);
			}
			
			function ghostcall(){
				$('#soldservice').html(nosaleopt);
				$('#nosale').html(ghostopt);
				$('#issue').html(ghostopt);
			}
			
			function showSPdecline(){
				$("#spdecline").remove();
				$('#nosale').html('<OPTION selected value="none">None</OPTION>');
				$("#soldservices").after(sppdeclineReason);
			}				   
								   			
		});
</script>
<?php
include( TEMPLATE_PATH . "/inc/footer.inc.php" );
?>
