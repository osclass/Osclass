
INSERT INTO /*TABLE_PREFIX*/t_currency (pk_c_code, s_name, s_description, b_enabled) VALUES
    ('GBP', 'United Kingdom pound', 'Pound £', true),
    ('USD', 'United States dollar', 'Dollar US$', true),
    ('EUR', 'European Union euro', 'Euro €', true);

INSERT INTO /*TABLE_PREFIX*/t_preference VALUES
    ('osclass', 'version', 110, 'INTEGER'),
    ('osclass', 'theme', 'modern', 'STRING'),
    ('osclass', 'admin_language', 'en_US', 'STRING'),
    ('osclass', 'language', 'en_US', 'STRING'),
    ('osclass', 'pageDesc', 'open source classifieds', 'STRING'),
    ('osclass', 'maxSizeKb', 1000000, 'INTEGER'),
    ('osclass', 'allowedExt', 'png,gif,jpg', 'STRING'),
    ('osclass', 'dimThumbnail', '240x200', 'STRING'),
    ('osclass', 'dimNormal', '640x480', 'STRING'),
    ('osclass', 'keep_original_image', '1', 'BOOLEAN'),
    ('osclass', 'dateFormat', 'F j, Y', 'STRING'),
    ('osclass', 'timeFormat', 'g:i a', 'STRING'),
    ('osclass', 'weekStart', '0', 'STRING'),
    ('osclass', 'moderate_comments', '1', 'BOOLEAN'),
    ('osclass', 'reg_user_post', '1', 'BOOLEAN'),
    ('osclass', 'num_rss_items', '50', 'INTEGER'),
    ('osclass', 'active_plugins', '', 'STRING'),
    ('osclass', 'notify_new_item', '1', 'BOOLEAN'),
    ('osclass', 'auto_cron', '1', 'BOOLEAN'),
    ('osclass', 'notify_contact_item', '1', 'BOOLEAN'),
    ('osclass', 'notify_contact_friends', '1', 'BOOLEAN'),
    ('osclass', 'notify_new_comment', '1', 'BOOLEAN'),
    ('osclass', 'enabled_recaptcha_items', '0', 'BOOLEAN'),
    ('osclass', 'enabled_item_validation', '1', 'BOOLEAN'),
    ('osclass', 'enabled_user_validation', '1', 'BOOLEAN'),
    ('osclass', 'enabled_comments', '1', 'BOOLEAN'),
    ('osclass', 'mailserver_host', 'localhost', 'STRING'),
    ('osclass', 'mailserver_port', '', 'INTEGER'),
    ('osclass', 'mailserver_username', '', 'STRING'),
    ('osclass', 'mailserver_password', '', 'STRING'),
    ('osclass', 'mailserver_type', 'custom', 'STRING'),
    ('osclass', 'mailserver_auth', '', 'BOOLEAN'),
    ('osclass', 'mailserver_ssl', '', 'STRING'),
    ('osclass', 'currency', 'USD','STRING'),

    ('osclass', 'rewriteEnabled', '0', 'BOOLEAN'),
    ('osclass', 'mod_rewrite_loaded', '0', 'BOOLEAN'),
    ('osclass', 'rewrite_rules', '', 'STRING');

INSERT INTO /*TABLE_PREFIX*/t_cron (e_type, d_last_exec, d_next_exec) VALUES
    ('HOURLY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    ('DAILY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    ('WEEKLY', '0000-00-00 00:00:00', '0000-00-00 00:00:00');