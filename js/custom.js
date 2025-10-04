/* global $, alert, console */

$(function () {
    'use strict';

    let userError   = true,
        eamilError  = true,
        phoneError  = true,
        msgError    = true;


    // Start Username Field
    $('.username').blur(function () {

        $('.empty-username, .length-username').hide();

        
        if ($(this).val().trim() === '') {
            $(this).css('border', '1px solid #f00');
            $('.empty-username').fadeIn(300);
            $(this).parent().find('.asterisx').fadeIn(100);
            userError = true;
        }else if ($(this).val().length < 3) {
            $(this).css('border', '1px solid #f00');
            $('.length-username').fadeIn(300);
            $(this).parent().find('.asterisx').fadeIn(100);
            userError = true;
        }
        else {
            $(this).css('border', '1px solid #080');
            $(this).parent().find('.asterisx').fadeOut(100);
            userError = false;
        }
    });
    // End Username Field

    // Start Email Field
    $('.email').blur(function () {

        $('.empty-email').hide();

        if ($(this).val().trim() === '') {
            $(this).css('border', '1px solid #f00');
            $('.empty-email').fadeIn(300);
            $(this).parent().find('.asterisx').fadeIn(100);
            eamilError = true;
        }
        else {
            $(this).css('border', '1px solid #080');
            $(this).parent().find('.asterisx').fadeOut(100);
            eamilError = false;
        }
    });
    // End Email Field

    // Start Phone Field
    $('.phone').blur(function () {

        $('.len-phone').hide();


        if ($(this).val().length != 11 || !$(this).val().match(/^(010|011|012|015)[0-9]{8}$/)) {
            $(this).css('border', '1px solid #f00');
            $('.len-phone').fadeIn(300);
            phoneError = true;
        }
        else {
            $(this).css('border', '1px solid #080');
            phoneError = false;
        }

    });
    // End Phone Field

    // Start Message Field
    $('.message').blur(function () {

        $('.empty-message, .len-message').hide();

        
        if ($(this).val().trim() === '') {
            $(this).css('border', '1px solid #f00');
            $('.len-message').fadeIn(300);
            msgError = true;
        }else if ($(this).val().length < 10) {
            $(this).css('border', '1px solid #f00');
            $('.len-message').fadeIn(300);
            msgError = true;
        }
        else {
            $(this).css('border', '1px solid #080');
            msgError = false;
        }

    });
    // End Message Field


    $('.contact-form').submit(function (e) {
        if( userError === true || userError === true || eamilError === true || phoneError === true || msgError === true ) {
            e.preventDefault(); // بيمنع الفورم من الارسال
            $('.username, .email, .phone, .message').blur();
        }
    });
    

});