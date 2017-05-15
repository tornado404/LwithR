<?php
	/**
	 * 管理员列表  admin_list.php
	 *
	 * @version       v0.03
	 * @create time   2014-8-3
	 * @update time   2016/3/26
	 * @author        hlc jt
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

	$POWERID        = '7002';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);
    
    $FLAG_TOPNAV	= "system";
	$FLAG_LEFTMENU  = 'admin_list';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<title>管理员 - 管理设置 - 管理系统 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<link rel="stylesheet" href="css/boxy.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript">
			$(function(){
				//添加管理员
				$('#addadmin').click(function(){
		            layer.open({
		                type: 2,
		                title: '添加管理员',
		                shadeClose: true,
		                shade: 0.3,
		                area: ['500px', '300px'],
		                content: 'admin_add.php'
		            }); 
				});
                //修改管理员
				$(".editinfo").click(function(){
					var thisid = $(this).parent('td').find('#aid').val();
		            layer.open({
		                type: 2,
		                title: '修改管理员',
		                shadeClose: true,
		                shade: 0.3,
		                area: ['500px', '300px'],
		                content: 'admin_edit.php?id='+thisid
		            }); 
				});
                //重置密码
				$(".reset").click(function(){
					var thisid = $(this).parent('td').find('#aid').val();
					layer.confirm('确认重置该管理员账号的密码吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									id:thisid
								},
                                dataType :    'json',
								url :         'admin_do.php?act=reset',
								success : function(data){
												layer.close(index);
                                                
                                                code = data.code;
    											msg  = data.msg;
    											switch(code){
    												case 1:
    													layer.alert(msg, {icon: 6}, function(index){
    														location.reload();
    													});
    													break;
    												default:
    													layer.alert(msg, {icon: 5});
    											}
                                            }
							});
			            }, function(){}
			        );
				});
                //删除管理员
				$(".delete").click(function(){
					var thisid = $(this).parent('td').find('#aid').val();
					layer.confirm('确认删除该管理员账号吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									id:thisid
								},
                                dataType : 'json',
								url : 'admin_do.php?act=del',
								success : function(data){
												layer.close(index);
                                                
												code = data.code;
    											msg  = data.msg;
    											switch(code){
    												case 1:
    													layer.alert(msg, {icon: 6}, function(index){
    														location.reload();
    													});
    													break;
    												default:
    													layer.alert(msg, {icon: 5});
    											}
                                            }
							});
			            }, function(){}
			        );
				});
				$(".reset").mouseover(function(){
					layer.tips('重置密码', $(this), {
					    tips: [4, '#3595CC'],
					    time: 500
					});
				});
				$(".editinfo").mouseover(function(){
					layer.tips('修改', $(this), {
					    tips: [4, '#3595CC'],
					    time: 500
					});
				});
				$(".delete").mouseover(function(){
					layer.tips('删除', $(this), {
					    tips: [4, '#3595CC'],
					    time: 500
					});
				});
			});
				
		</script>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
			<?php include('admin_menu.inc.php');?>
			<div id="maincontent">
				<div id="position">当前位置：<a href="admingroup.php">系统设置</a> &gt; 管理员设置</div>
				<div id="handlelist">
                    <?php
                        //初始化
                        $rows = Admin::getList();
                    ?>
					<input type="button" class="btn-handle" href="javascript:" id="addadmin" value="添 加">
					<span class="table_info">共<?php echo count($rows);?>条数据</span>
					<div>
					</div>
				</div>
				<div class="tablelist">
					<table>
						<tr>
							<th>帐号</th>
							<th>管理员所属组</th>
							<th>最近一次登录IP</th>
							<th>最近一次登录时间</th>
							<th>登录次数</th>
							<th>操作</th>
						</tr>
						<?php

							$i=1;
							//如果列表不为空
							if(!empty($rows)){
								foreach($rows as $row){
									$groupInfo     = new Admingroup($row['group']);
                                    
									if(!empty($row['logintime']))
										$logintime = date('Y-m-d H:i:s',$row['logintime']);
									else
										$logintime = '';
                                        
									echo '<tr>
											<td>'.$row['account'].'</td>
											<td>'.$groupInfo->name.'</td>
											<td class="center">'.$row['loginip'].'</td>
											<td class="center">'.$logintime.'</td>
											<td class="center">'.$row['logincount'].'</td>
											<td class="center">
												<a href="javascript:void(0)" class="reset"><img src="images/dot_reset.png" /></a>
												<a class="editinfo" href="javascript:void(0)"><img src="images/dot_edit.png"/></a> 
												<a class="delete" href="javascript:void(0)"><img src="images/dot_del.png"/></a>
												<input type="hidden" id="aid" value="'.$row['id'].'"/>
											</td>
										</tr>
									';
									$i++;
								}
							}else{
								echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
							}
						?>
						
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>