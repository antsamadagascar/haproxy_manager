<!-- index.php -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Configuration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/terminal.css') ?>">
    <!-- <link href="<?= base_url('assets/css/accueil/global.css') ?>" rel="stylesheet"> -->
     <style>
        /* styles/main.css */
        
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

        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .container {
            margin-top: 50px;
        }
     </style>

</head>
<body>
    <div class="container">
        <h1 class="mb-4">HAProxy Global Configuration: <code>global</code></h1>

        <!-- Display Global Configuration -->
        <pre>
<?php
if (!empty($global_config)) {
    echo "global\n";
    foreach ($global_config as $line) {
        echo "    " . htmlspecialchars($line) . "\n";
    }
} else {
    echo "No configuration found or an error occurred.";
}
?>
        </pre>

        <!-- Buttons -->
        <button class="btn btn-success mb-3" id="addLogButton">
            <i class="bi bi-plus"></i> Add Log
        </button>

        <button class="btn btn-danger mb-3" id="deleteLogButton">
            <i class="bi bi-trash"></i> Delete Log
        </button>

        <!-- Forms -->
        <form id="addLogForm" action="<?= base_url('Global_Controller/add_section_global') ?>" method="POST" class="form-inline" style="display: none;">
            <input type="text" id="logInput" name="log" class="form-control" value="/dev/log" readonly>
            <input type="text" id="nameInput" name="name" class="form-control" readonly>
            <label for="logLevel" class="form-label">Level:</label>
            <select id="logLevel" name="level" class="form-select" required>
                <!-- Options dynamically loaded -->
            </select>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
        
        <form id="deleteLogForm" action="<?= base_url('Global_Controller/delete_section_global') ?>" method="POST" class="form-inline" style="display: none;">
            <label for="logToDelete" class="form-label">Select Log to Delete:</label>
            <select id="logToDelete" name="log_to_delete" class="form-select" required>
                <!-- Options dynamically loaded -->
            </select>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        
    </div>

    <!-- Scripts -->
    <script>
        const GLOBAL_CONFIG = <?= json_encode($global_config) ?>;
        const BASE_URL = '<?= base_url() ?>';
    </script>
<script src="<?= base_url('assets/js/accueil/global.js') ?>"></script>
<script src="<?= base_url('assets/js/terminal.js') ?>"></script>
</body>
</html>