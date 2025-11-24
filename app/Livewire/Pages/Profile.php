<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public $name = '';
    public $school = null;
    public $class = null;
    public $email = '';
    public $isPupil = true;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $showDeleteModal = false;
    public $deletePassword = '';

    protected $rules = [
        'name' => 'required|min:2',
        'school' => 'nullable',
        'class' => 'nullable',
        'email' => 'required|email',
    ];

    public function mount()
    {
        /** @var User $user */
        $user = Auth::user();
        $this->name = $user->name;
        $this->school = $user->school;
        $this->class = $user->class;
        $this->email = $user->email;
        $this->isPupil = (bool) $user->is_pupil;
    }

    public function updateProfile()
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();

        // Check if email is changed and if it's unique
        if ($user->email !== $this->email) {
            $this->validate([
                'email' => 'unique:users,email'
            ]);
        }

        $user->name = $this->name;
        $user->school = $this->school;
        $user->class = $this->class;
        $user->email = $this->email;
        $user->save();

        session()->flash('message', 'Профиль успешно обновлен');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Текущий пароль неверный');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('password_message', 'Пароль успешно обновлен');
    }

    public function confirmDeleteAccount()
    {
        $this->showDeleteModal = true;
        $this->deletePassword = '';
    }

    public function cancelDeleteAccount()
    {
        $this->showDeleteModal = false;
        $this->deletePassword = '';
        $this->resetErrorBag('deletePassword');
    }

    public function deleteAccount()
    {
        $this->validate([
            'deletePassword' => 'required',
        ], [
            'deletePassword.required' => 'Введите пароль для подтверждения удаления аккаунта'
        ]);

        $user = Auth::user();

        if (!Hash::check($this->deletePassword, $user->password)) {
            $this->addError('deletePassword', 'Неверный пароль');
            return;
        }

        // Удаляем связанные данные пользователя
        $user->userAnswers()->delete();
        $user->testSessions()->delete();

        // Удаляем самого пользователя
        $user->delete();

        // Выходим из системы и перенаправляем на главную
        Auth::logout();

        return redirect('/')->with('message', 'Аккаунт успешно удален');
    }

    public function render()
    {
        return view('livewire.pages.profile');
    }
}
