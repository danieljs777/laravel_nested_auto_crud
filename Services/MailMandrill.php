<?php

namespace App\Services;

use Mail;
use \Exception;
use \RuntimeException;

class MailMandrill
{

    // Config Mandrill
    private $template;
    private $vars;
    private $options;
    private $assunto;
    private $attachs = [];
    // User Send
    private $to;
    private $name;
    private $cc      = [];
    // $errors
    private $errors  = [];

    public function __construct(string $template, array $vars = [], array $options = [])
    {
        // Config Mandrill
        $this->template = $template;
        $this->vars     = $vars;
        $this->options  = $options;
    }

    public function attach($file)
    {
        $this->attachs[] = $file;
        return $this;
    }

    // add option
    public function addOption(string $key, $value)
    {
        $this->options[$key] = $this->getValue($value);
    }

    // assunto
    public function subject(string $value)
    {
        $this->assunto = $value;
        return $this;
    }

    // set to
    public function setTo(string $to)
    {
        $this->to = $to;

        return $this;
    }

    // set Name
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    // Obter o valor para options
    private function getValue($value)
    {
        if (is_array($value))
        {
            return json_encode($value);
        }
        else
        {
            return $value;
        }
    }

    // Validate params send mandrill
    private function validate()
    {
        if (empty($this->to))
        {
            throw new RuntimeException('Sender not informed');
        }
    }

    // get errors
    public function getErrors()
    {
        return $this->errors;
    }

    // add cc
    public function addCc(string $cc)
    {
        $this->cc[] = $cc;
        return $this;
    }

    public function setCc(Array $cc)
    {
        $this->cc = $cc;
        return $this;
    }

    // send mandrill
    public function send()
    {

        try
        {
            // Validate
            $this->validate();

            Mail::send('mails.mandrill', [], function($message)
            {

                $headers = $message->getHeaders();
                $headers->addTextHeader('X-MC-Template', $this->template);

                if (!empty($this->vars))
                {
                    $headers->addTextHeader('X-MC-MergeVars', json_encode($this->vars));
                }

                // Outros parametros
                foreach ($this->options as $key => $value)
                {
                    $headers->addTextHeader($key, $value);
                }

                foreach ($this->attachs as $attach)
                {
                    $message->attach($attach);
                }

                if (!empty($this->assunto))
                {
                    $message->subject($this->assunto);
                }

                // Caso seja ambiente de produção
                if (config('app.env') == 'production')
                {
                    // Cópias de emails
                    foreach ($this->cc as $cc)
                    {
                        if (filter_var($cc, FILTER_VALIDATE_EMAIL))
                        {
                            $message->cc($cc);
                        }
                    }
                    $message->to($this->to, $this->name ?? '');
                }
                else
                {
                    $message->to(config('ambiente.email_dev'), $this->name ?? '');
                }
            });

            return true;
        }
        catch (Exception $e)
        {

            $this->errors = [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ];

            return false;
        }
    }

}
