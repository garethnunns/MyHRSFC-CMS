function addOnloadEvent(onloadfuncion) {
  var curOnload = window.onload
  if(typeof window.onload != 'function') {
    window.onload = onloadfuncion;
  }
  else {
    window.onload = function() {
      if(curOnload) {
        curOnload();
      }
      onloadfuncion();
    }
  }
}


// Jquery with no conflict
jQuery(document).ready(function($) {
/*
	//##########################################
	// Tweet feed
	//##########################################
	
	$("#tweets").tweet({
        count: 3,
        username: "myhrsfc"
    }); */
    
    //##########################################
	// HOME SLIDER
	//##########################################
	
    $('.home-slider').flexslider({
    	animation: "fade",
    	controlNav: true,
    	keyboardNav: true
    });
    
   	//##########################################
	// PROJECT SLIDER
	//##########################################
	
    $('.project-slider').flexslider({
    	animation: "fade",
    	controlNav: true,
    	directionNav: false,
    	keyboardNav: true
    });

    
	//##########################################
	// Superfish
	//##########################################
	
	$("ul.sf-menu").superfish({ 
        animation: {height:'show'},   // slide-down effect without fade-in 
        delay:     200 ,              // 1.2 second delay on mouseout 
        autoArrows:  false,
        speed: 200
    });
    /*
    //##########################################
	// PrettyPhoto
	//##########################################
	
	$('a[data-rel]').each(function() {
	    $(this).attr('rel', $(this).data('rel'));
	});
	
	$("a[rel^='prettyPhoto']").prettyPhoto();
    */
    //##########################################
	// Mobile nav
	//##########################################

	var mobnavContainer = $("#mobile-nav");
	var mobnavTrigger = $("#nav-open");
	
	mobnavTrigger.click(function(){
		mobnavContainer.slideToggle();
	});
	/*
	//##########################################
	// SIDEBAR
	//##########################################
	
    $('#sidebar-opener').click(function(){
    	$('#sidebar-content').slideDown();
    	$('#sidebar-closer').show();
    });
    
    $('#sidebar-closer').click(function(){
    	$('#sidebar-content').slideUp();
    	$('#sidebar-closer').hide();
    });
    *//*
    //##########################################
	// Accordion box
	//##########################################

	$('.accordion-container').hide(); 
	$('.accordion-trigger:first').addClass('active').next().show();
	$('.accordion-trigger').click(function(){
		if( $(this).next().is(':hidden') ) { 
			$('.accordion-trigger').removeClass('active').next().slideUp();
			$(this).toggleClass('active').next().slideDown();
		}
		return false;
	});
	
	//##########################################
	// Toggle box
	//##########################################
	
	$('.toggle-trigger').click(function() {
		$(this).next().toggle('slow');
		$(this).toggleClass("active");
		return false;
	}).next().hide();
	
	//##########################################
	// Tabs
	//##########################################

    $(".tabs").tabs("div.panes > div", {effect: 'fade'});
	*//*
	//##########################################
	// Masonry
	//##########################################
	
	
	function masonryStart(){
	
		// Destroy by default
		
		


		// Featured posts
		
		var $container = $('.featured');
		
		$container.imagesLoaded(function(){
			$container.masonry({
				itemSelector: 'figure',
				isAnimated: true
			});
		});
		
		// Text posts
		
		var $container2 = $('.text-posts');
		
		$container2.imagesLoaded(function(){
			$container2.masonry({
				itemSelector: 'li'
			});
		});
		
		// Home gallery
		
		var $container3 = $('.home-gallery');
		
		$container3.imagesLoaded(function(){
			$container3.masonry({
				itemSelector: 'li'
			});
		});
	
	}
	*/	
	//##########################################
	// Tool tips
	//##########################################
	
	
	function tooltipPosition(){
		
		$('#social-bar a').poshytip('destroy');
		 
		 if( $(window).width() >= 992){
		 	$('#social-bar a').poshytip({
		    	className: 'tip-twitter',
				showTimeout: 1,
				alignTo: 'target',
				alignY: 'center',
				alignX: 'right',
				offsetX: 5,
				allowTipHover: false
		    });
		 }else{
		 	$('#social-bar a').poshytip({
		    	className: 'tip-twitter',
				showTimeout: 1,
				alignTo: 'target',
				alignY: 'center',
				alignX: 'left',
				offsetX: 5,
				allowTipHover: false
		    });
		 }
		 
	}// ends tooltipPosition
	
   
    
    $('.form-poshytip').poshytip({
		className: 'tip-twitter',
		showOn: 'focus',
		alignTo: 'target',
		alignX: 'right',
		alignY: 'center',
		offsetX: 5
	});
	
	//##########################################
	// Scroll to top
	//##########################################
	
        
    $('#to-top').click(function(){
		$('html, body').animate({ scrollTop: 0 }, 300);
	});
	
	//##########################################
	// Resize event
	//##########################################
	
	$(window).resize(function() {
		tooltipPosition();
		//masonryStart();
	}).trigger("resize");

    

//close			
});

// FIXED SOCIAL BAR AFTER SCROLL by Thomas Frodsham, 2013

function getNodeX(node) {
  var curX = 0;
  while(node.offsetParent) {
    curX += node.offsetLeft;
    node = node.offsetParent;
  }
  return curX;
}

var socialIsFixed = false;

window.onresize = function() {
  if(socialIsFixed) document.getElementById('social-bar').style.left = getNodeX(document.getElementById('social-cont'))+'px';
}
window.onscroll = function(e) {
  if(!e) e = window.event;
  var curScroll = pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
  if(curScroll > 175)
    fixSocial();
  else
    unfixSocial();
}

addOnloadEvent(window.onscroll);

function fixSocial() {
 if(!socialIsFixed) {
  socialIsFixed = true;
  var socialContNode = document.getElementById('social-cont');
  var socialNode = document.getElementById('social-bar');
  socialNode.style.left = getNodeX(socialContNode)+'px';
  socialNode.style.position = 'fixed';
  socialNode.style.top = '0px';

 }
}
function unfixSocial() {
 if(socialIsFixed) {
  socialIsFixed = false;
  var socialNode = document.getElementById('social-bar');
  socialNode.style.position = 'static';
 }
}