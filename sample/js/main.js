$(function() {
    validation = {
       'amount'  :   /^(?:0\.(?:0[1-9]|[1-9]\d)|[1-9]+\d*\.\d{2})$/, 
       'customer':   /.+/, 
       'card_num':   /^\d{12,19}$/, 
       'expiration': /^(?:0[1-9]|1[0-2])\/20\d{2}$/, 
       'ccv':        /^\d{3,4}$/, 
       'holder':     /^[A-Za-z]+$/ 
    };

    validate = function(e) {
        regexp = validation[e.attr('data-valid')];
        
        if (regexp.test(e.val())) {
            e.parents('.form-group')
                .addClass('has-success')
                .removeClass('has-error')
                .children('.help-block')
                .hide();
        } else {
            e.parents('.form-group')
                .addClass('has-error')
                .removeClass('has-success')
                .children('.help-block')
                .show();
        }
    }

    function refreshModal(modal)
    {
        modal.find('.modal-header')
            .removeClass("bg-success bg-danger")
            .find('.modal-title')
            .text("");

        modal.find('.modal-body > p').text("");
    }

    function setModalHeader(modal, text, bg_class) 
    {
        modal.find('.modal-header').addClass(bg_class).find('.modal-title').text(text)
    }

    function setModalBody(modal, text)
    {
        modal.find('.modal-body > p').text(text)
    }

    function submit(url, data) 
    {
        $.getJSON(url, data, function(answer){
            modal = $('.modal')
            if (answer.success) {
                setModalHeader(modal, 'Succes!', 'bg-success');
            } else {
                setModalHeader(modal, 'Error!', 'bg-danger');
            }

            setModalBody(modal, answer.msg);
        });
    }

    $('input').on('input', function(){
        validate($(this));
    });

    $('form').submit(function(event){
        event.preventDefault();
        
        $('input').trigger('input');
        
        if ($('input').parents('.form-group').not('.has-success').length > 0) {
            return;
        } else {
            form = $('form');
            modal = $('.modal');
            data = form.serialize();
            url  = form.attr('action');
            refreshModal(modal)
            setModalHeader(modal, 'Please wait...', "")
            modal.modal('show');
            submit(url, data)
        }
    });
    
});
