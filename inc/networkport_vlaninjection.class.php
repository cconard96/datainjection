<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Remi Collet
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

/// Location class
class PluginDatainjectionNetworkport_VlanInjection extends NetworkPort_Vlan
   implements PluginDatainjectionInjectionInterface {

   function __construct() {
      $this->table = getTableForItemType('NetworkPort_Vlan');
   }

   function isPrimaryType() {
      return false;
   }

   function connectedTo() {
      return array();
   }

   function getOptions($primary_type = '') {
      //Remove some options because some fields cannot be imported
      $options['ignore_fields'] = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();
      $options['displaytype'] = array();
      return PluginDatainjectionCommonInjectionLib::addToSearchOptions(parent::getSearchOptions(),
                                                                       $options);
   }

   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param values fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
    */
   function addOrUpdateObject($values=array(), $options=array()) {
      global $LANG;
      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   function addSpecificNeededFields($primary_type,$values) {
      $fields['networkports_id'] = $values['NetworkPort']['id'];
      return $fields;
   }
}

?>