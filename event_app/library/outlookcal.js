$(function () {
	var date = new Date();
    var d = date.getDate(),
    m = date.getMonth(),
    y = date.getFullYear();
    $('#calendar').fullCalendar({
		header: {
        	left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
		},
        buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
        },
        events: function(start, end, timezone, callback) {
			$.ajax({
				url: 'api/process.php?cmd=calview',
				dataType: 'json',
				type: 'POST',
				data: {
					start: start.format(),
					end: end.format()
				},
				success: function(doc) {
					var events = [];
					callback(doc);
				}
			});
		},
        editable: true,
        droppable: true,
        drop: function (date, allDay) { },
		eventClick: function(calEvent, jsEvent, view) {
			alert('Event: ' + calEvent.title);
			$(this).css('border-color', 'red');
		}
	});
});