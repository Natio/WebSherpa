<?

require_once ( __EXTERNAL_LIBRARIES__ . '/phpmailer/class.phpmailer.php');
/*
require_once (__EXTERNAL_LIBRARIES__ . '/aws/aws.phar');

namespace Aws\Tests\Ses;

use Aws\Ses\SesClient;
*/
class PCEmailSender {

    /**
     * 
     * @param PCEmail $mail
     */
    public static function sendMail($mail) {
       /*
        $client = new SesClient(array(
            "key" => "AKIAIDBPOWSJGOKXMFXQ",
            "secret" => "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",
            'region' => Region::EU_WEST_1)
        );
        * 
        */
        $mailer = new PHPMailer();
        if($mail->isHTML()) $mailer->IsHTML();
        $mailer->IsSMTP();
        $mailer->Host="email-smtp.us-east-1.amazonaws.com:25";
        $mailer->Username = "AKIAIRY6BQCOYTQUGWHQ";
        $mailer->Password = "Atk2E+Qc0aMGXzwejLB2WlkH1nb6exvHU0uwy5ChAGqS";
        $mailer->SMTPAuth = TRUE;
        $mailer->SMTPSecure = "tls";
        $mailer->From = $mail->getSender();
        $mailer->FromName = $mail->getSenderName();
        $mailer->AddAddress($mail->getRecipient());
        $mailer->Subject = $mail->getSubject();
        $mailer->Body = $mail->getBody();
        
        if($mailer->Send()){
            return TRUE;
        }
        error_log($mailer->ErrorInfo);
        return FALSE;
    }

}

