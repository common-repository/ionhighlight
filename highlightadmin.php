<?php
/*
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
function syntax_highlight_menu() {
    add_options_page('Syntax Highlight Settings', 'Syntax Highlighter', 
                      8, "ionhighlighter/syntaxhighlighter.php", 'syntax_highlight_options');
}

function syntax_highlight_options() {
    $lines = get_option('highlight_lines');
    $style = get_option('highlight_style');
    $default = '.hl-default {
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


?>
<div class="wrap">
    <h2>Syntax Highlight</h2>
    <form method="post" action="options.php">
        <?php echo wp_nonce_field('update-options'); ?>

        <p>Line Numbers</p>
        <input type="radio" name="highlight_lines" id="lines" value="yes" <?php echo ($lines == "yes") ? "checked" : ""; ?>/>
        <label for="lines">Yes</label>

        <input type="radio" name="highlight_lines" id="no_lines" value="no" <?php echo ($lines == "no") ? "checked" : ""; ?>/>
        <label for="no_lines">No</label><br />
    
        <p>Style</p>
        <textarea name="highlight_style" rows="20" cols="50"><?php echo (empty($style)) ? $default : $style; ?></textarea>


        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="highlight_lines,highlight_style" />
        <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
    </form>
</div>




<?php

}

?>
