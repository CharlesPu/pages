<?php 
class HomeModel{
	public function GetCompanyMsgs($nam){
		$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");
		// $db->query("set character_set_client=utf8");
		// $db->query("set character_set_results=utf8");

		$res = $db->query("SELECT * FROM Company");
		$results = array();
		while ( $row = $res->fetch_array()) {
			$results[]=$row;
		}
		$db->close();

		return $results;
	}
	public function GetCompanyNum($nam){
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');

		// $res=array();
		$res = $db->query("SELECT * FROM Company");
		$db->close();
		return $res->num_rows;
	}
}

 ?>