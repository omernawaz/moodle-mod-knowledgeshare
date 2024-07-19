define(['jquery', 'core/modal_factory', 'core/modal_events', 'core/ajax','core/notification'],
    function($,ModalFactory,ModalEvents,Ajax,Notification){
        let trigger = $('.delete-btn');

        let postid = null;
        let modid = null;
        let action = null;


        var clickedInstanceBtn = null;
        let posttype = '';

        $(".delete-btn").click(function (e) { 
            e.preventDefault();
            clickedInstanceBtn = $(this);

            postid = clickedInstanceBtn.data('postid');
            modid = clickedInstanceBtn.data('modid');
            action = clickedInstanceBtn.data('action');


            if (action.includes('deletepost'))
                posttype = "Post";
            else 
                posttype = "Comment";
        });

        

        ModalFactory.create({
            type: ModalFactory.types.DELETE_CANCEL,
            title: "Delete " + posttype,
            body: "Are you sure you'd like to continue? This action cannot be undone",
            large: false,
        }, trigger)
            .done(function(modal){

                modal.getRoot().on(ModalEvents.delete, function(e){
                    e.preventDefault();

                    postid = clickedInstanceBtn.data('postid');
                    modid = clickedInstanceBtn.data('modid');
                    action = clickedInstanceBtn.data('action');

                    let request = {
                        methodname: 'mod_knowledgeshare_delete_post_or_reply',
                        args: {
                            itemid: postid,
                            modid: modid,
                            action: action
                        }
                    };

                    Ajax.call([request])[0].done(function(result){
                        if(result)
                            window.location.reload();
                        else
                        {
                            Notification.addNotification({
                                message: "Failed to delete",
                                type: 'error',
                            });
                        }
                    }).fail(function(error) {
                        console.log(error);
                    });
                    
                });

            });


    }
);