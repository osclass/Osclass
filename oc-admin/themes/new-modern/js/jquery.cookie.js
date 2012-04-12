jQuery.cookie = {
	
   	set : function(name,value,options){
   		
   		options = $.extend({}, options);
   		
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        
  		value = options.json ? encodeURIComponent($.JSON.encode(value)):encodeURIComponent(value);
  
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        
        document.cookie = [name, '=', value, expires, path, domain, secure].join('');
	},
	get : function(name,json){
		
		var cookieValue = null;
		
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = $.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = json ? $.JSON.decode(decodeURIComponent(cookie.substring(name.length + 1))):decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        
        return cookieValue;
        
	},
	unset: function(name){
		this.set(name,'',-1);
	}
};
