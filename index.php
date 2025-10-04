<?php
    require __DIR__ . '/config.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // استدعاء المكتبة
    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';

    // check if user coming from a request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $user =     filter_var($_POST['username'], FILTER_SANITIZE_STRING); // تخفي اي اكواد html في البيانات المبعوته
        //$user = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // تعطل اكواد ال html // هتففضل موجوده بس هتبقي معطله اكنها  نص عادي
        $email =    filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // بتشيل اي حاجه مش مناسبه في الايميل زي المسافات والعلامات الخاصة
        $phone =    filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
        $message =  $_POST['message'];
        
        // form errors array to store errors
        $formErrors = array();

        if (empty($user)) { 
            $formErrors[] = 'Username can\'t be empty';
        }
        else if (strlen($user) < 2) {
            $formErrors[] = 'Username must be larger than 2 characters';
        }
        if (empty($email)) { 
            $formErrors[] = 'Email can\'t be empty';
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formErrors[] = 'Email is not valid';
        }
        if (!preg_match('/^(010|011|012|015)[0-9]{8}$/', $phone) && !empty($phone)) { 
            $formErrors[] = 'Phone number must start with 010, 011, 012, or 015 and be 11 digits long';
        }
        if (strlen($message) < 10 && !empty($message)) {
            $formErrors[] = 'Message must be larger than 10 characters';
        }

        
        if (empty($formErrors)) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = $_ENV['MAIL_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['MAIL_USERNAME'];
                $mail->Password   = $_ENV['MAIL_PASSWORD'];
                $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
                $mail->Port       = $_ENV['MAIL_PORT'];


                $mail->setFrom($_ENV['MAIL_USERNAME'], 'Website Contact');
                $mail->addAddress($_ENV['MAIL_USERNAME']);
                $mail->addReplyTo($_POST['email'], $_POST['username']);

                $mail->isHTML(true);
                $mail->Subject =    "New Contact Form Submission";
                $mail->Body    =    "<p><b>Name:</b> " . htmlspecialchars($_POST['username']) . "</p>
                                    <p><b>Email:</b> " . htmlspecialchars($_POST['email']) . "</p>
                                    <p><b>Phone:</b> " . htmlspecialchars($_POST['phone']) . "</p>
                                    <p><b>Message:</b><br>" . nl2br(htmlspecialchars($_POST['message'])) . "</p>";

                $mail->send();
                
                // رسالة تاكيد لصاحب الرسالة
                $mail->clearAddresses();
                $mail->addAddress($email, $user); 
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);

                $mail->Subject = "Thank you for contacting us";

                // HTML Template for confirmation email
                $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
                        .email-container {
                            background:#fff; border-radius:8px; padding:20px;
                            box-shadow:0 2px 8px rgba(0,0,0,0.1); max-width:600px; margin:auto;
                        }
                        h2 { color:#28a745; }
                        p { color:#333; line-height:1.5; }
                        .footer { margin-top:20px; font-size:12px; color:#777; }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <h2>Thank you, " . htmlspecialchars($user) . "!</h2>
                        <p>We have received your message and will get back to you as soon as possible.</p>
                        <p>If you need urgent support, feel free to reply directly to this email.</p>

                        <p><b>Our Contact Email:</b> " . $_ENV['MAIL_USERNAME'] . "</p>

                        <div class='footer'>
                            <p>This message was sent from the contact form on our website.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
				$mail->send();
                
                $user = $email = $phone = $message = '';
                $success = '<div class="alert alert-success">We have received your message</div>';
                
            } catch (Exception $e) {
                $formErrors[] = "Message could not be sent. Error: {$mail->ErrorInfo}";
            }
            
    	}

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/contact.css">
    <script src="https://kit.fontawesome.com/2824da9ec7.js" crossorigin="anonymous"></script>
    <title>contact</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-3">Contact Me</h1>
        <form class="contact-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <div class="errors ">
                <?php
                    if(isset($formErrors) && !empty($formErrors)){
                        foreach($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
					if (isset($success)) {echo $success;}

                ?>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-signature"></i></span>
                <input
                    type="text"
                    name="username"
                    class="username form-control user"
                    placeholder="Username"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                    value="<?php if(isset($user)) {echo $user;} ?>"
                >
                <span class="input-group-text asterisx" id="basic-addon1">*</span>
            </div>
            <div class="alert alert-danger custom-alert empty-username">
                        Username can't be <strong>empty</strong>
            </div>
            <div class="alert alert-danger custom-alert length-username">
                        Username must be larger than <strong>2</strong> characters
            </div>


            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input
                    type="email"
                    name="email"
                    class="form-control email"
                    placeholder="example@gmail.com"
                    aria-label="Email"
                    value="<?php if(isset($email)) {echo $email;} ?>"
                >
                <span class="input-group-text asterisx" id="basic-addon1">*</span>
            </div>
            <div class="alert alert-danger custom-alert empty-email">
                Email can't be <strong>empty</strong>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                <input
                    type="tel"
                    name="phone"
                    class="form-control phone"
                    aria-label="Phone number"
                    minlength="11"
                    placeholder="Enter your phone number"
                    value="<?php if(isset($phone)) {echo $phone;} ?>"
                >
            </div>
            <div class="alert alert-danger custom-alert len-phone">
                Phone number must start with <strong>010, 011, 012</strong> or <strong>015</strong> and be <strong>11</strong> digits long
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Your Message!</span>
                <textarea
                    name="message"
                    class="form-control message"
                    aria-label="With textarea"><?php if(isset($message)) {echo $message;} ?></textarea>
            </div>
            <div class="alert alert-danger custom-alert mb-3 len-message">
                Message must be larger than <strong>10</strong> characters
            </div>
                <button type="submit" class="btn btn-success">
                    <i class="fa-regular fa-paper-plane"></i> Send
                </button>
                <!-- <input type="submit" class="btn btn-success mt-3" value="Send"> -->
        </form>
    </div>

    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>