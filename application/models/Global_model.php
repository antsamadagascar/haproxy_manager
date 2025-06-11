<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model {

    public function get_all_type_logs() {
        $query = $this->db->get('type_log');
        return $query->result();
    }
    public function get_global_section($file_path) {
        // Vérifier si le fichier existe
        if (!file_exists($file_path)) {
            return false;
        }

        $global_config = [];
        $is_global_section = false;

        // Lire le fichier ligne par ligne
        $file = fopen($file_path, 'r');
        while (($line = fgets($file)) !== false) {
            $trimmed_line = trim($line);

            // Détecter le début de la section 'global'
            if (strpos($trimmed_line, 'global') === 0) {
                $is_global_section = true;
                continue;
            }

            // Arrêter si une nouvelle section commence
            if ($is_global_section && preg_match('/^(defaults|frontend|backend|listen)\\b/', $trimmed_line)) {
                break;
            }

            // Ajouter les lignes de la section 'global'
            if ($is_global_section && !empty($trimmed_line)) {
                $global_config[] = $trimmed_line;
            }
        }
        fclose($file);

        return $global_config;
    }
    
    public function add_log_to_config($haproxy_config_file, $log_path, $name, $level) {
        // Lire le contenu original du fichier
        $original_content = file_get_contents($haproxy_config_file);
        if ($original_content === false) {
            return false;
        }
        
        // Détecter le type de saut de ligne utilisé (par défaut "\n")
        $newline = "\n";
        if (strpos($original_content, "\r\n") !== false) {
            $newline = "\r\n";
        }
        
        // Vérifier si le fichier se termine par un saut de ligne
        $ends_with_newline = (substr($original_content, -strlen($newline)) === $newline);
        
        // Découper le contenu en lignes
        $config_data = explode($newline, $original_content);
        
        // Si le fichier se termine par un saut de ligne, le dernier élément est vide : on le retire temporairement
        if ($ends_with_newline && end($config_data) === '') {
            array_pop($config_data);
        }
        
        // Préparer la nouvelle ligne à ajouter
        $new_line = "    log {$log_path} {$name} {$level}";
        
        // Identifier la section 'global'
        $global_start_index = array_search('global', $config_data);
        if ($global_start_index === false) {
            return false; // Section 'global' introuvable
        }
        
        // Rechercher la fin de la section 'global' (les lignes indentées)
        $global_end_index = $global_start_index + 1;
        while ($global_end_index < count($config_data) && preg_match('/^\s+/', $config_data[$global_end_index])) {
            $global_end_index++;
        }
        
        // Insérer la nouvelle ligne à la fin de la section 'global'
        array_splice($config_data, $global_end_index, 0, $new_line);
        
        // Reconstituer le contenu du fichier en préservant le même séparateur de ligne
        $new_content = implode($newline, $config_data);
        
        // Ajouter le saut de ligne final s'il était présent dans le fichier original
        if ($ends_with_newline) {
            $new_content .= $newline;
        }
        
        // Sauvegarder le nouveau contenu dans le fichier
        if (file_put_contents($haproxy_config_file, $new_content) !== false) {
            return true;
        } else {
            return false;
        }
    }
    

    public function delete_global_log($configFilePath, $logLine) {
        // Vérifier si le fichier existe
        if (!file_exists($configFilePath)) {
            return ['status' => false, 'message' => 'Configuration file not found.'];
        }
    
        // Lire le fichier en conservant tous les sauts de ligne
        $fileContent = file($configFilePath);
        if ($fileContent === false) {
            return ['status' => false, 'message' => 'Unable to read the configuration file.'];
        }
    
        // Variables de suivi
        $isInGlobalSection = false;
        $updatedContent = [];
        $found = false;
    
        foreach ($fileContent as $line) {
            $trimmedLine = trim($line);
    
            // Début de la section 'global'
            if ($trimmedLine === 'global') {
                $isInGlobalSection = true;
            }
    
            // Fin de la section 'global' (début d'une autre section)
            if ($isInGlobalSection && preg_match('/^(defaults|frontend|backend|listen)\b/', $trimmedLine)) {
                $isInGlobalSection = false;
            }
    
            // Supprimer uniquement la ligne ciblée dans la section 'global'
            if ($isInGlobalSection && $trimmedLine === trim($logLine)) {
                $found = true;
                continue; // Ignorer cette ligne (ne pas l'ajouter à $updatedContent)
            }
    
            // Ajouter toutes les autres lignes sans modification
            $updatedContent[] = $line;
        }
    
        if (!$found) {
            return ['status' => false, 'message' => 'Log line not found in the global section.'];
        }
    
        // S'assurer qu'il y a un saut de ligne à la fin
        if (substr(end($updatedContent), -1) !== "\n") {
            $updatedContent[] = "\n";
        }
    
        // Réécrire le fichier avec le contenu mis à jour
        $result = file_put_contents($configFilePath, implode('', $updatedContent));
    
        if ($result === false) {
            return ['status' => false, 'message' => 'Unable to write to the configuration file.'];
        }
    
        return ['status' => true, 'message' => 'Log line successfully deleted.'];
    }  
}
