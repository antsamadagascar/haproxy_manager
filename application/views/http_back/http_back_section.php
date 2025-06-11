<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAProxy Backend Configuration</title>
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
        /* Styles pour les formulaires côte à côte */
        .servers-form-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 20px;
        }
        .server-form {
            width: 48%;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">HAProxy Backend Configuration: <code>http_back</code></h1>
        <div class="form-container">
            <!-- Display Defaults Configuration -->
            <div class="config-display">
                <pre>
<?php
if (!empty($http_back_config)) {
    foreach ($http_back_config as $line) {
        echo htmlspecialchars($line) . "\n";
    }
} else {
    echo "No configuration found for backend http_back or an error occurred.";
}
?>
                </pre>
            </div>
            <form action="<?= base_url('Http_back_Controller/update_http_back_section') ?>" method="POST" class="form-group">
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
                    <label for="balance" class="form-label">Balance:</label>
                    <select id="balance" name="balance" class="form-control">
                        <?php if (!empty($algorithms)): ?>
                            <?php foreach ($algorithms as $algorithm): ?>
                                <option value="<?= htmlspecialchars($algorithm->nom) ?>">
                                    <?= htmlspecialchars($algorithm->nom) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No algorithms available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3 form-inline">
                    <label for="server" class="form-label">Server:</label>
                    <select id="server" name="server" class="form-select server-select" onchange="updateServerAddress()">
                        <option value="" data-address="">Select server</option>
                        <?php if (!empty($servers)): ?>
                            <?php foreach ($servers as $server): ?>
                                <option value="<?= htmlspecialchars($server['name']) ?>" data-address="<?= htmlspecialchars($server['address']) ?>">
                                    <?= htmlspecialchars($server['name'] . ' (' . $server['address'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No servers available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3 form-inline">
                    <label for="server-address" class="form-label">Selected Server Address:</label>
                    <input type="text" id="server-address" name="server-address" class="form-control">
                </div>
                <div class="mb-3 form-inline">
                    <label for="check" class="form-label">Check:</label>
                    <input type="checkbox" id="check" name="check" value="check">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

        <!-- Formulaires côte à côte pour ajouter et supprimer un serveur -->
        <h2 class="mt-4 mb-3">Gérer les serveurs</h2>
        <div class="servers-form-container">
            <!-- Formulaire pour ajouter un serveur -->
            <div class="server-form">
                <h3>Ajouter un serveur</h3>
                <form action="<?= base_url('Http_back_Controller/add_server') ?>" method="POST">
                    <div class="mb-3">
                        <label for="new-server-name" class="form-label">Nom du serveur:</label>
                        <input type="text" id="new-server-name" name="new-server-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-server-address" class="form-label">Adresse du serveur:</label>
                        <input type="text" id="new-server-address" name="new-server-address" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" id="check-add" name="check" value="check" class="form-check-input">
                        <label for="check-add" class="form-check-label">Check</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Ajouter le serveur</button>
                </form>
            </div>

            <!-- Formulaire pour supprimer un serveur -->
            <div class="server-form">
                <h3>Supprimer un serveur</h3>
                <form action="<?= base_url('Http_back_Controller/remove_server') ?>" method="POST">
                    <div class="mb-3">
                        <label for="server_to_delete" class="form-label">Sélectionner le serveur à supprimer:</label>
                        <select id="server_to_delete" name="server_to_delete" class="form-select" required>
                            <option value="">Choisir un serveur</option>
                            <?php if (!empty($servers)): ?>
                                <?php foreach ($servers as $server): ?>
                                    <option value="<?= htmlspecialchars($server['name']) ?>">
                                        <?= htmlspecialchars($server['name'] . ' (' . $server['address'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Aucun serveur disponible</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <!-- Espace pour aligner avec l'autre formulaire -->
                        <p class="text-muted">Cette action est irréversible et supprimera définitivement le serveur de la configuration.</p>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Supprimer le serveur</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateServerAddress() {
            const select = document.getElementById('server');
            const selectedOption = select.options[select.selectedIndex];
            const address = selectedOption.getAttribute('data-address') || '';
            document.getElementById('server-address').value = address;
        }

        // Initialize with the first server's address if available
        document.addEventListener('DOMContentLoaded', () => {
            updateServerAddress();
        });
    </script>
    <script src="<?= base_url('assets/js/terminal.js') ?>"></script>
</body>
</html>