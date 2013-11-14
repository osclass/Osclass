<?php
// Theses classes were adapted from qqUploader
class AjaxUploader {
    private $_allowedExtensions;
    private $_sizeLimit;
    private $_file;

    function __construct($variable, array $allowedExtensions = null, $sizeLimit = null){
        if($allowedExtensions===null) { $allowedExtensions = osc_allowed_extension(); }
        if($sizeLimit===null) { $sizeLimit = 1024*osc_max_size_kb(); }
        $this->_allowedExtensions = array_map("strtolower", $allowedExtensions);
        $this->_sizeLimit = $sizeLimit;

        if(!isset($_SERVER['CONTENT_TYPE'])) {
            $this->_file = false;
        } else if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') === 0) {
            $this->_file = new AjaxUploadedFileForm($variable);
        } else {
            $this->_file = new AjaxUploadedFileXhr($variable);
        }
    }

    public function getOriginalName(){ return $this->_file->getOriginalName(); }

    function handleUpload($uploadFilename, $replace = false){
        if(!is_writable(dirname($uploadFilename))){ return array('error' => __("Server error. Upload directory isn't writable.")); }
        if(!$this->_file){ return array('error' => __('No files were uploaded.')); }
        $size = $this->_file->getSize();
        if($size == 0) { return array('error' => __('File is empty')); }
        if($size > $this->_sizeLimit) { return array('error' => __('File is too large')); }

        $pathinfo = pathinfo($this->_file->getName());
        $ext = @$pathinfo['extension'];

        if($this->_allowedExtensions && !in_array(strtolower($ext), $this->_allowedExtensions)){
            $these = implode(', ', $this->_allowedExtensions);
            return array('error' => sprintf(__('File has an invalid extension, it should be one of %s.'), $these));
        }

        if(!$replace){
            if(file_exists($$uploadFilename)) {
                return array('error' => 'Could not save uploaded file. File already exists');
            }
        }

        if($this->file->save($uploadFilename)){
            return array('success' => true);
        } else {
            return array('error' => 'Could not save uploaded file. The upload was cancelled, or server error encountered');
        }

    }
}

class AjaxUploadedFileXhr {
    private $_variable;
    function __construct($variable) { $this->_variable = $variable; }

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

    public function getOriginalName() { return Params::getParam($this->_variable); }
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
    function __construct($variable) { $this->_file = Params::getFiles($variable); }
    public function save($path) { return move_uploaded_file($this->_file['tmp_name'], $path); }
    public function getOriginalName() { return $this->_file['name']; }
    public function getSize() { return $this->_file['size']; }
}
?>