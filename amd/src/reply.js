define(['jquery', 'core/ajax', 'core/modal_factory', 'core/modal_events', 'mod_knowledgeshare/reply_modal'], function($, Ajax, ModalFactory, ModalEvents, MyModal){

    let trigger = $(".reply-btn");

    $(trigger).click(function (e) { 
        e.preventDefault();
        console.log("Clicked");
    });

    ModalFactory.create({
        type: MyModal.TYPE,
        title: "Test Title",
        large: true
    } , trigger)
        .done(function(modal) {
            modal.getRoot().on(ModalEvents.save, function(e) {
                e.preventDefault();
                console.log("Test");
                window.location.reload();
            });
            
        });
});