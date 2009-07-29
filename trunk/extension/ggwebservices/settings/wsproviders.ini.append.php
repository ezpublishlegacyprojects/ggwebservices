<?php /*

[GeneralSettings]
# Logging of outgoing webservice calls
Logging=warning
# maximum size of logfiles before they are rotated: 2MB ( 2 * 1024 * 1024)
MaxLogSize=2097152
# maximum number of rotated log files to be kept
MaxLogrotateFiles=3
# Incoming webservice calls
# If enabled, the instance's default siteaccess will always be used.
# If disabled, the matched siteaccess (host, port, etc) will be used, allowing
# the usage of webservices when multiple ezpublish websites are hosted on one instance
UseDefaultAccess=enabled

# enable reception of incoming webservice calls
EnableJSONRPC=false
EnableXMLRPC=false

[ExtensionSettings]
# list of extensions providing webservice functionality
JSONRPCExtensions[]
XMLRPCExtensions[]

### definition of webservice servers that can be called by template or php code

#[myserver]
#providerType=JSONRPC, SOAP PhpSOAP, REST or XMLRPC
#providerUri=http://my.test.server/wsendpoint.php
#providerUsername=
#providerPassword=
#timeout=60
# If these variables are not set here, the Proxy defined globally in site.ini ProxySerrings block will be used
# (nb: a variable with an empty value is still considered to be set. To unset it, remove or comment the line)
#ProxyServer=myproxy:8080
#ProxyUser=
#ProxyPassword=

# SOAP server using wsdl: only PhpSOAP is supported
# providerUri empty means use the uri specified in the wsdl, otherwise it will be used instead of it
#[mySOAPserver]
#providerType=PhpSOAP
#providerUri=
#WSDL=http://mydomain.com/NUSOAP/Hellowsdl.php?wsdl

*/ ?>