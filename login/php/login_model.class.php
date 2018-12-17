<?php 
	class LoginModel extends BaseModel{
		public function CheckAdmin($name,$pswd) {
			$res=$this->db->query("SELECT * from User where User_Name='".$name."' and User_Password='".$pswd."'");	
			if ($res == null ) return 1;
			$row=$res->fetch_array();
			$ret = 0;
			
			if ( $row == null) $ret = 1; 
			else $ret = 0;
			return $ret;
		}
		public function SetSessionId($name,$pswd,$sessid){
			$r=$this->db->query("UPDATE User SET User_SessionID = '".$sessid."' WHERE User_Name='".$name."' and User_Password='".$pswd."'");
		}
		public function SetLoginTimeIP($name) {
			$datetime = date("Y/m/d h:i:sa");
			$ip = getenv('REMOTE_ADDR');

			$r=$this->db->query("UPDATE User SET User_LoginTime = '".$datetime."', User_LoginIP = '".$ip."' WHERE User_Name='".$name."'");
		}
		public function GetUserType($name){
			$r = $this->db->query("SELECT User_Type from User where User_Name='".$name."'");

			$row = $r->fetch_array();
			$ret = "";
			if ($row[0] == "administrator") {
				$ret = "admin";
			}else if ($row[0] == "firm") {
				$ret = "firm";
			}else if ($row[0] == "certification") {
				$ret = "certif";
			}
			
			return $ret;
		}
		public function GetCompanyID($nam) {
			$res = $this->db->query("SELECT User_CompanyID FROM User WHERE User_Name = '".$nam."'");
			$row = $res->fetch_array();

			return $row[0];
		}
	};
?>