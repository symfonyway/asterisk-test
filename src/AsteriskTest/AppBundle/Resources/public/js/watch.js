(function() {
    console.log('INIT');
    var $container = $('.js-listen-container'),
        $button = $container.find('.js-listen-button'),
        $placeholder = $container.find('.js-listen-placeholder'),
        $result = $container.find('.js-listen-result'),
        $number = $result.find('.js-listen-number'),
        $validation = $result.find('.js-listen-validation'),
        $error = $container.find('.js-listen-error')
    ;
    function runListenScript() {
        console.log('runListenScript');
        $button.add($result).add($error).hide();
        $placeholder.show();

        $
            .ajax({
                'method': 'GET',
                'url': '/app_dev.php/listen'
            })
            .done(showListenStatus)
            .fail(showError)
        ;
    }

    function showListenStatus(data) {
        console.log('showListenStatus', data);
        $number.text(data.phoneNumber);
        $validation.text('');

        if (data.hasOwnProperty('errors')) {
            $validation.text(data.errors.join(', '));
        }

        $placeholder.add($error).hide();
        $result.add($button).show();
    }

    function showError(xht, textStatus) {
        console.log('showError', 'textStatus');
        $error.text(textStatus).add($button).show();
        $placeholder.add($result).hide();
    }

    $button.on('click', runListenScript);
})();