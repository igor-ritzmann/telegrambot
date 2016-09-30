<?php

/*
* @version $Id: HEADER 15930 2016-08-29 10:47:55Z jmd $
-------------------------------------------------------------------------
GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2003-2016 by the INDEPNET Development Team.

http://indepnet.net/   http://glpi-project.org
-------------------------------------------------------------------------

LICENSE

This file is part of GLPI.

GLPI is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

require_once('telegram.class.php');

class PluginTelegrambotCron extends CommonDBTM {
   static function getTypeName($nb = 0) {
      return 'Telegrambot';
   }

   static function cronInfo($name) {
      switch ($name) {
         case 'MessageListener':
            return array('description' => __('Handles incoming bot messages', 'telegrambot'));
      }
      return array();
   }

   static function cronMessageListener($task) {
      global $DB;

      $query      = "SELECT `value` AS token FROM glpi_plugin_telegrambot_configs WHERE `name` = 'token'";
      $result     = $DB->query($query);
      $token      = $DB->result($result, 0, 'token');

      $telegram   = new PluginTelegrambotCore();
      $response   = $telegram->handle_get_updates();
      $count      = count($response);

      $task->log("Telegrambot has processed $count new messages");
      return 1;
   }
}

?>