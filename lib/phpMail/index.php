<?php 
$emailll = 'yc@y-courier.ru';  


  function smtpmail($to, $subject, $content, $attach=false)
  {
require_once('config.php');
require_once('class.phpmailer.php');
$mail = new PHPMailer(true);

$mail->IsSMTP();
echo $to."<br />".$subject."<br />".$content."<br />";
try {
  $mail->Host       = $__smtp['host']; 
  $mail->SMTPDebug  = $__smtp['debug']; 
  $mail->SMTPSecure = "ssl";

  $mail->SMTPAuth   = $__smtp['auth'];
  $mail->Host       = $__smtp['host'];
  $mail->Port       = $__smtp['port']; 
  $mail->CharSet    = 'UTF-8';
  $mail->Username   = $__smtp['username'];
  $mail->Password   = $__smtp['password'];
  $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
  $mail->AddAddress($to);
  $mail->SetFrom($__smtp['addreply'], $__smtp['username']);
  $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
  $mail->Subject = htmlspecialchars($subject);
  $mail->MsgHTML($content);
  if($attach)  $mail->AddAttachment($attach);
  $mail->Send();
  echo "Message sent Ok!</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); 
} catch (Exception $e) {
  echo $e->getMessage(); 
}
  }
  if(1 == 0)
  {

  }else{
    $too1 = "Сообщение с сайта Ваш курьер";
    $too2 = 'Вопрос: ' . $_POST['mesag1']. 
    '<br> Сообщение: ' . $_POST['mesag2']. 
    '<br> Компания: ' . $_POST['сом']. 
    '<br> Контактное лицо: ' . $_POST['name'] . 
    '<br> Телефон: ' . $_POST['phon'] . 
    '<br> E-mail: ' . $_POST['email'];

    smtpmail($emailll, $too1, $too2);
  }



?>  