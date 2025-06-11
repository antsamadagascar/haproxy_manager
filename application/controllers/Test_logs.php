<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_logs extends CI_Controller {
    
    public function index() {
        $this->load->view('test_logs_view');
    }
    
    public function get_logs() {
        $command = 'docker logs haproxy-container 2>&1';
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
    }
}
?>