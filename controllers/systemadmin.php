<?php
/**
 * @class SystemAdmin
 * @brief 后台登陆处理
 */

class Systemadmin extends IController{

	public $layout='';
	
	//后台登录
	function login_act(){
		$admin_name = IFilter::act(IReq::get('admin_name'));
		$password = IReq::get('password');
		$data = array();
		$message    = '';
		
		if($admin_name == ''){
			$message = '用户名不能为空!';
		}else if($password == ''){
			$message = '密码不能为空!';
		}else{
			$adminObj = new IModel('admin');
			$adminRow = $adminObj->getObj('admin_name = "'.$admin_name.'"');
			
			if(!empty($adminRow) && ($adminRow['password'] == md5($password)) && ($adminRow['is_del'] == 0)){
				ISafe::set('admin_id',$adminRow['id']);
				ISafe::set('admin_name',$adminRow['admin_name']);
				ISafe::set('admin_pwd',$adminRow['password']);
				$this->redirect('/system/default');
			}else{
				$message = '用户名与密码不匹配!';
			}
		}
		
		$data['message'] = $message;
		
		if($data['message'] != ''){
			$this->setRenderData($data);
			$this->admin_name = $admin_name;
			$this->redirect('index',false);
		}
	}
	
	//后台登出
	function logout()
	{
    	ISafe::clear('admin_id');
    	ISafe::clear('admin_right');
    	ISafe::clear('admin_name');
    	ISafe::clear('admin_pwd');
    	ISafe::clearAll();
    	$this->redirect('index');
	}
	
}
