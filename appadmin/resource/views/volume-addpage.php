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
                <td>
                	<input type="text" class="txt" id="rid_ac" value="<?=$this->resource['title']?>" />
                    <input type="hidden" id="rid" name="p[rid]" value="<?=HTMLUtils::pick_value($this->item['rid'],$this->resource['id'])?>" />
                </td>
            </tr>
            <tr>
                <th>Title:</th>
                <td><input type="text" class="txt" name="p[title]" v="required" value="<?=$this->item['title']?>" /></td>
            </tr>
            <tr>
                <th>Title EN:</th>
                <td><input type="text" class="txt" name="p[title_en]" value="<?=$this->item['title_en']?>" /></td>
            </tr>
            <tr>
                <th>Title JP:</th>
                <td><input type="text" class="txt" name="p[title_jp]" value="<?=$this->item['title_jp']?>" /></td>
            </tr>
            <tr>
                <th>Cover:</th>
                <td>
                	<img class="cover" width="300" height="300" />
                	<input type="hidden" name="p[cover]" value="" />
                    <button id="upf_bsw">Upload</button>
                </td>
            </tr>
            <tr>
                <th>Summary:</th>
                <td><textarea id="summary" name="p[summary]"><?=$this->item['summary']?></textarea></td>
            </tr>
        </table>
    </form>
</div>
<?php $this->headLink()->appendStylesheet('/css/jquery.validationEngine.css');?>
<?php $this->headScript()->appendFile('/js/jquery.ValidationEngineEx.js');?>
<?php $this->headScript()->appendFile('/js/jquery.form.js');?>
<?php $this->headScript()->appendFile('/js/lyq.Uploader.js');?>
<?php $this->headScript()->appendFile('/plugins/ckeditor/ckeditor.js');?>
<?=JsUtils::ob_start();?>
<script>
$(function ()
{
	var ckeditor = CKEDITOR.replace('summary');
	
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
		$('#summary').val(ckeditor.getData());
		
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
	
	var uploader = new Uploader({
		url: '<?=$this->buildUrl('upload')?>',
		callback: function (data)
		{
			if ('' != data.err_no)
			{
				alert(data.err_text);
				return;
			}
			
			form[0]['p[cover]'].value = data.content;
			$('.cover').attr('src', '<?=$this->buildUrl('cover')?>?' + $.param({cover:data.content}));
		}
	});
	
	$('#upf_bsw').click(function ()
	{
		uploader.upload();
		return false;
	});
	
	$("#rid_ac").autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: '<?=$this->buildUrl('ajaxparent','resource')?>',
				dataType: "json",
				data: {
					maxRows: 12,
					keyword: request.term
				},
				success: function( data ) {
					response( $.map( data.content, function( item ) {
						return {
							label: item.title,
							value: item.title,
							data: item
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			$("#rid").val(ui.item.data.id);
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
});
</script>
<?=JsUtils::ob_end();?>