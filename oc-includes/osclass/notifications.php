<?php
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

    function alert_new_item($item)
    {
        if (osc_notify_new_item()) {
            require_once LIB_PATH . 'phpmailer/class.phpmailer.php' ;
            $mail = new PHPMailer ;
            $mail->CharSet = "utf-8" ;
            $mail->Host = 'localhost' ;
            $mail->From = osc_contact_email() ;
            $mail->FromName = osc_page_title() ;
            $mail->Subject = '[ ' . __('New item') . ' ] ' . osc_page_title() ;
            $mail->AddAddress(osc_contact_email(), osc_page_title()) ;
            $mail->IsHTML(true) ;
            $body = '' ;
            $body .= __('Contact Name') . ': ' . $item['s_contact_name'] . '<br/>';
            $body .= __('Contact E-mail') . ': ' . $item['s_contact_email'] . '<br/>';
            if (isset($item['locale'])) {
                foreach ($item['locale'] as $locale => $data) {
                    $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'");
                    $body .= '<br/>';
                    if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
                        $body .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
                    } else {
                        $body .= __('Language') . ': ' . $locale . '<br/>';
                    }
                    $body .= __('Title') . ': ' . $data['s_title'] . '<br/>';
                    $body .= __('Description') . ': ' . $data['s_description'] . '<br/>';
                    $body .= '<br/>';
                }
            } else {
                $body .= __('Title') . ': ' . $item['s_title'] . '<br/>';
                $body .= __('Description') . ': ' . $item['s_description'] . '<br/>';
            }
            $body .= __('Price') . ': ' . $item['f_price'] . ' ' . $item['fk_c_currency_code'] . '<br/>';
            $body .= __('Country') . ': ' . $item['s_country'] . '<br/>';
            $body .= __('Region') . ': ' . $item['s_region'] . '<br/>';
            $body .= __('City') . ': ' . $item['s_city'] . '<br/>';
            $body .= __('Url') . ': ' . osc_create_item_url($item) . '<br/>';
            $mail->Body = $body;
            if (!$mail->Send())
                echo $mail->ErrorInfo;
        }
    }

    function mail_validation($item) {
        if (osc_enabled_item_validation()) {
            $from = osc_contact_email() ;
            $from_name = osc_page_title() ;
            $subject = __('Validate your ad') . ' - ' . osc_page_title() ;
            $body = '' ;
            $site = osc_page_title() ;
            $body .= __('Dear ') . $item['s_contact_name'] . ',<br/>';
            $body .= __('You\'re receiving this email because an Ad is being placed at ' . $site . '. You are requested to validate this item with the link at the end of the email. If you didn\'t place this ad, please ignore this email. Details of the ads:') . '<br/>';
            $body .= __('Contact Name') . ': ' . $item['s_contact_name'] . '<br/>';
            $body .= __('Contact E-mail') . ': ' . $item['s_contact_email'] . '<br/>';

            if (isset($item['locale'])) {
                foreach ($item['locale'] as $locale => $data) {
                    $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'");
                    $body .= '<br/>';
                    if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
                        $body .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
                    } else {
                        $body .= __('Language') . ': ' . $locale . '<br/>';
                    }
                    $body .= __('Title') . ': ' . $data['s_title'] . '<br/>';
                    $body .= __('Description') . ': ' . $data['s_description'] . '<br/>';
                    $body .= '<br/>';
                }
            } else {
                $body .= __('Title') . ': ' . $item['s_title'] . '<br/>';
                $body .= __('Description') . ': ' . $item['s_description'] . '<br/>';
            }


            $body .= __('Price') . ': ' . $item['f_price'] . ' ' . $item['fk_c_currency_code'] . '<br/>' ;
            $body .= __('Country') . ': ' . $item['s_country'] . '<br/>' ;
            $body .= __('Region') . ': ' . $item['s_region'] . '<br/>' ;
            $body .= __('City') . ': ' . $item['s_city'] . '<br/>' ;
            $body .= __('Url') . ': ' . osc_create_item_url($item) . '<br/>' ;
            $body .= __('You can validate your ad in this url') . ': <a href="' . osc_base_url() . 'item.php?action=activate&id=' . $item['pk_i_id'] . '&secret=' . $item['s_secret'] . '" >' . osc_base_url() . 'item.php?action=activate&id=' . $item['pk_i_id'] . '&secret=' . $item['s_secret'] . '</a><br/>' ;
            $body .= "<br/>--<br/>" . osc_page_title() ;

            $params = array(
                'from' => $from
                ,'from_name' => $from_name
                ,'subject' => $subject
                ,'to' => $item['s_contact_email']
                ,'to_name' => $item['s_contact_name']
                ,'body' => $body
                ,'alt_body' => $body
            ) ;
            osc_sendMail($params) ;
        }
    }
?>