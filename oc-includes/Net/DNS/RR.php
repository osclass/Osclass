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

/* Include files {{{ */
require_once(LIB_PATH . "Net/DNS/RR/A.php");
require_once(LIB_PATH . "Net/DNS/RR/AAAA.php");
require_once(LIB_PATH . "Net/DNS/RR/NS.php");
require_once(LIB_PATH . "Net/DNS/RR/CNAME.php");
require_once(LIB_PATH . "Net/DNS/RR/PTR.php");
require_once(LIB_PATH . "Net/DNS/RR/SOA.php");
require_once(LIB_PATH . "Net/DNS/RR/MX.php");
require_once(LIB_PATH . "Net/DNS/RR/TSIG.php");
require_once(LIB_PATH . "Net/DNS/RR/TXT.php");
require_once(LIB_PATH . "Net/DNS/RR/HINFO.php");
require_once(LIB_PATH . "Net/DNS/RR/SRV.php");
require_once(LIB_PATH . "Net/DNS/RR/NAPTR.php");
require_once(LIB_PATH . "Net/DNS/RR/RP.php");
require_once(LIB_PATH . "Net/DNS/RR/SPF.php");
/* }}} */
/* Net_DNS_RR object definition {{{ */
/**
 * Resource Record object definition
 *
 * Builds or parses resource record sections of the DNS  packet including
 * the answer, authority, and additional  sections of the packet.
 *
 * @package Net_DNS
 */
class Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    /* }}} */

    /*
     * Use Net_DNS_RR::factory() instead
     *
     * @access private
     */
    /* class constructor - Net_DNS_RR($rrdata) {{{ */
    function Net_DNS_RR($rrdata)
    {
        if ($rrdata != 'getRR') { //BC check/warning remove later
            trigger_error("Please use Net_DNS_RR::factory() instead");
        }
    }

    /*
     * Returns an RR object, use this instead of constructor
     *
     * @param mixed $rr_rdata Options as string, array or data
     * @return object Net_DNS_RR or Net_DNS_RR_<type>
     * @access public
     * @see Net_DNS_RR::new_from_array Net_DNS_RR::new_from_data Net_DNS_RR::new_from_string
     */
    function &factory($rrdata, $update_type = '')
    {
        if (is_string($rrdata)) {
            $rr = &Net_DNS_RR::new_from_string($rrdata, $update_type);
        } elseif (count($rrdata) == 7) {
            list($name, $rrtype, $rrclass, $ttl, $rdlength, $data, $offset) = $rrdata;
            $rr = &Net_DNS_RR::new_from_data($name, $rrtype, $rrclass, $ttl, $rdlength, $data, $offset);
        } else {
            $rr = &Net_DNS_RR::new_from_array($rrdata);
        }
        return $rr;
    }

    /* }}} */
    /* Net_DNS_RR::new_from_data($name, $ttl, $rrtype, $rrclass, $rdlength, $data, $offset) {{{ */
    function &new_from_data($name, $rrtype, $rrclass, $ttl, $rdlength, $data, $offset)
    {
        $rr = new Net_DNS_RR('getRR');
        $rr->name = $name;
        $rr->type = $rrtype;
        $rr->class = $rrclass;
        $rr->ttl = $ttl;
        $rr->rdlength = $rdlength;
        $rr->rdata = substr($data, $offset, $rdlength);
        if (class_exists('Net_DNS_RR_' . $rrtype)) {
            $scn = 'Net_DNS_RR_' . $rrtype;
            $rr = new $scn($rr, $data, $offset);
        }
        return $rr;
    }

    /* }}} */
    /* Net_DNS_RR::new_from_string($rrstring, $update_type = '') {{{ */
    function &new_from_string($rrstring, $update_type = '')
    {
        $rr = new Net_DNS_RR('getRR');
        $ttl = 0;
        $parts = preg_split('/[\s]+/', $rrstring);
        while (count($parts) > 0) {
            $s = array_shift($parts);
            if (!isset($name)) {
                $name = preg_replace('/\.+$/', '', $s);
            } else if (preg_match('/^\d+$/', $s)) {
                $ttl = $s;
            } else if (!isset($rrclass) && ! is_null(Net_DNS::classesbyname(strtoupper($s)))) {
                $rrclass = strtoupper($s);
                $rdata = join(' ', $parts);
            } else if (! is_null(Net_DNS::typesbyname(strtoupper($s)))) {
                $rrtype = strtoupper($s);
                $rdata = join(' ', $parts);
                break;
            } else {
                break;
            }
        }

        /*
         *  Do we need to do this?
         */
        $rdata = trim(chop($rdata));

        if (! strlen($rrtype) && strlen($rrclass) && $rrclass == 'ANY') {
            $rrtype = $rrclass;
            $rrclass = 'IN';
        } else if (! isset($rrclass)) {
            $rrclass = 'IN';
        }

        if (! strlen($rrtype)) {
            $rrtype = 'ANY';
        }

        if (strlen($update_type)) {
            $update_type = strtolower($update_type);
            if ($update_type == 'yxrrset') {
                $ttl = 0;
                if (! strlen($rdata)) {
                    $rrclass = 'ANY';
                }
            } else if ($update_type == 'nxrrset') {
                $ttl = 0;
                $rrclass = 'NONE';
                $rdata = '';
            } else if ($update_type == 'yxdomain') {
                $ttl = 0;
                $rrclass = 'ANY';
                $rrtype = 'ANY';
                $rdata = '';
            } else if ($update_type == 'nxdomain') {
                $ttl = 0;
                $rrclass = 'NONE';
                $rrtype = 'ANY';
                $rdata = '';
            } else if (preg_match('/^(rr_)?add$/', $update_type)) {
                $update_type = 'add';
                if (! $ttl) {
                    $ttl = 86400;
                }
            } else if (preg_match('/^(rr_)?del(ete)?$/', $update_type)) {
                $update_type = 'del';
                $ttl = 0;
                $rrclass = $rdata ? 'NONE' : 'ANY';
            }
        }

        if (strlen($rrtype)) {
            $rr->name = $name;
            $rr->type = $rrtype;
            $rr->class = $rrclass;
            $rr->ttl = $ttl;
            $rr->rdlength = 0;
            $rr->rdata = '';

            if (class_exists('Net_DNS_RR_' . $rrtype)) {
                $scn = 'Net_DNS_RR_' . $rrtype;

                $obj = new $scn($rr, $rdata);
                return $obj;
            }

            return $rr;

        }

        return null;
    }

    /* }}} */
    /* Net_DNS_RR::new_from_array($rrarray) {{{ */
    function &new_from_array($rrarray)
    {
        $rr = new Net_DNS_RR('getRR');
        foreach ($rrarray as $k => $v) {
            $rr->{strtolower($k)} = $v;
        }

        if (! strlen($rr->name)) {
            return null;
        }
        if (! strlen($rr->type)){
            return null;
        }
        if (! $rr->ttl) {
            $rr->ttl = 0;
        }
        if (! strlen($rr->class)) {
            $rr->class = 'IN';
        }
        if (strlen($rr->rdata)) {
            $rr->rdlength = strlen($rr->rdata);
        }
        if (class_exists('Net_DNS_RR_' . $rr->type)) {
            $scn = 'Net_DNS_RR_' . $rr->type;

            $obj = new $scn($rr, !empty($rr->rdata) ? $rr->rdata : $rrarray);
            return $obj;
        }

        return $rr;
    }

    /* }}} */
    /* Net_DNS_RR::display() {{{ */
    function display()
    {
        echo $this->string() . "\n";
    }

    /* }}} */
    /* Net_DNS_RR::string() {{{ */
    function string()
    {
        return $this->name . ".\t" . (strlen($this->name) < 16 ? "\t" : '') .
                $this->ttl  . "\t"  .
                $this->class. "\t"  .
                $this->type . "\t"  .
                $this->rdatastr();

    }

    /* }}} */
    /* Net_DNS_RR::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->rdlength) {
            return '; rdlength = ' . $this->rdlength;
        }
        return '; no data';
    }

    /* }}} */
    /* Net_DNS_RR::rdata() {{{ */
    function rdata(&$packetORrdata, $offset = '')
    {
        if ($offset) {
            return $this->rr_rdata($packetORrdata, $offset);
        } else if (strlen($this->rdata)) {
            return $this->rdata;
        } else {
            return null;
        }
    }

    /* }}} */
    /* Net_DNS_RR::rr_rdata($packet, $offset) {{{ */
    function rr_rdata(&$packet, $offset)
    {
        return (strlen($this->rdata) ? $this->rdata : '');
    }
    /* }}} */
    /* Net_DNS_RR::data() {{{ */
    function data(&$packet, $offset)
    {
        $data = $packet->dn_comp($this->name, $offset);
        $data .= pack('n', Net_DNS::typesbyname(strtoupper($this->type)));
        $data .= pack('n', Net_DNS::classesbyname(strtoupper($this->class)));
        $data .= pack('N', $this->ttl);

        $offset += strlen($data) + 2;  // The 2 extra bytes are for rdlength

        $rdata = $this->rdata($packet, $offset);
        $data .= pack('n', strlen($rdata));
        $data .= $rdata;

        return $data;
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