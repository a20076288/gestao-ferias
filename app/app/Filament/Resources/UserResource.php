<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\CargoEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Filament\Facades\Filament;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()->can('manage-users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('primeiro_nome')
                    ->label('Primeiro Nome')
                    ->required(),

                TextInput::make('ultimo_nome')
                    ->label('Último Nome')
                    ->required(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->nullable(),

                Select::make('cargo')
                    ->label('Cargo')
                    ->options([
                        CargoEnum::ADMINISTRACAO->value => 'Administração',
                        CargoEnum::DIRECAO->value => 'Direção',
                        CargoEnum::RESPONSAVEL_DEPARTAMENTO->value => 'Responsável Departamento',
                        CargoEnum::RESPONSAVEL_FUNCAO->value => 'Responsável Função',
                        CargoEnum::COLABORADOR->value => 'Colaborador',
                    ])
                    ->required(),

                TextInput::make('funcao')
                    ->label('Função na Empresa')
                    ->nullable(),

                // ✅ Correção do campo roles para garantir que está associado corretamente ao relacionamento
                Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->required()
                    ->default(fn () => Role::where('name', 'colaborador')->first()?->id),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->nullable()
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->required(fn ($record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('primeiro_nome')
                    ->label('Primeiro Nome')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('ultimo_nome')
                    ->label('Último Nome')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cargo')
                    ->label('Cargo')
                    ->sortable(),

                // ✅ Correção para mostrar os roles corretamente na listagem
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->sortable()
                    ->getStateUsing(fn (User $record) => $record->getRoleNames()->join(', ')),
            ])
            ->actions([
                EditAction::make()->visible(fn () => Filament::auth()->user()->can('manage-users')),
                DeleteAction::make()->visible(fn () => Filament::auth()->user()->can('manage-users')),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->visible(fn () => Filament::auth()->user()->can('manage-users')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
