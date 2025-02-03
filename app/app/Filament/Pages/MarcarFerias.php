<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Ferias;
use Illuminate\Support\Facades\Auth;
use App\Models\CargoEnum;


class MarcarFerias extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Marcar Férias';
    protected static string $view = 'filament.pages.marcar-ferias';

    /**
     * 🔹 Apenas Colaboradores podem ver esta página
     */
    public static function canView(): bool
    {
        return Auth::user() && Auth::user()->cargo === CargoEnum::COLABORADOR;
    }

    public function submit()
    {
        Ferias::create([
            'user_id' => Auth::id(),
            'data_inicio' => request()->input('data_inicio'),
            'data_fim' => request()->input('data_fim'),
            'status' => 'pendente',
        ]);

        session()->flash('success', 'Pedido de férias submetido para aprovação.');
    }
}
