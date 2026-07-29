<?php

// population order form
add_filter( 'gform_pre_render_2', 'custom_populate_orderform' );
add_filter( 'gform_pre_validation_2', 'custom_populate_orderform' );
add_filter( 'gform_pre_submission_filter_2', 'custom_populate_orderform' );
add_filter( 'gform_admin_pre_render_2', 'custom_populate_orderform' );

function custom_populate_orderform( $form ) {

    foreach ( $form['fields'] as &$field ) {

		if ($field->type == 'select' && strstr( $field->cssClass, 'populate-locations' )) {
	        $posts = get_posts(array(
            	'numberposts'	=> -1,
            	'post_type'		=> 'location',
            	'meta_key'		=> 'online_ordering',
            	'meta_value'	=> '1'
            ));
	        $choices = array();

	        foreach ( $posts as $post ) {
               $choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title . '|' . get_post_meta($post->ID, 'email', true) );
	        }

	        $field->placeholder = 'Select a location';
	        $field->choices = $choices;
		} else if ($field->type == 'select' && strstr( $field->cssClass, 'populate-dates' )) {
			$choices = array();

			foreach( range(0,6) as $cnt ){
				$day = strtolower( date('l j F',strtotime( "today + $cnt day") ) );
                if($cnt == 0) {
                    $time = strtotime("15:15");
                    $now = strtotime("now");
                    $diff = $time - $now;
                    if ($diff > (60*15) && (strlen(strstr($day,'saturday')) == 0 && strlen(strstr($day,'sunday')) == 0)){
    				    $choices[] = array( 'text' => $day, 'value' => $day );
                    }
                } else {
                    if (strlen(strstr($day,'saturday')) == 0 && strlen(strstr($day,'sunday')) == 0){
    				    $choices[] = array( 'text' => $day, 'value' => $day );
                    }
                }
			}

	        $field->placeholder = 'Select a day';
	        $field->choices = $choices;
		} else if ($field->type == 'select' && strstr( $field->cssClass, 'populate-times' )) {
			$choices = array();

            $range = range(strtotime("07:00"),strtotime("15:30"),15*60);
            $now = strtotime("now");

            foreach($range as $time){
                $diff = $time - $now;
                if ($diff > (60*15)){
                    $choices[] = array( 'text' => date('g:i A',$time), 'value' => date('g:i A',$time) );
                }
            }

	        $field->placeholder = 'Select a time';
	        $field->choices = $choices;
		} else {
			continue;
		}
    }

    return $form;
}



?>
