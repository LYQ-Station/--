<div class="page_head">
    <div class="page_title">管理日志列表</div>
    <div class="page_nav">
    	<a href="<?=$this->buildUrl('index')?>">所有日志</a>
    	<a href="<?=$this->buildUrl('index',null,null,array('c'=>SearchFilter::encode('status<>0')))?>">操作异常的日志</a>
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
    <table class="ttable">
        <tr>
            <th width="25"><input type="checkbox" /></th>
            <th width="120">操作者</th>
            <th>事件</th>
            <th>操作结果</th>
            <th width="120">时间</th>
            <th width="70">状态</th>
        </tr>
        <?php if (!$this->logs):?>
        <tr>
        	<td colspan="6" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->logs as $log):?>
        <tr>
            <td width="25"><input type="checkbox" /></td>
            <td><?=$log['who']?></td>
            <td><?=$log['event']?></td>
            <td><?=$log['result']?></td>
            <td><?=$log['createtime']?></td>
            <td><?=$log['status']?></td>
        </tr>
        <?php endforeach; endif;?>
    </table>
</div>
<div class="page_foot">
    <div class="right">
        <?=$this->navigator?>
    </div>
</div>
<script>
$(function ()
{
	$('.ttable').tablex();
	
	$.ajaxSetup({
		global: false,
		type: "POST",
		dataType: 'json'
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
					window.location = '<?=$this->buildUrl('index')?>' + '?c=' + data;
				});
				
				srh_win.focus();
			}
		});
		
		return false;
	});
});
</script>