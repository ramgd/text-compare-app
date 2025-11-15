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
        /* Loader CSS */
        .loader {
            border: 10px solid #f3f3f3; /* Light grey */
            border-top: 10px solid #15857a; /* Blue */
            border-radius: 50%;
            width: 0px;
            height: 0px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #chatbot {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}
#chatbot-icon {
    background-color: #0d5e66;
    color: white;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
}
#chatbot-window {
    display: none;
    position: absolute;
    bottom: 50px;
    right: 0;
    width: 300px;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
#chatbot-header {
    background-color: #10706b;
    color: white;
    padding: 10px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
#chatbot-body {
    padding: 10px;
}
#chatbot-messages {
    height: 200px;
    overflow-y: auto;
    margin-bottom: 10px;
    background-color: #f9f9f9;
    padding: 10px;
    border-radius: 5px;
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
}
#chatbot-input {
    width: 75%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
#send-chatbot-message {
    padding: 6px 12px;
    background-color: #0d5e66;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
#close-chatbot {
    cursor: pointer;
}

    </style>
</head>
<body class="light-mode">
    <div class="header">
        <h1>Text Compare Tool</h1>
        <div class="dark-mode-toggle">
<!--             <label for="darkModeToggle">
                <i id="modeIcon" class="fas fa-moon"></i> Dark Mode
            </label>
            <input type="checkbox" id="darkModeToggle"> -->
            <i class="fas fa-sun light-mode-icon"></i>
            <i class="fas fa-moon dark-mode-icon"></i>
        </div>
    </div>
    <div class="container">
            <form id="compareForm">
                <button type="submit"><i class="fas fa-balance-scale"></i> Compare</button>
               <button type="button" id="switchText"><i class="fas fa-exchange-alt"></i> Switch Text</button>
                <button type="button" id="jsonFormatter" onclick="window.location.href='<?php echo base_url('json_formatter'); ?>'"><i class="fas fa-code"></i>JSON Formatter</button>
                <button type="button" id="passwordGenerator" onclick="window.location.href='<?php echo base_url('passwordGenerator'); ?>'"><i class="fas fa-key"></i> Password Generator</button>
                <button type="button" id="sqlQueryMinifier" onclick="window.location.href='<?php echo base_url('sqlQueryMinifier'); ?>'"><i class="fas fa-database"></i> SQL Query Minifier</button>
                <button type="button" id="docxPdfConverter" 
                onclick="window.location.href='<?php echo base_url('docxPdfConverter'); ?>'">
                <i class="fa fa-file-pdf"></i> Docx Pdf Converter
                </button>
                <button type="button" id="imageCompressor" 
                onclick="window.location.href='<?php echo base_url('image-compressor'); ?>'">
                <i class="fa fa-file-pdf"></i> Image Compressor
                </button>
                <button type="button" id="image-to-pdf" 
                onclick="window.location.href='<?php echo base_url('image-to-pdf'); ?>'">
                <i class="fa fa-file-pdf"></i> Image Pdf Maker
                </button>

            </form>
        <div class="form-container">
            <form id="compareForm">
                <div class="textarea-container">
                    <textarea id="text1" placeholder="Enter first text"></textarea>
                    <textarea id="text2" placeholder="Enter second text"></textarea>
                </div>
                <!-- <button type="submit">Compare</button> -->
            </form>
        </div>
        <button type="button" id="clearAll">Clear All</button>
        <h2 >Comparison Result</h2>
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
    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="loader"></div>
    </div>
    <script>
        $(document).ready(function() {
            // Toggle dark mode
 $('.dark-mode-toggle').on('click', function() {
        $('body').toggleClass('dark-mode light-mode');

        // Toggle the icons based on the mode
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
            if (localStorage.getItem('darkMode') === 'true') {
                $('body').removeClass('light-mode').addClass('dark-mode');
                $('#darkModeToggle').prop('checked', true);
                $('#modeIcon').removeClass('fa-moon').addClass('fa-sun');
            }
            $('#darkModeToggle').on('change', function() {
                if (this.checked) {
                    $('body').removeClass('light-mode').addClass('dark-mode');
                    localStorage.setItem('darkMode', 'true');
                     $('#modeIcon').removeClass('fa-moon').addClass('fa-sun');
                } else {
                    $('body').removeClass('dark-mode').addClass('light-mode');
                    localStorage.setItem('darkMode', 'false');
                    $('#modeIcon').removeClass('fa-sun').addClass('fa-moon');
                }
            });

            var editor1 = CodeMirror.fromTextArea(document.getElementById('text1'), {
                lineNumbers: true,
                mode: 'javascript',
                lineWrapping: true // Enable line wrapping
            });

            var editor2 = CodeMirror.fromTextArea(document.getElementById('text2'), {
                lineNumbers: true,
                mode: 'javascript',
                lineWrapping: true // Enable line wrapping
            });
            $('#chatbot-icon').on('click', function() {
            $('#chatbot-window').toggle();
        });

    // Close chatbot window
    $('#close-chatbot').on('click', function() {
        $('#chatbot-window').hide();
    });

    // Handle sending a message
$('#send-chatbot-message').on('click', function() {
    var message = $('#chatbot-input').val().trim();
    if (message) {
        $('#chatbot-messages').append('<div><strong>You:</strong> ' + message + '</div>');
        $('#chatbot-input').val('');
        $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);

        var response = getChatbotResponse(message);
        setTimeout(function() {
            $('#chatbot-messages').append('<div><strong>Chatbot:</strong> ' + response + '</div>');
            $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);
        }, 1000);
    }
});
    $('#chatbot-input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $('#send-chatbot-message').click();
        }
    });
// function getChatbotResponse(message) {
//     // Example responses based on keywords
//     if (message.toLowerCase().includes('hello')) {
//         return 'Hi there! How can I help you today?';
//     } else if (message.toLowerCase().includes('help')) {
//         return 'Sure, I am here to assist you! What do you need help with?';
//     } else if (message.toLowerCase().includes('compare')) {
//         return 'The Text Compare tool can help you find differences between two texts. Just input your texts and click Compare.';
//     } else {
//         return 'Sorry, I am not sure how to respond to that. I am still learning!';
//     }
// }



function getChatbotResponse(message) {
    if (message.toLowerCase().includes('hello')) {
        return 'Hi there! How can I help you today?';
    } else if (message.toLowerCase().includes('help')) {
        return 'Sure, I am here to assist you! What do you need help with?';
    } else if (message.toLowerCase().includes('compare')) {
        return 'The Text Compare tool can help you find differences between two texts. Just input your texts and click Compare.';
    } else if (message.toLowerCase().includes('father')) {
        return 'I don\'t have a father in the traditional sense since I\'m an AI created by a team of RDX engineers, researchers, and data scientists at Ramsey. You could say that Ramsey is my creator or "parent," as they developed the models and technology that allow me to function and interact with you.';
    } else if (message.toLowerCase().includes('developed')) {
        return 'Mr. Ramji Dwivedi';
    }
    else if (message.toLowerCase().includes('how are you')) {
        return 'I am fine and you ?';
    }

    else if (message.toLowerCase().includes('fine')) {
    return 'Okay';
    } 
    else if (message.toLowerCase().includes('who are you')) {
    return 'I am Chat-Bot.';
    }
    else if (message.toLowerCase().includes('php')) {
    return 'php is programming language.';
    }

    else {
        return 'Sorry, I am not sure how to respond to that. I am still learning!';
    }
}


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
                // Show loader
                $('.loading-overlay').show();
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

                        // Highlight differences
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
                        // Hide loader
                        $('.loading-overlay').hide();
                        // Scroll to the resultContainer
                        $('html, body').animate({
                            scrollTop: $('#resultContainer').offset().top
                        }, 500);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error: ' + textStatus + ' - ' + errorThrown);
                        // Hide loader in case of error
                        $('.loading-overlay').hide();
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
    <div id="chatbot">
    <div id="chatbot-icon">
        <i class="fas fa-robot"></i>
    </div>
    <div id="chatbot-window">
        <div id="chatbot-header">
            <h3>Chatbot</h3>
            <span id="close-chatbot"class="fas fa-times"></span>
        </div>
        <div id="chatbot-body">
            <div id="chatbot-messages"></div>
            <input type="text" id="chatbot-input" placeholder="Type a message..." />
            <button id="send-chatbot-message">Send</button>
        </div>
    </div>
</div>

</body>
</html>