(function() {
    var $successBlock = $('.alert-success.js-status-block'),
        $errorBlock = $('.alert-danger.js-status-block')
    ;

    function showSuccessMessage(text) {
        $errorBlock.hide();
        $successBlock.text(text).show()
    }

    function showErrorMessage(text) {
        $successBlock.hide();
        $errorBlock.text(text).show()
    }

    function hideMessage() {
        $successBlock.hide();
        $errorBlock.hide();
    }

    function validateForm($form) {
        var regexNanpFormat = /([\+]?1[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[0-9]{4})$/,
            $field = $form.find('#form_phone_phone'),
            value = $field.val();
        ;

        if (!value.length) {
            showErrorMessage('Phone number is empty.');

            return false;
        }

        if (!value.match(regexNanpFormat)) {
            showErrorMessage('Value "' + value + '" is not a valid phone number.');

            return false;
        }

        hideMessage();

        return true;
    }

    $('.js-phone-form').on('submit', function() {
        var $this = $(this),
            $button = $this.find('[type=submit]')
        ;

        if (!validateForm($this)) {
            return false;
        }

        $button.attr('disabled', 'disabled');
        hideMessage();

        $
            .ajax({
                method: $this.attr('method'),
                url: location.href,
                data: $this.serialize()
            })
            .success(function(data) {
                if (data.hasOwnProperty('status') && data.status == 'ok') {
                    if (data.hasOwnProperty('message') && data.message.length) {
                        showSuccessMessage(data.message);
                    }
                } else {
                    if (data.hasOwnProperty('message') && data.message.length) {
                        showErrorMessage(data.message);
                    }
                }
            })
            .error(function(xhr) {
                showErrorMessage(xhr.statusText);
            })
            .complete(function() {
                $button.removeAttr('disabled');
            })
        ;

        return false;
    });
})();