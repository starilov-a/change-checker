<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    protected $from = 'antonstarilov@yandex.ru';
    protected $notificate;
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    public function sendChangeNotificate($address, $sites) {
        try {
            $this->settings();
            //Recipients
            $this->mail->setFrom($this->from, 'Mailer');
            foreach ($address as $addres) {
                $this->mail->addAddress($addres);
            }

            //Content
            $this->mail->CharSet = "utf-8";
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Site monitoring - notification of changes';
            $this->mail->Body    = "Fixed changes on the sites:<br>";
            foreach ($sites as $site)
                $this->mail->Body    = "<b>$site->url</b><br>";
            $this->mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    protected function settings() {
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = 'smtp.yandex.ru';                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = $this->from;                     //SMTP username
        $this->mail->Password   = 'anton2000starilov';                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $this->mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    }

}
