<div class="page_head">
    <div class="page_title">用户列表</div>
    <div class="page_nav">
    	<a href="<?=$this->buildUrl('list')?>">所有用户</a>
    	<a href="<?=$this->buildUrl('list',null,null,array('c'=>SearchFilter::encode('status=1')))?>">未审核的用户</a>
        <a href="<?=$this->buildUrl('list',null,null,array('c'=>SearchFilter::encode('status=2')))?>">被锁定的用户</a>
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
            <th>ID</th>
            <th>PID</th>
            <th>Title</th>
            <th>Create Time</th>
            <th width="120">操作</th>
        </tr>
        <?php if (!$this->items):?>
        <tr>
        	<td colspan="6" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->items as $item):?>
        <tr>
            <td width="25"><input type="checkbox" /></td>
            <td><?=$item['username']?></td>
            <td><?=$item['nickname']?></td>
            <td><?=$item['email']?></td>
            <td><?=$item['regdate']?></td>
            <td width="120" class="op">
                <a href="<?=$this->buildUrl('index','info',null,array('uid'=>$user['uid']))?>">详细</a>
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