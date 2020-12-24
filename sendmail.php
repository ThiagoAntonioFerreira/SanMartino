<?php

include 'banco.php';
$id = $_GET["id_orcamento"];
$pdo = Banco::conectar();
$sql = "select id, smtp, remetente, porta, usa_ssl,
usuario_email, senha_email from parametro";
$q = $pdo->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);

Banco::desconectar();

$email_smtp = $data['smtp'];
$email_usuario = $data['usuario_email'];
$email_senha = $data['senha_email'];
$email_porta = $data['porta'];
$email_segura = $data['usa_ssl'];
$destinatario_teste = 'thiago.antonio.ferreira@gmail.com';

$segura = "ssl";
if ($email_segura == "S") {
    $segura = "ssl";
}else if ($email_segura == "T") {
    $segura = "tls";
}else {
    $segura = "";
}


use PHPMailer\PHPMailer\PHPMailer;
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


$mail = new PHPMailer();

// Settings
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';

$mail->Host       = $email_smtp; // SMTP server example
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = $segura;
$mail->Port       = $email_porta;                    // set the SMTP port for the GMAIL server
$mail->From       = $email_usuario;
$mail->addAddress($destinatario_teste);
$mail->Username   = $email_usuario; // SMTP account username example
$mail->Password   = $email_senha;        // SMTP account password example

// Content
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Email de Teste gerado pelo Integrador DW';
$mail->Body    = 'Esta é uma mensagem de teste gerada pelo <b>Integrador da DW.</b>';
$mail->AltBody = 'Esta é uma mensagem de teste gerada pelo Integrador da DW.';

if(!$mail->send()) {
    echo 'Mensagem nao pode ser enviada.';
    echo 'Erro: ' . $mail->ErrorInfo;
} else {
    echo 'E-mail de testes enviado com sucesso.';
}


?>