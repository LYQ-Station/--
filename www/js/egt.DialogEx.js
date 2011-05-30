/**
 * dialog
 * egt.dialog
 *
 * @author Steven
 */
var DialogEx = function (url, options) 
{
	if (!url)
		return null;
		
	DialogEx.instance_no++;
	
	this.url = url;
	this.options = options || {};
	
	this.options.features = options.features || '';
	
	this.name = options.name || 'dialog__' + DialogEx.instance_no;
	this.return_value = null;
	
	if (this.options.events)
	{
		for (var e in this.options.events)
		{
			$(this).bind(e, this, this.options.events[e]);
		}	
	}
};

DialogEx.instance_no = 0;

DialogEx.prototype.open = function ()
{
	var win_w = this.options.features.match(/width=(\d+)/);
	if (win_w)
	{
		win_w = win_w[1];
		var win_x = window.screenX + (window.outerWidth - win_w) * .5;
		
		if (this.options.features.match(/left/))
			this.options.features = this.options.features.replace(/left=(\d+)/, 'left='+win_x);
		else
			this.options.features = 'left=' + win_x + ',' + this.options.features;
	}
	
	var win_h = this.options.features.match(/height=(\d+)/);	
	if (win_h)
	{
		win_h = win_h[1];
		var win_y = window.screenY + (window.outerHeight - win_h) * .5;
		
		if (this.options.features.match(/top/))
			this.options.features = this.options.features.replace(/top=(\d+)/, 'top='+win_y);
		else
			this.options.features = 'top=' + win_y + ',' + this.options.features;
	}
	
	this.win = window.open(this.url, this.name, this.options.features);
	
	this.win.navigator['dialog_events_handle'] = $(this);
	
	this.win.focus();
	
	if (!win_x || !win_y)
	{
		win_x = win_x ? win_x : this.win.opener.screenX + (this.win.opener.outerWidth - this.win.outerWidth) * .5;
		win_y = win_y ? win_y : this.win.opener.screenY + (this.win.opener.outerHeight - this.win.outerHeight) * .5;
		
		this.win.moveTo(win_x, win_y);
	}
}

DialogEx.prototype.open_modal = function (args)
{
	var win_w = this.options.features.match(/width=(\d+)/);
	if (win_w)
	{
		win_w = win_w[1];
		var win_x = window.screenX + (window.outerWidth - win_w) * .5;
		
		this.options.features = this.options.features.replace(/width/, 'dialogWidth');
		
		if (this.options.features.match(/left/))
			this.options.features = this.options.features.replace(/left=(\d+)/, 'dialogLeft='+win_x);
		else
			this.options.features = 'dialogLeft=' + win_x + ',' + this.options.features;
	}
	
	var win_h = this.options.features.match(/height=(\d+)/);	
	if (win_h)
	{
		win_h = win_h[1];
		
		this.options.features = this.options.features.replace(/top=(\d+)/, 'dialogTop='+win_y);
		
		var win_y = window.screenY + (window.outerHeight - win_h) * .5;
		
		if (this.options.features.match(/top/))
			this.options.features = this.options.features.replace(/top=(\d+)/, 'dialogTop='+win_y);
		else
			this.options.features = 'dialogTop=' + win_y + ',' + this.options.features;
			
		this.options.features = this.options.features.replace(/height/, 'dialogHeight');
	}
	
	this.options.features = this.options.features.replace(/\,/g, ';');
	
	var args = args || 1;
	if (1 != args)
		args.dialog = $(this);
	else
		args = $(this);

	this.return_value = window.showModalDialog(this.url, args, this.options.features);
}

DialogEx.init_client_handle = function ()
{
	if (!window.navigator['dialog_events_handle'])
		window.navigator['dialog_events_handle'] = window.dialogArguments;
}

DialogEx.get_client_handle = function ()
{
	return window.navigator['dialog_events_handle'];
}