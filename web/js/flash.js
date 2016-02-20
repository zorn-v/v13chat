jQuery(function($){
    var $flash = $('.flash-message');
    $('.flash-close > div, .overlay').click(function(){
        $flash.removeClass('active');
    });
});