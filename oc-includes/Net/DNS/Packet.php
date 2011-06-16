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

/* Net_DNS_Packet object definition {{{ */
/**
 * A object represation of a DNS packet (RFC1035)
 *
 * This object is used to manage a DNS packet.  It contains methods for
 * DNS packet compression as defined in RFC1035, as well as parsing  a DNS
 * packet response from a DNS server, or building a DNS packet from  the
 * instance variables contained in the class.
 *
 * @package Net_DNS
 */
class Net_DNS_Packet
{
    /* class variable definitions {{{ */
    /**
     * debugging flag
     *
     * If set to true (non-zero), debugging code will be displayed as the
     * packet is parsed.
     *
     * @var boolean $debug
     * @access  public
     */
    var $debug;
    /**
     * A packet Header object.
     *
     * An object of type Net_DNS_Header which contains the header
     * information  of the packet.
     *
     * @var object Net_DNS_Header $header
     * @access  public
     */
    var $header;
    /**
     * A hash of compressed labels
     *
     * A list of all labels which have been compressed in the DNS packet
     * and  the location offset of the label within the packet.
     *
     * @var array   $compnames
     */
    var $compnames;
    /**
     * The origin of the packet, if the packet is a server response.
     *
     * This contains a string containing the IP address of the name server
     * from which the answer was given.
     *
     * @var string  $answerfrom
     * @access  public
     */
    var $answerfrom;
    /**
     * The size of the answer packet, if the packet is a server response.
     *
     * This contains a integer containing the size of the DNS packet the
     * server responded with if this packet was received by a DNS server
     * using the query() method.
     *
     * @var string  $answersize
     * @access  public
     */
    var $answersize;
    /**
     * An array of Net_DNS_Question objects
     *
     * Contains all of the questions within the packet.  Each question is
     * stored as an object of type Net_DNS_Question.
     *
     * @var array   $question
     * @access  public
     */
    var $question;
    /**
     * An array of Net_DNS_RR ANSWER objects
     *
     * Contains all of the answer RRs within the packet.  Each answer is
     * stored as an object of type Net_DNS_RR.
     *
     * @var array   $answer
     * @access  public
     */
    var $answer;
    /**
     * An array of Net_DNS_RR AUTHORITY objects
     *
     * Contains all of the authority RRs within the packet.  Each authority is
     * stored as an object of type Net_DNS_RR.
     *
     * @var array   $authority
     * @access  public
     */
    var $authority;
    /**
     * An array of Net_DNS_RR ADDITIONAL objects
     *
     * Contains all of the additional RRs within the packet.  Each additional is
     * stored as an object of type Net_DNS_RR.
     *
     * @var array   $additional
     * @access  public
     */
    var $additional;

    const INT32SZ = 4;
    const INT16SZ = 2;
	
    /* }}} */
    /* class constructor - Net_DNS_Packet($debug = false) {{{ */
    /*
     * unfortunately (or fortunately), we can't follow the same
     * silly method for determining if name is a hostname or a packet
     * stream in PHP, since there is no ref() function.  So we're going
     * to define a new method called parse to deal with this
     * circumstance and another method called buildQuestion to build a question.
     * I like it better that way anyway.
     */
    /**
     * Initalizes a Net_DNS_Packet object
     *
     * @param boolean $debug Turns debugging on or off
     */
    function Net_DNS_Packet($debug = false)
    {
        $this->debug = $debug;
        $this->compnames = array();
    }

    /* }}} */
    /* Net_DNS_Packet::buildQuestion($name, $type = "A", $class = "IN") {{{ */
    /**
     * Adds a DNS question to the DNS packet
     *
     * @param   string $name    The name of the record to query
     * @param   string $type    The type of record to query
     * @param   string $class   The class of record to query
     * @see Net_DNS::typesbyname(), Net_DNS::classesbyname()
     */
    function buildQuestion($name, $type = 'A', $class = 'IN')
    {
        $this->header = new Net_DNS_Header();
        $this->header->qdcount = 1;
        $this->question[0] = new Net_DNS_Question($name, $type, $class);
        $this->answer = null;
        $this->authority = null;
        $this->additional = null;
        /* Do not print question packet
        if ($this->debug) {
            $this->display();
        }
        */
    }

    /* }}} */
    /* Net_DNS_Packet::parse($data) {{{ */
    /**
     * Parses a DNS packet returned by a DNS server
     *
     * Parses a complete DNS packet and builds an object hierarchy
     * containing all of the parts of the packet:
     * <ul>
     *   <li>HEADER
     *   <li>QUESTION
     *   <li>ANSWER || PREREQUISITE
     *   <li>ADDITIONAL || UPDATE
     *   <li>AUTHORITY
     * </ul>
     *
     * @param string $data  A binary string containing a DNS packet
     * @return boolean true on success, null on parser error
     */
    function parse($data)
    {
        if ($this->debug) {
            echo ';; HEADER SECTION' . "\n";
        }

        $this->header = new Net_DNS_Header($data);
        $header = $this->header;

        if ($this->debug) {
            $header->display();
        }

        /*
         *  Print and parse the QUESTION section of the packet
         */
        if ($this->debug) {
            echo "\n";
            $section = ($header->opcode  == 'UPDATE') ? 'ZONE' : 'QUESTION';
            echo ";; $section SECTION (" . $header->qdcount . ' record' .
                ($header->qdcount == 1 ? '' : 's') . ")\n";
        }

        $offset = 12;

        $this->question = array();
        for ($ctr = 0; $ctr < $header->qdcount; $ctr++) {
            list($qobj, $offset) = $this->parse_question($data, $offset);
            if (is_null($qobj)) {
                return null;
            }

            $this->question[count($this->question)] = $qobj;
            if ($this->debug) {
                echo ";;\n;";
                $qobj->display();
            }
        }

        /*
         *  Print and parse the PREREQUISITE or ANSWER  section of the packet
         */
        if ($this->debug) {
            echo "\n";
            $section = ($header->opcode == 'UPDATE') ? 'PREREQUISITE' :'ANSWER';
            echo ";; $section SECTION (" .
                $header->ancount . ' record' .
                (($header->ancount == 1) ? '' : 's') .
                ")\n";
        }

        $this->answer = array();
        for ($ctr = 0; $ctr < $header->ancount; $ctr++) {
            list($rrobj, $offset) = $this->parse_rr($data, $offset);

            if (is_null($rrobj)) {
                return null;
            }
            array_push($this->answer, $rrobj);
            if ($this->debug) {
                $rrobj->display();
            }
        }

        /*
         *  Print and parse the UPDATE or AUTHORITY section of the packet
         */
        if ($this->debug) {
            echo "\n";
            $section = ($header->opcode == 'UPDATE') ? 'UPDATE' : 'AUTHORITY';
            echo ";; $section SECTION (" .
                $header->nscount . ' record' .
                (($header->nscount == 1) ? '' : 's') .
                ")\n";
        }

        $this->authority = array();
        for ($ctr = 0; $ctr < $header->nscount; $ctr++) {
            list($rrobj, $offset) = $this->parse_rr($data, $offset);

            if (is_null($rrobj)) {
                $this->header = $header;
                return null;
            }
            array_push($this->authority, $rrobj);
            if ($this->debug) {
                $rrobj->display();
            }
        }

        /*
         *  Print and parse the ADDITIONAL section of the packet
         */
        if ($this->debug) {
            echo "\n";
            echo ';; ADDITIONAL SECTION (' .
                $header->arcount . ' record' .
                (($header->arcount == 1) ? '' : 's') .
                ")\n";
        }

        $this->additional = array();
        for ($ctr = 0; $ctr < $header->arcount; $ctr++) {
            list($rrobj, $offset) = $this->parse_rr($data, $offset);

            if (is_null($rrobj)) {
                $this->header = $header;
                return null;
            }
            array_push($this->additional, $rrobj);
            if ($this->debug) {
                $rrobj->display();
            }
        }

        $this->header = $header;
        return true;
    }

    /* }}} */
    /* Net_DNS_Packet::data() {{{*/
    /**
     * Build a packet from a Packet object hierarchy
     *
     * Builds a valid DNS packet suitable for sending to a DNS server or
     * resolver client containing all of the data in the packet hierarchy.
     *
     * @return string A binary string containing a DNS Packet
     */
    function data()
    {
        $data = $this->header->data();

        $qdcount = $this->header->qdcount;
        $ancount = $this->header->ancount;
        $nscount = $this->header->nscount;
        $arcount = $this->header->arcount;
        for ($ctr = 0; $ctr < $qdcount; $ctr++) {
            $data .= $this->question[$ctr]->data($this, strlen($data));
        }

        for ($ctr = 0; $ctr < $ancount; $ctr++) {
            $data .= $this->answer[$ctr]->data($this, strlen($data));
        }

        for ($ctr = 0; $ctr < $nscount; $ctr++) {
            $data .= $this->authority[$ctr]->data($this, strlen($data));
        }

        for ($ctr = 0; $ctr < $arcount; $ctr++) {
            $data .= $this->additional[$ctr]->data($this, strlen($data));
        }

        return $data;
    }

    /*}}}*/
    /* Net_DNS_Packet::dn_comp($name, $offset) {{{*/
    /**
     * DNS packet compression method
     *
     * Returns a domain name compressed for a particular packet object, to
     * be stored beginning at the given offset within the packet data.  The
     * name will be added to a running list of compressed domain names for
     * future use.
     *
     * @param string    $name       The name of the label to compress
     * @param integer   $offset     The location offset in the packet to where
     *                              the label will be stored.
     * @return string   $compname   A binary string containing the compressed
     *                              label.
     * @see Net_DNS_Packet::dn_expand()
     */
    function dn_comp($name, $offset)
    {
        $names = explode('.', $name);
        $compname = '';
        while (count($names)) {
            $dname = join('.', $names);
            if (isset($this->compnames[$dname])) {
                $compname .= pack('n', 0xc000 | $this->compnames[$dname]);
                break;
            }

            $this->compnames[$dname] = $offset;
            $first = array_shift($names);
            $length = strlen($first);
            $compname .= pack('Ca*', $length, $first);
            $offset += $length + 1;
        }
        if (!count($names)) {
            $compname .= pack('C', 0);
        }
        return $compname;
    }

    /*}}}*/
    /* Net_DNS_Packet::dn_expand($packet, $offset) {{{ */
    /**
     * DNS packet decompression method
     *
     * Expands the domain name stored at a particular location in a DNS
     * packet.  The first argument is a variable containing  the packet
     * data.  The second argument is the offset within the  packet where
     * the (possibly) compressed domain name is stored.
     *
     * @param   string  $packet The packet data
     * @param   integer $offset The location offset in the packet of the
     *                          label to decompress.
     * @return  array   Returns a list of type array($name, $offset) where
     *                  $name is the name of the label which was decompressed
     *                  and $offset is the offset of the next field in the
     *                  packet.  Returns array(null, null) on error
     */
    function dn_expand($packet, $offset)
    {
        $packetlen = strlen($packet);
        $name = '';
        while (1) {
            if ($packetlen < ($offset + 1)) {
                return array(null, null);
            }

            $a = unpack("@$offset/Cchar", $packet);
            $len = (int)$a['char'];

            if ($len == 0) {
                $offset++;
                break;
            } else if (($len & 0xc0) == 0xc0) {
                if ($packetlen < ($offset + Net_DNS_Packet::INT16SZ)) {
                    return array(null, null);
                }
                $ptr = unpack("@$offset/ni", $packet);
                $ptr = $ptr['i'];
                $ptr = $ptr & 0x3fff;
                $name2 = Net_DNS_Packet::dn_expand($packet, $ptr);

                if (is_null($name2[0])) {
                    return array(null, null);
                }
                $name .= $name2[0];
                $offset += Net_DNS_Packet::INT16SZ;
                break;
            } else {
                $offset++;

                if ($packetlen < ($offset + $len)) {
                    return array(null, null);
                }

                $elem = substr($packet, $offset, $len);
                $name .= $elem . '.';
                $offset += $len;
            }
        }
        $name = preg_replace('/\.$/', '', $name);
        return array($name, $offset);
    }

    /*}}}*/
    /* Net_DNS_Packet::label_extract($packet, $offset) {{{ */
    /**
     * DNS packet decompression method
     *
     * Extracts the label stored at a particular location in a DNS
     * packet.  The first argument is a variable containing  the packet
     * data.  The second argument is the offset within the  packet where
     * the (possibly) compressed domain name is stored.
     *
     * @param   string  $packet The packet data
     * @param   integer $offset The location offset in the packet of the
     *                          label to extract.
     * @return  array   Returns a list of type array($name, $offset) where
     *                  $name is the name of the label which was decompressed
     *                  and $offset is the offset of the next field in the
     *                  packet.  Returns array(null, null) on error
     */
    function label_extract($packet, $offset)
    {
        $packetlen = strlen($packet);
        $name = '';
        if ($packetlen < ($offset + 1)) {
            return array(null, null);
        }

        $a = unpack("@$offset/Cchar", $packet);
        $len = $a['char'];
        $offset++;

        if ($len + $offset > $packetlen) {
            $name = substr($packet, $offset);
            $offset = $packetlen;
        } else {
            $name = substr($packet, $offset, $len);
            $offset += $len;
        }
        return array($name, $offset);
    }

    /*}}}*/
    /* Net_DNS_Packet::parse_question($data, $offset) {{{ */
    /**
     * Parses the question section of a packet
     *
     * Examines a DNS packet at the specified offset and parses the data
     * of the QUESTION section.
     *
     * @param   string  $data   The packet data returned from the server
     * @param   integer $offset The location offset of the start of the
     *                          question section.
     * @return  array   An array of type array($q, $offset) where $q
     *                  is a Net_DNS_Question object and $offset is the
     *                  location of the next section of the packet which
     *                  needs to be parsed.
     */
    function parse_question($data, $offset)
    {
        list($qname, $offset) = $this->dn_expand($data, $offset);
        if (is_null($qname)) {
            return array(null, null);
        }

        if (strlen($data) < ($offset + 4)) {
            return array(null, null);
        }

        $q = unpack("@$offset/n2int", $data);
        $qtype = $q['int1'];
        $qclass = $q['int2'];
        $offset += 4;

        $qtype = Net_DNS::typesbyval($qtype);
        $qclass = Net_DNS::classesbyval($qclass);

        $q = new Net_DNS_Question($qname, $qtype, $qclass);
        return array($q, $offset);
    }

    /*}}}*/
    /* Net_DNS_Packet::parse_rr($data, $offset) {{{ */
    /**
     * Parses a resource record section of a packet
     *
     * Examines a DNS packet at the specified offset and parses the data
     * of a section which contains RRs (ANSWER, AUTHORITY, ADDITIONAL).
     *
     * @param string    $data   The packet data returned from the server
     * @param integer   $offset The location offset of the start of the resource
     *                          record section.
     * @return  array   An array of type array($rr, $offset) where $rr
     *                  is a Net_DNS_RR object and $offset is the
     *                  location of the next section of the packet which
     *                  needs to be parsed.
     */
    function parse_rr($data, $offset)
    {
        list($name, $offset) = $this->dn_expand($data, $offset);
        if ($name === null) {
            return array(null, null);
        }

        if (strlen($data) < ($offset + 10)) {
            return array(null, null);
        }

        $a = unpack("@$offset/n2tc/Nttl/nrdlength", $data);
        $type = $a['tc1'];
        $class = $a['tc2'];
        $ttl = $a['ttl'];
        $rdlength = $a['rdlength'];

        $type = Net_DNS::typesbyval($type);
        $class = Net_DNS::classesbyval($class);

        $offset += 10;
        if (strlen($data) < ($offset + $rdlength)) {
            return array(null, null);
        }

        $rrobj = &Net_DNS_RR::factory(array($name,
                    $type,
                    $class,
                    $ttl,
                    $rdlength,
                    $data,
                    $offset));

        if (is_null($rrobj)) {
            return array(null, null);
        }

        $offset += $rdlength;

        return array($rrobj, $offset);
    }

    /* }}} */
    /* Net_DNS_Packet::display() {{{ */
    /**
     * Prints out the packet in a human readable formatted string
     */
    function display()
    {
        echo $this->string();
    }

    /*}}}*/
    /* Net_DNS_Packet::string() {{{ */
    /**
     * Builds a human readable formatted string representing a packet
     */
    function string()
    {
        $retval = '';
        if ($this->answerfrom) {
            $retval .= ';; Answer received from ' . $this->answerfrom . '(' .
                $this->answersize . " bytes)\n;;\n";
        }

        $retval .= ";; HEADER SECTION\n";
        $retval .= $this->header->string();
        $retval .= "\n";

        $section = ($this->header->opcode == 'UPDATE') ? 'ZONE' : 'QUESTION';
        $retval .= ";; $section SECTION (" . $this->header->qdcount     .
            ' record' . ($this->header->qdcount == 1 ? '' : 's') .
            ")\n";

        foreach ($this->question as $qr) {
            $retval .= ';; ' . $qr->string() . "\n";
        }

        $section = ($this->header->opcode == 'UPDATE') ? 'PREREQUISITE' : 'ANSWER';
        $retval .= "\n;; $section SECTION (" . $this->header->ancount     .
            ' record' . ($this->header->ancount == 1 ? '' : 's') .
            ")\n";

        if (is_array($this->answer)) {
            foreach ($this->answer as $ans) {
                $retval .= ';; ' . $ans->string() . "\n";
            }
        }

        $section = ($this->header->opcode == 'UPDATE') ? 'UPDATE' : 'AUTHORITY';
        $retval .= "\n;; $section SECTION (" . $this->header->nscount     .
            ' record' . ($this->header->nscount == 1 ? '' : 's') .
            ")\n";

        if (is_array($this->authority)) {
            foreach ($this->authority as $auth) {
                $retval .= ';; ' . $auth->string() . "\n";
            }
        }

        $retval .= "\n;; ADDITIONAL SECTION (" . $this->header->arcount     .
            ' record' . ($this->header->arcount == 1 ? '' : 's') .
            ")\n";

        if (is_array($this->additional)) {
            foreach ($this->additional as $addl) {
                $retval .= ';; ' . $addl->string() . "\n";
            }
        }

        $retval .= "\n\n";
        return $retval;
    }

    /*}}}*/
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
