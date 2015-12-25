{if !empty($callme)}
	<div style="display: none;">
		<div class="box-modal" id="callme-modal">
			<div class="box-modal_close arcticmodal-close">X</div>
			<form id="callme-form">
				<span>{l s='Call me' mod='callmefast'}</span>
				<input placeholder="{l s='Name' mod='callmefast'}" class="call_me_name" name="call_me_name" type="text">
				<input placeholder="{l s='Phone' mod='callmefast'}" class="call_me_phone" name="call_me_phone" type="text">
				<input class="call-me-btn" type="button" name="submit" onclick="callme_btn()" value="{l s='Send' mod='callmefast'}">
				<input name="call_me_token" value="{$token}" type="hidden">
			</form>		
			<div id="callme-result"></div>
		</div>
	</div>

	<script>
	function callme_btn(){
		$.ajax({
			url: '/modules/callmefast/ajax/ajax.php',
			type: "POST",
			data: '&n='+$( "#callme-form" ).find( "input[name='call_me_name']" ).val()+
			'&p='+$( "#callme-form" ).find( "input[name='call_me_phone']" ).val()+
			'&t='+$( "#callme-form" ).find( "input[name='call_me_token']" ).val(),
			dataType : "json",
			complete:function(data){
				$('#callme-result').html(data.responseText);
				$("#callme-result").empty();
				$("#callme-result").append(data.responseText);
			}
		})
	}
	</script>

{/if}
