<?php
/**
*  License Information:
*
*  Net_DNS:  A resolver library for PHP
*  Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
*  Maintainers:
*  Marco Kaiser <bate@php.net>
*  Florian Anderiasch <fa@php.net>
*
* PHP versions 4 and 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt.  If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*/

/* Net_DNS_Question object definition {{{ */
/**
 * Builds or parses the QUESTION section of a DNS packet
 *
 * Builds or parses the QUESTION section of a DNS packet
 *
 * @package Net_DNS
 */
class Net_DNS_Question
{
    /* class variable definitions {{{ */
    var $qname = null;
    var $qtype = null;
    var $qclass = null;

    /* }}} */
    /* class constructor Net_DNS_Question($qname, $qtype, $qclass) {{{ */
    function Net_DNS_Question($qname, $qtype, $qclass)
    {
        $qtype  = !is_null($qtype)  ? strtoupper($qtype)  : 'ANY';
        $qclass = !is_null($qclass) ? strtoupper($qclass) : 'ANY';

        // Check if the caller has the type and class reversed.
        // We are not that kind for unknown types.... :-)
        if ( ( is_null(Net_DNS::typesbyname($qtype)) ||
               is_null(Net_DNS::classesbyname($qtype)) )
          && !is_null(Net_DNS::classesbyname($qclass))
          && !is_null(Net_DNS::typesbyname($qclass)))
        {
            list($qtype, $qclass) = array($qclass, $qtype);
        }
        $qname = preg_replace(array('/^\.+/', '/\.+$/'), '', $qname);
        $this->qname = $qname;
        $this->qtype = $qtype;
        $this->qclass = $qclass;
    }
    /* }}} */
    /* Net_DNS_Question::display() {{{*/
    function display()
    {
        echo $this->string() . "\n";
    }

    /*}}}*/
    /* Net_DNS_Question::string() {{{*/
    function string()
    {
        return $this->qname . ".\t" . $this->qclass . "\t" . $this->qtype;
    }

    /*}}}*/
    /* Net_DNS_Question::data(&$packet, $offset) {{{*/
    function data($packet, $offset)
    {
        $data = $packet->dn_comp($this->qname, $offset);
        $data .= pack('n', Net_DNS::typesbyname(strtoupper($this->qtype)));
        $data .= pack('n', Net_DNS::classesbyname(strtoupper($this->qclass)));
        return $data;
    }

    /*}}}*/
}
/* }}} */
/* VIM settings{{{
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