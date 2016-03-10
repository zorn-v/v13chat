jQuery(function ($) {
    var $smileBox = $('#smilebox');
    var $clearControls = $('.chat-controls .control-to, .chat-controls .control-message');
    $('.chat-controls .control-smiles-button').click(function () {
        $smileBox.toggle();
    });
    $('.chat-controls .control-color').change(function () {
        this.style.backgroundColor = this.value;
    }).change();
    $('.chat-controls .control-clear').click(function () {
        $clearControls.val('');
    })
});
