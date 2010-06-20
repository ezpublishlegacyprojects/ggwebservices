<?php
/**
 *
 * @author G. Giunta
 * @version $Id$
 * @copyright (C) G. Giunta 2009
 */

$Module = array( 'name' => 'Webservices', "variable_params" => true );

$ViewList = array(

    /*
    * View used to proxy webservices calls to other wervices, overcoming the same-domain limitation.
    * General scheme: call a jsonrpc or xmlrpc webservice via a js function in your templates
    * pointing to /webservices/proxy/jsonrpc/remoteservername or /webservices/proxy/xmlrpc/remoteservername
    */
    'proxy' => array(
        'script' => 'proxy.php',
        'params' => array( 'protocol', 'remoteServerName', 'session' ),
        'functions' => array( 'proxy' ),
    ),

    /*
    * View used to invoke webservices witout usage of the custom index files jsonrpc.php and phpxmlrpc.php
    * Execution will be slower, but access control can be managaed via standard user policies
    */
    'execute' => array(
        'script' => 'execute.php',
        'params' => array( 'protocol', 'session' ),
        'functions' => array( 'execute' ),
    ),

    /*'wsdl' => array(
        'script' => 'wsdl.php',
        'params' => array( 'webservice' ),
        'functions' => array( 'execute' ),
    ),*/

    'debugger' => array(
        'script' => 'debugger.php',
        'params' => array( 'target', 'patchtarget' ),
        'functions' => array( 'debugger' ),
        'default_navigation_part' => 'wsdebuggernavigationpart',
    ),
);

$FunctionList = array(
    'execute' => array(
        'Webservices' => array(
            'name'=> 'Webservices',
            'values'=> array(),
            'path' => '../extension/ggwebservices/classes/', // starts within 'kernel'...
            'file' => 'ggezwebservices.php',
            'class' => 'ggeZWebservices',
            'function' => 'getMethodsList',
            'parameter' => array() ),
        'SiteAccess' => array(
            'name'=> 'SiteAccess',
            'values'=> array(),
            'path' => 'classes/',
            'file' => 'ezsiteaccess.php',
            'class' => 'eZSiteAccess',
            'function' => 'siteAccessList',
            'parameter' => array()
            )
         ),
    'proxy' => array(
        'RemoteServers' => array(
            'name'=> 'RemoteServers',
            'values'=> array(),
            'path' => '../extension/ggwebservices/classes/',
            'file' => 'ggezwebservices.php',
            'class' => 'ggeZWebservices',
            'function' => 'getServersList',
            'parameter' => array() )
    ),
	'debugger' => array()
);

?>