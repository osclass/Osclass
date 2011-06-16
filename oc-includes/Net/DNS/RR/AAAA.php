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

/* Net_DNS_RR_AAAA object definition {{{ */
/**
 * A representation of a resource record of type <b>AAAA</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_AAAA extends Net_DNS_RR
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
    /* class constructor - Net_DNS_RR_AAAA(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_AAAA(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            $this->address = Net_DNS_RR_AAAA::ipv6_decompress(substr($this->rdata, 0, $this->rdlength));
        } elseif (is_array($data)) {
            $this->address = $data['address'];
        } else {
            if (strlen($data)) {
                if (count($adata = explode(':', $data, 8)) >= 3) {
                    foreach($adata as $addr)
                        if (!preg_match('/^[0-9A-F]{0,4}$/i', $addr)) return;
                    $this->address = trim($data);
                }
            }
        }
    }

    /* }}} */
    /* Net_DNS_RR_AAAA::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->address)) {
            return $this->address;
        }
        return '; no data';
    }
    /* }}} */
    /* Net_DNS_RR_AAAA::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        return Net_DNS_RR_AAAA::ipv6_compress($this->address);
    }

    /* }}} */
    /* Net_DNS_RR_AAAA::ipv6_compress($addr) {{{ */
    function ipv6_compress($addr)
    {
        $numparts = count(explode(':', $addr));
		if ($numparts < 3 || $numparts > 8 ) {
            /* Non-sensical IPv6 address */
            return pack('n8', 0, 0, 0, 0, 0, 0, 0, 0);
        }
        if (strpos($addr, '::') !== false) {
			if (!preg_match('/^([0-9A-F]{0,4}:){0,7}(:[0-9A-F]{0,4}){0,7}$/i', $addr)) {
				return pack('n8', 0, 0, 0, 0, 0, 0, 0, 0);
			}
            /* First we have to normalize the address, turn :: into :0:0:0:0: */
            $filler = str_repeat(':0', 9 - $numparts) . ':';
            if (substr($addr, 0, 2) == '::') {
                $filler = "0$filler";
            }
            if (substr($addr, -2, 2) == '::') {
                $filler .= '0';
            }
            $addr = str_replace('::', $filler, $addr);
        } elseif (!preg_match('/^([0-9A-F]{0,4}:){7}[0-9A-F]{0,4}$/i', $addr)) {
			return pack('n8', 0, 0, 0, 0, 0, 0, 0, 0);
		}

        $aparts = explode(':', $addr);
        return pack('n8', hexdec($aparts[0]), hexdec($aparts[1]), hexdec($aparts[2]), hexdec($aparts[3]),
                          hexdec($aparts[4]), hexdec($aparts[5]), hexdec($aparts[6]), hexdec($aparts[7]));
    }
    /* }}} */

    /* Net_DNS_RR_AAAA::ipv6_decompress($pack) {{{ */
    function ipv6_decompress($pack)
    {
        if (strlen($pack) != 16) {
            /* Must be 8 shorts long */
            return '::';
        }
        $a = unpack('n8', $pack);
        $addr = vsprintf("%x:%x:%x:%x:%x:%x:%x:%x", $a);
        /* Shorthand the first :0:0: set into a :: */
        /* TODO: Make this is a single replacement pattern */
        if (substr($addr, -4) == ':0:0') {
            return preg_replace('/((:0){2,})$/', '::', $addr);
        } elseif (substr($addr, 0, 4) == '0:0:') {
            return '0:0:'. substr($addr, 4);
        } else {
            return preg_replace('/(:(0:){2,})/', '::', $addr);
        }
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