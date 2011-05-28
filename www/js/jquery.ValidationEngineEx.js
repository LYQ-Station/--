/*
 * Inline Form Validation Engine 1.7.3, jQuery plugin
 * 
 * Copyright(c) 2010, Cedric Dugas
 * http://www.position-absolute.com
 *	
 * Form validation engine allowing custom regex rules to be added.
 * Thanks to Francois Duquette and Teddy Limousin 
 * and everyone helping me find bugs on the forum
 * Licenced under the MIT Licence
 *
 */
(function($) {
	
	var allRules = {
		'required' : function () {
			return {
				result : !/^$/i.test(this.elem.value),
				msg : '* 此栏位是必填字段'
			}
		},
		'match' : function () {
			var reg = new RegExp(arguments[0].replace(/,/g, '|'), 'g');
			return {
				result : reg.test(this.elem.value),
				msg : '* 此栏位必须输入以下值[%s]'.replace(/%s/, arguments[0])
			}
		},
		'value' : function () {
			var num = arguments[0].split('-');
			return {
				result : (parseFloat(this.elem.value) > parseFloat(num[0])) && (parseFloat(this.elem.value) < parseFloat(num[1])),
				msg : '* 此栏位的值必须在 ' + num[0] + ' 到 ' + num[1] + ' 之间'
			}
		},
		'date' : function () {
			return {
				result : /^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(this.elem.value),
				msg : '* 日期格式错误，必须为 YYYY-MM-DD 格式'
			}
		},
		'uint' : function () {
			return {
				result : /^[1-9]\d+$/.test(this.elem.value),
				msg : '* 必须为正整数'
			}
		},
		'sint' : function () {
			return {
				result : /^\-[1-9]\d+$/.test(this.elem.value),
				msg : '* 必须为负整数'
			}
		},
		'number' : function () {
			return {
				result : /^[\-\+]?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)$/.test(this.elem.value),
				msg : '* 必须为小数'
			}
		},
		'phone' : function () {
			return {
				result : /^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/.test(this.elem.value),
				msg : '* 电话号码格式错误'
			}
		},
		'email' : function () {
			return {
				result : /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/.test(this.elem.value),
				msg : '* 邮件地址格式错误'
			}
		},
		'code' : function () {
			return {
				result : /^[a-zA-Z0-9]+$/.test(this.elem.value),
				msg : '* 只能输入字母数字'
			}
		},
		'name' : function () {
			return {
				result : /^([\u2E80-\u9FFF]|[a-zA-Z])+$/.test(this.elem.value),
				msg : '* 只能输入汉字或者字母'
			}
		},
		'pname' : function () {
			return {
				result : /^([\u2E80-\u9FFF]|[\w\-])+$/.test(this.elem.value),
				msg : '* 只能输入汉字字母数字'
			}
		},
		'num' : function () {
			return {
				result : /^[1-9][0-9]+$/.test(this.elem.value),
				msg : '* 只能输入数字且起始不为零'
			}
		},
		'allnum' : function () {
			return {
				result : /^[0-9]+$/.test(this.elem.value),
				msg : '* 只能输入数字'
			}
		},
		'tax' : function () {
			return {
				result : /^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/.test(this.elem.value),
				msg : '* 传真格式错误'
			}
		},
		'people' : function () {
			return {
				result : /^\d{17}[0-9xX]$|^\d{15}$/.test(this.elem.value),
				msg : '* 身份证号码错误'
			}
		}
	};
	
	var settings = {
		allrules:allRules,
		validationEventTriggers:"focusout",
		inlineValidation: true,
		returnIsValid:false,
		showTriangle:true,
		liveEvent:false,
		openDebug: true,
		unbindEngine:true,
		containerOverflow:false,
		containerOverflowDOM:"",
		ajaxSubmit: false,
		scroll:true,
		promptPosition: "topRight",	// OPENNING BOX POSITION, IMPLEMENTED: topLeft, topRight, bottomLeft, centerRight, bottomRight
		success : false,
		beforeSuccess :  function() {},
		failure : function() {},
		
		sround: '',
		
		style : {
			required : {'backgroundColor':'#ffcccc'},
			readonly : {'backgroundColor':'#f5f5f5'},
			relative : {'backgroundColor':'#FFFFB0'}
		}
	};
	
	function ValidationEngineEx ()
	{
		this.vd_arr = null;
	}
	
	ValidationEngineEx.EVN_PROMPT	= 'evn_prompt';
	
	ValidationEngineEx.prototype.validate_all = function ()
	{
		for (var i=0; i<this.vd_arr.length; i++)
		{
			$(this.vd_arr[i].elem).trigger(settings.validationEventTriggers);
		}

		for (i=0; i<this.vd_arr.length; i++)
		{
			if (/^-.*/.test(this.vd_arr[i].elem.getAttribute('v')))
				continue;
			
			if (false == this.vd_arr[i].result)
				return false;
		}

		return true;
	}
	
	ValidationEngineEx.prototype.validate = function (caller)
	{
		var promptText ='';	
		
		var rules = caller.elem.getAttribute('v').split(';');
		var rule = '';
		var fn;
		var fn_result;
		
		settings.showTriangle = true;
		
//		if($("input[name='"+callerName+"']").size()> 1 && (callerType == "radio" || callerType == "checkbox"))
//		{        // Hack for radio/checkbox group button, the validation go the first radio/checkbox of the group
//			caller = $("input[name='"+callerName+"'][type!=hidden]:first");
//			$.validationEngine.showTriangle = false;
//		}

		for (var i=0; i<rules.length;i++)
		{
			rule = rules[i].match(/(\w+)\[(.*?)\]/);

			if (!rule)
			{
				if (!settings.allrules[rules[i]])
					continue;

				fn = settings.allrules[rules[i]];
				fn_result = fn.apply(caller);
			}
			else
			{
				if (!settings.allrules[rule[1]])
					continue;

				fn = settings.allrules[rule[1]];
				fn_result = fn.call(caller, rule[2]);
			}

			caller.result = fn_result.result;

			promptText += fn_result.msg + "<br />";
		}
		
		if (caller.result == false)
		{
			this.buildPrompt(caller,promptText,"error");
		}else{
			this.closePrompt(caller);
		}
		
		return caller.result;
	}
	
	ValidationEngineEx.prototype.validate_fields = function (fields)
	{
		if ('string' === typeof fields)
			fields = [fields];
		
		for (var i=0; i<this.vd_arr.length; i++)
		{
			for (var j=fields.length-1; j>=0; j--)
			{
				if (this.vd_arr[i].elem.getAttribute('name') == fields[j])
				{
					$(this.vd_arr[i].elem).trigger(settings.validationEventTriggers);
					
				}
			}
		}
	}
	
	ValidationEngineEx.prototype.response_ajax = function (data)
	{
		if (!data || data.err_no != '9001')
			reutrn;
		
		if ('string' === typeof fields)
			fields = [fields];
		
		for (var i=0; i<this.vd_arr.length; i++)
		{
			for (var p in data.content)
			{
				if (this.vd_arr[i].elem.getAttribute('name') == settings.sround.replace('%v', p))
				{
					$(this.vd_arr[i].elem).trigger(ValidationEngineEx.EVN_PROMPT, data.content[p]);
					delete data.content[p];
				}
			}
		}
	}
	
	ValidationEngineEx.prototype.evn_show_prompt = function (evn, caller)
	{
		this.buildPrompt(caller, evn.data, 'error');
	}
	
	ValidationEngineEx.prototype.buildPrompt = function(caller,promptText,type,ajaxed,id) 
	{			// ERROR PROMPT CREATION AND DISPLAY WHEN AN ERROR OCCUR
		if (caller.prompt)
		{
			$(caller.prompt).remove();
			caller.prompt = null;
		}
		
		callerr = caller.elem;
		
		var divFormError = document.createElement('div');
		var formErrorContent = document.createElement('div');

		var linkTofield = this.linkTofield(caller);
		$(divFormError).addClass("formError");

		if(type == "pass")
			$(divFormError).addClass("greenPopup");
		if(type == "load")
			$(divFormError).addClass("blackPopup");
		if(ajaxed)
			$(divFormError).addClass("ajaxed");

		$(divFormError).addClass(linkTofield);
		$(formErrorContent).addClass("formErrorContent");

		if(settings.containerOverflow)		// Is the form contained in an overflown container?
			$(callerr).before(divFormError);
		else
			$("body").append(divFormError);

		$(divFormError).append(formErrorContent);

		if(settings.showTriangle != false){		// NO TRIANGLE ON MAX CHECKBOX AND RADIO
			var arrow = document.createElement('div');
			$(arrow).addClass("formErrorArrow");
			$(divFormError).append(arrow);
			if(settings.promptPosition == "bottomLeft" || settings.promptPosition == "bottomRight") {
				$(arrow).addClass("formErrorArrowBottom");
				$(arrow).html('<div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div>');
			}
			else if(settings.promptPosition == "topLeft" || settings.promptPosition == "topRight"){
				$(divFormError).append(arrow);
				$(arrow).html('<div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div>');
			}
		}
		$(formErrorContent).html(promptText);

		var calculatedPosition = this.calculatePosition(callerr,promptText,type,ajaxed,divFormError);
		calculatedPosition.callerTopPosition +="px";
		calculatedPosition.callerleftPosition +="px";
		calculatedPosition.marginTopSize +="px";
		$(divFormError).css({
			"top":calculatedPosition.callerTopPosition,
			"left":calculatedPosition.callerleftPosition,
			"marginTop":calculatedPosition.marginTopSize,
			"opacity":1
		});
		
		caller.prompt = divFormError;
		
		$(divFormError).click(function () {$(this).remove();})
	};
	
	ValidationEngineEx.prototype.updatePromptText = function(caller,promptText,type,ajaxed) 
	{	// UPDATE TEXT ERROR IF AN ERROR IS ALREADY DISPLAYED
		var linkTofield = this.linkTofield(caller);
		var updateThisPrompt =  "."+linkTofield;

		if(type == "pass")
			$(updateThisPrompt).addClass("greenPopup");
		else
			$(updateThisPrompt).removeClass("greenPopup");

		if(type == "load")
			$(updateThisPrompt).addClass("blackPopup");
		else
			$(updateThisPrompt).removeClass("blackPopup");

		if(ajaxed)
			$(updateThisPrompt).addClass("ajaxed");
		else
			$(updateThisPrompt).removeClass("ajaxed");

		$(updateThisPrompt).find(".formErrorContent").html(promptText);

		var calculatedPosition = this.calculatePosition(caller,promptText,type,ajaxed,updateThisPrompt);
		calculatedPosition.callerTopPosition +="px";
		calculatedPosition.callerleftPosition +="px";
		calculatedPosition.marginTopSize +="px";
		$(updateThisPrompt).animate({
			"top":calculatedPosition.callerTopPosition,
			"marginTop":calculatedPosition.marginTopSize
			});
	};
	
	ValidationEngineEx.prototype.calculatePosition = function(caller,promptText,type,ajaxed,divFormError)
	{
		var callerTopPosition,callerleftPosition,inputHeight,marginTopSize;
		var callerWidth =  $(caller).width();

		if(settings.containerOverflow){		// Is the form contained in an overflown container?
			callerTopPosition = 0;
			callerleftPosition = 0;
			inputHeight = $(divFormError).height();					// compasation for the triangle
			marginTopSize = "-"+inputHeight;
		}else{
			callerTopPosition = $(caller).offset().top;
			callerleftPosition = $(caller).offset().left;
			inputHeight = $(divFormError).height();
			marginTopSize = 0;
		}

		/* POSITIONNING */
		if(settings.promptPosition == "topRight"){ 
			if(settings.containerOverflow){		// Is the form contained in an overflown container?
				callerleftPosition += callerWidth -30;
			}else{
				callerleftPosition +=  callerWidth -30; 
				callerTopPosition += -inputHeight; 
			}
		}
		if(settings.promptPosition == "topLeft"){
			callerTopPosition += -inputHeight -10;
		}

		if(settings.promptPosition == "centerRight"){
			callerleftPosition +=  callerWidth +13;
		}

		if(settings.promptPosition == "bottomLeft"){
			callerTopPosition = callerTopPosition + $(caller).height() + 15;
		}
		if(settings.promptPosition == "bottomRight"){
			callerleftPosition +=  callerWidth -30;
			callerTopPosition +=  $(caller).height() +5;
		}
		return {
			"callerTopPosition":callerTopPosition,
			"callerleftPosition":callerleftPosition,
			"marginTopSize":marginTopSize
		};
	};
	
	ValidationEngineEx.prototype.linkTofield = function(caller)
	{
		var linkTofield = "formError" + caller.prompt_id;
		//var linkTofield = $(caller).attr("id") + "formError";
		linkTofield = linkTofield.replace(/\[/g,""); 
		linkTofield = linkTofield.replace(/\]/g,"");
		return linkTofield;
	};
	
	ValidationEngineEx.prototype.closePrompt = function(caller,outside) 
	{						// CLOSE PROMPT WHEN ERROR CORRECTED
		if (caller.prompt)
		{
			$(caller.prompt).remove();
			caller.prompt = null;
		}
		
//		if(outside){
//			$(caller).fadeTo("fast",0,function(){
//				$(caller).remove();
//			});
//			return false;
//		}
//
//		// orefalo -- review conditions non sense
//		if(typeof(ajaxValidate)=='undefined')
//		{
//			ajaxValidate = false;
//		}
//		if(!ajaxValidate){
//			var linkTofield = this.linkTofield(caller);
//			var closingPrompt = "."+linkTofield;
//			$(closingPrompt).fadeTo("fast",0,function(){
//				$(closingPrompt).remove();
//			});
//		}

		return false;
	};
	
	$.fn.ValidationEngineEx = function (configs)
	{
		configs = configs || {};
		
		for (var p in configs)
			settings[p] = configs[p];
		
		var return_obj = new ValidationEngineEx();
		
		var vd_arr = [];
		function TempClass (elem)
		{
			var self = this;
			this.result = true;
			this.elem = elem;
			this.prompt = null;
			this.prompt_id = TempClass.increase_no++;
			$(this.elem).bind(settings.validationEventTriggers, function(){return_obj.validate(self);});
			$(this.elem).bind(ValidationEngineEx.EVN_PROMPT, function (evn, data){evn.data = data; return_obj.evn_show_prompt(evn, self);});
		}

		TempClass.increase_no = 0;

		var v_boxes = $(this).find("[v]");
		v_boxes.each(function ()
		{
			if (/required/.test(this.getAttribute('v')))
			{
				$(this).css(settings.style.required);
			}
			else if (/readonly/.test(this.getAttribute('v')))
			{
				$(this).css(settings.style.readonly);
				$(this).attr('readonly', true);
			}
			else if (/relative/.test(this.getAttribute('v')))
			{
				$(this).css(settings.style.relative);
				$(this).attr('readonly', true);
			}
			else if (/relative/.test(this.getAttribute('v')))
			{
				$(this).attr('disabled', true);
			}
		});
		
		v_boxes.not("[type=checkbox]").each(function ()
		{
			vd_arr.push(new TempClass(this));
		});

		$(this).find("[v][type=checkbox]").each(function ()
		{
			vd_arr.push(new TempClass(this));
		});
		
		return_obj.vd_arr = vd_arr;
		return return_obj;
	};
})(jQuery);