"use strict";
window.__globals.default_show_message = true;
window.appFuncs = {
    modelSuccessCallback: function (data, callbackParams) {
        if ((data.reaction === 1) && callbackParams) {
            if (callbackParams.datatableId) {
                if (_.isArray(callbackParams.datatableId)) {
                    _.each(callbackParams.datatableId, function (index) {
                        $(index).dataTable().api().ajax.reload();
                    });
                } else {
                    $(callbackParams.datatableId).dataTable().api().ajax.reload();
                }
            }
            if (callbackParams.modalId) {
                $(callbackParams.modalId).modal('hide');
            }

            if (callbackParams.pageReload) {
                _.delay(function () {
                    __Utils.viewReload();
                }, 300);
            }
        }
    },
    clearContainer: function (data, $element) {
        var $responseHolder = $($element.data('response-template')),
            $responseTemplate = $($element.data('response-template') + '-template');
        $responseHolder.html(
            '<div class="lw-spinner-box"><div class="text-center align-middle"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><div></div>'
        );
    },
    resetForm: function (data, $element) {
        var $targetForm = $('#whatsAppMessengerForm');
        $targetForm[0].reset();
        var validator = $targetForm.validate();
        validator.resetForm();
        if (jQuery().emojioneArea) {
            window.lwMessengerEmojiArea[0].emojioneArea.setText('');
        }
    },
    prepareUpload: function () {
    }
};

$('.modal').on('shown.bs.modal', function (shownEvent) {
    var $targetModal = $(shownEvent.target);
    if ($targetModal.data('init-uploader') && $targetModal.find('.lw-file-uploader').length) {
        window.initUploader();
    }
    var $targetForm = $targetModal;
    if ($targetForm.length) {
        lwPluginsInit();
    }
});

// Reset forms in the modal after close
$('.modal').on('hidden.bs.modal', function (hiddenEvent) {
    // Get the modal
    var $targetForm = $(hiddenEvent.target).find('form');
    if ($targetForm.length) {
        $targetForm[0].reset();
        var validator = $targetForm.validate();
        validator.resetForm();
        $targetForm.find('div.lw-validation-error').remove();
        $targetForm.find('.lw-validation-error').removeClass('.lw-validation-error');
        if ($targetForm.data('on-close-update-models')) {
            __DataRequest.updateModels(($targetForm.data('on-close-update-models')))
        }
        // reset Selectize
        $targetForm.find('[data-lw-plugin="lwSelectize"]').each(function (index, selectizeFieldElement) {
            if (selectizeFieldElement.selectize) {
                selectizeFieldElement.selectize.destroy();
                $(selectizeFieldElement).val('').change();
            }
        });
    }
});

//Outer-home
window.addEventListener('DOMContentLoaded', event => {

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            offset: 74,
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

});