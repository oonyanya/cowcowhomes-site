window.addEventListener( 'load', function(){
	$.ajax({
		type: 'GET',
		url: '/rss/index.php',
		dataType: 'json',
		success: function(json){
			var rss_items = json["item"];

			var len = rss_items.length;
			if(len > 10)
				len = 10;

			var root = document.getElementsByClassName('rss-items')[0];

			var template = document.getElementById('rss-template');

			for(var i=0; i < len; i++){
				//複製してliタグを得る
				var new_li = document.importNode(template.content, true).firstElementChild;
				var new_a = new_li.firstElementChild;

				new_a.setAttribute("href", rss_items[i]["link"]);
				new_a.innerHTML = rss_items[i]["title"];

				var m = rss_items[i]["title"].match(/\((.+)\)/);
				if(m)
					var view_item_text = m[1].trim();
				else
					var view_item_text = "craiglist_item";

				var gtag_text = "gtag('event','view_item', {'id': '%id%', 'name': '%text%'})";
				gtag_text = gtag_text.replace("%text%",view_item_text);
				gtag_text = gtag_text.replace("%id%", Math.floor(Math.random() * 32767));
				new_a.setAttribute("onclick" , gtag_text );

				root.appendChild(new_li);
			}

			var rssbox = document.getElementsByClassName('summary')[0];
			rssbox.setAttribute("href", json["channel"]["link"]);
			rssbox.innerHTML = json["channel"]["title"];
		}
	});
},false);
