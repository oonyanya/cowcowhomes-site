const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

// Output File
const output_file = ".\\public\\" + "rentals.json";

// Site url to crawl
const startUrl = "https://tokyo.craigslist.org/search/hhh?availabilityMode=0&cc=us&lang=en&query=99902%20-sale&sort=date#search=1~gallery~0~0";

const visitedUrls = new Set();
const urlTitleList = [];

async function crawl(url) {
  if (!visitedUrls.has(url)) {
    try {
      const response = await axios.get(url);
      const $ = cheerio.load(response.data);
      visitedUrls.add(url);

      // このページ内のリンクを取得
      const links = [];
      $("a[href]").each((_, element) => {
        let detail = $(element).find("div.details");
        let url = $(element).attr('href');
        if(url == "#" || url =="/")
          return;
        urlTitleList.push({
          link: url,
          title: $(element).find("div.title").text(),
          price: detail.find("div.price").text(),
        });
      });

    } catch (error) {
      console.error(`Error crawling ${url}:`, error.message);
    }
  }
}

(async () => {
  console.log("fetch start:" + startUrl);
  await crawl(startUrl);
  console.log("fetch success");
  fs.writeFile(output_file, JSON.stringify(urlTitleList),(err)=>{
   if(err)
     console.log("write file failed:" + err);
   else
     console.log("saved to " + output_file);
  });
})();
