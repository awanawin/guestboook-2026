<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestResource\Pages;
use App\Models\Guest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Tambahkan baris ini:
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Log Tamu Global';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pengguna_id')->relationship('pengguna', 'name')->disabled(),
                Forms\Components\Select::make('guestbook_id')->relationship('guestbook', 'name')->disabled(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('phone')->required(),
                Forms\Components\TextInput::make('email'),
                Forms\Components\Textarea::make('address'),
                Forms\Components\Textarea::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengguna.name')->label('Tenant')->sortable(),
                Tables\Columns\TextColumn::make('guestbook.name')->label('Event/Buku Tamu'),
                Tables\Columns\TextColumn::make('name')->label('Nama Tamu')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu Hadir')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guestbook_id')
                    ->relationship('guestbook', 'name')->label('Filter per Event')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Implementasi Export Excel:
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->withFilename(fn ($resource) => 'Data-Tamu-' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('pengguna.name')->heading('Tenant'),
                                Column::make('guestbook.name')->heading('Event'),
                                Column::make('name')->heading('Nama Tamu'),
                                Column::make('phone')->heading('WhatsApp'),
                                Column::make('created_at')->heading('Waktu Hadir'),
                            ]),
                    ]),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuests::route('/'),
            'create' => Pages\CreateGuest::route('/create'),
            'edit' => Pages\EditGuest::route('/{record}/edit'),
        ];
    }
}
