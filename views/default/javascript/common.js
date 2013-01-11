/**
 * @author moskito
 */

/**
 * @brief 删除操作
 * @param object conf
	   msg :提示信息;
	   form:要提交的表单名称;
	   link:要跳转的链接地址;
 */
function delModel(conf,msg)
{
	var yesFn = null;            //执行操作
	if(!msg){
		msg   = '确定要删除么？';//提示信息
	}
	

	if(conf)
	{
		if(conf.link){
				if(confirm(msg)){
					window.location.href=conf.link;
				}
		}
	}else{
		/*if(yesFn==null && document.forms.length >= 1)
			var yesFn = 'document.forms[0].submit();';*/
		if(confirm(msg)){
			document.forms[0].submit();
		}
	}

}
