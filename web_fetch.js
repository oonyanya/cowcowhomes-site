const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

// 出力先のルートフォルダー
const output_root_folder = ".\\public\\";

// 処理したいリスト。クレイグリストのみ対応
const parse_list=[
 {
   output_image_folder: "image\\rentals\\",
   output_file: "rentals.json",
   start_url: "https://tokyo.craigslist.org/search/hhh?availabilityMode=0&cc=us&lang=en&query=99902%20-sale&sort=date#search=1~gallery~0~0"
 },
 {
   output_image_folder: "image\\sales\\",
   output_file: "sales.json",
   start_url: "https://tokyo.craigslist.org/search/hhh?availabilityMode=0&cc=us&lang=en&query=99902+sale&sort=date#search=1~gallery~0~0"
 }
];

// 内容を取得する上限
const limit = 20;

// サマリーとして表示する文字数の上限
const summary_limit = "100";

async function download_async(uri, filename ,headers){
  try{
    const response = await axios.get(uri,{responseType: 'arraybuffer', ...headers});
    new Promise((resloved,reject)=>{
      fs.writeFile(filename, new Buffer.from(response.data), "binary", resloved);
    });
  }catch(error){
    console.log(uri + "is failed." + error);
  }
};


async function crawl(item) {
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
    const response = await axios.get(item.start_url,{headers});
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
        summary: null
      });

      left--;
    });

    // await foreachをするのがとてつもなく面倒なので、２回に分ける
    let image_number = 0;
    for(let e of urlTitleList)
    {
      // TODO:あまりよろしくないのでうまいこと分ける
      console.log("fetching... " + e.link);
      let response = await axios.get(e.link);
      let page_document = cheerio.load(response.data);
      e.title += page_document(".housing").text();
      let summary = "";
      page_document("#postingbody").contents().each((i,e)=>{
        if(e.type == "text")
          summary += e.data.trim() + " ";  // 元のデーターには改行と余計な空白があるのでこれらを除去する
      });      
      e.summary = summary.slice(0,summary_limit);

      //　画像をマナー上ローカルに保存しなければならない
      let image_uri = page_document(".gallery img").attr("src");
      let image_local_uri = item.output_image_folder + image_number + ".jpg";
      await download_async(image_uri, output_root_folder + image_local_uri);
      e.image_url = image_local_uri.replaceAll("\\","/");
      // TODO:あまりよろしくないのでうまいこと分ける
      console.log(image_uri + " fetched and saved to " + output_root_folder + image_local_uri);

      image_number++;
    }

    return {items:urlTitleList,summary:{link:item.start_url,title:""}};

  } catch (error) {
    // TODO:あまりよろしくないのでうまいこと分ける
    console.log(`Error crawling ${item.start_url}:`, error.message);
    return null;
  }
}

(async () => {
  for(let item of parse_list){
    console.log("fetch start:" + item.start_url);
    let result = await crawl(item);
    console.log("fetch success");
    fs.writeFile(output_root_folder + item.output_file, JSON.stringify(result),(err)=>{
     if(err)
       console.log("write file failed:" + err);
     else
       console.log("saved to " + output_root_folder + item.output_file);
    });
  }
})();
