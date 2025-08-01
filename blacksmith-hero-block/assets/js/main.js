(function($) {
    'use strict';

    // Hero Block Frontend functionality
    const BlacksmithHero = {
        init: function() {
            this.bindEvents();
            this.optimizeVideoLoad();
        },

        bindEvents: function() {
            $(document).ready(this.onReady.bind(this));
            $(window).on('load', this.onLoad.bind(this));
            $(window).on('resize', this.onResize.bind(this));
        },

        onReady: function() {
            this.initHeroBlocks();
        },

        onLoad: function() {
            this.optimizeVideoPlayback();
        },

        onResize: function() {
            this.handleResponsiveVideo();
        },

        initHeroBlocks: function() {
            $('.blacksmith-hero-wrapper').each(function() {
                const $hero = $(this);
                BlacksmithHero.setupHeroBlock($hero);
                
                // Debug logging
                if ($hero.hasClass('hero-bg-video')) {
                    console.log('Blacksmith Hero: Video background detected', $hero);
                }
            });
        },

        setupHeroBlock: function($hero) {
            if ($hero.hasClass('hero-bg-video')) {
                this.setupVideoBackground($hero);
            }
        },

        setupVideoBackground: function($hero) {
            const $iframe = $hero.find('iframe');
            
            if ($iframe.length) {
                // Ensure video is properly positioned
                this.positionVideo($iframe);
                
                // Add loading state
                $hero.addClass('video-loading');
                
                // Remove loading state when video is ready
                $iframe.on('load', function() {
                    $hero.removeClass('video-loading').addClass('video-loaded');
                    
                    // Attempt to start video playback after load
                    setTimeout(() => {
                        BlacksmithHero.attemptVideoPlay($(this));
                    }, 1000);
                });
                
                // Handle iframe errors
                $iframe.on('error', function() {
                    console.warn('Hero video failed to load');
                    $hero.removeClass('video-loading').addClass('video-error');
                });
            }
        },

        attemptVideoPlay: function($iframe) {
            try {
                // Send play command to YouTube iframe
                $iframe[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
            } catch (e) {
                console.warn('Could not control video playback:', e);
            }
        },

        positionVideo: function($iframe) {
            const updateVideoSize = () => {
                const windowWidth = $(window).width();
                const windowHeight = $(window).height();
                const aspectRatio = 16 / 9;
                
                let videoWidth, videoHeight;
                
                if (windowWidth / windowHeight > aspectRatio) {
                    videoWidth = windowWidth + 400;
                    videoHeight = videoWidth / aspectRatio;
                } else {
                    videoHeight = windowHeight + 400;
                    videoWidth = videoHeight * aspectRatio;
                }
                
                $iframe.css({
                    width: videoWidth + 'px',
                    height: videoHeight + 'px'
                });
            };
            
            updateVideoSize();
            $(window).on('resize.heroVideo', updateVideoSize);
        },

        optimizeVideoLoad: function() {
            // Intersection Observer for lazy loading videos
            if ('IntersectionObserver' in window) {
                const videoObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const $hero = $(entry.target);
                            this.loadVideo($hero);
                            videoObserver.unobserve(entry.target);
                        }
                    });
                });

                $('.blacksmith-hero-wrapper.hero-bg-video').each(function() {
                    videoObserver.observe(this);
                });
            }
        },

        loadVideo: function($hero) {
            const $iframe = $hero.find('iframe');
            if ($iframe.length && !$iframe.attr('data-loaded')) {
                $iframe.attr('data-loaded', 'true');
                // Video will auto-load due to src already being set
            }
        },

        optimizeVideoPlayback: function() {
            // Pause videos when tab is not visible
            $(document).on('visibilitychange', function() {
                const $videos = $('.blacksmith-hero-wrapper.hero-bg-video iframe');
                
                if (document.hidden) {
                    $videos.each(function() {
                        try {
                            this.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                        } catch (e) {
                            // Silently fail if unable to control video
                        }
                    });
                } else {
                    $videos.each(function() {
                        try {
                            this.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                        } catch (e) {
                            // Silently fail if unable to control video
                        }
                    });
                }
            });
        },

        handleResponsiveVideo: function() {
            $('.blacksmith-hero-wrapper.hero-bg-video iframe').each(function() {
                BlacksmithHero.positionVideo($(this));
            });
        }
    };

    // Initialize when DOM is ready
    BlacksmithHero.init();

})(jQuery);
//# sourceMappingURL=main.js.map
