<?php
/*
 * @version $Id$
 LICENSE

 This file is part of the order plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection; along with Behaviors. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2011 Order plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionContractInjection extends Contract
                                           implements PluginDatainjectionInjectionInterface {

   function __construct() {
      $this->table = getTableForItemType(get_parent_class($this));
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array();
   }


   function getOptions($primary_type = '') {

      $tab = Search::getOptions(get_parent_class($this));

      //Specific to location
      $tab[5]['checktype'] = 'date';

      $tab[6]['minvalue']  = 0;
      $tab[6]['maxvalue']  = 120;
      $tab[6]['step']      = 1;
      $tab[6]['checktype'] = 'integer';

      $tab[7]['minvalue']  = 0;
      $tab[7]['maxvalue']  = 120;
      $tab[7]['step']      = 1;
      $tab[7]['checktype'] = 'integer';

      $tab[11]['checktype'] = 'float';

      $tab[22]['linkfield'] = 'billing';

      //Remove some options because some fields cannot be imported
      $options['ignore_fields'] = array(2, 19, 80);
      $options['displaytype']   = array("dropdown"         => array(4),
                                        "date"             => array(5),
                                        "dropdown_integer" => array(6,7,21),
                                        "bool"             => array(86),
                                        "alert"            => array(59),
                                        "billing"          => array(22),
                                        "renewal"          => array(23),
                                        "multiline_text"   => array(16,90));
      $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
      return $tab;
   }


   /**
    * Standard method to add an object into glpi
 
    *
    * @param values fields to add into glpi
    * @param options options used during creation
    *
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   function showAdditionalInformation($info=array(),$option=array()) {

      $name = "info[".$option['linkfield']."]";

      switch ($option['displaytype']) {
         case 'alert' :
            Contract::dropdownAlert($name, 0);
            break;

         case 'renewal' :
            Contract::dropdownContractRenewal($name, 0);
            break;

         case 'billing' :
            Dropdown::showInteger($name, 0, 12, 60, 12, array(0 => DROPDOWN_EMPTY_VALUE,
                                                              1 => "1",
                                                              2 => "2",
                                                              3 => "3",
                                                              6 => "6"));
            break;

         default:
            break;
      }
   }

}

?>