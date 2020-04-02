<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function jsbarre( $time, $name )
    {
    echo "<div id='myProgress$name' style='width:100%;background-color:#DDD;margin:3px'><div id='myBar$name' style='width:1%;height:10px;background-color:#0D0;'></div></div>";
    echo "<script>function movebar$name(){var e=document.getElementById('myBar$name');var w=1;var id=setInterval(f$name," . ($time * 10) . ");function f$name(){if(w>=100){clearInterval(id);}else{w++;e.style.width=w+'%';}}}movebar$name();</script>";
    }

function date_eu_to_my( $date )
    {
    
    preg_match( "#([0-9]{2})/([0-9]{2})/([0-9]{4})#", $date, $matches );
    return $matches[3] . "-" . $matches[2] . "-" . $matches[1];
    }

function getDateForSpecificDayBetweenDates( $startDate, $endDate, $dh_jours )
    {
    $endDate = strtotime( $endDate );
    $days = array ( 'lu' => 'Monday', 'ma' => 'Tuesday', 'me' => 'Wednesday', 'je' => 'Thursday', 've' => 'Friday', 'sa' => 'Saturday', 'di' => 'Sunday' );
    $time_array = array ();

    $dh_jours_list = array_filter( array_unique( explode( ",", $dh_jours ) ) );

    foreach( $dh_jours_list as $day_jr )
        {
        //echo "<br/>jour " . $day_jr;
        for( $i = strtotime( $days[$day_jr], strtotime( $startDate ) ); $i <= $endDate; $i = strtotime( '+1 week', $i ) )
            {
            $time_array[] = $i;
            //echo "<br/>" . date( 'Y-m-d', $i );
            }
        }


    sort( $time_array );
    //echo "<br/><br/>sorted<br/>";
    /* foreach( $time_array as $value )
      {
      echo "<br/>" . date( 'Y-m-d', $value );
      }

      echo "<br/><br/>time_array " . count( $time_array ); */

    $j = 0;

    //verifie toutes les dates comprises dans time_array et forme des groupes de dates contigues
    $former_time = $time_array[0];
    $date_array[$j]['deb'] = date( 'Y-m-d', $former_time );
    //initialisation de la date de fin
    $date_array[$j]['fin'] =$date_array[$j]['deb'];
    //echo "<br/>datedeb " . $j . " :" . $date_array[$j]['deb'];
    for( $i = 1; $i < count( $time_array ); $i++ )
        {
        $next_day = $former_time + (60 * 60 * 25);
        //echo "<br/>former " . date( 'Y-m-d', $former_time ) . " next day " . date( 'Y-m-d', $next_day );
        if( date( 'Y-m-d', $time_array[$i] ) == date( 'Y-m-d', $next_day ) )
            {
            $date_array[$j]['fin'] = date( 'Y-m-d', $next_day );
            // echo "<br/>datefin contigue " . $j . " :" . $date_array[$j]['fin'];
            }
        else
            {
            $j++;
            $date_array[$j]['deb'] = date( 'Y-m-d', $time_array[$i] );
            $date_array[$j]['fin'] =$date_array[$j]['deb'];
            //echo "<br/>nouvelle datedeb " . $j . " :" . $date_array[$j]['deb'];
            }
        $former_time = $time_array[$i];
        }

    return $date_array;
    }

function get_details_from_hades_agenda( $exp )
    {

    if( !preg_match( "/^20[0-9][0-9]([0-1][0-9]([0-3][0-9](20[0-9][0-9]([0-1][0-9]([0-3][0-9])?)?)?)?)?$/", $exp ) )
        {
        /*  formats attendus :  Y | Ym | Ymd | YmdYmd   */
        return NULL;
        }

    $hymd = substr( $exp, 0, 8 );
    $hyear = substr( $exp, 0, 4 );
    $hmonth = substr( $exp, 4, 2 );
    $hday = substr( $exp, 6, 2 );

    $hymd2 = substr( $exp, 8, 8 );

    if( $hymd2 )
        {
        $hyear2 = substr( $hymd2, 0, 4 );
        $hmonth2 = substr( $hymd2, 4, 2 );
        $hday2 = substr( $hymd2, 6, 2 );
        }

    // Let's figure out when we are
    if( !empty( $hmonth ) && !empty( $hyear ) )
        {
        $thismonth = zeroise( intval( $hmonth ), 2 );
        $thisyear = (int) $hyear;
        }
    elseif( !empty( $hymd ) && intval( $hymd ) > 2000 )
        {
        $thisyear = (int) substr( $hymd, 0, 4 );
        if( strlen( $hymd ) < 6 )
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
        $thisyear = date( 'Y' );
        $thismonth = date( 'm' );
        }

    $unixmonth = mktime( 0, 0, 0, $thismonth, 1, $thisyear );
    $last_day = date( 't', $unixmonth );
    $details = new stdClass();

    $details->date_deb = $thisyear . "-" . $thismonth . "-" . ($hday ? $hday : "01") . " 00:00:00";
    if( !$hymd2 )
        {
        $details->date_fin = $thisyear . "-" . $thismonth . "-" . ($hday ? $hday : $last_day) . " 23:59:59";
        }
    else
        {
        $details->date_fin = $hyear2 . "-" . $hmonth2 . "-" . $hday2 . " 23:59:59";
        }

    return $details;
    }

//set_error_handler("hadeslog");
function hadeslog( $errno = NULL, $errstr = '', $errfile = '', $errline = '', $process = FALSE )
    {
    static $fd_log;
    static $time;

    static $process_txt;

    $tempdir = HADES_TMP . '/';

    if( $process )
        {
        return $process_txt;
        }

    if( !$time )
        {
        $time = microtime( TRUE );
        }

    if( !$fd_log )
        {
        $fd_log = fopen( $tempdir . "log/hades" . date( "Y-m-d" ) . ".log", "a" );
        fwrite( $fd_log, "\n==============================================================================\n" );
        }

    if( !$fd_log )
        {
        if( !is_dir( $tempdir . "log" ) )
            {
            mkdir( $tempdir );
            if( !mkdir( $tempdir . "log" ) )
                {
                die( "le répertoire de log " . $tempdir . "log" . " n'a pu être créé." );
                }
            }
        $fd_log = fopen( $tempdir . "log/hades" . date( "Y-m-d" ) . ".log", "a" );
        fwrite( $fd_log, "\n==============================================================================\n" );
        }

    if( !$fd_log )
        {
        die( "L'erreur suivante n'a pu être loggée : " . $errstr );
        }



    switch( $errno )
        {
        case E_USER_ERROR :
            fwrite( $fd_log, date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: ERREUR " . $errstr . "\n" );
            fwrite( $fd_log, date( "H:i:s" ) . ": Processus interrompu.\n"
                    . "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n\n\n" );
            fclose( $fd_log );
            die( $errstr );
            break;

        case E_ERROR :
            fwrite( $fd_log, date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: ERREUR " . $errstr . " [" . $errfile . " ligne " . $errline . "]\n" );
            fwrite( $fd_log, date( "H:i:s" ) . ": Processus interrompu.\n"
                    . "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n\n\n" );
            fclose( $fd_log );
            die( $errstr );
            break;

        case E_USER_WARNING :
            fwrite( $fd_log, date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: ALERTE " . $errstr . "\n" );
            $process_txt.=date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: ALERTE " . $errstr . "\n";
            break;

        case E_USER_NOTICE:
            fwrite( $fd_log, date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: NOTE " . $errstr . "\n" );
            $process_txt.= date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: NOTE " . $errstr . "\n";
            break;

        CASE "log" :
            fwrite( $fd_log, date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: " . $errstr . "\n" );
            $process_txt.= date( "H:i:s" ) . "[" . sprintf( "%0.3f", (microtime( TRUE ) - $time ) ) . "s]: " . $errstr . "\n";
            break;

        default:
            break;
        }
    }

function date_vers_texte( $d, $f, $lg = "" )
    {
    if( $lg == "" )
        $lg = 'fr';
    $x = strtotime( $d );
    $y = strtotime( $f );
    $tmp_config = setlocale( LC_TIME, "0" );
    setlocale( LC_TIME, $GLOBALS['lgs'][$lg] );

    switch( true )
        {
        case (date( "Y", $x ) == date( "Y" ) && date( "Y", $y ) == date( "Y" ) && date( "m", $x ) == date( "m", $y ) && date( "d", $x ) != date( "d", $y ) ):
            $dtx = strftime( "%d ", $x );
            $dty = strftime( "%d %b ", $y );
            break;
        case (date( "Y", $x ) == date( "Y" ) && date( "Y", $y ) == date( "Y" )):
            $dtx = strftime( "%d %b ", $x );
            $dty = strftime( "%d %b ", $y );
            break;
        case (date( "Y", $y ) == 2000 || date( "Y", $x ) == 2000):
            $dty = strftime( "%d %b ", $y );
            $dtx = strftime( "%d %b ", $x );
            break;
        case (date( "Y", $x ) != date( "Y" ) || date( "Y", $y ) != date( "Y" )):
            $dtx = strftime( "%d %b ", $x );
            $dty = strftime( "%d %b %Y ", $y );
            break;
        default :
            $dtx = strftime( "%d %b %Y ", $x );
            $dty = strftime( "%d %b %Y ", $y );
        }

    $dtx = utf8_verif( $dtx );
    $dty = utf8_verif( $dty );



    switch( true )
        {
        case ($x == $y && $x != 0): $text.= "" . $dtx;
            break;
        case ($x != 0 && $y != 0): $text.= $dtx . "=>" . $dty;
            break;
        }

    setlocale( LC_TIME, $tmp_config );

    return $text;
    }

function utf8_verif( $texte )
    {
    if( mb_check_encoding( $texte, "UTF-8" ) )
        {
        return $texte;
        }
    else
        {
        return utf8_encode( $texte );
        }
    }

function xsl_event_date( $event_tab )
    {
    static $mois = array ( "JAN", "FEV", "MAR", "AVR", "MAI", "JUN", "JUL", "AOU", "SEP", "OCT", "NOV", "DEC" );
    foreach( $event_tab as $event )
        {
        $dd =  date_eu_to_my($event->getElementsByTagName( "date_deb" )->item( 0 )->nodeValue);
        $df = date_eu_to_my($event->getElementsByTagName( "date_fin" )->item( 0 )->nodeValue);
        $str = "";
        if( $dd )
            {
            $str.= "<div class='news_dates" . ($df && ($df != $dd ) ? " double" : "") . "' >";

            $str.= "<div class='news_date_deb'>";
            $str.= "<span class='jour'>" . substr( $dd, 8, 2 ) . "</span><br/>";
            $str.= "<span class='mois'>" . $mois[intval( substr( $dd, 5, 2 ) ) - 1] . "</span>";
            $str.= "</div>";


            if( $df && ($df != $dd ) )
                {
                $str.= "<div class='news_date_fin'>";
                $str.= "<span class='jour'>" . substr( $df, 8, 2 ) . "</span><br/>";
                $str.= "<span class='mois'>" . $mois[intval( substr( $df, 5, 2 ) ) - 1] . "</span>";
                $str.= "</div>";
                }
            $str.= "</div>";
            }

        }
       if($str){
       $dom=dom_import_simplexml( simplexml_load_string($str));
       }
   else
       {
       $dom=dom_import_simplexml( simplexml_load_string("<div class='news_dates'/>"));
           }
        return $dom;
    }

function hades_set_time( $t )
    {
    $settings = get_option( 'hades_settings' );
    if( $settings['time-extend'] === 'checked' )
        {
        set_time_limit( $t );
        }
    }
