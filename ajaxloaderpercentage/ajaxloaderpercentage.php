<link rel="stylesheet" type="text/css" href="ajaxloaderpercentage/ajaxloaderpercentage.css" />
<script type="text/javascript">
(function($) {

    $.fn.extend({
        ajaxloaderpercentage: function(options) {
            options = $.extend( {}, $.ajaxloaderpercentage.defaults, options );

            this.each(function() {
                new $.ajaxloaderpercentage(this,options);
            });
            return this;
        }
    });
		
    $.ajaxloaderpercentage = function( el, options ) {
				
				var ajaxloaderpercentage = {
					percentvalue : 0,
					previousvalue: 0,
					currentrow : 1,
					init: function(_el){
						
						if(options.process == ''){
							$(options.loader).append('<span class="phpwldr-warning">Please specified the process php file.</span>');
							$(_el).on('submit',function(e){return false;});
							return false;
						}
						
						ajaxloaderpercentage.pause(_el);
						ajaxloaderpercentage.submit(_el);
						
					},
					pause: function(_el){
						
						$(_el).attr('rel','pause');
						
					},
					start: function(_el){
						
						$(_el).attr('rel','onprocess');
						
					},
					remove: function(){
						options.loader.html('');
					},
					submit: function(_el){
						
						$(_el).on('submit', function(e){
							e.preventDefault();	
							
							if($(this).attr('rel')=='onprocess') return false;
							
							ajaxloaderpercentage.start();
							$(this).prepend('<input type="hidden" name="rowtotal" value="'+options.rowtotal+'"/>');
							$(this).prepend('<input type="hidden" name="rowcurrent" value="'+ajaxloaderpercentage.currentrow+'"/>');
							$(this).prepend('<input type="hidden" name="rowperproc" value="'+options.rowperprocess+'"/>');
							options.loader.css({position:'relative',width:options.width,height:options.height});
							options.loader.append('<div class="phpwldr-wrapper"><div class="phpwldr-proc-value"></div></div><span class="phpwldr-proc-text">0%</span>');
							
							ajaxloaderpercentage.generateDocument($(this), ajaxloaderpercentage.generateDocument);
							ajaxloaderpercentage.previousvalue = 0;
							ajaxloaderpercentage.percentvalue = 0;
							ajaxloaderpercentage.currentrow = 1;
						});
						
					},
					sleep: function(ms){
						
						return new Promise(resolve => setTimeout(resolve, ms));
						
					},
					animateLoader: function(){
						
						ajaxloaderpercentage.animateText();
						options.loader.find('.phpwldr-proc-value').animate({
							width: ajaxloaderpercentage.percentvalue + '%',
						},500,function(){
							if(ajaxloaderpercentage.percentvalue>=75) options.loader.find('.phpwldr-proc-value,.phpwldr-proc-text').css({background:'#4991c4',color:'#fff'});
							if(ajaxloaderpercentage.percentvalue>=90) options.loader.find('.phpwldr-proc-value,.phpwldr-proc-text').css({background:'#01a92d',color:'#fff'});
						});
						
					},
					animateText: async function(){
						
						for(var i=ajaxloaderpercentage.previousvalue; i<=ajaxloaderpercentage.percentvalue; i++){
							options.loader.find('.phpwldr-proc-text').text(i.toFixed(0)+'%');
							await ajaxloaderpercentage.sleep(50);
						}
						
					},
					generateDocument: function(_el, callback){
						
						$.post(options.process,_el.serialize(),function(_res){
							
							ajaxloaderpercentage.previousvalue = ajaxloaderpercentage.percentvalue;
							
							if(ajaxloaderpercentage.currentrow * options.rowperprocess < options.rowtotal){
								
								ajaxloaderpercentage.percentvalue = ((ajaxloaderpercentage.currentrow * options.rowperprocess) / options.rowtotal) * 100;
								ajaxloaderpercentage.animateLoader();
								ajaxloaderpercentage.currentrow++;
								
								$(_el).children('input[name="rowcurrent"]').val(ajaxloaderpercentage.currentrow);
								
								setTimeout(function(){
									options.callback_each(_el, _res);
									callback(_el, callback);
								},500);
								
							}
							else{
								
								ajaxloaderpercentage.percentvalue = 100;
								ajaxloaderpercentage.animateLoader( '100%' );
								ajaxloaderpercentage.pause();
								
								setTimeout(function(){
									ajaxloaderpercentage.pause();
									ajaxloaderpercentage.remove();
								},1000);
								
								options.callback_full(_el,_res);
								
							}
							
						});
						
					},
					callback_each: function(_el, _res){
						console.log(_el);
					},
					callback_full: function(_el, _res){
						console.log(_el);
					}
				};
				
				ajaxloaderpercentage.init(el);
    };

    $.ajaxloaderpercentage.defaults = {
        loader : $('#phpword-loader'),
				width: '100%',
				height: '15px',
				rowtotal: 150,
				rowperprocess: 6,
				process: '',
				callback_each: '',
				callback_full: ''
    };

})(jQuery);
</script>