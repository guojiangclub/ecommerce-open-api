/**
 * jQuery.loader Plugin
 * @author Stéphan Zych <info@monkeymonk.be>
 * @copyriht 2010-2011 Stéphan Zych <info@monkeymonk.be>
 * @license New BSD License <http://creativecommons.org/licenses/BSD/>
 */

(function ($) {
    "use strict";


    var render = function (template, data) {
        for (var k in data) {
            template = template.replace(new RegExp('{' + k + '}', 'g'), data[k]);
        }
        
        return template;
    }; // render

    var Loader = function () {
        var defaults = {
            className: 'loader',

            tpl: '<div class="{className} hide"><div class="{className}-load"></div><div class="{className}-overlay"></div></div>',

            delay: 200,
            loader: true,
            overlay: true,

            onHide: function () {},
            onShow: function () {}
        }; // defaults

        var init = function (oParams) {
            return this.each(function () {
                defaults = $.extend({}, defaults, oParams);

                var $self = $(this), $loader;

                $self.append(render(defaults.tpl, defaults));
                $loader = $('> .' + defaults.className, $self);

                if (defaults.overlay) {
                    $loader.addClass('overlay');
                }

                if (defaults.loader) {
                    $('.' + defaults.className + '-load', $loader)
                    .on('click', function () {
                        $self.loader('hide', defaults.onHide);
                    });
                }
            });
        }; // init

        var show = function (oParams) {
            defaults = $.extend({}, defaults, oParams);

            var self = this, $self = $(self), $loader = $('> .' + defaults.className + ':first', $self);

            if (!$loader.length) {
                init.call(self, oParams);
                $loader = $('> .' + defaults.className + ':first', $self);
            }

            $loader.fadeIn(defaults.delay, function () {
                $loader.removeClass('hide');

                defaults.onShow(defaults);
            });
        }; // show

        var hide = function (onHide) {
            defaults = $.extend({}, defaults, {onHide: onHide});

            var self = this, $self = $(self), $loader = $('> .' + defaults.className + ':first', $self);

            if ($loader.length) {
                $loader.fadeOut(defaults.delay, function () {
                    $loader.addClass('hide');

                    defaults.onHide(defaults);
                });
            }
        }; // hide
        
        return {
            init: init,
            show: show,
            hide: hide
        };
    }; // Loader


    $.fn.loader = function (options) {
        if (!$.data(this, 'loader')) {
            $.data(this, 'loader', new Loader(this, options));
        }

        var plugin = $.data(this, 'loader');

        if (plugin[options]) {
            return plugin[options].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof options === 'object' || !options) {
            return plugin.init.apply(this, arguments);
        } else {
            $.error('Method "' + arguments[0] + '" does not exist in $.loader plugin!');
        }
    }; // $.fn.loader

} (jQuery)); // jQuery.loader() by Stéphan Zych (monkeymonk.be)