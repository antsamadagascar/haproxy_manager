<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Http_back_Controller extends CI_Controller {
    public function get_http_back_section() {
        $data['protocols'] = $this->Protocole_model->get_all_protocols();
        $data['algorithms'] = $this->Algorithm_model->get_all_algorithms();
        $data['http_back_config'] = $this->Http_back_model->get_http_back_section("/etc/haproxy/haproxy.cfg");
        $data['servers'] = $this->Http_back_model->get_servers("/etc/haproxy/haproxy.cfg");

        $this->load->view('template/nav-bar');
		$this->load->view('http_back/http_back_section',$data);
        $this->load->view('template/terminal.php');
    }
    public function update_http_back_section() {
        $form_data = $this->input->post();
    
        $haproxy_config_file = "/etc/haproxy/haproxy.cfg";

        $success = $this->Http_back_model->update_http_back_section($haproxy_config_file,$form_data);
        $this->Haproxy_reloader->reloadHaproxy();
        redirect(base_url('Http_back_Controller/get_http_back_section'));
    }
    public function add_server() {
        $form_data = $this->input->post();
    
        $haproxy_config_file = "/etc/haproxy/haproxy.cfg";

        $success = $this->Http_back_model->add_server($haproxy_config_file,$form_data);
        $this->Haproxy_reloader->reloadHaproxy();
        redirect(base_url('Http_back_Controller/get_http_back_section'));
    }
    // Méthode pour supprimer un serveur dans la section http_back
    public function remove_server() {
        // Récupérer le nom du serveur à supprimer envoyé par le formulaire
        $server_name = $this->input->post('server_to_delete');
        
        // Vérifier qu'un nom de serveur a bien été fourni
        if (empty($server_name)) {
            $this->session->set_flashdata('error', 'Aucun serveur sélectionné pour suppression.');
            redirect('Http_back_Controller');
        }
        
        // Définir le chemin du fichier de configuration HAProxy (à adapter selon votre environnement)
        $haproxy_config_file = '/etc/haproxy/haproxy.cfg';
        
        // Appeler la fonction remove_server du modèle pour supprimer la ligne correspondante
        $result = $this->Http_back_model->remove_server($haproxy_config_file, $server_name);
        
        // Préparer un message de feedback selon le résultat de l'opération
        if ($result) {
            $this->session->set_flashdata('success', 'Le serveur a été supprimé avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression du serveur.');
        }
        
        // Rediriger vers la page principale pour afficher la vue mise à jour
        redirect(base_url('Http_back_Controller/get_http_back_section'));
    }
}
