<style>
table td {
	text-align:center;
}

table td select {
	width:90%;
}
</style>
<div class="page_head">
	<div class="page_title">高级查询</div>
    <div class="page_tools">
    	<form action="<?=$this->buildUrl('list')?>" method="get">
            <button id="submit_btn">查询</button>
            <button id="close_btn">关闭</button>
        </form>
    </div>
</div>
<div class="page_body">
	<table width="100%">
    	<tr>
        	<th width="10%"></th>
            <th>字段</th>
            <th width="10%"></th>
            <th>值</th>
            <th width="10%"><button class="add_btn">+</button></th>
        </tr>
        <tr id="tr_tmp">
        	<td>
            	<select class="c">
                	<option value="AND">AND</option>
                    <option value="OR">OR</option>
                </select>
            </td>
            <td>
            	<select class="f">
                	<option>-- 条件字段 --</option>
                </select>
            </td>
            <td>
            	<select class="op">
                	<option value="=">=</option>
                    <option value=">">&gt;</option>
                    <option value="<">&lt;</option>
                    <option value="LIKE">LIKE</option>
                </select>
            </td>
            <td>
            	<input type="text" class="v" style="width:90%" />
            </td>
            <td>
                <button class="del_btn">-</button>
            </td>
        </tr>
    </table>
</div>
<script>
var data;

function init_data (d)
{
	sel = $('.f')[0];
	
	var p;
	while (p = d.pop())
	{
		var op = new Option(p.l, p.f);
		
		for (var a in p)
			op.setAttribute(a, p[a]);
		
		sel.options.add(op);
	}
}

$(function ()
{
	init_data(window.navigator['data']);
	
	var table = $('table');
	
	var tr_tmp = $('#tr_tmp').clone();
	//$('#tr_tmp').remove();
	
	$('.add_btn').click(function ()
	{
		table.append(tr_tmp.clone());
		return false;
	});
	
	$('.del_btn').live('click', function ()
	{
		$(this).parents('tr').eq(0).remove();
		return false;
	});
	
	var join_query = function ()
	{
		var query_arr = [];
		var f = null;
		var v = null;
		
		table.find('tr:not(:has(th))').each(function (i)
		{
			f = $(this).find('select.f');
			v = $(this).find('input.v').val();
			
			if ('' == v)
				return;
			
			if ('date' == f[0].options[f[0].selectedIndex].getAttribute('t'))
				v = Date.parse(v.replace(/\-/g,'/')) / 1000;
			
			if (0 == i)
			{
				query_arr.push(f.val() + $(this).find('select.op').val() + "'" + v + "'");
			}
			else
			{
				query_arr.push($(this).find('select.c').val() + ' ' + f.val() + $(this).find('select.op').val() + "'" + v + "'");
			}
		});
		
		if (0 == query_arr.length)
			return null;
		
		return query_arr.join(' ');
	};
	
	$('.f').live('change', function ()
	{
		var type = this.options[this.selectedIndex].getAttribute('t');
		var val_elem = $(this).closest('tr').find('.v');
		val_elem.val('');
		
		if ('date' == type)
			val_elem.datepicker();
		else
			val_elem.datepicker('destroy');
	});
	
	$('#submit_btn').click(function ()
	{
		var query = join_query();
		
		if (!query)
		{
			alert('未设置查询条件！');
			return false;
		}
		
		$.ajax({
			url: '<?=$this->buildUrl('encode')?>',
			data: $.param({query:query}),
			dataType: 'json',
			success: function (data)
			{
				window.navigator['listener'].trigger('submit', [data.content]);
				window.close();
			}
		});
		
		return false;
	});
	
	$('#close_btn').click(function ()
	{
		window.close();
		return false;
	});
});
</script>