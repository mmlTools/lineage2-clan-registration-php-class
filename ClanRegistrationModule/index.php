<?php
##################################################
# Copyright ©Darksoke's Coding Services
# Discord: https://discord.gg/YCUpNz77j
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

namespace ClanRegistrationModule;

use ClanRegistrationModule\Plugin\Engine\Controller\ClanController;

class index{
    public function __construct()
    {
        $data['roots'] = [
            'Plugin/Engine' => [
                'Models',
                'Libraries',
                'Entity',
                'Controller'
            ]
        ];

        foreach ($data['roots'] as $key => $root) {
            foreach (glob(__DIR__."/{$key}/*.php") as $filename)
                require_once $filename;

            foreach ($root as $v)
                foreach (glob(__DIR__."/{$key}/{$v}/*.php") as $filename)
                    require_once $filename;
        }
    }

    public static function getForm(){
        return ClanController::renderFormCard();
    }

    public static function getList(array $params = []){
        return ClanController::renderTableCard($params);
    }

    public static function getStyle(){
        return ClanController::loadCss();
    }
}
