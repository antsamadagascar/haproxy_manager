<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Algorithm_model extends CI_Model {

    public function get_all_algorithms() {
        $query = $this->db->get('algorithmes');
        return $query->result();
    }
}
?>