<div class="page_head">
    <div class="page_title"><?=$this->item['title']?></div>
    <div class="page_nav">
    	<button id="btn_save">Save</button>
        <button id="btn_sn">Save and create new</button>
        <button id="btn_cv" link="<?=$this->buildUrl('addpage','volume',null,array('rid'=>$this->item['id']))?>">Create new volume</button>
    	<a href="<?=$this->buildUrl('list')?>">Cancel</a>
    </div>
</div>
<div class="page_body_b">
	<div id="tabs">
    	<ul>
            <li><a href="#t-info">Information</a></li>
            <li><a href="#t-volumes">Volumes</a></li>
            <li><a href="#t-ifrm">Additional</a></li>
        </ul>
        <div id="t-info">
        <form action="<?=$this->submit_link?>" method="post">
            <input type="hidden" name="id" value="<?=$this->item['id']?>" />
            <table class="tinfo">
                <tr>
                    <th>Path:</th>
                    <td>
                    <?php if (empty($this->item['path_arr'])):?>
                    -
                    <?php else: foreach ($this->item['path_arr'] as $id => $p): ?>
                     : <a href="<?=$this->buildUrl('info',null,null,array('id'=>$id))?>"><?=$p?></a>
                    <?php endforeach; endif;?>
                    </td>
                </tr>
                <tr>
                    <th>Parent Resource:</th>
                    <td>
                        <input type="text" class="txt" id="pid_ac" value="<?=$this->item['parent']['title']?>" />
                        <input type="hidden" id="pid" name="p[pid]" value="<?=$this->item['pid']?>" />
                    </td>
                </tr>
                <tr>
                    <th>Full Title:</th>
                    <td><input type="text" class="txt" readonly="readonly" value="<?=$this->item['full_title']?>" /></td>
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
                    <td><input type="text" class="txt" name="p[cover]" value="<?=$this->item['cover']?>" /></td>
                </tr>
                <tr>
                    <th>Summary:</th>
                    <td><textarea id="summary" name="p[summary]"><?=$this->item['summary']?></textarea></td>
                </tr>
            </table>
        </form>
        </div>
        <div id="t-volumes">
            <table class="ttable">
                <tr>
                    <th width="25"><input type="checkbox" /></th>
                    <th width="100">ID</th>
                    <th width="100">RID</th>
                    <th width="100">ROOT ID</th>
                    <th>Title</th>
                    <th width="150">Create Time</th>
                    <th width="120">操作</th>
                </tr>
                <?php if (empty($this->volumes)):?>
                <tr>
                    <td colspan="7" align="center">暂无记录。</td>
                </tr>
                <?php else: foreach ($this->volumes as $item):?>
                <tr>
                    <td width="25"><input type="checkbox" /></td>
                    <td><?=$item['id']?></td>
                    <td><?=$item['rid']?></td>
                    <td><?=$item['rootid']?></td>
                    <td><?=$item['title']?></td>
                    <td><?=$item['create_time']?></td>
                    <td width="170" class="op">
                        <a href="<?=$this->buildUrl('info','volume',null,array('id'=>$item['id']))?>">Info</a>
                        <a class="a_dis" href="#" lang="<?=$item['id']?>" status="<?=$item['status']?>">Disable</a>
                        <a class="a_del" href="#" lang="<?=$item['id']?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; endif;?>
            </table>
        </div>
        <div id="t-ifrm">
        	<iframe id="ifrm" width="100%" height="800"></iframe>
        </div>
    </div>
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
	
	$('.ttable').tablex();
	
	$('#tabs').tabs();
	$('#tabs').tabs('');
	
	$('a[href$=ifrm]').hide();
	
	var form = $('form');
	var ifrm = $('#ifrm')
	
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
	
	$('#btn_cv').click(function ()
	{
		$('a[href$=ifrm]').show();
		$('a[href$=ifrm]').html('Create new Volume');
		$('#tabs').tabs('select', 2);
		ifrm.attr('src', $(this).attr('link'));
		
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
	
	$("#pid_ac").autocomplete({
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
			$("#pid").val(ui.item.data.id);
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