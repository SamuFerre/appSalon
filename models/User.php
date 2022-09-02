<?php

namespace Model;

class User extends ActiveRecord{
    // DB
    protected static $tabla = 'users';
    protected static $columnasDB = ['id', 'name', 'surname', 'email', 'password', 'phone', 'admin', 'confirmed', 'token'];

    public $id;
    public $name;
    public $surname;
    public $email;
    public $password;
    public $phone;
    public $admin;
    public $confirmed;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->surname = $args['surname'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->phone = $args['phone'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmed = $args['confirmed'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //Mensajes de validacion para la creacion de la cuenta
    public function validarNuevaCuenta()    {
        if(!$this->name) {
            self::$alertas['error'][] = 'The Name cannot be empty';
        }
        if(!$this->surname) {
            self::$alertas['error'][] = 'The Surname cannot be empty';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'The Email cannot be empty';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'The Password cannot be empty';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'The Password must have at least 6 characters';
        }
        if(!$this->phone) {
            self::$alertas['error'][] = 'The Phone cannot be empty';
        }
        if(!strlen($this->phone) === 10) {
            self::$alertas['error'][] = 'The Phone must have at least 10 characters';
        }
        return self::$alertas;
    }

    //Validar Login
    public function validarLogin()
    {
        if(!$this->email) {
            self::$alertas['error'][] = 'The email is Obligatory';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'The password is Obligatory';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'The email is Obligatory';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'The Password is obligatory';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'The password must have at least 6 characters';
        }
        return self::$alertas;
    }

    //Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'This email is already registered';
        }

        return $resultado;
    }

    // Hashear la password
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    // Crear Token
    public function crearToken(){
        $this->token = uniqid();
    }

    // Comprobar password y verificarlo
    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmed) {
            self::$alertas['error'][] = 'The passsword is not correct or this account was not confirmed';
        } else {
            return true;
        }
    }

}