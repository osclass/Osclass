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

define('NET_DNS_DEFAULT_ALGORITHM', 'hmac-md5.sig-alg.reg.int');
define('NET_DNS_DEFAULT_FUDGE', 300);

/* Net_DNS_RR_TSIG definition {{{ */
/**
 * A representation of a resource record of type <b>TSIG</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_TSIG extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $time_signed;
    var $fudge;
    var $mac_size;
    var $mac;
    var $original_id;
    var $error;
    var $other_len;
    var $other_data;
    var $key;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_TSIG(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                $packet = new Net_DNS_Packet();

                list($alg, $offset) = $packet->dn_expand($data, $offset);
                $this->algorithm = $alg;

                $d = unpack("@$offset/nth/Ntl/nfudge/nmac_size", $data);
                $time_high = $d['th'];
                $time_low = $d['tl'];
                $this->time_signed = $time_low;
                $this->fudge = $d['fudge'];
                $this->mac_size = $d['mac_size'];
                $offset += 10;

                $this->mac = substr($data, $offset, $this->mac_size);
                $offset += $this->mac_size;

                $d = unpack("@$offset/noid/nerror/nolen", $data);
                $this->original_id = $d['oid'];
                $this->error = $d['error'];
                $this->other_len = $d['olen'];
                $offset += 6;

                if ($this->other_len) {
                    $odata = substr($data, $offset, $this->other_len);
                    $d = unpack('nodata_high/Nodata_low', $odata);
                    $this->other_data = $d['odata_low'];
                }
            }
        } elseif (is_array($data)) {
            $this->key = $data['key'];
            $this->algorithm = $data['algorithm'];
            $this->time_signed = $data['time_signed'];
            $this->fudge = $data['fudge'];
            $this->mac = $data['mac'];
            $this->original_id = $data['original_id'];
            $this->error = $data['error'];
            $this->other_len = $data['other_len'];
            $this->other_data = $data['other_data'];
        } else {
            if (strlen($data) && preg_match('/^(.*)$/', $data, $regs)) {
                $this->key = $regs[1];
            }

            $this->algorithm   = NET_DNS_DEFAULT_ALGORITHM;
            $this->time_signed = time();

            $this->fudge       = NET_DNS_DEFAULT_FUDGE;
            $this->mac_size    = 0;
            $this->mac         = '';
            $this->original_id = 0;
            $this->error       = 0;
            $this->other_len   = 0;
            $this->other_data  = '';

            // RFC 2845 Section 2.3
            $this->class = 'ANY';
        }
    }

    /* }}} */
    /* Net_DNS_RR_TSIG::rdatastr() {{{ */
    function rdatastr()
    {
        $error = $this->error;
        if (! $error) {
            $error = 'UNDEFINED';
        }

        if (strlen($this->algorithm)) {
            $rdatastr = $this->algorithm . '. ' . $this->time_signed . ' ' .
                $this->fudge . ' ';
            if ($this->mac_size && strlen($this->mac)) {
                $rdatastr .= ' ' . $this->mac_size . ' ' . base64_encode($this->mac);
            } else {
                $rdatastr .= ' 0 ';
            }
            $rdatastr .= ' ' . $this->original_id . ' ' . $error;
            if ($this->other_len && strlen($this->other_data)) {
                $rdatastr .= ' ' . $this->other_data;
            } else {
                $rdatastr .= ' 0 ';
            }
        } else {
            $rdatastr = '; no data';
        }

        return $rdatastr;
    }

    /* }}} */
    /* Net_DNS_RR_TSIG::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        $rdata = '';
        $sigdata = '';

        if (strlen($this->key)) {
            $key = $this->key;
            $key = preg_replace('/ /', '', $key);
            $key = base64_decode($key);

            $newpacket = $packet;
            $newoffset = $offset;
            array_pop($newpacket->additional);
            $newpacket->header->arcount--;
            $newpacket->compnames = array();

            /*
             * Add the request MAC if present (used to validate responses).
             */
            if (isset($this->request_mac)) {
                $sigdata .= pack('H*', $this->request_mac);
            }
            $sigdata .= $newpacket->data();

            /*
             * Don't compress the record (key) name.
             */
            $tmppacket = new Net_DNS_Packet;
            $sigdata .= $tmppacket->dn_comp(strtolower($this->name), 0);

            $sigdata .= pack('n', Net_DNS::classesbyname(strtoupper($this->class)));
            $sigdata .= pack('N', $this->ttl);

            /*
             * Don't compress the algorithm name.
             */
            $tmppacket->compnames = array();
            $sigdata .= $tmppacket->dn_comp(strtolower($this->algorithm), 0);

            $sigdata .= pack('nN', 0, $this->time_signed);
            $sigdata .= pack('n', $this->fudge);
            $sigdata .= pack('nn', $this->error, $this->other_len);

            if (strlen($this->other_data)) {
                $sigdata .= pack('nN', 0, $this->other_data);
            }

            $this->mac = $this->hmac_md5($sigdata, $key);
            $this->mac_size = strlen($this->mac);

            /*
             * Don't compress the algorithm name.
             */
            unset($tmppacket);
            $tmppacket = new Net_DNS_Packet;
            $rdata .= $tmppacket->dn_comp(strtolower($this->algorithm), 0);

            $rdata .= pack('nN', 0, $this->time_signed);
            $rdata .= pack('nn', $this->fudge, $this->mac_size);
            $rdata .= $this->mac;

            $rdata .= pack('nnn',$packet->header->id,
                    $this->error,
                    $this->other_len);

            if ($this->other_data) {
                $rdata .= pack('nN', 0, $this->other_data);
            }
        }
        return $rdata;
    }
    /* }}} */
    /* Net_DNS_RR_TSIG::error() {{{ */
    function error()
    {
        if ($this->error != 0) {
            $rcode = Net_DNS::rcodesbyval($error);
        }
        return $rcode;
    }
    /* }}} */

    /* Net_DNS_RR_TSIG::hmac() {{{ */
    // Calculate HMAC according to RFC2104
    // http://www.ietf.org/rfc/rfc2104.txt
    // posted by mina86 at tlen dot pl on http://php.net/manual/en/function.md5.php
    /**
     * @deprecated
     */
    function hmac($data, $key, $hash = 'md5', $blocksize = 64) {
        if ($hash === 'md5') {
            return $this->hmac_md5($data, $key);
        }

        return false;
    }

    /* Net_DNS_RR_TSIG::hmac_md5() {{{ */
    // Calculate HMAC according to RFC2104
    // http://www.ietf.org/rfc/rfc2104.txt
    function hmac_md5($data, $key) {
        if (strlen($key)>64) {
            $key = md5($key, true);
        }
        $key  = str_pad($key, 64, chr(0));
        $ipad = str_repeat(chr(0x36), 64);
        $opad = str_repeat(chr(0x5c), 64);
        return md5(($key^$opad) . md5(($key^$ipad) . $data, true), true);
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
 * expandtab on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
?>
