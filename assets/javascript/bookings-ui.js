;
(function ($) {    
    //jQuery Plugins
    $.fn.rotate = function (degrees) {
        $(this).css({
            'transform': "rotate(" + degrees + "deg)"
        });
    };
    
    //Behavior



})(jQuery);
