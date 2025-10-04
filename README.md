# Contact Form Project
A responsive PHP contact form with PHPMailer integration, Bootstrap layout, and jQuery validation. Users can submit messages, and both admin and user receive confirmation emails.

## Live Demo
[Try the contact form here](https://contact.free.nf/index.php)

## Features
- PHP backend validation for username, email, phone, and message.
- Sends email to admin using PHPMailer.
- Sends confirmation email to user.
- Responsive design using Bootstrap 5.
- jQuery validation and interactive error messages.

## Configure config.php or .env with your mail settings:
MAIL_HOST=smtp.example.com
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=yourpassword
MAIL_PORT=587
MAIL_ENCRYPTION=tls

---
## Usage
- Fill in the contact form fields.
- Click **Send**.
- Errors will show if input is invalid.
- Success message confirms the message is sent.

## Notes
- Phone numbers must start with 010, 011, 012, or 015 and be 11 digits.
- Username must be at least 3 characters.
- Message must be at least 10 characters.

## Tech Stack
- PHP
- PHPMailer
- Bootstrap 5
- jQuery
