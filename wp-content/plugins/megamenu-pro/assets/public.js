/*jslint browser: true, white: true */
/*global console,jQuery,megamenu,window,navigator*/

(function($) {
    "use strict";

    $(function() {
        $('body').on('edd_cart_item_added', function(event, data) {
            $('.mega-menu-edd-cart-total').html(data.total);
            $('.mega-menu-edd-cart-count').html(data.cart_quantity);
        });
    });

})(jQuery);

/**
 * Max Mega Menu Searchbox jQuery plugin
 */
(function($) {
    "use strict";

    $.maxmegamenu_searchbox = function(menu, options) {

        var plugin = this;
        var $menu = $(menu);
        var $wrap = $menu.parent();
        var breakpoint = $menu.attr('data-breakpoint');

        var is_mobile = function() {
            return $(window).width() <= breakpoint;
        };

        plugin.init_replacements_search = function() {

            $(".mega-search", $menu).children('input[type=text]').val("");


            if ( is_mobile() ) {
                $(".mega-search.expand-to-left .search-icon", $menu).on('click', function(e) {
                    $(this).parents(".mega-search").submit();
                });
            } else {
                $(".mega-search input[type=text]", $menu).on('focus', function(e) {
                    var form = $(this).parents('.mega-search');

                    if (! form.hasClass('static') && form.hasClass('mega-search-closed') && $menu.hasClass('mega-keyboard-navigation') ) {
                        $(this).attr('placeholder', $(this).attr('data-placeholder'));
                        form.removeClass('mega-search-closed');
                        form.addClass('mega-search-open');
                    }
                });

                $(".mega-search input[type=text]", $menu).on('blur', function(e) {
                    var form = $(this).parents('.mega-search');

                    if ( ! form.hasClass('static') && form.hasClass('mega-search-open') && $menu.hasClass('mega-keyboard-navigation') ) {
                        $(this).attr('placeholder', '');
                        form.removeClass('mega-search-open');
                        form.addClass('mega-search-closed');
                    }
                });

                $(".mega-search .search-icon", $menu).on('click', function(e) {

                    var input = $(this).parents('.mega-search').children('input[type=text]');
                    var form = $(this).parents('.mega-search');

                    if (form.hasClass('static') ) {
                        form.submit();
                    } else if (form.hasClass('mega-search-closed')) {
                        input.focus();
                        input.attr('placeholder', input.attr('data-placeholder'));
                        form.removeClass('mega-search-closed');
                        form.addClass('mega-search-open');
                    } else if ( input.val() == '' ) {
                        form.addClass('mega-search-closed');
                        form.removeClass('mega-search-open');
                        input.attr('placeholder', '');
                    } else {
                        form.submit();
                    }
                });
            }

        };


        plugin.init_toggle_search = function() {

            $(".mega-menu-toggle .mega-search", $wrap).children('input[type=text]').val("");

            $(".mega-menu-toggle .mega-search .search-icon", $wrap).on('click', function(e) {

                var input = $(this).parents('.mega-search').children('input[type=text]');
                var form = $(this).parents('.mega-search');

                if (form.hasClass('static') ) {
                    form.submit();
                } else if (form.hasClass('mega-search-closed')) {
                    input.focus();
                    input.attr('placeholder', input.attr('data-placeholder'));
                    form.removeClass('mega-search-closed');
                    form.addClass('mega-search-open');
                } else if ( input.val() == '' ) {
                    form.addClass('mega-search-closed');
                    form.removeClass('mega-search-open');
                    input.attr('placeholder', '');
                } else {
                    form.submit();
                }
            });

        };

        plugin.init_replacements_search();
        plugin.init_toggle_search();

    };

    $.fn.maxmegamenu_searchbox = function(options) {

        return this.each(function() {
            if (undefined === $(this).data('maxmegamenu_searchbox')) {
                var plugin = new $.maxmegamenu_searchbox(this, options);
                $(this).data('maxmegamenu_searchbox', plugin);
            }
        });

    };

    $(function() {
        $(".mega-menu").maxmegamenu_searchbox();
    });

})(jQuery);

/**
 * Max Mega Menu Sticky jQuery Plugin
 */
(function($) {

    "use strict";

    $.maxmegamenu_sticky = function(menu, options) {
        var plugin = this;
        var $menu = $(menu);
        var $wrap = $menu.parent();
        var breakpoint = $menu.attr('data-breakpoint');
        var sticky_on_mobile = $menu.attr('data-sticky-mobile');
        var sticky_offset = $menu.attr('data-sticky-offset');
        var sticky_menu_offset_top;
        var sticky_menu_offset_left;
        var sticky_menu_width;
        var sticky_menu_width_round_up;
        var sticky_menu_height;
        var is_stuck = false;
        var admin_bar_height = 0;

        var sticky_enabled = function() {
            return $(window).width() > breakpoint || sticky_on_mobile === 'true';
        };

        var calculate_menu_position = function() {
            sticky_menu_offset_top = $wrap.offset().top;

            if ($('body.admin-bar').length && $(window).width() > breakpoint) {
                admin_bar_height = $('#wpadminbar').height();
                sticky_menu_offset_top = sticky_menu_offset_top - admin_bar_height;
            }

            sticky_menu_offset_top = sticky_menu_offset_top - sticky_offset;
            sticky_menu_offset_left = $wrap.offset().left;
            sticky_menu_width = window.getComputedStyle($wrap[0]).width;
            sticky_menu_width_round_up = Math.ceil(parseFloat(sticky_menu_width));
            sticky_menu_height = $wrap.height();
        };

        plugin.stick_menu = function() {
            is_stuck = true;

            var total_offset = parseInt(admin_bar_height, 10) + parseInt(sticky_offset, 10);

            var placeholder = $("<div />").css({
                'height' : sticky_menu_height + 'px',
                'position' :'static'
            });

            $wrap.addClass('mega-sticky').wrap(placeholder).css({
                'margin-top' : total_offset + 'px'
            });

            $menu.css({
                'margin-left' : sticky_menu_offset_left + 'px',
                'max-width' : sticky_menu_width_round_up + 'px'
            });
        };

        plugin.unstick_menu = function() {
            is_stuck = false;

            $wrap.removeClass('mega-sticky').unwrap().css({
                'margin-top' : ''
            });

            $menu.css({
                'margin-left' : '',
                'max-width' : ''
            });
        };

        var mega_sticky_on_scroll = function(){
            if ( ! sticky_enabled() ) {
                return;
            }

            var scroll_top = $(window).scrollTop();

            if (scroll_top > sticky_menu_offset_top) {
                if (!is_stuck) {
                    plugin.stick_menu();
                }
            } else {
                if (is_stuck) {
                    plugin.unstick_menu();
                }
            }
        };

        var mega_sticky_on_resize = function() {
            if ( sticky_enabled() ) {
                if (is_stuck) {
                    plugin.unstick_menu();
                    calculate_menu_position();
                    plugin.stick_menu();
                } else {
                    calculate_menu_position();
                    mega_sticky_on_scroll();
                }
            } else {
                if (is_stuck) {
                    plugin.unstick_menu();
                }
            }
        };

        plugin.init = function() {
            calculate_menu_position();
            mega_sticky_on_scroll();

            $(window).scroll(function() {
                 mega_sticky_on_scroll();
            });

            $(window).resize(function() {
                mega_sticky_on_resize();
            });
        };

        plugin.init();
    };

    $.fn.maxmegamenu_sticky = function(options) {

        return this.each(function() {
            if (undefined === $(this).data('maxmegamenu_sticky')) {
                var plugin = new $.maxmegamenu_sticky(this, options);
                $(this).data('maxmegamenu_sticky', plugin);
            }
        });

    };

    $(function() {
        $(".mega-menu[data-sticky]").maxmegamenu_sticky();
    });

})(jQuery);