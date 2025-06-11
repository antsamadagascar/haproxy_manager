<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Http_front_Controller extends CI_Controller {
    public function get_http_front_section() {
        // $data = $this->Global_model->get_all_type_logs();
        $data['protocols'] = $this->Protocole_model->get_all_protocols();
        $data['http_front_config'] = $this->Http_front_model->get_http_front_section("/etc/haproxy/haproxy.cfg");

        $this->load->view('template/nav-bar');
		$this->load->view('http_front/http_front_section',$data);
        $this->load->view('template/terminal.php');
    }
    public function update_http_front_section() {
        $form_data = $this->input->post();
    
        $haproxy_config_file = "/etc/haproxy/haproxy.cfg";

        $success = $this->Http_front_model->update_http_front_section($haproxy_config_file,$form_data);
        $this->Haproxy_reloader->reloadHaproxy();
        redirect(base_url('Http_front_Controller/get_http_front_section'));
    }
}
