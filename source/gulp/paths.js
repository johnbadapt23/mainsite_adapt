module.exports = {
    build: {
        base: 'assets/',
        scripts: 'assets/js/',
        styles: 'assets/css/',
        images: 'assets/images/',
        fonts: 'assets/fonts/',
        favicons: 'assets/icons/'
    },
    src: {
        html: '**/*.html',
        php: '**/*.php',
        scripts: [
            'source/components/select2/dist/js/select2.js',
            'source/components/magnific-popup/dist/jquery.magnific-popup.js',
            'source/components/slick-carousel/slick/slick.min.js',
            'source/components/jquery.scrollTo/jquery.scrollTo.js',
            'source/components/jquery.localScroll/jquery.localScroll.js',
            'source/components/aos/dist/aos.js',
            'source/components/js-cookie/src/js.cookie.js',
            'source/components/jquery.scrollbar-master/jquery.scrollbar.js',
            'source/components/perfect-scrollbar/js/perfect-scrollbar.jquery.js',
            'source/components/matchHeight/dist/jquery.matchHeight-min.js',
            'source/components/modernizr/modernizr-2.7.1.min.js',
            'source/js/main.js',
        ],
        styles: [
            'source/components/aos/dist/aos.css',
            'source/components/magnific-popup/dist/magnific-popup.css',
            'source/components/select2/dist/css/select2.css',
            'source/components/perfect-scrollbar/css/perfect-scrollbar.css',
            'source/components/jquery.scrollbar-master/jquery.scrollbar.css',
            'source/components/slick-carousel/slick/slick.css',
            'source/components/slick-carousel/slick/slick-theme.css',
            'source/components/hover/css/hover-min.css',
            'source/scss/main.scss',
        ],
        images: [
            'source/images/**/*.jpg',
            'source/images/**/*.gif',
            'source/images/**/*.svg',
            'source/images/**/*.png'
        ],
        fonts: 'source/fonts/*.{ttf,otf}',
        icons: 'source/icons/*.svg',
        favicon: {
            master: 'source/images/favicon.png',
            path: '/assets/icons/',
            data: 'faviconData.json',
            html: 'templates/partials/_icons.php',
            design: {
    			ios: {
    				pictureAspect: 'backgroundAndMargin', // backgroundAndMargin, noChange
    				backgroundColor: '#ffffff',
    				margin: '21%'
    			},
    			desktopBrowser: {},
    			windows: {
    				pictureAspect: 'whiteSilhouette', // noChange, whiteSilhouette
    				backgroundColor: '#b69e58',
    				onConflict: 'override'
    			},
    			androidChrome: {
    				pictureAspect: 'backgroundAndMargin', // noChange, backgroundAndMargin, shadow
    				margin: '17%',
    				backgroundColor: '#ffffff',
    				themeColor: '#ffffff',
    				manifest: {
    					name: 'Orchards',
    					display: 'browser', // browser, standalone
    					orientation: 'notSet',
    					onConflict: 'override'
    				}
    			},
    			safariPinnedTab: {
    				pictureAspect: 'silhouette', // noChange, silhouette, blackAndWhite
    				themeColor: '#000000'
    			}
    		},
            settings: {
    			compression: 5, // 0-5
    			scalingAlgorithm: 'Lanczos', // Mitchell, NearestNeighbor, Cubic, Bilinear, Lanczos, Spline
    			errorOnImageTooSmall: false
    		}
        }
    },
    watch: {
        html: '**/*.html',
        php: '**/*.php',
        scripts: 'source/js/**/*.js',
        style: 'source/scss/**/*.scss',
        images: 'source/images/**/*.*',
        fonts: 'source/fonts/**/*.ttf',
        icons: 'source/icons/*.svg',
        favicon: 'source/images/favicon.png'
    },
    deploy: {
        files: '**/*',
        folder: './',
        archive: 'CARERSNT.zip',
        repository: 'https://github.com/shop12dev/carersnt.git'
    }
};
