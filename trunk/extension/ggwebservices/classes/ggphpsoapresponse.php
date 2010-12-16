<?php
/**
 * Class used to wrap soap responses. Modeled after the eZ Soap equivalent.
 *
 * @author G. Giunta
 * @version $Id$
 * @copyright (C) G. Giunta 2009-2010
 */

class ggPhpSOAPResponse extends ggWebservicesResponse
{

    /// the use done of this function is a bit warped, ie. it does not conform
	/// to parent's class usage. @see ggPhpSOAPClient::_send()
    function payload( )
    {
        return $this->Value;
    }

    /// the use done of this function is a bit warped, ie. it does not conform
	/// to parent's class usage. @see ggPhpSOAPClient::_send()
    function decodeStream( $request, $stream, $headers = false )
    {
        $this->Value = $stream;
    }
}

?>