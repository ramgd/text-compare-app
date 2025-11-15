<!DOCTYPE html>
<html>
<head>
    <title>Text Compare App</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"></script>
    <style>
        body.light-mode {
            background-color: white;
            color: black;
        }
        body.dark-mode {
            background-color: #202124;
            color: white;
        }
        .diff {
            background-color: #a9d0f5;
        }
        .dark-mode .diff {
            background-color: #396a93;
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
            position: absolute;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dark-mode-icon {
            display: none;
        }
        .light-mode .dark-mode-icon {
            display: block;
        }
        .dark-mode .dark-mode-icon {
            display: block;
        }
        .dark-mode .light-mode-icon {
            display: none;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .form-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px; /* Add some margin to separate from header */
        }
        .textarea-container {
            display: flex;
            gap: 20px;
        }
        .result-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .box {
            border: 1px solid black;
            padding: 10px;
            width: 600px;
            height: auto;
            overflow-y: auto;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
        .box1, .box2 {
            border: 1px solid black;
            padding: 10px;
            width: 600px;
            height: auto;
            overflow-y: auto;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            background-color: white; /* Ensure background color matches text areas */
        }
        .dark-mode .box, .dark-mode .box1, .dark-mode .box2 {
            border: 1px solid white;
            background-color: #303136;
        }
        #compareForm button {
            background-color: #0d5e66;
            color: white;
            border: 1;
            border-color: orange;
            padding: 10px 20px;
            cursor: pointer;
        }
        #compareForm button:hover {
            background-color: #0f7874; /* lighter shade of green on hover */
        }
        #clearAll {
            background-color: #224f7e;
            color: white;
            border: 1;
            border-color: orange;
            padding: 10px 20px;
            cursor: pointer;
        }
        #clearAll:hover {
            background-color: #a90d0d; /* lighter shade of green on hover */
        }
        .CodeMirror {
            border: 1px solid black;
            border-color: orange;
            width: 600px;
            height: 300px;
        }
        .dark-mode .CodeMirror {
            background-color: #303136;
            color: white;
        }
        /* Toaster CSS */
        .toaster {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #8ed99f;
            color: black;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body class="light-mode">
    <div class="header">
        <h1>Text Compare App</h1>
        <div class="dark-mode-toggle">
            <label for="darkModeToggle">
                <i id="modeIcon" class="fas fa-moon"></i> Dark Mode
            </label>
            <input type="checkbox" id="darkModeToggle">
        </div>
    </div>
</body>
</html>


//code


<?php include 'header.php'; ?>

<div class="container">
    <form id="compareForm">
        <button type="submit"><i class="fas fa-balance-scale"></i> Compare</button>
        <button type="button" id="switchText"><i class="fas fa-exchange-alt"></i> Switch Text</button>
        <button type="button" id="jsonFormatter" onclick="window.location.href='<?php echo base_url('text_compare/json_formatter'); ?>'"><i class="fas fa-code"></i>JSON Formatter</button>
    </form>
    <div class="form-container">
        <form id="compareForm">
            <div class="textarea-container">
                <textarea id="text1" placeholder="Enter first text"></textarea>
                <textarea id="text2" placeholder="Enter second text"></textarea>
            </div>
        </form>
    </div>
    <button type="button" id="clearAll">Clear All</button>
    <h2>Comparison Result</h2>
    <div id="resultContainer" class="result-container" style="display: none;">
        <div>
            <h3>Text 1 (<span id="text1Length"></span> characters)</h3>
            <div style="border-color: orange;" id="text1Result" class="box1"></div>
        </div>
        <div>
            <h3>Text 2 (<span id="text2Length"></span> characters)</h3>
            <div style="border-color: orange;" id="text2Result" class="box2"></div>
        </div>
    </div>
</div>

<!-- Toaster Notification -->
<div id="toaster" class="toaster"></div>

<script>
    $(document).ready(function() {
        if (localStorage.getItem('darkMode') === 'true') {
            $('body').removeClass('light-mode').addClass('dark-mode');
            $('#darkModeToggle').prop('checked', true);
            $('#modeIcon').removeClass('fa-moon').addClass('fa-sun'); // Change to sun icon
        }
        $('#darkModeToggle').on('change', function() {
            if (this.checked) {
                $('body').removeClass('light-mode').addClass('dark-mode');
                localStorage.setItem('darkMode', 'true');
                $('#modeIcon').removeClass('fa-moon').addClass('fa-sun'); // Change to sun icon
            } else {
                $('body').removeClass('dark-mode').addClass('light-mode');
                localStorage.setItem('darkMode', 'false');
                $('#modeIcon').removeClass('fa-sun').addClass('fa-moon'); // Change to moon icon
            }
        });

        var editor1 = CodeMirror.fromTextArea(document.getElementById('text1'), {
            lineNumbers: true,
            mode: 'javascript'
        });

        var editor2 = CodeMirror.fromTextArea(document.getElementById('text2'), {
            lineNumbers: true,
            mode: 'javascript'
        });

        $('#compareForm').on('submit', function(e) {
            e.preventDefault();

            var text1 = editor1.getValue();
            var text2 = editor2.getValue();

            if (!text1.trim() || !text2.trim()) {
                showToast("No text found to compare! I am too glad you use this tool!");
                $('#resultContainer').hide();
                return;
            }
            if (text1 === text2) {
                showToast("Both texts are identical! I am happy you use this software!");
                $('#resultContainer').hide();
                return;
            }

            $.ajax({
                url: '<?php echo base_url('text_compare/compare'); ?>',
                type: 'POST',
                data: {
                    text1: text1,
                    text2: text2
                },
                success: function(response) {
                    var differences = JSON.parse(response);
                    var text1Result = '';
                    var text2Result = '';

                    for (var i = 0; i < text1.length || i < text2.length; i++) {
                        var char1 = i < text1.length ? text1[i] : '';
                        var char2 = i < text2.length ? text2[i] : '';

                        if (differences.some(diff => diff.index === i)) {
                            text1Result += '<span class="diff">' + htmlspecialchars(char1) + '</span>';
                            text2Result += '<span class="diff">' + htmlspecialchars(char2) + '</span>';
                        } else {
                            text1Result += htmlspecialchars(char1);
                            text2Result += htmlspecialchars(char2);
                        }
                    }

                    $('#text1Result').html(text1Result);
                    $('#text2Result').html(text2Result);
                    $('#text1Length').text(text1.length);
                    $('#text2Length').text(text2.length);
                    $('#resultContainer').show();

                    $('html, body').animate({
                        scrollTop: $('#resultContainer').offset().top
                    }, 500);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });

        $('#switchText').on('click', function() {
            var temp = editor1.getValue();
            editor1.setValue(editor2.getValue());
            editor2.setValue(temp);
        });

        $('#clearAll').on('click', function() {
            editor1.setValue('');
            editor2.setValue('');
            $('#text1Result').html('');
            $('#text2Result').html('');
            $('#text1Length').text('');
            $('#text2Length').text('');
            $('#resultContainer').hide();
        });

        function showToast(message) {
            var $toaster = $('#toaster');
            $toaster.text(message).fadeIn(400).delay(3000).fadeOut(400);
        }
    });

    function htmlspecialchars(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>