<?php
/*
Plugin Name: AtariAge Dashboard Feed
Plugin URI: http://www.wordpress.org/#
Description: Add AtariAge RSS Feed to your WordPress Dashboard
Author: Doc4
Version: .1
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
add_action('wp_dashboard_setup', 'AtariAgeRSS_register_dashboard_widget');
function AtariAgeRSS_register_dashboard_widget() {
	wp_register_sidebar_widget('dashboard_AtariAgeRSS', __('AtariAge Dashboard Feed', 'AtariAgeRSS'), 'dashboard_AtariAgeRSS',
		array(
		'all_link' => "http://www.AtariAge.com", 
		'feed_link' => "http://www.AtariAge.com/news/rss.php", 
		'width' => 'half', // OR 'fourth', 'third', 'half', 'full' (Default: 'half')
		'height' => 'single', // OR 'single', 'double' (Default: 'single')
		)
	);
}
 
// Add Dashboard Widget
add_filter('wp_dashboard_widgets', 'AtariAgeRSS_add_dashboard_widget');
function AtariAgeRSS_add_dashboard_widget($widgets) {
	global $wp_registered_widgets;
	if (!isset($wp_registered_widgets['dashboard_AtariAgeRSS'])) {
		return $widgets;
	}
	array_splice($widgets, sizeof($widgets)-1, 0, 'dashboard_AtariAgeRSS');
	return $widgets;
}
 
// Print Dashboard Widget
function dashboard_AtariAgeRSS($sidebar_args) {
	global $wpdb;
	$tech_rss_feed = "http://www.AtariAge.com/news/rss.php";
	extract($sidebar_args, EXTR_SKIP);
	echo $before_widget;
	echo $before_title;
	echo $widget_name;
	echo $after_title;
	echo "<ul>";
	$rss = @fetch_rss($tech_rss_feed);
	$rss->items = array_slice($rss->items, 0, 20);
	foreach ($rss->items as $item ) {
		$parsed_url = parse_url(wp_filter_kses($item['link']));
		echo "<li><a href=" . wp_filter_kses($item['link']) . ">" . wptexturize(wp_specialchars($item['title'])) . "</a></li>";
		echo "<p>" . wptexturize(wp_specialchars($item['description'])) . "</p>";
	}
	echo "</ul>";
	echo $after_widget;
}

?>
