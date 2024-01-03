<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_V1 extends CI_Migration
{

    public function up()
    {
        #region tabla de usuario
        $this->dbforge->add_field(array(
            'IdUsuario' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'Nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '128'
            ),
            'Apellido' => array(
                'type' => 'VARCHAR',
                'constraint' => '128'
            ),
            'Usuario' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => FALSE
            ),
            'Contrasenia' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => FALSE

            ),
            'RegEstatus' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => FALSE

            ),
        ));
        $this->dbforge->add_key('IdUsuario', TRUE);
        $this->dbforge->create_table('Usuarios');
        #endregion

        #region tabla Empresas
        $this->dbforge->add_field(array(
            'IdEmpresa' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'NombreComercial' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => FALSE,

            ),
            'NombreFiscal' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => FALSE,
            ),
            'RFC' => array(
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => TRUE,
            ),
            'RegFiscal' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => TRUE,
            ),
            'FechaReg' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'RegEstatus' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => FALSE,
            ),
        ));

        $this->dbforge->add_key('IdEmpresa', TRUE);
        $this->dbforge->create_table('Empresas');
        #endregion
    }

    public function down()
    {
        $this->dbforge->drop_table('Usuarios');
        $this->dbforge->drop_table('Empresas');
    }
}
