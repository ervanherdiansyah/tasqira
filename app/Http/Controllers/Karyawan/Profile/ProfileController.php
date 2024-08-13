<?php

namespace App\Http\Controllers\Karyawan\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{

    public function index()
    {
        try {
            //code...
            $user = Auth::user()->id;
            $profile = Profile::where('id', $user)->first();
            return view('src.pages.karyawan.profile.index', compact('profile'));
        } catch (\Throwable $th) {
            //throw $th;
            toast($th->getMessage(), 'warning');
            return redirect('/karyawan/profile');
        }
    }

    public function store(Request $request)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|string|max:255|email',
                'nohp' => 'required|string|max:255',
            ]);
            $file_name = $request->foto->getClientOriginalName();
            $namaGambar = str_replace(' ', '_', $file_name);
            $image = $request->foto->storeAs('public/foto', $namaGambar);
            $accountUser = Profile::create([
                'user_id' => Auth::user()->id,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'nohp' => $request->nohp,
                'foto' => 'foto/' . $namaGambar,
            ]);
            
            $userEmail = User::where('id', Auth::user()->id)->first();
            $userEmail->update([
                'email' => $accountUser->email,
            ]);
            toast('Berhasil Tambah Data!!!', 'success');
            return redirect('/karyawan/profile');
        } catch (\Throwable $th) {
            //throw $th;
            toast($th->getMessage(), 'warning');
            return redirect('/karyawan/profile');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|net|org|id|co\.id)$/'
                ],
                'nohp' => 'required|string|max:255',
            ], [
                'email.regex' => 'Email harus memiliki domain .com, .net, .org, .id, atau .co.id.',
            ]);
            $user = Profile::where('id', Auth::user()->id)->first();
            if ($request->hasFile('foto')) {
                $file_name = $request->foto->getClientOriginalName();
                $namaGambar = str_replace(' ', '_', $file_name);
                $user->foto = 'foto/' . $namaGambar;
                $request->foto->storeAs('public/foto', $namaGambar);
            }
            $user->fullname = $request->fullname;
            $user->email = $request->email;
            $user->nohp = $request->nohp;
            $user->save();
            
            $userEmail = User::where('id', Auth::user()->id)->first();
            $userEmail->update([
                'email' => $user->email,
            ]);
            toast('Berhasil Update Data!!!', 'success');
            return redirect('/karyawan/profile');
        } catch (\Throwable $th) {
            //throw $th;
            toast($th->getMessage(), 'warning');
            return redirect('/karyawan/profile');
        }
    }

    public function destroy($id)
    {
        try {
            //code...
            $user = Auth::user()->id;
            $accountUser = Profile::where('id', $user)->first();
            $accountUser->delete();
            toast('Berhasil Delete Data!!!', 'success');
            return redirect('/karyawan/profile');
        } catch (\Throwable $th) {
            //throw $th;
            toast($th->getMessage(), 'warning');
            return redirect('/karyawan/profile');
        }
    }
}
