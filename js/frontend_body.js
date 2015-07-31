if(typeof $.validator != 'undefined')
{
    jQuery('form.commentform').validate({
        rules: {
            comment_author: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            comment: {
                required: true,
                minlength: 20
            },
            captcha: {
                required: true
            }
        },
        messages: {
            comment_author: cattranslate("Please specify your name",'','','comments'),
            email: {
                required: cattranslate("Your email address is required",'','','comments'),
                email: cattranslate("Your email address must be in the format of name@domain.com",'','','comments')
            },
            comment: {
                required: cattranslate('Please leave a comment','','','comments'),
                minlength: jQuery.validator.format(cattranslate("At least {0} characters required!",'','','comments'))
            }
        },
        errorPlacement: function(error, element) {
            error.insertBefore(element);
        }
  });
}

$('a.reply,a.scroll').unbind('click').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    $('input[name="parent"]').val($(this).data('parent'));
    $('html, body').animate({
        scrollTop: $("form.commentform").offset().top
    }, 2000);
    return false;
});

$("form.commentform").unbind('submit').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    $.ajax({
		type:		'POST',
		url:		CAT_URL + '/modules/comments/ajax/ajax_save.php',
		dataType:	'json',
		data:		$('form.commentform').serialize() + '&submit=1&section_id=' + $('input[name="section_id"]').val(),
		cache:		false,
		success:	function( data, textStatus, jqXHR  )
		{
            if ( data.success === true )
			{
                $('div.respond').html(
                    '<div class="success">' +
                    cattranslate(data.message) +
                    '</div>'
                );
			}
			else {
                $('div.respond').html(
                    '<div class="error">' +
                    cattranslate(data.message) +
                    '</div>'
                );
			}
        }
    });
    return false;
});

if(typeof window.qtip != 'undefined') {
    $('[title!=""]').qtip({
        content: {attr: 'title'},
        style  : {classes: 'qtip-light qtip-shadow qtip-rounded'},
    });
}