<?php

namespace App\Filament\Resources\GuestBookResource\Pages;

use App\Filament\Resources\GuestBookResource;
use Filament\Resources\Pages\EditRecord;

class EditGuestBook extends EditRecord
{
    protected static string $resource = GuestBookResource::class;
}
