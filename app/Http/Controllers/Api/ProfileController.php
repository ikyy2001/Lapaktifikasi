<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerModel;
use App\Models\Toko;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends ApiController
{
    public function getProfile(Request $request)
    {
        $user = $request->user();
        
        $roleName = match((int)$user->role_id) {
            1 => 'admin',
            3 => 'seller',
            default => 'customer',
        };

        $data = [
            'user' => $user,
            'role' => $roleName,
        ];

        if ($user->role_id == 2) {
            $customer = CustomerModel::with('tier')->where('user_id', $user->id)->first();
            if ($customer) {
                $data['customer'] = $customer;
                $data['tier_progress'] = $customer->progressKeTierBerikutnya();
            }
        } elseif ($user->role_id == 3) {
            $toko = Toko::with('badges')->where('user_id', $user->id)->first();
            $data['toko'] = $toko;
        }

        return $this->sendResponse($data, 'Profil berhasil diambil');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'password' => 'nullable|min:10',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('assets/img/avatar');
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }
            
            $file->move($targetDir, $filename);
            
            if ($user->profile_picture && !in_array($user->profile_picture, ['avatar-1.png', 'avatar-2.png', 'avatar-3.png', 'avatar-4.png', 'avatar-5.png', 'avatar-admin.png', 'default.png'])) {
                $oldPath = public_path('assets/img/avatar/' . $user->profile_picture);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $user->profile_picture = $filename;
        }

        $user->save();

        // Customer details update
        if ($user->role_id == 2) {
            $customer = CustomerModel::where('user_id', $user->id)->first();
            if ($customer) {
                if ($request->filled('name')) {
                    $customer->nama_customer = $request->name;
                }
                if ($request->has('no_whatsapp')) {
                    $customer->nomor_telepon = $request->no_whatsapp;
                }
                $customer->save();
            }
        }

        return $this->getProfile($request);
    }
}
