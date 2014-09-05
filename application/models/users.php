<?php
Class Users extends CI_Model
{
 function login($user_email, $user_password)
 {
   $this -> db -> select('user_id, user_active, user_email, user_fname, user_lname, user_password, acl_id');
   $this -> db -> from('users');
   $this -> db -> where('user_email', $user_email);
   $this -> db -> where('user_password', $user_password);
   $this -> db -> where('acl_id >=', 1);
   $this -> db -> where('user_active', 1);
   $this -> db -> limit(1);

   $query = $this -> db -> get();

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
}
?>
