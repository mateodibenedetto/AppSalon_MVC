<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;


class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($nombre, $email, $token) 
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        // Crear el objeto de email
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a023a1acb88f80';
        $mail->Password = '38972ba6b6732d';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Definir el contenido
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/public/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar este mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones() { 
        // Crear el objeto de email
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a023a1acb88f80';
        $mail->Password = '38972ba6b6732d';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu contraseña';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Definir el contenido
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/public/recuperar?token=" . $this->token . "'>Reestablecer contraseña</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar este mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }
}

?>