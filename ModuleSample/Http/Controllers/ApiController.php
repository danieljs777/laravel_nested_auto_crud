<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Controllers;

use LaravelNestedAutoCrud\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Services\LeadService;

class ApiController extends \LaravelNestedAutoCrud\Http\Controllers\ApiController
{

    protected $service;
    protected $request;


    public function __construct(Request $request, LeadService $service)
    {
        parent::__construct($request, $service);

    }

}
