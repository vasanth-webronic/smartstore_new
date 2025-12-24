<?php

function custom_uam_settings() {

  
  // Define (at least) one section for our fields
  add_settings_section(
    // Unique identifier for the section
    'custom_uam_settings_section',
    // Section Title
    __( 'Plugin Settings Section', 'custom-uam' ),
    // Callback for an optional description
    'custom_uam_settings_section_callback',
    // Admin page to add section to
    'custom-uam'
  );

  // Input Text Field
  add_settings_field(
    // Unique identifier for field
    'custom_uam_settings_input_text',
    // Field Title
    __( 'Role Name', 'custom-uam'),
    // Callback for field markup
    'custom_uam_settings_text_input_callback',
    // Page to go on
    'custom-uam',
    // Section to go in
    'custom_uam_settings_section');

  register_setting(
    'custom_uam_settings',
    'custom_uam_settings'
  );

}
//add_action( 'admin_init', 'custom_uam_settings' );

function custom_uam_settings_section_callback() {

  esc_html_e( 'Plugin settings section description', 'custom-uam' );

}

function custom_uam_settings_text_input_callback() {

  $options = get_option( 'custom-uam_settings' );

	$text_input = '';
	if( isset( $options[ 'text_input' ] ) ) {
		$text_input = esc_html( $options['text_input'] );
	}

  echo '<input type="text" id="custom-uam_customtext" name="custom-uam_settings[text_input]" value="' . $text_input . '" />';

}

function custom_uam_settings_textarea_callback() {

  $options = get_option( 'custom-uam_settings' );

	$textarea = '';
	if( isset( $options[ 'textarea' ] ) ) {
		$textarea = esc_html( $options['textarea'] );
	}

  echo '<textarea id="custom-uam_settings_textarea" name="custom-uam_settings[textarea]" rows="5" cols="50">' . $textarea . '</textarea>';

}

function custom_uam_settings_checkbox_callback( $args ) {

  $options = get_option( 'custom-uam_settings' );

  $checkbox = '';
	if( isset( $options[ 'checkbox' ] ) ) {
		$checkbox = esc_html( $options['checkbox'] );
	}

	$html = '<input type="checkbox" id="custom-uam_settings_checkbox" name="custom-uam_settings[checkbox]" value="1"' . checked( 1, $checkbox, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="custom-uam_settings_checkbox">' . $args['label'] . '</label>';

	echo $html;

}

function custom_uam_settings_radio_callback( $args ) {

  $options = get_option( 'custom-uam_settings' );

  $radio = '';
	if( isset( $options[ 'radio' ] ) ) {
		$radio = esc_html( $options['radio'] );
	}

	$html = '<input type="radio" id="custom-uam_settings_radio_one" name="custom-uam_settings[radio]" value="1"' . checked( 1, $radio, false ) . '/>';
	$html .= ' <label for="custom-uam_settings_radio_one">'. $args['option_one'] .'</label> &nbsp;';
	$html .= '<input type="radio" id="custom-uam_settings_radio_two" name="custom-uam_settings[radio]" value="2"' . checked( 2, $radio, false ) . '/>';
	$html .= ' <label for="custom-uam_settings_radio_two">'. $args['option_two'] .'</label>';

	echo $html;

}

function custom_uam_settings_select_callback( $args ) {

  $options = get_option( 'custom-uam_settings' );

  $select = '';
	if( isset( $options[ 'select' ] ) ) {
		$select = esc_html( $options['select'] );
	}

  $html = '<select id="custom-uam_settings_options" name="custom-uam_settings[select]">';

	$html .= '<option value="option_one"' . selected( $select, 'option_one', false) . '>' . $args['option_one'] . '</option>';
	$html .= '<option value="option_two"' . selected( $select, 'option_two', false) . '>' . $args['option_two'] . '</option>';
	$html .= '<option value="option_three"' . selected( $select, 'option_three', false) . '>' . $args['option_three'] . '</option>';

	$html .= '</select>';

	echo $html;

}
