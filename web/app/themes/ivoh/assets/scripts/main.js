// FBSage - Firebelly 2015
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var FBSage = (function($) {

  var $window = $(window),
      $body = $('body'),
      breakpointIndicatorString,
      breakpoint_xl,
      breakpoint_lg,
      breakpoint_nav,
      breakpoint_md,
      breakpoint_sm,
      breakpoint_xs,
      breakpoints = [],
      breakpointClasses = ['xs','sm','md','nav','lg','xl'],
      resizeTimer,
      transitionElements,
      $document,
      $siteNav,
      loadingTimer,
      page_at;

  function _init() {
    // touch-friendly fast clicks
    FastClick.attach(document.body);

    // Cache some common DOM queries
    $document = $(document);
    $body.addClass('loaded');
    $siteNav = $('#site-nav');

    // Set screen size vars
    _resize();

    // Transition elements to enable/disable on resize
    transitionElements = [$siteNav, $('.search-form-container')];

    // Fit them vids!
    $('main').fitVids();

    _initThemeSwitcher();
    _initActiveToggle();
    _initNav();
    _initSearch();
    _initFormActions();
    _initCarousels();
    // _initLoadMore();
    _initAccordions();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        _hideSearch();
        _hideMobileNav();
      }
    });

    // Smoothscroll links
    $('a.smoothscroll').click(function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      _scrollBody($(href));
    });

    // Scroll down to hash afer page load
    $(window).load(function() {
      if (window.location.hash) {
        _scrollBody($(window.location.hash));
      }
    });

  } // end init()

  function _scrollBody(element, duration, delay) {
    if ($('#wpadminbar').length) {
      wpOffset = $('#wpadminbar').height();
    } else {
      wpOffset = 0;
    }
    element.velocity("scroll", {
      duration: duration,
      delay: delay,
      offset: -wpOffset
    }, "easeOutSine");
  }

  function _initThemeSwitcher() {
    $('#theme-switcher select').on('change', function(e) {
      var color = $(this).val();
      $('body').attr('data-theme', color);
    });
  }

  function _initActiveToggle() {
    $(document).on('click', '[data-active-toggle]', function(e) {
      $(this).toggleClass('-active');
      if ($(this).attr('data-active-toggle') !== '') {
        $($(this).attr('data-active-toggle')).toggleClass('-active');
      }
    });
  }

  function _initSearch() {
    $('.search-toggle').on('click', function(e) {
      if ($(this).is('.-active')) {
        _hideSearch();
      } else {
        _showSearch();
      }
    });

    $('.search-close').on('click', function() {
      _hideSearch();
    });
  }

  function _showSearch() {
    $body.addClass('search-open');
  }

  function _hideSearch() {
    $body.removeClass('search-open');
    $('#search, .search-toggle').removeClass('-active');
  }

  function _initFormActions() {  
    $('form input, form textarea').on('blur', function() {
      if($(this).val()) {
        $(this).parents('.input-wrap').addClass('filled');
      } else {
        $(this).parents('.input-wrap').removeClass('filled');
      }
    });
  }

  function _initCarousels() {
    // Page Banner Carousels
    var $storyImageCarousel = $('.story-image-carousel'),
        $storyContentCarousel = $('.story-content-carousel');

    var storyContentCarousel = $storyContentCarousel.flickity({
      pageDots: true,
      wrapAround: true,
      cellAlign: 'left',
      cellSelector: '.story-content',
      arrowShape: 'M55.1,100L7.9,52.8H100v-5.6H7.9L55.1,0h-7.9L0,47.2v5.6L47.2,100H55.1z'
    });
    
    var storyImageCarousel = $storyImageCarousel.flickity({
      pageDots: false,
      bgLazyLoad: 1,
      draggable: false,
      cellAlign: 'left',
      prevNextButtons: false,
      adaptiveHeight: true,
      setGallerySize: false,
      cellSelector: '.story-image-container',
      asNavFor: '.story-content-carousel'
    });

  }

  // Handles main nav
  function _initNav() {
    // SEO-useless nav toggler
    $('<div class="menu-toggle" aria-hidden="true"><span class="text">Menu</span><span class="menu-bar"></span></div>')
      .appendTo('header.site-header')
      .on('click', function(e) {
        if ($(this).is('.-active')) {
          _hideMobileNav();
        } else {
          _showMobileNav();
        }
      });

    // Adding functionality to sub-nav
    $siteNav.find('.menu-item-has-children').each(function(e) {
      $(this).append('<button class="sub-nav-button expand-contract" aria-hidden="true"><span class="icon plus-minus"></span></button>');
    });

    // Toggling sub-nav from the mobile menu
    $siteNav.on('click', '.menu-item-has-children > a', function(e) {
      e.preventDefault();
      var $listItem = $(this).parent('li');

      if (!breakpoint_nav) {
        if ($listItem.is('.-active')) {
          _contractSubNav($listItem);
        } else {
          _expandSubNav($listItem);
        }
      }
    });
  }

  function _expandSubNav($listItem) {
    // Close others that are open
    _contractSubNav($('.menu-item-has-children.-active').not($listItem));

    $listItem.addClass('-active');
    $listItem.find('.sub-nav-button').addClass('-active');
    $listItem.find('.sub-menu').velocity('slideDown', {
      easing: 'easeOutQuart', 
      duration: 250
    });
  }

  function _contractSubNav($listItem) {
    $listItem.removeClass('-active');
    $listItem.find('.sub-nav-button').removeClass('-active');
    $listItem.find('.sub-menu').velocity('slideUp', {
      easing: 'easeOutQuart', 
      duration: 250
    });
  }

  function _showMobileNav() {
    $('.menu-toggle .text').html('close');
    $body.addClass('menu-open');
    $('.menu-toggle, .site-nav').addClass('-active');
  }

  function _hideMobileNav() {
    $('.menu-toggle .text').html('menu');
    $body.removeClass('menu-open');
    $('.menu-toggle, .site-nav').removeClass('-active');
  }

  function _initLoadMore() {
    $document.on('click', '.load-more a', function(e) {
      e.preventDefault();
      var $load_more = $(this).closest('.load-more');
      var post_type = $load_more.attr('data-post-type') ? $load_more.attr('data-post-type') : 'news';
      var page = parseInt($load_more.attr('data-page-at'));
      var per_page = parseInt($load_more.attr('data-per-page'));
      var category = $load_more.attr('data-category');
      var more_container = $load_more.parents('section,main').find('.load-more-container');
      loadingTimer = setTimeout(function() { more_container.addClass('loading'); }, 500);

      $.ajax({
          url: wp_ajax_url,
          method: 'post',
          data: {
              action: 'load_more_posts',
              post_type: post_type,
              page: page+1,
              per_page: per_page,
              category: category
          },
          success: function(data) {
            var $data = $(data);
            if (loadingTimer) { clearTimeout(loadingTimer); }
            more_container.append($data).removeClass('loading');
            if (breakpoint_medium) {
              more_container.masonry('appended', $data, true);
            }
            $load_more.attr('data-page-at', page+1);

            // Hide load more if last page
            if ($load_more.attr('data-total-pages') <= page + 1) {
                $load_more.addClass('hide');
            }
          }
      });
    });
  }

  function _initAccordions() {
    if ($('.accordion').length) {
      var accordions = $('.accordion');
      for (var i=0;i<accordions.length;i++) {
        var $accordion = $(accordions[i]),
            $toggle = $accordion.find('.accordion-toggle'),
            $content = $accordion.find('.accordion-content');

        // Start contracted/expanded depending on screen size
        for (var b=0;b<=breakpoints.length;b++){
          if ($accordion.is('.expanded-'+breakpointClasses[b])) {
            if (!breakpoints[b]) {
              $content.hide();
            } else {
              _activateAccordion($accordion);
            }
          }
        }
      }

      $('.accordion').on('click', '.accordion-toggle', function(e) {
        var $accordion = $(this).parent('.accordion');
        if ($accordion.is('.-active')) {
          _collapseAccordion($accordion);
        } else {
          _expandAccordion($accordion);
        }
      });
    }
  }

  function _resetAccordions() {
    if ($('.accordion').length) {
      var accordions = $('.accordion');
      for (var i=0;i<accordions.length;i++) {
        var $accordion = $(accordions[i]);
        // Contracted/expand accordions depending on screen size
        // and their assigned '.expanded-xx' class
        for (var b=0;b<breakpoints.length;b++){
          if ($accordion.is('.expanded-'+breakpointClasses[b])) {
            if (!breakpoints[b]) {
              _hideAccordion($accordion);
            } else {
              _showAccordion($accordion);
            }
          }
        }
      }
    }
  }

  function _deactivateAccordion($accordion) {
    $accordion.removeClass('-active');
    $accordion.find('.expand-contract').removeClass('-active');        
  }

  function _activateAccordion($accordion) {
    $accordion.addClass('-active');
    $accordion.find('.expand-contract').addClass('-active');
  }

  function _collapseAccordion($accordion) {
    _deactivateAccordion($accordion);
    $accordion.find('.accordion-content').velocity('slideUp', {
      easing: 'easeOutQuart', 
      duration: 250
    });
  }

  function _expandAccordion($accordion) {
    _activateAccordion($accordion);
    $accordion.find('.accordion-content').velocity('slideDown', {
      easing: 'easeOutQuart', 
      duration: 250
    });
  }

  function _hideAccordion($accordion) {
    _deactivateAccordion($accordion);
    $accordion.find('.accordion-content').hide();
  }

  function _showAccordion($accordion) {
    _activateAccordion($accordion);
    $accordion.find('.accordion-content').show();
  }

  // Track ajax pages in Analytics
  function _trackPage() {
    if (typeof ga !== 'undefined') { ga('send', 'pageview', document.location.href); }
  }

  // Track events in Analytics
  function _trackEvent(category, action) {
    if (typeof ga !== 'undefined') { ga('send', 'event', category, action); }
  }

  // Disabling transitions on certain elements on resize
  function _disableTransitions() {
    $.each(transitionElements, function() {
      $(this).css('transition', 'none');
    });
  }

  function _enableTransitions() {
    $.each(transitionElements, function() {
      $(this).attr('style', '');
    });
  }

  // Called in quick succession as window is resized
  function _resize() {
    // Check breakpoint indicator in DOM ( :after { content } is controlled by CSS media queries )
    breakpointIndicatorString = window.getComputedStyle(
      document.querySelector('#breakpoint-indicator'), ':after'
    ).getPropertyValue('content')
    .replace(/['"]+/g, '');

    // Determine current breakpoint
    breakpoint_xl = breakpointIndicatorString === 'xl';
    breakpoint_lg = breakpointIndicatorString === 'lg' || breakpoint_xl;
    breakpoint_nav = breakpointIndicatorString === 'nav' || breakpoint_lg;
    breakpoint_md = breakpointIndicatorString === 'md' || breakpoint_nav;
    breakpoint_sm = breakpointIndicatorString === 'sm' || breakpoint_md;
    breakpoint_xs = breakpointIndicatorString === 'xs' || breakpoint_sm;

    breakpoints = [breakpoint_xs,breakpoint_sm,breakpoint_md,breakpoint_nav,breakpoint_lg,breakpoint_xl];

    // Reset inline styles for navigation for medium breakpoint
    if (breakpoint_nav && $('.site-nav .sub-menu')[0].hasAttribute('style')) {
      $('#site-nav .-active').removeClass('-active');
      $('#site-nav .sub-menu[style]').attr('style', '');
    }

    // Disable transitions when resizing  
    _disableTransitions();

    // Functions to run on resize end
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      // Re-enable transitions
      _enableTransitions();
      // Reset Acordions
      _resetAccordions();
    }, 250);
  }

  // Public functions
  return {
    init: _init,
    resize: _resize,
    scrollBody: function(section, duration, delay) {
      _scrollBody(section, duration, delay);
    }
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(FBSage.init);

// Zig-zag the mothership
jQuery(window).resize(FBSage.resize);
