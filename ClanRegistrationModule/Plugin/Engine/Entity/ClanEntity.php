<?php
##################################################
# Copyright ©Darksoke's Coding Services
# Discord: https://discord.gg/YCUpNz77j
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

namespace ClanRegistrationModule\Plugin\Engine\Entity;

use ClanRegistrationModule\Plugin\Engine\Libraries\DatabaseLibrary;
use ClanRegistrationModule\Plugin\Engine\Configuration;

class ClanEntity{
    protected
        // DB and requried configurations
        $db, $table;

    private
        // Class data
        $clan = [], $field, $value;

    private
        // Fields that can be accessed when querying for clan data
        $allowedFields = ['id', 'clan_name', 'leader', 'members', 'crest', 'registered'],
        // Clan data fields
        $id, $clan_name, $leader, $members, $crest, $registered;

    /**
     * ClanEntity constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        // Catch data into field => value for database query
        list($a, $b) = $data;

        // Check if input field can be accessed to query data otherwise
        // throw an error
        if(!in_array($a, $this->allowedFields))
            return;

        // Field and value set
        $this->field = $a;
        $this->value = $b;

        // Get our required configurations
        $this->table = Configuration::getConfigs("database", "table");

        // Load clan data from database
        $this->db = new DatabaseLibrary();
        $this->loadClan();
        $this->db->close();
    }

    /**
     * Load Clan data from database using prepared statement
     */
    private function loadClan(){
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->field}` = ?;";
        $this->clan = $this->db->query($sql, $this->value)->fetchArray();
        foreach ($this->clan as $key => $c)
            $this->{$key} = $c;
    }

    /**
     * @return array
     */
    public function getClan(){
        return $this->clan;
    }

    /**
     * @return mixed
     */
    public function getClanID(){
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getClanName(){
        return $this->clan_name;
    }

    /**
     * @return mixed
     */
    public function getClanLeader(){
        return $this->leader;
    }

    /**
     * @return mixed
     */
    public function getClanMembers(){
        return $this->members;
    }

    /**
     * @return mixed
     */
    public function getClanCrest(){
        return $this->crest;
    }

    /**
     * @return mixed
     */
    public function getClanRegisteredDate(){
        return $this->registered;
    }
}