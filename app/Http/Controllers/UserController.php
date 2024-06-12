<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as Model;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $viewIndex = 'user_index';
    private $viewCreate = 'user_form';
    private $viewEdit = 'user_form';
    private $viewShow = 'user_show';
    private $routePrefix = 'user';
    /**
     * Display a listing of the resource.
     */
    public function view()
    {
        $select = DB::table("users")->get();
        return response()->json([
            "status" => 200,
            "data" => $select
        ]);
    }
    public function index()
    {
        return view('operator.' . $this->viewIndex,[
            'models' => Model::where('akses', '<>', 'wali')
                ->latest()
                ->paginate(50),
            'routePrefix' => $this->routePrefix,
            'title' => 'Data User'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'model' => new Model(),
            'method' => 'POST',
            'route' => $this->routePrefix . '.store',
            'button' => 'SIMPAN',
            'title' => 'FORM DATA USER'
        ];
        return view('operator.' . $this->viewCreate, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {
            $model->name = $request->name;
            $model->email = $request->email;
            $model->nohp = $request->nohp;
            $model->akses = $request->akses;
            $model->password = $request->password;
       } catch (\Throwable $th) {
        return response()->json([
            "status" => 400,
            "message" => $th
        ]);
       }
       
        return response()->json([
        "status" => 200,
        "message" => "Data successfully submited!!"
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'model' => Model::findOrFail($id),
            'method' => 'PUT',
            'route' => [$this->routePrefix . '.update', $id],
            'button' => 'UPDATE',
            'title' => 'FORM DATA USER',
        ];
        return view('operator.' . $this->viewEdit, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requestData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'nohp' => 'required|unique:users,nohp,' . $id,
            'akses' => 'required|in:operator,admin',
            'password' => 'nullable',
        ]);
        $model = Model::findOrFail($id);
        if ($requestData['password'] == "") {
            unset($requestData['password']);
        } else {
            $requestData['password'] = bcrypt($requestData['password']);
        }
        $model->fill($requestData);
        $model->save();
        flash('Data Berhasil Diubah');
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Model::findOrFail($id);

        if ($model->id == 2) {
            flash('Data Tidak Bisa Dihapus')->error();
            return back();
        }

        $model->delete();
        flash('Data Berhasil Dihapus');
        return back();
    }
}
