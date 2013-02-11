<h1>Messages</h1>
<div id="messages" class="result">!!!!</div>
<script>

$(document).ready(function() {
	var channelId = 123456888;
	function check_messages() {
		
		$.ajax({
			type: "GET",
			url: '/listen?cid=' + channelId,
			data: {},
			dataType: "html",
			timeout: 25000,
			//complete: setTimeout(check_messages, 500)
		}).done(function( r ) {
			$('#messages').append(r);
			setTimeout(check_messages, 500);
		}).fail(function( r ) {
			setTimeout(check_messages, 500);
		});
		
//		$.get('/listen?cid=' + channelId, {}, function(r) {
//			// —читаем, что у нас есть div c id=messages,
//			// куда мы дописываем сообщени€
//			$('#messages').append(r);
//			setTimeout(check_messages, 500);
//		}, 'html');
	}

	check_messages();
})
</script>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
