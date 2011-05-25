<div class="page_head">
    <div class="page_title">图片设置</div>
    <div class="page_nav"></div>
</div>
<div class="page_body_b">
	<form method="post" action="<?=$this->buildUrl('save')?>" name="img_frm">
        <table class="tinfo">
            <tr>
                <th width="241" valign="top">图片类型：</th>
                <td width="321">
                	<div>
                    	<input type="text" />
                        <button id="add">+</button>
                    </div>
                	<select name="type[]" multiple="multiple" style="width:200px;height:300px;">
                    	<?=HTMLUtils::array_options($this->img_settings['type'])?>
                    </select>
                    <button id="del">-</button>
                </td>
            </tr>
            <tr>
                <th>图片尺寸：</th>
                <td><input type="text" class="inputTxt" value="<?=$this->img_settings['measurement']?>" name="measurement"> 单位kb</td>
            </tr>
            <tr>
                <th>图片文件大小：</th>
                <td><input type="text" class="inputTxt" value="<?=$this->img_settings['filesize']?>" name="filesize"> 单位px</td>
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
	var sel = frm['type[]'];
	
	$('#add').click(function ()
	{
		var inp = $(this).prev()
		var v = inp.val().replace('\W+', '');
		if (!v.length)
			return false;
			
		if ($(sel).find('option[value=' + v + ']').length)
			return false;
		
		sel.options.add(new Option(v));
		inp.val('');
		inp[0].focus();
		
		return false;
	});
	
	$('#del').click(function ()
	{
		$(sel).find(':selected').remove();
		return false;
	});
	
	$('.submit').click(function ()
	{
		$(sel).find('option').attr('selected', true);
		
		frm.submit();
		return false;
	});

});
</script>