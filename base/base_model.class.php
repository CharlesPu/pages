<?php 
	class BaseModel {
		public function GetSessionId($name){
			$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf-8');

			$res=$db->query("SELECT * FROM User where User_Name = '{$name}'");
			$row=$res->fetch_array();
			$db->close();

			return $row[User_SessionID];
		}

	}
 ?>