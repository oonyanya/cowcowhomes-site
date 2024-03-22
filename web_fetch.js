const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

// Output File
const output_file = ".\\public\\" + "rentals.json";

// Site url to crawl
const startUrl = "https://tokyo.craigslist.org/search/hhh?availabilityMode=0&cc=us&lang=en&query=99902%20-sale&sort=date#search=1~gallery~0~0";

// 内容を取得する上限
const limit = 10;

async function crawl(url) {
  try {
    const response = await axios.get(url);
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

    return urlTitleList;

  } catch (error) {
    // TODO:あまりよろしくないのでうまいこと分ける
    console.log(`Error crawling ${url}:`, error.message);
    return null;
  }
}

(async () => {
  console.log("fetch start:" + startUrl);
  let result = await crawl(startUrl);
  console.log("fetch success");
  fs.writeFile(output_file, JSON.stringify(result),(err)=>{
   if(err)
     console.log("write file failed:" + err);
   else
     console.log("saved to " + output_file);
  });
})();
