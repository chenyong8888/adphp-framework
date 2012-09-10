<?php
/* $Id: session.inc.php,v 2.5 2005/11/10 19:57:43 lem9 Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:
/**
 * session handling 
 * 
 * @TODO    add failover or warn if sessions are not configured properly
 * @TODO    add an option to use mm-module for session handler
 * @see     http://www.php.net/session
 * @uses    session_name()
 * @uses    session_start()
 * @uses    ini_set()
 * @uses    version_compare()
 * @uses    PHP_VERSION
 */

// verify if PHP supports session, die if it does not

if (!@function_exists('session_name')) {

    $cfg = array('DefaultLang'           => 'en-iso-8859-1',
                    'AllowAnywhereRecoding' => FALSE);
    // Loads the language file
    require_once('./libraries/select_lang.lib.php');
    // Displays the error message
    // (do not use &amp; for parameters sent by header)
    header( 'Location: error.php'
            . '?lang='  . urlencode( $available_languages[$lang][2] )
            . '&char='  . urlencode( $charset )
            . '&dir='   . urlencode( $text_dir )
            . '&type='  . urlencode( $strError )
            . '&error=' . urlencode(
                sprintf($strCantLoad, 'session'))
             );
    exit();
}

// disable starting of sessions before all settings are done
ini_set( 'session.auto_start', false );

// cookies are safer
ini_set( 'session.use_cookies', true );

// but not all user allow cookies
ini_set( 'session.use_only_cookies', false );
ini_set( 'session.use_trans_sid', true );
ini_set( 'url_rewriter.tags',
    'a=href,frame=src,input=src,form=fakeentry,fieldset=' );
ini_set( 'arg_separator.output' , '&amp;' );

// delete session/cookies when browser is closed
ini_set( 'session.cookie_lifetime', 0 );

// warn but dont work with bug
ini_set( 'session.bug_compat_42', false );
ini_set( 'session.bug_compat_warn', true );

// use more secure session ids (with PHP 5)
if ( version_compare( PHP_VERSION, '5.0.0', 'ge' ) 
  && substr( PHP_OS, 0 ,3 ) != 'WIN' ) {
    ini_set( 'session.hash_function', 1 );
    ini_set( 'session.hash_bits_per_character', 6 );
}

// start the session
// on some servers (for example, sourceforge.net), we get a permission error
// on the session data directory, so I add some "@"
@session_name( 'phpMyAdmin' );
@session_start();

/**
 * trys to secure session from hijacking and fixation
 * should be called before login and after successfull login
 * (only required if sensitive information stored in session)
 * 
 * @uses    session_regenerate_id() to secure session from fixation
 * @uses    session_id()            to set new session id
 * @uses    strip_tags()            to prevent XSS attacks in SID
 * @uses    function_exists()       for session_regenerate_id()
 */
function PMA_secureSession() {
    // prevent session fixation and XSS
    if ( function_exists( 'session_regenerate_id' ) ) {
        session_regenerate_id( true );
    } else {
        session_id( strip_tags( session_id() ) );
    }
}
?>