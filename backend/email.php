<?php
require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // server settings
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'caidgahs@gmail.com';
        $this->mail->Password = 'iyga dtdd ndpq rskd';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;

        // sender inf  
        $this->mail->setFrom('caidgahs@gmail.com', 'VoteEase');
        $this->mail->isHTML(true);


        $this->mail->SMTPDebug = 0;
    }

    public function sendWelcomeEmail($toEmail, $toName)
    {
        try {

            $firstName = $this->getFirstName($toName);
            $properFirstName = $this->capitalizeName($firstName);

            // clear all addresses
            $this->mail->clearAddresses();

            // recipient
            $this->mail->addAddress($toEmail, $properFirstName);

            // email contents
            $this->mail->Subject = 'Welcome to VoteEase - Account Confirmation';

            $emailBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        line-height: 1.6; 
                        color: #333; 
                        margin: 0; 
                        padding: 0; 
                        background-color: #f4f4f4;
                    }
                    .container { 
                        max-width: 600px; 
                        margin: 0 auto; 
                        background: white;
                        border-radius: 10px;
                        overflow: hidden;
                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    }
                    .header { 
                        background: linear-gradient(135deg, #3b82f6, #1e40af); 
                        color: white; 
                        padding: 30px; 
                        text-align: center; 
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 28px;
                    }
                    .content { 
                        padding: 30px; 
                    }
                    .welcome-text { 
                        font-size: 20px; 
                        font-weight: bold; 
                        margin-bottom: 20px; 
                        color: #1e293b; 
                    }
                    .features {
                        background: #f8fafc;
                        padding: 20px;
                        border-radius: 8px;
                        margin: 20px 0;
                    }
                    .features ul { 
                        margin: 15px 0; 
                        padding-left: 20px; 
                    }
                    .features li { 
                        margin-bottom: 10px; 
                    }
                    .footer { 
                        text-align: center; 
                        margin-top: 30px; 
                        padding-top: 20px; 
                        border-top: 1px solid #e2e8f0; 
                        color: #64748b; 
                        font-size: 14px; 
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Welcome to VoteEase! 🗳️</h1>
                    </div>
                    <div class='content'>
                        <div class='welcome-text'>Thank you for creating an account, $properFirstName!</div>
                        
                        <p>We're excited to welcome you to <strong>VoteEase</strong> - <em>Your Voice, Your Vote. Simplified.</em></p>
                        
                        <p>Your account has been successfully created and you can now:</p>
                        
                        <div class='features'>
                            <ul>
                                <li>✅ Participate in secure online elections</li>
                                <li>✅ Access important voting information</li>
                                <li>✅ Stay updated with election schedules</li>
                                <li>✅ Exercise your democratic right conveniently</li>
                            </ul>
                        </div>
                        
                        <p>If you have any questions or need assistance with our platform, please don't hesitate to contact our support team.</p>
                        
                        <p>Ready to make your voice heard?</p>
                        
                        <p>Best regards,<br><strong>The VoteEase Team</strong></p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " VoteEase. All rights reserved.</p>
                        <p>This is an automated message, please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $this->mail->Body = $emailBody;

            $this->mail->AltBody = "Welcome to VoteEase!\n\nThank you for creating an account, $properFirstName!\n\nWe're excited to welcome you to VoteEase - Your Voice, Your Vote. Simplified.\n\nYour account has been successfully created and you can now:\n• Participate in secure online elections\n• Access important voting information  \n• Stay updated with election schedules\n• Exercise your democratic right conveniently\n\nIf you have any questions or need assistance with our platform, please don't hesitate to contact our support team.\n\nBest regards,\nThe VoteEase Team\n\n© " . date('Y') . " VoteEase. All rights reserved.\nThis is an automated message, please do not reply to this email.";

            $this->mail->send();
            return true;

        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    private function getFirstName($fullName)
    {

        if (strpos($fullName, ',') !== false) {
            $parts = explode(',', $fullName);
            if (count($parts) > 1) {
                $firstNamePart = trim($parts[1]);

                $firstName = explode(' ', $firstNamePart)[0];
                return $firstName;
            }
        }


        $nameParts = explode(' ', $fullName);
        return $nameParts[0];
    }

    private function capitalizeName($name)
    {

        return ucfirst(strtolower($name));
    }
}

?>