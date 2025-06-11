<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Defaults_Controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    public function index() {
        $this->load->view('template/nav-bar.php');
        $this->load->view('defaults/defaults_section');
		$this->load->view('template/terminal.php');
    }

	public function get_defaults_section() {
		$haproxy_config_file = "/etc/haproxy/haproxy.cfg";
        $data['defaults_config'] = $this->Defaults_model->get_defaults_section($haproxy_config_file);
		$data['protocols'] = $this->Protocole_model->get_all_protocols();
		
		$this->load->view('template/nav-bar.php');
		$this->load->view('template/terminal.php');
        $this->load->view('defaults/defaults_section',$data);
    }
	
	public function update_defaults() {
		$filePath = '/etc/haproxy/haproxy.cfg'; // Chemin du fichier de configuration
		// $formData = $this->input->post(); // Récupération des données du formulaire
		$form_data = [
			'log' => $_POST['log'] ?? 'global',
			'option' => $_POST['option'] ?? 'httplog',
			'timeout_connect' => $_POST['timeout_connect'] ?? '5000',
			'timeout_client' => $_POST['timeout_client'] ?? '50000',
			'timeout_server' => $_POST['timeout_server'] ?? '50000',
		];
		try {
			$this->Defaults_model->update_defaults_section($filePath, $form_data);
			$this->Haproxy_reloader->reloadHaproxy();
			redirect(base_url('Defaults_Controller/get_defaults_section'));
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}
}
