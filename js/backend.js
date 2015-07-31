jQuery(document).ready(function($) {
    $('div.mod_comments ul li span.icons a.del').unbind('click').click( function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('<div></div>').appendTo('body')
            .html(
                '<span class="icon icon-warning" style="float:left;margin:0 7px 40px 0;color:#f00;font-size:1.4em;text-shadow: 3px 3px 3px #ccc;"></span>' +
                cattranslate('This comment and all it\'s replies will be deleted! This action cannot be recovered. Are you sure that you want to do this?','','','comments')
            )
            .dialog({
                modal: true
                ,title: cattranslate('Confirm')
                ,width: 400
                ,buttons: [
                    {
                        text: cattranslate('Yes'),
                        click: function() {
                            $(this).dialog("close");
                        }
                    },
                    {
                        text: cattranslate('No'),
                        click: function() {
                            $(this).dialog("close");
                        }
                    }
                ]
            });
        return false;
    });
    $('div.mod_comments ul li span.icons a.view').unbind('click').click( function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('<div></div>').appendTo('body')
            .html(
                $(this).parent().parent().find('span.content').text()
            )
            .dialog({
                modal: true
                ,title: cattranslate('Content','','','comment')
                ,buttons: [
                    {
                        text: cattranslate('Close'),
                        click: function() {
                            $(this).dialog("close");
                        }
                    }
                ]
            });
        return false;
    });
});