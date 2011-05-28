<div class="page_head">
    <div class="page_title"><?=$this->title?></div>
    <div class="page_nav">
    	<button id="btn_save">Save</button>
        <button id="btn_sn">Save and create new</button>
    	<a href="<?=$this->buildUrl('list')?>">Cancel</a>
    </div>
</div>
<div class="page_body_b">
	<form action="<?=$this->submit_link?>" method="post">
        <table class="tinfo">
            <tr>
                <th>Parent Resource:</th>
                <td><input type="text" class="txt" name="p[pid]" /></td>
            </tr>
            <tr>
                <th>Title:</th>
                <td><input type="text" class="txt" name="p[title]" v="required" /></td>
            </tr>
            <tr>
                <th>Cover:</th>
                <td><input type="text" class="txt" name="p[cover_id]" /></td>
            </tr>
            <tr>
                <th>Summary:</th>
                <td><textarea name="p[summary]"></textarea></td>
            </tr>
        </table>
    </form>
</div>
<?php $this->headLink()->appendStylesheet('/css/jquery.validationEngine.css');?>
<?php $this->headScript()->appendFile('/js/jquery.ValidationEngineEx.js');?>
<?php $this->headScript()->appendFile('/js/jquery.form.js');?>
<?=JsUtils::ob_start();?>
<script>
$(function ()
{
	$.ajaxSetup({
		global: false,
		type: "POST",
		dataType: 'json'
	});
	
	var form = $('form');
	
	var vd = form.ValidationEngineEx({sround:'p[%v]'});
	
	var on_submit = function () {};
	
	function f_submit ()
	{
		var res = vd.validate_all();
		
		if (!res)
			return false;
		
		form.ajaxSubmit({
			dataType:'json',
			success: on_submit
		});
	}
	
	$('#btn_save').click(function ()
	{
		on_submit = function (data)
		{
			if (data.err_no)
			{
				alert(data.err_text);
				return;
			}
		};
		
		f_submit();
		
		return false;
	});
	
	$('#btn_sn').click(function ()
	{
		on_submit = function (data)
		{
			if (data.err_no)
			{
				alert(data.err_text);
				return;
			}
			
			form[0].reset();
		};
		
		f_submit();
		
		return false;
	});
});
</script>
<?=JsUtils::ob_end();?>