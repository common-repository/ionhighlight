<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
Plugin Name: Ion Snytax Highlighter
Description: Provides functionality to perform syntax highlighting for different file formats.
Version: 1.3
Author: John Ciacia
Author URI: http://www.johnciacia.com/


Copyright 2009  John Ciacia  (email : john@ionicware.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/** 
 * If the PEAR package Text_Highlighter is not installed there is a version
 * of the package included with this plugin. $pear_dir is the location of 
 * this package.
 */
$pear_dir = ABSPATH . 'wp-content/plugins/ionhighlight';

if( is_dir( $pear_dir ) )
    ini_set( "include_path", ini_get( "include_path" ) . PATH_SEPARATOR . $pear_dir );

/**
 * With Text_Highlighter it is possible to create syntax highlighted 
 * versions of different file formats. The supportedfile formats are: 
 * ABAP, C++, CSS, DTD, HTML, Java, JavaScript, MySQL, Perl, PHP, Python, 
 * Ruby, sh, SQL, VBScript, and XML.
 */
require_once( "Text/Highlighter.php" );
/**
 * There are different options for getting the results of the highlighting, 
 * through the use of renderers. This plugin uses the HTML renderer to
 * output the content using span-tags containing a CSS class name. 
 */
require_once( "Text/Highlighter/Renderer/Html.php" );

require_once( "highlightadmin.php" );
/**
 * Create our custom highlighter, and then add the filters.
 */
$Highlight = new Highlight();

add_shortcode('code', array(&$Highlight , 'parse'));

if(remove_filter('the_content','do_shortcode', 11))
    add_filter('the_content','do_shortcode',9);
    
remove_filter( 'the_content', 'convert_smilies');
//remove_filter('the_content', 'wptexturize');
//remove_filter('the_content', 'convert_chars');
//remove_filter('the_content', 'wpautop');

add_action('admin_menu', 'syntax_highlight_menu');
add_action('wp_head', 'highlight_custom_style');


/**
 * @todo: Perhaps the syntax should not be highlighted for mobile devices
 * @todo: Add a "copy to clipboard" function
 * @todo: Add a "download syntax" function
 * @todo: Alternating line styles
 * @todo: Add a "startat" parameter
 * @todo: Add a "hl" parameter to highlight certain lines
 * @todo: Add a "valid languages" array to reject languages not suported
 */

class Highlight
{
    var $renderer = NULL;

    function __construct() {
        /**
         * @todo: Allow the user to set the tab size
         */
        $numbers = ( get_option('highlight_lines') == "yes" ) ? HL_NUMBERS_TABLE : FALSE;
        $this->renderer =& new Text_Highlighter_Renderer_Html(
                               array( "numbers" => $numbers,
                                      "tabsize" => 4 ) );
    }

    function parse($atts, $content = null) {
		extract(shortcode_atts(array(
			'lang' => 'PHP'
		), $atts));

		
    	if(is_single()) {
    		$renderer = $this->renderer;
    	} else {
        	$renderer =& new Text_Highlighter_Renderer_Html(array( "tabsize" => 4 ) );
    	}

        $hl =& Text_Highlighter::factory( $lang );
        $hl->setRenderer( $renderer );
        $output = '<div class="hl-container">'
                 . $hl->highlight( $content )
                 . '</div>';
        return $output;    
    }

}



function highlight_custom_style($default) {
    $default = '
.hl-default {
    color: Black;
}
.hl-code {
    color: Gray;
}
.hl-brackets {
    color: Olive;
}
.hl-comment {
    color: Orange;
}
.hl-quotes {
    color: Darkred;
}
.hl-string {
    color: Red;
}
.hl-identifier {
    color: Blue;
}
.hl-builtin {
    color: Teal;
}
.hl-reserved {
    color: Green;
}
.hl-inlinedoc {
    color: Blue;
}
.hl-var {
    color: Darkblue;
}
.hl-url {
    color: Blue;
}
.hl-special {
    color: Navy;
}
.hl-number {
    color: Maroon;
}
.hl-inlinetags {
    color: Blue;
}
.hl-main {}

.hl-gutter {
    background-color: #999999;
    color: #000;
}
.hl-table {}

.hl-container {
	font-family: courier;
    font-size: 12px;
    max-height: 200px;
    background-color: #F9FBFC;
    border: 1px solid #C3CED9;
    padding: 8px;
    margin-bottom: 5px;
    overflow: auto;
    text-align: left;
}';
    $style = get_option('highlight_style');
    echo '<style type="text/css">';
    echo (empty($style)) ? $default : $style;
    echo "</style>\n";
}

?>
