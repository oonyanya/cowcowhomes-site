const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

// 処理したいリスト。クレイグリストのみ対応
const parse_list=[
 {
   output_file:".\\public\\rentals.json",
   start_url: "https://tokyo.craigslist.org/search/hhh?availabilityMode=0&cc=us&lang=en&query=99902%20-sale&sort=date#search=1~gallery~0~0"
 }
];

// 内容を取得する上限
const limit = 20;

async function crawl(url) {
  try {
    const headers = {
      'Cache-Control':'no-cache',
      'Accept':'text/html,application/xhtml+xml,application/xml',
      'Accept-Language': 'ja,en',
      'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 Edg/122.0.0.0',
      'Sec-Ch-Ua':'Chromium";v="122", "Not(A:Brand";v="24", "Microsoft Edge";v="122',
      'Sec-Ch-Ua-Mobile':'?0',
      'Sec-Ch-Ua-Platform':"Windows",
      'Sec-Fetch-Dest':'document',
      'Sec-Fetch-Mode':'navigate',
      'Sec-Fetch-Site':'cross-site',
      'Sec-Fetch-User':'?1'
    }
    const response = await axios.get(url,{headers});
    const $ = cheerio.load(response.data);
    let urlTitleList = [];

    let left = limit;
    // このページ内のリンクを取得
    $("a[href]").each((_, element)=>{
      let detail = $(element).find("div.details");
      let url = $(element).attr('href');
      let title = $(element).find("div.title").text();

      if(url == "#" || url =="/") //意味がないものはスキップする
        return true;

      if(left == 0) // ループから抜ける
        return false;

      urlTitleList.push({
        link: url,
        title: title,
        price: detail.find("div.price").text(),
        location: detail.find("div.location").text(),
      });

      left--;
    });

    // await foreachをするのがとてつもなく面倒なので、２回に分ける
    for(let e of urlTitleList)
    {
      // TODO:あまりよろしくないのでうまいこと分ける
      console.log("fetching... " + e.link);
      let response = await axios.get(e.link);
      let page_document = cheerio.load(response.data);
      e.title += page_document(".housing").text();
    }

    return {items:urlTitleList,summary:{link:url,title:""}};

  } catch (error) {
    // TODO:あまりよろしくないのでうまいこと分ける
    console.log(`Error crawling ${url}:`, error.message);
    return null;
  }
}

(async () => {
  for(let item of parse_list){
    console.log("fetch start:" + item.start_url);
    let result = await crawl(item.start_url);
    console.log("fetch success");
    fs.writeFile(item.output_file, JSON.stringify(result),(err)=>{
     if(err)
       console.log("write file failed:" + err);
     else
       console.log("saved to " + item.output_file);
    });
  }
})();
