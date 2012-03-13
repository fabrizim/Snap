(function($){
    
    var methods,
        send_to_editor = window.send_to_editor,
        tb_remove = window.tb_remove;
        
    methods = {
        init : function(){
            $(this.selector).live('click', function(){
                var self = $(this);
                window.send_to_editor = function(arg){ snap_send_to_editor(arg, self); };
                window.tb_remove = snap_tb_remove;
                tb_show('Choose Image', self.attr('data-url'));
            });
            return this;
        },
        
        update_image : function(src){
            update_image( src, $(this) );
            return this;
        }
    }
    
    function update_image( src, self )
    {
        var ct;
        if( (ct = self.prevAll('.img-ct')) ){
            var h = ct.attr('data-height');
                
            var style = 'max-width: 100%;';
            if( h ) style+='max-height: '+h+'px;'
                
            ct.html('<img src="'+src+'" style="'+style+'"/>');
        }
    }
    
    function save_html(html, self){
        var ct, src = $('img', html).attr('src');
        self.prevAll('input').val( src );
        update_image( src );
    }
    
    function save_json(data, self)
    {
        var ct
          , size
          , url = data.url
          ;
          
        if( (ct = self.prevAll('.img-ct')) && (size = ct.attr('data-size')) ){
            url = data.sizes[size];
        }
        self.prev().val( data.id );
        update_image( url, self );
    }
    
    function snap_send_to_editor(arg, self)
    {
        self.attr('data-use_id') ? save_json(arg, self) : save_html(arg, self);
        tb_remove();
        return false;
    }
    
    function snap_tb_remove()
    {
        window.send_to_editor = send_to_editor;
        window.tb_remove = tb_remove;
        tb_remove();
    }
    
    $.fn.snapupload = function(method){
        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            return $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }    
    };
    
})(jQuery);

jQuery(function($){
    $('.snap-upload-button').snapupload();
});