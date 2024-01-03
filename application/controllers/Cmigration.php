<?php 
        
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Cmigration extends CI_Controller {

public function Execute()
{
    $this->load->library('migration');

    if ($this->migration->latest() === FALSE)
    {
        show_error($this->migration->error_string());
    }else{
        echo 'Migrations runs successfuly!';
    }
}

public function ExecuteVersion($version = 0)
{
    $this->load->library('migration');

    if ($this->migration->version($version) === FALSE)
    {
        show_error($this->migration->error_string());
    }else{
        echo 'Migration Version ' . $version . ' runs successfuly!';
    }
}
        
}
        
    /* End of file  Cmigration.php */
        
                            