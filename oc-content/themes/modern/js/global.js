$(document).ready(function() {
	/**
	 * UniForm plugin: Apply styling to <select> and <button>
	 */
	$(".search select, .search button, .filters select, .filters button,  #comments form button, #contact form button, .user_forms form button, .add_item form select, .add_item form button").uniform();
		
	// Flash message: effects
	$("#FlashMessage").slideDown(250).delay(3000).slideUp(250);
    
	// Flash message: close when clicked
	$("#FlashMessage").click(function(){
		$("#FlashMessage").hide();
	});
    
	// Header: show/hide login form
    $('#login_open').click(function(e) {
        e.preventDefault();
        $('#login').slideToggle(250, function(){});
    });
    
	// Header: show/hide language list
	$("#user_menu .with_sub").hover(function(){
		$(this).find("ul").show();
	},
	function(){
		$(this).find("ul").hide();
	});
    
	// Show advanced search in internal pages
	$("#expand_advanced").click(function(e){
		e.preventDefault();
		$(".search .extras").slideToggle();
	});
	
	// Item Page: show/hide 'mark as' list
	$("#report").hover(function(){
		$(this).find("span").show();
	},
	function(){
		$(this).find("span").hide();
	});
});