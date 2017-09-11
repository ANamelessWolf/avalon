<?php
    $input = file_get_contents('php://input');
    $input = json_decode($input);
    //Se definen las variables para poder enviar el correo
    $message = $input->message;
    $subject = $input->subject;
    $email = $input->email;
    $message = wordwrap($message,70);
    //Se envía el correo
    $result = mail($email, $subject, $message);
    if(!$result)
        $result = 0;
    else
        $result = 1;
?>