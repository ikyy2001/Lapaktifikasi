<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends ApiController
{
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $data = [
            'user' => $user,
        ];

        if ($user->role_id == 2) { // Customer
            $customer = CustomerModel::where('user_id', $user->id)->first();
            $data['customer_details'] = $customer;
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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('no_whatsapp')) {
            $user->no_whatsapp = $request->no_whatsapp;
        }

        if ($request->has('password') && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            
            // Move file to public/assets/img/avatar or appropriate directory
            $file->move(public_path('assets/img/avatar'), $filename);
            
            // Delete old picture if it's not a default one
            if ($user->profile_picture && !in_array($user->profile_picture, ['avatar-1.png', 'avatar-2.png', 'avatar-3.png', 'avatar-4.png', 'avatar-5.png', 'avatar-admin.png', 'default.png'])) {
                $oldPath = public_path('assets/img/avatar/' . $user->profile_picture);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            $user->profile_picture = $filename;
        }

        $user->save();

        return $this->sendResponse(['user' => $user], 'Profil berhasil diperbarui');
    }
}
