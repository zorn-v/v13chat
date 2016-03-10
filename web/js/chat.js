jQuery(function ($) {
    var $smileBox = $('#smilebox');
    var $clearControls = $('.chat-controls .control-to, .chat-controls .control-message');
    var $message = $('.chat-controls .control-message');

    $('body').click(function () {
        $smileBox.hide();
    });
    $('.chat-controls .control-smiles-button').click(function (e) {
        e.stopPropagation();
        $smileBox.toggle();
    });
    $('.chat-controls .control-color').change(function () {
        this.style.backgroundColor = this.value;
    }).change();
    $('.chat-controls .control-clear').click(function () {
        $clearControls.val('');
    });
    $('#smilebox img').click(function (e) {
        e.stopPropagation();
        $message.val($message.val() + ' ' + $(this).data('smile-text') + ' ');
    });
});