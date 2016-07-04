var farolaCommon = (function(){
	var _submitDone = false;

	var _canSubmit = function($update) {
		if (_submitDone)
			{
				return false;
			}
			if ($update)
			{
				_submitDone = true;
			}
			return true;
	}

	return {
		'canSubmit': _canSubmit,
		'sendChangeRequest': function($url) {
			$.post( $url, function( data ) {
		  		window.location.href = data.redirectTo;
			});
		},
		'sendChangeRequest': function($url, $urlRedirect) {
			$.post( $url, function( data ) {
		  		if ($urlRedirect == 'reload')
		  		{
		  			window.location.reload();
		  		}
		  		else
		  		{
		  			window.location.href = $urlRedirect;
		  		}
			});
		},

		'postDataRequest': function($jqFormElt, $url, $urlRedirect ) {
			var $dataToPost = $jqFormElt.serializeArray();

			if (_canSubmit(true))
			{
				$.post( $url, $dataToPost, function( data ) {
		  			if(typeof $urlRedirect !== 'undefined')
		  			{
		  				window.location.href = $urlRedirect;
		  			}
		  			else
		  			{
		  				window.location.reload();
		  			}
				});
			}
		},
		'moreResultsRequest': function($formSearchName, $url, $jqResultContainer ) {
			var dataToPost = null;
			if ($formSearchName != null)
			{
				dataToPost = $('[name="'+$formSearchName+'"]').serializeArray();
			}

			$.get( $url, dataToPost, function( data ) {
		  		$jqResultContainer.append(data.html);
		  		$('html, body').animate({
		            scrollTop: $jqResultContainer.offset().top + 'px'
		        }, 'slow');
			});
		},
		'getContentRequest': function($url, $jqResultContainer ) {
			$.get( $url, function( data ) {
		  		$jqResultContainer.html('');
		  		$jqResultContainer.append(data.html);
		  		// $('html, body').animate({
		    //         scrollTop: $jqResultContainer.offset().top + 'px'
		    //     }, 'fast');
			});
		}
	}
}());

$( document ).ready(function() {
    $('form').submit(function (event) {
    	if (farolaCommon.canSubmit(true) == false)
    	{
    		event.preventDefault();
    	}
    });

    $('.modal').on('shown.bs.modal', function () {
    	var focusElt = document.activeElement.querySelector('.frl-focusme');
    	console.log(focusElt);
    	if (focusElt)
    	{
    		focusElt.focus();
    	}
    	
    	// $('#'+document.activeElement.id).find('.frl-focusme').focus()
  	})
});