
module.exports = {
  // Browsersync configuration.
  browsersync: {
    development: {
      proxy: "fluxkraft.dev",
      notify: true,
      //logLevel: 'debug',
      reloadDelay: 2000
    }
  },
  sass_all: {
    // Which sass folder should be used?
    src: [
      'sass/*.scss',
      'sass/panels/**/*.scss'
    ],
    // Destination folder.
    dest: 'css'
  },
  // Sass dev settings.
  sass_options_dev: {
    includePaths: [ './node_modules' ],
    errLogToConsole: true,
    sourceComments: true,
    outputStyle: 'nested' //nested, expanded, compact, compressed
  },
  // Sass productive settings.
  sass_options_prod: {
    includePaths: [ './node_modules' ],
    errLogToConsole: false,
    sourceComments: false,
    outputStyle: 'compressed' //nested, expanded, compact, compressed
  },
  // Autoprefixer settings.
  autoprefixer: {
    browsers: [
      'last 2 versions',
      'ie 10'
    ],
    cascade: false // Adds nice visual cascade for prefixes.
  }
};