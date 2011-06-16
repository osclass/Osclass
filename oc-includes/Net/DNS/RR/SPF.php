<?php
/**
*  License Information:
*
*  Net_DNS:  A resolver library for PHP
*  Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
*  Maintainers:
*  Marco Kaiser <bate@php.net>
*  Florian Anderiasch <fa@php.net>
*  Mike Pultz <mike@mrhost.ca>
*
* PHP versions 4 and 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt.  If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*/

/* Net_DNS_RR_SPF definition {{{ */
/**
 * A representation of a resource record of type <b>SPF</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_SPF extends Net_DNS_RR_TXT
{

    //
    // There's no work to be done here, since the SPF RR is identical to the TXT RR- we
    // just extend the Net_DNS_RR_TXT record class and inherit everything.
    //

}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
?>