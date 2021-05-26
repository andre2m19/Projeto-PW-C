<?php

class Finance extends \ActiveRecord\Model {
    static $validates_presence_of = array(

        array('valor', 'message' => 'YooaaH it must be provided'),
        array('descricao', 'message' => 'YooaaH it must be provided'),
        array('tipocategoria', 'message' => 'YooaaH it must be provided')
    );
}
