<?php
class Hades_Widget_Event_Calendar extends WP_Widget
    {
    private static $instance = 0;
    protected $defaults;

    function __construct()
        {
        $widget_ops = array ( 'description' => __( 'Widget affichant un calendrier des offres Hadès de type événement', 'text-domain' ) );
        parent::__construct( 'hades-calendar', __( 'Hadès: Calendrier' ), $widget_ops );
        }

    public function widget( $args, $instance )
        {
        ?>
        <aside id="" class="widget widget_calendar widget_twentyfourteen_ephemera">
          <h1 class="widget-title calendar-title">
            <?php echo $instance['title']; ?>
          </h1>
          <div id="calendar_wrap" class="calendar_wrap">
            <?php echo $this->get_hades_calendar(); ?>
          </div>
        </aside>
        <?php
        }

    public function form( $instance )
        {
        if( true == empty( $instance['title'] ) )
            {
            $title = __( 'Calendrier des événements', 'text-domain' );
            }
        else
            {
            $title = $instance['title'];
            }
        ?>
        <p>
          <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
            <?php _e( esc_attr( 'Title:' ) ); ?>
          </label>
          <input class="widefat" 
                 id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                 name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
                 type="text" 
                 value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
        }

    public function update( $new_instance, $old_instance )
        {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        return $instance;
        }

    private function get_hades_calendar( $initial = true, $echo = true )
        {
        ini_set( 'display_errors', '1' );
        global $wpdb, $wp_locale, $posts;

        $hyear = substr( $_GET['hades_agenda'], 0, 4 );
        $hmonth = substr( $_GET['hades_agenda'], 4, 2 );
        $hday = substr( $_GET['hades_agenda'], 6, 2 );
        $hymd = $_GET['hades_agenda'];
        echo $hyear . " - " . $hmonth . " - " . $hday;
        echo "<style>
    .nbevent1 {	background-color:RGBA(255, 185, 64, 0.15);}
    .nbevent2 {	background-color: RGBA(255, 185, 64, 0.20);}
    .nbevent3 {	background-color: RGBA(255, 185, 64, 0.30);}
    .nbevent4 {	background-color: RGBA(255, 185, 64, 0.40);}
    .nbevent5 {	background-color: RGBA(255, 185, 64, 0.50);}
    .nbevent6 {	background-color: RGBA(255, 185, 64, 0.60);}
    .nbevent7 {	background-color: RGBA(255, 185, 64, 0.70);}
    .nbevent8 {	background-color: RGBA(255, 185, 64, 0.80);}
    .nbevent9 {	background-color: RGBA(255, 185, 64, 0.90);}
    .nbevent10 { background-color: RGBA(255, 185, 64,1);}
</style>";
        $key = md5( $hday . $hmonth . $hyear );
        $cache = wp_cache_get( 'get_hades_calendar', 'calendar' );

        if( $cache && is_array( $cache ) && isset( $cache[$key] ) )
            {
            /** This filter is documented in wp-includes/general-template.php */
            $output = apply_filters( 'get_hades_calendar', $cache[$key] );

            if( $echo )
                {
                echo $output;
                return;
                }

            return $output;
            }

        if( !is_array( $cache ) )
            {
            $cache = array ();
            }

        // Quick check. If we have no posts at all, abort!
        if( !$posts )
            {
            $gotsome = $wpdb->get_var( "SELECT 1 as test FROM $wpdb->posts WHERE post_type = '" . HADES_CPT . "' AND post_status = 'publish' LIMIT 1" );
            if( !$gotsome )
                {
                $cache[$key] = '';
                wp_cache_set( 'get_hades_calendar', $cache, 'calendar' );
                return;
                }
            }

        if( isset( $_GET['w'] ) )
            {
            $w = (int) $_GET['w'];
            }
        // week_begins = 0 stands for Sunday
        $week_begins = (int) get_option( 'start_of_week' );
        $ts = current_time( 'timestamp' );

        // Let's figure out when we are
        if( !empty( $hmonth ) && !empty( $hyear ) )
            {
            $thismonth = zeroise( intval( $hmonth ), 2 );
            $thisyear = (int) $hyear;
            }
        elseif( !empty( $w ) )
            {
            // We need to get the month from MySQL
            $thisyear = (int) substr( $hymd, 0, 4 );
            //it seems MySQL's weeks disagree with PHP's
            $d = ( ( $w - 1 ) * 7 ) + 6;
            $thismonth = $wpdb->get_var( "SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')" );
            }
        elseif( !empty( $hym ) )
            {
            $thisyear = (int) substr( $hymd, 0, 4 );
            if( strlen( $hym ) < 6 )
                {
                $thismonth = '01';
                }
            else
                {
                $thismonth = zeroise( (int) substr( $hymd, 4, 2 ), 2 );
                }
            }
        else
            {
            $thisyear = gmdate( 'Y', $ts );
            $thismonth = gmdate( 'm', $ts );
            }

        $unixmonth = mktime( 0, 0, 0, $thismonth, 1, $thisyear );
        $last_day = date( 't', $unixmonth );

        $previous = new stdClass();
        $previous->month = date( "m", mktime( 0, 0, 0, $thismonth - 1, 1, $thisyear ) );
        $previous->year = date( "Y", mktime( 0, 0, 0, $thismonth - 1, 1, $thisyear ) );

        
        $next = new stdClass();
        $next->month = date( "m", mktime( 0, 0, 0, $thismonth + 1, 1, $thisyear ) );
        $next->year = date( "Y", mktime( 0, 0, 0, $thismonth + 1, 1, $thisyear ) );

        /* translators: Calendar caption: 1: month name, 2: 4-digit year */
        $calendar_caption = _x( '%1$s %2$s', 'calendar caption' );
        $calendar_output = '<table id="wp-calendar">
  	<caption>' . sprintf(
                        $calendar_caption, $wp_locale->get_month( $thismonth ), date( 'Y', $unixmonth )
                ) . '</caption>
  	<thead>
  	<tr>';

        $myweek = array ();

        for( $wdcount = 0; $wdcount <= 6; $wdcount++ )
            {
            $myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
            }

        foreach( $myweek as $wd )
            {
            $day_name = $initial ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
            $wd = esc_attr( $wd );
            $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name </th>";
            }

        $calendar_output .= '
  	</tr>
  	</thead>

  	<tfoot>
  	<tr>';

        if( $previous )
            {
            $calendar_output .= "\n\t\t" . '<td colspan="3" id="prev"><a href="' . get_hades_agenda_month_link( $previous->year, $previous->month ) . '">&laquo; ' .
                    $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) .
                    '</a></td>';
            }
        else
            {
            $calendar_output .= "\n\t\t" . '<td colspan="3" id="prev" class="pad">&nbsp;</td>';
            }

        $calendar_output .= "\n\t\t" . '<td class="pad">&nbsp;</td>';

        if( $next )
            {
            $calendar_output .= "\n\t\t" . '<td colspan="3" id="next"><a href="' . get_hades_agenda_month_link( $next->year, $next->month ) . '">' .
                    $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) .
                    ' &raquo;</a></td>';
            }
        else
            {
            $calendar_output .= "\n\t\t" . '<td colspan="3" id="next" class="pad">&nbsp;</td>';
            }

        $calendar_output .= '
  	</tr>
  	</tfoot>

  	<tbody>
  	<tr>';

        $daywithpost = array ();

        // Get days with posts
        $dayswithposts = $wpdb->get_results( "SELECT DISTINCT DAYOFMONTH(post_date)
  		FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
  		AND post_type = '" . HADES_CPT . "' AND post_status = 'publish'
  		AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N );

        $dayswithposts = $wpdb->get_results( "SELECT
                                        IF(dh.date_deb >= '{$thisyear}-{$thismonth}-01 00:00:00' AND dh.date_fin <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59', 
                                            DAYOFMONTH(dh.date_deb),
                                            0) AS dofm,
                                        COUNT(*)
                                      FROM wp_hades_offre_dateheure dh
                                      WHERE dh.date_fin >= '{$thisyear}-{$thismonth}-01 00:00:00'
                                      AND dh.date_deb <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'
                                      GROUP BY dofm", ARRAY_N );

        if( $dayswithposts )
            {
            foreach( (array) $dayswithposts as $daywith )
                {
                $daywithpost[$daywith[0]] = $daywith[1];
                }
            }

        // See how much we should pad in the beginning
        $pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
        if( 0 != $pad )
            {
            $calendar_output .= "\n\t\t" . '<td colspan="' . esc_attr( $pad ) . '" class="pad">&nbsp;</td>';
            }

        $newrow = false;
        $daysinmonth = (int) date( 't', $unixmonth );

        for( $day = 1; $day <= $daysinmonth; ++$day )
            {
            if( isset( $newrow ) && $newrow )
                {
                $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
                }
            $newrow = false;

            $dayclass = "nbevent" . intval( sqrt( $daywithpost[$day] ) );

            if( $day == gmdate( 'j', $ts ) &&
                    $thismonth == gmdate( 'm', $ts ) &&
                    $thisyear == gmdate( 'Y', $ts ) )
                {
                $calendar_output .= '<td id="today" class="' . $dayclass . '">';
                }
            else
                {
                $calendar_output .= '<td class="' . $dayclass . '">';
                }

            if( key_exists( $day, $daywithpost ) )
                {
                // any posts today?
                $date_format = date( _x( 'F j, Y', 'daily archives date format' ), strtotime( "{$thisyear}-{$thismonth}-{$day}" ) );
                $label = sprintf( __( 'Posts published on %s' ), $date_format );
                $calendar_output .= sprintf(
                        '<a href="%s" aria-label="%s" >%s</a>', get_hades_agenda_day_link( $thisyear, $thismonth, $day ), esc_attr( $label ), $day
                );
                }
            else
                {
                $calendar_output .= $day;
                }
            $calendar_output .= '</td>';

            if( 6 == calendar_week_mod( date( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins ) )
                {
                $newrow = true;
                }
            }

        $pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins );
        if( $pad != 0 && $pad != 7 )
            {
            $calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . esc_attr( $pad ) . '">&nbsp;</td>';
            }
        $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

        $cache[$key] = $calendar_output;
        wp_cache_set( 'get_hades_calendar', $cache, 'calendar' );

        if( $echo )
            {
            /**
             * Filter the HTML calendar output.
             *
             * @since 3.0.0
             *
             * @param string $calendar_output HTML output of the calendar.
             */
            echo apply_filters( 'get_hades_calendar', $calendar_output );
            return;
            }

        return apply_filters( 'get_hades_calendar', $calendar_output );
        }

    private function delete_get_hades_calendar_cache()
        {
        wp_cache_delete( 'get_hades_calendar', 'calendar' );
        }

    }

function get_hades_agenda_month_link( $year, $month )
    {
    if( !$year )
        $year = gmdate( 'Y', current_time( 'timestamp' ) );
    if( !$month )
        $month = gmdate( 'm', current_time( 'timestamp' ) );
    $monthlink = home_url('?hades_agenda=' . $year . zeroise( $month, 2 ));
    return $monthlink;
    }

function get_hades_agenda_day_link( $year, $month, $day )
    {
    if( !$year )
        $year = gmdate( 'Y', current_time( 'timestamp' ) );
    if( !$month )
        $month = gmdate( 'm', current_time( 'timestamp' ) );
    if( !$day )
        $day = gmdate( 'j', current_time( 'timestamp' ) );
    $daylink = home_url('?hades_agenda=' . $year . zeroise( $month, 2 ) . zeroise( $day, 2 ));
    return $daylink;
    }
