(function($) {
	$.fn.pform = function(options){
		var pform = this;
		var settings = jQuery.extend({
			rows: 		0,
			default_size: 0,
			callback:	function(selection, status, id, j3sswobj){}
		},options||{});
	//find the inputs
	var inputs = $("input:text");

	$('input[name="date"]').datepicker();

	//setup the enter to tab crap
	$(document.body).on('keypress', 'input:text:not(:disabled)' , function (e) {
		var code = e.keyCode || e.which; 
		if(code == 13)
		{
			if(this.getAttribute('class') && this.getAttribute('class').indexOf('create_row') >= 0)
			{
				cloneRow();
				updateInputs();
			}
			var nextBox = inputs[inputs.index(this) + 1];
			if(nextBox)
			{
				nextBox.focus();
				nextBox.select();
			}
			return false;
		}
		CB(e);
	});

	$('form').bind("keyup", function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {
			e.preventDefault();
			return false;
		}
	});

	function updateInputs()
	{
		inputs = $("input:text");
	}

	function cloneRow()
	{
		var rowCloned = $('#detail-' + (settings.rows)).clone();
		$('#detail-' + (settings.rows++)).after(rowCloned);
		rowCloned.attr('id', 'detail-'+settings.rows);

		var cinputs = rowCloned.children('td').children('input').get();

		for ( var i in cinputs  )
		{
			cinputs[i].value = '';
			if(cinputs[i].getAttribute("type") == "checkbox")
				cinputs[i].value = 1;
			cinputs[i].setAttribute("name", cinputs[i].getAttribute("name").replace(/\d+/, settings.rows));
		}
		var cselects = rowCloned.children('td').children('select').get();

		for ( var i in cselects  )
		{
			cselects[i].value = settings.default_size;
			cselects[i].setAttribute("name", cselects[i].getAttribute("name").replace(/\d+/, settings.rows));
		}
		//change the label clone
		var labels = rowCloned.children('td').children('label').get();
		if(labels.length > 0)
		{
			labels[0].setAttribute("id", labels[0].getAttribute("id").replace(/\d+/, settings.rows));
			$("#" + labels[0].getAttribute("id")).text(0);
		}

		updateInputs();
	}

	var CB = function(e)
	{
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	};

	};
})(jQuery);