<?php

class Snap_Wordpress_Form_Renderer_AdminTable extends Snap_Wordpress_Form_Renderer_Default
{
    
    public function renderOpenGroup($form)
    {
        ?>
        <table class="form-table">
        <?php
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
                <th colspan="2"><span style="font-size: 1.2em; font-weight: bold;"><?= $field->getLabel() ?></span></th>
            </tr>
            <?php
        }
        ?>
        <tr valign="top">
            <td colspan="2">
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