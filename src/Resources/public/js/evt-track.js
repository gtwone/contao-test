jQuery(function($){
	
	//Attribute data-where="Bannerwerbung Beruffeld Detail" data-what="Medium Rectangle"
    //Bei Klick, Event "Geklickt" tracken. Gesendet wird auf welcher Seite geklickt wurde (data-where), was (data-what) und der Name (data-name)
	$('.wrb').click(function(){
		var where = $(this).attr('data-where');
		var what = $(this).attr('data-what');
		var name = $(this).attr('data-name');
		_gaq.push(['_trackEvent', where, 'Geklickt', what+' '+name]);
	})
	
    //Prüfen ob Werbung angezeigt wurde. Falls ja wird der Event "Angezeigt" bei erstmaligen Laden oder "Angezeigt nach Resize" bei verkleinern des Bildschirms getrackt.
	function checkVis(eventName){
		$('.wrb').each(function() {
			if($(this).is(':visible')){
				var where = $(this).attr('data-where');
				var what = $(this).attr('data-what');
				var name = $(this).attr('data-name');
				_gaq.push(['_trackEvent', where, eventName, what+' '+name]);
			}
		})
	}
	
	checkVis('Angezeigt');

	$(window).resize(function() {
		checkVis('Angezeigt nach Resize');
	})
	
  
});