<?php
/*
Plugin Name: WP tags to Trendfo
Plugin URI: http://trendfo.com/wordpress
Description: Simple plugin to convert Wordpress 2.3's tags to Trendfo ('http://trendfo.com') links.
Version: 0.01
Author: Glen Stansberry
Author URI: http://trendfo.com/
*/

/*

    Copyright 2008 by Glen Stansberry <blogfuse@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*

Date		Rev	Modification
11/17/08	0.1	Initital version	
*/

$trendfotags_version = 0.01;

$tag_url = "http://trendfo.com";

$tag_start = "\n<!-- start wp-tags-to-trendfo $trendfotags_version -->\n";
$tag_end = "\n<!-- end wp-tags-to-trendfo -->\n";

set_magic_quotes_runtime(0);

function trendfotags_content ($text) {

	$include_footer = get_option('trendfotags_footer');
	$include_feed = get_option('trendfotags_feed');
	$include_home = get_option('trendfotags_home');

	if ($include_footer && is_feed() && !$include_feed) {
		$include_footer = false;
	}	
	
	if ($include_footer && is_home() && !$include_home) {
		$include_footer = false;
	}	
	
	if ($include_footer && (!is_feed() || is_feed() && $include_feed)) {
		return $text.trendfotags_get_tags_links();
	} else {
		return $text;
	}

}

function trendfotags_get_tags_links() {

	global $tag_start,$tag_end;

	$new_window = get_option('trendfotags_new_window');

	$tags = get_the_tags();

	$tag_text = get_option('trendfotags_label')." ";
	
	$count=0;

	$tag_count=count($tags);

	if (is_array($tags)) {
		foreach($tags as $tag) {
			$count++;
			$link = trendfotags_get_link($tag->name,$new_window);
			$tag_text = $tag_text.($count>1?', ':'').$link;
		}
		$tag_links = "\n<p class='trendfo-tags'>".$tag_text."</p>\n";
	} elseif ($tags->name != "") {
		$tag_links = "\n<p class='trendfo-tags'>".$tag_text.trendfotags_get_link($tags->name,$new_window)."</p>\n";
	} else {
		$tag_links = "";
	}

	return $tag_start.$tag_links.$tag_end;
}

function trendfotags_get_link($tag,$new_window = false) {
	global $tag_url;

	$rel_nofollow = get_option('trendfotags_rel_nofollow');

	$link_rel = 'tag';

	if ($rel_nofollow) {
		$link_rel .= ',nofollow';
	}
	$encoded_tag = trendfo_encode_slug($tag);

	$target = $new_window?'_blank':'_self';

	$link = "<a class='trendfo-link' href='$tag_url/$encoded_tag' rel='$link_rel' target='$target'>$tag</a>";
	
	return $link;
}

function trendfotags_options_menu() {

	?>
	<div class="wrap">
	<h2>Tags to trendfo Settings</h2>
	<form method="post" action="options.php">
	 <!-- <?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo attribute_escape($_GET['page']); ?>"> -->
	<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
 <tr>
 	<th scope="row" valign="top">Trendfo Tags label:</th>
 	<td>
	<input id="trendfotags_label" type="text" name="trendfotags_label" value="<?php echo get_option('trendfotags_label'); ?>" />
  	 	<label for="inputid">Text that will display in front of the tags</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Open Trendfo links in a new window?</th>
 	<td>
	<input id=trendfotags_new_window" type="checkbox" name="trendfotags_new_window" <?php echo get_option('trendfotags_new_window')?'checked=checked':''; ?> /> 
  	 	<label for="trendfotags_new_window">Check this box to open links to trendfo in a new window.</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Include tags in post footer?</th>
 	<td>
	<input id="trendfotags_footer" type="checkbox" name="trendfotags_footer" <?php echo get_option('trendfotags_footer')?'checked=checked':''; ?> /> 
  	 	<label for="trendfotags_footer">If you want to include the links in any other location on the page than the footer, disable this checkbox and add a call to <code>trendfotags_get_tags_links()</code> somewhere in the main wordpress 'loop'.</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Add "nofollow" to the links rel attribute?</th>
 	<td>
	<input id=trendfotags_rel_nofollow" type="checkbox" name="trendfotags_rel_nofollow" <?php echo get_option('trendfotags_rel_nofollow')?'checked=checked':''; ?> /> 
  	 	<label for="trendfotags_rel_nofollow">Check this box to add "nofollow" to the generated links rel attribute (they already have the value 'tag').</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Include tags in RSS feed?</th>
 	<td>
	<input id=trendfotags_feed" type="checkbox" name="trendfotags_feed" <?php echo get_option('trendfotags_feed')?'checked=checked':''; ?> /> 
  	 	<label for="trendfotags_feed">Check this box to include the tags in the RSS feed.</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Include tags on home page?</th>
 	<td>
	<input id=trendfotags_home" type="checkbox" name="trendfotags_home" <?php echo get_option('trendfotags_home')?'checked=checked':''; ?> /> 
  	 	<label for="trendfotags_home">Check this box to include the tags on the home page.</label>
 	</td>
 </tr>
</table>


        <p class="submit">
        <input type="submit" name="Submit" value="Save Changes" />
        </p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="trendfotags_label,trendfotags_footer,trendfotags_new_window,trendfotags_rel_nofollow,trendfotags_feed,trendfotags_home"/>
	</form>
	</div>
	<?php 

}

function trendfotags_menu(){
    add_options_page('Tags to Trendfo', 'Tags to Trendfo', 8, __FILE__, 'trendfotags_options_menu');
}

// clean up slug for linking to ourselves
function trendfo_encode_slug($slug){
	$slug = trendfo_encode_slugtitle($slug);
    
    $slug = str_replace(array(" ", "+", "-"), array("_", "_", "_"), $slug); // TODO: do these work for eastern languages?
    $slug = urlencode($slug);
	$slug = preg_replace("/[^a-zA-Z0-9.]/u", "_", $slug);
	return $slug;
	}
		
// Clean titles received from feeds for proper displaying
// translate any %XX characters to their proper equivalents
// replace any non-alphanumeric characters to spaces
// remove extra spaces
function trendfo_encode_slugtitle($slugtitle){
	$slugtitle = urldecode($slugtitle);
	$slugtitle = preg_replace("/[^a-zA-Z0-9.]/u", " ", $slugtitle);
	$slugtitle = preg_replace("/ +/u", " ", $slugtitle);
	trim($slugtitle);

	// capitalize the first letter of all words
	$slugtitle = mb_ucwords($slugtitle);

	// lowercase certain words
	// (some characters will break this list:  / \ | or any whitespace character
	$lowercase_words = "a|an|and|as|at|but|by|en|for|from|if|in|is|of|on|or|the|to|v|v\.|via|vs|vs\.";
	$slugtitle = preg_replace("/\b($lowercase_words)\b/ei", "strtolower('$1')", $slugtitle);

	// exceptions to the rule, specify exact case
	$exceptions = array("ipod", "iphone");
	$except_rep = array("iPod", "iPhone");
	$slugtitle = str_ireplace($exceptions, $except_rep, $slugtitle);

	return $slugtitle;
}


function trendfotags_checkupgrade() {
	global $trendfotags_version;

	$last_version = get_option('trendfotags_version');
	$current_version = $trendfotags_version;
	if ($current_version > $last_version) {
		echo "<!-- upgrading to $trendfotags_version -->\n";
		$label = get_option('trendfotags_label');
		echo "<!-- oldlabel = $label -->\n";
		if (substr($label,-1) <> ":") {
			$newlabel = $label.":";
			update_option('trendfotags_label',$newlabel);
		}
		update_option('trendfotags_version',$trendfotags_version);
	}

}

function trendfotags_activate()
{
        // Let's add some options
	// add_option('trendfotags_label', 'Trendfo Tags');
}

function trendfotags_deactivate()
{
        // Clean up the options
	delete_option('trendfotags_label');
	delete_option('trendfotags_footer');
	delete_option('trendfotags_new_window');
	delete_option('trendfotags_rel_nofollow');
	delete_option('trendfotags_feed');
	delete_option('trendfotags_home');
}

add_option('trendfotags_version', $trendfotags_version);
add_option('trendfotags_label', 'Trendfo Tags:');
add_option('trendfotags_footer', true);
add_option('trendfotags_new_window', false);
add_option('trendfotags_rel_nofollow', false);
add_option('trendfotags_feed', true);
add_option('trendfotags_home', true);
add_filter('the_content', 'trendfotags_content');
add_action('admin_menu', 'trendfotags_menu');

// register_activation_hook( __FILE__, 'trendfotags_activate' );

add_action('activate_wp-tags-to-trendfo/wp-tags-to-trendfo.php',
	'trendfotags_activate');
add_action('deactivate_wp-tags-to-trendfo/wp-tags-to-trendfo.php',
	'trendfotags_deactivate');

// add_action('wp_head','trendfotags_checkupgrade');
add_action('plugins_loaded','trendfotags_checkupgrade');

// copied from the comments for ucwords on php.net and modified
if(!function_exists('mb_ucwords')){
    function mb_ucwords($str) {
        $separator = array(" ","-","+","\t","\n");
        
        $str = trim($str);
        foreach($separator as $s){
            $word = explode($s, $str);
            
            $return = "";
            foreach ($word as $val){
                $return .= $s . mb_strtoupper($val{0}) . mb_substr($val,1);
            }
            $str = mb_substr($return, 1);
        }
        
        return $str;
    }
}

?>
