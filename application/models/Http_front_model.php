<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Http_front_model extends CI_Model {
    public function get_http_front_section($file_path) {
        // Lire le fichier de configuration
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Extraire la section 'frontend http_front'
        $http_front_config = [];
        $in_http_frontend = false;

        foreach ($file_content as $line) {
            if (preg_match('/^frontend http_front$/', $line)) {
                $in_http_frontend = true;
                $http_front_config[] = $line;
                continue;
            }

            if ($in_http_frontend) {
                if (preg_match('/^\S/', $line)) {
                    // Fin de la section détectée
                    break;
                }
                $http_front_config[] = $line;
            }
        }

        return $http_front_config;
    }
    public function update_http_front_section($file_path, $form_data) {
        // Lire le fichier en conservant toutes les lignes, y compris les vides
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES);
    
        $updated_content = [];
        $in_http_front = false;
    
        foreach ($file_content as $line) {
            // Détecter la ligne de début de la section "frontend http_front"
            if (preg_match('/^frontend\s+http_front\s*$/', $line)) {
                $in_http_front = true;
                // Conserver la ligne de titre
                $updated_content[] = $line;
                // Insérer les lignes mises à jour de la section
                $updated_content[] = "    mode " . $form_data['mode'];
                $updated_content[] = "    option " . $form_data['option'];
                $updated_content[] = "    bind *:" . $form_data['bind'];
                $updated_content[] = "    default_backend " . $form_data['default_backend'];
                continue;
            }
    
            if ($in_http_front) {
                // Si la ligne est vide, on la considère comme la fin de la section et on la conserve
                if (trim($line) === '') {
                    $in_http_front = false;
                    $updated_content[] = $line;
                    continue;
                }
                // Si la ligne est indentée (appartient à la section), on l'ignore (puisqu'elle est remplacée)
                if (preg_match('/^\s+/', $line)) {
                    continue;
                }
                // Sinon, on sort de la section (c'est le début d'une nouvelle section)
                $in_http_front = false;
            }
    
            // Conserver la ligne telle quelle
            $updated_content[] = $line;
        }
    
        // Recomposer le contenu en utilisant les sauts de ligne et ajouter un saut de ligne final s'il manque
        $result = implode(PHP_EOL, $updated_content);
        if (substr($result, -1) !== PHP_EOL) {
            $result .= PHP_EOL;
        }
    
        file_put_contents($file_path, $result);
    }        
}
