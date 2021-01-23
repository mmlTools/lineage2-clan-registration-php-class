<?php
##################################################
# Copyright ©Darksoke's Coding Services
# Discord: https://discord.gg/YCUpNz77j
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

namespace ClanRegistrationModule\Plugin\Engine\Libraries;
use ClanRegistrationModule\Plugin\Engine\Configuration;
use Exception;

class UploadLibrary
{
    private
        $destinationPath,
        $errorMessage,
        $extensions,
        $allowAll,
        $maxSize,
        $uploadName,
        $sameName,
        $sameFileName,
        $imageSeq;

    public $useTable = false;

    /**
     * @param $path
     */
    function setDir($path){
        $this->destinationPath = $path;
        $this->allowAll = false;
    }

    /**
     *
     */
    function allowAllFormats(){
        $this->allowAll = true;
    }

    /**
     * @param $sizeMB
     */
    function setMaxSize($sizeMB){
        $this->maxSize = $sizeMB * (1024*1024);
    }

    /**
     * @param $options
     */
    function setExtensions($options){
        $this->extensions = $options;
    }

    /**
     *
     */
    function setSameFileName(){
        $this->sameFileName =   true;
        $this->sameName =   true;
    }

    /**
     * @param $string
     * @return string
     */
    function getExtension($string){
        $ext = "";
        try{
            $parts = explode(".",$string);
            $ext = strtolower($parts[count($parts)-1]);
        }catch(Exception $c) {
            $ext = "";
        }
        return $ext;
    }

    /**
     * @param $message
     */
    function setMessage($message){
        $this->errorMessage = $message;
    }

    /**
     * @return mixed
     */
    function getMessage(){
        return $this->errorMessage;
    }

    /**
     * @return mixed
     */
    function getUploadName(){
        return $this->uploadName;
    }

    /**
     * @param $seq
     */
    function setSequence($seq){
        $this->imageSeq = $seq;
    }

    /**
     * @return string
     */
    function getRandom(){
        return strtotime(date('Y-m-d H:i:s')).rand(1111,9999).rand(11,99).rand(111,999);
    }

    /**
     * @param $true
     */
    function sameName($true){
        $this->sameName = $true;
    }

    /**
     * @param $fileBrowse
     * @return bool
     */
    function uploadFile($fileBrowse){
        $result =   false;
        $size   =   $_FILES[$fileBrowse]["size"];
        $name   =   $_FILES[$fileBrowse]["name"];
        $ext    =   $this->getExtension($name);

        $filename = $_FILES[$fileBrowse]['tmp_name'];
        list($width, $height) = getimagesize($filename);

        $maxWidth = Configuration::getConfigs("crest","width");
        $maxHeight = Configuration::getConfigs("crest","height");

        $nameNoExt = explode('.', $name)[0];

        if(!is_dir($this->destinationPath)){
            $this->setMessage("Destination folder is not a directory ");
        }else if(!is_writable($this->destinationPath)){
            $this->setMessage("Destination is not writable !");
        }else if($width > $maxWidth || $height > $maxHeight){
            $this->setMessage("Wrong crest dimensions selected, max dimension is <strong>{$maxWidth} Width x {$maxHeight} Height</strong>");
        }else if(empty($name)){
            $this->setMessage("File not selected ");
        }else if($size>$this->maxSize){
            $this->setMessage("Too large file !");
        }else if($this->allowAll || (!$this->allowAll && in_array($ext,$this->extensions))){
            if($this->sameName == false){
                $this->uploadName = $nameNoExt."-".Configuration::getRandomString().".".$ext;
                if(strlen($this->imageSeq) > 0)
                    $this->uploadName = $this->imageSeq."-".$this->uploadName;
            }else{
                $this->uploadName = $nameNoExt.".".$ext;
            }
            if(move_uploaded_file($_FILES[$fileBrowse]["tmp_name"],$this->destinationPath.$this->uploadName)){
                $result =   true;
            }else{
                $this->setMessage("Upload failed , try later !");
            }
        }else{
            $this->setMessage("Invalid file format !");
        }
        return $result;
    }

    function deleteUploaded(){
        unlink($this->destinationPath.$this->uploadName);
    }

}