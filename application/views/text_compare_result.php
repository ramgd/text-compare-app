<!DOCTYPE html>
<html>
<head>
    <title>Text Compare Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .box {
            border: 1px solid black;
            padding: 10px;
            width: 45%;
            height: 80vh;
            overflow-y: auto;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
        .diff {
            background-color: lightgreen;
        }
        .line-number {
            color: gray;
            display: inline-block;
            width: 30px;
            user-select: none;
        }
        .line {
            display: flex;
        }
        .line-content {
            flex-grow: 1;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h1>Text Compare Result</h1>
    <div class="container">
        <div class="box">
            <h2>Text 1</h2>
            <div>
                <?php foreach ($result as $index => $line): ?>
                    <div class="line">
                        <span class="line-number"><?php echo $index + 1; ?></span>
                        <span class="line-content <?php echo $line['isDifferent'] ? 'diff' : ''; ?>">
                            <?php echo htmlspecialchars($line['line1']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="box">
            <h2>Text 2</h2>
            <div>
                <?php foreach ($result as $index => $line): ?>
                    <div class="line">
                        <span class="line-number"><?php echo $index + 1; ?></span>
                        <span class="line-content <?php echo $line['isDifferent'] ? 'diff' : ''; ?>">
                            <?php echo htmlspecialchars($line['line2']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <a href="<?php echo site_url('text_compare'); ?>">Compare Again</a>
</body>
</html>
