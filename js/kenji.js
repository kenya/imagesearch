// JavaScript Document
var scrollSpeed = 70;       // Speed in milliseconds
var step = 1;               // How many pixels to move per step
var current = 0;            // The current pixel row
var imageHeight = 700;     // Background image height
var headerHeight = 0;     // How tall the header is.

var countNo=1;
var maxItem=10;
var sliderWidth = 630;
var sliderSpeed = 600;
var sliderInterval = 5000;

var tab = "#sliderBox .sliderMenu";

//The pixel row where to start a new loop
var restartPosition = -(imageHeight - headerHeight);

function scrollBg(){
    //Go to next pixel row.
    current -= step;
    //If at the end of the image, then go to the top.
    if (current == restartPosition){
        current = 0;
    }
    //Set the CSS of the header.
    $('#background').css("background-position","center "+current+"px");
}

//slider function
function scrollSlider(){
	countNo++;
	if(countNo > maxItem){ countNo=1;}
	var leftPixcels = -sliderWidth*(countNo-1);
	$("#sliderItem").animate(
	{"left":leftPixcels+"px"},sliderSpeed);
	$(tab+countNo).parent().find("li.now").removeClass("now");
	$(tab+countNo).addClass("now");
}

$(document).ready(function(){

	$(".wink").hover(function(){
		$(this).css("opacity", "0.2");
		$(this).css("filter", "alpha(opacity=20)");
		$(this).fadeTo("fast", 1.0);
	});

	$(".fade").hover(function(){
		$(this).fadeTo("fast", 0.6);
		$(this).css("border-color","#000");
	}, function(){
		$(this).fadeTo("fast", 1);
		$(this).css("border-color","#fff");
	});
	
	
	$("#grid .itemBox").hover(function(){
		$(this).addClass("hoverDeco");
	}, function(){
		$(this).removeClass("hoverDeco");												 
	});
	
	// smooth scroller
	$("a[href^=#]").click(function() {
		var hash = this.hash;
		if(!hash || hash == "#")
			return false;
		$($.browser.safari ? 'body' : 'html')
			.animate({scrollTop: $(hash).offset().top}, 1000, "swing");
	});


$(".gmenuAbout").click(function(){
		$("#about").addClass("hoverDeco");
		$("#profile").removeClass("hoverDeco");												 
		$("#twitter").removeClass("hoverDeco");												 
		$("#link").removeClass("hoverDeco");												 
		$("#contact").removeClass("hoverDeco");												 
	});
	
	$(".gmenuProfile").click(function(){
		$("#profile").addClass("hoverDeco");
		$("#about").removeClass("hoverDeco");												 
		$("#twitter").removeClass("hoverDeco");												 
		$("#link").removeClass("hoverDeco");												 
		$("#contact").removeClass("hoverDeco");												 
	});
	
	$(".gmenuTwitter").click(function(){
		$("#twitter").addClass("hoverDeco");
		$("#about").removeClass("hoverDeco");												 
		$("#profile").removeClass("hoverDeco");												 
		$("#link").removeClass("hoverDeco");												 
		$("#contact").removeClass("hoverDeco");												 
	});
	
	$(".gmenuLink").click(function(){
		$("#link").addClass("hoverDeco");
		$("#about").removeClass("hoverDeco");												 
		$("#profile").removeClass("hoverDeco");												 
		$("#twitter").removeClass("hoverDeco");												 
		$("#contact").removeClass("hoverDeco");												 
	});
	
	$(".gmenuContact").click(function(){
		$("#contact").addClass("hoverDeco");
		$("#about").removeClass("hoverDeco");												 
		$("#profile").removeClass("hoverDeco");												 
		$("#twitter").removeClass("hoverDeco");												 
		$("#link").removeClass("hoverDeco");												 
	});


		
	//slider
	$("#sliderBox .sliderMenu1").addClass("now");
	$(".rightBtn").css("cursor","pointer");
	$(".leftBtn").css("cursor","pointer");

	$(".rightBtn").hover(function(){
		$(".rightBtn").css("background-position","0 -55px");
	}, function(){
		$(".rightBtn").css("background-position","0 0");
	});
	

	$(".leftBtn").hover(function(){
		$(".leftBtn").css("background-position","0 -55px");
	}, function(){
		$(".leftBtn").css("background-position","0 0");
	});
	
	var arr = new Array(maxItem+1);

	$.each(arr,function(i){
			var leftPixcels = -sliderWidth*(i-1);
			$(tab+i).click(function(){
					$("#sliderItem").animate(
					{"left":leftPixcels+"px"},sliderSpeed);
					$(this).parent().find("li.now").removeClass("now");
					$(this).addClass("now");
					countNo=i;
			});
	});
	
	
	$(".leftBtn").click(function(){
		clearInterval (init2);
		countNo--;
		if(countNo < 1){ countNo=maxItem;}
		var leftPixcels = -sliderWidth*(countNo-1);
		$("#sliderItem").animate(
		{"left":leftPixcels+"px"},sliderSpeed);
		$(tab+countNo).parent().find("li.now").removeClass("now");
		$(tab+countNo).addClass("now");
		init2 = setInterval("scrollSlider()", sliderInterval);
	});


	$(".rightBtn").click(function(){
		clearInterval (init2);
		countNo++;
		if(countNo > maxItem){ countNo=1;}
		var leftPixcels = -sliderWidth*(countNo-1);
		$("#sliderItem").animate(
		{"left":leftPixcels+"px"},sliderSpeed);
		$(tab+countNo).parent().find("li.now").removeClass("now");
		$(tab+countNo).addClass("now");
		init2 = setInterval("scrollSlider()", sliderInterval);
	});

	init2 = setInterval("scrollSlider()", sliderInterval);
	
	$("#sliderItem").hover(function(){
		clearInterval (init2);
	}, function(){
		init2 = setInterval("scrollSlider()", sliderInterval);
	});

	var timer = 0;

	$("#linkBox a").hover(function(){
			$(this).animate({top:'3px'},100);
			$(this).animate({top:'0px'},100);
	}, function(){
			$(this).animate({ top:0},100);
	});


	$(".btnWork a").hover(function(){
			$(this).animate({top:'5px'},100);
			$(this).animate({top:'-5px'},100);
			$(this).animate({top:'0px'},100);
	}, function(){
			$(this).animate({ top:0},100);
	});



	$(".socialIcon a").hover(function(){
			$(this).animate({top:'5px'},100);
			$(this).animate({top:'-10px'},100);
			$(this).animate({top:'-5px'},100);
	}, function(){
			$(this).animate({ top:0},100);
	});

	$(".socialIcon a").each(function(i)
	{
		// margin left = - ([width of element] + [total vertical padding of element])
		$(this).css("top","-40px");
		// updates timer
		timer = (timer*0.8+150);
		$(this).animate({ top: "0" }, timer);
		$(this).animate({ top: "10px" }, timer);
		$(this).animate({ top: "0" }, timer);
	});								

	$(".siteTitle a").hover(function(){
			$(this).animate({top:'5px'},100);
			$(this).animate({top:'-10px'},100);
			$(this).animate({top:'-5px'},100);
	}, function(){
			$(this).animate({ top:0},100);
	});

	// margin left = - ([width of element] + [total vertical padding of element])
	$(".siteTitle a").css("top","-40px");
	// updates timer
	timer2 = 300;
	$(".siteTitle a").animate({ top: "0" }, timer2);
	$(".siteTitle a").animate({ top: "10px" }, timer2);
	$(".siteTitle a").animate({ top: "0" }, timer2);
	$(".siteTitle a").animate({ top: "5px" }, timer2);
	$(".siteTitle a").animate({ top: "0" }, timer2);

	
});

$(window).load(function(){

	$('#grid').masonry({
		itemSelector: '.itemBox:visible',
		animate: true
	});

	$('#grid2').masonry({
		itemSelector: '.itemBox:visible',
		animate: true
	});

});