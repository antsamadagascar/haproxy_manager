<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Defaults_model extends CI_Model {
    public function get_defaults_section($configFilePath) {
        $section_lines = [];
        $in_defaults_section = false;
        if (file_exists($configFilePath)) {
            $file = fopen($configFilePath, 'r');
            while (($line = fgets($file)) !== false) {
                $trimmed_line = trim($line);

                if (strpos($trimmed_line, 'defaults') === 0) {
                    $in_defaults_section = true;
                    continue;
                }
                if ($in_defaults_section) {
                    if (preg_match('/^(global|frontend|backend)/', $trimmed_line)) {
                        break;
                    }
                    if (!empty($trimmed_line)) {
                        $section_lines[] = $trimmed_line;
                    }
                }
            }
            fclose($file);
        }
        return $section_lines;
    }

    function update_defaults_section($file_path, $form_data) {
        $file_lines = file($file_path);
        if ($file_lines === false) {
            throw new Exception("Impossible de lire le fichier de configuration HAProxy.");
        }
    
        $updated_lines = [];
        $in_defaults_section = false;
    
        foreach ($file_lines as $line) {
            $newline = (substr($line, -2) === "\r\n") ? "\r\n" : "\n";
            $trimmed_line = rtrim($line, "\r\n");
    
            if (preg_match('/^defaults\s*$/', $trimmed_line)) {
                $in_defaults_section = true;
                $updated_lines[] = $trimmed_line . $newline;
                $updated_lines[] = "        " . $form_data['log'] . $newline;
                $updated_lines[] = "    option    " . $form_data['option'] . $newline;
                $updated_lines[] = "    timeout connect     " . $form_data['timeout_connect'] . "ms" . $newline;
                $updated_lines[] = "    timeout client      " . $form_data['timeout_client'] . "ms" . $newline;
                $updated_lines[] = "    timeout server      " . $form_data['timeout_server'] . "ms" . $newline ."\n";
                continue;
            }
    
            if ($in_defaults_section) {
                if (preg_match('/^\S/', $trimmed_line)) {
                    $in_defaults_section = false;
                } else {
                    continue;
                }
            }
    
            $updated_lines[] = $line;
        }
        $new_content = implode('', $updated_lines);
    
        $result = file_put_contents($file_path, $new_content);
        if ($result === false) {
            throw new Exception("Impossible d'écrire dans le fichier de configuration HAProxy.");
        }
    }              
}
