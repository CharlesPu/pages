<?php 
class HomeController extends BaseController{
	private $co_num = null;
	private $co_msg = array();

	public function HomeAction(){
		session_start();
		$name 		= $_SESSION['username'];
		$sessid_new	= $_SESSION['usersessionid'];
		
		if (empty($name)) //avoid illegal access!!! 
			BaseController::GoToURL('?pos=login');
		else if(BaseController::CheckSessionIdAction($name,$sessid_new)){//如果重复登录！
			session_destroy();//销毁此次会话，重新开始！
			BaseController::GoToURL('?pos=login','您已在别处登录，请重新登录！');			
		}else{
			$home_model = new HomeModel();	
			$this->co_num = $home_model->GetCompanyNum($name);
			$this->co_msg = $home_model->GetCompanyMsgs($name);
	
			require POSITION_PATH.POSITION.".html";	
		}
	}

}

?>