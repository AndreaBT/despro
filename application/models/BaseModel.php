<?php
class BaseModel extends CI_Model
{
    public $Limit;
    public $Offset;

    protected function set_pagination()
    {
        if ($this->Limit != 0) {
            
            if ($this->Offset == 0) {
                $this->db->limit($this->Limit);
            } else {
                $this->db->limit($this->Limit, $this->Offset);
            }
        }
    }
}
