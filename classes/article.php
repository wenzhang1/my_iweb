<?php

 /**
 * @class article
 * @brief 文章管理模块
 */

class Article{
	
	//显示文章分类选择框
	static function showArticleCat($selectName='catergory_id',$selectedValue=null,$defaultValue=array()){
		//取得文章分类信息
		$catObj = new IModel('article_catergory');
		$data   = $catObj->query('','id,name,path','path','asc');

		$str = '<select class="auto" name="'.$selectName.'" pattern="required" alt="请选择分类值">';

		//默认option值
		if(!empty($defaultValue))
			$str.='<option value="'.current($defaultValue).'">'.key($defaultValue).'</option>';

		//拼接分类信息
		foreach($data as $val)
		{
			$isSelect = ($val['id']==$selectedValue) ? 'selected=selected':null;
			$path_count = substr_count($val['path'],",");
			if($path_count != 2){
				$str.='<option value="'.$val['id'].'" '.$isSelect.'>'.str_repeat("&nbsp;&nbsp;",substr_count($val['path'],",")-2).'└'.$val['name'].'</option>';
			}else{
				$str.='<option value="'.$val['id'].'" '.$isSelect.'>'.str_repeat("&nbsp;&nbsp;",substr_count($val['path'],",")-2).$val['name'].'</option>';
			}
		}
		$str.='</select>';
		return $str;
	}
	
	//显示相册分类选择框
	static function showPhotoCat($selectName='catergory_id',$selectedValue=null,$defaultValue=array()){
		//取得文章分类信息
		$catObj = new IModel('photo_category');
		$data   = $catObj->query('','id,name','id','asc');

		$str = '<select class="auto" name="'.$selectName.'" pattern="required" alt="请选择相册">';

		//默认option值
		if(!empty($defaultValue))
			$str.='<option value="'.current($defaultValue).'">'.key($defaultValue).'</option>';

		//拼接分类信息
		foreach($data as $val)
		{
			$isSelect = ($val['id']==$selectedValue) ? 'selected=selected':null;
				$str.='<option value="'.$val['id'].'" '.$isSelect.'>'.$val['name'].'</option>';
		}
		$str.='</select>';
		return $str;
	}
}