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
        <a href="<?=$this->buildUrl('list')?>">List View</a>
        <a href="<?=$this->buildUrl('tree')?>">Tree View</a>
    </div>
</div>
<div class="page_body_b">
	<div class="base-grid" id="list">
    	<div class="thead">
        	<table>
                <tr id="xxx">
                    <th width="25" _field_='chk'><input type="checkbox" /></th>
                    <th width="100" _field_='id'>ID</th>
                    <th width="1500" _field_='code'>Code</th>
                    <th width="300" _field_='model_sn'>Module</th>
                    <th width="150" _field_='name'>name</th>
                    <th width="120" _field_='notes'>notes</th>
                    <th width="120" _field_='flag_grp'>flag_grp</th>
                    <th width="70">op.</th>
                </tr>
            </table>
        </div>
        <div class="tbody">
        	<table id="tt">
                <?php if (!$this->items):?>
                <tr>
                    <td colspan="7" align="center">暂无记录。</td>
                </tr>
                <?php else: foreach ($this->items as $item):?>
                <tr>
                    <td width="25"><input type="checkbox" /></td>
                    <td>
					<?=$item['id']?></td>
                    <td><?=$item['code']?></td>
                    <td><?=$item['module_sn']?></td>
                    <td><?=$item['name']?></td>
                    <td><?=$item['notes']?></td>
                    <td><?=$item['flag_grp']?></td>
                    <td class="op">
                        <a href="<?=$this->buildUrl('edit',null,null,array('id'=>$item['id']))?>">edit</a>
                    </td>
                </tr>
                <?php endforeach; endif;?>
            </table>
        </div>
        <div class="tfoot">
        	<div class="left">x</div>
            <div class="right">xxx</div>
        </div>
    </div>
	
</div>
<div id="dialog" class="dialog">
    <form name="frm" method="post">
    	<input type="hidden" name="gid" />
        <table class="table03">
        	<tr>
                <td class="r">Code:</td>
                <td><input type="text" /></td>
            </tr>
            <tr>
                <td class="r">name:</td>
                <td><input id="group_name" type="text" name="p[name]" /></td>
            </tr>
            <tr>
                <td class="r">notes:</td>
                <td><textarea name="p[notes]"></textarea></td>
            </tr>
        </table>
    </form>
</div>
<?php $this->headScript()->appendFile('/js/lyq.BaseGrid.js');?>
<?=JsUtils::ob_start();?>
<script>
$(function()
{
	$('.ttable').tablex();
	
	var frm = document.forms['frm'];
	
	if($("#premits").html() == "")
	{
		$("#premits").html("暂无权限组")   
	}
	
	new lyq.BaseGrid($('#list'), {
		//columes : ['','id','code','module','name','notes','flag_grp','op'],
		/*columes : [
			{'_field_':'',width:25},
		],*/
		header : $('#xxx')[0],
		enabled : true,
		editable : true,
		events : {
			dblclick : function ()
			{
				alert(this);
			}
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