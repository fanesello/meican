<?php

include_once 'libs/common.php';

class AuthSystem {

//    static function userAbleToAccess($resource) {
//        AuthSystem::isUserLoggedIn();
//
//        if (!Common::hasSessionVariable('user_rights')) {
//            AuthSystem::reloadRights();
//        }
//        $user_rights = Common::getSessionVariable('user_rights');
//        return (isset ($user_rights[$resource]));
//    }


//    static function reloadRights() {
//        $rights = Access::getAccessRules();
//
//        Common::destroySessionVariable('user_rights');
//
//        if ($rights) {
//            $user_rights = array();
//            foreach ($rights as $r)
//                $user_rights[$r->rsc_id] = TRUE;
//            Common::setSessionVariable('user_rights', $user_rights);
//        }
//    }

    static function setAuthUser($user) {
        Common::setSessionVariable('usr_login', $user->usr_login);
        Common::setSessionVariable('usr_id', $user->usr_id);
        Common::setSessionVariable('usr_password', $user->usr_password);
        $lang = Language::getLang();
        Language::refreshLangSetting($lang);
    }

    static function isUserLoggedIn() {
        if (Common::hasSessionVariable('usr_login') &&
            Common::hasSessionVariable('usr_id') &&
            Common::hasSessionVariable('usr_password')) {

            return TRUE;
        }

        header('HTTP/1.1 402 Timeout');
    }

    static function userTryToLogin() {
        return (array_key_exists('login', $_POST) && array_key_exists('password', $_POST));
    }
    
    static function userLogout () {
        if (AuthSystem::isUserLoggedIn()) {
            /**
             * @todo: criar um array com as informações da sessão
             */
            Common::destroySessionVariable('usr_login');
            Common::destroySessionVariable('usr_id');
            Common::destroySessionVariable('usr_password');
            Common::destroySessionVariable('welcome_loaded');
            Common::destroySessionVariable('acl');
            Common::destroySessionVariable('last_update');
        }
    }

    static function getUserLogin () {
        return Common::getSessionVariable('usr_login');
    }

    static function getUserId() {
        return Common::getSessionVariable('usr_id');
    }

    static function selfUserTest($usr_id) {
        if (Common::getSessionVariable('usr_id') == $usr_id) {
            return TRUE;
        }
        else return FALSE;
    }

}

?>
