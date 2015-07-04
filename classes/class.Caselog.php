<?php
/*
* Main Caselog Class
* CRUD access for caselogs
*/
require_once('class.BaseDB.php');

class Caselog{
	protected $_date;
	protected $_weekStart; //sunday
	protected $_weekEnding; //saturday
	protected $_month;	//month in format Y-m-% for 
	
	const Q_STATS = "SELECT *, IF(Sales=0,'', CONCAT(FORMAT(Sales*100/Calls,2),'%') )as Conversion, 
					 IF(`SPP sales`=0,'', CONCAT (FORMAT( (`SPP sales`/`CT sales`)*100,2),'%') ) AS `ETF Rate` FROM
						(SELECT 
							createdby as Name,
							count(*)as Calls,
							sum(CASE WHEN calltype='sales' THEN 1 ELSE 0 END)as `Sales Calls`, 
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1			
									 WHEN soldservice='HWP' THEN 1		
									 WHEN soldservice='SPPHWP' THEN 2		
									 WHEN soldservice='EAHWP' THEN 2		
									 WHEN soldservice='EAEHWP' THEN 2 ELSE 0 END)as Sales,
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1					
			 						 WHEN soldservice='SPPHWP' THEN 1		
			 						 WHEN soldservice='EAHWP' THEN 1		
			 						 WHEN soldservice='EAEHWP' THEN 1 ELSE 0 END)as `CT sales` ,
							SUM(CASE WHEN soldservice='SPP' THEN 1
									 WHEN soldservice='SPPNOETF' THEN 1
			 						 WHEN soldservice='SPPHWP' THEN 1 ELSE 0 END)as `SPP sales`,
			 				SUM(CASE WHEN calltype='misrouted' or calltype='ghost call' THEN 1 ELSE 0 END) AS `No Opp` 
						FROM caselogs_with_team
						WHERE {datecreated} AND 
						manager = :manager
						GROUP BY createdby)as D
						ORDER BY Sales/Calls DESC
						";
		
	const Q_TOTAL = "SELECT * , 
					 IF(Sales=0,'', CONCAT(FORMAT(Sales*100/Calls,2),'%') )as Conversion, 
					 IF(`SPP sales`=0,'', CONCAT (FORMAT( (`SPP sales`/`CT sales`)*100,2),'%') ) AS `ETF Rate` FROM
				(SELECT Name, SUM(Calls)as Calls, SUM(Sales)as Sales, SUM(`Sales Calls`)as `Sales Calls`, SUM(`CT sales`) as `CT sales`, SUM(`SPP sales`)as `SPP sales`, SUM(`No Opp`)as `No Opp` FROM
						(SELECT
							'Total' as Name,
							count(*)as Calls,
							sum(CASE WHEN calltype='sales' THEN 1 ELSE 0 END)as `Sales Calls`, 
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EAE' THEN 1			
									 WHEN soldservice='HWP' THEN 1		
									 WHEN soldservice='SPPHWP' THEN 2		
									 WHEN soldservice='EAHWP' THEN 2		
									 WHEN soldservice='EAEHWP' THEN 2 ELSE 0 END)as Sales,
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1					
			 						 WHEN soldservice='SPPHWP' THEN 1		
			 						 WHEN soldservice='EAHWP' THEN 1		
			 						 WHEN soldservice='EAEHWP' THEN 1 ELSE 0 END)as `CT sales` ,
							SUM(CASE WHEN soldservice='SPP' THEN 1	
									 WHEN soldservice='SPPNOETF' THEN 1	
			 						 WHEN soldservice='SPPHWP' THEN 1 ELSE 0 END)as `SPP sales`,
			 				SUM(CASE WHEN calltype='misrouted' or calltype='ghost call' THEN 1 ELSE 0 END) AS `No Opp`  
						FROM caselogs_with_team
						WHERE {datecreated} AND 
						manager = :manager)as D
						ORDER BY Sales/Calls DESC
						)as E
						"; 
						
	const Q_STATS_OVERALL = "SELECT *, 
					 IF(Sales=0,'', CONCAT(FORMAT(Sales*100/Calls,2),'%') )as Conversion, 
					 IF(`SPP sales`=0,'', CONCAT (FORMAT( (`SPP sales`/`CT sales`)*100,2),'%') ) AS `ETF Rate` FROM
						(SELECT 
							manager as Team,
							count(*)as Calls,
							sum(CASE WHEN calltype='sales' THEN 1 ELSE 0 END)as `Sales Calls`, 
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EAE' THEN 1			
									 WHEN soldservice='HWP' THEN 1		
									 WHEN soldservice='SPPHWP' THEN 2		
									 WHEN soldservice='EAHWP' THEN 2		
									 WHEN soldservice='EAEHWP' THEN 2 ELSE 0 END)as Sales,
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1					
			 						 WHEN soldservice='SPPHWP' THEN 1		
			 						 WHEN soldservice='EAHWP' THEN 1		
			 						 WHEN soldservice='EAEHWP' THEN 1 ELSE 0 END)as `CT sales` ,
							SUM(CASE WHEN soldservice='SPP' THEN 1
									 WHEN soldservice='SPPNOETF' THEN 1	
			 						 WHEN soldservice='SPPHWP' THEN 1 ELSE 0 END)as `SPP sales`,
			 				SUM(CASE WHEN calltype='misrouted' or calltype='ghost call' THEN 1 ELSE 0 END) AS `No Opp`  
						FROM caselogs_with_team
						WHERE {datecreated} 
						GROUP BY manager)as D
						ORDER BY Sales/Calls DESC
						";
		
	const Q_TOTAL_OVERALL = "SELECT * , 
					 IF(Sales=0,'', CONCAT(FORMAT(Sales*100/Calls,2),'%') )as Conversion, 
					 IF(`SPP sales`=0,'', CONCAT (FORMAT( (`SPP sales`/`CT sales`)*100,2),'%') ) AS `ETF Rate` FROM
				(SELECT Team, SUM(Calls)as Calls, SUM(Sales)as Sales, SUM(`Sales Calls`)as `Sales Calls`, SUM(`CT sales`) as `CT sales`, SUM(`SPP sales`)as `SPP sales`, SUM(`No Opp`)as `No Opp` FROM
						(SELECT
							'Total' as Team,
							count(*)as Calls,
							sum(CASE WHEN calltype='sales' THEN 1 ELSE 0 END)as `Sales Calls`, 
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1			
									 WHEN soldservice='HWP' THEN 1		
									 WHEN soldservice='SPPHWP' THEN 2		
									 WHEN soldservice='EAHWP' THEN 2		
									 WHEN soldservice='EAEHWP' THEN 2 ELSE 0 END)as Sales,
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1					
			 						 WHEN soldservice='SPPHWP' THEN 1		
			 						 WHEN soldservice='EAHWP' THEN 1		
			 						 WHEN soldservice='EAEHWP' THEN 1 ELSE 0 END)as `CT sales` ,
							SUM(CASE WHEN soldservice='SPP' THEN 1	
									 WHEN soldservice='SPPNOETF' THEN 1	
			 						 WHEN soldservice='SPPHWP' THEN 1 ELSE 0 END)as `SPP sales`,
			 				SUM(CASE WHEN calltype='misrouted' or calltype='ghost call' THEN 1 ELSE 0 END) AS `No Opp`  
						FROM caselogs_with_team
						WHERE {datecreated} )as D
						ORDER BY Sales/Calls DESC
						)as E
						"; 					
						
	const Q_RANK = "SELECT *, IF(Sales=0,'', CONCAT(FORMAT(Sales*100/Calls,2),'%') )as Conversion, 
					 IF(`SPP sales`=0,'', CONCAT (FORMAT( (`SPP sales`/`CT sales`)*100,2),'%') ) AS `ETF Rate` FROM
						(SELECT 
							createdby as Name,
							count(*)as Calls,
							sum(CASE WHEN calltype='sales' THEN 1 ELSE 0 END)as `Sales Calls`, 
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1			
									 WHEN soldservice='HWP' THEN 1		
									 WHEN soldservice='SPPHWP' THEN 2		
									 WHEN soldservice='EAHWP' THEN 2		
									 WHEN soldservice='EAEHWP' THEN 2 ELSE 0 END)as Sales,
							SUM(CASE WHEN soldservice='SPP' THEN 1 		
									 WHEN soldservice='SPPNOETF' THEN 1		
									 WHEN soldservice='EA' THEN 1		
									 WHEN soldservice='EAE' THEN 1					
			 						 WHEN soldservice='SPPHWP' THEN 1		
			 						 WHEN soldservice='EAHWP' THEN 1		
			 						 WHEN soldservice='EAEHWP' THEN 1 ELSE 0 END)as `CT sales` ,
							SUM(CASE WHEN soldservice='SPP' THEN 1	
									 WHEN soldservice='SPPNOETF' THEN 1	
			 						 WHEN soldservice='SPPHWP' THEN 1 ELSE 0 END)as `SPP sales`,
			 				SUM(CASE WHEN calltype='misrouted' or calltype='ghost call' THEN 1 ELSE 0 END) AS `No Opp` 
						FROM caselogs_with_team
						WHERE datecreated like :month
						GROUP BY createdby)as D
						ORDER BY Sales/Calls DESC
						";
						
	
	const Q_TEAMS = "SELECT manager FROM caselogs_with_team WHERE {datecreated} GROUP BY manager";
	
	
	
	public function __construct(){
		$this->setDates();
	}
	
	private function setDates(){
		$this->_date = date('Y-m-d');
		$this->_month = date('Y-m-%');
		$this->_monthName = date('F');
			$sunday = strtotime('sunday', strtotime('previous sunday'));
			$saturday = strtotime('next saturday', strtotime('previous sunday'));
		$this->_weekStart = date('Y-m-d',$sunday);
		$this->_weekEnding = date('Y-m-d',$saturday);
	}
	
	function getIndividualLog($createdby, $date=''){
		if(empty($date)){
			$date = $this->_date;
		}
		$query = "SELECT * from caselogs where createdby = :createdby AND datecreated = :datecreated ";
		$params = array(":createdby"=>$createdby, ":datecreated"=>$date) ;
		$db = new BaseDB();
		$res = $db->secure_query($query, $params);
		$logs = $res->fetchAll(PDO::FETCH_ASSOC);
		$db = null;
		
		return $logs;
	}
	
	/*
	*	mode daily, weekly, monthly, 
	*/
	function getTeams($mode='daily', $date='',  $weekStart='', $weekEnding='', $month=''){
		if(empty($month)){
			$month = $this->_month;
		}
		if(empty($weekStart)){
			$weekStart = $this->_weekStart;
		}
		if(empty($weekEnding)){
			$weekEnding = $this->_weekEnding;
		}
		if(empty($date)){
			$date = $this->_date;
		}
		
		
		switch($mode){
			case 'weekly':
				{
					$q_stats = str_replace('{datecreated}', ' datecreated BETWEEN :weekStart AND :weekEnding ', Caselog::Q_TEAMS);
					$params = array(":weekStart"=>$weekStart, ":weekEnding"=>$weekEnding) ;
				}break;
			case 'monthly':
				{
					$q_stats = str_replace('{datecreated}', 'datecreated LIKE :datecreated', Caselog::Q_TEAMS);
					$params = array(":datecreated"=>$month) ;
				}break;
			case 'daily':
			default:
				{
					$q_stats = str_replace('{datecreated}', 'datecreated = :datecreated', Caselog::Q_TEAMS);
					$params = array(":datecreated"=>$date) ;
				}break;
			
		}
		
		$db = new BaseDB();
		$res = $db->secure_query($q_stats, $params);
		$t = $res->fetchAll(PDO::FETCH_ASSOC);
		foreach($t as $team){
			$teams[] = $team['manager'];
		}
		$db = null;				
		
		return isset($teams) ? $teams : [];
	}
	
	protected function getTeamStats($team='', $mode='daily', $date='',  $weekStart='', $weekEnding='', $month=''){
		if(empty($month)){
			$month = $this->_month;
		}
		if(empty($weekStart)){
			$weekStart = $this->_weekStart;
		}
		if(empty($weekEnding)){
			$weekEnding = $this->_weekEnding;
		}
		if(empty($date)){
			$date = $this->_date;
		}
		if(empty($team)){
			$team = 'overall';
		}
		
		$stats = $team=='overall' ? Caselog::Q_STATS_OVERALL : Caselog::Q_STATS;
		$total = $team=='overall' ? Caselog::Q_TOTAL_OVERALL : Caselog::Q_TOTAL;
		
		switch($mode){
			case 'weekly':
				{
					$q_stats = str_replace('{datecreated}', ' datecreated BETWEEN :weekStart AND :weekEnding ', $stats);
					$q_total = str_replace('{datecreated}', ' datecreated BETWEEN :weekStart AND :weekEnding ', $total);
					$params = array(":manager"=>$team, ":weekStart"=>$weekStart, ":weekEnding"=>$weekEnding) ;
				}break;
			case 'monthly':
				{
					$q_stats = str_replace('{datecreated}', 'datecreated LIKE :datecreated', $stats);
					$q_total = str_replace('{datecreated}', 'datecreated LIKE :datecreated', $total);
					$params = array(":manager"=>$team, ":datecreated"=>$month) ;
				}break;
			case 'daily':
			default:
				{
					$q_stats = str_replace('{datecreated}', 'datecreated = :datecreated', $stats);
					$q_total = str_replace('{datecreated}', 'datecreated = :datecreated', $total);
					$params = array(":manager"=>$team, ":datecreated"=>$date) ;
				}break;
			
		}
		
		if($team=='overall'){
			unset($params[':manager']);
		}
		
		
		$db = new BaseDB();
		
		
		$res = $db->secure_query($q_stats, $params);
		$stats = $res->fetchAll(PDO::FETCH_ASSOC);
		
		
		$res = $db->secure_query($q_total, $params);
		$stats[] = $res->fetchAll(PDO::FETCH_ASSOC)[0];
		$db = null;				
		
		return $stats;				
		
		
	}
	
	
	function getTeamStatsDaily($team='', $date='' ){
		return $this->getTeamStats($team, $mode='daily', $date, $weekStart='', $weekEnding='', $month='');			
	}
	
	function getTeamStatsWeekly($team='',  $weekStart='', $weekEnding=''){
		return $this->getTeamStats($team, $mode='weekly', $date='', $weekStart, $weekEnding, $month='');	
	}
	
	function getTeamStatsMonthly($team='', $month='' ){
		return $this->getTeamStats($team, $mode='monthly', $date='', $weekStart='', $weekEnding='', $month);	
	}
	
	static function deleteLog($id, $createdby){
		$db = new BaseDB();
		$del = $db->secure_query("DELETE FROM `caselogs` WHERE log_id= :id and createdby= :createdby ", array(":id"=>$id, ":createdby"=>$createdby));
		$db = null;	
	}
	
	function getDates(){
		return array('date'=>$this->_date, 'week_start'=>$this->_weekStart, 'week_ending'=>$this->_weekEnding, 'month'=>$this->_month, 'monthName'=>$this->_monthName);
	}
	
	function getRank($month=''){
		if(empty($month)){
			$month = $this->_month;
		}
		$db = new BaseDB();
		$ranking = $db->secure_query(self::Q_RANK, array(':month'=>$month))->fetchAll(PDO::FETCH_ASSOC);
		$db = null;
		
		$ranking[] = array('Name'=>'','Calls'=>'','Sales Calls'=>'','Sales'=>'','CT sales'=>'','SPP sales'=>'','No Opp'=>'','Conversion'=>'','ETF Rate'=>'');
		return $ranking;
	}
	
	
	function getDeclines(){
	//"SELECT * FROM `caselogs_with_team` WHERE datecreated='2015-06-27' and calltype='Sales' and soldservice='none' and manager like '%babata%' ";
	$db = new BaseDB();
	$q_declines = "SELECT @i:=@i+1 AS `#`, d.* from 
(SELECT manager, createdby, nosale, issue FROM `caselogs_with_team` WHERE datecreated=:date and calltype='Sales' and soldservice='none' ORDER BY manager, createdby)d, (SELECT @i:=0) foo ";
	$params = array(':date'=>$this->_date);
	$res = $db->secure_query($q_declines, $params)->fetchAll(PDO::FETCH_ASSOC);
	$db = null;
	return $res;
}
}



?>
