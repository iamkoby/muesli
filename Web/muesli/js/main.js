$(function () {
	
	// Style file input
	$("input[type=file]").filestyle({ 
	    image: "/muesli/images/upload.gif",
	    imageheight : 30,
	    imagewidth : 80,
	    width : 250
	});
	$("input.library").filestyle({ 
	    image: "/muesli/images/library.gif",
	    imageheight : 30,
	    imagewidth : 80,
	    width : 250
	});
	
	// Set WYSIWYG editor
	$('.wysiwyg.rtl').wysiwyg({css: "/muesli/css/wysiwygheb.css"});
	$('.wysiwyg.ltr').wysiwyg({css: "/muesli/css/wysiwygeng.css"});
	
	
	// Messages
	$('.block .message').append('<span class="close" title="Dismiss"></span>');
	$('.block .message .close').hover(
		function() { $(this).addClass('hover'); },
		function() { $(this).removeClass('hover'); }
	);
		
	$('.block .message .close').click(function() {
		$(this).parent().fadeOut('slow', function() { $(this).remove(); });
	});
	
		
	// CSS tweaks
	$('#header #nav li:last').css('background', 'none');
	$('.block_head ul').each(function() { $('li:first', this).css('background', 'none'); });
	$('.block table tr:odd').addClass('alt');
	$('.block form input[type=file]').addClass('file');
	
	// IE6 PNG fix
	$(document).pngFix();
		
});