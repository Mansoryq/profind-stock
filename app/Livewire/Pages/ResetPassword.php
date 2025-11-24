<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPassword extends Component
{
    public $token;
    public $email;
    public $password = '';
    public $password_confirmation = '';
    public $success = false;

    protected $rules = [
        'password' => 'required|min:8|confirmed',
    ];

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request('email');
        
        // Verify token exists and is not expired (60 minutes)
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->where('token', $this->token)
            ->where('created_at', '>=', now()->subMinutes(60))
            ->first();
            
        if (!$resetRecord) {
            abort(404, 'Ссылка недействительна или истекла');
        }
    }

    public function resetPassword()
    {
        $this->validate();

        // Find user
        $user = User::where('email', $this->email)->first();
        
        if (!$user) {
            $this->addError('email', 'Пользователь не найден.');
            return;
        }

        // Update password
        $user->password = Hash::make($this->password);
        $user->save();

        // Delete the reset token
        DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->where('token', $this->token)
            ->delete();

        $this->success = true;
    }

    public function render()
    {
        return view('livewire.pages.reset-password');
    }
}
