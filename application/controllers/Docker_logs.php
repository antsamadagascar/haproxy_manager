<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docker_logs extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_logs() {
        try {
            $logFile = '/var/log/haproxy.log';

            // Vérifier si le fichier existe et est lisible
            if (file_exists($logFile)) {
                $data['logs'] = file_get_contents($logFile);
            } else {
                $data['logs'] = "Fichier de '/var/log/haproxy.log' introuvable ou inaccessible.";
            }

            // Convertir le contenu du log en tableau de lignes
            $logLines = array_filter(explode("\n", $data['logs']));
            
            // Limiter aux 100 dernières lignes pour éviter une surcharge
            $logLines = array_slice($logLines, -100);

            $response = [
                'success' => true,
                'logs'    => $logLines
            ];
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }

        // Renvoyer la réponse en JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
