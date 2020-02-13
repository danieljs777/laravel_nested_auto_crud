<?php

namespace LaravelNestedAutoCrud\Http\Controllers;

use LaravelNestedAutoCrud\Http\Controllers\Controller;
use Illuminate\Http\Request;

/*
 * @author daniel (daniel.js at gmail dot com)
 */

class ApiController extends Controller
{

    protected $service;
    protected $request;


    public function __construct(Request $request, $service)
    {
        $this->request = $request;
        $this->service = $service;

    }

    public function index()
    {
        return $this->service->list();

    }

    public function show($id)
    {
        return $this->service->show($id);

    }

    public function store()
    {
        return $this->service->store();

    }

    public function update($id)
    {
        return $this->service->update($id);

    }

    public function destroy($id)
    {
        return $this->service->destroy($id);

    }

}
