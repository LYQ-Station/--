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
            <th>用户名</th>
            <th>昵称</th>
            <th>Email</th>
            <th>注册时间</th>
            <th>最后登录时间</th>
            <th width="120">操作</th>
        </tr>
        <?php if (!$this->users):?>
        <tr>
        	<td colspan="7" align="center">暂无记录。</td>
        </tr>
        <?php else: foreach ($this->users as $user):?>
        <tr>
            <td width="25"><input type="checkbox" /></td>
            <td><?=$user['username']?></td>
            <td><?=$user['nickname']?></td>
            <td><?=$user['email']?></td>
            <td><?=date('Y-m-d H:i:s',$user['regdate'])?></td>
            <td><?=date('Y-m-d H:i:s',$user['lastlogintime'])?></td>
            <td width="120" class="op">
                <a href="<?=$this->buildUrl('index','info',null,array('uid'=>$user['uid']))?>">详细</a>
                
                <?php if (1 == $user['status']):?><a href="#" class="approval" lang="<?=$user['uid']?>">审核</a><?php endif;?>
                
                <?php if (2 == $user['status']):?>
                <a href="#" lang="<?=$user['uid']?>" class="lck_lock" locked="<?=$user['status']?>">解锁</a>
                <?php else:?>
                <a href="#" lang="<?=$user['uid']?>" class="lck_lock" locked="<?=$user['status']?>">锁定</a>
                <?php endif;?>
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
<script>
$(function ()
{
	$('.ttable').tablex();
	
	$.ajaxSetup({
		global: false,
		type: "POST",
		dataType: 'json'
	});
	
	//-------------------------------- 锁定/解锁 --------------------------------
	var lock_fn = function ()
	{
		if (!confirm('是否要锁定此用户？'))
			return false;
			
		var self = $(this);
		
		$.ajax({
			url: '<?=$this->buildUrl('lock','ajax',null)?>',
			data: $.param({id:this.lang}),
			success: function (data)
			{
				if (data.err_no)
				{
					alert(data.err_text);
					return;
				}
				
				self.html('解锁');
				self.attr('class', 'lck_unlock');
				rebind_lock();
				
				alert('已经锁定此用户');
			}
		});
		
		return false;
	};
	
	var unlock_fn = function ()
	{
		if (!confirm('是否要解除此用户的锁定？'))
			return false;
		
		var self = $(this);
		
		$.ajax({
			url: '<?=$this->buildUrl('unlock','ajax',null)?>',
			data: $.param({id:this.lang}),
			success: function (data)
			{
				if (data.err_no)
				{
					alert(data.err_text);
					return;
				}
				
				self.html('锁定');
				self.attr('class', 'lck_lock');
				rebind_lock();
				
				alert('已经解除此用户的锁定');
			}
		});
		
		return false;
	};
	
	var rebind_lock = function ()
	{
		var lock_btns = $('a[class^=lck_]');
		lock_btns.unbind('click');
		
		lock_btns.each(function ()
		{
			if (/^lck_lock$/i.test($(this).attr('class')))
			{
				$(this).bind('click', lock_fn, this);
			}
			else
			{
				$(this).bind('click', unlock_fn, this);
			}
		});
	}
	
	//rebind_lock();
	
	var lock_fnx = function ()
	{
		var self = $(this);
		
		if (0 == self.attr('locked'))
		{
			if (!confirm('是否要锁定此用户？'))
				return false;
				
			$.ajax({
				url: '<?=$this->buildUrl('lock','ajax',null)?>',
				data: $.param({id:this.lang}),
				success: function (data)
				{
					if (data.err_no)
					{
						alert(data.err_text);
						return;
					}
					
					self.html('解锁');
					
					alert('已经锁定此用户');
				}
			});
			
			return false;
		}
		else
		{
			if (!confirm('是否要解除此用户的锁定？'))
				return false;
			
			$.ajax({
				url: '<?=$this->buildUrl('unlock','ajax',null)?>',
				data: $.param({id:this.lang}),
				success: function (data)
				{
					if (data.err_no)
					{
						alert(data.err_text);
						return;
					}
					
					self.html('锁定');
					
					alert('已经解除此用户的锁定');
				}
			});
		}
		
		return false;
	};
	
	var lock_btns = $('a[class^=lck_]');
	
	lock_btns.each(function ()
	{
		$(this).bind('click', lock_fnx, this);
	});
	
	//-------------------------------- 审核/反审 --------------------------------
	$('.approval').click(function ()
	{
		if (!confirm('是否要审核此用户？'))
			return false;
			
		var self = $(this);
		
		$.ajax({
			url: '<?=$this->buildUrl('approval','ajax',null)?>',
			data: $.param({id:this.lang}),
			success: function (data)
			{
				if (data.err_no)
				{
					alert(data.err_text);
					return;
				}
				
				self.remove();
				
				alert('已经审核此用户');
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