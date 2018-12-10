<?php 
	class BaseController {
		public function CheckLegality($name, $sessid_now) {
			if (empty($name)) //avoid illegal access!!! 
				$this->GoToURL('?pos=login');
			else if($this->CheckSessionIdAction($name, $sessid_now)) {//如果重复登录！
				session_destroy();//销毁此次会话，重新开始！
				$this->GoToURL('?pos=login','您已在别处登录，请重新登录！');
			}
		}
		public function CheckSessionIdAction($name, $sessid_now){
			$base_model = new BaseModel();
			$sessid_old = $base_model->GetSessionId($name);
			if($sessid_old == $sessid_now)return 0;
			else return 1;
		}
		public function GoToURL($url,$alert_text){
			if ( empty($alert_text) ) {
				echo "<script>location.href='$url';</script>";
			}else {
				echo "<script>alert('$alert_text');</script>";
				echo "<script>location.href='$url';</script>";
			}
		}

	}
 ?>