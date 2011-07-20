<style>
#premits {
	margin:20px;
}

.on {
	color:red;
}
</style>
<div class="page_head">
    <div class="page_title">基本权限列表</div>
    <div class="page_nav">
        <button id="btn_edit">编辑</button>
		<a href="<?=$this->buildUrl('list')?>">Cancel</a>
    </div>
</div>
<div class="page_body_b">
	<div id="premits"><?=$this->treeview?></div>
</div>
<div id="dialog">
    <form name="frm" method="post">
    	<input type="hidden" name="gid" />
        <table class="table03">
            <tr>
                <td class="r">组名：</td>
                <td><input id="group_name" type="text" name="p[name]" /></td>
            </tr>
            <tr>
                <td class="r">备注：</td>
                <td><textarea name="p[notes]"></textarea></td>
            </tr>
        </table>
    </form>
</div>
<?php $this->headLink()->appendStylesheet('/css/jquery.treeview.css');?>
<?php $this->headScript()->appendFile('/js/jquery.treeview.js');?>
<?=JsUtils::ob_start();?>
<script>
$(function()
{
	var frm = document.forms['frm'];
	
	if($("#premits").html() == "")
	{
		$("#premits").html("暂无权限组")   
	}
	
	$("#premits").treeview({
		collapsed: false,
		click : function (evn)
		{
			$('#dept a').removeClass('on');
			$(this).addClass('on');
			this.blur();
		},
		dblclick : function (evn)
		{
			frm.action = '<?=$this->buildUrl('edit')?>';
			
			frm['gid'].value = this.getAttribute('id');
			frm['p[pid]'].value = this.getAttribute('pid');
			frm['p[name]'].value = this.innerHTML;
			frm['p[notes]'].value = this.title;
			
			$('#dialog').dialog('open');
		}
	});
	
	$('#dialog').dialog({
		autoOpen : false,
		modal : true,
		width : 600,
		title : '权限组',
		buttons : {
			"确定": function() {
				frm.submit();
				$(this).dialog("close");
			}, 
			"取消": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	$('#btn_edit').click(function ()
	{
		frm.reset();
		frm.action = '<?=$this->buildUrl('add')?>';
		$('#dialog').dialog('open');
	});
});
</script>
<?=JsUtils::ob_end();?>