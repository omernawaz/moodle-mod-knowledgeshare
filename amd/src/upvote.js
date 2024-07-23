define(['jquery', 'core/ajax'], function($, Ajax) {
    $('.upvote-btn').click(function(e) {
        e.preventDefault();
        let element = $(this);
        let modid = element.data('modid');
        let postid = element.data('postid');

        let request = {
            methodname: 'mod_knowledgeshare_upvote_post',
            args: {
                modid: modid,
                postid: postid,
            }
        };

        let button = $(this);
        Ajax.call([request])[0].done(function(result) {
            result = JSON.parse(result);
            if (result.status == 'upvote') {
                $('.upvote-html').html("<b>Upvotes: </b>" + result.upvotes);
                button.html("<b>Upvoted</b>");
            } else {
                $('.upvote-html').html("<b>Upvotes: </b>" + result.upvotes);
                button.html("Upvote");
            }

        }).fail(function(error) {
            console.log(error);
        });
    });
});