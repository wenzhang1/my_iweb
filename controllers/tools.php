<?php
/**
 * @brief 工具模块
 * @class Tools
 * @note  后台
 */
class Tools extends IController
{
	public $layout='admin';
	protected $checkRight = array('check' => 'all','uncheck' => array('seo_sitemaps'));

	function init()
	{
		if(IReq::get('action') == 'img_upload')
		{
			$admin_name = IFilter::act(IReq::get('admin_name'));
			$admin_pwd  = IFilter::act(IReq::get('admin_pwd'));

			$adminObj = new IModel('admin');
			$adminRow = $adminObj->getObj("admin_name = '".$admin_name."'",'password');
			if(empty($adminRow) || ($adminRow['password'] != $admin_pwd))
			{
				exit;
			}
		}
		else
		{
			$checkObj = new CheckRights($this);
			$checkObj->checkAdminRights();
		}
	}

	//上传图片(动作)
	static function img_upload()
	{
		//获得配置文件中的数据
		$config      = new Config("site_config");
		$config_info = $config->getInfo();

		$list_thumb_width  = isset($config_info['list_thumb_width'])  ? $config_info['list_thumb_width']  : 175;
	 	$list_thumb_height = isset($config_info['list_thumb_height']) ? $config_info['list_thumb_height'] : 175;
	 	$show_thumb_width  = isset($config_info['show_thumb_width'])  ? $config_info['show_thumb_width']  : 85;
		$show_thumb_height = isset($config_info['show_thumb_height']) ? $config_info['show_thumb_height'] : 85;

	 	//调用文件上传类
		$photoObj = new PhotoUpload();
		$photoObj->setThumb($show_thumb_width,$show_thumb_height,'show');
		$photoObj->setThumb($list_thumb_width,$list_thumb_height,'list');
		$photo    = $photoObj->run();
		//判断上传是否成功，如果float=1则成功
		if($photo['Filedata']['flag']==1)
		{
			$list = $photo['Filedata']['thumb']['list'];
			$list = strrchr($list,'/');
			$id = substr($list,1,strpos($list,'_')-1);
			$show = $photo['Filedata']['thumb']['show'];
			$img = $photo['Filedata']['img'];
			echo IUrl::creatUrl().$show.'|'.$show.'|'.$img.'|'.$id.'|'.$photo['Filedata']['thumb']['list'].'|'.'_'.$show_thumb_width.'_'.$show_thumb_height;
			exit;
		}
		else
		{
			echo '0';
			exit;
		}
	}
	
	
	//文章分类编辑(单页)
	function article_catergory_edit(){
		$data = array();
		$id = IFilter::act( IReq::get('id'),'int' );

		if($id)
		{
			$catObj = new IModel('article_catergory');
			$where  = 'id = '.$id;
			$data = $catObj->getObj($where);
			if(count($data)>0)
			{
				$this->catRow = $data;
				$this->redirect('article_catergory_edit',false);
			}
		}
		if(count($data)==0)
		{
			$this->redirect('article_catergory_edit');
		}
	}
	
	//文章分类编辑(动作)
	function article_catergory_edit_act(){
		$id        = IFilter::act( IReq::get('id','post') );
		$parent_id = IFilter::act( IReq::get('parent_id','post') ) ;

		$catObj    = new IModel('article_catergory');
		$DataArray = array(
			'parent_id' => $parent_id,
			'name'      => IFilter::act( IReq::get('name','post'),'string')
		);
		
		/*开始--获取path信息*/
		//1,修改操作
		if($id)
		{
			$where  = 'id = '.$id;
			$catRow = $catObj->getObj($where);
			if($catRow['parent_id']==$parent_id)
			{
				$isMoveNode = false;
				$DataArray['path'] = $catRow['path'];
			}
			else
				$isMoveNode = true;

			$localId = $id;
		}
		//2,新增操作
		else
		{
			$max_id  = $catObj->getObj('','max(id) as max_id');
			$localId = $max_id['max_id'] ? $max_id['max_id']+1 : 1;
		}
		
		//如果不存在path数据时,计算path数据
		if(!isset($DataArray['path']))
		{
			//获取父节点的path路径
			if($parent_id==0)
				$DataArray['path'] = ','.$localId.',';
			else
			{
				$where     = 'id = '.$parent_id;
				$parentRow = $catObj->getObj($where);
				$DataArray['path'] = $parentRow['path'].$localId.',';
			}
		}
		/*结束--获取path信息*/
		//设置数据值
		$catObj->setData($DataArray);
		
		//1,修改操作
		if($id)
		{
			//节点移动
			if($isMoveNode == true)
			{
				if(isset($parentRow) && $parentRow['path']!=null && strpos($parentRow['path'],','.$id.',')!==false)
				{
					$this->catRow = array(
						'parent_id' => $DataArray['parent_id'],
						'name'      => $DataArray['name'],
						'id'        => $id,
					);
					$this->redirect('article_catergory_edit',false);
					Util::showMessage('不能该节点移动到其子节点的位置上');
				}
				else
				{
					//其子节点批量移动
					$childObj = new IModel('article_catergory');
					$oldPath  = $catRow['path'];
					$newPath  = $DataArray['path'];
					
					$where = 'path like "'.$oldPath.'%"';
					$updateData = array(
						'path' => "replace(pa1th,'".$oldPath."','".$newPath."')",
					);
					$childObj->setData($updateData);
					$childObj->update($where,array('path'));
				}
			}
			$where = 'id = '.$id;
			$catObj->update($where);
		}
		//2,新增操作
		else
			$catObj->add();

		$this->redirect('article_catergory');
	}
	
	//文章分类删除
	function article_catergory_del(){
		$id = IFilter::act( IReq::get('id'),'int' );
		$catObj = new IModel('article_catergory');

		//是否执行删除检测值
		$isCheck=true;

		//检测是否有parent_id 为 $id
		
		if(!empty($id)){
			if(is_array($id) && isset($id[0]) && $id[0]!=''){
				$id_str = join(',',$id);
				$where = 'parent_id IN ('.$id_str.')';
				$catData = $catObj->getObj($where);
				if(!empty($catData))
				{
					$isCheck=false;
					$message='此分类下还有子分类';
				}
		
				//检测是否有article的category_id 为 $id
				else
				{
					$articleObj = new IModel('article');
					$where = 'catergory_id IN ('.$id_str.')';
					$catData = $articleObj->getObj($where);
		
					if(!empty($catData))
					{
						$isCheck=false;
						$message='此分类下还有文章';
					}
				}
		
				if($isCheck == false)
				{
					$message = isset($message) ? $message : '删除失败';
					$this->redirect('article_catergory',false);
					Util::showMessage($message);
				}
				else
				{
					$where  = 'id IN ('.$id_str.')';
					$result = $catObj->del($where);
					$this->redirect('article_catergory');
				}
			}else{
				$where   = 'parent_id = '.$id;
				$catData = $catObj->getObj($where);
				if(!empty($catData))
				{
					$isCheck=false;
					$message='此分类下还有子分类';
				}
		
				//检测是否有article的category_id 为 $id
				else
				{
					$articleObj = new IModel('article');
					$where = 'catergory_id = '.$id;
					$catData = $articleObj->getObj($where);
		
					if(!empty($catData))
					{
						$isCheck=false;
						$message='此分类下还有文章';
					}
				}
		
				if($isCheck == false)
				{
					$message = isset($message) ? $message : '删除失败';
					$this->redirect('article_catergory',false);
					Util::showMessage($message);
				}
				else
				{
					$where  = 'id = '.$id;
					$result = $catObj->del($where);
					$this->redirect('article_catergory');
				}
			}
		}else{
			$this->redirect('article_catergory',false);
			Util::showMessage('请选择要删除的分类');
		}
	}
	
	//文章编辑(单页)
	function article_edit(){
		$data = array();
		$id = IFilter::act(IReq::get('id'),'int');
		
		if($id)
		{
			$id = intval($id);

			//获取文章信息
			$articleObj = new IModel('article');
			$where      = 'id = '.$id;
			$data = $articleObj->getObj($where);
			if(count($data)>0)
			{
				$this->articleRow = $data;
				$this->redirect('article_edit',false);
			}
		}
		if(count($data)==0)
		{
			$this->redirect('article_edit');
		}
		
	}
	
	
	//文章编辑(动作)
	function article_edit_act(){
		$id = intval(IReq::get('id','post'));
		
		$articleObj = new IModel('article');
		$DataArray  = array(
			'title'       => IFilter::act(IReq::get('title','post')),
			'content'     => IFilter::act(IReq::get('text_content','post'),'text'),
			'catergory_id' => IFilter::act(IReq::get('catergory_id','post'),'int'),
			'create_time' => ITime::getDateTime()
		);
		
		//检查catid是否为空
		if($DataArray['catergory_id'] == 0)
		{
			$this->articleRow = $DataArray;
			$this->redirect('article_edit',false);
			Util::showMessage('请选择分类');
		}
		
		$articleObj->setData($DataArray);
		
		if($id)
		{
			//开始更新操作
			$where = 'id = '.$id;
			$is_success = $articleObj->update($where);
		}
		else
		{
			$id = $articleObj->add();
			$is_success = $id ? true : false;
		}
		
		if($is_success)
		{
			$this->redirect('article');
			Util::showMessage('文章发表成功');
		}
		else
		{
			$this->articleRow = $DataArray;
			$this->redirect('article_edit',false);
			Util::showMessage('插入数据时发生错误');
		}
	}
	
	//文章删除
	function article_del(){
		$id = IFilter::act( IReq::get('id') ,'int' );
		if(!empty($id))
		{
			$obj = new IModel('article');


			if(is_array($id) && isset($id[0]) && $id[0]!='')
			{
				$id_str = join(',',$id);
				$where = ' id in ('.$id_str.')';
			}
			else
			{
				$where = 'id = '.$id;
			}
			$obj->del($where);               
			$this->redirect('article');
		}
		else
		{
			$this->redirect('article',false);
			Util::showMessage('请选择要删除的文章');
		}
	}
	
	//增加公告(动作)
	function notice_edit_act(){
		$id = intval(IReq::get('id','post'));
		$noticeObj = new IModel('notice');
		
		$data = array(
				'title'	=> IFilter::act(IReq::get('title','post')),
				'content' => IFilter::act(IReq::get('text_content','post'),'text')
		);
		$data['create_time'] = date("Y-m-d H:i:s");
		$noticeObj->setData($data);
		
		if($id){
			$is_success = $noticeObj->update( "id={$id}" );
		}else{
			$noticeObj->add();
		}
		
		$this->redirect('notice_list');
	}
	
	//增加公告(单页)
	function notice_edit(){
		$id = IReq::get('id');
		
		if($id)
		{
			$id = intval($id);

			//获取文章信息
			$noticeObj = new IModel('notice ');
			$where      = 'id = '.$id;
			$this->noticeRow = $noticeObj->getObj($where);
		}
		
		$this->redirect('notice_edit',false);
	}
	
	//公告删除
	function notice_del(){
		$id = IFilter::act( IReq::get('id') , 'int'  );
		
		if(!is_array($id)){
			$id = array($id);
		}
		$id = implode(",",$id);
		$noticeObj = new IModel('notice');
		$noticeObj->del( "id IN ({$id})" );
		$this->redirect('notice_list');
	}
	
	
	//相册编辑(单页)
	function photo_catergory_edit(){
		$data = array();
		$id = IReq::get('id');

		if($id)
		{
			$catObj = new IModel('photo_category');
			$where  = 'id = '.$id;
			$data = $catObj->getObj($where);
			if(count($data)>0)
			{
				$this->photoCatRow = $data;
				$this->redirect('photo_catergory_edit',false);
			}
		}
		if(count($data)==0)
		{
			$this->redirect('photo_catergory_edit');
		}
	}
	
	//相册编辑(动作)
	function photo_category_edit_act(){
		$id = IFilter::act(IReq::get('id','post'));
		$name = IFilter::act(IReq::get('name','post'),'string');

		$photoCatObj = new IModel('photo_category');
		
		$DataArray = array(
				'name' => $name,
		);
		$photoCatObj->setData($DataArray);
		
		if($id){
			$where = 'id = '.$id;
			$photoCatObj->update($where);
		}else{
			$photoCatObj->add();
		}
		

		
		$this->redirect('photo_category');
	}
	
	//相册删除
	function photo_category_del(){
		$id = IFilter::act(IReq::get('id'),'int');
		$id_str = IFilter::act(IReq::get('id_str'),'string');
		$del_photo = IFilter::act(IReq::get('del_photo'),'bool');
		$photoCatObj = new IModel('photo_category');
		$message = '';
		$isCheck = false;
		
		if(!empty($id) || !empty($id_str)){
			if((is_array($id) && isset($id[0]) && $id[0]!='') || (isset($id_str) && !empty($id_str))){
				$photoObj = new IModel('photo');
				if(!isset($id_str) || empty($id_str)){
					$id_str = join(',',$id);
				}
				$where = 'category_id IN('.$id_str.')';
				$photoData = $photoObj->getObj($where);
				if(!empty($photoData)){
					if(!empty($del_photo) && $del_photo == true){
						$photoDel = $photoObj->del($where);
						$isCheck = true;
						$message = '删除成功';
					}else{
						$isCheck=false;
						$message = '此相册下还有图片,是否确定要删除';
					}
				}else{
					$isCheck = true;
					$message = '删除成功';
				}
				
				if($isCheck == false){
					$this->redirect('photo_category',false);
					$conf = '{link:\'/my_iweb/index.php?controller=tools&action=photo_category_del&id_str='.$id_str.'&del_photo=true\'}';
					Util::delMessage($conf, $message);
				}else{
					$where = 'id IN('.$id_str.')';
					$photoCatdel = $photoCatObj->del($where);
					$this->redirect('photo_category');
					Util::showMessage($message);
				}
			}else{
				$photoObj = new IModel('photo');
				$where = 'category_id = '.$id;
				$photoData = $photoObj->getObj($where);
				
				if(!empty($photoData)){
					if(!empty($del_photo) && $del_photo == true){
						$photoDel = $photoObj->del($where);
						$isCheck = true;
						$message = '删除成功';
					}else{
						$isCheck=false;
						$message = '此相册下还有图片,是否确定要删除';
					}
				}else{
					$isCheck = true;
					$message = '删除成功';
				}
				
				if($isCheck == false){
					$this->redirect('photo_category',false);
					$conf = '{link:\'/my_iweb/index.php?controller=tools&action=photo_category_del&id='.$id.'&del_photo=true\'}';
					Util::delMessage($conf, $message);
				}else{
					$where = 'id ='.$id;
					$photoCatdel = $photoCatObj->del($where);
					$this->redirect('photo_category');
					Util::showMessage($message);
				}
			}
		}else{
			$this->redirect('photo_category',false);
			Util::showMessage('请选择要删除的分类');
		}
	}
	
	//相册上传图片(单页)
	function photo_upload(){
		$category_id = IFilter::act(IReq::get('id'),'int');
		$data = array();
		$catData = array();
		
		if($category_id){
			$id = intval($category_id);
			
			$photoCatObj = new IModel('photo_category');
			$photoObj = new IModel('photo');
			
			$where = "category_id = ".$category_id;
			$data = $photoObj->query($where);
			
			$where = "id = ".$category_id;
			$catData = $photoCatObj->getObj($where,'photo_focus');
			
			if(count($data)>0 && count($catData)>0){
				$data[0]['focus_photo'] = $catData['photo_focus'];
				$data[0]['photo_name'] ='';
				$title = '';
				for($i = 0; $i < count($data); $i++){
					$data[0]['photo_name'] .= $data[$i]['img'].",";
					
					$imgData = explode('/',$data[$i]['img']); 
					$imgTitleData = explode('.',$imgData[4]);
					$data[$i]['img_title'] = $imgTitleData[0];
				}
				$this->photoRow = $data;
				$this->redirect('photo_upload',false);
			}
		}
		if(count($data) == 0 || count($catData) == 0){
			$this->redirect('photo_upload');
		}
	}
	
	//相册上传图片(动作)
	function photo_upload_act(){
		$id = IFilter::act(IReq::get('id','post'),'int');
		$category_id = IFilter::act(IReq::get('category_id','post'),'int');
		$focus_photo = IFilter::act(IReq::get('focus_photo','post'));
		$photoSrcStr = IFilter::act(IReq::get('photo_name','post'),'string');
		$photoSrcStr = trim($photoSrcStr, ',');
		$photoSrcArray = (explode(',',$photoSrcStr));
		$photoObj = new IModel('photo');
		$photoCatObj = new IModel('photo_category');
		if($id){
			$delPhotoStr = IFilter::act(IReq::get('del_photo','post'),'string');
			$delPhotoStr = trim($delPhotoStr,',');
			$delPhotoArray = explode(',',$delPhotoStr);
			foreach($delPhotoArray as $key => $value){
				$delImgRow = $photoObj->getObj('img = \''.$value.'\'');
				if(!empty($delImgRow)){
					$where = 'img = \''.$value.'\'';
					$photoObj->del($where);
				}
			}
			foreach($photoSrcArray as $key => $value){
				$foot = substr($value,strpos($value,'.'));
				$head = substr($value,0,strpos($value,'.'));
				$list_thumb_width = 100;
				$list_thumb_height = 100;
				$list_img = $head.'_'.$list_thumb_width.'_'.$list_thumb_height.$foot;
				$photoDataArray = array(
						'img' => $value,
						'category_id' => $category_id,
						'list_img' => $list_img,
				);
				$imgRow = $photoObj->getObj('img = \''.$value.'\'');
				if(empty($imgRow) || $imgRow['category_id'] != strval($category_id)){
					$photoObj->setData($photoDataArray);
					$photoObj->add();
				}
			}
			$photoCatData = array(
						'photo_focus' => $focus_photo,
			);
			$photoCatObj->setData($photoCatData);
			$photoCatObj->update('id = '.$category_id);
		}else{
			foreach($photoSrcArray as $key => $value){
				$foot = substr($value,strpos($value,'.'));
				$head = substr($value,0,strpos($value,'.'));
				$list_thumb_width = 100;
				$list_thumb_height = 100;
				$list_img = $head.'_'.$list_thumb_width.'_'.$list_thumb_height.$foot;
				$photoDataArray = array(
						'img' => $value,
						'category_id' => $category_id,
						'list_img' => $list_img,
				);
				$imgRow = $photoObj->getObj('img = \''.$value.'\'');
				if(empty($imgRow) || $imgRow['category_id'] != strval($category_id)){
					$photoObj->setData($photoDataArray);
					$photoObj->add();
				}
			}
			$photoCatData = array(
						'photo_focus' => $focus_photo,
			);
			$photoCatObj->setData($photoCatData);
			$photoCatObj->update('id = '.$category_id);
		}
		
		$this->redirect('photo_category');
	}
}
