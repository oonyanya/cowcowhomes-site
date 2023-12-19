hexo.extend.tag.register('get_resouce', function(args) {
	let lang = this.language;
	let result = this.site.data.resources[lang];
	for(let i = 0; i < args.length; i++)
	{
		let arg = args[i];
		result = result[arg];
	}
	return result;
});
