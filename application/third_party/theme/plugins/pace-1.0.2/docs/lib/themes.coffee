if module?
  vm = require('vm')

  # Used by the eval'd code
  Color = require('application/third_party/theme/plugins/pace-1.0.2/docs/lib/color')

loadTheme = (name, cb) ->
  $.ajax
    url: "/pace/templates/pace-theme-#{ name }.tmpl.css"
    success: cb

compileTheme = (body, args={}) ->
  body.replace /`([\s\S]*?)`/gm, (match, code) ->
    if module?
      val = vm.runInNewContext code, {args, Color}
    else
      # It matters that args is in the context
      Color = window.Color
      val = eval(code)

    val

if module?
  module.exports = {compileTheme}
else
  window.loadTheme = loadTheme
  window.compileTheme = compileTheme
