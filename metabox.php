<?php
  add_action('admin_init', 'add_participants_meta', 2);

function add_participants_meta() {
    add_meta_box( 'dmv_metabox_field', 'Event Participants', 'dmv_participants_events', 'old_events');
    add_meta_box( 'dmv_post_metafield', 'Add Posts', 'dmv_add_posts', 'old_events');
}

function dmv_participants_events() {
  global $post;
  $value = get_post_meta($post->ID, 'dmv_repeater_meta_key', true);
           wp_nonce_field( 'dmv_repeater_nonce', 'dmv_repeater_nonce' );
  ?>

<table id="dmv_repeater_field">
<tbody>
  <?php
   if ( $value ) :
    foreach ( $value as $field ) {
  ?>
  <tr>
    <td>
      <input type="text"  placeholder="Name" name="NameItem[]" value="<?php if($field['NameItem'] != '') echo esc_attr( $field['NameItem'] ); ?>" /></td> 
      <td>
      <input type="text"  placeholder="Surname" name="SurnameItem[]" value="<?php if($field['SurnameItem'] != '') echo esc_attr( $field['SurnameItem'] ); ?>" /></td> 
      <td>
      <input type="url"  placeholder="Social network" name="SocialItem[]" value="<?php if($field['SocialItem'] != '') echo esc_attr( $field['SocialItem'] ); ?>" /></td> 
      <td ><a class="button remove-row" href="#1">Remove</a></td>
  </tr>
  <?php
  }
  else :
  // show a blank one
  ?>
  <tr>
    <td> 
      <input type="text" placeholder="Name" value="Name" name="NameItem[]" /></td>
      <td> 
      <input type="text" placeholder="Surname" value="Surname" name="SurnameItem[]" /></td>
      <td> 
      <input type="url" placeholder="Social network" value="Social network" name="SocialItem[]" /></td>
    <td><a class="button  cmb-remove-row-button button-disabled" href="#">Remove</a></td>
  </tr>
  <?php endif; ?>

  <!-- empty hidden one for jQuery -->
  <tr class="empty-row screen-reader-text">
    <td>
      <input type="text" placeholder="Name" name="NameItem[]"/></td>
      <td>
      <input type="text" placeholder="Surname" name="SurnameItem[]"/></td>
      <td>
      <input type="url" placeholder="Social network" name="SocialItem[]"/></td>
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
</tbody>
</table>
<p><a id="add-row" class="button" href="#">Add another</a></p>
<?php
}
add_action('save_post', 'custom_repeatable_meta_box_save');
function custom_repeatable_meta_box_save($post_id) {
  if ( ! isset( $_POST['dmv_repeater_nonce'] ) ||
  ! wp_verify_nonce( $_POST['dmv_repeater_nonce'], 'dmv_repeater_nonce' ) )
      return;

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return;

  if (!current_user_can('edit_post', $post_id))
      return;

  $old = get_post_meta($post_id, 'dmv_repeater_meta_key', true);
  $new = array();
  $name = $_POST['NameItem'];
  $surname = $_POST['SurnameItem'];
  $social = $_POST['SocialItem'];
  $count = count( $name );

   for ( $i = 0; $i < $count; $i++ ) {
        if ( $name[$i] != '' ) {
           $new[$i]['NameItem'] = stripslashes( strip_tags( $name[$i] ) );
        }
        if ( $surname[$i] != '' ) {
           $new[$i]['SurnameItem'] = stripslashes( $surname[$i] );
        }
        if ( $social[$i] != '' ) {
           $new[$i]['SocialItem'] = stripslashes( $social[$i] );
        }
  }
  if ( !empty( $new ) && $new != $old )
      update_post_meta( $post_id, 'dmv_repeater_meta_key', $new );
  elseif ( empty($new) && $old )
      delete_post_meta( $post_id, 'dmv_repeater_meta_key', $old );
}


function dmv_add_posts() {
  wp_nonce_field( plugin_basename(__FILE__), 'dmv_post_meta_nonce' );

  $baza = get_post_meta($post->ID, 'dmv_save_posts_event', true);
  $hh = get_posts( array(
    'post_type' => 'old_events',
  ));

  $args = get_posts( array(
    'post_type' => 'post',
  ));

  foreach($hh as $id) {
    $old_id = get_the_ID($id);
  }
 
  ?>
  <label for='states'>Add posts:</label>
    <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
  <?php
    foreach ( $args as $field ) {
  ?>
    <option option value="<?php echo $field->ID; ?>" <?php if( in_array($field->ID, get_post_meta($old_id, 'dmv_save_posts_event', true)))  echo 'selected'; ?>><?php echo $field->post_title ?></option>
  <?php
}
?>  
</select>
<?php
}

function dmv_post_save( $post_id ) {

  $baza = get_post_meta($post_id, 'dmv_save_posts_event', true);
	if ( ! wp_verify_nonce( $_POST['dmv_post_meta_nonce'], plugin_basename(__FILE__) ) )
		return;

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;

	if( ! current_user_can( 'edit_post', $post_id ) )
		return;
  
  if( isset( $_POST[ 'states' ] ) ) {
    update_post_meta( $post_id, 'dmv_save_posts_event', $_POST['states'] );
  } else {
    delete_post_meta( $post_id, 'dmv_save_posts_event');
  }

}

add_action('save_post', 'dmv_post_save');