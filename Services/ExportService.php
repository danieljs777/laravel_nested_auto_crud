<?php

namespace App\Services;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * BaseExportService is a class helper that provides base operations for generating XLS/XLSX reports through
 * Laravel Excel version 3.1. This class can be used as automatic report generation or customizing its output data
 * 
 *
 * @author daniel
 */
class BaseExportService implements FromCollection, WithHeadings
{

    use Exportable;

    private $model;
    private $data    = [];
    private $headers = [];

    public function setData($data)
    {
        // Can set data manually for customization
        $this->data = $data;
    }

    public function setHeaders($headers)
    {
        // Can set data manually for customization
        $this->headers = $headers;
    }

    public function setModel($model)
    {
        // Can set model for automation. ModelView is preferable to reflect the datagrid resultset
        $this->model = $model;
    }

    public function collection()
    {
        $data = [];

        //Preferable to use model as automation purposes
        if ($this->model)
        {
            $data = $this->model->get()->toArray();
            $data = collect($data);
        }
        elseif ($this->data)
            $data = collect($this->data);

        return $data;
    }

    public function headings(): array
    {
        //Preferable to use headers as customization purposes
        if ($this->headers)
            return $this->headers;
        elseif ($this->model)
            return $this->model->getFillable();
        else
            return [];
    }

}
