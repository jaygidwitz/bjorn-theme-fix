<?php
/**
 * Theme custom metaboxes
 **/

// Custom metabox for pages title
function bjorn_pages_settings_box() {

    $screens = array( 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'bjorn_pages_settings_box',
            esc_html__( 'Page settings', 'bjorn' ),
            'bjorn_pages_settings_inner_box',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'bjorn_pages_settings_box' );

function bjorn_pages_settings_inner_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'bjorn_pages_settings_inner_box', 'bjorn_pages_settings_inner_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $value_page_class = get_post_meta( $post->ID, '_page_class_value', true );

  $value_page_notdisplaytitle = get_post_meta( $post->ID, '_page_notdisplaytitle_value', true );

  $value_page_sidebarposition = get_post_meta( $post->ID, '_page_sidebarposition_value', true );

  echo '<label for="page_class" style="width: 130px; display:inline-block;">';
       esc_html_e( "Page CSS class: ", 'bjorn' );
  echo '</label> ';
  echo '<input type="text" id="page_class" name="page_class" value="' . esc_attr( $value_page_class ) . '" style="width: 100%" />';

  $checked = '';
  if( $value_page_notdisplaytitle == true ) { 
    $checked = 'checked = "checked"';
  }
  echo '<p><input type="checkbox" id="page_notdisplaytitle" name="page_notdisplaytitle" '.$checked.' /> <label for="page_notdisplaytitle">'.esc_html__( "Don't display this page title (only show page content)", 'bjorn' ).'</label></p>';

  $selected_1 = '';
  $selected_2 = '';
  $selected_3 = '';
  $selected_4 = '';

  if($value_page_sidebarposition == 0) {
    $selected_1 = ' selected';
  }
  if($value_page_sidebarposition == "left") {
    $selected_2 = ' selected';
  }
  if($value_page_sidebarposition == "right") {
    $selected_3 = ' selected';
  }
  if($value_page_sidebarposition == "disable") {
    $selected_4 = ' selected';
  }
  
  echo '<p><label for="page_sidebarposition" style="display: inline-block; width: 150px;">'.esc_html__( "Page sidebar position: ", 'bjorn' ).'</label>';
  echo '<select name="page_sidebarposition" id="page_sidebarposition">
        <option value="0"'.$selected_1.'>'.esc_html__( "Use theme control panel settings", 'bjorn' ).'</option>
        <option value="left"'.$selected_2.'>'.esc_html__( "Left", 'bjorn' ).'</option>
        <option value="right"'.$selected_3.'>'.esc_html__( "Right", 'bjorn' ).'</option>
        <option value="disable"'.$selected_4.'>'.esc_html__( "Disable sidebar", 'bjorn' ).'</option>
    </select></p>';

}

function bjorn_page_settings_save_postdata( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['bjorn_pages_settings_inner_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['bjorn_pages_settings_inner_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'bjorn_pages_settings_inner_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['page_class'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_page_class_value', $mydata );

  // Sanitize user input.
  if(!isset($_POST['page_notdisplaytitle'])) $_POST['page_notdisplaytitle'] = false;
  
  $mydata = sanitize_text_field( $_POST['page_notdisplaytitle'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_page_notdisplaytitle_value', $mydata );

  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['page_sidebarposition'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_page_sidebarposition_value', $mydata );

}
add_action( 'save_post', 'bjorn_page_settings_save_postdata' );

// BLOG POST META BOX
function bjorn_post_settings_box() {

    $screens = array( 'post' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'bjorn_post_settings_box',
            esc_html__( 'Post settings', 'bjorn' ),
            'bjorn_post_settings_inner_box',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'bjorn_post_settings_box' );

function bjorn_post_settings_inner_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'bjorn_post_settings_inner_box', 'bjorn_post_settings_inner_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $value_post_featured = get_post_meta( $post->ID, '_post_featured_value', true );
  $value_post_editorpick = get_post_meta( $post->ID, '_post_editorpick_value', true );
  $value_post_disable_featured_image = get_post_meta( $post->ID, '_post_disable_featured_image_value', true );
  $value_post_sidebarposition = get_post_meta( $post->ID, '_post_sidebarposition_value', true );

  $checked = '';
  if( $value_post_featured == true ) { 
    $checked = 'checked = "checked"';
  }
  echo '<p><input type="checkbox" id="post_featured" name="post_featured" '.$checked.' /> <label for="post_featured">'.esc_html__( "Show this post in Homepage Posts Slider (Featured post)", 'bjorn' ).'</label></p>';

  $checked = '';
  if( $value_post_editorpick == true ) { 
    $checked = 'checked = "checked"';
  }
  echo '<p><input type="checkbox" id="post_editorpick" name="post_editorpick" '.$checked.' /> <label for="post_editorpick">'.esc_html__( "Show this post in Editors Pick Block (Editor Pick post)", 'bjorn' ).'</label></p>';


  $checked = '';
  if( $value_post_disable_featured_image == true ) { 
    $checked = 'checked = "checked"';
  }
  echo '<p><input type="checkbox" id="post_disable_featured_image" name="post_disable_featured_image" '.$checked.' /> <label for="post_disable_featured_image">'.esc_html__( "Don't show featured image on single post page", 'bjorn' ).'</label></p>';

  $value_post_socialshare_disable = get_post_meta( $post->ID, '_post_socialshare_disable_value', true );

  $checked = '';
  if( $value_post_socialshare_disable == true ) { 
    $checked = 'checked = "checked"';
  }

  echo '<p><input type="checkbox" id="post_socialshare_disable" name="post_socialshare_disable" '.$checked.' /> <label for="post_socialshare_disable">'.esc_html__( "Disable social share buttons on this post", 'bjorn' ).'</label></p>';

  $selected_1 = '';
  $selected_2 = '';
  $selected_3 = '';
  $selected_4 = '';

  if($value_post_sidebarposition == 0) {
    $selected_1 = ' selected';
  }
  if($value_post_sidebarposition == "left") {
    $selected_2 = ' selected';
  }
  if($value_post_sidebarposition == "right") {
    $selected_3 = ' selected';
  }
  if($value_post_sidebarposition == "disable") {
    $selected_4 = ' selected';
  }
  
  echo '<p><label for="post_sidebarposition" style="display: inline-block; width: 150px;">'.esc_html__( "Post sidebar position: ", 'bjorn' ).'</label>';
  echo '<select name="post_sidebarposition" id="post_sidebarposition">
        <option value="0"'.$selected_1.'>'.esc_html__( "Use theme control panel settings", 'bjorn' ).'</option>
        <option value="left"'.$selected_2.'>'.esc_html__( "Left", 'bjorn' ).'</option>
        <option value="right"'.$selected_3.'>'.esc_html__( "Right", 'bjorn' ).'</option>
        <option value="disable"'.$selected_4.'>'.esc_html__( "Disable sidebar", 'bjorn' ).'</option>
    </select></p>';

}

function bjorn_post_settings_save_postdata( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['bjorn_post_settings_inner_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['bjorn_post_settings_inner_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'bjorn_post_settings_inner_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  if(!isset($_POST['post_featured'])) $_POST['post_featured'] = false;
  
  $mydata = sanitize_text_field( $_POST['post_featured'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_post_featured_value', $mydata );

  // Sanitize user input.
  if(!isset($_POST['post_editorpick'])) $_POST['post_editorpick'] = false;
  
  $mydata = sanitize_text_field( $_POST['post_editorpick'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_post_editorpick_value', $mydata );

  // Sanitize user input.
  if(!isset($_POST['post_disable_featured_image'])) $_POST['post_disable_featured_image'] = false;
  
  $mydata = sanitize_text_field( $_POST['post_disable_featured_image'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_post_disable_featured_image_value', $mydata );
  
  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['post_sidebarposition'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_post_sidebarposition_value', $mydata );

  if(!isset($_POST['post_socialshare_disable'])) $_POST['post_socialshare_disable'] = false;
  
   // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['post_socialshare_disable'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_post_socialshare_disable_value', $mydata );

}
add_action( 'save_post', 'bjorn_post_settings_save_postdata' );

// CMB2 Metaboxes

function bjorn_register_post_format_settings_metabox() {

  // Start with an underscore to hide fields from custom fields list
  $prefix = '_bjorn_';
  /**
   * Metabox to be displayed on a single page ID
   */
  $cmb_post_header_settings = new_cmb2_box( array(
    'id'           => $prefix . 'post_header_settings_metabox',
    'title'        => esc_html__( 'Header Background', 'bjorn' ),
    'object_types' => array( 'post', 'page' ), // Post type
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
  ) );
  $cmb_post_header_settings->add_field( array(
    'name'         => esc_html__( 'Header Background image', 'bjorn' ),
    'desc'         => esc_html__( 'You can display fullwidth image background in your post/page header with post title. Use wide image here.', 'bjorn' ),
    'id'           => $prefix . 'header_image',
    'type'         => 'file',
    'options' => array(
        'url' => false, // Hide the text input for the url
        'add_upload_file_text' => 'Select or Upload Image' 
    ),
    'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
  ) );
  $cmb_post_format_settings = new_cmb2_box( array(
    'id'           => $prefix . 'post_format_settings_metabox',
    'title'        => esc_html__( 'Post Formats options', 'bjorn' ),
    'object_types' => array( 'post' ), // Post type
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
  ) );

  $cmb_post_format_settings->add_field( array(
    'name'         => wp_kses_post(__( 'Gallery images<br> (for <i>Gallery</i> post format).', 'bjorn' )),
    'desc'         => esc_html__( 'Use this field to add your images for gallery in Gallery post format. Use SHIFT/CTRL keyboard buttons to select multiple images.', 'bjorn' ),
    'id'           => $prefix . 'gallery_file_list',
    'type'         => 'file_list',
    'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
  ) );

  $cmb_post_format_settings->add_field( array(
    'name' => wp_kses_post(__( 'Video url<br> (for <i>Video</i> post format)', 'bjorn' )),
    'desc' => esc_html__( 'Enter a Youtube, Vimeo, Flickr, TED or Vine video page url for Video post format.', 'bjorn' ),
    'id'   => $prefix . 'video_embed',
    'type' => 'oembed',
  ) );

  $cmb_post_format_settings->add_field( array(
    'name' => wp_kses_post(__( 'Audio url<br> (for <i>Audio</i> post format)', 'bjorn' )),
    'desc' => esc_html__( 'Enter a SoundCloud, Mixcloud, Rdio or Spotify audio page url for Audio post format.', 'bjorn' ),
    'id'   => $prefix . 'audio_embed',
    'type' => 'oembed',
  ) );
  
}
add_action( 'cmb2_init', 'bjorn_register_post_format_settings_metabox' );
