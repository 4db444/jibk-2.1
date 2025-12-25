<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require BASE_PATH . '/vendor/autoload.php';
    require __DIR__ . '/../mail.conf.php';

    class MailController {
        static function Send ($email, $otp) {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = EMAIL_ADDRESS;                     //SMTP username
                $mail->Password   = EMAIL_PASS;                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('support.jibk@gmail.com', 'JIBK Project');
                $mail->addAddress($email, 'brahim alhiane');     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'OTP Authentication';
                $mail->Body    = <<<HtmlBody
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>Your OTP Code</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    </head>
                    <body style="margin:0; padding:0; background-color:#f3f4f6; font-family: Arial, Helvetica, sans-serif;">

                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:20px;">
                            <tr>
                                <td align="center">
                                    <!-- Email Container -->
                                    <table width="100%" max-width="500" cellpadding="0" cellspacing="0"
                                        style="background-color:#ffffff; border-radius:8px; padding:30px; max-width:500px;">
                                        <tr>
                                            <td align="center" style="padding-bottom:20px;">
                                                <h1 style="margin:0; color:#16a34a; font-size:24px;">
                                                    OTP Verification
                                                </h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#374151; font-size:16px; line-height:1.5;">
                                                <p>Hello,</p>
                                                <p>
                                                    Use the following One-Time Password (OTP) to complete your login:
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding:20px 0;">
                                                <div style="
                                                    display:inline-block;
                                                    background-color:#f0fdf4;
                                                    color:#16a34a;
                                                    font-size:28px;
                                                    font-weight:bold;
                                                    letter-spacing:6px;
                                                    padding:15px 30px;
                                                    border-radius:6px;
                                                    border:1px solid #bbf7d0;
                                                ">
                                                    $otp
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color:#6b7280; font-size:14px; line-height:1.5;">
                                                <p>
                                                    This code will expire in a few minutes.
                                                    If you did not request this, please ignore this email.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding-top:20px; font-size:12px; color:#9ca3af;">
                                                © <?= date('Y') ?> JIBK. All rights reserved.
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>

                    </body>
                    </html>

                HtmlBody;
                $mail->AltBody = "This is your OTP code : $otp";

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }