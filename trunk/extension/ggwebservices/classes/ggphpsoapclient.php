<?php
/**
 * Class used to communicate with soap servers via the native soap extension
 *
 * @author G. Giunta
 * @version $Id$
 * @copyright (C) G. Giunta 2009-2011
 *
 * The BIG difference with the eZ soap class is that this one groks WSDL
 *
 * Known differences from other ws clients:
 * - the timeout set is not active while socket is open (reading/writing) but only
 *   for opening it
 */

class ggPhpSOAPClient extends ggWebservicesClient
{
    /**
    * The difference between this constructor and the parent one is the addition
    * of the wsdl parameter
    */
    function __construct( $server, $path = '/', $port = 80, $protocol=null, $wsdl=null )
    {
        /// @todo verify: the following 2 fields are unused?
        //$this->ContentType = 'text/xml'; /// @todo add UTF8 charset by default?

        $this->Wsdl = $wsdl;

        // test it here, since we later use an @ operator to catch soap warnings
        // related to wsdl
        if ( !class_exists( 'SoapClient' ) )
        {
            throw new Exception("Class 'SoapClient' not found. Cannot instantiate ggPhpSOAPClient object");
        }
        parent::__construct( $server, $path, $port, $protocol );

    }

    /// @todo what if instead of doing send -> __soapCall -> __doRequest -> _send -> parent::send
    ///       we just overloaded the request's payload() and the response's decodestream
    ///       methods? Is it doable at all?
    /// @todo test: proxy and auth usage of client for getting the wsdl are taken
    ///       from $options['login'] and $options['proxy_host'] ? ...
    function send( $request )
    {

        $this->RequestPayload = '';
        $this->ResponsePayload = '';

        /// @todo add a check that request is a soap / phpsoap one, or it will have no namespace method...
        $options = array( 'exceptions' => true, 'soap_version' => $this->SoapVersion );
        /*
        if ( $this->Login != '' )
        {
            $options['login'] = $this->Login;
            $options['password'] = $this->Password;
        }
        if ( $this->Proxy != '' )
        {
            $options['proxy_host'] = $this->Proxy;
            $options['proxy_port'] = $this->ProxyPort;
            if ( $this->ProxyLogin != '' )
            {
                $options['proxy_login'] = $this->ProxyLogin;
                $options['proxy_password'] = $this->ProxyPassword;
            }
        }*/
        if ( $this->Wsdl == null )
        {
            // non-wsdl mode
            $options['location'] = $this->Protocol . "://" . $this->Server . ":" . $this->Port . $this->Path;
            /// @todo test if request is not a ggsoaprequest / ggphpsoaprequest
            ///       and has thus no ->ns() method
            $options['uri'] = $request->ns();
        }
        else
        {
            if ( preg_match( '#^https?://#', $this->Wsdl ) && $this->Timeout != 0 )
            {
                // patch around buggy soapclient behaviour: force socket timeout on getting wsdl call
                $deftimeout = ini_get( 'default_socket_timeout' );
                if ( $deftimeout !=  $this->Timeout )
                {
                    ini_set( 'default_socket_timeout', $this->Timeout );
                }
                else
                {
                    unset( $deftimeout );
                }
            }

        }
        try
        {
            $response = new $this->ResponseClass();
            $client = new ggPhpSOAPClientTransport( $this->Wsdl, $options, $this, $request );
            if ( isset( $deftimeout ) )
            {
                ini_set( 'default_socket_timeout', $deftimeout );
            }
            $results = $client->__soapCall( $request->name(), $request->parameters(), array(), array(), $output_headers );

            // phpSoapResponse responses do not parse anything anyway - no need to call this
            //$rawResponse = $client->__getLastResponse();
            //$response->decodeStream( $request, $rawResponse );
            if ( is_soap_fault( $results ) )
            {
                $response->setValue( new ggWebservicesFault( $result->faultcode, $result->faultstring ) );
            }
            else
            {
                $response->setValue( $results );
            }
            return $response;
        }
        catch( exception $e )
        {
            if ( isset( $deftimeout ) )
            {
                ini_set( 'default_socket_timeout', $deftimeout );
            }
            if ( $this->errorNumber() )
            {
                $response->setValue( new ggWebservicesFault( $this->errorNumber(), $this->errorString() ) );
            }
            else if ( $e instanceof SoapFault )
            {
                $response->setValue( new ggWebservicesFault( $e->faultcode, $e->faultstring ) );
            }
            else
            {
                $response->setValue( new ggWebservicesFault( $e->code, $e->message ) );
            }
            return $response;
        }

    }

    function _send( $request, $location, $action, $version, $one_way = 0 )
    {
        if ( $this->Wsdl != null )
        {
            /// patch temporarily Server, Path, Port using $location (needed in wsdl mode)
            $server = $this->Server;
            $path = $this->Path;
            $port = $this->Port;
            $protocol = $this->Protocol;

            $parsed = parse_url( $location );
            $this->Server = $parsed['host'];
            $this->Path = $parsed['path'];
            $this->Port = isset( $parsed['port'] ) ? $parsed['port'] : ( @$parsed['scheme'] == 'https' ? 443 : 80 );
            $this->Protocol = isset( $parsed['scheme'] ) ? $parsed['scheme'] : ( @$parsed['port'] == 443 ? 'https' : 'http' );
        }
        $response = parent::send( $request );
        if ( $this->Wsdl != null )
        {
             $this->Server = $server;
             $this->Path = $path;
             $this->Port = $port;
             $this->Protocol = $protocol;
        }
        if ( is_object( $response ) )
        {
            if ( !$response->isFault() )
            {
                return $response->value();
            }
            else
            {
                // copy into our members the error codes, so that we can recover them
                // later while finishing the send() call
                $this->errorNumber = $response->FaultCode();
                $this->errorString = $response->FaultString();

                return $response->FaultCode() . ' ' . $response->FaultString();
            }
        }
        else
        {
            return $this->errorNumber() . ' ' . $this->errorString();
        }
    }

    public function setSoapVersion( $version )
    {
        $this->SoapVersion = $version;
    }

    public function setOption( $option, $value )
    {
        if ( $option == 'soapVersion' )
        {
            $this->SoapVersion = $value;
        }
        else
        {
            return parent::setOption( $option, $value );
        }
    }

    /// @todo override function payload() of parent and throw an exception when called, as we do not set up proper RequestHeaders anyway

    protected $Wsdl;
    protected $SoapVersion = SOAP_1_1;

    protected $ResponseClass = 'ggPhpSOAPResponse';
    protected $UserAgent = 'gg eZ PHPSOAP client';
}

?>