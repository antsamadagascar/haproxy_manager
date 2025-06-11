<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haproxy_reloader extends CI_Model {
    public function reloadHaproxy() {
        $command = 'sudo service haproxy reload';
        exec($command, $output, $return_var);
        
        if ($return_var !== 0) {
            throw new Exception('Le rechargement de HAProxy a échoué.');
            echo "HAProxy a été rechargé avec succès.";
        }
    }    
}
