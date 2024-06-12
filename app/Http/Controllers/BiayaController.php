<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBiayaRequest;
use App\Http\Requests\StoreBiayaRequest;
use App\Models\Biaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiayaController extends Controller
{
    private $viewIndex = 'biaya_index';
    private $viewCreate = 'biaya_form';
    private $viewEdit = 'biaya_form';
    private $viewShow = 'biaya_show';
    private $routePrefix = 'biaya';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->filled('q')) {
            $models = Biaya::with('user')->search($request->q)->paginate(50);
        } else {
            $models = Biaya::with('user')->latest()->paginate(50);
        }

        return view('operator.' . $this->viewIndex, [
            'models' => $models,
            'routePrefix' => $this->routePrefix,
            'title' => 'Data Biaya'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'model' => new Biaya(),
            'method' => 'POST',
            'route' => $this->routePrefix . '.store',
            'button' => 'SIMPAN',
            'title' => 'FORM DATA BIAYA',
        ];
        return view('operator.' . $this->viewCreate, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBiayaRequest $request)
    {
        $requestData = $request->validated();
        $requestData['user_id'] = auth()->user()->id;

        Biaya::create($requestData);

        flash('Data Berhasil Disimpan');
        return redirect()->route($this->routePrefix . '.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('operator.' . $this->viewShow, [
            'model' => Biaya::findOrFail($id),
            'title' => 'Detail Biaya'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'model' => Biaya::findOrFail($id),
            'method' => 'PUT',
            'route' => [$this->routePrefix . '.update', $id],
            'button' => 'UPDATE',
            'title' => 'FORM DATA BIAYA',
        ];
        return view('operator.' . $this->viewEdit, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBiayaRequest $request, string $id)
    {
        $requestData = $request->validated();
        $model = Biaya::findOrFail($id);
        $requestData['user_id'] = auth()->user()->id;

        $model->fill($requestData);
        $model->save();

        flash('Data Berhasil Diubah');
        return redirect()->route($this->routePrefix . '.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Biaya::findOrFail($id);
        $model->delete();

        flash('Data Berhasil Dihapus');
        return redirect()->route($this->routePrefix . '.index');
    }
}
