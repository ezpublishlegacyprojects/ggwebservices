<?php
/**
 * WSDL helper functions
 *
 * @author G. Giunta
 * @version
 * @copyright (C) G. Giunta 2011
 */

class ggWSDLParser
{
    /**
     * Transforms the array returned by getFunctionsResults() into the formats
     * used by xmlrpc's introspection methods (system.listMethods and friends)
     */
    static function transformGetFunctionsResults( $results, $rname, $method = '' )
    {
        switch( $rname )
        {
            case 'system.listMethods':
                foreach ( $results as $key => $value )
                {
                    $results[$key] = preg_replace( array( '/^([^ ]+ )/', '/(\(.+)$/' ), '', $value );
                }
                return $results;
                break;
            case 'system.methodHelp':
                return ''; /// @todo
                break;
            case 'system.methodSignature':
                foreach ( $results as $key => $value )
                {
                    if ( preg_match( '/^([^ ]+) ([^\(]+)\((.+)\)$/', $value, $matches ) )
                    {
                        if ( $method == $matches[2] )
                        {
                            $params = array( $matches[1] );
                            foreach( explode( ', ', $matches[3] ) as $param )
                            {
                                $param = explode( ' ', $param, 2 );
                                $name = substr( $param[1], 1); // php likes dollars in var names
                                $params[$name] = $param[0];
                            }
                            return array( $params );
                        }
                    }
                }
                return array(); /// @todo return error 'method not found'
                break;
        } // switch
    }

}

?>