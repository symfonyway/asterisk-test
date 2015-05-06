(function() {
    var $statusBlock = $('.js-status-block');


    function validateForm($form) {
        var regexNanpFormat = /([\+]?1[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[0-9]{4})$/,
            $field = $form.find('#form_phone_phone'),
            value = $field.val();
        ;

        if (!value.length) {
            $statusBlock.text('Phone number is empty.').show();

            return false;
        }

        if (!value.match(regexNanpFormat)) {
            $statusBlock.text('Value "' + value + '" is not a valid phone number.').show();

            return false;
        }

        $statusBlock.hide().text('');

        return true;
    }

    $('.js-phone-form').on('submit', function() {
        var $this = $(this),
            url = $this.attr('action') ? $this.attr('action') : location.href
        ;

        if (!validateForm($this)) {
            return false;
        }

        $
            .ajax({
                url: url,
                data: $this.serialize()
            })
            .success(function(data) {
                if (data.hasOwnProperty('message')) {
                    $statusBlock.text(data.message);
                }

                if (data.hasOwnProperty('status') && data.status == 'ok') {
                    $statusBlock.css('background-color', '#green');
                } else {
                    $statusBlock.css('background-color', '#red');
                }

                $statusBlock.show();
            })
            .error(function(xhr, status) {
                if (status && status.length) {
                    $statusBlock.text(status);
                } else {
                    $statusBlock.text('Unknown error.');
                }

                $statusBlock.css('background-color', '#red').show();
            })
        ;

        return false;
    });
})();