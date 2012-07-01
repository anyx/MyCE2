
var Anyx = Anyx || {};

Anyx.Utils = Anyx.Utils || {};

Anyx.Utils.Alert = (function(){
    
    var alert = {
        
        show       : function( message, options ) {
            
            var options = _.extend({
                title           : 'alertTitle',
                buttonCaption   : 'Close'
            }, options);
            
            var title =  Anyx.Utils.Translator.translate( options.title );
            var buttonCaption = Anyx.Utils.Translator.translate( options.buttonCaption );
            
            var buttons = {};
            buttons[buttonCaption] = {
            	classes : 'gray-gradient',
                click   : function(modal) { modal.closeModal(); }
            }
            
            var modalOptions = {
                    title: title,
                    content: Anyx.Utils.Translator.translate(message),
                    buttons: buttons
                };
            
            $.modal(modalOptions);
        }
    };
    
    Anyx.Utils.alert = alert.show;
    
    Anyx.Utils.alertError = function( message ){
        return alert.show( message, {
            title   : 'error'
        });
    };
    
    return alert;
    
})();