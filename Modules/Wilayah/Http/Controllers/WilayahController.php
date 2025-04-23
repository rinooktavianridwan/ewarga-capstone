<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wilayah\Services\WilayahService;

class WilayahController extends Controller
{
    protected $wilayahService;

    public function __construct(WilayahService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }

    public function getAsetStatistics()
    {
        return response()->json($this->wilayahService->getAsetStatistics());
    }

    public function index()
    {
        return view('wilayah::index');
    }

    public function create()
    {
        return view('wilayah::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('wilayah::show');
    }

    public function edit($id)
    {
        return view('wilayah::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
