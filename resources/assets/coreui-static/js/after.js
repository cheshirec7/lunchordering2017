// Loaded after CoreUI app.js

import swal from 'sweetalert2';

/**
 * Place any jQuery/helper plugins in here.
 */
$(document).ready(function ($) {
    var $loading = $('.loader'),
        $spinner = $('.ajax-spinner'),
        $token = $('meta[name="csrf-token"]').attr('content');

    $(document).ajaxStart(function () {
        $loading.show();
    }).ajaxError(function (event, jqxhr, settings, thrownError) {
        $loading.hide();
        // alert(thrownError);
        location.reload();
    }).ajaxStop(function () {
        $loading.hide();
    });

    /**
     * Place the CSRF token as a header on all pages for access in AJAX requests
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $token
        }
    });

    /**
     * Generic confirm form delete using Sweet Alert
     */
    $('body').on('click', 'a[data-method="delete"]', function (e) {
        e.preventDefault();

        let $this = $(this);

        swal({
            title: $this.attr('data-trans-title'),
            text: $this.attr('data-trans-text'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.value) {
                $spinner.hide();
                $.ajax({
                    url: $this.attr('href'),
                    type: 'post',
                    data: {_method: 'delete', _token: $token}
                }).done(function () {
                    location.reload();
                });
            } else {
                // handle dismiss, result.dismiss can be 'cancel', 'overlay', 'close', and 'timer'
                //console.log(result.dismiss)
            }
        })
    }).on('click', function (e) {
        /**
         * This closes popovers when clicked away from
         */
        $('[data-toggle="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});