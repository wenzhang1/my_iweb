<?php
/**
 * @brief 公共模块
 * @class Block
 */
class Block extends IController
{
	public $layout='';

	public function init()
	{
		$checkObj = new CheckRights($this);
		$checkObj->checkAdminRights();
	}

	/**
	 * 用户在后台编辑器里上传图片
	 */
	public function upload_img_from_editor()
	{
		$checkRight = new checkRights($this);
		$checkRight->checkAdminRights();

		$photoUpload = new PhotoUpload();
		$photoUpload->setIterance(false);
		$re = $photoUpload->run();

		if(isset($re['imgFile']['flag']) && $re['imgFile']['flag']==1 )
		{
			$filePath = IUrl::creatUrl().$re['imgFile']['dir'].$re['imgFile']['name'];
			//echo JSON::encode(array('error' => 0, 'url' => $filePath));
			var_dump(JSON::encode(array('error' => 0, 'url' => $filePath)));
			exit;
		}
		else
		{
			$this->alert("上传失败");
		}
	}
}
?>
