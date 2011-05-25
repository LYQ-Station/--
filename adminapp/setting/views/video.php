<div class="page_head">
    <div class="page_title">视频音乐设置</div>
    <div class="page_nav"></div>
</div>
<div class="page_body_b">
	<form method="post" action="<?=$this->buildUrl('save')?>" name="img_frm">
        <table class="tinfo">
            <tr>
                <th width="241" valign="top">视频音乐类型：</th>
                <td width="321">
                	<div>
                    	<input type="text" />
                        <button id="add_type">+</button>
                    </div>
                	<select name="type[]" multiple="multiple" style="width:250px;height:300px;">
                    	<?=HTMLUtils::array_options($this->vdo_settings['type'])?>
                    </select>
                    <button id="del_type">-</button>
                </td>
            </tr>
            <tr>
                <th width="241" valign="top">外部来源：</th>
                <td width="321">
                	<div>
                    	<input type="text" />
                        <button id="add_src">+</button>
                    </div>
                	<select name="src[]" multiple="multiple" style="width:250px;height:300px;">
                    	<?=HTMLUtils::array_options($this->vdo_settings['src'])?>
                    </select>
                    <button id="del_src">-</button>
                </td>
            </tr>
            <tr>
                <th>文件大小：</th>
                <td><input type="text" class="inputTxt" value="<?=$this->vdo_settings['filesize']?>" name="filesize"> 单位M</td>
            </tr>
            <tr>
                <td colspan="2"><input type="button" value="保 存" class="submit" /></td>
            </tr>
        </table>
    </form>
</div>
<script>
$(function ()
{
	var frm = document.forms['img_frm'];
	var type_sel = frm['type[]'];
	var src_sel = frm['src[]'];
	
	$('#add_type').click(function ()
	{
		var inp = $(this).prev()
		var v = inp.val().replace('\W+', '');
		if (!v.length)
			return false;
			
		if ($(type_sel).find('option[value=' + v + ']').length)
			return false;
		
		type_sel.options.add(new Option(v));
		inp.val('');
		inp[0].focus();
		
		return false;
	});
	
	$('#del_type').click(function ()
	{
		$(type_sel).find(':selected').remove();
		return false;
	});
	
	$('#add_src').click(function ()
	{
		var inp = $(this).prev()
		var v = inp.val().replace('\W+', '');
		if (!v.length)
			return false;
			
		if ($(src_sel).find('option[value=' + v + ']').length)
			return false;
		
		src_sel.options.add(new Option(v));
		inp.val('');
		inp[0].focus();
		
		return false;
	});
	
	$('#del_src').click(function ()
	{
		$(src_sel).find(':selected').remove();
		return false;
	});
	
	$('.submit').click(function ()
	{
		$(type_sel).find('option').attr('selected', true);
		$(src_sel).find('option').attr('selected', true);
		
		frm.submit();
		return false;
	});

});
</script>