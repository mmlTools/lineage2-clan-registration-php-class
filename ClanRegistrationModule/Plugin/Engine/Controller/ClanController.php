<?php
##################################################
# Copyright ©Darksoke's Coding Services
# Discord: https://discord.gg/YCUpNz77j
#
# Redistribution of this code is not permitted
# Do not touch the code below unless you know
# what you are doing
##################################################

namespace ClanRegistrationModule\Plugin\Engine\Controller;

use ClanRegistrationModule\Plugin\Engine\Entity\ClanEntity;
use ClanRegistrationModule\Plugin\Engine\Libraries\DatabaseLibrary;
use ClanRegistrationModule\Plugin\Engine\Configuration;
use ClanRegistrationModule\Plugin\Engine\Models\ClanModel;


class ClanController{
    /**
     * @return array
     */
    private static function getAll(){
        $clans = [];
        $db = new DatabaseLibrary();
        $table = Configuration::getConfigs("database", "table");
        $limit = Configuration::getConfigs("list", "maxResults");

        $sql = "SELECT `id` FROM `{$table}` ORDER BY `registered` DESC LIMIT {$limit}";

        $result = $db->query($sql)->fetchAll();

        foreach ($result as $clan) {
            $entity = new ClanEntity(['id' , $clan['id']]);
            $clans[] = $entity->getClan();
        }

        $db->close();
        return $clans;
    }

    /**
     * @return array
     */
    private static function countAll(){
        $db = new DatabaseLibrary();
        $table = Configuration::getConfigs("database", "table");

        $sql = "SELECT `id` FROM `{$table}`";
        $count = $db->query($sql)->numRows();
        $db->close();
        return $count;
    }

    /**
     * @return string
     */
    public static function loadCss(){
        return '<link rel="stylesheet" href="ClanRegistrationModule/Plugin/vendor/main.css">';
    }

    /**
     * @param array $cfg
     * @return string
     */
    public static function renderFormCard(array $cfg = []){
        $request = new ClanModel();

        if(isset($_POST['ClanSave']))
            $data = $request->save();

        $cardTitle = isset($cfg['title']) ? $cfg['title'] : "REGISTER YOUR CLAN";
        $cardSubTitle = 'Subscribe to your clan';

        $maxWidth = Configuration::getConfigs("crest","width");
        $maxHeight = Configuration::getConfigs("crest","height");
        $extenstionsString = Configuration::getConfigs("crest","extensions");

        if(isset($cfg['subTitle'])){
            if(is_array($cfg['subTitle']))
                $cardSubTitle = $cfg['subTitle'][0];
            else
                $cardSubTitle = $cfg['subTitle'];
        }

        $form ='<div class="card clan-registration-module">';
        $form .='    <div class="card-header">';
        $form .='        <h5 class="card-title">'.$cardTitle.'</h5>';
        $form .='        <span class="text-muted small">'.$cardSubTitle.'</span>';
        $form .='    </div>';
        $form .='    <div class="card-body">';

        if(isset($data[1]['error']) && !empty($data[1]['error'])):
                $form .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                $form .= '  <strong>Error!</strong> '.$data[1]['error'];
                $form .= '  <button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                $form .= '    <span aria-hidden="true">&times;</span>';
                $form .= '  </button>';
                $form .= '</div>';
        endif;

        if(isset($data[1]['success']) && !empty($data[1]['success'])):
                $form .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                $form .= '  <strong>Error!</strong> '.$data[1]['success'];
                $form .= '  <button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                $form .= '    <span aria-hidden="true">&times;</span>';
                $form .= '  </button>';
                $form .= '</div>';
        endif;

        $form .='        <form method="post" enctype="multipart/form-data">';
        $form .='            <div class="form-group">';
        $form .='                <label class="sr-only" for="inputClanName">Clan Name</label>';
        $form .='                <input type="text" class="form-control" id="inputClanName" name="inputClanName" '.(isset($_POST['inputClanName']) ? "value=\"{$_POST['inputClanName']}\"" : "").' aria-describedby="inputClanNameHelp" placeholder="Enter clan name">';
        $form .='            </div>';
        $form .='            <div class="form-group">';
        $form .='                <label class="sr-only" for="inputClanLeader">Clan Leader</label>';
        $form .='                <input type="text" class="form-control" id="inputClanLeader" name="inputClanLeader" '.(isset($_POST['inputClanName']) ? "value=\"{$_POST['inputClanLeader']}\"" : "").' aria-describedby="inputClanLeaderHelp" placeholder="Enter clan leader">';
        $form .='            </div>';
        $form .='            <div class="form-group">';
        $form .='                <label class="sr-only" for="inputClanMembers">Clan Members</label>';
        $form .='                <input type="text" class="form-control" id="inputClanMembers" name="inputClanMembers" '.(isset($_POST['inputClanName']) ? "value=\"{$_POST['inputClanMembers']}\"" : "").' aria-describedby="inputClanMembersHelp" placeholder="Enter clan population">';
        $form .='            </div>';
        $form .='            <div class="form-group">';
        $form .='                <label for="fileClanCrest">Upload Clan Crest</label>';
        $form .='                <input type="file" class="form-control-file" id="fileClanCrest" name="fileClanCrest" '.(Configuration::getConfigs("crest","required") ? "required" : "").'>';
        $form .='                <small id="fileClanCrestHelp" class="form-text text-muted">';
        $form .='                    Crest must be <strong>'.$extenstionsString.'</strong> and max image dimension of <strong>'.$maxWidth.'px Width x '.$maxHeight.'px Height</strong>';
        $form .='                </small>';
        $form .='            </div>';
        $form .='            <div class="d-block text-right">';
        $form .='                <button type="submit" id="inputSubmit" name="ClanSave" class="btn btn-primary right">Submit</button>';
        $form .='            </div>';
        $form .='        </form>';
        $form .='    </div>';
        $form .='</div>';

        return $form;
    }

    public static function renderTableCard(array $cfg = []){
        $clans = self::getAll();
        $cardTitle = isset($cfg['title']) ? $cfg['title'] : "REGISTERED CLANS";
        $cardSubTitle = 'Clans that play on our server';
        $limit = Configuration::getConfigs("list", "maxResults");
        $total = self::countAll();

        if(isset($cfg['subTitle'])){
            if(is_array($cfg['subTitle']))
            {
                $cardSubTitle = $cfg['subTitle'][0];
                $list = boolval($cfg['subTitle'][1]);
                if($list)
                    $cardSubTitle .= ' Showing <strong>'.($limit > $total ? $total : $limit).'</strong> out of total <strong>'.$total.'</strong> clans';
            }
            else
                $cardSubTitle = $cfg['subTitle'];
        }

        $listCfg = Configuration::getConfigs("list");
        $uploadPath = Configuration::getConfigs("upload", "path");

        $table ='<div class="card clan-registration-module">';
        $table .='    <div class="card-header">';
        $table .='        <h5 class="card-title">'.$cardTitle.'</h5>';
        $table .='        <span class="text-muted small">'.$cardSubTitle.'</span>';
        $table .='    </div>';
        $table .='    <div class="card-body">';
        $table .='        <table class="table">';
        $table .='            <thead>';
        $table .='            <tr>';
        if($listCfg['crest'])
            $table .='                <th>Crest</th>';
        if($listCfg['clan_name'])
            $table .='                <th>Clan</th>';
        if($listCfg['leader'])
            $table .='                <th>Leader</th>';
        if($listCfg['members'])
            $table .='                <th class="text-center">Members</th>';
        $table .='            </tr>';
        $table .='            </thead>';
        $table .='            <tbody>';
        foreach ($clans as $clan):
            $table .='				<tr>';
            if($listCfg['crest'])
                $table .='					<td><img src="'.$uploadPath.$clan['crest'].'" /></td>';
            if($listCfg['clan_name'])
                $table .='					<td>'.$clan['clan_name'].'</td>';
            if($listCfg['leader'])
                $table .='					<td>'.$clan['leader'].'</td>';
            if($listCfg['members'])
                $table .='					<td class="text-center">'.$clan['members'].'</td>';
            $table .='				</tr>';
        endforeach;
        $table .='            </tbody>';
        $table .='        </table>';
        $table .='    </div>';
        $table .='</div>';

        return $table;
    }
}