<?php
/*
Plugin Name: AtariAge Dashboard Feed
Plugin URI: http://www.doc4design.com/plugins/atariage-dashboard-feed
Description: Add the AtariAge RSS Feed to your WordPress Dashboard
Version: 2.5.2
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

include_once(ABSPATH . WPINC . '/rss.php');
 
// Print Dashboard Widget
function dashboard_AtariAge() {
	$tech_rss_feed = 'http://www.atariage.com/news/rss.php';
    $widget_options = dashboard_AtariAge_Options();
    $rss = fetch_rss($tech_rss_feed);
	
	if ( !empty($rss->items) ) {
	     echo '<a href="http://www.atariage.com/" title="Go to AtariAge.com"><img src="'.get_bloginfo('wpurl').'/wp-content/plugins/atariage-dashboard-feed/icon.png" class="floatit" alt="AtariAge.com"/></a>';
	     echo '<ul>';
         $rss->items = array_slice($rss->items, 0, $widget_options['items']);
         
		 foreach ($rss->items as $item ) {
                  $trlink = '<li><a href="' . wp_filter_kses($item['link']) . '">' . wptexturize(wp_specialchars($item['title'])) . '</a>';

                 if($widget_options['showtime']) {				
                    $trlink .=  "<div class='rss-date'>".date("M dS g:i a", strtotime($item['pubdate']))."</div>";
				 } else {
				    echo '';
                 }

                 if($widget_options['showexcerpt']) {
	                $trlink .=  "<p>" . wptexturize(wp_specialchars(substr(strip_tags($item['description']), 0,350) )) . "...</p>";
                 }
                
                 echo '<p>';
                 echo $trlink;
                 echo '</p>'; 
                 }


                 echo '<li></ul>';
				
                 } else {
				 
                 echo '<p>' . __( 'This dashboard widget queries <a href="http://www.atariage.com">AtariAge</a> for their latest 
				                   RSS (Really Simple Syndication) feed. Oddly enough, it hasn\'t found any... yet. It\'s okay - Albert is surely working on it.', 'dashboard_AtariAge' ) . "</p>";
		         }
		         echo '<p class="textright"><a class="button" href="http://www.atariage.com">'.__('View all').'</a></p>';
                 }
	


/* add Dashboard Widget via function wp_add_dashboard_widget() */
function dashboard_AtariAge_Init() {
	wp_add_dashboard_widget( 'dashboard_AtariAge', __( 'AtariAge Dashboard Feed' ), 'dashboard_AtariAge', 'dashboard_AtariAge_Setup');
	add_action('admin_head', 'AtariAge_head', 999);
    }

function AtariAge_head() {
	echo '<link href="'.get_bloginfo('wpurl').'/wp-content/plugins/atariage-dashboard-feed/style.css" rel="stylesheet" type="text/css" />'."\n";
    }

function dashboard_AtariAge_Options() {
	$defaults = array( 'items' => 5, 'showtime' => 1, 'showURL' => 1, 'showexcerpt' => 1);
	if ( ( !$options = get_option( 'dashboard_AtariAge' ) ) || !is_array($options) )
		    $options = array();
	return array_merge( $defaults, $options );
    }

function dashboard_AtariAge_Setup() {
	$options = dashboard_AtariAge_Options();

	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'dashboard_AtariAge' == $_POST['widget_id'] ) {
		 foreach ( array( 'items', 'showtime', 'showurl', 'showexcerpt' ) as $key )
				 $options[$key] = $_POST[$key];
		         update_option( 'dashboard_AtariAge', $options );
    }
		
?>

<!-- add Dashboard configure panel -->
<div id='identity-configure'></div>

<p>
 <label for="items"><?php _e('How many items would you like to display?', 'dashboard_AtariAge' ); ?>
  <select id="items" name="items">
   <?php for ( $i = 5; $i <= 20; $i = $i + 1 )
          echo "<option value='$i'" . ( $options['items'] == $i ? " selected='selected'" : '' ) . ">$i</option>"; ?>
  </select>
 </label>
</p>
   
<p>
 <label for="showtime">
  <input id="showtime" name="showtime" type="checkbox" value="1"<?php if ( 1 == $options['showtime'] ) echo ' checked="checked"'; ?> />
  <?php _e('Show post date?', 'dashboard_AtariAge' ); ?>
 </label>
</p>
	
<p>
 <label for="showexcerpt">
  <input id="showexcerpt" name="showexcerpt" type="checkbox" value="1"<?php if ( 1 == $options['showexcerpt'] ) echo ' checked="checked"'; ?> />
  <?php _e('Show excerpt?', 'dashboard_AtariAge' ); ?>
 </label>
</p>
	
<?php } add_action('wp_dashboard_setup', 'dashboard_AtariAge_Init'); ?>