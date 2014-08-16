<?php
/*
Plugin Name: Big Social Share Buttons
Plugin URI:
Version: 1.02
Author: <a href="http://www.seo101.net">Seo101</a>
Description: Adds 3 cool big social share buttons to your posts and pages (Facebook, Twitter & Google)
License: GPLv2 a

*/
if (!class_exists("BigSocialShareButtons")) {
	class BigSocialShareButtons {
		function BigSocialShareButtons() { //constructor remains empty

		}
		function addHeaderCode() {
			?>
			<?php

		}
		function addContent($content = '') {
 	 	  global $wp_query;
	 	  global $post;
			$excludelist = ',' . str_replace(' ', '',get_option('bssb_global_excludelist')) . ',';
			$postid = ',' . $post->ID . ',';
			$excludepost = 'false';
			if (strpos($excludelist,$postid) !== false) {
				$excludepost = 'true';
			}

		  if (is_singular() && $excludepost=='false' ) {
			global $url;
			if (!$url)
				$url = get_permalink($post->ID);

			$original = $content;
			$content = '';
			$content .= "<STYLE type='text/css'>

.bssb-buttons {
display:inline-block;
float:left;
padding-bottom:7px;
}

.bssb-buttons > .facebook{
font-size: 13px;
border-radius: 2px;
margin-right: 4px;
background: #2d5f9a;
position: relative;
display: inline-block;
cursor: pointer;
height: 41px;
width: 134px;
color: #FFF;
line-height:41px;
background: url(" . plugins_url( 'facebook.png', __FILE__ ) . ") no-repeat 10px 12px #2D5F9A;
padding-left: 35px;
}

.bssb-buttons > .twitter{
font-size: 13px;
border-radius: 2px;
margin-right: 7px;
background: #00c3f3;
position: relative;
display: inline-block;
cursor: pointer;
height: 41px;
width: 116px;
color: #FFF;
line-height:41px;
background: url(" . plugins_url( 'twitter.png', __FILE__ ) . ") no-repeat 10px 14px #00c3f3;
padding-left:37px;
}

.bssb-buttons > .google {
font-size: 13px;
border-radius: 2px;
margin-right: 7px;
background: #eb4026;
position: relative;
display: inline-block;
cursor: pointer;
height: 41px;
width: 116px;
color: #FFF;
line-height:41px;
background: url(" . plugins_url( 'google.png', __FILE__ ) . ") no-repeat 10px 11px #eb4026;
padding-left:37px;
}


 </STYLE><script>
function openFacebook(url, title, descr, winWidth, winHeight) {
	var winTop = (screen.height / 2) - (winHeight / 2);
	var winLeft = (screen.width / 2) - (winWidth / 2);
	window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&u=' + url + '&p[images][0]=' + '', 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}
function openTwitter(url, title, descr, winWidth, winHeight) {
	var winTop = (screen.height / 2) - (winHeight / 2);
	var winLeft = (screen.width / 2) - (winWidth / 2);
	window.open('https://twitter.com/intent/tweet?text=' + title + '&url=' + url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}
function openGooglePlus(url, title, descr, winWidth, winHeight) {
	var winTop = (screen.height / 2) - (winHeight / 2);
	var winLeft = (screen.width / 2) - (winWidth / 2);
	window.open('https://plus.google.com/share?text=' + title + '&url=' + url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

</script>";


			$return = '
			    <div class="bssb-buttons">
	<a class="facebook" onclick="javascript:openFacebook(\'' . $url . '\',\'' . esc_html($title) . '\', \'Facebook share popup\',520,350)" href="javascript:return(0);">Share on Facebook</a>
	<a class="twitter" onclick="javascript:openTwitter(\'' . $url . '\', \'' . urlencode(html_entity_decode(get_the_title())) . '\', \'Twitter share popup\', 520, 350)" href="javascript:return(0)">Tweet on Twitter</a>
	<a class="google" onclick="javascript:openGooglePlus(\'' . $url . '\', \'' . urlencode(html_entity_decode(get_the_title())) . '\', \'Google share popup\', 520, 350)" href="javascript:return(0)">Share on Google</a>
			    </div>
			    <div style="clear:both;:"></div>
			    ';

			if ( get_option('bssb_locations')=='both' || get_option('bssb_locations')=='top' ) {
				$content .= $return;
			}
			$content .= $original;
			if ( get_option('bssb_locations')=='both' || get_option('bssb_locations')=='bottom' ) {
				$content .= $return;
			}
		  }

		  return $content;
		}

		function authorUpperCase($author = '') {
			return strtoupper($author);
		}

	}

} //End Class BigSocialShareButtons


if (class_exists("BigSocialShareButtons")) {
	$dl_pluginSeriesBigSocialShareButtons = new BigSocialShareButtons();
}
//Actions and Filters
if (isset($dl_pluginSeriesBigSocialShareButtons )) {
	//Actions
	//Filters
	add_filter('the_content', array(&$dl_pluginSeriesBigSocialShareButtons , 'addContent'));
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'big_social_share_buttons_install');

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'big_social_share_buttons_remove' );

function big_social_share_buttons_install() {
/* Creates new database field */
add_option("bssb_locations", 'both', '', 'yes');
add_option("bssb_global_excludelist", '', '', 'yes');
}

function big_social_share_buttons_remove() {
/* Deletes the database field */
}


// Add settings link on plugin page
function big_social_share_buttons_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=big-social-share-buttons.php">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'big_social_share_buttons_settings_link' );



if ( is_admin() ){

/* Call the html code */
add_action('admin_menu', 'big_social_share_buttons_admin_menu');

function big_social_share_buttons_admin_menu() {
add_options_page('Big Social Share Buttons', 'Big Social Share Buttons', 'administrator',
'big-social-share-buttons', 'big_social_share_buttons_page');
}
}

?>
<?php
function big_social_share_buttons_page() {
?>
<div>
<h2>Big Social Share Buttons - Settings</h2>
<BR><BR>
<p>
<strong><font size=4 color="#DF0101">10 times more Google Adsense revenue?: </font></strong><a href="http://www.seo101.net/go/ctrthemebssb/" target="_top">Try CTRtheme</a>, the best selling Wordpress Adsense theme.
</p>
<BR>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table width="850">
<tr valign="top">
<th width="250" scope="row">Location of the Big Share Buttons:</th>
<td width="600">
<select name="bssb_locations" id="bssb_locations">
<option value="top" <?php if (get_option('bssb_locations')=='top') echo ' selected ' ?> >Top</option>
<option value="bottom" <?php if (get_option('bssb_locations')=='bottom') echo ' selected ' ?> >Bottom</option>
<option value="both" <?php if (get_option('bssb_locations')=='both') echo ' selected ' ?> >Both</option>
</select>
</td>
</tr>
<tr>
<th width="250" scope="row">ID's of posts and pages where NO share buttons should appear: (seperate with a comma ',')</th>
	<td width="600px">
			<input name="bssb_global_excludelist" id="bssb_global_excludelist" type="text" style="width:300px" value="<?php echo str_replace(' ', '',get_option('bssb_global_excludelist')); ?>" />
	</td>
</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="bssb_locations, bssb_global_excludelist" />

<p>
<input type="submit" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
<BR><BR>
<?php
}
?>