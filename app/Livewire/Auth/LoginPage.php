<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login Page')]
class LoginPage extends Component
{
    public $email;
    public $password;

    public function save() {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
        ]);
        
        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Email atau password salah.');
            return;
        }
        return redirect()->to('/');
    }
    
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
