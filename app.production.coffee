axis         = require 'axis'
rupture      = require 'rupture'
autoprefixer = require 'autoprefixer-stylus'
js_pipeline  = require 'js-pipeline'
css_pipeline = require 'css-pipeline'
dynamic_content = require 'dynamic-content'
common = require './common'

module.exports =
  ignores: ['readme.md', '*.md', '**/layout.jade', '**/_*', '.gitignore', 'ship.*conf', '*.cmd', 'common.coffee', '*.exe', '*.ps1']

  extensions: [
    js_pipeline(files: 'assets/js/*.coffee', out: 'js/build.js', minify: true),
    css_pipeline(files: 'assets/css/*.styl', out: 'css/build.css', minify: true),
    dynamic_content()
  ]

  stylus:
    use: [axis(), rupture(), autoprefixer()]

  jade:
    pretty: true
    basedir:__dirname + "/views"
    image: (src) ->
      common.image(src)

  'coffee-script':
    sourcemap: false
