<?php

class Snap_Wordpress_Form_Renderer_AdminTable extends Snap_Wordpress_Form_Renderer_Default
{
    
    public function renderOpenGroup($form)
    {
        ?>
        <table class="form-table">
        <?php
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
                $classes[] = 'regular-text';
                break;
        }
        return $classes;
    }
    
    public function getButtonClasses( $button )
    {
        return array(
            'button',
            'button-primary'
        );
    }
    
    
    public function renderFieldDefault( $field )
    {
        ?>
        <tr valign="top">
            <th scope="row">
                <label for="<?= $field->getId() ?>"><?= $field->getLabel() ?></label>
            </th>
            <td>
                <? $this->renderControl( $field ) ?>
                <? $this->renderInlineError( $field ) ?>
                <? $this->renderDescription( $field ) ?>
            </td>
        </div>
        <?php
    }
    public function renderFieldCheckbox( $field )
    {
        ?>
        <tr valign="top">
            <th scope="row">
                &nbsp;
            </th>
            <td>
                <label for="<?= $field->getId() ?>">
                    <? $this->renderControl( $field ) ?>
                    <span><?= $field->getLabel() ?></span>
                    <? $this->renderInlineError( $field ) ?>
                    <? $this->renderDescription( $field ) ?>
                </label>
            </td>
        </div>
        <?php
    }
    
    public function renderFieldWysiwyg( $field )
    {
        if( !$field->cfg('hide_label', true) ){
            ?>
            <tr valign="top">
                <th colspan="2"><span><?= $field->getLabel() ?></span></th>
            </tr>
            <?php
        }
        ?>
        <tr valign="top">
            <td colspan="2">
                <? $this->renderDescription( $field ) ?>
                <? $this->renderInlineError( $field ) ?>
                <? $this->renderControl( $field ) ?>
                
            </td>
        </div>
        <?php
    }
    
    
    public function renderCloseGroup($form)
    {
        ?>
        </table>
        <?php
    }
}