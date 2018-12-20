<?php 
class MonitorModel extends BaseModel {
	public function GetCompanyInfo($co_id){
		$res=$this->db->query("SELECT * FROM Company WHERE Company_ID = $co_id");
		$row=$res->fetch_array();

		$ret['sta_num'] = $row['Company_StationNum'];
		$ret['co_nam']  = $row['Company_Name'];

		return $ret;
	}
	public function GetRTBladePosi($co_id, $sta_id) {
		$res = $this->db->query("SELECT * FROM BladeRTInfo WHERE BladeRTInfo_CompanyID = $co_id AND BladeRTInfo_StationID = $sta_id ORDER BY BladeRTInfo_ID DESC LIMIT 1");
		$row = $res->fetch_array(MYSQLI_ASSOC);

		return $row['BladeRTInfo_Position'];
	}
	public function GetSysState($co_id,$sta_id) {
		$res = $this->db->query("SELECT * FROM VibrationPara WHERE VibraPara_CompanyID = $co_id AND VibraPara_StationID = $sta_id ORDER BY VibraPara_ID DESC LIMIT 1");
		$row = $res->fetch_array(MYSQLI_ASSOC);
		$ret['bl_am'] 		= $row['VibraPara_BladeAm'];
		$ret['bl_eff_cnt'] 	= $row['VibraPara_BladeEffCnt'];
		$ret['cy_am'] 		= $row['VibraPara_CylinderAm'];
		$ret['cy_eff_cnt'] 	= $row['VibraPara_CylinderEffCnt'];
		$ret['alm_no'] 		= $row['VibraPara_AlarmNumber'];

		$res = $this->db->query("SELECT * FROM AlarmLib WHERE AlarmLib_Number = $ret[alm_no]");
		$row = $res->fetch_array(MYSQLI_ASSOC);
		$ret['alm_ctx']		= $row['AlarmLib_Content'];

		return $ret;
	}
	public function GetRTCyPosi($co_id,$sta_id) {
		$ret = null;
		for ($i = 1; $i <= 4; $i++) { 
			$res = $this->db->query("SELECT * FROM CylinderRTInfo WHERE CylinderRTInfo_CompanyID = $co_id AND CylinderRTInfo_StationID = $sta_id AND CylinderRTInfo_CyID = $i ORDER BY CylinderRTInfo_ID DESC LIMIT 1");
			$row = $res->fetch_array(MYSQLI_ASSOC);
			$ret[$i - 1]['cy_posi'] = $row['CylinderRTInfo_Position'];
		}

		return $ret;
	}
	/********************************************** control ***************************************/
	public function SetSysControlVal($co_id,$sta_id,$blade_am_set,$blade_cnt_set,$cylinder_am_set,$cylinder_t_set) {
		$res = $this->db->query("SELECT * FROM VibrationControlPara WHERE VibraCtrl_CompanyID = $co_id AND VibraCtrl_StationID = $sta_id");
		$row = $res->fetch_array();
		if ($row != null) {
			$res = $this->db->query("UPDATE VibrationControlPara SET VibraCtrl_BladeAm = $blade_am_set, VibraCtrl_BladeVibraCnt = $blade_cnt_set, VibraCtrl_CylinderAm = $cylinder_am_set, VibraCtrl_CylinderCycle = $cylinder_t_set WHERE VibraCtrl_CompanyID = $co_id AND VibraCtrl_StationID = $sta_id");
		}else {
			$res = $this->db->query("INSERT INTO VibrationControlPara (VibraCtrl_CompanyID,VibraCtrl_StationID,VibraCtrl_BladeAm,VibraCtrl_BladeVibraCnt,VibraCtrl_CylinderAm,VibraCtrl_CylinderCycle) VALUES ($co_id,$sta_id,$blade_am_set,$blade_cnt_set,$cylinder_am_set,$cylinder_t_set)");
		}
		return $res;
	}
	//exist only one ctrl_msg every co, every sta and every cy
	public function SetCyControlVal($co_id,$sta_id,$cy_id,$act,$single_cy_am,$single_cy_t) {
		$act_field = null;
		if ($act == 'JogUp') {
			$act_field = 'SingleCyCtrl_JogUp';
		}else if ($act == 'JogDown') {
			$act_field = 'SingleCyCtrl_JogDown';
		}else if ($act == 'Reset') {
			$act_field = 'SingleCyCtrl_Reset';
		}
		$res = $this->db->query("SELECT * FROM SingleCyControlPara WHERE SingleCyCtrl_CompanyID = $co_id AND SingleCyCtrl_StationID = $sta_id AND SingleCyCtrl_CyID = $cy_id");
		$row = $res->fetch_array();
		if ($row != null) {
			$res = $this->db->query("DELETE FROM SingleCyControlPara WHERE SingleCyCtrl_CompanyID = $co_id AND SingleCyCtrl_StationID = $sta_id AND SingleCyCtrl_CyID = $cy_id");
		}
		if ($act == null) {
			$res = $this->db->query("INSERT INTO SingleCyControlPara (SingleCyCtrl_CompanyID,SingleCyCtrl_StationID,SingleCyCtrl_CyID,SingleCyCtrl_Amplitude,SingleCyCtrl_Cycle) VALUES ($co_id,$sta_id,$cy_id,$single_cy_am,$single_cy_t)");
		}else {
			$res = $this->db->query("INSERT INTO SingleCyControlPara (SingleCyCtrl_CompanyID,SingleCyCtrl_StationID,SingleCyCtrl_CyID,$act_field) VALUES ($co_id,$sta_id,$cy_id,1)");
		}		
		$this->db->query("ALTER TABLE SingleCyControlPara DROP SingleCyCtrl_ID");
		$this->db->query("ALTER TABLE SingleCyControlPara ADD SingleCyCtrl_ID int(10) NOT NULL AUTO_INCREMENT FIRST,ADD PRIMARY KEY(SingleCyCtrl_ID)");

		return $res;
	}

	/********************************************** IPC ***************************************/
	public function GetIPCMsgs($co_id, $sta_id){
		$res=$this->db->query("SELECT * FROM DTU WHERE DTU_CompanyID = $co_id AND DTU_StationID = $sta_id");
		
		$row = $res->fetch_array(MYSQLI_ASSOC);

		return $row['DTU_IPC_DevID'];
	}
}
?>