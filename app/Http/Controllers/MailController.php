<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\MailParam;
use App\Student;
use App\Course;

class MailController extends Controller
{
    public function enviarMail(Request $request)
    {
    	$param = new MailParam;
    	$alumnos = null;
        $transport = \Swift_SmtpTransport::newInstance($param->getSmptAddress(),
            $param->getPort(), $param->getEncryption())
                ->setUsername($param->getEmail())
                ->setPassword($param->getPassword());
        $mailer = \Swift_Mailer::newInstance($transport);

        if(isset($request->isTodos)){
            $curso = Course::find($request->ListaCursostbl8);

            foreach ($curso->students()->get() as $value){
                $alumnos[] = $value->email;
            }

        }else
            $alumnos = $request->Destinatarios;

        $message = (\Swift_Message::newInstance($request->Asunto))
		  ->setFrom(['appnotasproyecto@gmail.com' => 'Administrador'])
		  ->setTo($alumnos)
		  ->setBody($request->Mensaje);

		// Send the message
		if($mailer->send($message))
			return "Enviado";

		return "Error";


    }
}
