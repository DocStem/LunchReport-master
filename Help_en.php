<?php
/**
 * English Help texts
 *
 * Texts are organized by:
 * - Module
 * - Profile
 *
 * Please use this file as a model to translate the texts to your language
 * The new resulting Help file should be named after the following convention:
 * Help_[two letters language code].php
 *
 * @author Doc Stem
 *
 * @uses Heredoc syntax
 * @see  http://php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
 *
 * @package Reports
 * @subpackage Help
 */

// LUNCH REPORT ---.
if ( User( 'PROFILE' ) === 'admin' ) :

	$help['Reports/LunchReport.php'] = <<<HTML
<p>
	<i>Lunch Report</i> allows you to run the preconfigured Lunch Reports that come from the RosarioSIS system.
</p>
<p>
	
</p>
HTML;


endif;
