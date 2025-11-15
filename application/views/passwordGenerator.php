<!DOCTYPE html>
<html>
<head>
    <title>Password Generator</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <style>
        /* Copy over relevant styles for dark/light mode and other styling */
        body.light-mode {
            background-color: white;
            color: black;
        }
        body.dark-mode {
            background-color: #202124;
            color: white;
        }
        .header {
            width: 100%;
            background: linear-gradient(to right, #1ba593, #10706b);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 0px;
            color: white;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
        }
        .dark-mode-toggle {
            background: #3ca5a5;
            padding: 3px;
            border: 1px solid #40764c;
            border-radius: 5px;
            margin: 10px;
            position: absolute;
            right: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .light-mode .dark-mode-icon {
            display: none;
        }
        .dark-mode .light-mode-icon {
            display: none;
        }
        /* Add your own styles for the password generator section */
        .password-generator {
            margin-top: 20px;
            text-align: center;
        }
        .password-output {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .generate-button {
            background-color: #0d5e66;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .generate-button:hover {
            background-color: #0f7874;
        }
        .back-button-container {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }
        button {
            background-color: #0d5e66;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body class="light-mode">
    <div class="header">
        <h1>Password Generator</h1>
        <div class="dark-mode-toggle">
            <i class="fas fa-sun light-mode-icon"></i>
            <i class="fas fa-moon dark-mode-icon"></i>
        </div>
    </div>

    <div class="password-generator">
        <button class="generate-button">Generate Password</button>
        <div class="password-output" id="passwordOutput">Your password will appear here.</div>
    </div>
    <div class="back-button-container">
        <button type="button" onclick="window.location.href='<?php echo base_url(); ?>'">Back</button>
    </div>
    <script>
        $(document).ready(function() {
            $('.dark-mode-toggle').on('click', function() {
                $('body').toggleClass('dark-mode light-mode');

                if ($('body').hasClass('dark-mode')) {
                    $('.light-mode-icon').hide();
                    $('.dark-mode-icon').show();
                    localStorage.setItem('darkMode', 'true');
                } else {
                    $('.dark-mode-icon').hide();
                    $('.light-mode-icon').show();
                    localStorage.setItem('darkMode', 'false');
                }
            });

            if (localStorage.getItem('darkMode') === 'true') {
                $('body').removeClass('light-mode').addClass('dark-mode');
                $('.light-mode-icon').hide();
                $('.dark-mode-icon').show();
            } else {
                $('.dark-mode-icon').hide();
                $('.light-mode-icon').show();
            }

            $('.generate-button').on('click', function() {
                var password = generatePassword();
                $('#passwordOutput').text(password);
            });

            function generatePassword() {
                var length = 12;
                var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
                var password = "";
                for (var i = 0; i < length; i++) {
                    var randomIndex = Math.floor(Math.random() * charset.length);
                    password += charset[randomIndex];
                }
                return password;
            }
        });
    </script>
</body>
</html>
