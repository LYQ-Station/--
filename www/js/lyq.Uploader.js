/**
 * Uploder
 * 
 * @author Steven
 */
function Uploader (configs)
{
	Uploader.instance_no++;
	
	this.configs = configs || {};
	this.instance_no = Uploader.instance_no;
}

Uploader.instance_no = 0;

Uploader.prototype.upload = function (url, callback)
{
	var form = $('<form method="post" enctype="multipart/form-data" style="display:none" />');
	form.attr('action', url ? url : (this.configs.url ? this.configs.url : '#'))
	var file_inp = $('<input type="file" name="upload" />');
	
	var self = this;
	
	form.append(file_inp);
	form.appendTo(document.body);
	
	file_inp.bind('change', function (evn)
	{
		var ifrm = $('<iframe id="_ifrm' + this.instance_no + '_" name="_ifrm' + this.instance_no + '_" style="display:none" />');
		ifrm.appendTo(document.body);
		form.attr('target', '_ifrm' + this.instance_no + '_');
		
		ifrm.bind('load', function ()
		{
			var data = undefined;
			try
			{
				data = $.parseJSON(this.contentDocument.body.innerHTML);
			}
			catch (e)
			{}
			
			if (callback && 'function' == typeof callback)
			{
				callback.call(this, data);
			}
			else if (self.configs.callback && 'function' == typeof self.configs.callback)
			{
				self.configs.callback.call(this, data);
			}
			
			ifrm.unbind();
			ifrm.attr('src', '#');
			ifrm.remove();
			form.remove();
		});
		
		form.submit();
	});
	
	file_inp[0].click();
}