<?php // FBC Latest Backup for UpdraftPlus - Build Widget

if (! defined( 'ABSPATH' ) ) {
       exit;
}

require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

function fbc_latest_backup_check_dependency(){
    if ( is_plugin_active( 'updraftplus/updraftplus.php' ) ) {
        add_action( 'wp_dashboard_setup', 'fbc_latest_backup_dashboard_widget' );
    } else {
        add_action( 'wp_dashboard_setup', 'fbc_latest_backup_dashboard_widget_inactive' );
    }
}

add_action( 'admin_init', 'fbc_latest_backup_check_dependency' );

function fbc_latest_backup_dashboard_widget_inactive_display(){
    _e( 'No information available. Please make sure that UpdraftPlus Backup plugin is installed and activated.', 'fbc-latest-backup' );
}

function fbc_latest_backup_dashboard_widget_display(){
    $fbc_latest_backup_last_backup = get_option( 'updraft_last_backup' );
    if ( $fbc_latest_backup_last_backup ) {
        global $wpdb;

        $fbc_latest_backup_backup_check = date( 'Y-m-j G:i:s', $fbc_latest_backup_last_backup["backup_time"] );

        $fbc_post_types = [];

        $args = array(
            'post_type'     => 'any',
            'post_status'   => array('publish','inherit'),
            'date_query'    => array(
                'column'    => 'post_modified_gmt',
                 'after'    => $fbc_latest_backup_backup_check
            )
        );
        $fbc_latest_backup_query = new WP_Query($args);
        $fbc_latest_backup_count = 0;
        $fbc_latest_backup_color = 'green';
        if ( $fbc_latest_backup_query->have_posts() ) :
            while ( $fbc_latest_backup_query->have_posts() ) :
                $fbc_latest_backup_query->the_post();
                array_push($fbc_post_types, [get_post_type(), get_the_title()]);
                $fbc_latest_backup_count++;
            endwhile;
        endif;
        wp_reset_postdata();

        if (count($fbc_post_types) > 0) {
          foreach ($fbc_post_types as $key => $item) {
             $arr[$item[0]][$key] = $item;
          }
          ksort($arr, SORT_NUMERIC);
        }

        if ($fbc_latest_backup_count > 0) {
            $fbc_latest_backup_color = 'red';
        }
        ?>
        <div class='edits_since_wrapper'>
          <p><span class='edits_since <?php echo $fbc_latest_backup_color; ?>'> <?php echo $fbc_latest_backup_count; ?>
          </span>
          <span class='number_heading'><?php
          if (1 !== $fbc_latest_backup_count) {
            _e( 'Edits since last backup', 'fbc-latest-backup' );
          } else {
            _e( 'Edit since last backup', 'fbc-latest-backup' );
          }
            ?>
         </span>
       </p>
        </div>
        <div class='edits_count_wrapper'>

          <?php
          if ($fbc_latest_backup_count >= 1) {
            echo '<p><span>Content Type</span><span>Edits</span></p>';
            foreach ($arr as $type => $p) {
            ?>
              <div class='edits_count_content'>
              <?php
              if ($type){
                echo '<span>' . $type . '</span><span>' . count($p) . '</span>';
              }else{
                echo '<span>undefined</span><span>' . count($p) . '</span>';
              }
              ?>
              </div>
            <?php
            }
          }
          ?>
        </div>
        <table><thead>
          <tr>
            <th>
              <?php _e( 'Backup Status', 'fbc-latest-backup' ); ?>
            </th>
            <th>
              <?php _e( 'Date', 'fbc-latest-backup' ); ?>
            </th>
            <th>
              <?php  _e('Time', 'fbc-latest-backup'); ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <?php _e( 'Completed', 'fbc-latest-backup' ); ?>
            </td>
            <td><?php echo date( 'M jS, Y', $fbc_latest_backup_last_backup["backup_time"] ); ?>
            </td>
            <td><?php echo date( 'g:i:s a', $fbc_latest_backup_last_backup["backup_time"] ); ?>
            </td>
          </tr>
        </tbody>
      </table>
      <?php
    } else {
          _e( 'No information available. Please check if at least one backup has been made.', 'fbc-latest-backup' );
    }
}

function fbc_latest_backup_dashboard_widget(){
    wp_add_dashboard_widget(
        'fbc_latest_backup_dashboard_widget',
        __('Latest Backup', 'fbc-latest-backup' ),
        'fbc_latest_backup_dashboard_widget_display'
    );
}

function fbc_latest_backup_dashboard_widget_inactive(){
    wp_add_dashboard_widget(
        'fbc_latest_backup_dashboard_widget',
        __('Latest Backup', 'fbc-latest-backup' ),
        'fbc_latest_backup_dashboard_widget_inactive_display'
    );
}
