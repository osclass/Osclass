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

/* Net_DNS_RR_A object definition {{{ */
/**
 * A representation of a resource record of type <b>A</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_A extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $address;
    /* }}} */
    /* class constructor - Net_DNS_RR_A(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_A(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                /*
                 *  We don't have inet_ntoa in PHP?
                 */
                $aparts = unpack('C4b', $this->rdata);
                $addr = $aparts['b1'] . '.' .
                    $aparts['b2'] . '.' .
                    $aparts['b3'] . '.' .
                    $aparts['b4'];
                $this->address = $addr;
            }
        } elseif (is_array($data)) {
            $this->address = $data['address'];
        } else {
            if (strlen($data) && preg_match("/([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)[ \t]*$/", $data, $regs)) {
                if (($regs[1] >= 0 && $regs[1] <= 255) &&
                        ($regs[2] >= 0 && $regs[2] <= 255) &&
                        ($regs[3] >= 0 && $regs[3] <= 255) &&
                        ($regs[4] >= 0 && $regs[4] <= 255)) {
                    $this->address = $regs[1] . '.' . $regs[2] . '.' . $regs[3] . '.' .$regs[4];
                }
            }
        }
    }

    /* }}} */
    /* Net_DNS_RR_A::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->address)) {
            return $this->address;
        }
        return '; no data';
    }
    /* }}} */
    /* Net_DNS_RR_A::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        $aparts = explode('.', $this->address);
        if (count($aparts) == 4) {
            return pack('c4', $aparts[0], $aparts[1], $aparts[2], $aparts[3]);
        }
        return null;
    }

    /* }}} */

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
