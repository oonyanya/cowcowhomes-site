module.exports.image = (src) ->
  path = require('path')
  dirname = path.dirname(src)
  filename = path.basename(src)
  outputdir = "img"
  file_path = path.join outputdir,dirname,filename
  thumb_file_path = path.join outputdir,dirname,"thumb_" + filename
  output = "<a href='\\%{file_path}' data-lightbox='room-images'><img src='\\%{thumb_file_path}'></a>"
  output
    .replace("%{file_path}", file_path)
    .replace("%{thumb_file_path}", thumb_file_path)
