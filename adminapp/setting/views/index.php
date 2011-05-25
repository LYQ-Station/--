<style>
.kf_table {
	width:500px;
	border-collapse:collapse;
}

.kf_table td {
	padding:3px 5px;
}

.kf_table .k {
	width:220px;
}
</style>
<div class="page_head">
    <div class="page_title">关键字过滤</div>
    <div class="page_nav">
        <button id="create">新 建</button>
        <a href="#" id="del_all">删除所有</a>
        <a href="#" id="del_sel">删除选择的</a>
    </div>
</div>
<div class="page_body_b">
	<table class="kf_table">
    	<tr>
        	<td width="30"><input type="checkbox" /></td>
            <td><span></span></td>
            <td width="50"><button class="del">X</button>
        </tr>
        <?php if ($this->keywords): foreach ($this->keywords as $k):?>
        <tr>
        	<td width="30"><input type="checkbox" value="<?=$k['id']?>" /></td>
            <td><span><?=$k['keyword']?></span></td>
            <td width="50"><button class="del">X</button>
        </tr>
        <?php endforeach; endif;?>
    </table>
</div>
<script>
var KeywordsFilterTable = function (elem, configs)
{
	elem = $(elem);
	if (!elem)
		return;

	this.elem = elem;
	var self = this;
	
	var def_configs = {
		del_url : '<?=$this->buildUrl('delete','kfajax')?>',
		del_all_url : '<?=$this->buildUrl('deleteall','kfajax')?>',
		del_sel_url : '<?=$this->buildUrl('deletesel','kfajax')?>',
		add_url : '<?=$this->buildUrl('add','kfajax')?>',
		edit_url: '<?=$this->buildUrl('edit','kfajax')?>'
	};
	
	this.configs = configs || {};
	
	for (var p in def_configs)
	{
		this.configs[p] = def_configs[p];
	}
	
	this.temp_tr = elem.find('tr').eq(0);
	this.temp_tr.css('display', 'none');
	
	elem.find('.del').live('click', function (evn)
	{
		self.remove_row(this);
	});
	
	elem.find('span').live('click', function (evn)
	{
		self.edit_item(this);
	});
}

KeywordsFilterTable.prototype.create_row = function ()
{
	var row = this.temp_tr.clone();
	row.css('display', '');
	row.attr('_new_', 1);
	this.elem.append(row);
	
	row.find('span').trigger('click');
}

KeywordsFilterTable.prototype.remove_row = function (btn)
{
	btn.disabled = true;	
	btn = $(btn);
	
	var row = btn.parents('tr').eq(0);
	
	if (row.attr('_new_'))
	{
		row.remove();
		return;
	}
	
	$.ajax({
		url: this.configs.del_url,
		data: $.param({id:row.find(':checkbox').val()}),
		success : function (data)
		{
			if ('' != data.err_no)
			{
				alert(data.err_text);
				return;
			}
			
			row.remove();
		}
	});
}

KeywordsFilterTable.prototype.remove_selected = function ()
{
	var chk_boxes = this.elem.find(':checked');
	var ids = [];
	
	chk_boxes.each(function ()
	{
		ids.push(this.value);
	});
	
	var self = this;
	
	$.ajax({
		url: this.configs.del_sel_url,
		data: $.param({ids:ids.join('-')}),
		success: function (data)
		{
			if ('' != data.err_no)
			{
				alert(data.err_text);
				return;
			}
			
			self.elem.find('tr:has(:checked)').remove();
		}
	});
}

KeywordsFilterTable.prototype.remove_all = function ()
{
	var self = this;
	
	$.ajax({
		url: this.configs.del_all_url,
		success: function (data)
		{
			if ('' != data.err_no)
			{
				alert(data.err_text);
				return;
			}
			
			self.elem.find('tr:gt(0)').remove();
		}
	});
}

KeywordsFilterTable.prototype.edit_item =function (span)
{
	var self = this;
	
	span = $(span);
	
	var txt = span.text();
	span.hide();
	
	var inp = $('<input type="text" class="k" />');
	inp.val(txt);
	inp.bind('keyup', function (evn)
	{
		if (13 == evn.keyCode)
		{
			if ('' == this.value)
			{
				alert('不可为空！');
				return;
			}
			
			if (txt == this.value)
			{
				span.show();
				inp.unbind();
				inp.remove();
				return;
			}
			
			var row = span.parents('tr').eq(0);
			var url;
			if (row.attr('_new_'))
				url = self.configs.add_url;
			else
				url = self.configs.edit_url;
			
			var data = {};
			if (row.find('input:checkbox').val())
				data.id = row.find('input:checkbox').val();
			
			data.keyword = this.value;
			
			inp.attr('disabled', true);
			$.ajax({
				url : url,
				data : $.param(data),
				success : function (da)
				{
					span.show();
					inp.unbind();
					
					if ('' != da.err_no)
					{
						alert(da.err_text);
						inp.remove();
						return;
					}
					
					span.text(inp.val());
					row.removeAttr('_new_');
					inp.remove();
				}
			});
		}
	});
	
	span.parent().append(inp);
	inp[0].focus();
	
	/*var doc_click = document.onclick;
	document.onclick = function ()
	{
		span.show();
		inp.unbind();
		inp.remove();
		
		document.onclick = doc_click || null;
	}*/
}

$(function ()
{
	var kf = new KeywordsFilterTable('.kf_table');
	
	$.ajaxSetup({
		type: 'post',
		dataType : 'json'
	});
	
	$('#create').click(function ()
	{
		kf.create_row();
	});
	
	$('#del_all').click(function ()
	{
		if (!confirm('是否要删除所有关键词？'))
			return false;
		
		kf.remove_all();
		
		return false;
	});
	
	$('#del_sel').click(function ()
	{
		if (!confirm('是否要删除选中的关键词？'))
			return false;
			
		kf.remove_selected();
			
		return false;
	});
	
	$('.kf_table tr').live('mouseover', function ()
	{
		this.style.backgroundColor = '#ffffcc';
	});
	
	$('.kf_table tr').live('mouseout', function ()
	{
		this.style.backgroundColor = '';
	});
});
</script>