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
        // isotope, jquery.scrollTo, jquery.localScroll, js-cookie, and
        // jquery.scrollbar-master (both its .js and .css) were removed from
        // this list 2026-09-24 (S119): confirmed via a repo-wide search --
        // main.js, every PHP template, and every inline <script> block --
        // that none of their APIs (.isotope(, .scrollTo(, localScroll(,
        // Cookies., .scrollbar() are ever called anywhere in this theme.
        // The one file that used to call isotope (source/js/includes/
        // _isotope.js) was itself never wired into the build (main.js only
        // @@include()s _maps.js -- see main.js), and the [data-filter] a
        // click target its handler expected doesn't exist in any current
        // template either -- the whole feature was already retired at the
        // template level, this just stops shipping the JS for it. Their
        // source/components/ vendor directories were deleted in the same
        // commit.
        scripts: [
            'source/components/select2/dist/js/select2.js',
            'source/components/magnific-popup/dist/jquery.magnific-popup.js',
            'source/components/slick-carousel/slick/slick.min.js',
            'source/components/aos/dist/aos.js',
            'source/components/perfect-scrollbar/js/perfect-scrollbar.jquery.js',
            'source/components/matchHeight/dist/jquery.matchHeight-min.js',
            // source/components/modernizr/modernizr-2.7.1.min.js referenced
            // here doesn't exist in the repo and hard-crashes build:scripts.
            // Modernizr isn't used anywhere else and isn't in the live JS
            // bundle either, so the reference is just removed.
            'source/js/main.js',
        ],
        // ScrollMagic + its GSAP plugin, split out of scripts above
        // 2026-09-15 -- see source/gulp/tasks/build/scripts-scrollmagic.js
        // for the full rationale. debug.addIndicators.js (the third file
        // previously bundled alongside these two) is dropped entirely, not
        // moved here: it's ScrollMagic's dev-only visual debug overlay, and
        // grepping main.js confirms `.addIndicators(` is never called --
        // it was 23KB of pure dead code in every build, including this one.
        scriptsScrollmagic: [
            'source/components/scrollmagic/scrollmagic/uncompressed/ScrollMagic.js',
            'source/components/scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js',
        ],
        styles: [
            'source/components/aos/dist/aos.css',
            'source/components/magnific-popup/dist/magnific-popup.css',
            'source/components/select2/dist/css/select2.css',
            'source/components/perfect-scrollbar/css/perfect-scrollbar.css',
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
