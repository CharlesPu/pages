<?php 
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", __DIR__.DS);
//确定当前控制平台
$pos = !empty($_GET['pos']) ? $_GET['pos'] : "login";
define("POSITION", $pos);
define("POSITION_PATH",ROOT.POSITION.DS);
define("FRAME_PATH", POSITION_PATH.'php'.DS);

//autoload when new a class
function __autoload($class_name){
	require ROOT.'config.php';
	$base_class=array('BaseModel','BaseController');
	if (in_array($class_name,$base_class)) {
		(substr($class_name,-5) == "Model")
		? require ROOT.'base'.DS.'base_model.class.php'
		: require ROOT.'base'.DS.'base_controller.class.php';	
	}else if (substr($class_name,-5) == "Model") {
		require FRAME_PATH.POSITION."_model.class.php";
	}else if (substr($class_name,-10) == "Controller") {
		require FRAME_PATH.POSITION."_controller.class.php";
	}
}

//实例化控制器类
$ctrl_name=ucfirst(POSITION)."Controller";
$ctrl = new $ctrl_name();

//调用动作方法
$action = !empty($_GET['act']) ? $_GET['act'] : ucfirst(POSITION);
$action = $action."Action";

$ctrl->$action();

 ?>