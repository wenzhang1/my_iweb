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
      <h5>文章列表</h5>
      <ul class="links">
         <li><a href="<?php echo IUrl::creatUrl("/tools/article_edit");?>">添加新文章</a></li>
      </ul>
   </div>
   <form action="<?php echo IUrl::creatUrl("/tools/article_del");?>" method="post">
      <div class="table">
         <table>
            <col width="45%" />
            <col width="23%" />
            <col width="10%" />
            <col />
            <col width="5%" />
            <thead>
               <tr>
                  <th class="left">文章标题</th>
                  <th>创建时间</th>
                  <th>对应分类</th>
                  <th>操作</th>
                  <th class="last">
                     <input type="checkbox" class="checkall" />
                  </th>
               </tr>
            </thead>
            <tbody>
               <?php $page= (isset($_GET['page'])&&(intval($_GET['page'])>0))?intval($_GET['page']):1;?>
               <?php $query = new IQuery("article as a");$query->join = "left join article_catergory as ac ON ac.id = a.catergory_id";$query->fields = "a.title as a_title, a.create_time as a_create_time, ac.name as ac_name, a.id as a_id";$query->order = "create_time DESC";$query->page = "$page";$query->pagesize = "10";$items = $query->find(); foreach($items as $key => $item){?>
               <tr>
                  <td class="title"><b><?php echo isset($item['a_title'])?$item['a_title']:"";?></b></td>
                  <td class="date"><?php echo isset($item['a_create_time'])?$item['a_create_time']:"";?></td>
                  <td class="category"><?php echo isset($item['ac_name'])?$item['ac_name']:"";?></td>
                  <td class="operation">
                     <a href="<?php echo IUrl::creatUrl("/tools/article_edit/id/$item[a_id]");?>">编辑文章</a>
                     <a href="javascript:void(0)" onclick="delModel({link:'<?php echo IUrl::creatUrl("/tools/article_del/id/$item[a_id]");?>'})">删除文章</a>
                  </td>
                  <td class="last">
                     <input type="checkbox" name='id[]' value="<?php echo isset($item['a_id'])?$item['a_id']:"";?>" />
                  </td>
               </tr>
               <?php }?>
            </tbody>
         </table>
         <div class="pagination pagination-left">
            <div class="results"><span><?php echo $query->getTotalPage();?></span></div>
            <ul class="pager"><?php echo $query->getPageBar();?></ul>
         </div>
         <div class="action">
              <div class="button"><input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-state-focus" value="批量删除" onclick="delModel();" /></div>
         </div>
      </div>
   </form>
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