<?php
// Theses classes were adapted from qqUploader
class AjaxUploader {
    private $_allowedExtensions;
    private $_sizeLimit;
    private $_file;

    function __construct(array $allowedExtensions = null, $sizeLimit = null){
        if($allowedExtensions===null) { $allowedExtensions = osc_allowed_extension(); }
        if($sizeLimit===null) { $sizeLimit = 1024*osc_max_size_kb(); }
        $this->_allowedExtensions = $allowedExtensions;
        $this->_sizeLimit = $sizeLimit;

        if(!isset($_SERVER['CONTENT_TYPE'])) {
            $this->_file = false;
        } else if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') === 0) {
            $this->_file = new AjaxUploadedFileForm();
        } else {
            $this->_file = new AjaxUploadedFileXhr();
        }
    }

    public function getOriginalName(){ return $this->_file->getOriginalName(); }

    function handleUpload($uploadFilename, $replace = false){
        if(!is_writable(dirname($uploadFilename))){ return array('error' => __("Server error. Upload directory isn't writable.")); }
        if(!$this->_file){ return array('error' => __('No files were uploaded.')); }
        $size = $this->_file->getSize();
        if($size == 0) { return array('error' => __('File is empty')); }
        if($size > $this->_sizeLimit) { return array('error' => __('File is too large')); }

        $pathinfo = pathinfo($this->_file->getOriginalName());
        $ext = @$pathinfo['extension'];
        $uuid = pathinfo($uploadFilename);

        if(($this->_allowedExtensions && stripos($this->_allowedExtensions, strtolower($ext))===false) ) {
            @unlink($uploadFilename); // Wrong extension, remove it for security reasons
            return array('error' => sprintf(__('File has an invalid extension, it should be one of %s.'), $this->_allowedExtensions));
        }

        if(!$replace){
            if(file_exists($uploadFilename)) {
                return array('error' => 'Could not save uploaded file. File already exists');
            }
        }

        if($this->_file->save($uploadFilename)){
            $result = $this->checkAllowedExt($uploadFilename);
            if(!$result) {
                @unlink($uploadFilename); // Wrong extension, remove it for security reasons
                return array('error' => sprintf(__('File has an invalid extension, it should be one of %s.'), $this->_allowedExtensions));
            }
            $files = Session::newInstance()->_get('ajax_files');
            $files[Params::getParam('qquuid')] = $uuid['basename'];
            Session::newInstance()->_set('ajax_files', $files);
            return array('success' => true);
        } else {
            return array('error' => 'Could not save uploaded file. The upload was cancelled, or server error encountered');
        }
    }

    function checkAllowedExt($file) {
        require LIB_PATH . 'osclass/mimes.php';
        if($file!='') {
            $aMimesAllowed = array();
            $aExt = explode(',', osc_allowed_extension());
            foreach($aExt as $ext){
                if(isset($mimes[$ext])) {
                    $mime = $mimes[$ext];
                    if( is_array($mime) ){
                        foreach($mime as $aux){
                            if( !in_array($aux, $aMimesAllowed) ) {
                                array_push($aMimesAllowed, $aux );
                            }
                        }
                    } else {
                        if( !in_array($mime, $aMimesAllowed) ) {
                            array_push($aMimesAllowed, $mime );
                        }
                    }
                }
            }
            $bool_img = false;
            $fileMime = '';
            if(function_exists('finfo_file') && function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileMime = finfo_file($finfo, $file);
            } else if(function_exists('mime_content_type')) {
                $fileMime = mime_content_type($file);
            } else {
                // *WARNING* There's no way check the mime type of the file, you should not blindly trust on your users' input!
                $ftmp = Params::getFiles('qqfile');
                $fileMime = @$ftmp['type'];
            }

            if(stripos($fileMime, "image/")!==FALSE) {
                if(function_exists("getimagesize")) {
                    $info = getimagesize($file);
                    if(isset($info['mime'])) {
                        $fileMime = $info['mime'];
                    } else {
                        $fileMime = '';
                    }
                };
            };

            if(in_array($fileMime,$aMimesAllowed)) {
                return true;
            }
        }
        return false;
    }

}

class AjaxUploadedFileXhr {
    function __construct() {}

    public function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        if ($realSize != $this->getSize()){ return false; }
        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        return true;
    }

    public function getOriginalName() { return Params::getParam('qqfile'); }
    public function getSize() {
        if(isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception(__('Getting content length is not supported.'));
        }
    }
}

class AjaxUploadedFileForm {
    private $_file;
    function __construct() { $this->_file = Params::getFiles('qqfile'); }
    public function save($path) { return move_uploaded_file($this->_file['tmp_name'], $path); }
    public function getOriginalName() { return $this->_file['name']; }
    public function getSize() { return $this->_file['size']; }
}
?>