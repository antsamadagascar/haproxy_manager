<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAProxy Defaults Configuration</title>
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
        <h1 class="mb-4">HAProxy Defaults Configuration: <code>defaults</code></h1>

        <div class="form-container">
            <!-- Display Defaults Configuration -->
            <div class="config-display">
                <pre>
<?php
    if (!empty($defaults_config)) {
        echo "defaults\n"; // Ajouter le titre de la section
        foreach ($defaults_config as $line) {
            echo "    " . htmlspecialchars($line) . "\n"; // Ajouter une indentation pour simuler le fichier
        }
    } else {
        echo "No configuration found or an error occurred.";
    }
?>
                </pre>
            </div>

            <!-- Form to Modify Defaults Configuration -->
            <form action="<?= base_url('Defaults_Controller/update_defaults') ?>" method="POST" class="form-group">
                <div class="mb-3 form-inline">
                <label for="timeout_connect" class="form-label">log </label>    
                <input type="text" id="log" name="log" class="form-control" 
                           value="<?= htmlspecialchars($defaults_config[0] ?? 'log global') ?>" readonly>
                </div>
                <div class="mb-3 form-inline">
                    <label for="option" class="form-label">option </label>
                    <select id="option" name="option" class="form-select">
                        <?php if (!empty($protocols)): ?>
                            <?php foreach ($protocols as $protocol): ?>
                                <option value="<?= htmlspecialchars($protocol->log) ?>" 
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
                    <label for="timeout_connect" class="form-label">timeout connect (ms):</label>
                    <input type="number" id="timeout_connect" name="timeout_connect" class="form-control" 
                           value="<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $defaults_config[4] ?? '5000')) ?>" required>
                </div>
                <div class="mb-3 form-inline">
                    <label for="timeout_client" class="form-label">timeout client (ms):</label>
                    <input type="number" id="timeout_client" name="timeout_client" class="form-control" 
                           value="<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $defaults_config[5] ?? '50000')) ?>" required>
                </div>
                <div class="mb-3 form-inline">
                    <label for="timeout_server" class="form-label">timeout server (ms):</label>
                    <input type="number" id="timeout_server" name="timeout_server" class="form-control" 
                           value="<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $defaults_config[6] ?? '50000')) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
    <script src="<?= base_url('assets/js/terminal.js') ?>"></script>
</body>
</html>
