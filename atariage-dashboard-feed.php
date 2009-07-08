<?php
/*
Plugin Name: AtariAge Dashboard Feed
Plugin URI: http://www.doc4design.com/plugins/atariage-dashboard-feed
Description: Add the AtariAge RSS Feed to your WordPress Dashboard
Version: 1.2
Author: Doc4
Author URI: http://www.doc4design.com
*/

/******************************************************************************

Copyright 2008  Doc4 : info@doc4design.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at http://www.gnu.org/copyleft/gpl.html

*********************************************************************************/
 
// Register Dashboard Widget
add_action('wp_dashboard_setup', 'atariage_register_dashboard_widget');
function atariage_register_dashboard_widget() {
	wp_register_sidebar_widget('dashboard_atariage', __('AtariAge Dashboard Feed', 'atariage'), 'dashboard_atariage',
		array(
		'all_link' => "http://www.AtariAge.com", 
		'feed_link' => "http://www.AtariAge.com/news/rss.php", 
		'width' => 'half', // OR 'fourth', 'third', 'half', 'full' (Default: 'half')
		'height' => 'single', // OR 'single', 'double' (Default: 'single')
		)
	);
add_action('admin_head', 'atariage_head');
}

function atariage_head() {
	echo '<link href="'.get_bloginfo('siteurl').'/wp-content/plugins/d4-atariage.feed/css/aaStyle.css" rel="stylesheet" type="text/css" />'."\n";
}
 
// Add Dashboard Widget
add_filter('wp_dashboard_widgets', 'atariage_add_dashboard_widget');
function atariage_add_dashboard_widget($widgets) {
	global $wp_registered_widgets;
	if (!isset($wp_registered_widgets['dashboard_atariage'])) {
		return $widgets;
	}
	array_splice($widgets, sizeof($widgets)-1, 0, 'dashboard_atariage');
	return $widgets;
}
 
// Print Dashboard Widget
function dashboard_atariage($sidebar_args) {
	global $wpdb;
	$tech_rss_feed = "http://www.AtariAge.com/news/rss.php";
	extract($sidebar_args, EXTR_SKIP);
	echo $before_widget;
	echo $before_title;
	echo $widget_name;
	echo $after_title;
	echo "<div id='identity'></div>";
	echo "<ul>";
	$rss = @fetch_rss($tech_rss_feed);
	$rss->items = array_slice($rss->items, 0, 3);
	foreach ($rss->items as $item ) {
		$parsed_url = parse_url(wp_filter_kses($item['link']));
		echo "<li><a href=" . wp_filter_kses($item['link']) . ">" . wptexturize(wp_specialchars($item['title'])) . "</a></li>";
		echo "<div class=\"pubdate\">" . wptexturize(wp_specialchars($item['pubdate'])) . "</div>";
		echo "<p>" . wptexturize(wp_specialchars($item['description'])) . "</p>";
	}
	echo "</ul>";
	echo $after_widget;
}

?>
