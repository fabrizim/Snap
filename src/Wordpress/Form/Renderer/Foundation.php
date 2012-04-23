<?php

class Snap_Wordpress_Form_Renderer_Foundation extends Snap_Wordpress_Form_Renderer_Default
{
    public function getFormClasses( )
    {
        return array_merge( parent::getFormClasses(), array('nice'));
    }
    
    public function getFieldClasses( $field )
    {
        
        $classes = array(
            'form-field',
            'clearfix',
            'form-field-'.$field->getType()
        );
        if( $field->hasError() ){
            $classes[] = 'error';
        }
        return $classes;
    }
    
    public function getControlClasses( $field, $type )
    {
        $classes = parent::getControlClasses( $field, $type );
        switch( $type ){
            case 'textarea':
            case 'text':
            case 'password':
            case 'file':
            case 'select':
                $classes[] = 'input-text';
                break;
            case 'radios':
            case 'checkbox':
                $classes[] = 'custom';
                break;
        }
        return $classes;
    }
    
    public function getButtonClasses( $button )
    {
        return array(
            'nice',
            'button',
            'blue',
            'radius',
            'large'
        );
    }
    
    public function renderInlineError( $field )
    {
        if( $field->hasError() ):
            ?>
            <small>
                <?= $field->getError() ?>
            </small>
            <?php
        endif;
    }
    
    
    public function renderSelect( $field )
    {
        $options = $field->getOptions();
        ?>
        <select
            class="input-text"
            name="<?= $field->getName() ?>"
            id="<?= $field->getId() ?>"
        >
            <?php foreach( $options as $value=> $label ){
                ?>
            <option value="<?= esc_attr( $value ) ?>" <? if( $value == $field->getValue() ){ ?>selected<? } ?>><?= $label ?></option>
            <?php } ?>
        </select>
        <?php
    }
    
}