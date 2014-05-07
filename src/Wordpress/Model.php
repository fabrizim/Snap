<?php

class Snap_Wordpress_Model
{
  protected $post_type = 'post';
  protected $data = array();
  protected $array_values = array();
  protected $modified = array();
  protected $base_fields = array(
    'ID',
    'menu_order',
    'comment_status',
    'ping_status',
    'pinged',
    'post_author',
    'post_category',
    'post_content',
    'post_date',
    'post_date_gmt',
    'post_excerpt',
    'post_name',
    'post_parent',
    'post_password',
    'post_status',
    'post_title',
    'post_type',
    'tags_input',
    'to_ping',
    'tax_input'
  );
  protected $ignore = array();
  
  public function load($id=false){
    
    $this->modified = array();
    $this->data = array();
    
    if( is_int($id) || is_string($id) ){
    
      // $post = get_post($id);
      $post = get_post( $id );
      if( !$post || !$post->ID ) return false;
      
      $custom = (array)get_post_custom($id);
      
      foreach( $custom as $key => $ar ){
        $custom[$key] = in_array( $key, $this->array_values ) ? $ar : maybe_unserialize( $ar[0] );
      }
      
      $this->data = array_merge( (array)$post, $custom );
    }
    
    else if( $id instanceof WP_Post ){
      $custom = (array)get_post_custom($id->ID);
      
      foreach( $custom as $key => $ar ){
        $custom[$key] = in_array( $key, $this->array_values ) ? $ar : maybe_unserialize($ar[0]);
      }
      
      $this->data = array_merge( (array)$id, $custom );
    }
    
    else{
      $id = (array) $id;
      if( @$id['ID'] ){
        $custom = (array)get_post_custom($id['ID']);
        
        foreach( $custom as $key => $ar ){
          $custom[$key] = in_array( $key, $this->array_values ) ? $ar : maybe_unserialize($ar[0]);
        }
        
        $id = array_merge( $id, $custom );
      }
      $this->data = $id;
    }
    
    if( !$this->get('ID') ){
      $this->modified = array_keys( $this->data );
    }
    
    return $this;
  }
  
  public function get($name, $default=null)
  {
    return isset($this->data[$name]) ? $this->data[$name] : $default;
  }
  
  public function set($name, $value=null)
  {
    if( is_array($name) ){
      foreach( $name as $key => $val ){
        $this->set($key, $val);
      }
      return $this;
    }
    else if( @$this->data[$name] != $value ){
      $this->data[$name] = $value;
      if( !in_array($name, $this->ignore) ) $this->modified[] = $name;
    }
    return $this;
  }
  
  public function hydrate()
  {
    return $this;
  }
  
  public function data()
  {
    return $this->data;
  }
  
  public function save()
  {
    $save = array('post'=>array(),'meta'=>array());
    $id = $this->get('ID');
    foreach( $this->modified as $key ){
      $save[ in_array( $key, $this->base_fields ) ? 'post' : 'meta'][$key]
      = $this->data[$key];
    }
    
    if( count( $save['post'] ) ){
      if( $id ) {
        // need to include the ID
        $save['post']['ID'] = $id;
        wp_update_post( $save['post'] );
      }
      else {
        $id = wp_insert_post( $save['post'] );
        $this->set('ID', $id);
      }
    }
    if( count( $save['meta'] ) ) foreach( $save['meta'] as $key => $value ) {
      if( is_array($value) && in_array( $key, $this->array_values ) ){
        delete_post_meta( $id, $key );
        foreach( $value as $v ) add_post_meta( $id, $key, $v );
      }
      
      else update_post_meta( $id, $key, $value );
    }
    
    // Force an updated version of this post next time
    // `get_post` is called.
    wp_cache_delete( $id, 'posts');
    
    $this->modified = array();
    return $this;
  }
  
  public function get_list($query=array())
  {
    $query = array_merge_recursive(array(
      'post_type'   => $this->post_type
    , 'fields'      => 'ids'
    ), $query);
    
    
    $q = new WP_Query($query);
    $objects = array();
    
    foreach( $q->posts as $i=> $id ){
      $objects[$i] = $this
        ->load($id)
        ->hydrate()
        ->data();
    }
    
    return array(
      'items'       => $objects
    , 'count'       => $q->found_posts
    , 'offset'      => @$q->query_vars['offset'] ? $q->query_vars['offset'] : 0
    , 'limit'       => @$q->query_vars['posts_per_page'] ? $q->query_vars['posts_per_page'] : 10
    );
  }
}