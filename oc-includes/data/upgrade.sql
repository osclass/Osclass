INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'enabled_user_validation', true, 'BOOLEAN');

ALTER TABLE  /*TABLE_PREFIX*/t_user ADD  `s_pass_code` VARCHAR(100) NULL ,
ADD  `s_pass_date` DATETIME NULL ,
ADD  `s_pass_question` VARCHAR(100) NULL ,
ADD  `s_pass_answer` VARCHAR(100) NULL,
ADD  `s_pass_ip` VARCHAR(15) NULL;


INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (13, 'email_user_forgot_password', 1, NOW() );

INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (13, 'en_US', '{WEB_TITLE} Recover your password', '<p>Hi {USER_NAME},</p>\r\n<p> </p>\r\n<p>We sent this e-mail because you forgot your password. Follow the link to recover it : {PASSWORD_LINK}</p>\r\n<p>The link will be disabled in 24 hours.</p>\r\n<p> </p>\r\n<p>If you didn''t forget your password, ignore this message. This petition was made from IP : {IP_ADDRESS} on {DATE_TIME}</p>');

