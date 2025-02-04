<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeriasResource\Pages;
use App\Models\Ferias;
use App\Models\User;
use App\Models\CargoEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Facades\Filament;

class FeriasResource extends Resource
{
    protected static ?string $model = Ferias::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function canViewAny(): bool
    {
        return Auth::user() && in_array(Auth::user()->cargo, [CargoEnum::ADMINISTRACAO, CargoEnum::DIRECAO]);
    }

    public static function canCreate(): bool
    {
        return self::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return self::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return self::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return self::canViewAny();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('data_inicio')
                    ->required()
                    ->native(false)
                    ->locale('pt')
                    ->label('Data de Início')
                    ->minDate(now()),

                Forms\Components\DatePicker::make('data_fim')
                    ->required()
                    ->native(false)
                    ->locale('pt')
                    ->label('Data de Fim')
                    ->minDate(now()),

                Forms\Components\Select::make('status')
                    ->options([
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'rejeitado' => 'Rejeitado',
                    ])
                    ->default('pendente')
                    ->visible(fn () => Auth::user()->cargo !== CargoEnum::COLABORADOR),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.primeiro_nome')
                    ->label('Utilizador')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('data_inicio')
                    ->label('Início')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_fim')
                    ->label('Fim')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'rejeitado' => 'Rejeitado',
                    })
                    ->colors([
                        'pendente' => 'yellow',
                        'aprovado' => 'green',
                        'rejeitado' => 'red',
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn () => Auth::user()->cargo !== CargoEnum::COLABORADOR),
                Tables\Actions\DeleteAction::make()->visible(fn () => Auth::user()->cargo !== CargoEnum::COLABORADOR),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->visible(fn () => Auth::user()->cargo !== CargoEnum::COLABORADOR),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFerias::route('/'),
            'create' => Pages\CreateFerias::route('/create'),
            'edit' => Pages\EditFerias::route('/{record}/edit'),
        ];
    }
}
