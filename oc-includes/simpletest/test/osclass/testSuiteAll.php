<?php
require_once(dirname(__FILE__).'/../../test_case.php');
class AllTests extends TestSuite {
    function AllTests() {
        global $test_str;
        global $php_sapi;
        $this->TestSuite('All tests');
        $tests = array();

        if(PHP_SAPI==='cli') {
            $php_sapi = 'cli';
            foreach($_SERVER['argv'] as $k => $v) {
                $tmp_arg = explode("=", $v);
                $k = str_replace("--", "", $tmp_arg[0]);
                if(count($tmp_arg)>1) {
                    $v = $tmp_arg[1];
                    if($k=='installer' || $k=='frontend' || $k=='admin') {
                        if($v=='' || $v==null) {
                            $tests[$k] = '';
                        } else {
                            $tmp = explode(",", $v);
                            foreach ($tmp as $t) {
                                $tests[$k][$t] = 1;
                            }
                        }
                    }
                } else {
                    $tests[$k] = '';
                }
            }
        } else {
            $php_sapi = 'web';
            foreach($_REQUEST as $k => $v) {
                if($k=='installer' || $k=='frontend' || $k=='admin') {
                    if($v=='' || $v==null) {
                        $tests[$k] = '';
                    } else {
                        $tmp = explode(",", $v);
                        foreach ($tmp as $t) {
                            $tests[$k][$t] = 1;
                        }
                    }
                }
            }
        }

        if(empty($tests)) {
            $tests['installer'] = '';
            $tests['frontend'] = '';
            $tests['admin'] = '';
        }

        $test_str = '';

        foreach($tests as $k => $v) {
            if($k=="installer" || $k=="frontend" || $k=="admin") {
                $test_str .= $k." {".(is_array($v)?implode(",", array_keys($v)):'all')."}    ";
            }
        }

        // INSTALLER
        if(isset($tests['installer'])) {
            if(isset($tests['installer']['install']) || $tests['installer']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Installer-installer.php');
            }
            if(isset($tests['installer']['clean']) || $tests['installer']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Installer-clean.php');
            }
        }


        // FRONTEND
        if(isset($tests['frontend'])) {
            require_once(dirname(__FILE__).'/../../../../oc-load.php');

            if(isset($tests['frontend']['contact']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-contactForm.php');
            }
            if(isset($tests['frontend']['login']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-login.php');
            }
            if(isset($tests['frontend']['register']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-register.php');
            }
            if(isset($tests['frontend']['search']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-search.php');
            }
            if(isset($tests['frontend']['users']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-users.php');
            }
            if(isset($tests['frontend']['items']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-items.php');
            }
            if(isset($tests['frontend']['page']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-page.php');
            }
            if(isset($tests['frontend']['seo']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-seo.php');
            }
            if(isset($tests['frontend']['csrf']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-csrf.php');
            }
        }

        // FRONTEND WITH PERMALINKS
        /*if(isset($tests['frontend'])) {
            require_once(dirname(__FILE__).'/../../../../oc-load.php');

            // activate permalinks
            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-onPermalinks.php');

            if(isset($tests['frontend']['contact']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-contactForm.php');
            }
            if(isset($tests['frontend']['login']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-login.php');
            }
            if(isset($tests['frontend']['register']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-register.php');
            }
            if(isset($tests['frontend']['search']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-search.php');
            }
            if(isset($tests['frontend']['users']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-users.php');
            }
            if(isset($tests['frontend']['items']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-items.php');
            }
            if(isset($tests['frontend']['page']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-page.php');
            }
            if(isset($tests['frontend']['seo']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-seo.php');
            }
            // deactivate permalinks
            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-offPermalinks.php');
        }*/

        // ADMIN
        if(isset($tests['admin'])) {
            require_once(dirname(__FILE__).'/../../../../oc-load.php');

            if(isset($tests['admin']['categories']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-categories.php');
            }
            if(isset($tests['admin']['settings']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-settings.php');
            }
            if(isset($tests['admin']['administrators']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-administrators.php');
            }
            if(isset($tests['admin']['emailandalerts']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-emailsAndAlerts.php');
            }
            if(isset($tests['admin']['users']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-users.php');
            }
            if(isset($tests['admin']['languages']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-languages.php');
            }
            if(isset($tests['admin']['tools']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-tools.php');
            }
            if(isset($tests['admin']['pages']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-pages.php');
            }
            if(isset($tests['admin']['plugins']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-plugins.php');
            }
            if(isset($tests['admin']['appearance']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-appearance.php');
            }
            if(isset($tests['admin']['items']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-items.php');
            }
            if(isset($tests['admin']['stats']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-stats.php');
            }
            if(isset($tests['admin']['moderator']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-moderator.php');
            }
            if(isset($tests['admin']['reported']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-reported.php');
            }
            if(isset($tests['admin']['market']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-market.php');
            }
            if(isset($tests['admin']['customfields']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-customfields.php');
            }
            if(isset($tests['admin']['csrf']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-csrf.php');
            }
        }

    }
}
?>
