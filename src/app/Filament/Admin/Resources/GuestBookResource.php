<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestbookResource\Pages;
use App\Models\Guestbook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GuestbookResource extends Resource
{
    protected static ?string $model = Guestbook::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Buku Tamu Tenant';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pengguna_id')
                    ->relationship('pengguna', 'name')
                    ->required()->searchable(),
                Forms\Components\Select::make('theme_id')
                    ->relationship('theme', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')->label('Nama Event/Web')->required(),
                Forms\Components\TextInput::make('slug')->required(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengguna.name')->label('Pemilik (Tenant)')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Nama Web/Event')->searchable(),
                Tables\Columns\TextColumn::make('slug')->copyable()->description(fn($record) => url('/' . $record->slug)),
                Tables\Columns\TextColumn::make('theme.name')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pengguna_id')
                    ->relationship('pengguna', 'name')->label('Filter per Tenant')
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuestbooks::route('/'),
            'create' => Pages\CreateGuestbook::route('/create'),
            'edit' => Pages\EditGuestbook::route('/{record}/edit'),
        ];
    }
}