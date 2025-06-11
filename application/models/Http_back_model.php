<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Http_back_model extends CI_Model {
    public function get_http_back_section($file_path) {
        // Lire le fichier de configuration
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Extraire la section 'frontend http_front'
        $http_back_config = [];
        $in_http_back = false;

        foreach ($file_content as $line) {
            if (preg_match('/^backend http_back$/', $line)) {
                $in_http_back = true;
                $http_back_config[] = $line;
                continue;
            }

            if ($in_http_back) {
                if (preg_match('/^\S/', $line)) {
                    // Fin de la section détectée
                    break;
                }
                $http_back_config[] = $line;
            }
        }

        return $http_back_config;
    }
    public function get_servers($file_path) {
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $servers = [];

        // Extraire les serveurs de la section backend
        foreach ($file_content as $line) {
            if (preg_match('/^\s*server\s+(\S+)\s+(\S+)/', $line, $matches)) {
                $servers[] = [
                    'name' => $matches[1],  // Nom du serveur (e.g., server1)
                    'address' => $matches[2] // Adresse du serveur (e.g., host.docker.internal:8080)
                ];
            }
        }
        return $servers;
    }
    public function update_http_back_section($file_path, $form_data) {
        // Lire toutes les lignes en conservant les lignes vides
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES);
    
        $updated_content = [];
        $in_http_back = false;
        $mode_updated = false;    // S'assurer que `mode` est modifié/ajouté une seule fois
        $balance_updated = false; // S'assurer que `balance` est modifié/ajouté une seule fois
    
        foreach ($file_content as $line) {
            // Détecter le début de la section `http_back`
            if (trim($line) === 'backend http_back') {
                $in_http_back = true;
                $updated_content[] = $line; // Conserver la ligne "backend http_back"
                continue;
            }
    
            if ($in_http_back) {
                // Si une nouvelle section commence (ligne non indentée), on quitte la section
                if (preg_match('/^\S/', $line)) {
                    $in_http_back = false;
                } else {
                    // Mettre à jour le `mode` si ce n'est pas encore fait
                    if (!$mode_updated && preg_match('/^\s*mode\s+/', $line)) {
                        $line = "    mode " . $form_data['mode'];
                        $mode_updated = true;
                    }
    
                    // Mettre à jour le `balance` si ce n'est pas encore fait
                    if (!$balance_updated && preg_match('/^\s*balance\s+/', $line)) {
                        $line = "    balance " . $form_data['balance'];
                        $balance_updated = true;
                    }
    
                    // Modifier uniquement le serveur sélectionné
                    if (preg_match('/^\s*server\s+(\S+)\s+(\S+)/', $line, $matches)) {
                        $server_name = $matches[1];
                        if ($server_name === $form_data['server']) {
                            // On reconstruit la ligne avec les données fournies
                            $line = "    server " . $form_data['server'] . " " . $form_data['server-address'] . " " . $form_data['check'];
                        }
                    }
                }
            }
    
            $updated_content[] = $line;
        }
    
        // Si on est encore dans la section `http_back` (cas où elle est la dernière du fichier)
        // et que `mode` ou `balance` n'a pas été trouvé, les ajouter à la fin de la section
        if ($in_http_back && !$mode_updated) {
            $updated_content[] = "    mode " . $form_data['mode'];
        }
        if ($in_http_back && !$balance_updated) {
            $updated_content[] = "    balance " . $form_data['balance'];
        }
    
        // Concaténer les lignes en ajoutant un saut de ligne entre chaque et un saut de ligne final
        $final_content = implode(PHP_EOL, $updated_content) . PHP_EOL;
    
        file_put_contents($file_path, $final_content);
    }
    
    public function add_server($haproxy_config_file, $form_data) {
        // Préparer la ligne à ajouter
        $new_line = "    server {$form_data['new-server-name']} {$form_data['new-server-address']} {$form_data['check']}";

        // Lire le fichier de configuration existant
        $config_data = file($haproxy_config_file, FILE_IGNORE_NEW_LINES);

        // Identifier les lignes de la section 'http_back'
        $http_back_start_index = array_search('backend http_back', $config_data);
        if ($http_back_start_index !== false) {
            // Rechercher la fin de la section 'http_back'
            $http_back_end_index = $http_back_start_index + 1;
            while ($http_back_end_index <= count($config_data)-1 && preg_match('/^\s+/', $config_data[$http_back_end_index])) {
                $http_back_end_index++;
            }

            // Insérer la nouvelle ligne à la fin de la section 'http_back'
            array_splice($config_data, $http_back_end_index, 0, $new_line."\n");

            // Sauvegarder les modifications dans le fichier
            if (file_put_contents($haproxy_config_file, implode("\n", $config_data))) {
                return true; // Succès
            } else {
                return false; // Erreur de sauvegarde
            }
        } else {
            return false; // Section 'http_back' introuvable
        }
    }
    public function remove_server($haproxy_config_file, $server_name) {
        // Lire le fichier de configuration en un tableau de lignes
        $config_data = file($haproxy_config_file, FILE_IGNORE_NEW_LINES);
        
        // Rechercher la ligne de début de la section 'http_back'
        $http_back_start_index = array_search('backend http_back', $config_data);
        if ($http_back_start_index !== false) {
            // Déterminer la fin de la section : toutes les lignes indentées après 'backend http_back'
            $http_back_end_index = $http_back_start_index + 1;
            while ($http_back_end_index < count($config_data) && preg_match('/^\s+/', $config_data[$http_back_end_index])) {
                $http_back_end_index++;
            }
            
            // Parcourir la section 'http_back' pour trouver la ligne du serveur à supprimer
            $server_found = false;
            for ($i = $http_back_start_index + 1; $i < $http_back_end_index; $i++) {
                // La ligne doit commencer par "server" suivi du nom du serveur
                if (preg_match('/^\s+server\s+' . preg_quote($server_name, '/') . '\b/', $config_data[$i])) {
                    unset($config_data[$i]); // Supprimer la ligne correspondante
                    $server_found = true;
                    break;
                }
            }
            
            if ($server_found) {
                $config_data = array_values($config_data);
                // Sauvegarder les modifications dans le fichier de configuration
                if (file_put_contents($haproxy_config_file, implode("\n", $config_data))) {
                    return true; // Succès
                } else {
                    return false; // Erreur lors de la sauvegarde
                }
            } else {
                return false; // Le serveur n'a pas été trouvé dans la section 'http_back'
            }
        } else {
            return false; // La section 'http_back' est introuvable dans le fichier
        }
    }
}
