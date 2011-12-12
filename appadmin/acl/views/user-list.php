<div class="page_head">
    <div class="page_title">User List</div>
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
    <table class="ttable">
        <tr>
            <th width="25"><input type="checkbox" /></th>
            <th width="50">ID</th>
            <th width="30">St.</th>
            <th width="100">Username</th>
            <th>Nickname</th>
            <th width="150">Reg Time</th>
            <th width="150">Last Login Time</th>
            <th width="150">Last Login IP</th>
            <th width="120">操作</th>
        </tr>
        <?php if (!$this->items):?>
        <tr>
        	<td colspan="9" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->items as $item):?>
        <tr>
            <td width="25"><input type="checkbox" /></td>
            <td><?=$item['id']?></td>
            <td><?=$item['status']?></td>
            <td><?=$item['username']?></td>
            <td><?=$item['nickname']?></td>
            <td><?=$item['reg_time']?></td>
            <td><?=$item['last_login_time']?></td>
            <td><?=$item['last_login_ip']?></td>
            <td width="170" class="op">
            	<a href="<?=$this->buildUrl('info',null,null,array('id'=>$item['id']))?>">Info</a>
                <a class="a_dis" href="#" lang="<?=$item['id']?>" status="<?=$item['status']?>"><?=HTMLUtils::pick_value2($item['status'], 'Enable', 'Disable')?></a>
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
    
    $('.a_dis').live('click', function ()
    {
        return false;
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