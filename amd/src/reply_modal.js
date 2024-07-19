import Modal from 'core/modal';
import ModalFactory from 'core/modal_factory';

export default class ReplyModal extends Modal {
    static TYPE = "mod_knowledgeshare/reply_modal";
    static TEMPLATE = "mod_knowledgeshare/reply_modal";
}

let registered = false;
if (!registered) {
    ModalRegistry.register(ReplyModal.TYPE, ReplyModal, ReplyModal.TEMPLATE);
    registered = true;
}