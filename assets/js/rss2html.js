function addRssItem(json,rssbox)
{
	if(json.length == 0)
		return;

	var rss_items;
	if(typeof(json["@attributes"]) == "undefined")	//rss 1.0か
	{
		rss_items = json["item"];
	}else{
		rss_items = json["channel"]["item"];
	}

	//rss_itemが一つしか存在しない場合、配列で取得できない
	if(!Array.isArray(rss_items))
		rss_items = [rss_items]

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

		var gtag_text = "gtag('event','view_item', {})";
		new_a.setAttribute("onclick" , gtag_text );

		root.appendChild(new_li);
	}

	var summary_tag = rssbox.getElementsByClassName('summary');
	if(summary_tag.length == 1)
	{
		var summary = summary_tag[0];
		summary.setAttribute("href", json["channel"]["link"]);
		summary.innerHTML = json["channel"]["title"];
	}

	var progress = rssbox.getElementsByTagName('progress')[0];
	progress.style.display = "none";
}

window.addEventListener( 'load', function(){
	var rssboxs = document.getElementsByClassName('rss-box');
	for(var i = 0; i < rssboxs.length; i++)
	{
		//無名関数を使わないとajaxが意図したとおりに実行されない
		(function(i){
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
		})(i);
	}
},false);
