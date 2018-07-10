<?php
function disable_acf_load_field( $field ) {

	$field['disabled'] = 1;
	return $field;

}
add_filter('acf/load_field/name=lien_txt', 'disable_acf_load_field');
