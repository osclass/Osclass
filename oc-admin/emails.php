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

    class CAdminEmails extends AdminSecBaseModel
    {
        //specific for this class
        private $emailManager;

        function __construct()
        {
            parent::__construct();

            //specific things for this class
            $this->emailManager = Page::newInstance();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch($this->action) {

                case 'edit':
                    if(Params::getParam("id")=='') {
                        $this->redirectTo(osc_admin_base_url(true)."?page=emails");
                    }

                    $form     = count(Session::newInstance()->_getForm());
                    $keepForm = count(Session::newInstance()->_getKeepForm());
                    if($form == 0 || $form == $keepForm) {
                        Session::newInstance()->_dropKeepForm();
                    }

                    $this->_exportVariableToView("email", $this->emailManager->findByPrimaryKey(Params::getParam("id")));
                    $this->doView("emails/frm.php");
                    break;
                case 'edit_post':
                    osc_csrf_check();
                    $id = Params::getParam("id");
                    $s_internal_name = Params::getParam("s_internal_name");

                    $aFieldsDescription = array();
                    $postParams = Params::getParamsAsArray('', false);
                    $not_empty = false;
                    foreach ($postParams as $k => $v) {
                        if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                            if($m[2]=='s_title' && $v!='') { $not_empty = true; };
                            $aFieldsDescription[$m[1]][$m[2]] = $v;
                        }
                    }

                    Session::newInstance()->_setForm('s_internal_name',$s_internal_name);
                    Session::newInstance()->_setForm('aFieldsDescription',$aFieldsDescription);

                    if($not_empty) {
                        foreach($aFieldsDescription as $k => $_data) {
                            $this->emailManager->updateDescription($id, $k, $_data['s_title'], $_data['s_text']);
                        }

                        if(!$this->emailManager->internalNameExists($id, $s_internal_name)) {
                            if(!$this->emailManager->isIndelible($id)) {
                                $this->emailManager->updateInternalName($id, $s_internal_name);
                            }
                            Session::newInstance()->_clearVariables();
                            osc_add_flash_ok_message( _m('The email/alert has been updated'), 'admin' );
                            $this->redirectTo(osc_admin_base_url(true)."?page=emails");
                        }
                        osc_add_flash_error_message( _m('You can\'t repeat internal name'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m('The email couldn\'t be updated, at least one title should not be empty'), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true)."?page=emails&action=edit&id=" . $id);
                    break;
                default:
                    //-
                    if( Params::getParam('iDisplayLength') == '' ) {
                        Params::setParam('iDisplayLength', 10 );
                    }

                    $p_iPage      = 1;
                    if( is_numeric(Params::getParam('iPage')) && Params::getParam('iPage') >= 1 ) {
                        $p_iPage = Params::getParam('iPage');
                    }
                    Params::setParam('iPage', $p_iPage);

                    $prefLocale = osc_current_admin_locale();
                    $emails     = $this->emailManager->listAll(1);

                    // pagination
                    $start = ($p_iPage-1) * Params::getParam('iDisplayLength');
                    $limit = Params::getParam('iDisplayLength');
                    $count = count( $emails );

                    $displayRecords = $limit;
                    if( ($start+$limit ) > $count ) {
                        $displayRecords = ($start+$limit) - $count;
                    }
                    // ----
                    $aData = array();
                    $max = ($start+$limit);
                    if($max > $count) $max = $count;
                    for($i = $start; $i < $max; $i++) {
                        $email = $emails[$i];

                        if(isset($email['locale'][$prefLocale]) && !empty($email['locale'][$prefLocale]['s_title'])) {
                            $title = $email['locale'][$prefLocale];
                        } else {
                            $title = current($email['locale']);
                        }
                        $options = array();
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=emails&amp;action=edit&amp;id=' . $email["pk_i_id"] . '">' . __('Edit') . '</a>';

                        $auxOptions = '<ul>'.PHP_EOL;
                        foreach( $options as $actual ) {
                            $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                        }
                        $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                        $row = array();
                        $row[] = $email['s_internal_name'] . $actions;
                        $row[] = $title['s_title'];
                        $aData[] = $row;
                    }
                    // ----
                    $array['iTotalRecords']         = $displayRecords;
                    $array['iTotalDisplayRecords']  = count($emails);
                    $array['iDisplayLength']        = $limit;
                    $array['aaData'] = $aData;

                    $page  = (int)Params::getParam('iPage');
                    if(count($array['aaData']) == 0 && $page!=1) {
                        $total = (int)$array['iTotalDisplayRecords'];
                        $maxPage = ceil( $total / (int)$array['iDisplayLength'] );

                        $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                        if($maxPage==0) {
                            $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                            $this->redirectTo($url);
                        }

                        if($page > 1) {
                            $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                            $this->redirectTo($url);
                        }
                    }

                    $this->_exportVariableToView('aEmails', $array);

                    $this->doView("emails/index.php");
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

    /* file end: ./oc-admin/emails.php */
?>