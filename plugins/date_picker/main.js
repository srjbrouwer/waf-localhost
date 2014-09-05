/*
var elem = document.getElementById("returndate");
				if (typeof elem.click == "function") {
					elem.click.apply(elem);
				}
*/
$(document).ready(function(){


	$("#returndate").pickadate({
    		selectYears: true,
    		selectMonths: true,
			min: +1,
			monthsFull: [ 'januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december' ],
			monthsShort: [ 'jan', 'feb', 'maa', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec' ],
			weekdaysFull: [ 'zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag' ],
			weekdaysShort: [ 'zo', 'ma', 'di', 'wo', 'do', 'vr', 'za' ],
			today: 'vandaag',
			clear: 'verwijderen',
			firstDay: 1,
			format: 'dd-mmm-yyyy',
			formatSubmit: 'dd-mmm-yyyy',
			hiddenName: true,
			editable: false
		}); 
	var $returndate = $('#returndate').pickadate()
	$("#departuredate").pickadate({
		selectYears: true,
		selectMonths: true,
		min: +1,
		monthsFull: [ 'januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december' ],
		monthsShort: [ 'jan', 'feb', 'maa', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec' ],
		weekdaysFull: [ 'zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag' ],
		weekdaysShort: [ 'zo', 'ma', 'di', 'wo', 'do', 'vr', 'za' ],
		today: 'vandaag',
		clear: 'verwijderen',
		firstDay: 1,
		format: 'dd-mmm-yyyy',
		formatSubmit: 'dd-mmm-yyyy',
		hiddenName: true,
		editable: false,
		onClose: function(selectedDate){
			
			var picker = $returndate.pickadate('picker');
			//picker.set('select', this.component.item.select.pick );
			//picket.set('min', selectedDate);
			//picker.open();
			
		},
	});		
	var from_$input = $('#departuredate').pickadate(),
	from_picker = from_$input.pickadate('picker')
	var to_$input = $('#returndate').pickadate(),
	to_picker = to_$input.pickadate('picker')

	// Check if there’s a “from” or “to” date to start with.
	if ( from_picker.get('value') ) {
	  to_picker.set('min', from_picker.get('select'))
	}
	if ( to_picker.get('value') ) {
	  from_picker.set('max', to_picker.get('select'))
	}

	// When something is selected, update the “from” and “to” limits.
	from_picker.on('set', function(event) {
	  if ( event.select ) {
		to_picker.set('min', from_picker.get('select'))
	  }
	})
	to_picker.on('set', function(event) {
	  if ( event.select ) {
		from_picker.set('max', to_picker.get('select'))
	  }
	})
});
