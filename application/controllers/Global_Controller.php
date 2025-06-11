<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    public function get_global_section() {
        // $data = $this->Global_model->get_all_type_logs();
        $data['global_config'] = $this->Global_model->get_global_section("/etc/haproxy/haproxy.cfg");
        $this->load->view('template/nav-bar');
		$this->load->view('global/global_section',$data);
        $this->load->view('template/terminal.php');
    }
    public function add_section_global() {
        $log_path = $this->input->post('log');
        $name = $this->input->post('name');
        $level = $this->input->post('level');
    
        $haproxy_config_file = "/etc/haproxy/haproxy.cfg";

        $success = $this->Global_model->add_log_to_config($haproxy_config_file, $log_path, $name, $level);
    
        $this->Haproxy_reloader->reloadHaproxy();
        redirect(base_url('Global_Controller/get_global_section'));
    }
    public function delete_section_global() {
        $logLine = $this->input->post('log_to_delete');

        $haproxy_config_file = "/etc/haproxy/haproxy.cfg";
        $result = $this->Global_model->delete_global_log($haproxy_config_file, $logLine);

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }
        $this->Haproxy_reloader->reloadHaproxy();
        redirect(base_url('Global_Controller/get_global_section'));
    }
}
