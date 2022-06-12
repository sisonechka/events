
<div class="box box-primary">
  <div class="box-body no-padding">
    <div id="calendar"></div>
  </div>
</div>
<style>
.fc-disabled {
    color: #000;
    cursor: default;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
}
</style>
<script language="javascript">
$(function () {
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
				url	: '<?php echo WEB_ROOT; ?>api/process.php?cmd=calview',
				dataType: 'json',
				type	: 'POST',
				data	: {
					start	: start.format(),
					end		: end.format()
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
		eventClick: function(calEvent, jsEvent, view) { },
		dayRender: function(date, cell){
			 $(cell).css('opacity', 1);
		},
		viewRender: function(view, element) { },
		eventRender: function(ev, element, view) { },
		eventAfterRender : function(ev, element, view) {
			if(ev.block == true) {
				var start = ev.start.format();
				$("td.fc-day[data-date='"+ start +"']").addClass('fc-disabled');
			}
		}
	});
});
</script>