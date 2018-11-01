<?php 
	class LoginModel {
		public function CheckAdmin($name,$pswd) {
			$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');
			$res=$db->query("SELECT * from User where User_name='".$name."' and User_password='".$pswd."'");	
			if ($res == null ) return 1;
			$row=$res->fetch_array();
			$ret = 0;
			
			if ( $row == null) $ret = 1; 
			else $ret = 0;
			$db->close();
			return $ret;
		}
		public function ChangeSessionId($name,$pswd,$sessid){
			$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');

			$r=$db->query("UPDATE User SET User_session_id = '".$sessid."' WHERE User_name='".$name."' and User_password='".$pswd."'");
			$db->close();
		}
		public function SetLoginTimeIP($name) {
			$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');
			$datetime = date("Y/m/d h:i:sa");
			$ip = getenv('REMOTE_ADDR');

			$r=$db->query("UPDATE User SET User_login_time = '".$datetime."', User_login_IP = '".$ip."' WHERE User_name='".$name."'");
			$db->close();
		}
		public function GetUserType($name){
			$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,'zhongzhen');
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf8');

			$r = $db->query("SELECT User_type from User where User_name='".$name."'");

			$row = $r->fetch_array();
			$db->close();
			
			return $row[0];
		}
		public function GetCompanyID($nam) {
			$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
			if($db->connect_error)
				die('could not connect:'.$db->connect_error);
			$db->set_charset('utf-8');
			$db->query("set names 'utf8'");

			$res = $db->query("SELECT User_company FROM User WHERE User_name = '".$nam."'");
			$row = $res->fetch_array();
			$company_name = $row[0];

			$res = $db->query("SELECT Co_id FROM Company WHERE Co_name = '".$company_name."'");
			$row = $res->fetch_array();

			$db->close();

			return $row[0];
		}
	};
?>