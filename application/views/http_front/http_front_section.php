<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAProxy Frontend Configuration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/terminal.css') ?>">
    <style>
        .nav-link {
            font-size: 1.1rem;
        }
        .navbar-nav {
            margin: 0 auto;
        }
        body {
            padding-top: 56px;
            padding: 20px;
            font-family: monospace;
            background-color: #f8f9fa;
        }
        pre {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            overflow-x: auto;
        }
        .container {
            margin-top: 50px;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .config-display {
            width: 48%;
        }
        .form-group {
            width: 48%;
        }
        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">HAProxy Frontend Configuration: <code>http_front</code></h1>
        <div class="form-container">
            <!-- Display Defaults Configuration -->
            <div class="config-display">
                <pre>
<?php
if (!empty($http_front_config)) {
    foreach ($http_front_config as $line) {
        echo htmlspecialchars($line) . "\n";
    }
} else {
    echo "No configuration found for frontend http_front or an error occurred.";
}
?>
                </pre>
            </div>
        
            <form action="<?= base_url('Http_front_Controller/update_http_front_section') ?>" method="POST" class="form-group">
                <div class="mb-3 form-inline">
                    <label for="mode" class="form-label">Mode:</label>
                    <select id="mode" name="mode" class="form-control">
                        <?php if (!empty($protocols)): ?>
                            <?php foreach ($protocols as $protocol): ?>
                                <option value="<?= htmlspecialchars($protocol->nom_protocole) ?>" 
                                    data-log="<?= htmlspecialchars($protocol->log) ?>"
                                    <?= isset($defaults_config[1]) && trim($defaults_config[1]) == $protocol->log ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($protocol->nom_protocole) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No protocols available</option>
                        <?php endif; ?>
                    </select>
                </div>

                    <div class="mb-3 form-inline">
                        <label for="option" class="form-label">option </label>
                        <input type="text" id="option" name="option" class="form-control" 
                            value="<?= htmlspecialchars($protocol->log ?? '') ?>" readonly>
                    </div>

                <div class="mb-3 form-inline">
                    <label for="bind" class="form-label">Bind:</label>
                    <input type="text" id="bind" name="bind" class="form-control" 
                        value="<?= htmlspecialchars($http_frontend_config['bind'] ?? '80') ?>" required>
                </div>

                <div class="mb-3 form-inline">
                    <label for="default_backend" class="form-label">Default Backend:</label>
                    <input type="text" id="default_backend" name="default_backend" class="form-control" 
                        value="<?= htmlspecialchars($http_frontend_config['default_backend'] ?? 'http_back') ?>" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
        <script>
            document.getElementById('mode').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                document.getElementById('option').value = selectedOption.dataset.log;
            });

            // Pour initialiser la valeur au chargement de la page
            const selectedOption = document.getElementById('mode').options[document.getElementById('mode').selectedIndex];
            document.getElementById('option').value = selectedOption.dataset.log;
        </script>
        <script src="<?= base_url('assets/js/terminal.js') ?>"></script>
</body>
</html>

