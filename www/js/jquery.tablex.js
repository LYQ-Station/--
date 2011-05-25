/**
 * tablex
 */
;(function($){
	
$.fn.tablex = function(configs){
	var mouse_over_fn = function ()
	{
		this.style.backgroundColor = '#ffffcc';
	};
	
	var mouse_out_fn = function ()
	{
		this.style.backgroundColor = '';
	};
	
	return this.each(function ()
	{
		var table = $(this);
		
		table.find('tr:not(:has(th))').hover(mouse_over_fn, mouse_out_fn);
	});
};

})(jQuery);