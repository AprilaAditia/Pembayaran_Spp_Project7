<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran; 

class KwitansiPembayaranController extends Controller
{
  public function show($id)
  {
    $pembayaran = Pembayaran::findOrFail($id); // Perbaiki kesalahan penulisan
    $data['pembayaran'] = $pembayaran;
    return view('operator.kwitansi_pembayaran', $data);
  }
}
