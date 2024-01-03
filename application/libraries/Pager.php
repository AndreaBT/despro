<?php

/**
 *
 * Library Pager
 *
 * 
 * @package		CodeIgniter
 * @category    Library
 * @author      Luis Rogel <desarrollo7@lugarcreativo.mx>
 *
 */

class Pager
{
    public $TotalItems;
    public $CurrentPage;
    public $PageSize;
    public $TotalPages;
    public $StartPage;
    public $EndPage;
    public $Offset;

    /**
     * Función que establece la paginación
     *
     * @param int $totalItems Número total de elementos
     * @param int $page Página solicitada
     * @param int $pageSize Número de elemento por página
     * @return void
     */
    public function set_pagination($totalItems, $page, $pageSize)
    {
        // Si el parámetro $pageSize es vácio, entonces se asigna el total de items
        $pageSize = !empty($pageSize) ? (int)$pageSize: (int)$totalItems;

        // Si el parámetro $page es vacio, entonces se asigna 1
        $currentPage = !empty($page) ? (int)$page : 1;

        $totalPages = !empty($totalItems) ? ceil((float)$totalItems / (float)$pageSize) : 0;
        $startPage = $currentPage - 5;
        $endPage = $currentPage + 4;

        if($startPage <= 0){
            $endPage -= ($startPage - 1);
            $startPage = 1;
        }

        if($endPage > $totalPages){
            
            $endPage = $totalPages;

            if($endPage > 10){

                $startPage = $endPage - 9;
            }
        }

        $this->TotalItems = $totalItems;
        $this->CurrentPage = $currentPage;
        $this->PageSize = (int)$pageSize;
        $this->TotalPages = $totalPages;
        $this->StartPage = $startPage;
        $this->EndPage = $endPage;
        $this->Offset = ($this->CurrentPage - 1) * $this->PageSize;
    }

    /**
     * Función estática que establece la paginación y devuelve un objeto Pager.
     *
     * @param int $totalItems
     * @param int $page
     * @param int $pageSize
     * @return Pager
     */
    public static function get_pager($totalItems, $page, $pageSize)
    {
       
        $instance = new self();
        $instance->set_pagination($totalItems,$page,$pageSize);
        return $instance;
    }

}

