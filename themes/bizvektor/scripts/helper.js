hexo.extend.helper.register('get_resouce', function() {
  let lang = (this.page.lang || this.page.language);
  return this.site.data.resources[lang];
});

hexo.extend.helper.register('get_current_lang', function (item) {
  //一番最初は言語タグ
  return item.path.split('/')[0];
});

hexo.extend.helper.register('get_langs', function (item) {
  return this.config.language.filter(function(item){
    return item != "default";
  })
});

hexo.extend.helper.register('get_pages', function () {
  const self = this;
  //現在表示中のページの言語
  const lang = self.get_current_lang(this.page);
  if(this.site.data.resources[lang] == undefined)
    return [];
  else
    return this.site.data.resources[lang].pages;
});

hexo.extend.helper.register('gen_lang_switches', function(label){
  const self = this;
  return self.get_langs().map(function(lang){
    const lang_name = self.site.data.resources[lang].lang_name;
   	if(self.page.layout == undefined || self.page.layout == "index")
    {
  		return {url:`/${lang}`, label:lang_name};
    }
  	else
    {
  		var tokens = self.page.path.split("/");
  		var url = "";
  		var lang_tag_index = 0;
  		for(var i = 0;i < tokens.length; i++)
      {
  			url += "/";
  			if(i == lang_tag_index)
  			  url += lang;
  			else if(tokens[i] != "index.html")	//index.htmlはなくても動く
  				url += tokens[i];
      }
  		return {url:`${url}`, label:lang_name};
    }
  });
});
