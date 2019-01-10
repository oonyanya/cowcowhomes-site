window.addEventListener( 'load', function(){
	$.ajax({
		type: 'GET',
		url: '/rss/index.php',
		dataType: 'json',
		success: function(json){
			console.log(json["item"]);
			var rss_items = json["item"];

			var len = rss_items.length;
			if(len > 10)
				len = 10;

			$(".rss-box").append("<ul class='rss-items'></ul>");

			var new_ul = $(".rss-box .rss-items");

			for(var i=0; i < len; i++){
				var html = "<li class='rss-item'><a href='%url%'>%title%</a></li>";
				var str = html.replace("%url%",rss_items[i]["link"]);
				var str = str.replace("%title%",rss_items[i]["title"]);
				console.log(str);
				new_ul.append(str);
			}
		}
	});
},false);
