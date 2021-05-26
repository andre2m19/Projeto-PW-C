<?php


class user extends \ActiveRecord\Model
{
    static $validates_presence_of = array(
        array('nome'),
        array('morada'),
        array('nif'),
        array('telefone'),
        array('username'),
        array('password'),
        array('role')

    );
    static  $validates_size_of = array(
        array('nome', 'maximum' => 80),
        array('morada' , 'maximum' => 120),
        array('nif', 'is' => 9),
        array('telefone' , 'is' => 9),
        array('username' ,  'maximum ' =>120),
        array('password' , 'maximum' => 50),
        array('role' , 'maximum ' =>20)
    );

    static $validates_inclusion_of = array(
      array('email' , 'role' , 'is' => array ('administrador','passageiro','gestorvoo','operadorcheckin')
      )

    );
    static $validates_formates_of = array(
        array('email', 'with' => '/^[^0-9][A-z0-9_] + ([.][A-z0-9_]+[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/')
    );
    static  $validates_numercality_of = array(
      array('nif' ,  'only_integer' => true),
      array('telefone','only_integer' => true)
    );



}