<?php

/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * check if the item is expired
 */
function osc_isExpired($dt_expiration) {
    $now       = date("Ymdhis");

    $dt_expiration = str_replace(' ', '', $dt_expiration);
    $dt_expiration = str_replace('-', '', $dt_expiration);
    $dt_expiration = str_replace(':', '', $dt_expiration);

    if ($dt_expiration > $now) {
        return false;
    } else {
        return true;
    }
}
/**
 * Remove resources from disk
 * @param <type> $id
 * @param boolean $admin
 * @return boolean
 */
function osc_deleteResource( $id , $admin) {
    if( is_array( $id ) ){
        $id = $id[0];
    }
    $resource = ItemResource::newInstance()->findByPrimaryKey($id) ;
    if( !is_null($resource) ){
        Log::newInstance()->insertLog('item', 'delete resource', $resource['pk_i_id'], $id, $admin?'admin':'user', $admin ? osc_logged_admin_id() : osc_logged_user_id()) ;

        $backtracel = '';
        foreach(debug_backtrace() as $k=>$v){
            if($v['function'] == "include" || $v['function'] == "include_once" || $v['function'] == "require_once" || $v['function'] == "require"){
                $backtracel .= "#".$k." ".$v['function']."(".$v['args'][0].") called@ [".$v['file'].":".$v['line']."] / ";
            }else{
                $backtracel .= "#".$k." ".$v['function']." called@ [".$v['file'].":".$v['line']."] / ";
            }
        }

        Log::newInstance()->insertLog('item', 'delete resource backtrace', $resource['pk_i_id'], $backtracel, $admin?'admin':'user', $admin ? osc_logged_admin_id() : osc_logged_user_id()) ;

        @unlink(osc_base_path() . $resource['s_path'] .$resource['pk_i_id'].".".$resource['s_extension']);
        @unlink(osc_base_path() . $resource['s_path'] .$resource['pk_i_id']."_original.".$resource['s_extension']);
        @unlink(osc_base_path() . $resource['s_path'] .$resource['pk_i_id']."_thumbnail.".$resource['s_extension']);
        @unlink(osc_base_path() . $resource['s_path'] .$resource['pk_i_id']."_preview.".$resource['s_extension']);
        osc_run_hook('delete_resource', $resource);
    }
}

/**
 * Tries to delete the directory recursivaly.
 * @return true on success.
 */
function osc_deleteDir($path) {
    if (!is_dir($path))
        return false;

    $fd = @opendir($path);
    if (!$fd)
        return false;

    while ($file = @readdir($fd)) {
        if ($file != '.' && $file != '..') {
            if (!is_dir($path . '/' . $file)) {
                if (!@unlink($path . '/' . $file)) {
                    closedir($fd);
                    return false;
                } else {
                    osc_deleteDir($path . '/' . $file);
                }
            } else {
                osc_deleteDir($path . '/' . $file);
            }
        }
    }
    closedir($fd);

    return @rmdir($path);
}

/**
 * Unpack a ZIP file into the specific path in the second parameter.
 * @return true on success.
 */
function osc_packageExtract($zipPath, $path) {
    if(!file_exists($path)) {
        if (!@mkdir($path, 0666)) {
            return false;
        }
    }

    @chmod($path, 0777);

    $zip = new ZipArchive;
    if ($zip->open($zipPath) === true) {
        $zip->extractTo($path);
        $zip->close();
        return true;
    } else {
        return false;
    }
}

/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_path($file) {
    // Sanitize windows paths and duplicated slashes
    $file = preg_replace('|/+|','/', str_replace('\\','/',$file));
    $plugin_path = preg_replace('|/+|','/', str_replace('\\','/', osc_plugins_path()));
    $file = $plugin_path . preg_replace('#^.*oc-content\/plugins\/#','',$file);
    return $file;
}

/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_url($file) {
    // Sanitize windows paths and duplicated slashes
    $dir = preg_replace('|/+|','/', str_replace('\\','/',dirname($file)));
    $dir = WEB_PATH . 'oc-content/plugins/' . preg_replace('#^.*oc-content\/plugins\/#','',$dir) . "/";
    return $dir;
}

/**
 * Fix the problem of symbolics links in the path of the file
 *
 * @param string $file The filename of plugin.
 * @return string The fixed path of a plugin.
 */
function osc_plugin_folder($file) {
    // Sanitize windows paths and duplicated slashes
    $dir = preg_replace('|/+|','/', str_replace('\\','/',dirname($file)));
    $dir = preg_replace('#^.*oc-content\/plugins\/#','',$dir) . "/";
    return $dir;
}

/**
 * Serialize the data (usefull at plugins activation)
 * @return the data serialized
 */
function osc_serialize($data) {

    if (!is_serialized($data)) {
        if (is_array($data) || is_object($data)) {
            return serialize($data);
        }
    }

    return $data;
}

/**
 * Unserialize the data (usefull at plugins activation)
 * @return the data unserialized
 */
function osc_unserialize($data) {
    if (is_serialized($data)) { // don't attempt to unserialize data that wasn't serialized going in
        return @unserialize($data);
    }

    return $data;
}

/**
 * Checks is $data is serialized or not
 * @return bool False if not serialized and true if it was.
 */
function is_serialized($data) {
    // if it isn't a string, it isn't serialized
    if (!is_string($data))
        return false;
    $data = trim($data);
    if ('N;' == $data)
        return true;
    if (!preg_match('/^([adObis]):/', $data, $badions))
        return false;
    switch ($badions[1]) {
        case 'a' :
        case 'O' :
        case 's' :
            if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                return true;
            break;
    }
    return false;
}

/**
 * VERY BASIC
 * Perform a POST request, so we could launch fake-cron calls and other core-system calls without annoying the user
 */
function osc_doRequest($url, $_data) {
    if (function_exists('fsockopen')) {
        $data = http_build_query($_data);

        // parse the given URL
        $url = parse_url($url);

        // extract host and path:
        $host = $url['host'];
        $path = $url['path'];

        // open a socket connection on port 80
        // use localhost in case of issues with NATs (hairpinning)
        $fp = @fsockopen($host, 80);

        if($fp!==false) {
            $out  = "POST $path HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Referer: OSClass (v.". osc_version() .")\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: ".strlen($data)."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $out .= "$data";
            fwrite($fp, $out);
            fclose($fp);
        }
    }
}

function osc_sendMail($params) {
    // DO NOT send mail if it's a demo
    if( defined('DEMO') ) {
        return false;
    }

    if( key_exists('add_bcc', $params) ) {
        if( !is_array($params['add_bcc']) && $params['add_bcc'] != '' ) {
            $params['add_bcc'] = array($params['add_bcc']) ;
        }
    }

    require_once osc_lib_path() . 'phpmailer/class.phpmailer.php' ;

    if( osc_mailserver_pop() ) {
        require_once osc_lib_path() . 'phpmailer/class.pop3.php' ;
        $pop = new POP3() ;
        $pop->Authorise(
                ( isset($params['host']) ) ? $params['host'] : osc_mailserver_host(),
                ( isset($params['port']) ) ? $params['port'] : osc_mailserver_port(),
                30,
                ( isset($params['username']) ) ? $params['username'] : osc_mailserver_username(),
                ( isset($params['username']) ) ? $params['username'] : osc_mailserver_username(),
                0
        ) ;
    }

    $mail = new PHPMailer(true) ;
    try {
        $mail->CharSet = 'utf-8' ;

        if( osc_mailserver_auth() ) {
            $mail->IsSMTP() ;
            $mail->SMTPAuth = true ;
        } else if( osc_mailserver_pop() ) {
            $mail->IsSMTP() ;
        }

        $mail->SMTPSecure = ( isset($params['ssl']) ) ? $params['ssl'] : osc_mailserver_ssl() ;
        $mail->Username = ( isset($params['username']) ) ? $params['username'] : osc_mailserver_username() ;
        $mail->Password = ( isset($params['password']) ) ? $params['password'] : osc_mailserver_password() ;
        $mail->Host = ( isset($params['host']) ) ? $params['host'] : osc_mailserver_host() ;
        $mail->Port = ( isset($params['port']) ) ? $params['port'] : osc_mailserver_port() ;
        $mail->From = ( isset($params['from']) ) ? $params['from'] : 'osclass@' . osc_get_domain() ;
        if( osc_mailserver_username() !== '' ) {
            $mail->From = osc_mailserver_username();
        }
        if( array_key_exists('username', $params) ) {
            $mail->From = $params['username'];
        }
        $mail->FromName = ( isset($params['from_name']) ) ? $params['from_name'] : osc_page_title() ;
        $mail->Subject = ( isset($params['subject']) ) ? $params['subject'] : '' ;
        $mail->Body = ( isset($params['body']) ) ? $params['body'] : '' ;
        $mail->AltBody = ( isset($params['alt_body']) ) ? $params['alt_body'] : '' ;
        $to = ( isset($params['to']) ) ? $params['to'] : '' ;
        $to_name = ( isset($params['to_name']) ) ? $params['to_name'] : '' ;

        if( key_exists('add_bcc', $params) ) {
            foreach( $params['add_bcc'] as $bcc ) {
                $mail->AddBCC($bcc) ;
            }
        }

        if( isset($params['reply_to']) ) {
            $mail->AddReplyTo($params['reply_to']) ;
        }

        if( isset($params['attachment']) ) {
            $mail->AddAttachment($params['attachment']) ;
        }

        $mail->IsHTML(true) ;
        $mail->AddAddress($to, $to_name) ;
        $mail->Send() ;
        return true ;
    } catch (phpmailerException $e) {
        error_log("phpmailerException in osc_sendMail() Error: ".$mail->ErrorInfo, 0);
        return false ;
    } catch (Exception $e) {
        error_log("Exception in osc_sendMail() Error".$mail->ErrorInfo, 0);
        return false ;
    }

    return false ;
}

function osc_mailBeauty($text, $params) {

    $text = str_ireplace($params[0], $params[1], $text) ;
    $kwords = array(
        '{WEB_URL}',
        '{WEB_TITLE}',
        '{WEB_LINK}' ,
        '{CURRENT_DATE}',
        '{HOUR}',
        '{IP_ADDRESS}'
    );
    $rwords = array(
        osc_base_url(),
        osc_page_title(),
        '<a href="' . osc_base_url() . '">' . osc_page_title() . '</a>',
        date('Y-m-d H:i:s'),
        date('H:i'),
        $_SERVER['REMOTE_ADDR']
    );
    $text = str_ireplace($kwords, $rwords, $text) ;

    return $text ;
}


function osc_copy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755)) {
    $result =true;
    if (is_file($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if (!file_exists($dest)) {
                cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
            }
            $__dest=$dest."/".basename($source);
        } else {
            $__dest=$dest;
        }
        if(function_exists('copy')) {
            $result = @copy($source, $__dest);
        } else {
            $result=osc_copyemz($source, $__dest);
        }
        @chmod($__dest,$options['filePermission']);

    } elseif(is_dir($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if ($source[strlen($source)-1]=='/') {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest=$dest.basename($source);
                @mkdir($dest);
                @chmod($dest,$options['filePermission']);
            }
        } else {
            if ($source[strlen($source)-1]=='/') {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                @chmod($dest,$options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                @chmod($dest,$options['filePermission']);
            }
        }

        $dirHandle=opendir($source);
        $result = true;
        while($file=readdir($dirHandle)) {
            if($file!="." && $file!="..") {
                if(!is_dir($source."/".$file)) {
                    $__dest=$dest."/".$file;
                } else {
                    $__dest=$dest."/".$file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $data = osc_copy($source."/".$file, $__dest, $options);
                if($data==false) {
                    $result = false;
                }
            }
        }
        closedir($dirHandle);

    } else {
        $result=true;
    }
    return $result;
}



function osc_copyemz($file1,$file2){
    $contentx =@file_get_contents($file1);
    $openedfile = fopen($file2, "w");
    fwrite($openedfile, $contentx);
    fclose($openedfile);
    if ($contentx === FALSE) {
        $status=false;
    } else {
        $status=true;
    }

    return $status;
}

/**
 * Dump osclass database into path file
 *
 * @param type $path
 * @param type $file
 * @return type
 */
function osc_dbdump($path, $file) {

    require_once LIB_PATH . 'osclass/model/Dump.php';
    if ( !is_writable($path) ) return -4 ;
    if($path == '') return -1 ;

    //checking connection
    $dump = Dump::newInstance();
    if (!$dump) return -2 ;

    $path .= $file ;
    $result = $dump->showTables();

    if(!$result) {
        $_str = '' ;
        $_str .= '/* no tables in ' . DB_NAME . ' */' ;
        $_str .= "\n" ;

        $f = fopen($path, "a") ;
        fwrite($f, $_str) ;
        fclose($f) ;

        return -3 ;
    }

    $_str = '' ;
    $_str .= '/* OSCLASS MYSQL Autobackup (' . date('Y-m-d H:i:s') . ') */' ;
    $_str .= "\n" ;

    $f = fopen($path, "a") ;
    fwrite($f, $_str) ;
    fclose($f) ;

    $tables = array() ;
    foreach($result as $_table) {
        $tableName = current($_table);
        $tables[$tableName] = $tableName;
    }

    $tables_order = array('t_locale', 't_country', 't_currency', 't_region', 't_city', 't_city_area', 't_widget', 't_admin', 't_user', 't_user_description', 't_category', 't_category_description', 't_category_stats', 't_item', 't_item_description', 't_item_location', 't_item_stats', 't_item_resource', 't_item_comment', 't_preference', 't_user_preferences', 't_pages', 't_pages_description', 't_plugin_category', 't_cron', 't_alerts', 't_keywords', 't_meta_fields', 't_meta_categories', 't_item_meta');
    // Backup default OSClass tables in order, so no problem when importing them back
    foreach($tables_order as $table) {
        if(array_key_exists(DB_TABLE_PREFIX . $table, $tables)) {
            $dump->table_structure($path, DB_TABLE_PREFIX . $table) ;
            $dump->table_data($path, DB_TABLE_PREFIX . $table) ;
            unset($tables[DB_TABLE_PREFIX . $table]) ;
        }
    }

    // Backup the rest of tables
    foreach($tables as $table) {
        $dump->table_structure($path, $table) ;
        $dump->table_data($path, $table) ;
    }

    return 1 ;
}

// -----------------------------------------------------------------------------

/**
 * Returns true if there is curl on system environment
 *
 * @return type
 */
function testCurl() {
    if ( ! function_exists( 'curl_init' ) || ! function_exists( 'curl_exec' ) )
        return false;

    return true;
}

/**
 * Returns true if there is fsockopen on system environment
 *
 * @return type
 */
function testFsockopen() {
    if ( ! function_exists( 'fsockopen' ) )
        return false;

    return true;
}

/**
 * IF http-chunked-decode not exist implement here
 * @since 3.0
 */
if( !function_exists('http_chunked_decode') ) {
    /**
     * dechunk an http 'transfer-encoding: chunked' message
     *
     * @param string $chunk the encoded message
     * @return string the decoded message.  If $chunk wasn't encoded properly it will be returned unmodified.
     */
    function http_chunked_decode($chunk) {
        $pos = 0;
        $len = strlen($chunk);
        $dechunk = null;

        while(($pos < $len)
            && ($chunkLenHex = substr($chunk,$pos, ($newlineAt = strpos($chunk,"\n",$pos+1))-$pos)))
        {
            if (! is_hex($chunkLenHex)) {
                trigger_error('Value is not properly chunk encoded', E_USER_WARNING);
                return $chunk;
            }

            $pos = $newlineAt + 1;
            $chunkLen = hexdec(rtrim($chunkLenHex,"\r\n"));
            $dechunk .= substr($chunk, $pos, $chunkLen);
            $pos = strpos($chunk, "\n", $pos + $chunkLen) + 1;
        }
        return $dechunk;
    }
}

/**
 * determine if a string can represent a number in hexadecimal
 *
 * @since 3.0
 * @param string $hex
 * @return boolean true if the string is a hex, otherwise false
 */
function is_hex($hex) {
    // regex is for weenies
    $hex = strtolower(trim(ltrim($hex,"0")));
    if (empty($hex)) { $hex = 0; };
    $dec = hexdec($hex);
    return ($hex == dechex($dec));
}

/**
 * Process response and return headers and body
 *
 * @since 3.0
 * @param type $content
 * @return type
 */
function processResponse($content)
{
    $res = explode("\r\n\r\n", $content);
    $headers = $res[0];
    $body    = isset($res[1]) ? $res[1] : '';

    if (!is_string($headers)) {
        return array();
    }

    return array('headers' => $headers, 'body' => $body);
}

/**
 * Parse headers and return into array format
 *
 * @param type $headers
 * @return type
 */
function processHeaders($headers)
{
    $headers = str_replace("\r\n", "\n", $headers);
    $headers = preg_replace('/\n[ \t]/', ' ', $headers);
    $headers = explode("\n", $headers);
    $tmpHeaders = $headers;
    $headers = array();

    foreach ($tmpHeaders as $aux) {
        if (preg_match('/^(.*):\s(.*)$/', $aux, $matches)) {
            $headers[strtolower($matches[1])] = $matches[2];
        }
    }
    return $headers;
}

/**
 * Download file using fsockopen
 *
 * @since 3.0
 * @param type $sourceFile
 * @param type $fileout
 */
function download_fsockopen($sourceFile, $fileout = null)
{
    // parse URL
    $aUrl = parse_url($sourceFile);
    $host = $aUrl['host'];
    if ('localhost' == strtolower($host))
        $host = '127.0.0.1';

    $link = $aUrl['path'] . ( isset($aUrl['query']) ? '?' . $aUrl['query'] : '' );

    if (empty($link))
        $link .= '/';

    $fp = @fsockopen($host, 80, $errno, $errstr, 30);
    if (!$fp) {

    } else {
        $out = "GET $link HTTP/1.1\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= "\r\n";
        fwrite($fp, $out);

        $contents = '';
        while (!feof($fp)) {
            $contents.= fgets($fp, 1024);
        }
        // check redirections ?
        // if (redirections) then do request again
        $aResult = processResponse($contents);
        $headers = processHeaders($aResult['headers']);

        $location = @$headers['location'];
        if (isset($location) && $location != "") {
            $aUrl = parse_url($headers['location']);

            $host = $aUrl['host'];
            if ('localhost' == strtolower($host))
                $host = '127.0.0.1';

            $requestPath = $aUrl['path'] . ( isset($aUrl['query']) ? '?' . $aUrl['query'] : '' );

            if (empty($requestPath))
                $requestPath .= '/';

            download_fsockopen($host, $requestPath, $fileout);
        } else {
            $body = $aResult['body'];
            $transferEncoding = @$headers['transfer-encoding'];
            if($transferEncoding == 'chunked' ) {
                $body = http_chunked_decode($aResult['body']);
            }
            if($fileout!=null) {
                $ff = @fopen($fileout, 'w+');
                fwrite($ff, $body);
                fclose($ff);
            } else {
                return $body;
            }
        }
        fclose($fp);
    }
}

function osc_downloadFile($sourceFile, $downloadedFile)
{
    if ( testCurl() ) {
        @set_time_limit(0);

        $fp = @fopen (osc_content_path() . 'downloads/' . $downloadedFile, 'w+');
        if($fp) {
            $ch = curl_init($sourceFile);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    } else if (testFsockopen()) { // test curl/fsockopen
        $downloadedFile = osc_content_path() . 'downloads/' . $downloadedFile;
        download_fsockopen($sourceFile, $downloadedFile);
        return true;
    }
}

function osc_file_get_contents($url)
{
    if( testCurl() ) {
        $ch = curl_init() ;
        curl_setopt($ch, CURLOPT_URL, $url) ;
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] . ' OSClass (v.' . osc_version() . ')') ;
        if( !defined('CURLOPT_RETURNTRANSFER') ) define('CURLOPT_RETURNTRANSFER', 1) ;
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);
        curl_close($ch);
    } else if( testFsockopen() ) {
        $data = download_fsockopen($url);
    }
    return $data;
}
// -----------------------------------------------------------------------------

/**
 * Check if we loaded some specific module of apache
 *
 * @param string $mod
 *
 * @return bool
 */
function apache_mod_loaded($mod) {
    if(function_exists('apache_get_modules')) {
        $modules = apache_get_modules();
        if(in_array($mod, $modules)) { return true; }
    } else if(function_exists('phpinfo')) {
        ob_start();
        phpinfo(INFO_MODULES);
        $content = ob_get_contents();
        ob_end_clean();
        if(stripos($content, $mod)!==FALSE) { return true; }
    }
    return false;
}

/**
 * Change version to param number
 *
 * @param mixed version
 */
function osc_changeVersionTo($version = null) {

    if($version != null) {
        Preference::newInstance()->update(array('s_value' => $version), array( 's_section' => 'osclass', 's_name' => 'version'));
        //XXX: I don't know if it's really needed. Only for reload the values of the preferences
        Preference::newInstance()->toArray() ;
    }
}

function strip_slashes_extended($array) {
    if(is_array($array)) {
        foreach($array as $k => &$v) {
            $v = strip_slashes_extended($v);
        }
    } else {
        $array = stripslashes($array);
    }
    return $array;
}

/**
 * Unzip's a specified ZIP file to a location
 *
 * @param string $file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function osc_unzip_file($file, $to) {
    if (!file_exists($to)) {
        if (!@mkdir($to, 0766)) {
            return 0;
        }
    }

    @chmod($to, 0777);

    if (!is_writable($to)) {
        return 0;
    }

    if (class_exists('ZipArchive')) {
        return _unzip_file_ziparchive($file, $to);
    }

    // if ZipArchive class doesn't exist, we use PclZip
    return _unzip_file_pclzip($file, $to);
}

/**
 * We assume that the $to path is correct and can be written. It unzips an archive using the PclZip library.
 *
 * @param string $file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function _unzip_file_ziparchive($file, $to) {
    $zip = new ZipArchive();
    $zipopen = $zip->open($file, 4);

    if ($zipopen !== true) {
        return 2;
    }
    // The zip is empty
    if($zip->numFiles==0) {
        return 2;
    }


    for ($i = 0; $i < $zip->numFiles; $i++) {
        $file = $zip->statIndex($i);

        if (!$file) {
            return -1;
        }

        if (substr($file['name'], 0, 9) === '__MACOSX/') {
            continue;
        }

        if (substr($file['name'], -1) == '/') {
            @mkdir($to . $file['name'], 0777);
            continue;
        }

        $content = $zip->getFromIndex($i);
        if ($content === false) {
            return -1;
        }

        $fp = @fopen($to . $file['name'], 'w');
        if (!$fp) {
            return -1;
        }

        @fwrite($fp, $content);
        @fclose($fp);
    }

    $zip->close();

    return 1;
}

/**
 * We assume that the $to path is correct and can be written. It unzips an archive using the PclZip library.
 *
 * @param string $zip_file Full path of the zip file
 * @param string $to Full path where it is going to be unzipped
 * @return int
 */
function _unzip_file_pclzip($zip_file, $to) {
    // first, we load the library
    require_once LIB_PATH . 'pclzip/pclzip.lib.php';

    $archive = new PclZip($zip_file);
    if (($files = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING)) == false) {
        return 2;
    }

    // check if the zip is not empty
    if (count($files) == 0) {
        return 2;
    }

    // Extract the files from the zip
    foreach ($files as $file) {
        if (substr($file['filename'], 0, 9) === '__MACOSX/') {
            continue;
        }

        if ($file['folder']) {
            @mkdir($to . $file['filename'], 0777);
            continue;
        }


        $fp = @fopen($to . $file['filename'], 'w');
        if (!$fp) {
            return -1;
        }

        @fwrite($fp, $file['content']);
        @fclose($fp);
    }

    return 1;
}


/**
 * Common interface to zip a specified folder to a file using ziparchive or pclzip
 *
 * @param string $archive_folder full path of the folder
 * @param string $archive_name full path of the destination zip file
 * @return int
 */
function osc_zip_folder($archive_folder, $archive_name) {
    if (class_exists('ZipArchive')) {
        return _zip_folder_ziparchive($archive_folder, $archive_name);
    }
    // if ZipArchive class doesn't exist, we use PclZip
    return _zip_folder_pclzip($archive_folder, $archive_name);
}

/**
 * Zips a specified folder to a file
 *
 * @param string $archive_folder full path of the folder
 * @param string $archive_name full path of the destination zip file
 * @return int
 */
function _zip_folder_ziparchive($archive_folder, $archive_name) {

    $zip = new ZipArchive;
    if ($zip -> open($archive_name, ZipArchive::CREATE) === TRUE) {
        $dir = preg_replace('/[\/]{2,}/', '/', $archive_folder."/");

        $dirs = array($dir);
        while (count($dirs)) {
            $dir = current($dirs);
            $zip -> addEmptyDir(str_replace(ABS_PATH, '', $dir));

            $dh = opendir($dir);
            while (false !== ($_file = readdir($dh))) {
                if ($_file != '.' && $_file != '..') {
                    if (is_file($dir.$_file)) {
                        $zip -> addFile($dir.$_file, str_replace(ABS_PATH, '', $dir.$_file));
                    } elseif (is_dir($dir.$_file)) {
                        $dirs[] = $dir.$_file."/";
                    }
                }
            }
            closedir($dh);
            array_shift($dirs);
        }
        $zip -> close();
        return true;
    } else {
        return false;
    }

}

/**
 * Zips a specified folder to a file
 *
 * @param string $archive_folder full path of the folder
 * @param string $archive_name full path of the destination zip file
 * @return int
 */
function _zip_folder_pclzip($archive_folder, $archive_name) {

    // first, we load the library
    require_once LIB_PATH . 'pclzip/pclzip.lib.php';

    $zip = new PclZip($archive_name);
    if($zip) {
        $dir = preg_replace('/[\/]{2,}/', '/', $archive_folder."/");

        $v_dir = osc_base_path();
        $v_remove = $v_dir;

        // To support windows and the C: root you need to add the
        // following 3 lines, should be ignored on linux
        if (substr($v_dir, 1,1) == ':') {
            $v_remove = substr($v_dir, 2);
        }
        $v_list = $zip->create($v_dir, PCLZIP_OPT_REMOVE_PATH, $v_remove);
        if ($v_list == 0) {
            return false;
        }
        return true;
    } else {
        return false;
    }

}

function osc_check_recaptcha() {

    require_once osc_lib_path() . 'recaptchalib.php';
    if ( Params::getParam("recaptcha_challenge_field") != '') {
        $resp = recaptcha_check_answer (osc_recaptcha_private_key()
                                        ,$_SERVER["REMOTE_ADDR"]
                                        ,Params::getParam("recaptcha_challenge_field")
                                        ,Params::getParam("recaptcha_response_field"));

        return $resp->is_valid;
    }

    return false;
}

function osc_check_dir_writable( $dir = ABS_PATH ) {
    clearstatcache();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if($file!="." && $file!="..") {
                if(is_dir(str_replace("//", "/", $dir . "/" . $file))) {
                    if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/themes")) {
                        if($file=="modern" || $file=="index.php") {
                            $res = osc_check_dir_writable( str_replace("//", "/", $dir . "/" . $file));
                            if(!$res) { return false; };
                        }
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/plugins")) {
                        if($file=="google_maps" || $file=="google_analytics" || $file=="index.php") {
                            $res = osc_check_dir_writable( str_replace("//", "/", $dir . "/" . $file));
                            if(!$res) { return false; };
                        }
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/languages")) {
                        if($file=="en_US" || $file=="index.php") {
                            $res = osc_check_dir_writable( str_replace("//", "/", $dir . "/" . $file));
                            if(!$res) { return false; };
                        }
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/downloads")) {
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/uploads")) {
                    } else {
                        $res = osc_check_dir_writable( str_replace("//", "/", $dir . "/" . $file));
                        if(!$res) { return false; };
                    }
                } else {
                    return is_writable( str_replace("//", "/", $dir . "/" . $file));
                }
            }
        }
        closedir($dh);
    }
    return true;
}



function osc_change_permissions( $dir = ABS_PATH ) {
    clearstatcache();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if($file!="." && $file!=".." && substr($file,0,1)!="." ) {
                if(is_dir(str_replace("//", "/", $dir . "/" . $file))) {
                    if(!is_writable(str_replace("//", "/", $dir . "/" . $file))) {
                        $res = @chmod( str_replace("//", "/", $dir . "/" . $file), 0777);
                    }
                    if(!$res) { echo str_replace("//", "/", $dir . "/" . $file);return false; };
                    if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/themes")) {
                        if($file=="modern" || $file=="index.php") {
                            $res = osc_change_permissions( str_replace("//", "/", $dir . "/" . $file));
                            if(!$res) { echo str_replace("//", "/", $dir . "/" . $file);return false; };
                        }
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/plugins")) {
                        if($file=="google_maps" || $file=="google_analytics" || $file=="index.php") {
                            $res = osc_change_permissions( str_replace("//", "/", $dir . "/" . $file));
                            if(!$res) { echo str_replace("//", "/", $dir . "/" . $file);return false; };
                        }
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/languages")) {
                        if($file=="en_US" || $file=="index.php") {
                            $res = osc_change_permissions( str_replace("//", "/", $dir . "/" . $file));
                            if(!$res) { echo str_replace("//", "/", $dir . "/" . $file);return false; };
                        }
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/downloads")) {
                    } else if(str_replace("//", "/", $dir)==(ABS_PATH . "oc-content/uploads")) {
                    } else {
                        $res = osc_change_permissions( str_replace("//", "/", $dir . "/" . $file));
                        if(!$res) { echo str_replace("//", "/", $dir . "/" . $file);return false; };
                    }
                } else {
                    if(!is_writable(str_replace("//", "/", $dir . "/" . $file))) {
                        return @chmod( str_replace("//", "/", $dir . "/" . $file), 0777);
                    } else {
                        return true;
                    }
                }
            }
        }
        closedir($dh);
    }
    return true;
}

function osc_save_permissions( $dir = ABS_PATH ) {
    $perms = array();
    $perms[$dir] = fileperms($dir);
    clearstatcache();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if($file!="." && $file!="..") {
                if(is_dir(str_replace("//", "/", $dir . "/" . $file))) {
                    $res = osc_save_permissions( str_replace("//", "/", $dir . "/" . $file));
                    foreach($res as $k => $v) {
                        $perms[$k] = $v;
                    }
                } else {
                    $perms[str_replace("//", "/", $dir . "/" . $file)] = fileperms( str_replace("//", "/", $dir . "/" . $file));
                }
            }
        }
        closedir($dh);
    }
    return $perms;
}


function osc_prepare_price($price) {
    return $price/1000000;
}

/**
 * Recursive glob function
 *
 * @param string $pattern
 * @param string $flags
 * @param string $path
 * @return array of files
 */
function rglob($pattern, $flags = 0, $path = '') {
    if (!$path && ($dir = dirname($pattern)) != '.') {
        if ($dir == '\\' || $dir == '/') $dir = '';
        return rglob(basename($pattern), $flags, $dir . '/');
    }
    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);
    foreach ($paths as $p) $files = array_merge($files, rglob($pattern, $flags, $p . '/'));
    return $files;
}

// Market util functions

function osc_check_plugin_update($update_uri, $version = null) {
    $uri = _get_market_url('plugins', $update_uri);
    if($uri != false) {
        return _need_update($uri, $version);
    }
    return false;
}

function osc_check_theme_update($update_uri, $version = null) {
    $uri = _get_market_url('themes', $update_uri);
    if($uri != false) {
        return _need_update($uri, $version);
    }
    return false;
}

function osc_check_language_update($update_uri, $version = null) {
    $uri = _get_market_url('languages', $update_uri);
    if($uri != false) {
        return _need_update($uri, $version);
    }
    return false;
}

function _get_market_url($type, $update_uri) {
    if( $update_uri == null ) {
        return false;
    }

    if(in_array($type, array('plugins', 'themes', 'languages') ) ) {
        $uri = '';
        if(stripos("http://", $update_uri)===FALSE ) {
            // OSCLASS OFFICIAL REPOSITORY
            $uri = osc_market_url($type, $update_uri);
        } else {
            // THIRD PARTY REPOSITORY
            if(!osc_market_external_sources()) {
                return false;
            }
            $uri = $update_uri;
        }
        return $uri;
    } else {
        return false;
    }
}

function _need_update($uri, $version) {
    if(false===($json=@osc_file_get_contents($uri))) {
        return false;
    } else {
        $data = json_decode($json , true);
        if(isset($data['s_version']) && $data['s_version']>$version) {
            return true;
        }
    }
}
// END -- Market util functions

/**
 * Update category stats
 *
 * @param string $update_uri
 * @return boolean
 */
function osc_update_cat_stats() {
    $categoryTotal = array() ;
    $categoryTree  = array() ;
    $aCategories   = Category::newInstance()->listAll(false) ;

    // append root categories and get the number of items of each category
    foreach($aCategories as $category) {
        $total     = Item::newInstance()->numItems($category, true, true) ;
        $category += array('category' => array()) ;
        if( is_null($category['fk_i_parent_id']) ) {
            $categoryTree += array($category['pk_i_id'] => $category) ;
        }

        $categoryTotal += array($category['pk_i_id'] => $total) ;
    }

    // append childs to root categories
    foreach($aCategories as $category) {
        if( !is_null($category['fk_i_parent_id']) ) {
            $categoryTree[$category['fk_i_parent_id']]['category'][] = $category ;
        }
    }

    // sum the result of the subcategories and set in the parent category
    foreach($categoryTree as $category) {
        if( count( $category['category'] ) > 0 ) {
            foreach($category['category'] as $subcategory) {
                $categoryTotal[$category['pk_i_id']] += $categoryTotal[$subcategory['pk_i_id']] ;
            }
        }
    }

    $sql = 'REPLACE INTO '.DB_TABLE_PREFIX.'t_category_stats (fk_i_category_id, i_num_items) VALUES ';
    $aValues = array();
    foreach($categoryTotal as $k => $v) {
        array_push($aValues, "($k, $v)" );
    }
    $sql .= implode(',', $aValues);
    $result = CategoryStats::newInstance()->dao->query($sql);
}

/**
 * Recount items for a given a category id
 *
 * @param int $id
 */
function osc_update_cat_stats_id($id)
{
    // get sub categorias
    if( !Category::newInstance()->isRoot($id) ) {
        $auxCat = Category::newInstance()->findRootCategory($id);
        $id = $auxCat['pk_i_id'];
    }

    $aCategories    = Category::newInstance()->findSubcategories($id);
    $categoryTotal  = 0;

    if( count($aCategories) > 0 ) {
        // sumar items de la categoría
        foreach($aCategories as $category) {
            $total     = Item::newInstance()->numItems($category, true, true) ;
            $categoryTotal += $total;
        }
        $categoryTotal += Item::newInstance()->numItems(Category::newInstance()->findByPrimaryKey($id), true, true) ;
    } else {
        $category  = Category::newInstance()->findByPrimaryKey($id);
        $total     = Item::newInstance()->numItems($category, true, true) ;
        $categoryTotal += $total;
    }

    $sql = 'REPLACE INTO '.DB_TABLE_PREFIX.'t_category_stats (fk_i_category_id, i_num_items) VALUES ';
    $sql .= " (".$id.", ".$categoryTotal.")";
    $result = CategoryStats::newInstance()->dao->query($sql);
}

?>