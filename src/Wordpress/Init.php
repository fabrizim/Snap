<?php

class Snap_Wordpress_Init extends Snap_Wordpress_Plugin
{
    
    /**
     * @wp.action
     */
    function admin_head()
    {
        wp_enqueue_style('thickbox');
        wp_register_script('snap-upload', plugins_url('/resources/js/snap-upload.js', SNAP_DIR), array('media-upload'));
    }
    /**
     * @wp.filter
     */
    function image_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt)
    {
        // check referrer
        $referer =  @$_SERVER['HTTP_REFERER'];
        
        if( strpos($referer, 'json=1') == -1 ) return $html;
        
        $sizes = array();
        foreach( get_intermediate_image_sizes() as $size ){
            $src = wp_get_attachment_image_src( $id, $size );
            $sizes[$size] = $src[0];
        }
        
        $return_object = array(
            'html'          => $html,
            'id'            => $id,
            'caption'       => $caption,
            'title'         => $title,
            'align'         => $align,
            'url'           => $url,
            'size'          => $size,
            'alt'           => $alt,
            'sizes'         => $sizes
        );
        
        ?>
<script type="text/javascript">
/* <![CDATA[ */
var win = window.dialogArguments || opener || parent || top;
win.send_to_editor(<?php echo json_encode($return_object); ?>);
/* ]]> */
</script>        
        <?php
        exit;
    }
}