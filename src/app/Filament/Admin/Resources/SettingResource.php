<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Pengaturan';
    
    protected static ?string $slug = 'settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('custom_kiosk_title')
                    ->label('Judul Halaman Meja Tamu (Kiosk)')
                    ->placeholder('Contoh: Selamat Datang di Pernikahan Kami')
                    ->maxLength(255),
                    
                Textarea::make('whatsapp_template')
                    ->label('Template Pesan WhatsApp (Format Ucapan)')
                    ->placeholder('Halo {nama}, terima kasih telah hadir...')
                    ->rows(4),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table; // Dikosongkan karena di-bypass oleh ListSettings
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}