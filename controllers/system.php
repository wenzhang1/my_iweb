<?php
/**
 * @brief 系统模块
 * @class System
 * @note  后台
 */
class System extends IController
{
	protected $checkRight  = array('check' => 'all','uncheck' => array('default','navigation','navigation_update','navigation_del','navigation_edit','navigation_recycle','navigation_recycle_del','navigation_recycle_restore'));
	public $layout      = 'admin';
	public $except      = array('.','..','.svn','.htaccess');
	public $defaultConf = 'config.php';
	private $data = array();
	function init()
	{
		$checkObj = new CheckRights($this);
		$checkObj->checkAdminRights();
	}
}
