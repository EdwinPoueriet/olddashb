<?php

namespace App\Services;
use PHPMailer;

class Mailer
{


    public $mail;
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'mail.adgsystems.com.do';
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = 'notificaciones@adgsystems.com.do';                 // SMTP username
        $this->mail->Password = '789456123aA@a';                           // SMTP password
        $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 587;                                    // TCP port to connect to
    }


    public function send (
        $to,
        $subject,
        $body,
        $from_addr = 'notificaciones@adgsystems.com.do',
                          $from_name = 'SDM Dashboard - Notificaciones') {
        $this->mail->setFrom($from_addr, $from_name);
        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;

        $this->mail->isHTML(true);
        $this->mail->Body    = $body;
       if(!$this->mail->send()) {
           throw new \Exception('Could not send: '.$this->mail->ErrorInfo);
       } else {
           return true;
       }
    }

}