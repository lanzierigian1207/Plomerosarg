(function($) {
	var appStart = function () {

    var screenWidth = $(window).width();
    // handlePreloaderOnClick
    function handlePreloaderOnClick() {
        const preloader = document.querySelector(".prealoader");
        if (preloader) {
            setTimeout(() => {
                preloader.style.opacity = "0";
                preloader.style.visibility = "hidden";
            }, 1500);
        }
    }

    // handleStickyHeader
    var handleStickyHeader = function () {
        if ($(".sticky-nav").length > 0) {
            var navbar = $(".sticky-nav");
            var navbarOffset = navbar.offset().top;
            $(window).on("scroll", function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > navbarOffset) {
                    navbar.addClass("sticky-menu");
                } else {
                    navbar.removeClass("sticky-menu");
                }
            });
        }
    }
    
    // handle Header Menu ============
    var handleHeaderMenu = function () {
        if ($(window).width() <= 991) {
            jQuery('.menu-wrap li > a, .dropdown-menu li > a')
                .off("click keypress")
                .on("click keypress", function (e) {
                    if (e.type === "keypress" && e.key !== "Enter") return;
                    // handleMenus(e, jQuery(this));
                });
            jQuery('.tabindex').attr("tabindex", "0");
    
            jQuery('.mobile-toggler button')
                .off('click')
                .on('click', function (e) {
                    e.preventDefault();
                    let parentLi = jQuery(this).closest('li');
                    let dropdown = parentLi.find('.dropdown-menu').first();
                    if (parentLi.hasClass('open')) {
                        dropdown.slideUp('slow', function () {
                            jQuery(this).css('display', 'none');
                        });
                        parentLi.removeClass('open');
                    } else {
                        parentLi.siblings('li').removeClass('open')
                            .children('.dropdown-menu, .mega-menu').slideUp('slow').css('display', 'none');
                        dropdown.css('display', 'block').slideDown();
                        parentLi.addClass('open');
                    }
                });
        } else {
            jQuery('.tabindex').removeAttr("tabindex");

            jQuery('.dropdown-menu, .mega-menu').removeAttr('style');
            jQuery('.menu-wrap li').removeClass('open');
        }
    };
    
    
    // Handle Author Popup ============
	var handleAuthorPopup = function () {
        const menuToggler = document.querySelector('.about-toggle');
        const searchBox = document.querySelector('.docker-widget-popup');
        if (menuToggler) {
            const body = document.querySelector('body');
            const menuClose = document.querySelector('.docker-widget-close');
            const target = menuToggler.getAttribute('data-target'); 
            const targetElement = document.querySelector(target);
            if (targetElement) {
                menuToggler.addEventListener('click', function() {
                    body.classList.add('docker-popup-active');
                    searchBox.classList.add('docker-widget-active');
                });
                
                menuClose.addEventListener('click', function() {
                    body.classList.remove('docker-popup-active');
                    searchBox.classList.remove('docker-widget-active');
                });
            } else {
                console.error('Target element not found:', target);
            }
        }
    };

    // Handle Toggler Menu ============
    var handleTogglerMenu = function () {
        const menuToggler = document.querySelector('.menu-toggle');
    
        if (menuToggler) {
            const body = document.querySelector('body');
            const menuClose = document.querySelector('.menu-close');
            const target = menuToggler.getAttribute('data-target');
            const targetElement = document.querySelector(target);
    
            const toggleMenu = () => {
                if (window.innerWidth <= 991) {
                    menuToggler.addEventListener('click', openMenu);
                    menuClose.addEventListener('click', closeMenu);
                } else {
                    menuToggler.removeEventListener('click', openMenu);
                    menuClose.removeEventListener('click', closeMenu);
                    menuToggler.classList.remove('open');
                    body.classList.remove('fixed');
                    targetElement.classList.remove('show');
                }
            };
            const openMenu = () => {
                menuToggler.classList.add('open');
                body.classList.add('fixed');
                targetElement.classList.add('show');
            };
            const closeMenu = () => {
                menuToggler.classList.remove('open');
                body.classList.remove('fixed');
                targetElement.classList.remove('show');
            };
            toggleMenu();
            window.addEventListener('resize', toggleMenu);
        }
    }
    

    // Handle Search Toggle ============
    var handleSearchToggle = function () {
        const toggleButton = document.querySelector('.header-search-toggle');
        const searchBox = document.querySelector('.header-search-popup');
        const closeButton = document.querySelector('.header-search-close');
        if (toggleButton && searchBox) {
            toggleButton.addEventListener('click', function () {
                searchBox.classList.add('header-search-active');
            });
            document.addEventListener('click', function (e) {
                if (!searchBox.contains(e.target) && !toggleButton.contains(e.target)) {
                    searchBox.classList.remove('header-search-active');
                }
            });
            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    searchBox.classList.remove('header-search-active');
                });
            }
        } else {
            console.log('Search toggle elements are missing!');
        }
    };

    // handleMainSlider 1 ==============
    var handleMainSlider1 = function () {
        if (jQuery('.main-slider').length > 0) {
			var $mainSlider = $(".main-slider");

			if ($mainSlider.length > 0) {
				$mainSlider.owlCarousel({
					items: 1,
					loop: true,
					autoplay: true,
					autoplayTimeout: 5000,
					autoplayHoverPause: true,
					smartSpeed: 1000,
					nav: true,
					dots: false,
					autoHeight: true,
					animateOut: "fadeOut",
					animateIn: "fadeIn",
					navText: [
						"<i class='fa fa-chevron-left'></i>",
						"<i class='fa fa-chevron-right'></i>"
					],
					responsive: {
						0: { nav: false },
						767: { nav: true }
					},
					onInitialized: function () {
						updateNavBackgrounds();
						applyAnimations();
					},
					onTranslated: function () {
						updateNavBackgrounds();
						applyAnimations();
					}
				});
		
				function updateNavBackgrounds() {
					var $active = $(".main-slider .owl-item.active");
					$(".owl-item").removeClass("prev next");
		
					var $next = $active.next(".owl-item").addClass("next"),
						$prev = $active.prev(".owl-item").addClass("prev"),
						nextImg = $next.find(".item img").attr("data-img-url"),
						prevImg = $prev.find(".item img").attr("data-img-url");
					$(".main-slider .owl-nav .owl-prev").css({
						backgroundImage: prevImg ? "url(" + prevImg + ")" : "none"
					});
					$(".main-slider .owl-nav .owl-next").css({
						backgroundImage: nextImg ? "url(" + nextImg + ")" : "none"
					});
				}
		
				function applyAnimations() {
					var $activeSlide = $mainSlider.find(".owl-item.active");
					$mainSlider.find("[data-animation]").each(function () {
						var animName = $(this).data("animation");
						$(this).removeClass("animated " + animName).css("opacity", "0");
					});

					$activeSlide.find("[data-animation]").each(function () {
						var $el = $(this),
							animName = $el.data("animation"),
							animDelay = $el.data("delay") || "0s",
							animDuration = $el.data("duration") || "1s";
						$el.css({
							"animation-delay": animDelay,
							"animation-duration": animDuration,
							"opacity": "1"
						}).addClass("animated " + animName);
					});
				}
			}                       
        }
    };

    // handleMainSlider 2 ==============
    var handleMainSlider2 = function() {	
        if(jQuery('.main-slider2').length > 0){
            jQuery(document).ready(function ($) {
                if ($.fn.owlCarousel) {
                    var sync1 = $('.main-slider2');
                    var sync2 = $('.owl-thumbs-main');
                    var syncedSecondary = true;
                
                    sync1.owlCarousel({
                        items: 1,
                        autoplay: true,
                        smartSpeed: 1000,
                        nav: false,
                        dots: false,
                        loop: true,
                        mouseDrag:false,
                        responsiveRefreshRate: 200,
                        onTranslated: applyAnimations
                    }).on('changed.owl.carousel', syncPosition);
                
                    sync2.owlCarousel({
                        items: 6,
                        margin: 0,
                        nav: false,
                        dots: false,
                        smartSpeed: 1000,
                        slideBy: 6,
                        responsiveRefreshRate: 100,
                        responsive: {
                            0: { items: 4 },
                            768: { items: 5 },
                            991: { items: 6 },
                        }
                    }).on('changed.owl.carousel', syncPosition2);
                
                    function syncPosition(el) {
                        var count = el.item.count - 1;
                        var current = Math.round(el.item.index - (el.item.count / 2) - 0.5);
                        if (current < 0) {
                            current = count;
                        }
                        if (current > count) {
                            current = 0;
                        }
                        sync2.find('.owl-item').removeClass('synced').eq(current).addClass('synced');
                        if (syncedSecondary) {
                            sync2.trigger('to.owl.carousel', [current, 100, true]);
                        }
                    }
                    function syncPosition2(el) {
                        if (syncedSecondary) {
                            var number = el.item.index;
                            sync1.trigger('to.owl.carousel', [number, 100, true]);
                        }
                    }
                    sync2.on('click', '.owl-item', function(e) {
                        e.preventDefault();
                        var number = $(this).index();
                        sync1.trigger('to.owl.carousel', [number, 300, true]);
                    });
                    function applyAnimations() {
                        var $activeSlide = sync1.find(".owl-item.active");
                        
                        // Remove previous animations
                        sync1.find("[data-animation]").each(function () {
                            var animName = $(this).data("animation");
                            $(this).removeClass("animated " + animName).css("opacity", "0");
                        });
        
                        // Apply animations to active slide
                        $activeSlide.find("[data-animation]").each(function () {
                            var $el = $(this),
                                animName = $el.data("animation"),
                                animDelay = $el.data("delay") || "0s",
                                animDuration = $el.data("duration") || "1s";
                            
                            $el.css({
                                "animation-delay": animDelay,
                                "animation-duration": animDuration,
                                "opacity": "1"
                            }).addClass("animated " + animName);
                        });
                    }
                    applyAnimations();
                } else {
                    console.error("Owl Carousel plugin is not loaded.");
                }
            });
        }
    }

    // handle Portfolio  ==============
    var handlePortfolioSlider = function() {	
        if(jQuery('.portfolio-slider').length > 0){
            jQuery(document).ready(function ($) {
                $(".portfolio-slider").owlCarousel({
                    center: true,
                    items:2,
                    loop: true,
                    margin:55,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    nav: true,
                    dots: false,
                    autoHeight: true,
                    animateOut: "fadeOut",
                    animateIn: "fadeIn",
                    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
                    responsive: {
                        0: { 
                            items: 1
                        },
                        991: {
                            center: true,
                            items: 2
                        }
                    }
                });
            });
        }
    }

    // handle Testimonial  ============
    var handleTestimonialSlider = function() {	
        if(jQuery('.testimonial-slider').length > 0){
            jQuery(document).ready(function ($) { 
                var owl = $(".testimonial-slider").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: false,
                    dots: true,
                    autoplay: true,
                    autoHeight: false,
                    autoplayTimeout: 3000,
                    responsive: {
                        0: { items: 1 },
                        668: { items: 2 }
                    }
                });
                // Move Active Dot to Center
                function centerActiveDot() {
                    var totalDots = $('.owl-dot').length;
                    var activeDot = $('.owl-dot.active');
                    var index = activeDot.index();
                    var shift = Math.floor(totalDots / 2) - index;
                    $(".owl-dots").addClass('move').css("transform", `translateX(${shift * 20}px)`);
                }

                // Trigger when slide changes
                owl.on('changed.owl.carousel', function() {
                    centerActiveDot();
                });


            });
        }
    }
    
    // handle Business  ===============
    var handleBusinessSwiper = function() {
        if (jQuery('.business-section').length > 0) {
    
            const sliderThumbs = new Swiper('.business-thumbs-slider .swiper-container', {
                direction: 'vertical',
                slidesPerView: 2,
                mousewheel: true,
                navigation: {
                    nextEl: '.slider__next',
                    prevEl: '.slider__prev'
                },
                scrollbar: {
                    el: ".swiper-scrollbar",
                    hide: true,
                },
                breakpoints: {
                    0: {
                        direction: 'horizontal',
                        slidesPerView: 1,
                    },
                    575: {
                        direction: 'horizontal',
                        slidesPerView: 2,
                    },
                    768: {
                        direction: 'vertical',
                        slidesPerView: 2,
                    }
                }
            });
            const sliderImages = new Swiper('.business-main-slider .swiper-container', {
                direction: 'vertical',
                slidesPerView: 1,
                spaceBetween: 5,
                mousewheel: true,
                navigation: {
                    nextEl: '.slider__next',
                    prevEl: '.slider__prev'
                },
                grabCursor: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false
                },
                thumbs: {
                    swiper: sliderThumbs
                },
                breakpoints: {
                    0: {
                        direction: 'horizontal',
                        mousewheel: false,
                    },
                    768: {
                        direction: 'vertical',
                    }
                }
            });
    
        }
    };
    

    // handle Client  =================
    var handleClientSlider = function() {	
        if(jQuery('.client-slider').length > 0){
            jQuery(document).ready(function ($) {
                $(".client-slider").owlCarousel({
                    center: true,
                    items:5,
                    loop: true,
                    margin:40,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    nav: false,
                    dots: false,
                    autoHeight: true,
                    responsive: {
                        0: { 
                            items: 2,
                            center: false
                        },
                        575: { 
                            items: 3,
                            center: true
                        },
                        767: { 
                            items: 4 ,
                            center: false
                        },
                        991: { 
                            items: 5,
                            center: true
                        }
                    }
                });
            });
        }
    }
    // handle Social  =================
    var handleSocialSlider = function() {	
        if(jQuery('.social-slider').length > 0){
            jQuery(document).ready(function ($) {
                $(".social-slider").owlCarousel({
                    items:6,
                    loop: true,
                    // margin:40,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    nav: false,
                    dots: false,
                    autoHeight: true,
                    responsive: {
                        0: { 
                            items: 2
                        },
                        420: { 
                            items: 3
                        },
                        575: { 
                            items: 4
                        },
                        767: { 
                            items: 5
                        },
                        991: { 
                            items: 6
                        }
                    }
                });
            });
        }
    }
    // handle Award  =================
    var handleAwardSlider = function() {	
        if(jQuery('.award-slider').length > 0){
            jQuery(document).ready(function ($) {
                $(".award-slider").owlCarousel({
                    items:4,
                    loop: true,
                    margin:100,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    nav: false,
                    dots: false,
                    autoHeight: true,
                    responsive: {
                        0:{ 
                            items: 2,
                            margin:50,
                        },
                        575:{ 
                            items: 3,
                            margin:50,
                        },
                        767:{ 
                            items: 4,
                            margin:50,
                        },
                        991:{ 
                            items: 4,
                            margin:100,
                        }
                    }
                });
            });
        }
    }
    // handle Footer Client  =========
    var handleFooterClientSlider = function() {	
        if(jQuery('.footer-client-slider').length > 0){
            jQuery(document).ready(function ($) {
                $(".footer-client-slider").owlCarousel({
                    items:4,
                    loop: false,
                    margin:0,
                    autoplay: false,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    nav: false,
                    dots: false,
                    autoHeight: true,
                    responsive: {
                        0: { 
                            items: 1,
                        },
                        450: { 
                            items: 2,
                        },
                        767: { 
                            items: 3,
                        },
                        991: { 
                            items: 4,
                        }
                    }
                });
            });
        }
    }
    // handle Footer Download  ======
    var handleFooterDownloadSlider = function() {	
        if(jQuery('.footer-download-slider').length > 0){
            jQuery(document).ready(function ($) {
                $(".footer-download-slider").owlCarousel({
                    items:3,
                    loop: false,
                    margin:0,
                    autoplay: false,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    nav: false,
                    dots: false,
                    autoHeight: true,
                    responsive: {
                        0: { 
                            items: 1,
                        },
                        375: { 
                            items: 2,
                        },
                        520: { 
                            items: 3,
                        },
                        991:{
                            items: 2,
                        },
                        1044:{
                            items: 3,
                        }
                    }
                });
            });
        }
    }

    // handle Blog Post  ============
    var handleBlogPostCarousel = function() {	
        if(jQuery('.post-carousel').length > 0){
			$(".post-carousel").owlCarousel({
				center: true,
				loop: true,
				margin: 25,
				stagePadding: 15,
				nav: false,
				dots: false,
				autoplay: true,
				autoHeight: false,
				autoplayTimeout: 3000,
				responsive: {
					0: { items: 1 },
					600: { 
						items: 2,
						center: false,
					 },
					1024: { items: 3 }
				}
			});
        }
    }

    // handle About Testimonial  ============
    var handleAboutTestimonialCarousel = function() {	
        if(jQuery('.about-testimonial-carousel').length > 0){
            jQuery(document).ready(function ($) {
                $(".about-testimonial-carousel").owlCarousel({
                    center: true,
                    loop: true,
                    items: 3,
                    nav: false,
                    dots: false,
                    autoplay: true,
                    autoHeight: false,
                    autoplayTimeout: 3000,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 2,center: false,},
                        1024: { items: 3 }
                    }
                });
            });
        }
    }

    // handle Box Hover =============
	var handleBoxHover = function () {
		const boxHover = document.querySelectorAll('.box-hover');
		if(boxHover){
			boxHover.forEach(function(element){
				element.addEventListener('mouseenter', function(){
					const selector = element.parentElement.parentElement;
					selector.querySelectorAll('.box-hover').forEach(function(element){
						element.classList.remove('active');
					});
					element.classList.add('active');
				});
			});			
		}
	}

    // Marquee  =====================
    var handleMarquee = function () {
        const marquees = document.querySelectorAll('.mrq-loop');
    
        marquees.forEach(marquee => {
            const ul = marquee.querySelector('ul');
            if (ul) {
                ul.innerHTML += ul.innerHTML;
                let direction = marquee.getAttribute("direction") || "left";
                let baseSpeed = marquee.getAttribute("scrollamount") || "20";
                let speed = window.innerWidth <= 768 ? baseSpeed * 4 : baseSpeed;
                let duration = `${100 / speed * 10}s`;
                let animationName = direction === "right" ? "marquee-right" : "marquee-left";
                ul.style.animation = `${animationName} ${duration} linear infinite`;
            }
        });
    };
    

    // Scroll To Top ============
    var handleScrollTop = function () {
        const scrollTops = document.querySelectorAll('.scrollup');
        if (scrollTops.length > 0) {
            window.addEventListener('scroll', function() {
                const scrollEl = window.scrollY;
                scrollTops.forEach(scrollTop => {
                    if (scrollEl > 500) {
                        scrollTop.classList.add('is-active');
                    } else {
                        scrollTop.classList.remove('is-active');
                    }
                });
            });

            scrollTops.forEach(scrollTop => {
                scrollTop.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        }
    };
    
    // handle skill Bar =============
	var handleSkillBar = function () {
        $(".progress-bar").each(function () {
            let $this = $(this);
            if (isScrolledIntoView($this) && !$this.hasClass("animated")) {
                $this.addClass("animated");
                let percent = $this.attr("data-percent");
                let progressFill = $this.find(".progress-fill");
                let progressText = $this.find(".circle-fill");
                let countBar = $this.find(".count-bar");
                progressFill.animate({ width: percent }, 3000);
                progressText.animate({ left: percent }, 3000);
                $({ countNum: 0 }).animate({ countNum: parseInt(percent) }, {
                    duration: 3000,
                    easing: 'linear',
                    step: function () {
                        countBar.text(Math.floor(this.countNum));
                    },
                    complete: function () {
                        countBar.text(this.countNum);
                    }
                });
            }
        });
    };
    
    // Scroll START function---
    function isScrolledIntoView(element) {
       let windowHeight = $(window).height();
       let elementTop = $(element).offset().top;
       let scrollTop = $(window).scrollTop();
       return elementTop < (scrollTop + windowHeight - 50);
    }

    /* Magnific Popup ============ */
	var MagnificPopup = function(){
        if ($.fn.magnificPopup) {
            $(".popup-youtube, .popup-vimeo").magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: true,
                iframe: {
                    patterns: {
                        youtube: {
                            index: 'youtube.com/',
                            id: function(url) {
                                var match = url.match(/[\\?&]v=([^&#]*)/);
                                return match ? match[1] : null;
                            },
                            src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                        },
                        vimeo: {
                            index: 'vimeo.com/',
                            id: function(url) {
                                var match = url.match(/vimeo.com\/(\d+)/);
                                return match ? match[1] : null;
                            },
                            src: 'https://player.vimeo.com/video/%id%?autoplay=1'
                        }
                    }
                }
            });
        } else {
            console.error("Magnific Popup is not loaded!");
        }
	}

    /* Counter Number ============ */
	var counter = function(){
		if(jQuery('.counter').length)
		{
			jQuery('.counter').counterUp({
				delay: 100,
				time: 5000
			});	
		}
	}

    // handle Active Remove  ============
    var toggleActiveClass = function() {	
        if(jQuery('.price-feature, grid-list').length > 0){
            document.querySelectorAll('.price-feature, grid-list').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });
        }
    }

    // handle Masonry Filter  ============
    var handleMasonryFilter = function() {	
        jQuery(document).ready(function ($) {
            jQuery('.av-filter-init').mixItUp({
                selectors: {
                    target: '.tile',
                    filter: '.filter',
                    sort: '.sort-btn'
                },
                animation: {
                    animateResizeContainer: false,
                    effects: 'fade scale'
                }
            });
        });
    };
    
    // handle Clcik Toggle  ============
    var handleClcikToggle = function () {
        document.querySelectorAll(".toggle-group").forEach(group => {
            const toggles = group.querySelectorAll(".clcik-toggle");
            const groupName = group.getAttribute("data-group");
            const sections = document.querySelectorAll(`.custom-tab[data-group='${groupName}']`);
            toggles.forEach(toggle => {
                toggle.addEventListener("click", function () {
                    toggles.forEach(el => el.classList.remove("active"));
                    sections.forEach(sec => sec.classList.remove("active"));

                    this.classList.add("active");
                    document.getElementById(this.getAttribute("data-target")).classList.add("active");
                });
            });

            // Default Active State
            if (toggles.length > 0) {
                toggles[0].classList.add("active");
                sections[0].classList.add("active");
            }
        });
    };

    // handle Cursor Point  ============
    var handleCursorPoint = function () {
        const cursor = document.querySelector('#cursor');
        const cursorCircle = cursor.querySelector('.cursor__circle');
        
        const mouse = { x: -100, y: -100 }; // mouse pointer's coordinates
        const pos = { x: 0, y: 0 }; // cursor's coordinates
        const speed = 0.1; // between 0 and 1
        
        const updateCoordinates = e => {
          mouse.x = e.clientX;
          mouse.y = e.clientY;
        }
        
        window.addEventListener('mousemove', updateCoordinates);
        
        function getAngle(diffX, diffY) {
          return Math.atan2(diffY, diffX) * 180 / Math.PI;
        }
        
        function getSqueeze(diffX, diffY) {
          const distance = Math.sqrt(
            Math.pow(diffX, 2) + Math.pow(diffY, 2)
          );
          const maxSqueeze = 0.15;
          const accelerator = 1500;
          return Math.min(distance / accelerator, maxSqueeze);
        }
        
        
        const updateCursor = () => {
          const diffX = Math.round(mouse.x - pos.x);
          const diffY = Math.round(mouse.y - pos.y);
          
          pos.x += diffX * speed;
          pos.y += diffY * speed;
          
          const angle = getAngle(diffX, diffY);
          const squeeze = getSqueeze(diffX, diffY);
          
          const scale = 'scale(' + (1 + squeeze) + ', ' + (1 - squeeze) +')';
          const rotate = 'rotate(' + angle +'deg)';
          const translate = 'translate3d(' + pos.x + 'px ,' + pos.y + 'px, 0)';
        
          cursor.style.transform = translate;
          cursorCircle.style.transform = rotate + scale;
        };
        
        function loop() {
          updateCursor();
          requestAnimationFrame(loop);
        }
        
        requestAnimationFrame(loop);
        
        const cursorModifiers = document.querySelectorAll('[data-cursor-type]');
        const cursorLinks = document.querySelectorAll('a:not(.cursor-style)');
        
        cursorModifiers.forEach(curosrModifier => {
          curosrModifier.addEventListener('mouseenter', function() {
            const className = this.getAttribute('data-cursor-type');
            cursor.classList.add(className);
            const cursorText = this.getAttribute('data-custom-text');
            if (cursorText !== null) {
                cursor.setAttribute('data-cursor-text', cursorText);
            } else {
              cursor.setAttribute('data-cursor-text', 'Drag');
          }
        });
        
        curosrModifier.addEventListener('mouseleave', function() {
            const className = this.getAttribute('data-cursor-type');
            cursor.classList.remove(className);
            cursor.removeAttribute('data-cursor-text');
          });
        });
        
        cursorLinks.forEach(cursorLink => {
          cursorLink.addEventListener('mouseenter', function() {
            //const className = this.getAttribute('a');
            cursor.classList.add('cursor-link');
        });
        
        cursorLink.addEventListener('mouseleave', function() {
            //const className = this.getAttribute('a');
            cursor.classList.remove('cursor-link');
          });
        });
    }

    // handle Tilt Animation  ============
    var handleTiltAnimation = function () {
        const elements = document.querySelectorAll(".tilt"); // Select all tilt elements
        elements.forEach(item => {
            item.addEventListener("mousemove", event => {
                const { width, height, left, top } = item.getBoundingClientRect();

                const offsetX = ((event.clientX - left) / width - 0.5) * 20; // Normalize X (-5 to 5)
                const offsetY = ((event.clientY - top) / height - 0.5) * -20; // Normalize Y (-5 to 5)

                item.style.transform = `perspective(1000px) rotateY(${offsetX}deg) rotateX(${offsetY}deg)`;
            });

            // Reset effect on mouse leave
            item.addEventListener("mouseleave", () => {
                item.style.transform = "perspective(1000px) rotateY(0deg) rotateX(0deg)";
            });
        });
    }

    // ====== Dark / Light Theme Switcher ====>
    function Theme_dark_light() {
        const switcher = $('#dark-mode-switch');
        const dhtml = $("html");
        const logo = $(".logo a img");
        const storedDarkMode = localStorage.getItem('dark-mode') === 'true';
        const storedLogo = localStorage.getItem("logoTheme");
        if (storedDarkMode) {
            dhtml.addClass('dark-mode');
            switcher.prop('checked', true);
        }
        
        if (storedLogo) {
            logo.attr("src", storedLogo);
        }

        switcher.on('change', function () {
            const isDarkMode = dhtml.toggleClass('dark-mode').hasClass('dark-mode');
    
            localStorage.setItem('dark-mode', isDarkMode);
    
            const logoSrc = isDarkMode || window.location.pathname.includes("index02.html")
                ? "assets/images/logo-light.png"
                : "assets/images/logo-dark.png";
            
            logo.attr("src", logoSrc);
            localStorage.setItem("logoTheme", logoSrc);
        });
    
        // Show switcher button on scroll
        $(window).on('scroll', function () {
            $('.switcher').toggleClass('show-dark_mode', $(window).scrollTop() >= 250);
        });
    }
    // === End =====//
    
    // ---------------- Ripple Bg Effect---------------------->
    function rippleEffect() {
        if ($(".ripple-area").length) {
            $(".ripple-area").ripples({
                resolution: 512,
                dropRadius: 20,
                perturbance: 0.04,
            });
        }
    }

   // handle Bubbly Effect  ============
    function BubblyEffect() {
        var elements = document.getElementsByClassName('bubbly-effect');
        for (var i = 0; i < elements.length; i++) {
            elements[i].addEventListener('mouseover', function (e) {
                e.target.classList.add('animate');
                setTimeout(() => {
                    e.target.classList.remove('animate');
                }, 450);
            });
        }
    }

    // handle Grid List Toggle  ============
    var handleGridListToggle = function() {	
        if (jQuery('.gridlistToggle').length > 0) {
            document.querySelectorAll('.gridlistToggle').forEach(item => {
                item.addEventListener('click', function() {
                    // Remove 'active' class from both buttons
                    document.querySelectorAll('.gridlistToggle').forEach(btn => btn.classList.remove('active'));
                    
                    // Add 'active' class to the clicked button
                    this.classList.add('active');
    
                    // Get the product container
                    var productList = document.querySelector('.products');
    
                    if (productList) {
                        if (this.id === 'grid') {
                            productList.classList.remove('list');
                            productList.classList.add('grid');
                        } else if (this.id === 'list') {
                            productList.classList.remove('grid');
                            productList.classList.add('list');
                        }
                    }
                });
            });
        }
    };
    
    // handle Trap Focus  ============
    var handleTrapFocus = function(focusDelay = 0) {
        let $containers = $('.docker-widget-popup, .header-search-popup');

        $containers.each(function () {
            let $container = $(this);
            let tabbable = $container
                .find('select, input, textarea, button, a, [href], [tabindex]:not([tabindex="-1"])')
                .filter(':visible');

            if (tabbable.length === 0) return;

            let firstTabbable = tabbable.first();
            let lastTabbable = tabbable.last();

            setTimeout(() => {
                firstTabbable.focus();
            }, focusDelay);

            firstTabbable.off('keydown.trap').on('keydown.trap', function (e) {
                if (e.which === 9 && e.shiftKey) {
                    e.preventDefault();
                    lastTabbable.focus();
                }
            });

            lastTabbable.off('keydown.trap').on('keydown.trap', function (e) {
                if (e.which === 9 && !e.shiftKey) {
                    e.preventDefault();
                    firstTabbable.focus();
                }
            });
        });
    }
    function go() {
        const containerWidth = 1170;
        const extraWidth = 400;
        const totalWidth = window.innerWidth;
        const calculatedWidth = (totalWidth - containerWidth) / 2 + extraWidth;
        const finalWidth = Math.max(calculatedWidth, 240);
        document.documentElement.style.setProperty('--after-width', `${finalWidth}px`);
    }
    
    
    /* Function ============ */
	return {
		init: function () {
            BubblyEffect();
            handleTrapFocus(100);
            handlePreloaderOnClick();
            handleHeaderMenu();
            handleStickyHeader();
            handleTogglerMenu();
            handleAuthorPopup();
            handleSearchToggle();
            handleMainSlider1();
            handleMainSlider2();
            handlePortfolioSlider();
            handleTestimonialSlider();
            handleBusinessSwiper();
            handleClientSlider();
            handleSocialSlider();
            handleAwardSlider();
            handleBlogPostCarousel();
            handleAboutTestimonialCarousel();
            handleBoxHover();
            handleMarquee();
            handleFooterClientSlider();
            handleFooterDownloadSlider();
            handleScrollTop();
            MagnificPopup();
            handleMasonryFilter();
            toggleActiveClass();
            handleClcikToggle();
            handleCursorPoint();
            handleTiltAnimation();
            Theme_dark_light();
            rippleEffect();
            handleSkillBar();
            handleGridListToggle();
            go();
		},
        
		load: function () {
            BubblyEffect();
            handleSkillBar();
			counter();
		},
		
		resize: function () {
            screenWidth = $(window).width();
            handleHeaderMenu();
            handleTogglerMenu();
            go();
		},

        scroll: function () {
            handleSkillBar();
		}
	}
}();

 
/* Document.ready Start */
document.addEventListener('DOMContentLoaded', function() {
	appStart.init();
    initCustomUIcommen();
    initCustomPaymentMethod();
});
/* Document.ready END */

/* Window Load START */
window.addEventListener('load', function() {
	appStart.load();

});
/* Window Load END */

/* Window Resize START */
window.addEventListener('resize', function() {
	appStart.resize();
});
/*  Window Resize END */

/* Window Scroll START */
window.addEventListener("scroll", function () {
    appStart.scroll();
});
/*  Window Scroll END */


function initCustomUIcommen() {
    // Address 2 Toggle
    const toggleButton = document.querySelector(".wc-block-components-address-form__address_2-toggle");
    const inputContainer = document.querySelector(".wc-block-components-address-form__address_2");
    if (toggleButton && inputContainer) {
        inputContainer.style.display = "none";
        toggleButton.addEventListener("click", function () {
            toggleButton.style.display = "none";
            inputContainer.style.display = "block";
        });
    }

    // Checkbox-controlled textarea
    const checkbox = document.getElementById("checkbox-control-1");
    const textarea = document.querySelector(".wc-block-components-textarea");
    if (checkbox && textarea) {
        textarea.style.display = "none";
        checkbox.addEventListener("change", function () {
            textarea.style.display = checkbox.checked ? "block" : "none";
        });
    }

    // Panel toggle buttons
    const toggleButtons1 = document.querySelectorAll(".wc-block-components-panel__button");
    if (toggleButtons1) {
        toggleButtons1.forEach(function (button) {
            const content = button.nextElementSibling;
            const isExpanded = button.getAttribute("aria-expanded") === "true";
            if (content) {
                content.style.display = isExpanded ? "block" : "none";
                button.addEventListener("click", function () {
                    const currentlyVisible = content.style.display === "block";
                    content.style.display = currentlyVisible ? "none" : "block";
                    button.setAttribute("aria-expanded", !currentlyVisible);
                });
            }
        });
    }
}

// Payment method accordion behavior
function initCustomPaymentMethod() {
    const radioButtons = document.querySelectorAll('input[name="radio-control-wc-payment-method-options"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', () => {
            updateAccordionDisplay(radio);
        });
        if (radio.checked) {
            updateAccordionDisplay(radio);
        }
    });
    function updateAccordionDisplay(selectedRadio) {
        document.querySelectorAll('.wc-block-components-radio-control-accordion-option').forEach(option => {
            option.classList.remove('wc-block-components-radio-control-accordion-option--checked-option-highlighted');
            const content = option.querySelector('.wc-block-components-radio-control-accordion-content');
            if (content) {
                content.style.height = '0';
                content.style.opacity = '0';
                content.style.paddingTop = '0';
                content.style.paddingBottom = '0';
            }
        });
        const selectedOption = selectedRadio.closest('.wc-block-components-radio-control-accordion-option');
        if (selectedOption) {
            selectedOption.classList.add('wc-block-components-radio-control-accordion-option--checked-option-highlighted');
            const contentBox = selectedOption.querySelector('.wc-block-components-radio-control-accordion-content');
            if (contentBox) {
                contentBox.style.height = 'auto';
                let fullHeight = contentBox.scrollHeight + 20 + 'px';
                contentBox.style.height = '0';
                requestAnimationFrame(() => {
                    contentBox.style.height = fullHeight;
                    contentBox.style.opacity = '1';
                    contentBox.style.paddingTop = '0.5em';
                    contentBox.style.paddingBottom = '16px';
                });
            }
        }
    }
}
})(jQuery);