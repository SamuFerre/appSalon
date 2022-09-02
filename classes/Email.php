<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $name;
    public $token;
    public function __construct($email, $name, $token) 
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '285514bcd29f80';
        $mail->Password = '30c518baf0674b';
    
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirm your Account';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hi " . $this->email .  "</strong>, you created an account at App Salon, you just need to confirm your account pressing the next link</p>";
        $contenido .= "<p>Press here: <a href='http://localhost:3000/confirm-account?token=" . $this->token . "'>Confirm Account</a>";        
        $contenido .= "<p>If you have not created this account, just ignore this message</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();

    }

    public function enviarInstrucciones() {

         //Crear el objeto de email
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = 'smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Port = 2525;
         $mail->Username = '285514bcd29f80';
         $mail->Password = '30c518baf0674b';
     
         $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
         $mail->Subject = 'Reset your password';
 
         // Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';
 
         $contenido = '<html>';
         $contenido .= "<p><strong>Hi " . $this->name .  "</strong>, This is an email to reset your password, follow the instruccions to do it.</p>";
         $contenido .= "<p>Press here: <a href='http://localhost:3000/recover?token=" . $this->token . "'>Reset Password</a>";        
         $contenido .= "<p>If you have not asked to reset your password, just ignore this message</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;
 
         //Enviar el mail
         $mail->send();
    }
}

?>