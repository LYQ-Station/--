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
	
	var allRules = 	{
	};

	var egtRules = {
		'required' : function () {
			return {
				result : !/^$/i.test(this.elem.value),
				msg : '* 此栏位是必填字段'
			}
		},
		'match' : function () {
			var reg = new RegExp(arguments[0].replace(/,/g, '|'), 'g');
			return {
				result : reg.test(this.value),
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

	$.fn.validationEngine = function(settings) {
 	settings = jQuery.extend({
		allrules:allRules,
		egturles:egtRules,
		validationEventTriggers:"focusout",					
		inlineValidation: true,	
		returnIsValid:false,
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
		
		style : {
			required : {'backgroundColor':'#00C8C8'},
			readonly : {'backgroundColor':'#f5f5f5'},
			relative : {'backgroundColor':'#FFFFB0'}
		}
	}, settings);	
	$.validationEngine.settings = settings;
	$.validationEngine.ajaxValidArray = [];	// ARRAY FOR AJAX: VALIDATION MEMORY

	var vd_arr = [];
	function TempClass (elem)
	{
		var self = this;
		this.result = true;
		this.elem = elem;
		this.prompt_id = TempClass.increase_no++;
		$(this.elem).bind(settings.validationEventTriggers, function(){_inlinEvent(self);});
	}

	TempClass.increase_no = 0;

	function ReturnClass ()
	{
		
	}

	ReturnClass.prototype.check_all = function ()
	{
		for (var i=0; i<vd_arr.length; i++)
		{
			$(vd_arr[i].elem).trigger(settings.validationEventTriggers);
		}

		for (i=0; i<vd_arr.length; i++)
		{
			if (/^-.*/.test(vd_arr[i].elem.getAttribute('v')))
				continue;

			if (false == vd_arr[i].result)
				return false;
		}

		return true;
	}

	if(settings.inlineValidation === true){ 		// Validating Inline ?
		if(!settings.returnIsValid){					// NEEDED FOR THE SETTING returnIsValid
			
			if(settings.liveEvent){						// LIVE event, vast performance improvement over BIND
				$(this).find("[v]").live(settings.validationEventTriggers,
					function(caller){
						if($(caller).attr("type") != "checkbox")
							_inlinEvent(this);
					});
				$(this).find("[v][type=checkbox]").live("click", function(caller){_inlinEvent(this);});
			}
			else
			{
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
				
				//v_boxes.not("[type=checkbox]").bind(settings.validationEventTriggers, function(){_inlinEvent(this);});
				//$(this).find("[v][type=checkbox]").bind("click", function(){_inlinEvent(this);});
			}
			
			// what the hell orefalo
			//firstvalid = false;
		}
		
		function _inlinEvent(caller){
			$.validationEngine.settings = settings;
			if($.validationEngine.intercept === false || !$.validationEngine.intercept){		// STOP INLINE VALIDATION THIS TIME ONLY
				$.validationEngine.onSubmitValid=false;
				$.validationEngine.loadValidation(caller); 
			}else{
				$.validationEngine.intercept = false;
			}
		}
	}
	
	$(this).bind("submit", function(caller){   // ON FORM SUBMIT, CONTROL AJAX FUNCTION IF SPECIFIED ON DOCUMENT READY
		
		for (var i=0; i<vd_arr.length; i++)
		{
			$(vd_arr[i].elem).trigger(settings.validationEventTriggers);
		}

		for (i=0; i<vd_arr.length; i++)
		{
			if (/^-.*/.test(vd_arr[i].elem.getAttribute('v')))
				continue;
			
			if (false == vd_arr[i].result)
				return false;
		}

		return true;
	});

	$(".formError").live("click",function(){	 // REMOVE BOX ON CLICK
		$(this).remove();
		//$(this).fadeOut(150,function(){ $(this).remove(); });
	});

	return new ReturnClass();
};	
$.validationEngine = {
	defaultSetting : function(caller) {		// NOT GENERALLY USED, NEEDED FOR THE API, DO NOT TOUCH
		if($.validationEngineLanguage){				
			allRules = $.validationEngineLanguage.allRules;
		}else{
			$.validationEngine.debug("Validation engine rules are not loaded check your external file");
		}	
		settings = {
			allrules:allRules,
			validationEventTriggers:"blur",					
			inlineValidation: true,	
			containerOverflow:false,
			containerOverflowDOM:"",
			returnIsValid:false,
			scroll:true,
			unbindEngine:true,
			ajaxSubmit: false,
			promptPosition: "topRight",	// OPENNING BOX POSITION, IMPLEMENTED: topLeft, topRight, bottomLeft, centerRight, bottomRight
			success : false,
			failure : function() {}
		};	
		$.validationEngine.settings = settings;
	},
	loadValidation : function(caller) {		// GET VALIDATIONS TO BE EXECUTED
		if(!$.validationEngine.settings)
			$.validationEngine.defaultSetting();
		
		var str = $(caller.elem).attr('v');
		var pattern = /\[|;|\]/;
		var result= str.split(pattern);
		
		var validateCalll = $.validationEngine.validateCall(caller,result);
		return validateCalll;
	},
	validateCall : function(caller,rules) {	// EXECUTE VALIDATION REQUIRED BY THE USER FOR THIS FIELD
		var promptText ="";	
		
		// what the hell!
		//caller = caller;
		ajaxValidate = false;
		var callerName = $(caller.elem).attr("name");
		$.validationEngine.showTriangle = true;
		var callerType = $(caller.elem).attr("type");

		_egt(caller);

		radioHack();

		if (caller.result == false){
			var linkTofieldText = "." +$.validationEngine.linkTofield(caller);
			if(linkTofieldText != "."){
				if(!$(linkTofieldText)[0]){
					$.validationEngine.buildPrompt(caller,promptText,"error");
				}else{	
					$.validationEngine.updatePromptText(caller,promptText);
				}	
			}else{
				$.validationEngine.updatePromptText(caller,promptText);
			}
		}else{
			$.validationEngine.closePrompt(caller);
		}			
		/* UNFORTUNATE RADIO AND CHECKBOX GROUP HACKS */
		/* As my validation is looping input with id's we need a hack for my validation to understand to group these inputs */
		function radioHack(){
	      if($("input[name='"+callerName+"']").size()> 1 && (callerType == "radio" || callerType == "checkbox")) {        // Hack for radio/checkbox group button, the validation go the first radio/checkbox of the group
	          caller = $("input[name='"+callerName+"'][type!=hidden]:first");     
	          $.validationEngine.showTriangle = false;
	      }      
	    }
		/* VALIDATION FUNCTIONS */
			 // VALIDATE FOR EGT
		function _egt (caller)
		{
			var rules = caller.elem.getAttribute('v').split(';');
			var rule = '';
			var fn;
			var fn_result;

			for (var i=0; i<rules.length;i++)
			{
				rule = rules[i].match(/(\w+)\[(.*?)\]/);
				
				if (!rule)
				{
					if (!$.validationEngine.settings.egturles[rules[i]])
						continue;

					fn = $.validationEngine.settings.egturles[rules[i]];
					fn_result = fn.apply(caller);
				}
				else
				{
					if (!$.validationEngine.settings.egturles[rule[1]])
						continue;
					
					fn = $.validationEngine.settings.egturles[rule[1]];
					fn_result = fn.call(caller, rule[2]);
				}

				caller.result = fn_result.result;

				promptText += fn_result.msg + "<br />";
			}
		}

		return caller.result;
	},
	submitForm : function(caller){

		if ($.validationEngine.settings.success) {	// AJAX SUCCESS, STOP THE LOCATION UPDATE
			if($.validationEngine.settings.unbindEngine) $(caller).unbind("submit");
			var serializedForm = $(caller).serialize();
			$.validationEngine.settings.success && $.validationEngine.settings.success(serializedForm);
			return true;
		}
		return false;
	},
	buildPrompt : function(caller,promptText,type,ajaxed,id) {			// ERROR PROMPT CREATION AND DISPLAY WHEN AN ERROR OCCUR
		if(!$.validationEngine.settings) {
			$.validationEngine.defaultSetting();
		}
		//var deleteItself = "." + $(caller).attr("id") + "formError";
		var deleteItself = ".z" + caller.prompt_id + "formError";

		callerr = caller.elem;
		if($(deleteItself)[0]) {
			$(deleteItself).stop();
			$(deleteItself).remove();
		}
		var divFormError = document.createElement('div');
		var formErrorContent = document.createElement('div');
		
		var linkTofield = $.validationEngine.linkTofield(caller);
		$(divFormError).addClass("formError");
		
		if(type == "pass")
			$(divFormError).addClass("greenPopup");
		if(type == "load")
			$(divFormError).addClass("blackPopup");
		if(ajaxed)
			$(divFormError).addClass("ajaxed");
		
		$(divFormError).addClass(linkTofield);
		$(formErrorContent).addClass("formErrorContent");
		
		if($.validationEngine.settings.containerOverflow)		// Is the form contained in an overflown container?
			$(callerr).before(divFormError);
		else
			$("body").append(divFormError);
				
		$(divFormError).append(formErrorContent);
			
		if($.validationEngine.showTriangle != false){		// NO TRIANGLE ON MAX CHECKBOX AND RADIO
			var arrow = document.createElement('div');
			$(arrow).addClass("formErrorArrow");
			$(divFormError).append(arrow);
			if($.validationEngine.settings.promptPosition == "bottomLeft" || $.validationEngine.settings.promptPosition == "bottomRight") {
				$(arrow).addClass("formErrorArrowBottom");
				$(arrow).html('<div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div>');
			}
			else if($.validationEngine.settings.promptPosition == "topLeft" || $.validationEngine.settings.promptPosition == "topRight"){
				$(divFormError).append(arrow);
				$(arrow).html('<div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div>');
			}
		}
		$(formErrorContent).html(promptText);
		
		var calculatedPosition = $.validationEngine.calculatePosition(callerr,promptText,type,ajaxed,divFormError);
		calculatedPosition.callerTopPosition +="px";
		calculatedPosition.callerleftPosition +="px";
		calculatedPosition.marginTopSize +="px";
		$(divFormError).css({
			"top":calculatedPosition.callerTopPosition,
			"left":calculatedPosition.callerleftPosition,
			"marginTop":calculatedPosition.marginTopSize,
			"opacity":0
		});
		//orefalo - what the hell
		//return $(divFormError).animate({"opacity":0.87},function(){return true;});
		return $(divFormError).animate({"opacity":0.87});	
	},
	updatePromptText : function(caller,promptText,type,ajaxed) {	// UPDATE TEXT ERROR IF AN ERROR IS ALREADY DISPLAYED
		
		var linkTofield = $.validationEngine.linkTofield(caller);
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
		
		var calculatedPosition = $.validationEngine.calculatePosition(caller,promptText,type,ajaxed,updateThisPrompt);
		calculatedPosition.callerTopPosition +="px";
		calculatedPosition.callerleftPosition +="px";
		calculatedPosition.marginTopSize +="px";
		$(updateThisPrompt).animate({"top":calculatedPosition.callerTopPosition,"marginTop":calculatedPosition.marginTopSize});
	},
	calculatePosition : function(caller,promptText,type,ajaxed,divFormError){
		
		var callerTopPosition,callerleftPosition,inputHeight,marginTopSize;
		var callerWidth =  $(caller).width();
		
		if($.validationEngine.settings.containerOverflow){		// Is the form contained in an overflown container?
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
		if($.validationEngine.settings.promptPosition == "topRight"){ 
			if($.validationEngine.settings.containerOverflow){		// Is the form contained in an overflown container?
				callerleftPosition += callerWidth -30;
			}else{
				callerleftPosition +=  callerWidth -30; 
				callerTopPosition += -inputHeight; 
			}
		}
		if($.validationEngine.settings.promptPosition == "topLeft"){callerTopPosition += -inputHeight -10;}
		
		if($.validationEngine.settings.promptPosition == "centerRight"){callerleftPosition +=  callerWidth +13;}
		
		if($.validationEngine.settings.promptPosition == "bottomLeft"){
			callerTopPosition = callerTopPosition + $(caller).height() + 15;
		}
		if($.validationEngine.settings.promptPosition == "bottomRight"){
			callerleftPosition +=  callerWidth -30;
			callerTopPosition +=  $(caller).height() +5;
		}
		return {
			"callerTopPosition":callerTopPosition,
			"callerleftPosition":callerleftPosition,
			"marginTopSize":marginTopSize
		};
	},
	linkTofield : function(caller){
		var linkTofield = caller.prompt_id + "formError";
		//var linkTofield = $(caller).attr("id") + "formError";
		linkTofield = linkTofield.replace(/\[/g,""); 
		linkTofield = linkTofield.replace(/\]/g,"");
		return linkTofield;
	},
	closePrompt : function(caller,outside) {						// CLOSE PROMPT WHEN ERROR CORRECTED
		if(!$.validationEngine.settings){
			$.validationEngine.defaultSetting();
		}
		if(outside){
			$(caller).fadeTo("fast",0,function(){
				$(caller).remove();
			});
			return false;
		}
		
		// orefalo -- review conditions non sense
		if(typeof(ajaxValidate)=='undefined')
		{ajaxValidate = false;}
		if(!ajaxValidate){
			var linkTofield = $.validationEngine.linkTofield(caller);
			var closingPrompt = "."+linkTofield;
			$(closingPrompt).fadeTo("fast",0,function(){
				$(closingPrompt).remove();
			});
		}
	},
	debug : function(error) {
		if(!$.validationEngine.settings.openDebug) return false;
		if(!$("#debugMode")[0]){
			$("body").append("<div id='debugMode'><div class='debugError'><strong>This is a debug mode, you got a problem with your form, it will try to help you, refresh when you think you nailed down the problem</strong></div></div>");
		}
		$(".debugError").append("<div class='debugerror'>"+error+"</div>");
	},			
	submitValidation : function(caller) {					// FORM SUBMIT VALIDATION LOOPING INLINE VALIDATION
		var stopForm = false;
		$.validationEngine.ajaxValid = true;
		var toValidateSize = $(caller).find("[v]").size();
		
		$(caller).find("[v]").each(function(){
			var linkTofield = $.validationEngine.linkTofield(this);
			
			if(!$("."+linkTofield).hasClass("ajaxed")){	// DO NOT UPDATE ALREADY AJAXED FIELDS (only happen if no normal errors, don't worry)
				var validationPass = $.validationEngine.loadValidation(this);
				return(validationPass) ? stopForm = true : "";					
			};
		});
		var ajaxErrorLength = $.validationEngine.ajaxValidArray.length;		// LOOK IF SOME AJAX IS NOT VALIDATE
		for(var x=0;x<ajaxErrorLength;x++){
	 		if($.validationEngine.ajaxValidArray[x][1] == false)
	 			$.validationEngine.ajaxValid = false;
 		}
		if(stopForm || !$.validationEngine.ajaxValid){		// GET IF THERE IS AN ERROR OR NOT FROM THIS VALIDATION FUNCTIONS
			if($.validationEngine.settings.scroll){
				if(!$.validationEngine.settings.containerOverflow){
					var destination = $(".formError:not('.greenPopup'):first").offset().top;
					$(".formError:not('.greenPopup')").each(function(){
						var testDestination = $(this).offset().top;
						if(destination>testDestination)
							destination = $(this).offset().top;
					});
					$("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1100);
				}else{
					var destination = $(".formError:not('.greenPopup'):first").offset().top;
					var scrollContainerScroll = $($.validationEngine.settings.containerOverflowDOM).scrollTop();
					var scrollContainerPos = - parseInt($($.validationEngine.settings.containerOverflowDOM).offset().top);
					destination = scrollContainerScroll + destination + scrollContainerPos -5;
					var scrollContainer = $.validationEngine.settings.containerOverflowDOM+":not(:animated)";
					
					$(scrollContainer).animate({scrollTop: destination}, 1100);
				}
			}
			return true;
		}else{
			return false;
		}
	},
	addRules : function (obj)
	{
		for (var p in obj)
		{
			$.validationEngine.settings.egturles[p] = obj[p];
		}
	}
};
})(jQuery);