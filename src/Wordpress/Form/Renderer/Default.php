<?php

class Snap_Wordpress_Form_Renderer_Default
{
    
    protected $last_field;
    
    protected function implodeUnique( $classes )
    {
        return implode(' ', array_unique( $classes ) );
    }
    
    public function renderOpenGroup( $form )
    {
        ?>
        <div class="admin-form">
        <?php
    }
    
    public function renderCloseGroup( $form )
    {
        ?>
        </div>
        <?php
    }
    
    public function getFormClasses()
    {
        return array('form-horizontal');
    }
    
    public function getFieldClasses( $field )
    {
        return array(
            'clearfix',
            'control-group',
            'control-'.$field->getType()
        );
    }
    
    public function getButtonClasses()
    {
        return array('btn');
    }
    
    public function getControlClasses( $field, $type )
    {
        $classes = array('snap-'.$type);
        if( $field->hasError() ) $classes[] = 'error';
        return $classes;
    }
    
    public function renderOpenForm( $action, $method="post" )
    {
        $classes = $this->getFormClasses();
        apply_filters('snap_form_classes', $classes, $this);
        ?>
        <form action="<?= $action ?>" method="<?= $method ?>" class="<?= $this->implodeUnique($classes) ?>">
        <?php
    }
    
    public function renderFormErrors( $errors )
    {
        if( !$errors || !count($errors) ) return;
        ?>
        <?php foreach( $errors as $error ){ ?>
            <div class="alert alert-error">
                <a class="close" data-dismiss="alert">×</a>
                <?= $error ?>
            </div>
        <?php } ?>
        <?php
        
    }
    
    public function renderButtons( $buttons=array() )
    {
        static $id=0;
        if( !count($buttons) ) return;
        ?>
        <div class="form-actions">
        
        <?php
        foreach( $buttons as $button ){
            $options = array_merge(array(
                'tag' => 'input',
                'text' => 'Submit',
                'id' => 'snap_button_'.(++$id)
            ), $button);
            
            $attrs=array();
            $attrs['class'] = $this->implodeUnique($this->getButtonClasses( $button ) );

            switch( $options['tag'] ){
                case 'input':
                    $attrs['value'] = esc_attr($options['text']);
                    $attrs['type'] = 'submit';
                    ?>
                    <input <?= self::attrs($attrs) ?> />
                    <?php
                    break;
                
                default:
                    ?>
                    <button <?= self::attrs($attrs) ?>><?= $options['text'] ?></button>
                    <?php
                    break;
            }
        }
        ?>
        </div>
        <?php
    }
    
    public function attrs($a)
    {
        $b=array();
        foreach($a as $k => $v) $b[] = $k.'="'.esc_attr($v).'"';
        return implode(' ',$b);
    }
    
    public function renderCloseForm( )
    {
        ?>
        </form>
        <?php
    }
    
    public function renderInlineError( $field )
    {
        if( $field->hasError() ):
            ?>
            <span class="help-inline">
                <?= $field->getError() ?>
            </span>
            <?php
        endif;
    }
    
    public function renderField( $field )
    {
        $type = $field->getType();
        $Type = strtoupper( substr($type, 0, 1) ) . substr( $type, 1 );
        $fn = 'renderField'.$Type;
        if( method_exists( $this, $fn ) ){
            $this->$fn( $field );
        }
        else{
            $this->renderFieldDefault( $field );
        }
    }
    
    public function renderFieldDefault( $field )
    {
        $classes = $this->getFieldClasses( $field );
        if( $field->hasError() ){
            $classes[] = 'error';
        }
        ?>
        <div class="<?= $this->implodeUnique($classes) ?>">
            <label class="control-label" for="<?= $field->getId() ?>"><?= $field->getLabel() ?><? if( $field->isRequired() ): ?> <span class="required-asterisk">*</span><? endif; ?></label>
            <div class="controls">
            <? $this->renderControl( $field ) ?>
            <? $this->renderInlineError( $field ) ?>
            </div>
        </div>
        <?php
    }
    
    public function renderFieldCheckbox( $field )
    {
       $classes = $this->getFieldClasses( $field );
        if( $field->hasError() ){
            $classes[] = 'error';
        }
        $inputValue = $field->cfg('inputValue', 1);
        $checked = $inputValue == $field->getValue();
        ?>
        <div class="<?= $this->implodeUnique( $classes ) ?>">
            <div class="controls">
            <label for="<?= $field->getId() ?>" class="checkbox">
            
            <input
                class="checkbox"
                type="checkbox"
                value="<?= $inputValue ?>"
                <? if( $checked ){ ?>checked<? } ?>
                name="<?= $field->getName() ?>"
                id="<?= $field->getId() ?>"
            />
            <span><?= $field->getLabel() ?><? if( $field->isRequired() ): ?> <span class="required-asterisk">*</span><? endif; ?></span>
            </label>
            <? $this->renderInlineError( $field ) ?>
            </div>
        </div>
        <?php
    }
    
    public function renderFieldHidden( $field )
    {
        $this->renderControl( $field );
    }
    
    public function renderControl( $field )
    {
        $type = $field->getType();
        $Type = strtoupper( substr($type, 0, 1) ) . substr( $type, 1 );
        $fn = 'render'.$Type;
        if( method_exists( $this, $fn ) ){
            $this->$fn( $field );
        }
        else{
            $this->renderInput( $field );
        }
    }
    
    public function renderCheckbox( $field )
    {
        $classes = $this->getControlClasses( $field, 'checkbox' );
        $inputValue = $field->cfg('inputValue', 1);
        $checked = $inputValue == $field->getValue();
        ?>
        <input
            class="<?= $this->implodeUnique( $classes ) ?>"
            type="checkbox"
            value="<?= $inputValue ?>"
            <? if( $checked ){ ?>checked<? } ?>
            name="<?= $field->getName() ?>"
            id="<?= $field->getId() ?>"
        />
        <?php
    }
    
    public function renderInput( $field, $type=null )
    {
        if( !$type ) $type = $field->getType();
        $classes = $this->getControlClasses( $field, $type );
        ?>
        <input
            class="<?= $this->implodeUnique($classes) ?>"
            type="<?= $type ?>"
            value="<?= esc_attr($field->getValue()) ?>"
            name="<?= $field->getName() ?>"
            id="<?= $field->getId() ?>"
         />
        <?php
    }
    
    public function renderDate( $field )
    {
        static $scoped;
        $this->renderInput( $field, 'text' );
        ?>
        <script type="text/javascript">
        jQuery(function($){
            $('#<?= $field->getId() ?>').datepicker();
        
            <? if( !isset($scoped) ){ $scoped = true; ?>
            $(window).load(function(){
                $('#ui-datepicker-div').wrap('<div class="snap-ui" />');
            });
            <? } ?>
        });
        </script>
        <?php
        
    }
    
    public function renderDay( $field )
    {
        $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
        $val = $field->getValue();
        $value='';
        $m=$d=$y=null;
        if( $val && preg_match('/\d+\-\d+\-\d+/', $val) ){
            list($y,$m,$d) = explode( '-', $val );
        }
        ?>
        <input type="hidden" name="<?= $field->getName() ?>" value="<?= $value ?>" />
        <select name="<?=$field->getName()?>_month">
            <option value="">Month</option>
            <?php foreach( $months as $i => $month ){ ?>
            <option <?php if( $m == $i+1 ){ ?>selected<? } ?> value="<?= $i+1 ?>"><?= $month ?></option>
            <?php } ?>
        </select>
        <select name="<?=$field->getName()?>_day">
            <option value="">Day</option>
            <?php for( $i=0; $i<31; $i++ ){ ?>
            <option <?php if( $d == $i+1 ){ ?>selected<? } ?> value="<?= $i+1 ?>"><?= sprintf('%02s', $i+1) ?></option>
            <?php } ?>
        </select>
        <select name="<?=$field->getName()?>_year">
            <option value="">Year</option>
            <?php for( $i=(int)date('Y'); $i>1899; $i-- ){ ?>
            <option <?php if( $y == $i ){ ?>selected<? } ?> value="<?= $i ?>"><?= $i ?></option>
            <?php } ?>
        </select>
        <?php
    }
    
    public function renderTime( $field )
    {
        $value = $field->getValue();
        $tfid = $field->getId().'_timefield';
        
        $h = '12';
        $m = '00';
        $a = 'AM';
        
        if( $value && preg_match( '#(\d{2})\:(\d{2})\s(AM|PM)#', $value, $matches ) ){
            $h = $matches[1];
            $m = $matches[2];
            $a = $matches[3];
        }
        
        ?>
        <div class="time-field" id="<?= $tfid ?>">
            <input
                type="hidden"
                value="<?= esc_attr( $field->getValue() ) ?>"
                name="<?= $field->getName() ?>"
            />
            <select
                name="<? $field->getName().'_hour' ?>"
                id="<?= $field->getId() ?>"
            >
                <?php for( $i=1; $i<13; $i++) {
                    $v = sprintf('%02d', $i);
                    ?>
                <option <? if($h == $v ){?>selected<? } ?>><?= $v ?></option>
                <?php } ?>
            </select>
            <select
                id="<?= $field->getId() ?>"
                name="<? $field->getName().'_minute' ?>"
                
            >
                <?php for( $i=0; $i<60; $i+=15) {
                    $v = sprintf('%02d', $i);
                    ?>
                <option <? if($m == $v ){?>selected<? } ?>><?= $v ?></option>
                <?php } ?>
            </select>
            
            <select
                id="<?= $field->getId() ?>"
                name="<? $field->getName().'_ampm' ?>"
            >
                <?php foreach( array('AM','PM') as $i ) { ?>
                <option <? if($a == $i ){?>selected<? } ?>><?= $i ?></option>
                <?php } ?>
            </select>
        </div>
        <script type="text/javascript">
        jQuery(function($){
            $('#<?=$tfid?>').each(function(i, el){
                var h = $(el).find('input[type=hidden]'),
                    s = $(el).find('select');
                    
                s.change( setValue );
                
                function setValue(){
                    var o='';
                    s.each(function(i, e){
                        o+=$(e).find('option:selected').val()
                        switch(i){
                            case 0:
                                o+=':';
                                break;
                            case 1:
                                o+=' ';
                                break;
                        }
                    });
                    h.val(o);
                }
                
            });
        });
        </script>
        <?php
    }
    
    public function renderWysiwyg( $field )
    {
        $last_field = $this->last_field ? $this->last_field->getName() : '';
        wp_editor( $field->getValue(), $field->getName(), $last_field );
    }
    
    public function renderImage( $field )
    {
        static $includedJS=false;
        
        $url_params = array(
            //'post_id='.get_the_ID(),
            'type=image',
            'TB_iframe=1'
        );
        
        $use_id = $field->cfg('use_id');
        
        if( $use_id ){
            $url_params[] = 'json=1';
        }
        
        $url = 'media-upload.php?'.implode('&#038;', $url_params);
        
        if( $field->cfg('display_image') ){
            $style='max-width: 100%;';
            $h = $field->cfg('image_height');
            $size = $field->cfg('image_size');
            
            if( $h ){
                $style.="max-height: {$h}px;";
            }
            ?>
            <span class="img-ct" data-height="<?=$h?>"<? if( $size ){ ?> data-size="<?= $size ?>"<? } ?>>
            <?php
            if( $field->getValue() ){
                
                $val = $field->getValue();
                if( $use_id ){
                    if( ($size = $field->cfg('image_size')) ){
                        $size = wp_get_attachment_image_src( $val, $size );
                        $val = $src[0];
                    }
                    else{
                        $val = wp_get_attachment_url( $val );
                    }
                }
                ?>
                <img src="<?= $val ?>" style="<?= $style ?>" />
                <?php
            }else{
                ?>
                No Image... 
                <?php
            }
            ?>
            </span>
            <br />
            <?php
            $this->renderInput( $field, 'hidden' );
        }
        else{
            $this->renderInput( $field, 'text' );
        }
        ?>
        <a class="snap-upload-button button"
           data-url="<?= $url ?>"
           <? if( $use_id ){ ?>data-use_id="true"<? } ?>
        >Choose Image</a>
        <?php
        if( !$includedJS ){
            $includedJS = true;
            wp_enqueue_script('media-upload');
            wp_enqueue_script('snap-upload');
        }
    }
    
    public function renderSelect( $field )
    {
        $options = $field->getOptions();
        $classes = $this->getControlClasses( $field, 'select' );
        ?>
        <select
            class="<?= $this->implodeUnique($classes) ?>"
            name="<?= $field->getName() ?>"
            id="<?= $field->getId() ?>"
        >
            <?php foreach( $options as $value=> $label ){ ?>
            <option value="<?= esc_attr( $value ) ?>" <? if( $value == $field->getValue() ){ ?>selected<? } ?>><?= $label ?></option>
            <?php } ?>
        </select>
        <?php
    }
    
    public function renderTextarea( $field )
    {
        $classes = $this->getControlClasses( $field, 'textarea' );
        ?>
        <textarea
            class="<?= $this->implodeUnique( $classes ) ?>"
            style="width: 100%;"
            name="<?= $field->getName() ?>"
            id="<?= $field->getId() ?>"
        ><?= $field->getValue() ?></textarea>
        <?php
    }
}