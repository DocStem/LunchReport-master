<?php
/**
 * Menu.php file
 * Required
 * - Menu entries for the Lunch Report Plugin
 *
 * @package Lunch Report Plugin
 */



// Custom Lunch Report programs
//if ( $RosarioModules['Students'] )
//{
	// Place Attendance Summary program before Utilities separator.
	$utilities_pos = array_search( 1, array_keys( $menu['Students']['admin'] ) );

	$menu['Students']['admin'] = array_merge(
	    array_slice( $menu['Students']['admin'], 0, $utilities_pos ),
	    array( 'LunchReport/LunchReport.php' => _( 'Lunch Report' ) ),
	    array_slice( $menu['Students']['admin'], $utilities_pos )
	);

	$menu['Students']['teacher'] = array_merge(
	    array_slice( $menu['Students']['admin'], 0, $utilities_pos ),
	    array( 'LunchReport/LunchReport.php' => _( 'Lunch Report' ) ),
	    array_slice( $menu['Students']['admin'], $utilities_pos )
	);
//}
