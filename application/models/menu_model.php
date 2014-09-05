<?php
class menu_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
	public function get_menu($acl = 0)
	{
        $sql = 'SELECT menu.order, menu.id, menu.parent_id, menu.menu_id, menu.name, menu.link, menu.position, menu.acl_id FROM menu WHERE acl_id <= ? AND menu_id=1 ORDER BY menu.order ASC';
        $this->db->cache_on();
        $query = $this->db->query($sql,array($acl));
        return $query->result_array();
	}
	public function get_admin_menu($acl = 0)
	{
        $sql = 'SELECT menu.order, menu.id, menu.parent_id, menu.menu_id, menu.name, menu.link, menu.position, menu.acl_id FROM menu WHERE acl_id<= ? AND menu_id=2 ORDER BY menu.order ASC';
        $this->db->cache_on();
        $query = $this->db->query($sql,array($acl));
        return $query->result_array();
	}
}