function addRssItem(json,rssbox)
{
	if(json.length == 0)
		return;

	var rss_items = json["item"];

	var len = rss_items.length;
	if(len > 10)
		len = 10;

	var root = rssbox.getElementsByClassName('rss-items')[0];

	var template = root.getElementsByTagName('template')[0];

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

	var summary = rssbox.getElementsByClassName('summary')[0];
	summary.setAttribute("href", json["channel"]["link"]);
	summary.innerHTML = json["channel"]["title"];
}

window.addEventListener( 'load', function(){
	var rssboxs = document.getElementsByClassName('rss-box');
	for(var i = 0; i < rssboxs.length; i++)
	{
		var rssbox = rssboxs[i];
		var rss_url = rssbox.getAttribute("data-rss-url");
		$.ajax({
			type: 'GET',
			url: "/rss/index.php?rss_url=" + rss_url,
			dataType: 'json',
			success: function(json){
					addRssItem(json,rssbox);
				}
		});
	}
},false);
