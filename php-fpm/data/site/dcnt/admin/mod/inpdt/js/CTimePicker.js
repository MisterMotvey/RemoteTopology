/*js
//==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>
//
// AjaxCountDown v1.02
// Copyright (c) phpkobo.com ( http://www.phpkobo.com/ )
// Email : admin@phpkobo.com
// ID : DCAET-102
// URL : http://www.phpkobo.com/ajax-countdown
//
// This software is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2 of the
// License.
//
//==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<
*/
(function($){

function isInt( v ) {
	var x;
	if (isNaN(v)) {
		return false;
	}
	x = parseFloat(v);
	return (x | 0) === x;
};

function CInpInt( opt ) {
	for( var key in opt ) { this[key] = opt[key]; }
	this.step = 1;
	this.setup();
};

CInpInt.prototype = {

	setup : function() {
		var _this = this;

		var isCR = function( evt ) {
			evt = (evt) ? evt : window.event;
			var cc = (evt.which) ? evt.which : evt.keyCode;
			return ( 13 == cc ); //CR
		};

		var isDigit = function( evt ) {
			evt = (evt) ? evt : window.event;
			var cc = (evt.which) ? evt.which : evt.keyCode;
			return (( 48 <= cc ) && ( cc <= 57 )) || //0-9
				(( 8 == cc ) || ( cc == 46 )); //backspace. delete
		};

		this.jqo_inp
			.keypress(function(e) {
				if ( isCR(e.originalEvent) ) {
					e.preventDefault();
					_this.setVal($(this).val());
				}
				if ( !isDigit(e.originalEvent) ) {
					e.preventDefault();
				}
			})
			.blur(function(){
				_this.setVal($(this).val());
			});

		function autoRepeat(jqo,task,init_delay,repeat_delay){
			jqo
				.click(function(e){
					e.preventDefault();
					$(this).trigger(task);
				})
				.mousedown(function(e){
					if (e.which != 1) { return false; }

					var me = $(this);
					me.data("timer_id",-1);
					me.data("repeat_delay",repeat_delay);

					var startX = e.pageX;
					var startY = e.pageY;
					var h_mousemove = function(e){
						var dx = (e.pageX-startX);
						var dy = (e.pageY-startY);
						var dd = ( Math.abs(dx) > Math.abs(dy) ) ? dx : dy;
						var d = repeat_delay - dd;
						if ( d < 1 ) { d = 1; }
						me.data("repeat_delay",d);
					};
					$(document).mousemove(h_mousemove);

					var h_mouseup = function(){
						if ( me.data("timer_id") != -1 ) {
							$(document).unbind( "mousemove", h_mousemove );
							$(document).unbind( "mouseup", h_mouseup );
							var timer_id = me.data("timer_id");
							clearTimeout(timer_id);
						}
						me.data("timer_id",-2);
					};
					$(document).mouseup(h_mouseup);

					if ( me.data("timeout_id") ) {
						clearTimeout(me.data("timeout_id"));
					}

					var repeat_proc = function(){
						me.data("timer_id",setTimeout(function(e){
							me.trigger(task);
							repeat_proc();
						},me.data("repeat_delay")));
					};
					me.data("timeout_id",setTimeout(function(){
						if ( me.data("timer_id") == -1 ) {
							repeat_proc();
						}
					},init_delay));
				});
		};

		//-- inc button
		this.jqo_inc
			.bind("dotask",function(){
				var i = parseInt(_this.jqo_inp.val(),10);
				if ( !isNaN(i) ) {
					i = i + _this.step;
					_this.setVal(i);
				}
			});
		autoRepeat(this.jqo_inc,"dotask",this.init_delay,this.repeat_delay);

		//-- dec button
		this.jqo_dec
			.bind("dotask",function(){
				var i = parseInt(_this.jqo_inp.val(),10);
				if ( !isNaN(i) ) {
					i = i - _this.step;
					_this.setVal(i);
				}
			});
		autoRepeat(this.jqo_dec,"dotask",this.init_delay,this.repeat_delay);

		this.updateInput();
	},

	updateInput : function() {
		this.jqo_inp.val(("0"+this.val).slice(-2));
	},

	setVal : function( val ) {
		if ( !isInt( val ) ) {
			val = this.val;
		} else {
			if ( val >= this.max_val ) {
				val = this.min_val;
			}
			if ( val < this.min_val ) {
				val = this.max_val-1;
			}
		}
		val = parseInt(val);
		this.val = val;

		this.updateInput();
	},

	getVal : function() {
		return this.val;
	}

};

function CTimePicker( opt ) {
	for( var key in opt ) { this[key] = opt[key]; }

	this.setup();
};

CTimePicker.prototype = {

	setup : function() {
		var _this = this;

		this.inpint_h = new CInpInt({
			val : 0,
			jqo_inp : this.jqo_ctar.find(".tipk-h"),
			jqo_dec : this.jqo_ctar.find(".tipk-h-dec"),
			jqo_inc : this.jqo_ctar.find(".tipk-h-inc"),
			init_delay : 500,
			repeat_delay : 100,
			min_val : 0,
			max_val : 24
		});

		this.inpint_m = new CInpInt({
			val : 0,
			jqo_inp : this.jqo_ctar.find(".tipk-m"),
			jqo_dec : this.jqo_ctar.find(".tipk-m-dec"),
			jqo_inc : this.jqo_ctar.find(".tipk-m-inc"),
			init_delay : 500,
			repeat_delay : 100,
			min_val : 0,
			max_val : 60
		});

		this.inpint_s = new CInpInt({
			val : 0,
			jqo_inp : this.jqo_ctar.find(".tipk-s"),
			jqo_dec : this.jqo_ctar.find(".tipk-s-dec"),
			jqo_inc : this.jqo_ctar.find(".tipk-s-inc"),
			init_delay : 500,
			repeat_delay : 100,
			min_val : 0,
			max_val : 60
		});

		if ( this.init_val ) {
			this.setVal(this.init_val);
		} else {
			this.setVal(new Date());
		}

	},

	setVal : function( d ) {
		this.inpint_h.setVal(d.getHours());
		this.inpint_m.setVal(d.getMinutes());
		this.inpint_s.setVal(d.getSeconds());
	},

	getVal : function() {
		var h = this.inpint_h.getVal();
		var m = this.inpint_m.getVal();
		var s = this.inpint_s.getVal();
		return CDateTool.DateL("2000-01-01 "+h+":"+m+":"+s);
	}
};

window.CTimePicker = CTimePicker;

}(jQuery));
