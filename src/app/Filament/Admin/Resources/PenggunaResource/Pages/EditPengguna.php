<?php

namespace App\Filament\Admin\Resources\PenggunaResource\Pages;

use App\Filament\Admin\Resources\PenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengguna extends EditRecord
{
    protected static string $resource = PenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}