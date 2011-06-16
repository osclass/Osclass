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

/* Net_DNS_RR_TXT definition {{{ */
/**
 * A representation of a resource record of type <b>TXT</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_TXT extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $text;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_TXT(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                $maxoffset = $this->rdlength + $offset;
                while ($maxoffset > $offset) {
                    list($text, $offset) = Net_DNS_Packet::label_extract($data, $offset);
                    $this->text[] = $text;
                }
            }
        } elseif (is_array($data)) {
            $this->text = $data['text'];
        } else {
            $data = str_replace('\\\\', chr(1) . chr(1), $data); /* disguise escaped backslash */
            $data = str_replace('\\"', chr(2) . chr(2), $data); /* disguise \" */

            preg_match('/("[^"]*"|[^ \t]*)[ \t]*$/', $data, $regs);
            $regs[1] = str_replace(chr(2) . chr(2), '\\"', $regs[1]);
            $regs[1] = str_replace(chr(1) . chr(1), '\\\\', $regs[1]);
            $regs[1] = stripslashes($regs[1]);

            $this->text = $regs[1];
        }
    }

    /* }}} */
    /* Net_DNS_RR_TXT::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->text) {
             if (is_array($this->text)) {
                 $tmp = array();
                 foreach ($this->text as $t) {
                     $tmp[] = '"'.addslashes($t).'"';
                 }
                 return implode(' ',$tmp);
             } else {
                 return '"' . addslashes($this->text) . '"';
             }
        } else return '; no data';
    }

    /* }}} */
    /* Net_DNS_RR_TXT::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->text) {
            $rdata  = pack('C', strlen($this->text)) . $this->text;
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
