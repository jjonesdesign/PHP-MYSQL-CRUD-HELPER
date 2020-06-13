<?php

class WithId extends BaseClass{

    public $entry_string;
    public $entry_int;
    

    public function __construct() {
        //Give parent class name of desired Database Table and flag if it uses UIDs or not.
        //We set this to false for this example to tell the helper this Class will use Auto Incremeneted ID's
        parent::__construct("with_id", false);
    }

    public static function withData($input) {
        $instance = new WithId();
        foreach ($input as $k => $v) {
            $instance->$k = $v;
        }
        return $instance;
    }
}

