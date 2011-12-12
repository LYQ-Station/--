<div class="page_head">
    <div class="page_title">Token List</div>
    <div class="page_nav">
    	<a href="<?=$this->buildUrl('list')?>">All</a>
    	<a href="<?=$this->buildUrl('list',null,null,array('c'=>SearchFilter::encode('status<>0')))?>">Disabled</a>
    </div>
    <div class="page_tools">
    	<form action="" method="get">
            <input type="text" name="keyword" value="<?=$this->request->keyword?>" />
            <input type="submit" value="Search" />
            <button id="srh_adv_btn">高级 查询</button>
        </form>
    </div>
</div>
<div class="page_body">
    <table class="ttable">
        <tr>
            <th width="25"><input type="checkbox" /></th>
            <th width="90">SN</th>
            <th width="100">Username</th>
            <th>Nickname</th>
            <th width="150">Login Time</th>
            <th width="150">Last Sync Time</th>
            <th width="150">Login IP</th>
            <th width="120">操作</th>
        </tr>
        <?php if (!$this->items):?>
        <tr>
        	<td colspan="8" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->items as $item):?>
        <tr>
            <td width="25"><input type="checkbox" /></td>
            <td><?=$item['sn']?></td>
            <td><?=$item['username']?></td>
            <td><?=$item['nickname']?></td>
            <td><?=date('Y-m-d H:i:s', $item['login_time'])?></td>
            <td><?=date('Y-m-d H:i:s', $item['sync_time'])?></td>
            <td><?=long2ip($item['login_ip'])?></td>
            <td width="170" class="op">
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
    
    $('.a_del').live('click', function ()
	{
		if (!confirm('Do you want to delete this User?'))
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
});
</script>
<?=JsUtils::ob_end();?>