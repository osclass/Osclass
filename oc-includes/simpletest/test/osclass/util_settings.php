<?php
error_reporting(E_ALL);
class utilSettings {
 
    public function __construct() {
    }
    
    //oc-admin
    public function findValueByName($name)
    {
        $value = Preference::newInstance()->findValueByName($name);
        return $value;
    }
    
    
    // frontend
    
    /**
     * Set time in seconds, The user needs to wait $value
     * seconds for insert a new item again.
     * 
     * @param integer $value num of seconds
     * @return boolean 
     */
    public function set_items_wait_time($value = 0) 
    {
        $items_wait_time = Preference::newInstance()->findValueByName('items_wait_time');
        Preference::newInstance()->update(array('s_value' => $value) ,array('s_name'  => 'items_wait_time')); 
        return $items_wait_time;
    }
    
    /**
     * Set true or false the recaptcha for items.
     * 
     * @param boolean $bool
     * @return boolean 
     */
    public function set_enabled_recaptcha_items($bool = 0)
    {
        $enabled_recaptcha_items = Preference::newInstance()->findValueByName('enabled_recaptcha_items');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'enabled_recaptcha_items')); 
        return $enabled_recaptcha_items;
    }
    
    /**
     * Set true or false, Enable item validation by users.
     * 
     * @param boolean $bool 
     * @return boolean 
     */
    public function set_moderate_items($bool = -1)
    {
        $moderate_items = Preference::newInstance()->findValueByName('moderate_items');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'moderate_items')); 
        return $moderate_items;
    }
    
    /**
     * Set true or false, Only allow registered users to post items.
     * 
     * @param boolean $bool 
     * @return boolean 
     */
    public function set_reg_user_post($bool = 0)
    {
        $reg_user_post = Preference::newInstance()->findValueByName('reg_user_post');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'reg_user_post')); 
        return $reg_user_post;
    }
    
    
    public function set_selectable_parent_categories($bool = 0)
    {
        $selectable_parent_categories = Preference::newInstance()->findValueByName('selectable_parent_categories');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'selectable_parent_categories')); 
        return $selectable_parent_categories;
    }
    
    public function set_logged_user_item_validation($bool = 0)
    {
        $logged_user_item_validation = Preference::newInstance()->findValueByName('logged_user_item_validation');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'logged_user_item_validation'));
        return $logged_user_item_validation;
    }

    // users
    public function set_enabled_user_validation($bool = 0)
    {
        $enabled_user_validation = Preference::newInstance()->findValueByName('enabled_user_validation');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'enabled_user_validation'));
        return $enabled_user_validation;
    }

    public function set_enabled_users($bool = 0)
    {
        $enabled_users = Preference::newInstance()->findValueByName('enabled_users');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'enabled_users'));
        return $enabled_users;
    }

    public function set_enabled_user_registration($bool = 0)
    {
        $enabled_user_registration = Preference::newInstance()->findValueByName('enabled_user_registration');
        Preference::newInstance()->update(array('s_value' => $bool) ,array('s_name'  => 'enabled_user_registration'));
        return $enabled_user_registration;
    }
}
?>