/**
 * BaseGrid
 * 
 * @author Steven
 */

var lyq = lyq || {};

lyq.Table = function (table, configs)
{
	table = $(table);
	this.configs = {
		enabled : true,
		allow_multiple_selection : false,
		post_name : 'bg'
	};
	
	if (configs)
	{
		for (var p in configs)
		{
			this.configs[p] = configs[p];
		}
	}
	
	this.selected_index = -1;
	this.selected_indies = [];
	
	this.table = table;
	this.header = table.find('thead');
	this.body = table.find('tbody');
	this.foot = table.find('tfoot');

	var self = this;
	
		//set columes
	var columes_width = 0;
	if (this.header.length)
	{
		var col;
		var header_cols = this.header[0].rows[0].cells;
		for (var i=header_cols.length-1; i>=0; i++)
		{
			col = document.createElement('col');
			
			col.setAttribute('_field_', header_cols[i].getAttribute('_field_'));
			col.width = header_cols[i].width ? header_cols[i].width : header_cols[i].offsetWidth;
			table.prepend(col);
			
			columes_width += parseInt(col.width);
		}
	}
	else if (this.configs.columes)
	{
		var col;
		for (var i=this.configs.columes.length-1; i>=0; i--)
		{
			col = document.createElement('col');
			
			if ('string' == typeof this.configs.columes[i])
			{
				col.setAttribute('_field_', this.configs.columes[i]);
			}
			else if ('object' == typeof this.configs.columes[i])
			{
				for (var p in this.configs.columes[i])
				{
					col.setAttribute(p, this.configs.columes[i][p]);
				}
			}
			
			columes_width += parseInt(col.width);
			
			table.prepend(col);
		}
	}
	else if (this.configs.header)
	{
		var col;
		var header_cols = this.configs.header.cells;
		for (var i=header_cols.length-1; i>=0; i--)
		{
			col = document.createElement('col');
			
			col.setAttribute('_field_', header_cols[i].getAttribute('_field_') ? header_cols[i].getAttribute('_field_') : i);
			col.width = header_cols[i].width ? header_cols[i].width : header_cols[i].offsetWidth;

			table.prepend(col);
			
			columes_width += parseInt(col.width);
		}
		
		table[0].width = this.configs.header.offsetParent.width ? this.configs.header.offsetParent.width : this.configs.header.offsetParent.offsetWidth;
	}
	
		//set events
	if (self.configs.events)
	{
		for (var e in self.configs.events)
		{
			this.body.find('tr').live(e, self, self.configs.events[e]);
		}
	}
	
	this.refresh_cells();
	
	delete this.configs.enabled;
	this.set_enabled(configs.enabled);
}

lyq.Table.prototype.refresh_cells = function ()
{
	var rows = this.table[0].rows;
	var cells;
	var div;
	var cols = this.table[0].getElementsByTagName('col');
	
	for (var i=0; i<rows.length; i++)
	{
		cells = rows[i].cells;
		
		for (var j=0; j<cells.length; j++)
		{
			this.render_cell(cells[j], cols);
		}
	}
}

lyq.Table.prototype.render_cell = function (cell, cols)
{
	if (cols[cell.cellIndex])
	{
		cell.setAttribute('_field_', cols[cell.cellIndex].getAttribute('_field_'));
	}
	else
	{
		cell.setAttribute('_field_', cell.cellIndex);
	}
	
	var div = $('<div class="cell">');
	
	var jcell = $(cell);
	
	if (this.configs.editable)
	{
		var span = $('span');
		var flag = true;
		
		for (var i=0; i<cell.childNodes.length; i++)
		{
			if (1 == cell.childNodes[i].nodeType)
			{
				flag = false;
				break;
			}
		}
		
		if (1 == span.length || flag)
		{
			var json;
			var label = $('<label>');
			var input = $('<input type="hidden">');
			input.attr('name', this.configs.post_name + '[' + cell.parentNode.rowIndex + '][' + jcell.attr('_field_') + ']');
			
			try
			{
				json = span.length ? $.parseJSON(span.html()) : $.parseJSON(cell.innerHTML);
			}
			catch (e)
			{}
			
			if (json && 'object' == typeof json)
			{
				label.html(json.label);
				input.val(json.data ? json.data : json.label);
			}
			else
			{
				label.html(cell.innerHTML);
				input.val(cell.innerHTML);
			}
			
			div.append(label);
			div.append(input);
		}
		else
		{
			div.append(cell.childNodes);
		}
	}
	else
	{
		div.append(cell.childNodes);
	}
	
	jcell.empty();
	jcell.append(div);
}

lyq.Table.prototype.redraw = function ()
{
//	var rows = $this.body[0].rows;
	
}

lyq.Table.prototype.set_enabled = function (enabled)
{
	enabled = true && enabled;
	
	if (this.configs.enabled == enabled)
	{
		return;
	}
	
	if (enabled)
	{
		this.table.find('._table_cover').remove();
	}
	else
	{
		var cover = document.createElement('div');
		cover.setAttribute('class', '_table_cover');
		
		with (cover.style)
		{
			backgroundColor = '#eee';
			position = 'absolute';
			opacity = '.3';
			top = '0px';
			left = '0px';
			bottom = '0px';
			right = '0px';
			zIndex = '999';
			width = this.table[0].width + 'px';
		}
		
		this.table.before(cover);
	}
	
	this.configs.enabled = enabled;
}

lyq.Table.prototype.selecte_all = function ()
{
	if (!this.configs.allow_multiple_selection)
		return;
	
	$(this).troggle('event_selected_all');
}

lyq.Table.prototype.clear_selection = function ()
{
	this.selected_index = -1;
	
	var rows = this.table[0].rows;
	for (var i=0; i<rows; i++)
	{
		rows[i].style.backgroundColor = '';
		rows[i].setAttribute('_sel_', null);
	}
}

lyq.BaseGrid = function (table, configs)
{
	table = $(table);
	this.configs = {};
	
	if (configs)
	{
		for (var p in configs)
		{
			this.configs[p] = configs[p];
		}
	}
	
	var header_div = table.find('div.thead');
	var body_div = table.find('div.tbody');
	
	this.header = header_div.find('table:first');
	this.body = body_div.find('table:first');
	this.footer = table.find('div.tfoot');
	
	if (this.header.length)
	{
		var header_cols = this.header[0].rows[0].cells;
		var columes_width = 0;
		for (var i=0; i<header_cols.length; i++)
		{
			columes_width += parseInt(header_cols[i].width ? header_cols[i].width : header_cols[i].offsetWidth);
		}
		
		this.header[0].width = columes_width;
		
			//为子TABLE配置HEADER
		this.configs.header = this.header[0].rows[0];
	}
	
	
	table.find('div.tbody').scroll(function ()
	{
		header_div.scrollLeft(body_div.scrollLeft());
	});
	
	this.tbody = new lyq.Table(this.body, configs);
}

lyq.BaseGrid.prototype.init = function ()
{
	
}