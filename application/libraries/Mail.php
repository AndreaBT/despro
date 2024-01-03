<?php
class Mail {

    const PROTOCOL = 'smtp';
    const HOST = 'smtp.1and1.com';
    const PORT = 25;
    const USER = 'noreplay.desprosoft@nanodela.com';
    const PASSWORD = 'Desprosoft2020@';
    const CRYPTO = 'tls';
    public $Empresa='';
    public $From='';
    public $To='';
    public $Subject='';
    public $Message='';
    public $cc=null;
    public $bcc=null;
    public $files=null;

    /**
     * Permite enviar un correo electrónico y devuelve si fue o no enviado
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array $cc
     * @param array $bcc
     * @param array $files
     * @return string 
     */
    public  function SendEmail()
    {
        $config = [        
            'protocol'      => self::PROTOCOL,
            'smtp_host'     => self::HOST,
            'smtp_user'     => self::USER,
            'smtp_pass'     => self::PASSWORD,
            'smtp_crypto'   => self::CRYPTO,    
            'newline'       => "\r\n", 
            'crlf'          => "\r\n",
            'smtp_port'     => self::PORT,
            'charset'       => 'utf-8',
            'mailtype'      => 'html'
        ];
              
        $CI = &get_instance();
        $CI->load->library('email', $config);
        $CI->email->initialize($config);

        if ($this->From=='')
        {
            $CI->email->from(self::USER, $this->Empresa);
            $CI->email->reply_to(self::USER);
        }
        else
        {
            $CI->email->from($this->From, $this->Empresa);
            $CI->email->reply_to($this->From);
        }
          
        if(is_array($this->To)){
            $CI->email->to($this->To);
        }
        else
        {
           $CI->email->to($this->To); 
        }

        if(is_array($this->cc)){
            $CI->email->cc($this->cc);
        }

        if(is_array($this->bcc)){
            $CI->email->bcc($this->bcc);
        }

        if(is_array($this->files)){
            foreach($this->files as $file){
                $CI->email->attach($file);    
            }
        }

        $CI->email->subject($this->Subject);
        $CI->email->message($this->Message);  

        if (!$CI->email->send())
        {
            return 'No se ha logrado enviar el correo electrónico.';
        }

        return 'Se ha enviado correctamente el correo electrónico.';
    }
}
