define(['jquery', 'core/ajax', 'core/modal_factory', 'core/custom_interaction_events', 'mod_knowledgeshare/reply_modal'],
    function($, Ajax, ModalFactory, CustomEvents, ReplyModal) {

    let trigger = $(".reply-btn");

    var clickedInstanceBtn = null;
    $(".reply-btn").click(function() {
        clickedInstanceBtn = $(this);
    });


    ModalFactory.create({
        type: ReplyModal.TYPE,
        title: "Add a comment",
        large: true
    }, trigger)
        .done(function(modal) {
            modal.getRoot().on(CustomEvents.events.activate, '[data-action="reply"]', function() {
                let comment = modal.modal.find('#inputReply');
                let commentContent = comment[0].value;
                console.log(comment[0].value);

                let modid = clickedInstanceBtn.data('modid');
                let postid = clickedInstanceBtn.data('postid');

                if (commentContent == '') {
                    $(comment).addClass("is-invalid");
                } else {

                    let request = {
                        methodname: 'mod_knowledgeshare_add_reply',
                        args: {
                            modid: modid,
                            postid: postid,
                            content: commentContent
                        }
                    };

                    Ajax.call([request])[0].done(function() {
                        window.location.reload();
                    }).fail(function(error) {
                        console.log(error);
                    });
                }
            });

            modal.getRoot().on(CustomEvents.events.activate, '[data-action="cancel"]', function() {
                console.log("Cancel Clicked");
                modal.hide();
            });
        });
});