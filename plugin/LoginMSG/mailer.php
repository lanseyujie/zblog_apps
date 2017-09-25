<?php
/**
 * PHP 使用 fsockopen 函数实现 SMTP 发信
 *
 * @version     1.0.2
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com/post/php-fsockopen-smtp-email.html
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

function LoginMSG_Mailer($mailto, $subject = 'Test Subject', $body = '<i style="color:red">Test Body</i>', $smtp_debug = 0) {
    global $zbp;

    $smtp_host = $zbp->Config('LoginMSG')->Smtpserver;
    $smtp_port = $zbp->Config('LoginMSG')->Smtpport;
    $smtp_openssl = $zbp->Config('LoginMSG')->Openssl;
    $smtp_username = $zbp->Config('LoginMSG')->Servicemailadd;
    $smtp_password = $zbp->Config('LoginMSG')->Servicemailpwd;
    $smtp_from = $zbp->Config('LoginMSG')->Servicemailadd;

    $smtp = array(
        array("EHLO ". $smtp_host ."\r\n", "220,250", "EHLO: "),
        array("AUTH LOGIN\r\n", "334", "AUTH LOGIN: "),
        array(base64_encode($smtp_username) ."\r\n", "334", "Send Base64 Encode username: "),
        array(base64_encode($smtp_password) ."\r\n", "235", "Send Base64 Encode password: "),
        array("MAIL FROM: <". $smtp_from .">\r\n", "250", "MAIL FROM: "),
        array("RCPT TO: <". $mailto .">\r\n", "250", "RCPT TO: "),
        array("DATA\r\n", "354", "DATA Start: "),
        array("From: ". $smtp_from ."\r\n", "", ""),
        array("To: ". $mailto ."\r\n", "", ""),
        array("X-Mailer: LANSEYUJIE WebMailer 1.0\r\n", "", ""),
        array("X-Priority: 1 (Highest)\r\n", "", ""),
        array("Subject: ". $subject ."\r\n", "", ""),
        array("Content-Type: text/html; charset=\"utf-8\"\r\n", "", ""),
        array("Content-Transfer-Encoding: base64\r\n\r\n", "", ""),
        array(base64_encode($body) ."\r\n", "", ""),
        array("\r\n.\r\n", "250", "DATA End: "),
        array("QUIT\r\n", "221", "QUIT: ")
    );
    $info = '';
    if (1 == $smtp_openssl) {
        $fp = @fsockopen('ssl://'. $smtp_host, $smtp_port);
    }
    else {
        $fp = @fsockopen($smtp_host, $smtp_port);
    }
    if (!$fp) {
        $info .= "FSOCKOPEN Error: Cannot conect to ". $smtp_host ."\r\n<br />";
    }
    foreach ($smtp as $request) {
        @fputs($fp, $request[0]);
        if (1 == $smtp_debug && '' != $request[1]) {
            $response = @fgets($fp, 128);
            $info .= $request[2] . $response ."\r\n<br />";
        }
    }
    @fclose($fp);
    return $info;
}
