<!DOCTYPE html>
<html>
<head>
    <title>Upload Test</title>
</head>
<body>
    <h2>Upload Test</h2>
    
    <div>
        <h3>PHP Configuration:</h3>
        <p>Upload Max: <?php echo ini_get('upload_max_filesize'); ?></p>
        <p>Post Max: <?php echo ini_get('post_max_size'); ?></p>
        <p>Memory Limit: <?php echo ini_get('memory_limit'); ?></p>
        <p>Max Execution: <?php echo ini_get('max_execution_time'); ?></p>
    </div>

    <form action="test_upload.php" method="POST" enctype="multipart/form-data">
        <div>
            <label>Test File:</label>
            <input type="file" name="test_file" accept="video/*,image/*">
        </div>
        <div>
            <label>Title:</label>
            <input type="text" name="title" value="Test">
        </div>
        <button type="submit">Test Upload</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div style="background: #f0f0f0; padding: 10px; margin: 10px 0;">
            <h3>POST Data Received:</h3>
            <pre><?php print_r($_POST); ?></pre>
            
            <h3>FILES Data:</h3>
            <pre><?php print_r($_FILES); ?></pre>
            
            <h3>Content Length:</h3>
            <p><?php echo $_SERVER['CONTENT_LENGTH'] ?? 'Not set'; ?> bytes</p>
        </div>
    <?php endif; ?>
</body>
</html>