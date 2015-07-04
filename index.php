<?php
date_default_timezone_set('Asia/Manila');
$time_ph = date('H:m a');
session_start();
require( "config.php" );

$caselogs = new Caselog();
$view = isset( $_GET['view'] ) ? $_GET['view'] : "";
$dates = $caselogs->getDates();

switch($view){
	case 'rank':{
		getRank();
	}break;
	case 'date':{
		DailyWithDate();
	}break;
	case 'export': {
		checkLogin();
		raw_export();
	}break;
	case 'addcase': {
		checkLogin();
		addcase();
	}break;
	case 'logout':{
		logout();
	}break;
	
	case 'login':{
		login();
	}break;
	
	case 'delete':{
		$page_title = "Caselogs";
		Caselog::deleteLog($_POST['id'], $_SESSION['fullname']);
		require( TEMPLATE_PATH . "/caselogs.php" );
	}break;
	
	case 'logs':{
		checkLogin();
		$page_title = "Caselogs";
		require( TEMPLATE_PATH . "/caselogs.php" );
	}break;
	
	
	case 'weekly':{
		$page_title = "Weekly Stats " .$dates['week_start'] .' to ' .$dates['week_ending'];
		$teams_weekly = $caselogs->getTeams('weekly');
		$stats = "<h4>Weekly Stats   (<strong>" .$dates['week_start'] .'</strong> to <strong>' .$dates['week_ending'] ."</strong>)</h4>";
		$stats .= "<div class=\"overall \"> \n";
		$stats .= createStatsTable($caselogs->getTeamStatsWeekly());
		$stats .= "</div> \n";
		foreach($teams_weekly as $team){
			$stats .= "<div class=\"teamwise \"> \n";
			$stats .= "<h4>Team " .$team ."</h4> \n";
			$stats .= createStatsTable($caselogs->getTeamStatsWeekly($team));
			$stats .= "</div> \n";
			}
		require( TEMPLATE_PATH . "/homepage.php" );
		}break;
		
		
	case 'monthly':{
		$page_title = $dates['monthName'] ." MTD Stats as of - ".date('F j, Y');
		$teams_weekly = $caselogs->getTeams('monthly');
		$stats = "<h4><strong>MTD Stats as of(".date('F j, Y') ."</strong>)</h4>";
		$stats .= createStatsTable($caselogs->getTeamStatsMonthly());
		foreach($teams_weekly as $team){
			$stats .= "<div class=\"teamwise \"> \n";
			$stats .= "<h4>Team " .$team ."</h4> \n";
			$stats .= createStatsTable($caselogs->getTeamStatsMonthly($team));
			$stats .= "</div> \n";
			}
		require( TEMPLATE_PATH . "/homepage.php" );
		}break;
		
	case 'eod': {
		$teams_today = $caselogs->getTeams();
		$stats = "<h4><strong>Hybrid EOD (" .date('F  j,  Y ').")</strong></h4>";
		$stats = "<h4><strong>Hybrid Stats " .date('F j, Y') ."</strong> as of <strong>".$time_ph ."</strong></h4>";
		$stats .= createStatsTable($caselogs->getTeamStatsDaily());
		$team_stats = $caselogs->getTeamStatsDaily();
		$total = $team_stats[(count($team_stats)-1)];
		$stats .= "<h4>Highlights:</h4> \n";
		$stats .= "<ul class=\"eod\"> \n";
		$stats .= "<li><strong>Handled ".$total['Calls']." Inbound Calls Sales Split</strong></li> \n";
		$stats .= "<li><strong>Sales Conversion ".$total['Conversion']."</strong></li> \n";
		$stats .= "<li><strong>Calls with zero Opportunity: " .sprintf('%.2f', ($total['No Opp']*100/$total['Calls']) ) ."%" ."</strong></li> \n";
		$stats .= "</ul> \n";
		foreach($teams_today as $team){
			$team_stats = $caselogs->getTeamStatsDaily($team);
			$stats .= "<div class=\"eod \"> \n";
			$stats .= "<br><h4><strong>Team " .$team ."</strong></h4> \n";
			$stats .= createStatsTable($team_stats);
			$total = $team_stats[(count($team_stats)-1)];
			$stats .= "<h4>Highlights:</h4> \n";
			$stats .= "<ul class=\"eod\"> \n";
			$stats .= "<li><strong>Handled ".$total['Calls']." Inbound Calls Sales Split</strong></li> \n";
			$stats .= "<li><strong>Sales Conversion ".$total['Conversion']."</strong></li> \n";
			$stats .= "<li><strong>Calls with zero Opportunity: " .sprintf('%.2f', ($total['No Opp']*100/$total['Calls']) ) ."%" ."</strong></li> \n";
			$stats .= "</ul> \n";
			$stats .= "</div> \n";
		}
		require( TEMPLATE_PATH . "/homepage.php" );
	}break;
	
	
	case 'email_conversion': {
		date_default_timezone_set('America/New_York'); // EST
		$teams_today = $caselogs->getTeams();
		$stats = "<h4><strong>Hybrid Sales Conversion as of " .date('h:00a')." EST</strong></h4>";
		$stats .= "<h4><strong>Overall</strong></h4>";
		$stats .= createEmailTable($caselogs->getTeamStatsDaily());
		$stats .= "<br /><br /> \n";
		foreach($teams_today as $t=>$team){
			$team_color = "style=" .'"' .$colors[$t] .'"';
			$team_stats = $caselogs->getTeamStatsDaily($team);
			$stats .= "<div> \n";
			$stats .= "<h4>Team " .$team ."</h4> \n";
			$stats .= createEmailTable($team_stats, $team_color);
			$stats .= "<br /><br /></div> \n";
			
		}
		$_SESSION['email_table'] = $stats;
		$stats .= '
		<form method="POST" action="test/phpmailtest2.php?conversion" >
		NT Login: <input type="text" name="email_user"><br/><br/>
		Email Address: <input type="text" name="email_add"><br/><br/>
		Email Password: <input type="password" name="email_passwd"><br/><br/>
		<input type="submit" value="send hourly conversion">
		</form>';
		require( TEMPLATE_PATH . "/homepage.php" );
	}break;
	
	case 'email_eod': {
		date_default_timezone_set('America/Los_Angeles'); // Pacific
		$teams_today = $caselogs->getTeams();
		$stats  = "Hi Team, <br><br>";
		$stats .= "Below is the Hybrid Team EOD for " .date('F  j,  Y ') ."<br><br>";
		$stats .= "<h4><strong>Hybrid Overall </strong></h4>";
		$stats .= createEmailTable($caselogs->getTeamStatsDaily());
		$team_stats = $caselogs->getTeamStatsDaily();
		$total = $team_stats[(count($team_stats)-1)];
		$stats .= "<h4 style=\"font-weight: bold;\">Highlights:</h4> \n";
		$stats .= "<ul> \n";
		$stats .= "<li><strong>Handled ".$total['Calls']." Inbound Calls Sales Split</strong></li> \n";
		$stats .= "<li><strong>Sales Conversion ".$total['Conversion']."</strong></li> \n";
		$stats .= "<li><strong>Calls with zero Opportunity: " .sprintf('%.2f', ($total['No Opp']*100/$total['Calls']) ) ."%" ."</strong></li> \n";
		$stats .= "</ul> \n";
		$stats .= "<br /><br /> \n";
		foreach($teams_today as $t=>$team){
			$team_color = "style=" .'"' .$colors[$t] .'"';
			$team_stats = $caselogs->getTeamStatsDaily($team);
			$stats .= "<div> \n";
			$stats .= "<h4 style=\"font-weight: bold;\">Team " .$team ."</h4> \n";
			$stats .= createEmailTable($team_stats, $team_color);
			$total = $team_stats[(count($team_stats)-1)];
			$stats .= "<h4 style=\"font-weight: bold;\">Highlights:</h4> \n";
			$stats .= "<ul class=\"eod\"> \n";
			$stats .= "<li><strong>Handled ".$total['Calls']." Inbound Calls Sales Split</strong></li> \n";
			$stats .= "<li><strong>Sales Conversion ".$total['Conversion']."</strong></li> \n";
			$stats .= "<li><strong>Calls with zero Opportunity: " .sprintf('%.2f', ($total['No Opp']*100/$total['Calls']) ) ."%" ."</strong></li> \n";
			$stats .= "</ul> \n";
			$stats .= "<br /><br /></div> \n";
		}
		$_SESSION['email_table'] = $stats;
		$stats .= '
		<form method="POST" action="test/phpmailtest2.php?conversion" >
		NT Login: <input type="text" name="email_user"><br/><br/>
		Email Address: <input type="text" name="email_add"><br/><br/>
		Email Password: <input type="password" name="email_passwd"><br/><br/>
		<input type="submit" value="send hourly conversion">
		</form>';
		require( TEMPLATE_PATH . "/homepage.php" );
	}break;
	
	
	
	case 'declines': {
		date_default_timezone_set('America/New_York'); // EST
		$teams_today = $caselogs->getTeams();
		$stats = "<h4><strong>Sales Decline as of " .date('h:00a')." " .date('F j, Y') ." EST</strong></h4>";
		$stats .= "<br />";
		$stats .= "<div class='col-xs-8'>";
		$stats .= getDeclines();
		$stats .= "</div>";

		require( TEMPLATE_PATH . "/homepage.php" );
	}break;
	
	
		
	case 'home': 
	default: {
		$teams_today = $caselogs->getTeams();
		$stats = "<h4><strong>Production Date: " .date('F j, Y') ."</strong><small> (CST)</small></h4>";
		$stats .= "<h4><strong>Hybrid Stats </strong> as of <strong>".$time_ph ."</strong><small> (PH time)</small></h4>";
		$stats .= createStatsTable($caselogs->getTeamStatsDaily());
		foreach($teams_today as $team){
			$stats .= "<div class=\"teamwise \"> \n";
			$stats .= "<h4>Team " .$team ."</h4> \n";
			$stats .= createStatsTable($caselogs->getTeamStatsDaily($team));
			$stats .= "</div> \n";
		}
		require( TEMPLATE_PATH . "/homepage.php" );
	}break;
}



function createEmailTable($data, $style='style="background-color: rgb(0, 102, 204); color: white; font-weight: bold;"'){
	$bordered = 'border: 1px solid black; padding: 5px; font-size: 12px;';
	$table = "<table style=\"$bordered width: 500px; border-collapse: collapse;\" > \n";
	$table .= "  <thead> \n";
	$table .= "    <tr $style> \n";
	$table .= "      <th style=\"$bordered\">Name</th> \n";
	$table .= "      <th style=\"$bordered\">Calls</th> \n";
	$table .= "      <th style=\"$bordered\">Sales Calls</th> \n";
	$table .= "      <th style=\"$bordered\">Sale</th> \n";
	$table .= "      <th style=\"$bordered\">Conversion</th> \n";
	$table .= "      <th style=\"$bordered\">ETF Rate</th> \n";
	$table .= "    </tr> \n";
	$table .= "  </thead> \n";
	
	$table .= "  <tbody> \n";
	foreach($data as $d){
		$name = isset($d['Name']) ? $d['Name'] : $d['Team'];
		$header_color = ($name == 'Total') ? "    <tr style=\"background-color: rgb(0, 102, 204); color: white; font-weight: bold;\"> \n" : "    <tr> \n"; 
		$table .= $header_color; //"    <tr> \n";
		$table .= "      <td style=\"$bordered\">".$name ."</td> \n";
		$table .= "      <td style=\"$bordered\">".$d['Calls'] ."</td> \n";
		$table .= "      <td style=\"$bordered\">".$d['Sales Calls'] ."</td> \n";
		$table .= "      <td style=\"$bordered\">".$d['Sales'] ."</td> \n";
		$table .= "      <td style=\"$bordered\">".$d['Conversion'] ."</td> \n";
		$table .= "      <td style=\"$bordered\">".$d['ETF Rate'] ."</td> \n";
		$table .= "    </tr> \n";
	}
	$table .= "  </tbody> \n";
	$table .= "</table> \n";
	return $table;
}

function createStatsTable($data){
	$table = "<table class=\"table table-condensed  table-bordered stats \"> \n";
	$table .= "  <thead> \n";
	$table .= "    <tr> \n";
	$table .= "      <th>Name</th> \n";
	$table .= "      <th>Calls</th> \n";
	$table .= "      <th>Sales Calls</th> \n";
	$table .= "      <th>Sale</th> \n";
	$table .= "      <th>Conversion</th> \n";
	$table .= "      <th>ETF Rate</th> \n";
	$table .= "    </tr> \n";
	$table .= "  </thead> \n";
	
	$table .= "  <tbody> \n";
	foreach($data as $d){
		$name = isset($d['Name']) ? $d['Name'] : $d['Team'];
		$table .= "    <tr> \n";
		$table .= "      <td>".$name ."</td> \n";
		$table .= "      <td>".$d['Calls'] ."</td> \n";
		$table .= "      <td>".$d['Sales Calls'] ."</td> \n";
		$table .= "      <td>".$d['Sales'] ."</td> \n";
		$table .= "      <td>".$d['Conversion'] ."</td> \n";
		$table .= "      <td>".$d['ETF Rate'] ."</td> \n";
		$table .= "    </tr> \n";
	}
	$table .= "  </tbody> \n";
	$table .= "</table> \n";
	return $table;
}


function createStatsTable2($data){
	$table = "<table> \n";
	$table .= "  <thead> \n";
	$table .= "    <tr> \n";
	$table .= "      <th>Name</th> \n";
	$table .= "      <th>Calls</th> \n";
	$table .= "      <th>Sales Calls</th> \n";
	$table .= "      <th>Sale</th> \n";
	$table .= "      <th>Conversion</th> \n";
	$table .= "      <th>ETF Rate</th> \n";
	$table .= "    </tr> \n";
	$table .= "  </thead> \n";
	
	$table .= "  <tbody> \n";
	foreach($data as $d){
		$name = isset($d['Name']) ? $d['Name'] : $d['Team'];
		$table .= "    <tr> \n";
		$table .= "      <td>".$name ."</td> \n";
		$table .= "      <td>".$d['Calls'] ."</td> \n";
		$table .= "      <td>".$d['Sales Calls'] ."</td> \n";
		$table .= "      <td>".$d['Sales'] ."</td> \n";
		$table .= "      <td>".$d['Conversion'] ."</td> \n";
		$table .= "      <td>".$d['ETF Rate'] ."</td> \n";
		$table .= "    </tr> \n";
	}
	$table .= "  </tbody> \n";
	$table .= "</table> \n";
	return $table;
}

function createLogsTable($data){
	$table = 'No Logs for today. ';
	if(!empty($data)){
		$hide = ['log_id','createdby','misrouted_num','misroute_transfer','resolved','ivr','datecreated'];
		$table = "<table class=\"table table-condensed  table-bordered logs \"> \n";
		$table .= "  <thead> \n";
		$header = array_keys($data[0]);
		$table .= "    <tr class=\"green\"> \n";
		$table .= "      <th>Calls</th> \n";
		foreach($header as $h){
			if(!in_array($h,$hide)){
				$table .= "      <th>" .$h ."</th> \n";
			}
		
		}
		
		$table .= "      <th>Delete</th> \n";
		$table .= "    </tr> \n";
		$table .= "  </thead> \n";
	
		$table .= "  <tbody> \n";
	$count = 1;
		foreach($data as $dat){
			$table .= "    <tr> \n";
			$table .= "      <td>".$count ."</td> \n";
			foreach($dat as $c=>$d){
				if(!in_array($c,$hide)){
					$table .= "      <td>".$d ."</td> \n";
				}
			}
			$table .= "      <td> \n";
			$table .= "        <form method=\"POST\" action=\"?view=delete\"> \n";
			$table .= "          <input class=\"hidden\" type=\"hidden\" name=\"id\" value=\"" .$dat['log_id'] ."\"> \n";
			$table .= "          <input class=\"btn btn-danger\" type=\"submit\" value=\"delete\"> \n";
			$table .= "        </form> \n";
			$table .= "      </td> \n";
			$table .= "    </tr> \n";
	$count++;
		}
		$table .= "  </tbody> \n";
		$table .= "</table> \n";
	}
	
	return $table;
}

function login(){
	    $_GET['login'] = isset($_GET['login']) ? $_GET['login'] : '';
		if($_GET['login']=='submit'){
			$username = isset($_POST['ctt_username']) ? $_POST['ctt_username'] : '';
			$password = isset($_POST['ctt_password']) ? $_POST['ctt_password'] : '';
			$q_login = "SELECT * FROM users WHERE username = :user AND password = password(:pass) ";
			$db = new BaseDB();
			$login = $db->secure_query($q_login, array(':user'=>$username,':pass'=>$password) );
			$creds = $login->fetchAll(PDO::FETCH_ASSOC);
			if(empty($creds)){
				$msg = 'error loging in';
				$_GET['login_error'] = 1;
				$_GET['login'] = 1;
			}else{
				$_SESSION['username'] = $creds[0]['username'];
				$_SESSION['fullname'] = $creds[0]['fullname'];
				$_SESSION['team_manager'] = $creds[0]['manager'];
				$_SESSION['role'] = $creds[0]['role'];
				header('Location: ?view=home');
				exit;
			}
		}
		require( TEMPLATE_PATH . "/login-form.php" );
}


function logout(){
	$_SESSION = array();
	session_destroy();
	header('Location: .');
	exit;
}

function checkLogin(){
	if(!isset($_SESSION['username'])){
		header('Location: ?view=login');
		exit;
	}
}


function addcase(){
	$msg = '';
	$_GET['addcase'] = isset($_GET['addcase']) ? $_GET['addcase'] : '';
	if($_GET['addcase']=='submit'){
		$techname = $_SESSION['fullname'];
		$btn = $_POST['btn'];
		$calltype = $_POST['calltype'];
		$soldservice = $_POST['soldservice'];
		$nosale = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($_POST['nosale'])))))); //strip_tags($_POST['nosale']); //
		if(!isset($_POST['sppdecline'])){
			$sppdecline = 'None';
		}else{
			$sppdecline = $_POST['sppdecline'];
		}
		$issue = $_POST['issue'];
		$ticket = $_POST['ticket'];
		if(isset($_POST['misrouted_num'])){
			$misrouted_num = $_POST['misrouted_num'];
			$misroute_transfer = $_POST['misroute_transfer'];
		}else{ 
			$misrouted_num = "";
			$misroute_transfer = "";
		}

		$resolved = $_POST['Resolved'];
		$ivr = $_POST['ivr'];
		
		//process database query
		$query = "INSERT INTO `caselogs` (
		`log_id`, 
		`createdby`, 
		`btn`, 
		`calltype`, 
		`soldservice`, 
		`nosale`,
		`sppdecline`,
		`issue`, 
		`ticket`, 
		`misrouted_num`,
		`misroute_transfer`,
		`resolved`, 
		`ivr`,
		`datecreated`, 
		`timecreated`) 
	VALUES (
		NULL, 
		:name, 
		:btn, 
		:calltype, 
		:soldservice, 
		:nosale, 
		:sppdecline,
		:issue, 
		:ticket, 
		:misrouted_num,
		:misroute_transfer,
		:resolved, 
		:ivr,
		:datecreated, 
		:timecreated );
		";
		
		$datecreated = date('Y-m-d');
		$timecreated = date('H:i:s');
		$params = array(':name'=>$techname, 
						':btn'=>$btn,
						':calltype'=>$calltype, 
						':soldservice'=>$soldservice, 
						':nosale'=>$nosale, 
						':sppdecline'=>$sppdecline,
						':issue'=>$issue, 
						':ticket'=>$ticket, 
						':misrouted_num'=>$misrouted_num,
						':misroute_transfer'=>$misroute_transfer,
						':resolved'=>$resolved, 
						':ivr'=>$ivr,
						':datecreated'=>$datecreated, 
						':timecreated'=>$timecreated);
		
		$db = new BaseDB();
		$db->secure_query($query,$params);
		$msg = '<label class="label label-md label-success">Case Logged Successfully</label><br>';
	}
	require( TEMPLATE_PATH . "/addcase.php" );
}

function raw_export(){
	if($_GET['view']=='export' && isset($_GET['submit'])){
		$date_start = $_POST['start_date'];
		$date_end = $_POST['end_date'];
		$db = new BaseDB();
		$q = $db->secure_query("DESCRIBE caselogs_with_team");
		$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
		
		$query = "SELECT * FROM caselogs_with_team WHERE datecreated BETWEEN :start AND :end ";
		
		$result = $db->secure_query($query, array(':start'=>$date_start,':end'=>$date_end))->fetchAll(PDO::FETCH_ASSOC);
	
		$csv = '';
		
		foreach($table_fields as $field){
			$csv .= $field .",";
		}$csv .= "\n";
		foreach($result as $res){
			foreach($res as $r){
				$csv .= '"' .$r .'"' .','; 
			}$csv .= "\n";
		}
		$filename = "export.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		echo $csv;
		exit;
	}else{
		require( TEMPLATE_PATH . "/export.php" );
	}
}

function testSpeed(){
	$start = microtime(true);
	//script
	$end = microtime(true);
	$time = $end - $start;
	echo('script took ' . $time . ' seconds to execute.');
}


function DailyWithDate(){
	global $time_ph;
	$caselogs = new Caselog();
	
	$date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d', strtotime('yesterday'));
		$stats ="<form method=\"POST\" action=?view=date><input type=\"text\" name=\"date\" value=\"{$date}\" id=\"previousDate\"><input type=\"submit\" value=\"Generate\"></form>";
		$teams_today = $caselogs->getTeams('daily',$date);
		$stats .= "<h4><strong>Production Date: " .date('F j, Y',strtotime($date)) ."</strong><small> (CST)</small></h4>";
		//$stats .= "<h4><strong>Hybrid Stats </strong> as of <strong>".$time_ph ."</strong><small> (PH time)</small></h4>";
		$stats .= createStatsTable($caselogs->getTeamStatsDaily('',$date));
		$team_stats = $caselogs->getTeamStatsDaily('',$date);
		$total = $team_stats[(count($team_stats)-1)];
		$stats .= "<h4>Highlights:</h4> \n";
		$stats .= "<ul class=\"eod\"> \n";
		$stats .= "<li><strong>Handled ".$total['Calls']." Inbound Calls Sales Split</strong></li> \n";
		$stats .= "<li><strong>Sales Conversion ".$total['Conversion']."</strong></li> \n";
		$stats .= "<li><strong>Calls with zero Opportunity: " .sprintf('%.2f', ($total['No Opp']*100/$total['Calls']) ) ."%" ."</strong></li> \n";
		$stats .= "</ul> \n";
		foreach($teams_today as $team){
			$stats .= "<div class=\"eod \"> \n";
			$stats .= "<h4>Team " .$team ."</h4> \n";
			$team_stats = $caselogs->getTeamStatsDaily($team, $date);
			$stats .= createStatsTable($team_stats);
			$total = $team_stats[(count($team_stats)-1)];
			$stats .= "<h4>Highlights:</h4> \n";
			$stats .= "<ul class=\"eod\"> \n";
			$stats .= "<li><strong>Handled ".$total['Calls']." Inbound Calls Sales Split</strong></li> \n";
			$stats .= "<li><strong>Sales Conversion ".$total['Conversion']."</strong></li> \n";
			$stats .= "<li><strong>Calls with zero Opportunity: " .sprintf('%.2f', ($total['No Opp']*100/$total['Calls']) ) ."%" ."</strong></li> \n";
			$stats .= "</ul> \n";
			$stats .= "</div> \n";
			$stats .= '<script type="text/javascript">
			$(document).ready(function(){
				$("#previousDate").datepicker( {dateFormat: "yy-mm-dd"} );
			});
				
				
	
</script>';
			$stats .= " \n";
		}
		require( TEMPLATE_PATH . "/homepage.php" );
}



function getRank(){
	global $time_ph;
	$caselogs = new Caselog();
	$date = $caselogs->getDates()['date'];
	$stats = "<h4>Sales Conversion as of <strong>".date('F j, Y',strtotime($date)) ."</strong></h4>";
	$stats .= createStatsTable($caselogs->getRank());
	$caselogs = null;
	require( TEMPLATE_PATH . "/homepage.php" );
}

function create_Table($data=[], $headers=[]){
	if(!empty($data)){
		$table = "";
		$table .= "<table class='table table-bordered table-condensed'>";
		if(!empty($headers)){
			$table .= "<thead><tr class='bg-primary'>";
			foreach($headers as $h){
				$table .= "<th>" .$h ."</th>";
			}
			$table .= "</tr></thead>";
		}
		
		$table .= "<tbody>";
		foreach($data as $da){
			$table .= "<tr>";
			foreach($da as $d){
				$table .= "<td>" .$d ."</td>";
			}
			$table .= "</tr>";
		}
		$table .= "</tbody>";
		
		$table .= "</table>";
		return $table;
	}
	else{
		return "Empty Table!";
	}
}

function getDeclines(){
	$caselogs = new Caselog();
	$declines = $caselogs->getDeclines();
	$declines[] = ['#'=>'', 'manager'=>'','createdby'=>'','nosale'=>'','issue'=>''];
	$head = ['#', 'manager','createdby','nosale','issue' ];
	$table = create_Table($declines, $head);
	return $table;//$caselogs->getDeclines();
}


?>
