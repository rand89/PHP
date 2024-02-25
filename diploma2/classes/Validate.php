<?php

class Validate {
    
    private $passed = false, $errors = [], $db = null;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function check($source, $items = []) {
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $rule_value) {
                $value = $source[$item];
                if($rule == 'required' && empty($value)) {
                    $this->addError("Поле {$item} обязательно к заполнению.");
                }
                elseif(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("Минимальная длина поля {$item} {$rule_value} символов.");
                            }
                        break;

                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("Максимальная длина поля {$item} {$rule_value} символов.");
                            }
                        break;

                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("Поля {$item} и {$rule_value} должны совпадать.");
                            }
                        break;

                        case 'unique':
                            $check = $this->db->get($rule_value, [$item, '=', $value]);
                            if($check->count()) {
                                $this->addError("Этот эл. адрес уже занят другим пользователем.");
                            }
                        break;

                        case 'email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} не является эл. почтой.");
                            }
                        break;
                    }
                }
            }
        }

        if(empty($this->errors)) {
            $this->passed = true;
        }
        return $this;
    }

    public function addError($error) {
        $this->errors[] = $error;
    }

    public function errors() {
        return $this->errors;
    }

    public function passed() {
        return $this->passed;
    }
}

?>