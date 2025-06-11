<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Protocole_model extends CI_Model {

    public function get_all_protocols() {
        $query = $this->db->get('protocoles');
        return $query->result();
    }
}
?>