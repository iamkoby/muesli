function correctAlternation(){
	$('#table_order tr').removeClass('alt').filter(':odd').addClass('alt');
}
$(function(){
	$('#table_order a.up').click(function(){
		var tr = $(this).closest('tr');
		var prev = tr.prev(':not(.title)');
		if (prev.size()){
			var url = $(this).closest('form').attr('action');
			if (!url) return false;
			var item = tr.attr('item');
			tr.fadeTo('fast', 0.5, function(){ 
				$.ajax({
					type: 'POST',
					url: url,
					data: { 'direction': 'up', 'item': item },
					error: function(){
						tr.fadeTo('fast', 1);
					},
					success: function(){
						tr.insertBefore(prev).fadeTo('fast', 1);
						correctAlternation();
					}
				});
			});
		}
		return false;
	});
	$('#table_order a.down').click(function(){
		var tr = $(this).closest('tr');
		var next = tr.next();
		if (next.size()){
			var url = $(this).closest('form').attr('action');
			if (!url) return false;
			var item = tr.attr('item');
			tr.fadeTo('fast', 0.5, function(){ 
				$.ajax({
					type: 'POST',
					url: url,
					data: { 'direction': 'down', 'item': item },
					error: function(){
						tr.fadeTo('fast', 1);
					},
					success: function(){
						tr.insertAfter(next).fadeTo('fast', 1);
						correctAlternation();
					}
				});
			});
		}
		return false;
	});
});