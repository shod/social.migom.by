<?php
    $cs = Yii::app()->getClientScript();
    $cs->registerScript(
        'listener',
        '
			$(document).ready(function() {

				var channelId = \'' . $this->getChannelId() . '\';
				function check_messages() {
					
					$.ajax({
						type: "GET",
						url: \'/listen?cid=\' + channelId,
						data: {},
						dataType: "html",
						timeout: 30000,
						//complete: setTimeout(check_messages, 500)
					}).done(function( r ) {
						var obj = jQuery.parseJSON( r );
						if(obj.remove){
							if($(""+obj.withParent)){
								$(""+obj.remove).parent().remove();
							} else {
								$(""+obj.remove).remove();
							}
						}
						if(obj.menu){
							$.each(obj.menu, function(key, val) {
								if(val == 0){
									$(\'.\'+key+\' .count\').html(val)
									$(\'.\'+key+\' .count\').slideUp(\'slow\')
								} else{
									$(\'.\'+key+\' .count\').hide()
									$(\'.\'+key+\' .count\').html(val)
									$(\'.\'+key+\' .count\').slideDown(\'slow\')
								}
							});
						}
						if(obj.method){
							if(obj.method == "append"){
								$(""+obj.into).append(obj.html);
							} else {
								$(""+obj.into).prepend(obj.html);
							}
						}

						if(obj.read){
							$(\'#central_block .\'+obj.read).animate({ backgroundColor: "white" }, {duration: 5000})
						}
						
						//$("#dialog-posts").append(r);
						setTimeout(check_messages, 500);
					}).fail(function( r ) {
						setTimeout(check_messages, 500);
					});
				}
				
				check_messages();
				
			
			})

		',
      CClientScript::POS_END
    );