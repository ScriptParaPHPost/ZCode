'use strict';
$('input#ticket').on('keyup', function(event) {
	let text = $(this).val();
	$.post(`${ZCodeApp.url}/ticket-list.php`, { text }, response => $('tbody#tbody').html(response))
});