<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>后台管理</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<!-- stylesheets -->
		<link rel="stylesheet" type="text/css" href="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."//css/reset.css";?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."//css/style.css";?>" media="screen" />
		<link id="color" rel="stylesheet" type="text/css" href="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."//css/colors/blue.css";?>" />
		<!-- scripts (jquery) -->
		<script charset="UTF-8" src="/my_iweb/runtime/systemjs/jquery-1.4.4.min.js"></script>
		<!--[if IE]><script language="javascript" type="text/javascript" src="resources/scripts/excanvas.min.js"></script><![endif]-->
		<script src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/menu.js";?>" type="text/javascript"></script>
		<script src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/jquery-ui-1.8.custom.min.js";?>" type="text/javascript"></script>
      <script src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/smooth.js";?>" type="text/javascript"></script>
      <script src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/jquery.ui.selectmenu.js";?>" type="text/javascript"></script>
      <script src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/smooth.form.js";?>" type="text/javascript"></script>
      <script type="text/javascript" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/smooth.table.js";?>"></script>
      <script type="text/javascript" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/common.js";?>"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				style_path = "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/css/colors";?>";

				$("#date-picker").datepicker();

				$("#box-tabs, #box-left-tabs").tabs();
			});
		</script>
	</head>
	<body>
		<div id="colors-switcher" class="color">
			<a href="" class="blue" title="Blue"></a>
			<a href="" class="green" title="Green"></a>
			<a href="" class="brown" title="Brown"></a>
			<a href="" class="purple" title="Purple"></a>
			<a href="" class="red" title="Red"></a>
			<a href="" class="greyblue" title="GreyBlue"></a>
		</div>
		<!-- header -->
		<div id="header">
			<!-- logo -->
			<div id="logo">
				<h1><a href="" title="Smooth Admin"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/logo.png";?>" alt="Smooth Admin" /></a></h1>
			</div>
			<!-- end logo -->
			
			<div id="header-inner">
				<div id="home">
					<a href="<?php echo IUrl::creatUrl("system/default");?>" title="Home"></a>
				</div>
				<!-- user -->
            <ul id="user">
               <li class="first highlight">您好！欢迎您,<?php echo isset($this->admin['admin_name'])?$this->admin['admin_name']:"";?></li>
               <li><a href="<?php echo IUrl::creatUrl("systemadmin/logout");?>">退出登录</a></li>
               <li class="highlight last"><a href="<?php echo IUrl::creatUrl("/");?>">网站首页</a></li>
            </ul>
            <!-- end user -->
				<div class="corner tl"></div>
				<div class="corner tr"></div>
			</div>
		</div>
		<!-- end header -->
		<!-- content -->
		<div id="content">
			<!-- end content / left -->
			<div id="left">
				<div id="menu">
				</div>
				<?php $menu = new Menu();?>
				<script type='text/javascript'>
					var data = <?php echo $menu->submenu();?>;
					var current = '<?php echo $menu->current;?>';
					var url='<?php echo IUrl::creatUrl("/");?>';
					initMenu(data,current,url);
				</script>
				<div id="date-picker"></div>
			</div>
			<!-- end content / left -->
			<!-- content / right -->
			<div id="right">
				<?php $siteConfig = new Config("site_config");$siteConfig = $siteConfig->getInfo();?>
<?php $admin_name = $this->admin['admin_name']?>
<?php $admin_pwd = $this->admin['admin_pwd']?>
<script type='text/javascript' src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/swfupload.js";?>"></script>
<script type="text/javascript" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/fileprogress.js";?>"></script>
<script type='text/javascript' src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/swfupload.queue.js";?>"></script>
<script type="text/javascript" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/handlers.js";?>"></script>
<script type="text/javascript">
	var swfu;
	window.onload = function () {
		swfu = new SWFUpload({
			// Settings
			upload_url: "<?php echo IUrl::creatUrl("/tools/img_upload/admin_name/$admin_name/admin_pwd/$admin_pwd");?>",
			post_params: {"PHPSESSID": "NONE"},

			file_size_limit : "2 MB",	// 2MB
			file_types : "*.jpg;*.jpge;*.png;*.gif",
			file_types_description : "JPG Images",
			file_upload_limit : "0",
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,

			// Button Settings
			button_placeholder_id : "file",
			button_width: 50,
			button_height: 21,
			button_text : '',
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,

			// Flash Settings
			flash_url : "<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/swfupload.swf";?>",

			custom_settings : {
				upload_target : "show"
			},
			// Debug Settings
			debug: false
		});
	};
	
	function focus_img(little,obj)
	{
		$('#focus_photo').val(little);
		$("img[name='img_name']").removeClass('current');
		$(obj).parent().find('img').addClass('current');
	}
	
	function del_img(id)
	{
		//删除数组元素.
		Array.prototype.remove=function(dx)
		{
		    if(isNaN(dx)||dx>this.length){return false;}
		    for(var i=0,n=0;i<this.length;i++)
		    {
		        if(this[i]!=this[dx])
		        {
		            this[n++]=this[i]
		        }
		    }
		    this.length-=1
		}
		//在数组中获取指定值的元素索引
		Array.prototype.getIndexByValue= function(value)
		{
		    var index = -1;
		    for (var i = 0; i < this.length; i++)
		    {
		        if (this[i] == value)
		        {
		            index = i;
		            break;
		        }
		    }
		    return index;
		}
		
		var photo_name = $('#photo_name').val();
		photo_name = photo_name.substring(0,photo_name.length-1);
		var arr = photo_name.split(',');
		var key = 0;
		for(var i=0;i<arr.length;i++){
			if(arr[i].indexOf(id)>0){
				key = i;
			}
		}
		
		var del_photo = $('#del_photo').val();
		del_photo += arr[key] + ',';
		$('#del_photo').val(del_photo);
		arr.remove(key);
		if(arr.length>0)
		{
			//如果没有全部删除则不为空
			$('#photo_name').val(arr.join(',')+',');
			$("#a"+id).remove();
			var focus_photo = $('#focus_photo').val();
			if(focus_photo.indexOf(id)>0)
			{
				var photo_ar = arr.join(',');
				if(photo_ar!='')
				{
					p_arr = photo_ar.split(',');
					var head = p_arr[0].substring(0,p_arr[0].indexOf('.'));
					var footer = p_arr[0].substring(p_arr[0].indexOf('.'));
					var thumb = $('#thumb').val();
					$('#focus_photo').val(head+footer);
					$('#show_list img').each(function(){
						var src = $(this).attr("src");
						if(src.indexOf(head+thumb+footer)>0 || src.indexOf(head+thumb+footer) == 0)
						{
							$(this).addClass('current');
							return;
						}
					});
				}
			}
		}else{
			//如果图片全部删除则值为空
			$('#photo_name').val("");
			$('#focus_photo').val("");
			$("#a"+id).remove();
		}
	}
</script>


<div class="box">
   <?php $id = IReq::get('id')?>
   <div class="title">
      <h5><?php if(isset($this->photoRow[0]['category_id'])){?>相册查看<?php }else{?>相册图片上传<?php }?></h5>
   </div>
   <div class="form">
      <form action="<?php echo IUrl::creatUrl("/tools/photo_upload_act");?>" method="post">
		 <?php if(isset($this->photoRow)){?>
         <div class="fields">
           <div class="field">
              <div class="label"><label>相册：</label></div>
              <div class="input">
              	<input type="hidden" name="category_id" value="<?php echo isset($this->photoRow[0]['category_id'])?$this->photoRow[0]['category_id']:"";?>" />
              	<?php $category_id = $this->photoRow[0]['category_id']?>
              	<?php $query = new IQuery("photo_category");$query->where = "id = $category_id";$items = $query->find(); foreach($items as $key => $item){?>
              	<input type="text" id="input-small" class="small" disabled="disabled" value="<?php echo isset($item['name'])?$item['name']:"";?>" />
              	<?php }?>
              </div>
           </div>
           <div class="field">
              <div class="label"><em></em>
                 <label for="title">上传图片：</label>  
              </div>
			  <div class="input input-file">
			  	<input type="hidden" name="photo_name" id="photo_name" value="<?php echo isset($this->photoRow[0]['photo_name'])?$this->photoRow[0]['photo_name']:"";?>"/>
			  	<input type="hidden" name="focus_photo" id="focus_photo" value="<?php echo isset($this->photoRow[0]['focus_photo'])?$this->photoRow[0]['focus_photo']:"";?>"/>
				<input type="hidden" name="thumb" id="thumb" value="_100_100"/>
		      	<input type="hidden" name="id" id="id" value="<?php echo isset($this->photoRow[0]['category_id'])?$this->photoRow[0]['category_id']:"";?>"/>
				<input type="hidden" name="del_photo" id="del_photo" value="" />
		      	<input type="file" id="file" name="file" size="40" />
			  </div>
			  <div class="show_title">
				<div id="show"></div>
			  </div>
			  <div class="show_box">
				<div id="show_list">
				   <?php $photoData = $this->photoRow?>
				   <?php $i = 0?>
				   <?php foreach($photoData as $key => $item){?>
					<div class="pic" id="a<?php echo isset($photoData[$i]['img_title'])?$photoData[$i]['img_title']:"";?>">
						<?php if(isset($this->photoRow[0]['focus_photo']) && $this->photoRow[0]['focus_photo'] == $photoData[$i]['img']){?>
						<img name="img_name" class="current" onclick="focus_img('<?php echo isset($photoData[$i]['img'])?$photoData[$i]['img']:"";?>',this);" style="margin: 5px; opacity:1" src="<?php echo IUrl::creatUrl("")."";?><?php echo isset($photoData[$i]['list_img'])?$photoData[$i]['list_img']:"";?>" height="100" width="100">
						<?php }else{?>
						<img name="img_name" class="" onclick="focus_img('<?php echo isset($photoData[$i]['img'])?$photoData[$i]['img']:"";?>',this);" style="margin: 5px; opacity:1" src="<?php echo IUrl::creatUrl("")."";?><?php echo isset($photoData[$i]['list_img'])?$photoData[$i]['list_img']:"";?>" height="100" width="100">
						<?php }?>
						<input type="hidden" value="<?php echo isset($photoData[$i]['img'])?$photoData[$i]['img']:"";?>" name="src[]" />
						<a href="javascript:void(0);" onclick="focus_img('<?php echo isset($photoData[$i]['img'])?$photoData[$i]['img']:"";?>',this);">设为封面</a>
						<a href="javascript:void(0);" id="<?php echo isset($photoData[$i]['img_title'])?$photoData[$i]['img_title']:"";?>" onclick="del_img(this.id);">删除</a>
					</div>
					<?php $i++?>
					<?php }?>
				</div>
			  </div>
           </div>
           <div class="buttons">
               <div class="highlight"><input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" value="提交" /></div>
           </div>
      	</div>
      	<?php }else{?>
       <div class="fields">
           <div class="field">
           	<?php if(isset($id) && !empty($id)){?>
           	<div class="label"><label>相册：</label></div>
              <div class="input">
              		<?php $query = new IQuery("photo_category");$query->where = "id = $id";$items = $query->find(); foreach($items as $key => $item){?>
              		<input type="hidden" name="category_id" value="<?php echo isset($item['id'])?$item['id']:"";?>" />
              		<input type="text" id="input-small" class="small" disabled="disabled" value="<?php echo isset($item['name'])?$item['name']:"";?>" />
              		<?php }?>
              </div>
           	<?php }else{?>
              <div class="label"><label>选择相册：</label></div>
              <div class="select">
              	<?php echo Article::showPhotoCat('category_id',$this->articleRow['category_id'],array('请选择相册'=>null));?>
              </div>
              <?php }?>
           </div>
           <div class="field">
              <div class="label">
                 <label for="title">上传图片：</label>  
              </div>
			  <div class="input input-file">
			  	<input type="hidden" name="photo_name" id="photo_name" value=""/>
			  	<input type="hidden" name="focus_photo" id="focus_photo" value=""/>
				<input type="hidden" name="thumb" id="thumb" value="php"/>
		      	<input type="file" id="file" name="file" size="40" />
			  </div>
			  <div class="show_title">
				<div id="show"></div>
			  </div>
			  <div class="show_box">
				<div id="show_list"></div>
			  </div>
           </div>
           <div class="buttons">
               <div class="highlight"><input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" value="提交" /></div>
           </div>
      	</div>
      	<?php }?>
      </form>
   </div>
</div>
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
		<!-- footer -->
		<div id="footer">
			<p>Copyright &copy; 2000-2010 Your Company. All Rights Reserved.</p>
		</div>
		<!-- end footert -->
	</body>
</html>