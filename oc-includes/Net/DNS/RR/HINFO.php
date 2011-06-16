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

/* Net_DNS_RR_HINFO definition {{{ */
/**
 * A representation of a resource record of type <b>HINFO</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_HINFO extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $cpu;
    var $os;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_HINFO(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                list($cpu, $offset) = Net_DNS_Packet::label_extract($data, $offset);
                list($os,  $offset) = Net_DNS_Packet::label_extract($data, $offset);

                $this->cpu = $cpu;
                $this->os  = $os;
            }
        } elseif (is_array($data)) {
            $this->cpu = $data['cpu'];
            $this->os = $data['os'];
        } else {
            $data = str_replace('\\\\', chr(1) . chr(1), $data); /* disguise escaped backslash */
            $data = str_replace('\\"', chr(2) . chr(2), $data); /* disguise \" */

            preg_match('/("[^"]*"|[^ \t]*)[ \t]+("[^"]*"|[^ \t]*)[ \t]*$/', $data, $regs);
            foreach($regs as $idx => $value) {
                $value = str_replace(chr(2) . chr(2), '\\"', $value);
                $value = str_replace(chr(1) . chr(1), '\\\\', $value);
                $regs[$idx] = stripslashes($value);
            }

            $this->cpu = $regs[1];
            $this->os = $regs[2];
        }
    }

    /* }}} */
    /* Net_DNS_RR_HINFO::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->text) {
            return '"' . addslashes($this->cpu) . '" "' . addslashes($this->os) . '"';
        } else return '; no data';
    }

    /* }}} */
    /* Net_DNS_RR_HINFO::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->text) {
            $rdata  = pack('C', strlen($this->cpu)) . $this->cpu;
            $rdata .= pack('C', strlen($this->os))  . $this->os;
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