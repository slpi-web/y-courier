$(document).ready(function () {
	

	$('.thank-you-pages').center();



	

	$('.nt-bg, .clos').click(function(){
		fadeOutT();
	});

	$(".ajaxform").submit(function(){ 
		var form = $(this); 
		var error = false; 
		form.find('[name=custom_U1656]').each( function(){ 
			if ($(this).val() == '') { 
				
				error = true;
			}
		});
		form.find('[name=custom_U1816]').each( function(){ 
			if ($(this).val() == '') { 
				
				error = true;
			}
		});
		form.find('[name=custom_U1638]').each( function(){ 
			if ($(this).val() == '') { 
				
				error = true;
			}
		form.find('[name=custom_U1634]').each( function(){ 
			if ($(this).val() == '') { 
				
				error = true;
			}
		});
		if (!error) { 
			var data = form.serialize(); 
			$.ajax({ 
			   type: 'POST', 
			   url: 'lib/phpMail/index1.php', 
			   type: 'POST',
			   data: data, 
		       success: function(data){ 

		       		fadeInT();

		         },
		       error: function (xhr, ajaxOptions, thrownError) {
		           	alert('error');
		         },
		       complete: function(data) { 
		           
		         }
		                  
			     });
		}
		return false; 
	});
















	});
});

function fadeInT (){
	$('.nt-bg').fadeIn(300);
	$('.thank-you-pages').fadeIn(500);
}
function fadeOutT (){
	$('.nt-bg').fadeOut(300);
	$('.thank-you-pages').fadeOut(300);
}


jQuery.fn.center = function () {
    this.css("position","fixed");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) + "px");
    return this;
}