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

/* Net_DNS_RR_SRV definition {{{ */
/**
 * A representation of a resource record of type <b>SRV</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_SRV extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $preference;
    var $weight;
    var $port;
    var $target;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_SRV(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                $a = unpack("@$offset/npreference/nweight/nport", $data);
                $offset += 6;
                $packet = new Net_DNS_Packet();

                list($target, $offset) = $packet->dn_expand($data, $offset);
                $this->preference = $a['preference'];
                $this->weight = $a['weight'];
                $this->port = $a['port'];
                $this->target = $target;
            }
        } elseif (is_array($data)) {
            $this->preference = $data['preference'];
            $this->weight = $data['weight'];
            $this->port = $data['port'];
            $this->target = $data['target'];
        } else {
            preg_match("/([0-9]+)[ \t]+([0-9]+)[ \t]+([0-9]+)[ \t]+(.+)[ \t]*$/", $data, $regs);
            $this->preference = $regs[1];
            $this->weight = $regs[2];
            $this->port = $regs[3];
            $this->target = preg_replace('/(.*)\.$/', '\\1', $regs[4]);
        }
    }

    /* }}} */
    /* Net_DNS_RR_SRV::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->port) {
            return intval($this->preference) . ' ' . intval($this->weight) . ' ' . intval($this->port) . ' ' . $this->target . '.';
        }
        return '; no data';
    }

    /* }}} */
    /* Net_DNS_RR_SRV::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if (isset($this->preference)) {
            $rdata = pack('nnn', $this->preference, $this->weight, $this->port);
            $rdata .= $packet->dn_comp($this->target, $offset + strlen($rdata));
            return $rdata;
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
