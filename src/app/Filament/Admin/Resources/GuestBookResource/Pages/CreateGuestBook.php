<?php

namespace App\Filament\Resources\GuestBookResource\Pages;

use App\Filament\Resources\GuestBookResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateGuestBook extends CreateRecord
{
    protected static string $resource = GuestBookResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pengguna_id'] = Auth::id();
        return $data;
    }
}
