<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(
           'login/index' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'PasswordConfirmation',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required'
                                         )
                                    )
               );
?>