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
    js_pipeline(files: 'assets/js/*.coffee'),
    css_pipeline(files: 'assets/css/*.styl'),
    dynamic_content()
  ]

  stylus:
    use: [axis(), rupture(), autoprefixer()]
    sourcemap: true

  'coffee-script':
    sourcemap: true

  jade:
    pretty: true
    basedir:__dirname + "/views"
    image: (src) ->
      common.image(src)
