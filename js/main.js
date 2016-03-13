jQuery(document).ready(function ($) {

    //* Responsive Navigation
    var navPrimary = $('.nav-primary');
    var toggleLink = $('.toggle-link');
    var hamburgerLink = $('.hamburger-link');

    toggleLink.on('click', function () {
        $(this).parent().toggleClass('is-active');
        $(this).parent().siblings().removeClass('is-active');
    });

    hamburgerLink.on('click', function () {
        navPrimary.slideToggle(300);
    });

    $('.toggle-search, .toggle-follow').children('.toggle-link').on('click', function () {
        if (navPrimary.is(':visible')) {
            navPrimary.slideToggle(300);
        }
    });


    //* Featured area scrolling
    var featuredStoriesList = $('.featured-stories-list');
    var arrowPrev = $('.featured-stories-arrow.arrow-prev');
    var arrowNext = $('.featured-stories-arrow.arrow-next');
    var entryWidth = $('.featured-story:first').width();

    function scrollingAdjustArrows() {
        var leftPos = featuredStoriesList.scrollLeft();
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

    arrowPrev.on('click', function () {
        var leftPos = featuredStoriesList.scrollLeft();
        featuredStoriesList.animate({scrollLeft: leftPos - entryWidth * 2}, 800);
    });

    arrowNext.on('click', function () {
        var leftPos = featuredStoriesList.scrollLeft();
        featuredStoriesList.animate({scrollLeft: leftPos + entryWidth * 2}, 800);
    });
});

// TODO: Функция подгрузки шрифтов в localStorage: Добавить JSDOC, перевести комментарии, дописать catch(ex)
(function () {

    // Variable comes from functions.php
    //var template_dir = $wp_localized.template_dir;

    // Temporary solution!
    var template_dir = 'wp-content/themes/cutedecision';

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
            request.open('GET', template_dir + '/fonts/fonts.css', true);

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
}());

(function ($) {
    //..
})(jQuery);