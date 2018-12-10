<?php 
class MonitorController extends BaseController{
	public function MonitorAction(){
		session_start();
		$name 		= $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];

		BaseController::CheckLegality($name, $sessid_new);
		$co_id 				= $_GET['co_id'];
		$sta_id				= $_GET['sta_id'];
		$obj_temp			= $_GET['obj'];
		$_SESSION['co_id'] 	= $co_id;//存储本次会话的co_id，panel的ajax会用到!!

		$monitor_model 	= new MonitorModel();
		$res 			= $monitor_model->GetCompanyInfo($co_id);
		$sta_num 		= $res[sta_num];
		$co_nam 		= $res[co_nam];
		$user_type		= $_SESSION['usertype'];
		if (!($sta_num)) {
			if ($user_type == 'admin') {
				BaseController::GoToURL('?pos=home','there exists no station!');
			}else
				session_destroy();
				BaseController::GoToURL('?pos=login','there exists no station!');
		}else{
			if(empty($obj_temp))
				require POSITION_PATH.POSITION.".html";
			else
				require POSITION_PATH.$obj_temp.".html"; 
		}
	}
	public function GetBladeAmAction() {
		session_start();
		$name 		= $_SESSION['username'];
		$user_type	= $_SESSION['usertype'];
		$co_id		= $_SESSION['co_id'];
		$co_name 	= $_GET['co_name'];
		$sta_id		= $_GET['sta_id'];
		$obj_temp	= $_GET['obj'];

		$monitor_model = new MonitorModel();
		$ret = $monitor_model->GetLatestBladeAm($co_id, $sta_id);

		echo $ret;
	}
	// public function GetAllDataAction(){
	// 	session_start();
	// 	$name = $_SESSION['username'];
	// 	$loc_id = $_SESSION['loc_id'];
	// 	$loc_nam=$_GET['loc_name'];
	// 	$sta_id=$_GET['sta_id'];
	// 	$obj_temp=$_GET['obj'];
		
	// 	$monitor_model = new MonitorModel();
	// 	$res_cy = $monitor_model->GetCylindersData($name,$loc_id,$sta_id);
	// 	$res_mt = $monitor_model->GetMotorsData($name,$loc_id,$sta_id);
	// 	$res_ag = $monitor_model->GetAnglesData($name,$loc_id,$sta_id);
	// 	$res_at = $monitor_model->GetAlertsData($name,$loc_id,$sta_id);
	// 	$res = array("Cylinders"=>$res_cy, "Motors"=>$res_mt, "Angles"=>$res_ag, "Alerts"=>$res_at);
	// 	echo json_encode($res);
	// }
	// public function ControlDevicesAction(){
	// 	session_start();
	// 	$name = $_SESSION['username'];
	// 	$sessid_new = $_SESSION['usersessionid'];
		
	// 	if (empty($name)) { //avoid illegal access!!! 
	// 		BaseController::GoToURL('?pos=login');
	// 	}else if(BaseController::CheckSessionIdAction($name,$sessid_new)){//如果重复登录！
	// 		session_destroy();//销毁此次会话，重新开始！
	// 		BaseController::GoToURL('?pos=login','您已在别处登录，请重新登录!');	
	// 	}else{
	// 		$res = 0;
	// 		$json_obj = $_POST['json_obj'];
	// 		$loc_id = $_SESSION['loc_id'];
	// 		$loc_nam=$_GET['loc_name'];
	// 		$sta_id=$_GET['sta_id'];
	// 		$obj_temp=$_GET['obj'];
	// 		$monitor_model = new MonitorModel();
	// 		for ($i=0; $i < count($json_obj); $i++) { 
	// 			$dev_nam = $json_obj[$i]['dev_name'];
	// 			$dev_id = $json_obj[$i]['dev_id'];
	// 			$ctrl_para = $json_obj[$i]['ctrl_param'];
	// 			$ctrl_val = $json_obj[$i]['ctrl_value'];
	// 			$res += $monitor_model->SetControlVal($name, $loc_id, $sta_id, $dev_nam, $dev_id, $ctrl_para, $ctrl_val);
	// 		}	
	// 		// $res = $monitor_model->CountNums($name);
	// 		echo $res;
	// 	}

	// }
	public function GetIPCInfoAction(){
		session_start();
		$name = $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];
		
		BaseController::CheckLegality($name, $sessid_new);
		$co_id 		= $_SESSION['co_id'];
		$co_nam 	= $_GET['co_name'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];
		$monitor_model = new MonitorModel();
		$ipc_dev_id = $monitor_model->GetIPCMsgs($co_id, $sta_id);
		// $ipc_dev_id = "";
		// if ($name == "charles") {
		// 	$ipc_dev_id = 'IPCS00000101';
		// }else if($name == "test"){
		// 	$ipc_dev_id = 'IPCS00000102';
		// }
		
		echo $ipc_dev_id;
	}
	public function HeartBeatAction(){
		session_start();
		$res = 0;
		$co_id 		= $_SESSION['co_id'];
		$co_nam 	= $_GET['co_name'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];
		$act_para 	= $_GET['para'];
		$ipc_dev_id = $_GET['dev'];
		$res = $this->SocketClient($act_para, $ipc_dev_id);

		echo $res;
	}
	private function SocketClient($act_para, $ipc_dev_id){
		error_reporting(E_ALL);
		set_time_limit(0);
		 
		$service_port = 9001;
		$address = "127.0.0.1";
		 
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket < 0){
			// echo "socket_create() failed: reason: " . socket_strerror($socket) . "/n";
			return 1;
		}

		$result = socket_connect($socket, $address, $service_port);
		if ($result < 0){
			// echo "socket_connect() failed./nReason: ($result) " . socket_strerror($result) . "/n";
			return 2;
		}
		 
		$msgs = $act_para;
		$msgs .= ':';
		$msgs .= $ipc_dev_id;
		
		if(!socket_write($socket, $msgs, strlen($msgs))){
			// echo "socket_write() failed: reason: " . socket_strerror($socket) . "/n";
			return 3;
		}

		socket_close($socket);
		
		return 0;
	}
}
 ?>

