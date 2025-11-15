<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Query Minifier</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
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
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        textarea {
            width: 97%;
            height: 150px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            resize: none;
            font-family: monospace;
            font-size: 14px;
        }
        button {
            background-color: #0d5e66;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0f7874;
        }
        .output {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-family: monospace;
            white-space: pre-wrap;
            position: relative;
            text-align: center;
            color: inherit; /* Adjust color for dark mode */
        }
        .output.dark-mode {
            background: #2a2a2a;
            border: 1px solid #555;
        }
        .copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 12px;
        }
        .copy-button:hover {
            background-color: #0056b3;
        }
        .back-button-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body class="light-mode">
    <div class="header">
        <h1>SQL Query Minifier</h1>
        <div class="dark-mode-toggle">
            <i class="fas fa-sun light-mode-icon"></i>
            <i class="fas fa-moon dark-mode-icon"></i>
        </div>
    </div>

    <div class="container">
        <label for="sql-input">Enter SQL Query:</label>
        <textarea id="sql-input" placeholder="Paste your SQL query here..."></textarea>
        <button id="minify-btn">Minify Query</button>
        <div class="output" id="output-container" style="display: none;">
            <button class="copy-button" id="copy-btn">Copy Query</button>
            <strong>Minified Query:</strong>
            <div id="minified-query"></div>
        </div>
        <!-- Back Button -->
        <div class="back-button-container">
            <button type="button" onclick="window.location.href='<?= base_url(); ?>'">Back</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            toastr.options = {
            "positionClass": "toast-bottom-right", // Display messages in bottom-right
            "closeButton": true,
            "progressBar": true,
            "timeOut": "3000", // 3 seconds
            "extendedTimeOut": "1000",
        };
            // Dark/Light Mode Toggle
            $('.dark-mode-toggle').on('click', function () {
                $('body').toggleClass('dark-mode light-mode');
                $('.output').toggleClass('dark-mode');

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
                $('body').addClass('dark-mode');
                $('.output').addClass('dark-mode');
                $('.light-mode-icon').hide();
                $('.dark-mode-icon').show();
            }

            // Minify SQL Query
            $('#minify-btn').on('click', function () {
                const sqlInput = $('#sql-input').val();

                if (sqlInput.trim() === '') {
                    toastr.error('Please enter an SQL query.');
                    return;
                }

                const minifiedQuery = sqlInput
                    .replace(/(--[^\n]*)|((\/\*[\s\S]*?\*\/))/g, '') // Remove comments
                    .replace(/\s+/g, ' ') // Replace multiple spaces/newlines with single space
                    .trim();

                $('#minified-query').text(minifiedQuery);
                $('#output-container').show();
                toastr.success('SQL query has been minified successfully!');
            });

            // Copy Query
            $('#copy-btn').on('click', function () {
                const minifiedQuery = $('#minified-query').text();

                if (!minifiedQuery) {
                    toastr.error('Nothing to copy!');
                    return;
                }

                navigator.clipboard.writeText(minifiedQuery).then(function () {
                    toastr.success('Query copied successfully!');
                }).catch(function () {
                    toastr.error('Failed to copy query.');
                });
            });
        });
    </script>
</body>
</html>
