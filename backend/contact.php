<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$senderEmail = isset($data['email']) ? trim($data['email']) : '';
$subject = isset($data['subject']) ? trim($data['subject']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

if (empty($senderEmail) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

try {
    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'caidgahs@gmail.com';
    $mail->Password = 'iyga dtdd ndpq rskd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = 0;

    // Recipients
    $mail->setFrom('caidgahs@gmail.com', 'VoteEase Contact Form');
    $mail->addAddress('caidgahs@gmail.com', 'VoteEase Admin');
    $mail->addReplyTo($senderEmail);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'VoteEase Contact: ' . $subject;

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
                margin: 20px auto; 
                background: white;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .header { 
                background: #1d1f23; 
                color: white; 
                padding: 20px; 
                text-align: center; 
            }
            .header h1 {
                margin: 0;
                font-size: 22px;
                color: #4e9eff;
            }
            .content { 
                padding: 30px; 
            }
            .field {
                margin-bottom: 20px;
            }
            .field-label {
                font-weight: bold;
                color: #1e293b;
                margin-bottom: 5px;
            }
            .field-value {
                color: #475569;
                padding: 10px;
                background: #f8fafc;
                border-radius: 4px;
                border-left: 3px solid #4e9eff;
            }
            .message-box {
                background: #f8fafc;
                padding: 15px;
                border-radius: 4px;
                border-left: 3px solid #4e9eff;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .footer { 
                text-align: center; 
                padding: 15px; 
                background: #f8fafc; 
                color: #64748b; 
                font-size: 13px; 
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>New Contact Form Submission</h1>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='field-label'>From:</div>
                    <div class='field-value'>" . htmlspecialchars($senderEmail) . "</div>
                </div>
                
                <div class='field'>
                    <div class='field-label'>Subject:</div>
                    <div class='field-value'>" . htmlspecialchars($subject) . "</div>
                </div>
                
                <div class='field'>
                    <div class='field-label'>Message:</div>
                    <div class='message-box'>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>This message was sent via VoteEase Contact Form</p>
                <p>" . date('F j, Y, g:i a') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->Body = $emailBody;

    // Plain text version
    $mail->AltBody = "New Contact Form Submission\n\n" .
        "From: " . $senderEmail . "\n" .
        "Subject: " . $subject . "\n\n" .
        "Message:\n" . $message . "\n\n" .
        "Sent via VoteEase Contact Form\n" .
        date('F j, Y, g:i a');

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully'
    ]);

} catch (Exception $e) {
    error_log("Contact Form Error: " . $mail->ErrorInfo);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send message. Please try again later.'
    ]);
}
?>