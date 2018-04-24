$(function(){
	var windowWidth = window.innerWidth;

	if(windowWidth >= 768)
	{
		$(".dropdown-content",this).hide();
		$(".dropdown-parent").hover(function(){
			$(".dropdown-content",this).show();
		},
		function(){
			$(".dropdown-content",this).hide();
		});
	}else{
		$(".top_menu",this).hide();
		$(".global_nav").click(function(){
			$(".top_menu",this).toggle();
		});
	}
});
