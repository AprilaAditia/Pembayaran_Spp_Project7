<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiswaRequest;
use App\Http\Requests\StoreSiswaRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    private $viewIndex = 'siswa_index';
    private $viewCreate = 'siswa_form';
    private $viewEdit = 'siswa_form';
    private $viewShow = 'siswa_show';
    private $routePrefix = 'siswa';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->filled('q')) {
            $models = Siswa::search($request->q)->paginate(50);
        } else {
            $models = Siswa::with('wali', 'user')->latest()->paginate(50);
        }

        return view('operator.' . $this->viewIndex, [
            'models' => $models,
            'routePrefix' => $this->routePrefix,
            'title' => 'Data Siswa'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'model' => new Siswa(),
            'method' => 'POST',
            'route' => $this->routePrefix . '.store',
            'button' => 'SIMPAN',
            'title' => 'FORM DATA SISWA',
            'wali' => User::where('akses', 'wali')->pluck('name', 'id'),
        ];
        return view('operator.' . $this->viewCreate, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request)
    {
        $requestData = $request->validated();

        if ($request->hasFile('foto')) {
            $requestData['foto'] = $request->file('foto')->store('public');
        }
        if ($request->filled('wali_id')) {
            $requestData['wali_status'] = 'ok';
        }
        $requestData['user_id'] = auth()->user()->id;

        Siswa::create($requestData);

        flash('Data Berhasil Disimpan');
        return redirect()->route($this->routePrefix . '.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('operator.' . $this->viewShow, [
            'model' => Siswa::findOrFail($id),
            'title' => 'Detail Siswa'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'model' => Siswa::findOrFail($id),
            'method' => 'PUT',
            'route' => [$this->routePrefix . '.update', $id],
            'button' => 'UPDATE',
            'title' => 'FORM DATA SISWA',
            'wali' => User::where('akses', 'wali')->pluck('name', 'id'),
        ];
        return view('operator.' . $this->viewEdit, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, string $id)
    {
        $requestData = $request->validated();
        $model = Siswa::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($model->foto) {
                Storage::delete($model->foto);
            }
            $requestData['foto'] = $request->file('foto')->store('public');
        }
        if ($request->filled('wali_id')) {
            $requestData['wali_status'] = 'ok';
        }
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
        $model = Siswa::findOrFail($id);
        if ($model->foto) {
            Storage::delete($model->foto);
        }
        $model->delete();

        flash('Data Berhasil Dihapus');
        return redirect()->route($this->routePrefix . '.index');
    }
}
