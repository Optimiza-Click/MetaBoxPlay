<?php
/*
Plugin Name: MetaBoxPlay
Plugin URI: http://optimizaclick.com
Description: Show all custom params in the wordpress page edit
Author: Departamento de Desarrollo Optimizaclick
Author URI: http://optimizaclick.com
Version: 1.0
Copyright: 2016 - 2017
*/

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'MetaBoxPlay' ) ) {
  class MetaBoxPlay {
      public function __construct() {
        add_action( 'admin_init', array( $this, 'AddMetaBoxCustomPostType' ) );
        add_filter( 'save_post', array( $this, 'SaveMetaBoxCustomPostType' ), 10, 2 );
      }

      public function AddMetaBoxCustomPostType() {
          add_meta_box("display_options_custom_post", "DisplayOptions", array( $this,"MetaBoxesDisplayCustomPostType"), "post", "normal", "low");
      }

      public function MetaBoxesDisplayCustomPostType() {
          global $post;

           $all_meta_boxes = get_post_meta($post->ID, '', false);

           foreach($all_meta_boxes as $meta_box => $meta_box_value) {  ?>
              <p>
              <label>Option <?= $meta_box ?></label><br />
              <textarea cols="150" rows="2" name="<?= $meta_box ?>" class="width99"><?= $meta_box_value[0] ?></textarea>
              </p>
           <?php }
      }

      public function SaveMetaBoxCustomPostType() {
            global $post;

            $all_meta_boxes = get_post_meta($post->ID, '', false);

            $disabled = array('store_id', '_edit_lock', '_vc_post_settings');


           foreach($all_meta_boxes as $single_metabox_key => $single_metabox_value) {
               if(!in_array($single_metabox_key,$disabled)){
                  $array_metas_options[] = $single_metabox_key;
               }

           }
              if ( $post ) {
                  foreach($_POST as $key => $value) {
                      if(in_array($key, $array_metas_options)) {
                           update_post_meta($post->ID, $key, $value);
                      }
                  }
              }
        }
    }
  $GLOBALS['MetaBoxPlay'] = new MetaBoxPlay();
}
