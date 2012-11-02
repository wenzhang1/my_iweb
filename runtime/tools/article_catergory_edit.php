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
				<div class="box">
   <div class="title">
      <h5>文章分类管理-><?php if(isset($this->catRow['id'])){?>编辑<?php }else{?>添加<?php }?>文章分类</h5>
   </div>
   <div class="form">
      <form action="<?php echo IUrl::creatUrl("/tools/article_catergory_edit_act");?>" method="post">
         <div class="fields">
           <div class="field">
              <div class="label">
                 <label for="title">分类标题：</label>  
              </div>
              <div class="input">
                 <input type="hidden" name="id" value="<?php echo isset($this->catRow['id'])?$this->catRow['id']:"";?>"/>
                 <input type="text" class="small" name="name" id="title" value="<?php echo isset($this->catRow['name'])?$this->catRow['name']:"";?>" />
              </div>
           </div>
           <div class="field">
              <div class="label"><label>上级分类：</label></div>
              <div class="select"><?php echo Article::showArticleCat('parent_id',$this->catRow['parent_id'],array('顶级分类'=>0));?></div>
           </div>
           <div class="buttons">
               <div class="highlight"><input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" value="提交" /></div>
           </div>
         </div>
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