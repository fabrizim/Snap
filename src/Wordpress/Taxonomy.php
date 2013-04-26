<?php

/**
 * @wp.taxonomy.name                                custom_category
 * @wp.taxonomy.single                              Category
 * @wp.taxonomy.plural                              Categories
 *              
 * @wp.taxonomy.args.public                         true
 * @wp.taxonomy.args.show_ui                        true
 * @wp.taxonomy.args.show_in_nav_menus              true
 * @wp.taxonomy.args.show_tagcloud                  true
 * @wp.taxonomy.args.show_admin_column              false
 * @wp.taxonomy.args.hierarchical                   true
 * @wp.taxonomy.args.query_var                      $taxonomy
 * @wp.taxonomy.args.rewrite                        true
 *              
 * @wp.taxonomy.labels.name                         %Plural%
 * @wp.taxonomy.labels.singular_name                %Single%
 * @wp.taxonomy.labels.menu_name                    %Plural%
 * @wp.taxonomy.labels.all_items                    All %Plural%
 * @wp.taxonomy.labels.edit_item                    Edit %Single%
 * @wp.taxonomy.labels.view_item                    View %Single%
 * @wp.taxonomy.labels.update_item                  Update %Single%
 * @wp.taxonomy.labels.add_new_item                 Add New %Single%
 * @wp.taxonomy.labels.new_item_name                New %Single% Name
 * @wp.taxonomy.labels.parent_item                  Parent %Single%
 * @wp.taxonomy.labels.parent_item_colon            Parent %Single%:
 * @wp.taxonomy.labels.search_items                 Search %plural%
 * @wp.taxonomy.labels.popular_items                Popular %plural%
 * @wp.taxonomy.labels.separate_items_with_commas   Separate %plural% with commas
 * @wp.taxonomy.labels.add_or_remove_items          Add or remove %plural%
 * @wp.taxonomy.labels.choose_from_most_used        Choose from most used %plural%
 * @wp.posttype.labels.not_found                    No %plural% found
 * 
 */
class Snap_Wordpress_Taxonomy extends Snap_Wordpress_Plugin
{
  protected $name;
  protected $single;
  protected $plural;
  
  /**
   * @wp.action
   */
  public function init()
  {        
    $this->snap = Snap::get( $this );
    
    $args = $this->snap->klass('wp.taxonomy.args');
    $labels = $this->snap->klass('wp.taxonomy.labels');
    $types = $this->snap->klass('wp.taxonomy.post_types', array());
    
    $this->name = $this->snap->klass('wp.taxonomy.name', $this->name );
    $this->single = $this->snap->klass('wp.taxonomy.single', $this->single );
    $this->plural = $this->snap->klass('wp.taxonomy.plural', $this->plural );
    
    $args['labels'] = $this->parseLabels($labels);
    $args['supports'] = array();
    
    $args = apply_filters('snap_taxonomy_args', $args);
    
    register_taxonomy( $this->name, $types, $this->filterArgs($args) );
  }
  
  protected function filterArgs( $args )
  {
    return $args;
  }
  
  protected function parseLabels( $labels )
  {
    $replacements = array(
        '%single%'      => strtolower($this->single),
        '%plural%'      => strtolower($this->plural),
        '%Single%'      => $this->single,
        '%Plural%'      => $this->plural
    );
    $from = array_keys( $replacements );
    $to = array_values( $replacements );
    foreach( $labels as $key => $value ){
        $labels[$key] = str_replace( $from, $to, $value );
    }
    return $labels;
  }
}