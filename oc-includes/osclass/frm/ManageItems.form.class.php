<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class ManageItemsForm extends Form {

        // OK
        static public function category_select($categories = null, $item = null, $default_item = null, $parent_selectable = false)
        {
            // Did user select a specific category to post in?
            $catId = Params::getParam('catId') ;

            if($categories == null) {
                if(View::newInstance()->_exists('categories')) {
                    $categories = View::newInstance()->_get('categories') ;
                } else {
                    $categories = osc_get_categories() ;
                }
            }
            
            echo '<select name="catId" id="catId">' ;
            if(isset($default_item)) {
                echo '<option value="">' . $default_item . '</option>' ;
            } else {
                echo '<option value="">' . __('Select a category') . '</option>' ;
            }

            if(count($categories)==1) { $parent_selectable = 1; };
            
            foreach($categories as $c) {
                if ( !osc_selectable_parent_categories() && !$parent_selectable ) {
                    echo '<optgroup label="' . $c['s_name'] . '">' ;
                    if(isset($c['categories']) && is_array($c['categories'])) {
                        ManageItemsForm::subcategory_select($c['categories'], $item, $default_item, 1);
                    }
                } else {
                    $selected = ( (isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']) );
                    echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected"' : '' ). '>' . $c['s_name'] . '</option>' ;
                    if(isset($c['categories']) && is_array($c['categories'])) {
                        ManageItemsForm::subcategory_select($c['categories'], $item, $default_item, 1);
                    }
                }
            }
            echo '</select>' ;
            return true ;
        }
    
        // OK
        static public function subcategory_select($categories, $item, $default_item = null, $deep = 0)
        {
            // Did user select a specific category to post in?
            $catId = Params::getParam('catId');
            // How many indents to add?
            $deep_string = "";
            for($var = 0;$var<$deep;$var++) {
                $deep_string .= '&nbsp;&nbsp;';
            }
            $deep++;

            foreach($categories as $c) {
                $selected = ( (isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']) );

                echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected'.$item["fk_i_category_id"].'"' : '') . '>' . $deep_string . $c['s_name'] . '</option>' ;
                if(isset($c['categories']) && is_array($c['categories'])) {
                    ManageItemsForm::subcategory_select($c['categories'], $item, $default_item, $deep);
                }
            }
        }
        
        static public function country_text() 
        {
            // get params GET (only manageItems)
            if(Params::getParam('country') != '') {
                $item['s_country'] = Params::getParam('country') ;
                $item['fk_c_country_code'] = Params::getParam('countryId');
            }
            $only_one = false;
            if(!isset($item['s_country'])) {
                $countries = osc_get_countries();
                if(count($countries)==1) {
                    $item['s_country'] = $countries[0]['s_name'];
                    $item['fk_c_country_code'] = $countries[0]['pk_c_code'];
                    $only_one = true;
                }
            }
            parent::generic_input_text('countryName', (isset($item['s_country'])) ? $item['s_country'] : null, null, $only_one) ;
            parent::generic_input_hidden('countryId', (isset($item['fk_c_country_code']) && $item['fk_c_country_code']!=null)?$item['fk_c_country_code']:'');
            return true ;
        }
        
        static public function region_text() 
        {
            // get params GET (only manageItems)
            if(Params::getParam('region') != '') {
                $item['s_region'] = Params::getParam('region') ;
                $item['fk_i_region_id'] = Params::getParam('regionId');
            }
            parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null, false, false) ;
            parent::generic_input_hidden('regionId', (isset($item['fk_i_region_id']) && $item['fk_i_region_id']!=null)?$item['fk_i_region_id']:'');
            return true ;
        }
        
        static public function city_text() 
        {
            // get params GET (only manageItems)
            if(Params::getParam('city') != '') {
                $item['s_city'] = Params::getParam('city') ;
                $item['fk_i_city_id'] = Params::getParam('cityId');
            }
            parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null, false, false) ;
            parent::generic_input_hidden('cityId', (isset($item['fk_i_city_id']) && $item['fk_i_city_id']!=null)?$item['fk_i_city_id']:'');
            return true ;
        }
    }
?>