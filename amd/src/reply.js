define(['jquery', 'core/ajax', 'core/modal_factory', 'core/custom_interaction_events', 'mod_knowledgeshare/reply_modal'], function($, Ajax, ModalFactory, CustomEvents, ReplyModal){

    let trigger = $(".reply-btn");

    let postid = trigger.data('postid');
    let modid = trigger.data('modid');


    ModalFactory.create({
        type: ReplyModal.TYPE,
        title: "Add a comment",
        large: true
    } , trigger)
        .done(function(modal) {
            
            modal.getRoot().on(CustomEvents.events.activate, '[data-action="reply"]', function(e){

                let comment = modal.modal.find('#inputReply');
                let commentContent = comment[0].value;
                console.log(comment[0].value);


                if(commentContent == ''){
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

                    Ajax.call([request])[0].done(function(result){
                        window.location.reload();
                    }).fail(function(error){
                        console.log(error);
                    });
                }              
                //window.location.reload();
            });

            modal.getRoot().on(CustomEvents.events.activate, '[data-action="cancel"]', function(e){
                console.log("Cancel Clicked");
                modal.hide();
            });
        });
});