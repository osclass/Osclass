<?php
    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
    require_once ABS_PATH . 'oc-load.php';
    require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';

    $ppl_data = explode('|', Params::getParam('rpl'));
    $item     = Item::newInstance()->findByPrimaryKey($ppl_data[1]);
    $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);
    View::newInstance()->_exportVariableToView('category', $category);
    $url = osc_search_category_url();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script type="text/javascript" src="https://www.paypalobjects.com/js/external/dg.js"></script>
        <title><?php echo osc_page_title(); ?></title>
    </head>
    <body>
        <script type="text/javascript">
            top.rd.innerHTML = '<?php _e('You cancel the payment process or there was an error. If the error continue, please contact the administrator', 'paypal'); ?>.<br/><br/><?php _e('If you do not want to continue the process', 'paypal'); ?> <a href="<?php echo $url; ?>" /><?php _e('click here', 'paypal'); ?></a>';
            top.dg.closeFlow();
        </script>
    </body>
</html>