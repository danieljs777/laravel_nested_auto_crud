<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Controllers;

use LaravelNestedAutoCrud\Http\Controllers\Controller;
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Services\LeadService;
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http\Requests\ServiceRequest;
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\Lead;
use LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadView;

class WebController extends Controller
{

    public function index()
    {
        return redirect()->route('dbm-crm.leads.service', ['route' => 'lista']);

    }

    public function lista($route, $id = '')
    {
        return view('layout.dbm-crm.leads.datagrid');

    }

    public function service($route, $id = '')
    {
        return view('layout.dbm-crm.leads.datagrid');

    }

    public function novo($route, $id = '')
    {
        return view('layout.dbm-crm.leads.detail');

    }

    public function edit($route, $id = '')
    {
        return view('layout.dbm-crm.leads.detail');

    }

    public function download()
    {
        $service = new LeadService(new Lead(), new LeadView(), new ServiceRequest());
        $service->download('Leads');

    }

}
