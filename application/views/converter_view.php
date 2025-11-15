<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOCX ↔ PDF Converter</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body.light-mode { background-color: white; color: black; }
        body.dark-mode { background-color: #202124; color: white; }

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
            cursor: pointer;
        }
        .light-mode .dark-mode-icon { display: none; }
        .dark-mode .light-mode-icon { display: none; }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        body.dark-mode .container { background: #2a2a2a; }

        input[type=file] {
            width: 100%;
            background: #fff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            color: #000;
        }
        body.dark-mode input[type=file] {
            background: #3a3a3a;
            border: 1px solid #555;
            color: #fff;
        }

        button {
            background-color: #0d5e66;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 16px;
        }
        button:hover { background-color: #0f7874; }

        .message { margin-top: 15px; color: red; }

        .download {
            background: #f39c12;
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 20px;
            display: inline-block;
        }
        .download:hover { background: #d68910; }

        .back-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .back-button-container button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button-container button:hover { background: #c0392b; }
    </style>
</head>

<body class="light-mode">

    <div class="header">
        <h1>DOCX ↔ PDF Converter</h1>
        <div class="dark-mode-toggle">
            <i class="fas fa-sun light-mode-icon"></i>
            <i class="fas fa-moon dark-mode-icon"></i>
        </div>
    </div>

    <div class="container">

        <!-- Corrected Form Action -->
        <form action="<?php echo base_url('ConverterController/convert'); ?>" method="post" enctype="multipart/form-data">
            <label for="file">Select DOCX or PDF file:</label>
            <input type="file" name="file" accept=".pdf,.docx" required>
            <button type="submit"><i class="fas fa-sync-alt"></i> Convert</button>
        </form>

        <!-- SHOW ERROR -->
        <?php if (!empty($error)): ?>
            <div class="message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- SHOW DOWNLOAD FILE AFTER SUCCESS -->
        <?php if (!empty($converted_file)): ?>
            <h3>Conversion Successful!</h3>
            <a href="<?php echo $converted_file; ?>" class="download" download>
                <i class="fas fa-download"></i> Download Converted File
            </a>
        <?php endif; ?>

        <div class="back-button-container">
            <button type="button" onclick="window.location.href='<?= base_url(); ?>'">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.dark-mode-toggle').on('click', function () {
                $('body').toggleClass('dark-mode light-mode');
                if ($('body').hasClass('dark-mode')) {
                    localStorage.setItem('darkMode', 'true');
                } else {
                    localStorage.setItem('darkMode', 'false');
                }
            });

            if (localStorage.getItem('darkMode') === 'true') {
                $('body').addClass('dark-mode').removeClass('light-mode');
            }
        });
    </script>

</body>
</html>
