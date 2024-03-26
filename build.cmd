@rem あまりよくはない
set NODE_TLS_REJECT_UNAUTHORIZED=0

@call hexo clean
@call hexo gzip
node web_fetch.js
