<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Lupa Password')]
class ForgotPasswordPage extends Component
{
    public $email;

    public function save() {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Silahkan cek email anda untuk melakukan reset password.');
            $this->email = '';
        } else {
            session()->flash('error', 'Email tidak terdaftar.');
        }
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
