<?php 
class MonitorModel{
	public function GetCompanyInfo($nam, $co_id){
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD, MYSQL_DB_NAME);
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM Company WHERE Co_id = $co_id");
		$row=$res->fetch_array();

		$db->close();
		return $row;
	}
	public function GetLatestBladeAm($co_id, $sta_id) {
		$db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, MYSQL_DB_NAME);
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		// $db->query("set names 'utf8'");

		$res = $db->query("SELECT * FROM Blade_Amplitude WHERE BladeAm_company_id = $co_id AND BladeAm_station_id = $sta_id ORDER BY BladeAm_id DESC LIMIT 1");
		$row = $res->fetch_array(MYSQLI_ASSOC);

		$db->close();
		return $row['BladeAm_amplitude'];
	}



	/***************************************************************************************************/
	public function GetCylindersData($nam, $loc_id, $sta_id){
		// $db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, "$nam");
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"ccshj");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM Cylinders WHERE location_id = $loc_id AND station_id = $sta_id");
		$arr = array();
		while ( ($row=$res->fetch_array(MYSQLI_ASSOC)) != NULL) { //注意这里的fetch有个传入参数，默认是同时产生关联和数字数组，现在只要关联数组
			// return $row;
			array_push($arr, $row);
		}

		$db->close();
		return $arr;
	}
	public function GetMotorsData($nam, $loc_id, $sta_id){
		// $db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, "$nam");
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"ccshj");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM Motors WHERE location_id = $loc_id AND station_id = $sta_id");
		$arr = array();
		while ( ($row=$res->fetch_array(MYSQLI_ASSOC)) != NULL) { //注意这里的fetch有个传入参数，默认是同时产生关联和数字数组，现在只要关联数组
			// return $row;
			array_push($arr, $row);
		}

		$db->close();
		return $arr;
	}
	public function GetAnglesData($nam, $loc_id, $sta_id){
		// $db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, "$nam");
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"ccshj");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM Platform WHERE location_id = $loc_id AND station_id = $sta_id");
		$arr = array();
		while ( ($row=$res->fetch_array(MYSQLI_ASSOC)) != NULL) { //注意这里的fetch有个传入参数，默认是同时产生关联和数字数组，现在只要数组
			// return $row;
			array_push($arr, $row);
		}

		$db->close();
		return $arr;
	}
	public function GetAlertsData($nam, $loc_id, $sta_id){
		// $db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, "$nam");
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"ccshj");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM Alerts");
		$arr = array();
		while ( ($row=$res->fetch_array(MYSQLI_ASSOC)) != NULL) { //注意这里的fetch有个传入参数，默认是同时产生关联和数字数组，现在只要数组
			$id_tmp=$row["alert_bit_offset"];
			$r=$db->query("SELECT * FROM AlertContent where bit_offset = $id_tmp");
			$row["content"]=$r->fetch_array(MYSQLI_ASSOC)['content'];
			array_push($arr, $row);
		}

		$db->close();
		return $arr;
	}

	public function SetControlVal($nam, $loc_id, $sta_id, $dev_nam, $dev_id, $ctrl_para, $ctrl_val){
		// $db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, "$nam");
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"ccshj");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM Control WHERE device_name = '{$dev_nam}' and location_id = $loc_id and station_id = $sta_id and device_id = '{$dev_id}' and control_param = '{$ctrl_para}'");
		$row = $res->fetch_array();
		if($row != null){
			$res = $db->query("UPDATE Control SET control_value = $ctrl_val WHERE device_name = '{$dev_nam}' and location_id = $loc_id and station_id = $sta_id and device_id = '{$dev_id}' and control_param = '{$ctrl_para}'");
		}else{
			$res = $db->query("INSERT INTO Control (device_name,location_id,station_id,device_id,control_param,control_value) VALUES ('{$dev_nam}',$loc_id,$sta_id,'{$dev_id}','{$ctrl_para}',$ctrl_val)");
		}

		$db->close();
		return $res;
	}
	public function CountNums($nam){
		// $db = new mysqli('localhost', MYSQL_USER, MYSQL_PASSWD, "$nam");
		$db=new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"ccshj");
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		$db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM AlertContent");
		
		$row = $res->num_rows;
		// $pr = 0;
		// while ( ($row=$res->fetch_array(MYSQLI_ASSOC)) != NULL) { //注意这里的fetch有个传入参数，默认是同时产生关联和数字数组，现在只要关联数组
		// 	$pr++;
		// }
		$db->close();
		return $pr;
	}
	public function GetIPCMsgs($nam, $co_id, $sta_id){
		$db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,MYSQL_DB_NAME);
		if($db->connect_error)
			die('could not connect:'.$db->connect_error);
		$db->set_charset('utf-8');
		// $db->query("set names 'utf8'");

		$res=$db->query("SELECT * FROM RTU WHERE RTU_company_id = $co_id AND RTU_station_id = $sta_id");
		
		$row = $res->fetch_array(MYSQLI_ASSOC);
		$db->close();

		return $row['RTU_IPC_dev_id'];
	}
}

?>