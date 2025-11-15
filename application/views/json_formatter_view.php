<!DOCTYPE html>
<html>
<head>
    <title>JSON Formatter</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            position: relative;
            background-color: #f0f0f0;
            color: #333;
        }
        body.dark-mode {
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        .editor-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            background-color: #18bc9c;
            padding: 10px;
        }
        .editor {
            width: 41%;
            height: 430px;
            border: 1px solid #ccc;
        }
        body.dark-mode .editor {
            border: 1px solid #444;
        }
        button {
            padding: 10px 20px;
            background-color: #0d5e66;
            color: white;
            border: 1px solid #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #18bc9c;
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
            cursor: pointer;
/*    background: #3ca5a5;*/
background: linear-gradient(to right, #dfe3e0, #0b3e3e);
    padding: 3px;
    border: 1px solid #437d87;
    border-radius: 5px;
    margin: 10px;
    position: absolute;
    right: 0;
        }
        #beutyfybutton {
            position: absolute;
            left: 20px;
        }
        #beutyfybutton button {
            background-color: #0d5e66;
            color: white;
            border: 1px solid orange;
            padding: 10px 20px;
            cursor: pointer;
        }
        #beutyfybutton button:hover {
            background-color: #0f7874;
        }
        #beutyfyjsonbutton {
            position: absolute;
            bottom: 1px;
        }
        #beutyfyjsonbutton button {
            background-color: #0d5e66;
            color: white;
            border: 1px solid orange;
            padding: 10px 20px;
            cursor: pointer;
        }
        #beutyfyjsonbutton button:hover {
            background-color: #0f7874;
        }
        .button-group {
            display: flex;
            padding: 10px 20px;
            cursor: pointer;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .toast {
            visibility: hidden;
            max-width: 50%;
            margin: auto;
            background-color: #bb2124;
            color: white;
            border-radius: 20px;
            padding: 12px;
            position: fixed;
            z-index: 6;
            left: 50%;
            transform: translateX(-50%);
            bottom: 30px;
            font-size: 14px;
        }
        .toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }
        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @-webkit-keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
        .back-button-container {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{JSON Formatter}</h1>
        <i class="fas fa-adjust dark-mode-toggle"></i>
    </div>
    <div class="editor-container">
        <div class="editor" id="jsonInput" placeholder="Paste your JSON here"></div>
        <div>
            <div class="button-group">
                <button id="formatJson">Format JSON</button>
                <button id="minifyJson">Minify JSON</button>
                <button id="downloadJson">Download</button>
            </div>
        </div>
        <div class="editor" id="formattedJson" placeholder="Formatted JSON will appear here"></div>
    </div>
    <div class="back-button-container">
        <button type="button" onclick="window.location.href='<?php echo base_url(); ?>'">Back</button>
    </div>
    <div id="toast" class="toast">No data found. Thank you for using this software.</div>

    <script>
        $(document).ready(function() {
            
            if (localStorage.getItem('darkMode') === 'true') {
                $('body').addClass('dark-mode');
            }

            
            var editorInput = ace.edit("jsonInput");
            var editorOutput = ace.edit("formattedJson");

            
            [editorInput, editorOutput].forEach(editor => {
                editor.setTheme($('body').hasClass('dark-mode') ? "ace/theme/monokai" : "ace/theme/textmate");
                editor.session.setMode("ace/mode/json");
                editor.renderer.setShowGutter(true);
                editor.setOptions({
                    fontSize: "16px",
                    foldStyle: "markbegin",
                    displayIndentGuides: true,
                    useWorker: false,
                    wrap: true,
                    showPrintMargin: false
                });
                // editor.renderer.setScrollMargin(10, 10, 10, 10);
                editor.renderer.setVScrollBarAlwaysVisible(true);
                editor.renderer.setHScrollBarAlwaysVisible(false);
            });

            
            $('.dark-mode-toggle').on('click', function() {
                $('body').toggleClass('dark-mode');
                if ($('body').hasClass('dark-mode')) {
                    localStorage.setItem('darkMode', 'true');
                    [editorInput, editorOutput].forEach(editor => {
                        editor.setTheme("ace/theme/monokai");
                    });
                } else {
                    localStorage.setItem('darkMode', 'false');
                    [editorInput, editorOutput].forEach(editor => {
                        editor.setTheme("ace/theme/textmate");
                    });
                }
            });

            $('#formatJson').on('click', function() {
                var jsonInput = editorInput.getValue();
                if (jsonInput.trim() === "") {
                    showToast("No data found to format JSON.");
                } else {
                    try {
                        var jsonObject = JSON.parse(jsonInput);
                        var formattedJson = JSON.stringify(jsonObject, null, 4);
                        editorOutput.setValue(formattedJson, -1);
                    } catch (e) {
                        editorOutput.setValue('Invalid JSON: ' + e.message, -1);
                    }
                }
            });

            $('#minifyJson').on('click', function() {
                var jsonInput = editorInput.getValue();
                if (jsonInput.trim() === "") {
                    showToast("No data found to minify JSON.");
                } else {
                    try {
                        var jsonObject = JSON.parse(jsonInput);
                        var minifiedJson = JSON.stringify(jsonObject);
                        editorOutput.setValue(minifiedJson, -1);
                    } catch (e) {
                        editorOutput.setValue('Invalid JSON: ' + e.message, -1);
                    }
                }
            });

            $('#downloadJson').on('click', function() {
                var jsonContent = editorOutput.getValue();
                if (jsonContent.trim() === "") {
                    showToast("No data found. Thank you for using this software.");
                } else {
                    var blob = new Blob([jsonContent], { type: "application/json" });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'output.json';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }
            });

            function showToast(message) {
                var toast = $('#toast');
                toast.text(message);
                toast.addClass('show');
                setTimeout(function() {
                    toast.removeClass('show');
                }, 3000);
            }
        });
    </script>
</body>
</html>
