<?php
/**
 * @brief Menu
 * @class Menu
 * @note
 */

class menu{
	private static $commonMenu = array('/system/default');
	public $current;
	
	//菜单的配制数据
	private static $menu = array(
		'系统'=>array(
				'/system/default' => '后台首页',
		),
		
		'工具'=>array(
				'/tools/article_catergory' => '文章分类',
				'/tools/article' => '文章列表',
				'/tools/notice_list' => '公告列表',
				'/tools/notice_edit' => '添加公告',
				'/tools/photo_category' => '相册列表',
				'/tools/photo_upload' => '相册上传图片',
		)
	);
	
	private static $menu_non_display = array(
		'/tools/article_catergory_edit'=>'/tools/article_catergory',
		'/tools/article_edit_act'=>'/tools/article',
		'/system/self_edit' => '/system/default'
	);
	
    /**
     * @brief 取得当前菜单应该生成的对应JSON数据
     * @return Json
     */
	public function submenu_1()
	{
		$controllerObj = IWeb::$app->getController();
		$controller = $controllerObj->getId();
		$actionObj = $controllerObj->getAction();
		$action = $actionObj->getId();
		$this->current = '/'.$controller.'/'.$action;
		$this->vcurrent = '/'.$controller.'/';
		$items  = array();

		if(isset(self::$menu_non_display[$this->current]))
		{
			$this->current = self::$menu_non_display[$this->current];
			$tmp = explode("/",$this->current);
			$this->vcurrent = $tmp[1];
			$action = $tmp[2];
		}
		
		$find_current = false;
		$items = array();
		foreach(self::$menu as $key => $value)
		{
			if(!is_array($value))
			{
				return;
			}
			$item = array();
			$item['current'] = false;
			$item['title'] = $key;

			foreach($value as $big_cat_name => $big_cat)
			{
				foreach($big_cat as $link => $title)
				{
					if(!isset($item['link']) )
					{
						$item['link'] = $link;
					}

					if($find_current)
					{
						break;
					}

					$tmp1 = explode('_',$action);
					$tmp1 = $tmp1[0];
					if($link == $this->current || preg_match("!^/[^/]+/{$tmp1}_!",$link) )
					{
						$item['current'] = $find_current = true;
						foreach($value as $k=>$v)
						{
							foreach($v as $subMenuKey=>$subMenuName)
							{
								$tmpUrl = IUrl::creatUrl($subMenuKey);
								unset($value[$k][$subMenuKey]);
								$value[$k][$tmpUrl]['name'] = $subMenuName;
								$value[$k][$tmpUrl]['urlPathinfo'] = $subMenuKey;
							}
						}
						$item['list'] = $value;
					}
				}

				if($find_current)
				{
					break;
				}
			}
			$item['link'] = IUrl::creatUrl($item['link']);
			$items[] = $item;
		}
		return JSON::encode($items);
	}
	
	
	public function submenu(){
		$controllerObj = IWeb::$app->getController();
		$controller = $controllerObj->getId();
		$actionObj = $controllerObj->getAction();
		$action = $actionObj->getId();
		$this->current = '/'.$controller.'/'.$action;
		$this->vcurrent = '/'.$controller.'/';
		$find_current = false;
		$items = array();
			
		foreach(self::$menu as $key => $value){
			$item = array();
			$item['title'] = $key;
			foreach($value as $link => $title){
				if(!isset($item['link']) ){
					$item['link'] = $link;
				}
				$tmp1 = explode('_',$action);
				$tmp1 = $tmp1[0];
				if($link == $this->current || preg_match("!^/[^/]+/{$tmp1}_!",$link)){
					$item['current'] = $find_current = true;
				}
				$tmpUrl = IUrl::creatUrl($link);
				unset($value[$link]);
				$value[$tmpUrl]['name'] = $title;
				$value[$tmpUrl]['urlPathinfo'] = $link;
			}
			$item['list'] = $value;
			$item['link'] = IUrl::creatUrl($item['link']);
			$items[] = $item;
		}
		
		return JSON::encode($items);
	}
}