<?php

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

     Class UserActions {
         var $is_admin ;
         var $manager ;


         function __construct($is_admin) {
             $this->is_admin = $is_admin ;
             $this->manager = User::newInstance() ;
         }

         //add...
         function add()
         {
            $input = $this->prepareData(true) ;

            $email_taken = $this->manager->findByEmail($input['s_email']) ;
            if($email_taken == null) {
                $this->manager->insert($input) ;
                $userId = $this->manager->getConnection()->get_last_id() ;

                if ( is_array( Params::getParam('s_info') ) ) {
                    foreach (Params::getParam('s_info') as $key => $value) {
                        $this->manager->updateDescription($userId, $key, $value) ;
                    }
                }
                
                osc_run_hook('user_register_completed') ;

                if( osc_user_validation_enabled() && !$this->is_admin ) {
                    
                    $user = $this->manager->findByPrimaryKey($userId) ;

                    $mPages = new Page() ;
                    $locale = osc_get_user_locale() ;

                    $aPage = $mPages->findByInternalName('email_user_validation') ;

                    $content = array() ;
                    if(isset($aPage['locale'][$locale]['s_title'])) {
                        $content = $aPage['locale'][$locale] ;
                    } else {
                        $content = current($aPage['locale']) ;
                    }
                    
                    if (!is_null($content)) {
                        $validationLink = sprintf('%sindex.php?page=register&action=validate&id=%d&code=%s', osc_base_url(), $user['pk_i_id'], $input['s_secret']) ;
                        $words   = array();
                        $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{VALIDATION_LINK}') ;
                        $words[] = array($user['s_name'], $user['s_email'], osc_base_url(), $validationLink) ;
                        $title = osc_mailBeauty($content['s_title'], $words) ;
                        $body = osc_mailBeauty($content['s_text'], $words) ;

                        $emailParams = array('subject'  => $title
                                             ,'to'       => Params::getParam('s_email')
                                             ,'to_name'  => Params::getParam('s_name')
                                             ,'body'     => $body
                                             ,'alt_body' => $body
                        ) ;
                        osc_sendMail($emailParams) ;
                    }
                    
                    return 1 ;
                    
                } else {
                    User::newInstance()->update(
                                    array('b_enabled' => '1')
                                    ,array('pk_i_id' => $userId)
                    );
                    return 2 ;
                }
                return 0 ;
            } else {
                return 3 ;
            }
         }

        //edit...
        function edit($userId)
        {
            $input = $this->prepareData(false) ;
            $this->manager->update($input, array('pk_i_id' => $userId)) ;

            if ( is_array( Params::getParam('s_info') ) ) {
                foreach (Params::getParam('s_info') as $key => $value) {
                    $this->manager->updateDescription($userId, $key, $value) ;
                }
            }

            if ($this->is_admin) {
                if(Params::getParam("b_enabled") != '') {
                    $this->manager->update(array('b_enabled' => 1), array('pk_i_id' => $userId)) ;
                } else {
                    $this->manager->update(array('b_enabled' => 0), array('pk_i_id' => $userId)) ;
                }
            }

            return 2 ;
        }


        //   
        function prepareData($is_add)
        {
            $input = array() ;
            
            if ($is_add) {
                $input['s_secret'] = osc_genRandomPassword() ;
                $input['dt_reg_date'] = DB_FUNC_NOW ;
            } else {
                $input['dt_mod_date'] = DB_FUNC_NOW ;
            }

            //only for administration, in the public website this two params are edited separately
            if ($this->is_admin || $is_add) {
                $input['s_email'] = Params::getParam('s_email') ;

                if( Params::getParam('s_password') != Params::getParam('s_password2') ) {
                    return 1 ;
                }

                //if we want to change the password
                if( Params::getParam('s_password') != '') {
                    $input['s_password'] = sha1( Params::getParam('s_password') ) ;
                }
            }
            
            $input['s_name'] = Params::getParam('s_name') ;
            $input['s_website'] = Params::getParam('s_website') ;
            $input['s_phone_land'] = Params::getParam('s_phone_land') ;
            $input['s_phone_mobile'] = Params::getParam('s_phone_mobile') ;

            //locations...
            $country = Country::newInstance()->findByCode( Params::getParam('countryId') ) ;
            if(count($country) > 0) {
                $countryId = $country['pk_c_code'] ;
                $countryName = $country['s_name'] ;
            } else {
                $countryId = null ;
                $countryName = null ;
            }

            if( intval( Params::getParam('regionId') ) ) {
                $region = Region::newInstance()->findByPrimaryKey( Params::getParam('regionId') ) ;
                if( count($region) > 0 ) {
                    $regionId = $region['pk_i_id'] ;
                    $regionName = $region['s_name'] ;
                }
            } else {
                $regionId = null ;
                $regionName = Params::getParam('region') ;
            }

            if( intval( Params::getParam('cityId') ) ) {
                $city = City::newInstance()->findByPrimaryKey( Params::getParam('cityId') ) ;
                if( count($city) > 0 ) {
                    $cityId = $city['pk_i_id'] ;
                    $cityName = $city['s_name'] ;
                }
            } else {
                $cityId = null ;
                $cityName = Params::getParam('city') ;
            }

            $input['fk_c_country_code'] = $countryId ;
            $input['s_country'] = $countryName ;
            $input['fk_i_region_id'] = $regionId ;
            $input['s_region'] = $regionName ;
            $input['fk_i_city_id'] = $cityId ;
            $input['s_city'] = $cityName ;
            $input['s_city_area'] = Params::getParam('cityArea') ;
            $input['s_address'] = Params::getParam('address') ;
            
            return($input) ;
        }
     }

?>