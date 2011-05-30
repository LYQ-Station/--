<div class="page_head">
    <div class="page_title">Tags Picker</div>
    <div class="page_nav">
    	<a href="<?=$this->buildUrl('popuptag')?>">All</a>
    	<a href="<?=$this->buildUrl('popuptag',null,null,array('c'=>SearchFilter::encode('status<>0')))?>">Disabled</a>
    </div>
    <div class="page_tools">
    	<form action="" method="get">
            <input type="text" name="keyword" value="<?=$this->request->keyword?>" />
            <input type="submit" value="Search" />
            <button id="srh_adv_btn">高级查询</button>
            <button id="btn_sel">Select</button>
        </form>
    </div>
</div>
<div class="page_body">
    <table class="ttable">
        <tr>
            <th width="30"></th>
            <th width="70">ID</th>
            <th>Tag</th>
            <th width="150">Create Time</th>
        </tr>
        <?php if (!$this->items):?>
        <tr>
        	<td colspan="4" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->items as $item):?>
        <tr>
            <td><input type="checkbox" id="<?=$item['id']?>" tag="<?=$item['tag']?>" /></td>
            <td><?=$item['id']?></td>
            <td><?=$item['tag']?></td>
            <td><?=$item['create_time']?></td>
        </tr>
        <?php endforeach; endif;?>
    </table>
</div>
<div class="page_foot">
    <div class="right">
        <?=$this->navigator?>
    </div>
</div>
<?php $this->headScript()->appendFile('/js/egt.DialogEx.js');?>
<?=JsUtils::ob_start();?>
<script type="text/javascript">
$(function ()
{
	document.title = 'Select tags';
	
	$('.ttable').tablex();
	
	/*var list = new ListEx($('#table_ex')[0],
	{
		css : {'min-width':'1200px','width':'1200px'},
		events :
		{
			dblclick : function ()
			{
				var data = new Array();
				$(this).find('td:gt(0)').each(function (i)
				{
					data.push(this.innerHTML);
				});
				
				var egt_dialog = window.dialogArguments ? window.dialogArguments : window.navigator['dialog_events_handle'];
				egt_dialog.trigger('on_submit', [data]);
				window.close();
			}
		}
	});*/
	
	$('#btn_sel').click(function ()
	{
		var data = [];
		
		$('.ttable').find('input:checked').each(function ()
		{
			data.push({
				id: this.id,
				tag: this.getAttribute('tag')
			});
		});
		
		var egt_dialog = window.dialogArguments ? window.dialogArguments : window.navigator['dialog_events_handle'];
		egt_dialog.trigger('submit', [data]);
		window.close();
	});
	
	//-------------------------------- 高级查询窗口 --------------------------------
	var srh_win = null;
	$('#srh_adv_btn').click(function ()
	{
		$.ajax({
			url: '<?=$this->buildUrl('searchfields')?>',
			success: function (data)
			{	
				if (data.err_no)
				{
					alert(data.err_text);
					return false;
				}
				
				if (srh_win)
					srh_win.close();
								
				srh_win = open('<?=$this->buildUrl('index','search','default')?>', '', 'menubar=no,width=650,height=500');
				
				srh_win.navigator['data'] = data.content;
				var ob = {};
				srh_win.navigator['listener'] = $(ob);
				$(ob).bind('submit', function (evn, data)
				{
					window.location = '<?=$this->buildUrl('list')?>' + '?c=' + data;
				});
				
				srh_win.focus();
			}
		});
		
		return false;
	});
});
</script>
<?=JsUtils::ob_end();?>