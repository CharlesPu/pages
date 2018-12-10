<?php 
class HomeModel{
	public function GetCompanyMsgs(){
		$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		// $db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res = $db->query("SELECT * FROM Company");
		$results = array();
		while ( $row = $res->fetch_array()) {
			$results[]=$row;
		}
		$db->close();
		$ret = array();
		for($x = 0; $x < count($results); $x++) {
		  $ret[$x][co_id] 	= $results[$x][Company_ID];
		  $ret[$x][co_name] = $results[$x][Company_Name];
		  $ret[$x][co_desc] = $results[$x][Company_Description];
		}

		return $ret;
	}
}
?>