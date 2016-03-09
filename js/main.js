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

jQuery(document).ready(function ($) {

    /* Responsive Navigation */

    var nav_primary = $('.nav-primary');
    var hamburger_link = $('.site-nav-hamburger-link');
    var search_link = $('.site-nav-search-link');
    var follow_link = $('.site-nav-follow-link');
    var nav_links = [hamburger_link, search_link, follow_link];

    for (var i = 0; i < nav_links.length; i++) {
        nav_links[i].on('click', function () {
            $(this).parent().toggleClass('is-active'); // TODO: Эффект клика по блоку (полупрозрачный фон)
            $(this).parent().siblings().removeClass('is-active');
        });
    }

    function hide_nav_primary() {
        if (nav_primary.is(':visible')) {
            nav_primary.slideToggle(300);
        }
    }

    hamburger_link.click(function () {
        $(this).parent().next('.nav-primary').slideToggle(300);
    });

    search_link.click(function () {
        hide_nav_primary();
    });

    follow_link.click(function () {
        hide_nav_primary();
    });


    /* Waves Click Effect */

    $(window).resize(function () {
        if ($(window).width() <= 960) {
            for (var i = 0; i < nav_links.length; i++) {
                Waves.attach(nav_links[i].selector);
                var config = {
                    duration: 300,
                    delay: 100
                };
                Waves.init(config);
            }
        }
    });
});