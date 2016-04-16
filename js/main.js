/*!
 * Theia Sticky Sidebar v1.2.2
 * https://github.com/WeCodePixels/theia-sticky-sidebar
 *
 * Glues your website's sidebars, making them permanently visible while scrolling.
 *
 * Copyright 2013-2014 WeCodePixels and other contributors
 * Released under the MIT license
 */
!function (i) {
    i.fn.theiaStickySidebar = function (t) {
        var o = {
            containerSelector: "",
            additionalMarginTop: 0,
            additionalMarginBottom: 0,
            updateSidebarHeight: !0,
            minWidth: 0
        };
        t = i.extend(o, t), t.additionalMarginTop = parseInt(t.additionalMarginTop) || 0, t.additionalMarginBottom = parseInt(t.additionalMarginBottom) || 0, i("head").append(i('<style>.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>')), this.each(function () {
            function o() {
                e.fixedScrollTop = 0, e.sidebar.css({"min-height": "1px"}), e.stickySidebar.css({
                    position: "static",
                    width: ""
                })
            }

            function a(t) {
                var o = t.height();
                return t.children().each(function () {
                    o = Math.max(o, i(this).height())
                }), o
            }

            var e = {};
            e.sidebar = i(this), e.options = t || {}, e.container = i(e.options.containerSelector), 0 == e.container.size() && (e.container = e.sidebar.parent()), e.sidebar.parents().css("-webkit-transform", "none"), e.sidebar.css({
                position: "relative",
                overflow: "visible",
                "-webkit-box-sizing": "border-box",
                "-moz-box-sizing": "border-box",
                "box-sizing": "border-box"
            }), e.stickySidebar = e.sidebar.find(".theiaStickySidebar"), 0 == e.stickySidebar.length && (e.sidebar.find("script").remove(), e.stickySidebar = i("<div>").addClass("theiaStickySidebar").append(e.sidebar.children()), e.sidebar.append(e.stickySidebar)), e.marginTop = parseInt(e.sidebar.css("margin-top")), e.marginBottom = parseInt(e.sidebar.css("margin-bottom")), e.paddingTop = parseInt(e.sidebar.css("padding-top")), e.paddingBottom = parseInt(e.sidebar.css("padding-bottom"));
            var d = e.stickySidebar.offset().top, r = e.stickySidebar.outerHeight();
            e.stickySidebar.css("padding-top", 1), e.stickySidebar.css("padding-bottom", 1), d -= e.stickySidebar.offset().top, r = e.stickySidebar.outerHeight() - r - d, 0 == d ? (e.stickySidebar.css("padding-top", 0), e.stickySidebarPaddingTop = 0) : e.stickySidebarPaddingTop = 1, 0 == r ? (e.stickySidebar.css("padding-bottom", 0), e.stickySidebarPaddingBottom = 0) : e.stickySidebarPaddingBottom = 1, e.previousScrollTop = null, e.fixedScrollTop = 0, o(), e.onScroll = function (e) {
                if (e.stickySidebar.is(":visible")) {
                    if (i("body").width() < e.options.minWidth)return void o();
                    if (e.sidebar.outerWidth(!0) + 50 > e.container.width())return void o();
                    var d = i(document).scrollTop(), r = "static";
                    if (d >= e.container.offset().top + (e.paddingTop + e.marginTop - e.options.additionalMarginTop)) {
                        var s, n = e.paddingTop + e.marginTop + t.additionalMarginTop, c = e.paddingBottom + e.marginBottom + t.additionalMarginBottom, p = e.container.offset().top, b = e.container.offset().top + a(e.container), g = 0 + t.additionalMarginTop, l = e.stickySidebar.outerHeight() + n + c < i(window).height();
                        s = l ? g + e.stickySidebar.outerHeight() : i(window).height() - e.marginBottom - e.paddingBottom - t.additionalMarginBottom;
                        var f = p - d + e.paddingTop + e.marginTop, S = b - d - e.paddingBottom - e.marginBottom, h = e.stickySidebar.offset().top - d, m = e.previousScrollTop - d;
                        "fixed" == e.stickySidebar.css("position") && (h += m), h = m > 0 ? Math.min(h, g) : Math.max(h, s - e.stickySidebar.outerHeight()), h = Math.max(h, f), h = Math.min(h, S - e.stickySidebar.outerHeight());
                        var y = e.container.height() == e.stickySidebar.outerHeight();
                        r = (y || h != g) && (y || h != s - e.stickySidebar.outerHeight()) ? d + h - e.sidebar.offset().top - e.paddingTop <= t.additionalMarginTop ? "static" : "absolute" : "fixed"
                    }
                    if ("fixed" == r)e.stickySidebar.css({
                        position: "fixed",
                        width: e.sidebar.width(),
                        top: h,
                        left: e.sidebar.offset().left + parseInt(e.sidebar.css("padding-left")) + parseInt(e.sidebar.css("border-left"))
                    }); else if ("absolute" == r) {
                        var k = {};
                        "absolute" != e.stickySidebar.css("position") && (k.position = "absolute", k.top = d + h - e.sidebar.offset().top - e.stickySidebarPaddingTop - e.stickySidebarPaddingBottom), k.width = e.sidebar.width(), k.left = "", e.stickySidebar.css(k)
                    } else"static" == r && o();
                    "static" != r && 1 == e.options.updateSidebarHeight && e.sidebar.css({"min-height": e.stickySidebar.outerHeight() + e.stickySidebar.offset().top - e.sidebar.offset().top + e.paddingBottom}), e.previousScrollTop = d
                }
            }, e.onScroll(e), i(document).scroll(function (i) {
                return function () {
                    i.onScroll(i)
                }
            }(e)), i(window).resize(function (i) {
                return function () {
                    i.stickySidebar.css({position: "static"}), i.onScroll(i)
                }
            }(e))
        })
    }
}(jQuery);


/*! bigSlide - v0.10.0 - 2016-03-30
 * http://ascott1.github.io/bigSlide.js/
 * Copyright (c) 2016 Adam D. Scott; Licensed MIT */
!function (a) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof exports ? module.exports = a(require("jquery")) : a(jQuery)
}(function (a) {
    "use strict";
    function b(a, b) {
        for (var c, d = a.split(";"), e = b.split(" "), f = "", g = 0, h = d.length; h > g; g++) {
            c = !0;
            for (var i = 0, j = e.length; j > i; i++)("" === d[g] || -1 !== d[g].indexOf(e[i])) && (c = !1);
            c && (f += d[g] + "; ")
        }
        return f
    }

    a.fn.bigSlide = function (c) {
        var d = this, e = a.extend({
            menu: "#menu",
            push: ".push",
            shrink: ".shrink",
            side: "left",
            menuWidth: "15.625em",
            speed: "300",
            state: "closed",
            activeBtn: "active",
            easyClose: !1,
            saveState: !1,
            beforeOpen: function () {
            },
            afterOpen: function () {
            },
            beforeClose: function () {
            },
            afterClose: function () {
            }
        }, c), f = "transition -o-transition -ms-transition -moz-transitions webkit-transition " + e.side, g = {
            menuCSSDictionary: f + " position top bottom height width",
            pushCSSDictionary: f,
            state: e.state
        }, h = {
            init: function () {
                i.init()
            }, _destroy: function () {
                return i._destroy(), delete d.bigSlideAPI, d
            }, changeState: function () {
                "closed" === g.state ? g.state = "open" : g.state = "closed"
            }, setState: function (a) {
                g.state = a
            }, getState: function () {
                return g.state
            }
        }, i = {
            init: function () {
                this.$menu = a(e.menu), this.$push = a(e.push), this.$shrink = a(e.shrink), this.width = e.menuWidth;
                var b = {
                    position: "fixed",
                    top: "0",
                    bottom: "0",
                    height: "100%"
                }, c = {
                    "-webkit-transition": e.side + " " + e.speed + "ms ease-in-out",
                    "-moz-transition": e.side + " " + e.speed + "ms ease-in-out",
                    "-ms-transition": e.side + " " + e.speed + "ms ease-in-out",
                    "-o-transition": e.side + " " + e.speed + "ms ease-in-out",
                    transition: e.side + " " + e.speed + "ms ease-in-out"
                }, f = {
                    "-webkit-transition": "all " + e.speed + "ms ease-in-out",
                    "-moz-transition": "all " + e.speed + "ms ease-in-out",
                    "-ms-transition": "all " + e.speed + "ms ease-in-out",
                    "-o-transition": "all " + e.speed + "ms ease-in-out",
                    transition: "all " + e.speed + "ms ease-in-out"
                }, g = !1;
                b[e.side] = "-" + e.menuWidth, b.width = e.menuWidth;
                var j = "closed";
                e.saveState ? (j = localStorage.getItem("bigSlide-savedState"), j || (j = e.state)) : j = e.state, h.setState(j), this.$menu.css(b), "closed" === j ? this.$push.css(e.side, "0") : "open" === j && (this.$menu.css(e.side, "0"), this.$push.css(e.side, this.width), this.$shrink.css("width", "100%").css("width", "-=" + this.$menu.width()), d.addClass(e.activeBtn));
                var k = this;
                d.on("click.bigSlide touchstart.bigSlide", function (a) {
                    g || (k.$menu.css(c), k.$push.css(c), k.$shrink.css(f), g = !0), a.preventDefault(), "open" === h.getState() ? i.toggleClose() : i.toggleOpen()
                }), e.easyClose && a(document).on("click.bigSlide", function (b) {
                    a(b.target).parents().andSelf().is(d) || a(b.target).closest(e.menu).length || "open" !== h.getState() || i.toggleClose()
                })
            }, _destroy: function () {
                this.$menu.each(function () {
                    var c = a(this);
                    c.attr("style", b(c.attr("style"), g.menuCSSDictionary).trim())
                }), this.$push.each(function () {
                    var c = a(this);
                    c.attr("style", b(c.attr("style"), g.pushCSSDictionary).trim())
                }), this.$shrink.each(function () {
                    var c = a(this);
                    c.attr("style", b(c.attr("style"), g.pushCSSDictionary).trim())
                }), d.removeClass(e.activeBtn).off("click.bigSlide touchstart.bigSlide"), this.$menu = null, this.$push = null, this.$shrink = null, localStorage.removeItem("bigSlide-savedState")
            }, toggleOpen: function () {
                e.beforeOpen(), h.changeState(), this.$menu.css(e.side, "0"), this.$push.css(e.side, this.width), this.$shrink.css("width", "100%").css("width", "-=" + this.$menu.width()), d.addClass(e.activeBtn), e.afterOpen(), e.saveState && localStorage.setItem("bigSlide-savedState", "open")
            }, toggleClose: function () {
                e.beforeClose(), h.changeState(), this.$menu.css(e.side, "-" + this.width), this.$push.css(e.side, "0"), this.$shrink.css("width", "100%"), d.removeClass(e.activeBtn), e.afterClose(), e.saveState && localStorage.setItem("bigSlide-savedState", "closed")
            }
        };
        return h.init(), this.bigSlideAPI = {settings: e, model: g, controller: h, view: i, destroy: h._destroy}, this
    }
});


jQuery(document).ready(function ($) {
    /* Responsive navigation
     ---------------------------------------------------------------------------------------------------- */
    var burger = $('a.burger');
    var navPrimary = $('.nav-primary');
    var toggleLink = $('.toggle-link');
    var subMenus = navPrimary.find('.sub-menu');
    var menusWithSub = navPrimary.children('ul').children('.menu-item-has-children').children('a');
    var menuClose = $('.menu-close-link');

    var bigSlideOptions = {
        menu: '.nav-primary',
        menuWidth: '100vw',
        beforeOpen: bodyOverlay,
        afterClose: bodyOverlay,
        speed: 500
    };

    //menusWithSub.on('click', function (e) {
    //    e.preventDefault();
    //});


    toggleLink.on('click', function (e) {
        e.preventDefault();
    });

    menuClose.on('click', function () {
        burger.click();
    });

    menusWithSub.on('click', function (e) {
        if ($(window).width() <= 992) {
            e.preventDefault();
            $(this).parent().toggleClass('expanded');
        }
    });

    function bodyOverlay() {
        $('body').toggleClass('overlay');
    }

    function doNav() {
        if ($(window).width() <= 992) {
            burger.bigSlide(bigSlideOptions);

            subMenus.each(function () {
                if (!$(this).children('.sub-menu-parent-link').length != 0) {
                    var el = $(this).prev('a').clone();
                    var elSpan = el.children('span');

                    el.addClass('sub-menu-parent-link');
                    elSpan.html(' Go to ' + elSpan.html());
                    el.prependTo($(this));
                }
            });
        }
        else if ($(window).width() >= 992) {
            navPrimary.removeAttr('style'); // TODO: bigSlide добавляет inline-css, возможно что-то придумать
            subMenus.children('.sub-menu-parent-link').remove();
            if (burger.hasClass('active')) {
                burger.removeClass('active');
            }
        }
    }

    doNav();

    $(window).resize(function () {
        doNav();
    });


    /* Featured area scrolling
     ---------------------------------------------------------------------------------------------------- */
    var featuredStoriesList = $('.featured-stories-list');
    var arrowPrev = $('.featured-stories-arrow.arrow-prev');
    var arrowNext = $('.featured-stories-arrow.arrow-next');
    var entryWidth = $('.featured-story:first').width();

    $(window).resize(function () {
        entryWidth = $('.featured-story:first').width();
    });

    function scrollingAdjustArrows() {
        var leftPos = featuredStoriesList.scrollLeft();

        if ($(window).width() > 980) {
            return;
        }

        if (leftPos > 0) {
            arrowPrev.show();
        }
        else if (leftPos == 0) {
            arrowPrev.hide();
        }
        if (leftPos + featuredStoriesList.innerWidth() >= featuredStoriesList[0].scrollWidth) {
            arrowNext.hide();
        }
        else {
            arrowNext.show();
        }
    }

    $('.featured-stories').hover(scrollingAdjustArrows, function () {
            arrowPrev.hide();
            arrowNext.hide();
        }
    );

    featuredStoriesList.on('scroll', scrollingAdjustArrows);

    arrowPrev.on('click', function (e) {
        e.preventDefault();
        var leftPos = featuredStoriesList.scrollLeft();
        featuredStoriesList.animate({scrollLeft: leftPos - entryWidth * 2}, 800);
    });

    arrowNext.on('click', function (e) {
        e.preventDefault();
        var leftPos = featuredStoriesList.scrollLeft();
        featuredStoriesList.animate({scrollLeft: leftPos + entryWidth * 2}, 800);
    });


    /* Ajax load more
     ---------------------------------------------------------------------------------------------------- */
    var loadMore = $('.load-more');
    var loadMoreIcon = loadMore.children('.icon');
    var loadMoreButton = $('.load-more-button');
    var page = 2;
    var loading = false;

    loadMoreButton.on('click', function (e) {
        e.preventDefault();

        if (!loading) {
            loading = true;
            var data = {
                action: localizedLoadMore.action,
                nonce: localizedLoadMore.nonce,
                page: page,
                query: localizedLoadMore.query
            };
            var options = {
                type: 'POST',
                url: 'http://localhost:3000/wp-admin/admin-ajax.php',  // TODO: localized.ajaxUrl on production
                data: data,
                beforeSend: function () {
                    loadMoreButton.hide();
                    loadMoreIcon.css('display', 'inline-block');
                },
                success: function (res) {
                    if (res.success) {
                        var el = $(res.data.html).hide();
                        loadMore.before(el);
                        el.fadeIn(1000);
                        page++;
                        loading = false;
                        if (res.data.end) {
                            loadMore.hide();
                        }
                    }
                    else {
                        //console.log(res);
                    }
                },
                error: function (xhr) {
                    //console.log(xhr);
                },
                complete: function (res) {
                    loadMoreIcon.hide();
                    loadMoreButton.blur().show();
                }
            }

            $.ajax(options);
        }
    });


    /* Sticky sidebar
     ---------------------------------------------------------------------------------------------------- */
    //$('.sidebar-primary').theiaStickySidebar({
    //    //additionalMarginTop: 30
    //});


    /* Scroll to top
     ---------------------------------------------------------------------------------------------------- */
    var offset = 100;
    var speed = 1000;
    var duration = 500;

    $(window).scroll(function () {
        if ($(this).scrollTop() < offset) {
            $('.to-top').fadeOut(duration);
        } else {
            $('.to-top').fadeIn(duration);
        }
    });

    $('.to-top').on('click', function () {
        $('html, body').animate({scrollTop: 0}, speed);
        return false;
    });
});

(function ($) {
    //* Facebook widget fix
    if ($('.textwidget').has('.fb-page').css('text-align', 'center'));


    //* Local storage fonts load

    // Temporary solution! On production replace with localized var from WordPress
    var templateDir = 'wp-content/themes/cutedecision';
    //var templateDir = localized.templateDir;

    // Функция добавления шрифта на страницу
    function addFont() {
        var style = document.createElement('style');
        style.rel = 'stylesheet';
        document.head.appendChild(style);
        style.textContent = localStorage.cutedecision;
    }

    try {
        if (localStorage.cutedecision) {
            // CSS со шрифтом уже есть в localStorage, сразу вызываем функцию добавления
            addFont();
        } else {
            // Нужно асинхронно получить CSS со шрифтом
            var request = new XMLHttpRequest();
            request.open('GET', templateDir + '/fonts/fonts.css', true);

            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    // Запрос удался, пишем в localStorage
                    localStorage.cutedecision = request.responseText;
                    // Вызываем функцию добавления шрифта на страницу
                    addFont();
                }
            };

            request.send();
        }
    } catch (ex) {
        // Возможно, синхронная загрузка шрифта для браузеров без localStorage
        console.log(ex);
    }

})(jQuery);