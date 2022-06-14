<?php
##################################################
# Copyright ©MMLTech Coding Services
# Website: https://mmltools.com
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

namespace ClanRegistrationModule\Plugin\Engine;

class Configuration{
    private static $config = [];

    /**
     * Setup default values for the most important configurations
     */
    private static function buildDefaultConfiguration(){
        self::$config['database']['host'] = 'localhost';
        self::$config['database']['user'] = 'root';
        self::$config['database']['password'] = '';
        self::$config['database']['db'] = 'l2';
        self::$config['database']['table'] = 'l2_clans';

        self::$config['upload']['path'] = __DIR__ . '/vendor/uploads/';

        self::$config['crest']['width'] = 24;
        self::$config['crest']['height'] = 12;
    }

    /**
     * Configuration constructor.
     */
    public static function build()
    {
        self::buildDefaultConfiguration();

        $handle = fopen(dirname(__DIR__, 2) . '/Config.inf', "r");

        if (!$handle)
            die('Missing file Config.inf, please make sure the file exist in Plugin');

        while (($line = fgets($handle)) !== false) {
            if ($line[0] == "#" || strlen(preg_replace('/\s+/', '', $line)) == 0)
                continue;

            $line = preg_replace('/\s+/', '', $line);
            $cfgParts = explode("=", $line);

            list($config, $subconfig) = explode('.', $cfgParts[0]);

            self::$config[$config][$subconfig] = $cfgParts[1];
        }

        fclose($handle);
    }

    /**
     * @param $config
     * @param null $subconfig
     * @return array|mixed
     */
    public static function getConfigs($config, $subconfig = null){
        self::build();

       // Return an empty array if requested configuration does not exist
        if(!isset(self::$config[$config]))
            return [];

        // Return an empty array if requested sub configuration does not exist
        if(!is_null($subconfig) && !isset(self::$config[$config][$subconfig]))
            return [];

        return !is_null($subconfig) ? self::$config[$config][$subconfig] : self::$config[$config];
    }

    /**
     * @param int $length
     * @return string
     */
    public static function getRandomString($length = 5){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
