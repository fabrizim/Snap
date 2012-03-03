<?php

class Snap_Wordpress_Form_Renderer_Default
{
    
    protected $last_field;    
    
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
    
    public function renderOpenForm( $action, $method="post" )
    {
        ?>
        <form action="<?= $action ?>" method="<?= $method ?>" class="form-horizontal">
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
                'classes' => array('btn btn-primary'),
                'id' => 'snap_button_'.(++$id)
            ), $button);
            
            $attrs=array();
            $attrs['class'] = implode(' ',$options['classes']);

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
        $classes = array(
            'clearfix',
            'control-group',
            'control-'.$field->getType()
        );
        if( $field->hasError() ){
            $classes[] = 'error';
        }
        ?>
        <div class="<?= implode(' ', $classes) ?>">
            <label class="control-label" for="<?= $field->getId() ?>"><?= $field->getLabel() ?><? if( $field->isRequired() ){ ?> <sup class="required-asterisk">*</sup><? } ?></label>
            <div class="controls">
            <? $this->renderControl( $field ) ?>
            <? if( $field->hasError() ) { ?>
            <span class="help-inline">
                <?= $field->getError() ?>
            </span>
            <? } ?>
            </div>
        </div>
        <?php
    }
    
    public function renderFieldCheckbox( $field )
    {
        $classes = array(
            'clearfix',
            'control-group',
            'control-'.$field->getType()
        );
        if( $field->hasError() ){
            $classes[] = 'error';
        }
        $inputValue = $field->cfg('inputValue', 1);
        $checked = $inputValue == $field->getValue();
        ?>
        <div class="<?= implode(' ', $classes ) ?>">
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
            <span><?= $field->getLabel() ?><? if( $field->isRequired() ){ ?> <sup class="required-asterisk">*</sup><? } ?></span>
            </label>
            <? if( $field->hasError() ) { ?>
            <span class="help-inline">
                <?= $field->getError() ?>
            </span>
            <? } ?>
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
        if( $field->hasError() ){
            $classes[] = 'error';
        }
        $inputValue = $field->cfg('inputValue', 1);
        $checked = $inputValue == $field->getValue();
        ?>
        <input
            class="checkbox"
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
        ?>
        <input
            class="regular-text snap-<?= $field->getType() ?>"
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
        $this->renderInput( $field, 'text' );
        ?>
        <a class="snap-upload-button button">Choose Image</a>
        <?php
    }
    
    public function renderSelect( $field )
    {
        $options = $field->getOptions();
        ?>
        <select
            name="<?= $field->getName() ?>"
            id="<?= $field->getId() ?>"
        >
            <?php foreach( $options as $value=> $label ){ ?>
            <option value="<?= esc_attr( $value ) ?>" <? if( $value == $field->getValue() ){ ?>selected<? } ?>><?= $label ?></option>
            <?php } ?>
        </select>
        <?php
    }
}