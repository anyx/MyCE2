
var Anyx = Anyx || {};

Anyx.Utils = Anyx.Utils || {};

Anyx.Utils.Translator = (function(){
    
    var translations = {};
    
    return {
        
        setTranslations : function( messages ) {
            translations = messages
        },
        
        translate   : function( message ) {
            
            if ( message in translations ) {
                return translations[message];
            }
            
            return message;
        },
    }
})();