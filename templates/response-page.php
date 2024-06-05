<?php
/*
Template Name: Response Page Template
*/
?>

<!DOCTYPE html>
<html>
<head>
    <title>Response Page</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding-bottom: 150px;
        }
        .container { width: 80%; margin: 0 auto; text-align: center; }
        .error-message { margin-top: 50px; color: red; }
        .button-container { margin-top: 20px; }
        .button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; text-decoration: none; }
        .button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sure you're on DigiHub?</h1>
        <p class="error-message">Your username was not found. Not to worry, you can sign up with the button below.</p>
        <div class="button-container">
            <?php
            // Detect if the user is on a mobile device
            $is_mobile = preg_match('/Mobile|Android|iP(hone|od|ad)/i', $_SERVER['HTTP_USER_AGENT']);
            
            // Set the signup link based on the device type
            if ($is_mobile) {
                $signup_link = 'https://play.google.com/store/apps/details?id=com.greengrowthdigihub.app';
            } else {
                $signup_link = 'https://app.thegreengrowth.com/register';
            }
            ?>
            <a href="<?php echo esc_url($signup_link); ?>" class="button">Sign Up</a>
            <a href="javascript:history.back()" class="button">Go Back</a>
        </div>
    </div>
</body>
</html>
