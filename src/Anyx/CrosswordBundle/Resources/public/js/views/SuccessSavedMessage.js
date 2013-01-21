
Anyx.View.SuccessSavedMessage = Anyx.View.extend({
  
    _modal: null,
  
    events: {
        'click .i-close-modal' : 'closeModal'
    },
    
    showModal: function() {
        this._modal = $.modal({
                content: $(this.el),
                title: this.options.messages.congratulatons,
                buttons: {},
                resizable: false,
                actions: {}
        });
    },
    
    closeModal: function() {
        this._modal.closeModal();
    }
})