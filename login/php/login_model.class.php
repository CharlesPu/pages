<?php 
	class LoginModel {
		public function CheckAdmin($name,$pswd) {
			$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');
			$res=$db->query("SELECT * from User where User_Name='".$name."' and User_Password='".$pswd."'");	
			if ($res == null ) return 1;
			$row=$res->fetch_array();
			$ret = 0;
			
			if ( $row == null) $ret = 1; 
			else $ret = 0;
			$db->close();
			return $ret;
		}
		public function SetSessionId($name,$pswd,$sessid){
			$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');

			$r=$db->query("UPDATE User SET User_SessionID = '".$sessid."' WHERE User_Name='".$name."' and User_Password='".$pswd."'");
			$db->close();
		}
		public function SetLoginTimeIP($name) {
			$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');
			$datetime = date("Y/m/d h:i:sa");
			$ip = getenv('REMOTE_ADDR');

			$r=$db->query("UPDATE User SET User_LoginTime = '".$datetime."', User_LoginIP = '".$ip."' WHERE User_Name='".$name."'");
			$db->close();
		}
		public function GetUserType($name){
			$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');

			$r = $db->query("SELECT User_Type from User where User_Name='".$name."'");

			$row = $r->fetch_array();
			$db->close();
			$ret = "";
			if ($row[0] == "administrator") {
				$ret = "admin";
			}else if ($row[0] == "firm") {
				$ret = "firm";
			}else if ($row[0] == "certification") {
				$ret = "certific";
			}
			
			return $ret;
		}
		public function GetCompanyID($nam) {
			$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf-8');
			$db->query("set names 'utf8'");

			$res = $db->query("SELECT User_CompanyID FROM User WHERE User_Name = '".$nam."'");
			$row = $res->fetch_array();

			$db->close();

			return $row[0];
		}
	};
?>