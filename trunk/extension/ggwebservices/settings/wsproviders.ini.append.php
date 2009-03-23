<?php /*

[GeneralSettings]
# Logging of outgoing webservice calls
Logging=warning
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
#providerUri=http://my.test.server/wsendpoint.php
#providerType=JSONRPC, SOAP, REST or XMLRPC
#providerUsername=
#providerPassword=
#timeout=60
# If these variables are not set here, the Proxy defined globally in site.ini ProxySerrings block will be used
# (nb: a variable with an empty value is still considered to be set. To unset it, remove or comment the line)
#ProxyServer=myproxy:8080
#ProxyUser=
#ProxyPassword=


*/ ?>