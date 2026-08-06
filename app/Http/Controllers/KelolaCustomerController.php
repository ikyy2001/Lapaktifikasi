<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class KelolaCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role_id', 2)->paginate(20);
        return view('admin.kelola_customer.index', compact('customers'));
    }

    public function banCustomer(Request $request, $id)
    {
        $request->validate([
            'banned_reason' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($id);
        if ($user->role_id == 2) {
            $user->update([
                'is_banned' => true,
                'banned_reason' => $request->banned_reason,
            ]);
            Session::flash('success', 'Customer berhasil dibanned.');
        }

        return redirect('/kelola_customer');
    }

    public function unbanCustomer($id)
    {
        $user = User::findOrFail($id);
        if ($user->role_id == 2) {
            $user->update([
                'is_banned' => false,
                'banned_reason' => null,
            ]);
            Session::flash('success', 'Customer berhasil di-unban.');
        }

        return redirect('/kelola_customer');
    }
}
