$(document).ready(function(){
	// User_menu show/hide submenu
	$("#user_menu .with_sub").hover(function(){
		$(this).find("ul").show();
	},
	function(){
		$(this).find("ul").hide();
	});
	
	// Flash messages effect
	$("#FlashMessage").slideDown(250).delay(3000).slideUp(250);
    
	// Hide flash message when clicked
	$("#FlashMessage").click(function(){
		$("#FlashMessage").hide();
	});
    
	// Open login box in situ
    $('#login_open').click(function(e) {
        e.preventDefault();
        $('#login').slideToggle(250, function(){});
    });

	// Apply the UniForm plugin to pulldows and button
	$(".search select, .search button, .filters select, .filters button,  #comments form button, #contact form button, .user_forms form button, .add_item form select, .add_item form button").uniform();
	
	// Show advanced search in internal pages
	$("#expand_advanced").click(function(e){
		e.preventDefault();
		$(".search .extras").slideToggle();
	});
	
	// Show/hide Report as 
	$("#report").hover(function(){
		$(this).find("span").show();
	},
	function(){
		$(this).find("span").hide();
	});
});