<?php
/* 
* Base database class for access and query
*/
//require_once('conf.php');

class BaseDB extends PDO{
	public function __construct($options=null){
		parent::__construct(DB_DSN, DB_USER, DB_PASSWORD);
	}
	
	/*
	* @param string $query SQL Query 
	* @param array $params Parameters for Prepared Statement
	*/
	public function secure_query($query, $params=''){
		$res = parent::prepare($query);
		if(empty($params)){
			$res->execute();
		}else{
			$res->execute($params);
		}
		return $res;
	}
}

?>