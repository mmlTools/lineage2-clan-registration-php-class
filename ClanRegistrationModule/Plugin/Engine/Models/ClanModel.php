<?php
##################################################
# Copyright ©MMLTech Coding Services
# Website: https://mmltools.com
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

namespace ClanRegistrationModule\Plugin\Engine\Models;

use ClanRegistrationModule\Plugin\Engine\Configuration;
use ClanRegistrationModule\Plugin\Engine\Entity\ClanEntity;
use ClanRegistrationModule\Plugin\Engine\Libraries\DatabaseLibrary;
use ClanRegistrationModule\Plugin\Engine\Libraries\UploadLibrary;

class ClanModel{
    private
        // Various variables we use globally in our class
        $strMinLength, $strMaxLength, $maxMembersPerClan, $table, $redirect;

    public
        // Store method errors to pass into the view
        $message = [];

    /**
     * ClanModel constructor.
     */
    public function __construct()
    {
        if(!isset($_POST))
            return;

        $this->message['error'] = [];

        $this->strMinLength = Configuration::getConfigs("misc", "strFieldMinLength");
        $this->strMaxLength = Configuration::getConfigs("misc", "strFieldMaxLength");
        $this->maxMembersPerClan = Configuration::getConfigs("misc", "maxMembersPerClan");
        $this->table = Configuration::getConfigs("database", "table");
        $this->redirect = Configuration::getConfigs("redirect", "success");
    }

    /**
     * Save clan to database, return error otherwise
     *
     * @return array
     */
    public function save(){
        $this->prepareFields($_POST);

        if(!empty($this->message['errors']))
            return array([], $this->message);

        $data['inputClanName'] = $_POST['inputClanName'];
        $data['inputClanLeader'] = $_POST['inputClanLeader'];
        $data['inputClanMembers'] = $_POST['inputClanMembers'];

        if(empty($this->message['error'])) {
            if (isset($_FILES["fileClanCrest"]) && $_FILES['fileClanCrest']['size'] > 0) {
                $uploader = new UploadLibrary();
                $uploader->setDir(Configuration::getConfigs('upload', 'path'));
                $uploader->setExtensions(explode("/",Configuration::getConfigs("crest","extensions")));
                $uploader->setMaxSize(.5);

                if ($uploader->uploadFile('fileClanCrest'))
                    $data['image'] = $uploader->getUploadName();

                else
                    $this->appendMessage("error", $uploader->getMessage());
            }
        }

        if(empty($this->message['error'])) {
            $db = new DatabaseLibrary();
            $sql = "INSERT INTO `{$this->table}` (`clan_name`, `leader`, `members`, `crest`, `registered`) VALUES (?, ?, ?, ?, ?);";
            $db->query($sql, $data['inputClanName'], $data['inputClanLeader'], $data['inputClanMembers'], isset($data['image']) ? $data['image'] : "default_crest.png", time());

            if (strlen($this->redirect) > 0)
                header("Location: $this->redirect");

            $this->message['success'] = "Clan successfully registered";
        }

        return array($data, $this->message);
    }

    /**
     * Validate fields making sure we caan safely save data to database
     *
     * @param array $data
     */
    private function prepareFields(array $data){
        if(!isset($data['inputClanLeader']))
            $this->appendMessage("error", "Missing clan leader name");

        if(!$this->isUniqueField('leader', $data['inputClanLeader']))
            $this->appendMessage("error", "This leader already registered a clan");

        if(!$this->validateString($data['inputClanLeader']))
            $this->appendMessage("error", "Leader name length must be between {$this->strMinLength} and {$this->strMaxLength} characters long and can only contain letters and numbers");

        if(!isset($data['inputClanMembers']) || isset($data['inputClanMembers']) && ($data['inputClanMembers'] < 1 || $data['inputClanMembers'] > $this->maxMembersPerClan))
            $this->appendMessage("error", "Clan population must be between 1 and {$this->maxMembersPerClan} members");

        if(!isset($data['inputClanName']))
            $this->appendMessage("error", "Missing clan name field");

        if(!$this->isUniqueField('clan_name', $data['inputClanName']))
            $this->appendMessage("error", "This clan is already registered");

        if(!$this->validateString($data['inputClanName']))
            $this->appendMessage("error", "Clan name length must be between {$this->strMinLength} and {$this->strMaxLength} characters long and can only contain letter and numbers");
    }

    /**
     * Append to data we want to send to the controller
     * @param $key
     * @param $val
     */
    private function appendMessage($key, $val){
        $this->message[$key] = $val;
    }

    /**
     * Validate id field is unique in database
     *
     * @param $field
     * @param $value
     * @return bool
     */
    private function isUniqueField($field, $value){
        $entity = new ClanEntity([$field, $value]);
        if(!empty($entity->getClan()))
            return false;
        return true;
    }

    /**
     * Validate strings
     *
     * @param $str
     * @return bool
     */
    private function validateString($str){
        $pattern = "/^[a-zA-Z0-9]+$/";
        if(!preg_match($pattern, $str))
            return false;

        if(strlen($str) < $this->strMinLength)
            return false;

        if(strlen($str) > $this->strMaxLength)
            return false;

        return true;
    }
}