function getNextField($form){
	var j = 1;
	while( $form.find("input[name="+j+"]").length ){
		j++;
	}
	return j;
}

var customHandlers = [];

$(document).ready(function(){	
	var rePhone = /^\+\d \(\d{3}\) \d{3}-\d{2}-\d{2}$/,
		tePhone = '+7 (999) 999-99-99';

	$.validator.addMethod('customPhone', function (value) {
		return rePhone.test(value);
	});

	if( device.mobile() ){
		$(".mobile-not-fancy").removeClass("fancy");
		$(".detail-photo .fancy-img").removeClass("fancy-img").removeAttr("href");
	}

	$(".ajax").parents("form").each(function(){
		$(this).validate({
			rules: {
				email: 'email',
				phone: 'customPhone'
			}
		});
		if( $(this).find("input[name=phone]").length ){
			$(this).find("input[name=phone]").mask(tePhone,{placeholder:"_"});
		}
	});

	function whenScroll(){
		var scroll = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
		if( customHandlers["onScroll"] ){
			customHandlers["onScroll"](scroll);
		}
	}
	$(window).scroll(whenScroll);
	whenScroll();

	
	fancyInit();

	$(".b-go").click(function(){
		var block = $( $(this).attr("data-block") ),
			off = $(this).attr("data-offset")||0;
		$("body, html").animate({
			scrollTop : block.offset().top-off
		},800);
		return false;
	});

	$(".fancy-img").fancybox({
		padding : 0
	});

	$(".ajax").parents("form").submit(function(){
  		if( $(this).find("input.error,select.error,textarea.error").length == 0 ){
  			var $this = $(this),
  				$thanks = $($this.attr("data-block"));

  			if( $this.attr("data-beforeAjax") && customHandlers[$this.attr("data-beforeAjax")] ){
				customHandlers[$this.attr("data-beforeAjax")]($this);
			}

  			$.ajax({
			  	type: $(this).attr("method"),
			  	url: $(this).attr("action"),
			  	data:  $this.serialize(),
				success: function(msg){
					var $form;
					if( msg.trim() == "1" ){
						$form = $thanks;
					}else{
						$form = $("#b-popup-error");
					}

					if( $this.attr("data-afterAjax") && customHandlers[$this.attr("data-afterAjax")] ){
						customHandlers[$this.attr("data-afterAjax")]($this);
					}

					$this.find("input[type=text],textarea").val("");
					$.fancybox.open({
						content : $form,
						padding : 0,
				        afterShow: function(){
				            setTimeout(function(){
				                $('.fancybox-wrap').css('position','absolute');
				                $('.fancybox-inner').css('height','auto');
				                $('html').addClass('fancybox-margin').addClass('fancybox-lock');
				                $('.fancybox-overlay').html($('.fancybox-wrap'));
				            },100);
				        }
					});	
					if( msg.trim() == "1" ){
						if( $this.attr("id") == "b-form-call" ){
							yaCounter34102235.reachGoal('CALL_REQUEST');
						}
						if( $this.attr("id") == "b-form-buy" ){
							yaCounter34102235.reachGoal('BUY_REQUEST');
						}
						if( $this.attr("id") == "b-form-exit" ){
							yaCounter34102235.reachGoal('EXIT_REQUEST');
						}
					}
				}
			});
  		}else{
  			$(this).find("input.error").eq(0).focus();
  		}
  		return false;
  	});
});
function applePie() {
    return ( navigator.userAgent.match(/(iPhone|iPod|iPad)/i) );
}

function fancyInit() {
	$(".fancy").each(function(){
		var $popup = $($(this).attr("data-block")),
			$this = $(this);
		$this.fancybox({
			padding : 0,
			content : $popup,
			fitToView: false,
			scrolling   : 'no',
			autoDimensions : 'true',
			helpers: {
	         	overlay: {
	            	locked: true 
	         	}
	      	},
			beforeShow: function(){
				$popup.find(".custom-field").remove();
				if( $this.attr("data-value") ){
					var name = getNextField($popup.find("form"));
					$popup.find("form").append("<input type='hidden' class='custom-field' name='"+name+"' value='"+$this.attr("data-value")+"'/><input type='hidden' class='custom-field' name='"+name+"-name' value='"+$this.attr("data-name")+"'/>");
				}
				if( $this.attr("data-beforeShow") && customHandlers[$this.attr("data-beforeShow")] ){
					customHandlers[$this.attr("data-beforeShow")]($this);
				}
			},
			afterShow: function(){
				$popup.find("input:eq(0)").focus();
				if( $this.attr("data-afterShow") && customHandlers[$this.attr("data-afterShow")] ){
					customHandlers[$this.attr("data-afterShow")]($this);
				}
				if( $(".fancybox-inner>div").attr("id") == "b-popup-callback" ){
					yaCounter34102235.reachGoal('CALL_BUTTON');
				}else if( $(".fancybox-inner>div").attr("id") == "b-popup-buy" ){
					yaCounter34102235.reachGoal('BUY_BUTTON');
				}

				if ( applePie() ) { $('body,html').css({'overflow': 'hidden'}); } 
				// $('.fancybox-inner input[type="text"]').eq(0).focus();
			},
			beforeClose: function(){
				if( $this.attr("data-beforeClose") && customHandlers[$this.attr("data-beforeClose")] ){
					customHandlers[$this.attr("data-beforeClose")]($this);
				}
			},
			afterClose: function(){
				if( $this.attr("data-afterClose") && customHandlers[$this.attr("data-afterClose")] ){
					customHandlers[$this.attr("data-afterClose")]($this);
				}
        		if ( applePie() ) { $('body,html').css({'overflow': 'auto'}); }
			}
		});
	});
}