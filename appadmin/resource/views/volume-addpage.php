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
                <th>Path:</th>
                <td>
                	<?php if (empty($this->resource['path_arr'])):?>
                    -
                    <?php else: foreach ($this->resource['path_arr'] as $id => $p): ?>
                     : <a href="<?=$this->buildUrl('info',null,null,array('id'=>$id))?>"><?=$p?></a>
                    <?php endforeach; endif;?>
                </td>
            </tr>
            <tr>
                <th>Parent Resource:</th>
                <td><input type="text" class="txt" name="p[rid]" value="<?=HTMLUtils::pick_value($this->item['rid'],$this->resource['id'])?>" /><?=$this->resource['title']?></td>
            </tr>
            <tr>
                <th>Title:</th>
                <td><input type="text" class="txt" name="p[title]" v="required" value="<?=$this->item['title']?>" /></td>
            </tr>
            <tr>
                <th>Cover:</th>
                <td>
                	<input type="text" class="txt" name="p[cover_id]" value="<?=$this->item['cover_id']?>" />
                    <button id="upf_bsw">Upload</button>
                </td>
            </tr>
            <tr>
                <th>Summary:</th>
                <td><textarea name="p[summary]"><?=$this->item['summary']?></textarea></td>
            </tr>
        </table>
    </form>
</div>
<?php $this->headLink()->appendStylesheet('/css/jquery.validationEngine.css');?>
<?php $this->headScript()->appendFile('/js/jquery.ValidationEngineEx.js');?>
<?php $this->headScript()->appendFile('/js/jquery.form.js');?>
<?php $this->headScript()->appendFile('/js/lyq.Uploader.js');?>
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
			
			alert('Success!');
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
	
	var uploader = new Uploader();
	$('#upf_bsw').click(function ()
	{
		//$('#xx')[0].click();
		uploader.upload();
		return false;
	});
});
</script>
<?=JsUtils::ob_end();?>