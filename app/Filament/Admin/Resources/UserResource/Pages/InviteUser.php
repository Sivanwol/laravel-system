<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class InviteUser extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.admin.resources.user-resource.pages.invite-user';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email')
                    ->validationMessages([
                        'unique' => 'This email address is already registered.',
                    ])
            ])
            ->statePath('data');
    }

    public function invite(): void
    {
        $data = $this->form->getState();

        // Create the user as invited
        $user = User::create([
            'email' => $data['email'],
            'name' => explode('@', $data['email'])[0], // Temporary name based on email
            'password' => bcrypt(Str::random(16)), // Random password
            'is_invited' => true,
        ]);

        // Trigger event to send invitation email
        Event::dispatch('user.invited', $user);

        Notification::make()
            ->title('User invited successfully')
            ->success()
            ->send();

        $this->redirect(UserResource::getUrl());
    }
}
