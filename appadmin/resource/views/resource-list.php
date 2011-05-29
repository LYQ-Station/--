<div class="page_head">
    <div class="page_title">Resource List</div>
    <div class="page_nav">
    	<button id="btn_add">New</button>
    	<a href="<?=$this->buildUrl('list')?>">All</a>
    	<a href="<?=$this->buildUrl('list',null,null,array('c'=>SearchFilter::encode('status<>0')))?>">Disabled</a>
    </div>
    <div class="page_tools">
    	<form action="" method="get">
            <input type="text" name="keyword" value="<?=$this->request->keyword?>" />
            <input type="submit" value="Search" />
            <button id="srh_adv_btn">高级查询</button>
        </form>
    </div>
</div>
<div class="page_body">
	<div style="border-bottom:1px;#e5e5e5;solid;padding:5px 15px;">
    	<a href="<?=$this->buildUrl('list',null,null,array('id'=>0))?>">root</a>
    	<?php if (!empty($this->path_arr)): foreach ($this->path_arr as $id => $p): ?>
    	 : <a href="<?=$this->buildUrl('list',null,null,array('id'=>$id))?>"><?=$p?></a>
    	<?php endforeach; endif;?>
    </div>
    <table class="ttable">
        <tr>
            <th width="25"><input type="checkbox" /></th>
            <th width="100">ID</th>
            <th width="100">PID</th>
            <th>Title</th>
            <th width="150">Create Time</th>
            <th width="120">操作</th>
        </tr>
        <?php if (!$this->items):?>
        <tr>
        	<td colspan="6" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->items as $item):?>
        <tr>
            <td width="25"><input type="checkbox" /></td>
            <td><?=$item['id']?></td>
            <td><?=$item['pid']?></td>
            <td><?=$item['title']?></td>
            <td><?=$item['create_time']?></td>
            <td width="170" class="op">
            	<a href="<?=$this->buildUrl('list',null,null,array('id'=>$item['id']))?>">Enter</a>
            	<a href="<?=$this->buildUrl('info',null,null,array('id'=>$item['id']))?>">Info</a>
            	<a class="a_dis" href="#" lang="<?=$item['id']?>" status="<?=$item['status']?>">Disable</a>
                <a class="a_del" href="#" lang="<?=$item['id']?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; endif;?>
    </table>
</div>
<div class="page_foot">
    <div class="right">
        <?=$this->navigator?>
    </div>
</div>
<?=JsUtils::ob_start();?>
<script>
$(function ()
{
	$('.ttable').tablex();
	
	$.ajaxSetup({
		global: false,
		type: "POST",
		dataType: 'json'
	});
	
	$('#btn_add').click(function ()
	{
		location = '<?=$this->buildUrl('addpage')?>';
		return false;
	});
	
	$('.a_dis').live('click', function ()
	{
		return false;
	});
	
	$('.a_del').live('click', function ()
	{
		if (!confirm('Do you want to delete this Resource?'))
			return false;
		
		var self = $(this);
		
		$.ajax({
			url: '<?=$this->buildUrl('ajaxdelete')?>',
			data: $.param({id:self.attr('lang')}),
			success: function (data)
			{
				if (data.err_no)
				{
					alert(data.err_text);
					return false;
				}
				
				self.closest('tr').remove();
			}
		});
		
		return false;
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
					return;
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