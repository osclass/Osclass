<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CAdminCategories extends AdminSecBaseModel
    {
        //specific for this class
        private $categoryManager;

        function __construct()
        {
            parent::__construct();

            //specific things for this class
            $this->categoryManager = Category::newInstance();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch ($this->action)
            {
                case('add_post_default'): // add default category and reorder parent categories
                                        osc_csrf_check();
                                        $fields['fk_i_parent_id'] = NULL;
                                        $fields['i_expiration_days'] = 0;
                                        $fields['i_position'] = 0;
                                        $fields['b_enabled'] = 1;
                                        $fields['b_price_enabled'] = 1;

                                        $default_locale = osc_language();
                                        $aFieldsDescription[$default_locale]['s_name'] = "NEW CATEGORY, EDIT ME!";

                                        $categoryId = $this->categoryManager->insert($fields, $aFieldsDescription);

                                        // reorder parent categories. NEW category first
                                        $rootCategories = $this->categoryManager->findRootCategories();
                                        foreach($rootCategories as $cat){
                                            $order = $cat['i_position'];
                                            $order++;
                                            $this->categoryManager->updateOrder($cat['pk_i_id'],$order);
                                        }
                                        $this->categoryManager->updateOrder($categoryId,'0');

                                        $this->redirectTo(osc_admin_base_url(true).'?page=categories');
                break;
                default:                //
                                        $this->_exportVariableToView("categories", $this->categoryManager->toTreeAll() );
                                        $this->doView("categories/index.php");

            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/categories.php */
?>
