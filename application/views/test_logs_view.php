
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <button onclick="refreshLogs()">Rafraîchir les logs</button>
    <div id="logsContainer"></div>
<?php
$logs = shell_exec($command);
        
if ($logs === null) {
    echo "Erreur: Impossible de récupérer les logs";
    return;
}

$logLines = explode("\n", $logs);
echo json_encode([
    'success' => true,
    'logs' => $logLines
]);
?>
</body>
</html>