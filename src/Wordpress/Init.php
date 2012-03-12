<?php

class Snap_Wordpress_Init extends Snap_Wordpress_Plugin
{
    /**
     * @wp.filter
     */
    function image_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt)
    {
        // check referrer
        $referer =  @$_SERVER['HTTP_REFERER'];
        
        if( strpos($referer, 'json=1') == -1 ) return $html;
        
        $return_object = array(
            'html'          => $html,
            'id'            => $id,
            'caption'       => $caption,
            'title'         => $title,
            'align'         => $align,
            'url'           => $url,
            'size'          => $size,
            'alt'           => $alt
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