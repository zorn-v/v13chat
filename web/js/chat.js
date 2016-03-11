jQuery(function ($) {
    var $smileBox = $('#smilebox');
    var $clearControls = $('.chat-controls .control-to, .chat-controls .control-message');
    var $to = $('.chat-controls .control-to');
    var $message = $('.chat-controls .control-message');

    $message.focus();

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
        $message.val($message.val() + $(this).data('smile-text'));
    });

    /* ajax */
    var $ajaxBlocks = $('[data-ajax-block]');
    function updateBlocks() {
        $ajaxBlocks.each(function () {
            var $block = $(this);
            $.ajax($block.data('ajax-block'), {
                method: 'POST',
                success: function (data) {
                    if (data.indexOf('login') > 0) {
                        location.reload();
                    } else {
                        $block.html(data);
                    }
                },
                error: function (xhr) {
                    $block.html(xhr.responseText);
                },
            });
        });
    }
    $ajaxBlocks
        .delegate('[data-nick]', 'click', function () {
            $to.val($(this).data('nick'));
        })
        .delegate('[data-ajax-send]', 'click', function () {
            $.post($(this).data('ajax-send'), updateBlocks);
        });

    $('form[name="form"]').submit(function (e) {
        e.preventDefault();
        $.ajax(this.action, {
            data: $(this).serialize(),
            method: 'POST',
            success: function () {
                $clearControls.val('');
                updateBlocks();
            },
            error: function () {
                location.reload();
            },
        });
    });

    setInterval(updateBlocks, 5000);
    updateBlocks();
});
