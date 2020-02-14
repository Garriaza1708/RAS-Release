<?php
namespace App;

class MailParam
{
   //todos son configurables
   private $smtpAddress = 'smtp.gmail.com';
   private $port = 465;
   private $encryption = 'ssl';
   private $email = 'appnotasproyecto@gmail.com';//configurable
   private $password = 'administrador';//configurable

    public function getSmptAddress()
    {
    	return $this->smtpAddress;
    }

    public function getPort()
    {
    	return $this->port;
    }

    public function getEncryption()
    {
    	return $this->encryption;
    }

    public function getEmail()
    {
    	return $this->email;
    }

    public function getPassword()
    {
    	return $this->password;
    }
}
?>
