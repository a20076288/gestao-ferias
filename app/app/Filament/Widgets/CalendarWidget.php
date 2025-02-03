<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Ferias;

class CalendarWidget extends FullCalendarWidget
{
    /**
     * 🔹 Apenas Colaboradores podem ver este widget
     */
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasRole('colaborador');
    }

    /**
     * 🔹 Buscar eventos da base de dados para mostrar no calendário
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $query = Ferias::query()
            ->whereBetween('data_inicio', [$fetchInfo['start'], $fetchInfo['end']]);

        // 🔹 Se for um Colaborador, só pode ver as suas próprias férias
        if (auth()->user()->hasRole('colaborador')) {
            $query->where('user_id', auth()->id());
        }

        return $query->get()
            ->map(fn (Ferias $ferias) => [
                'id' => $ferias->id,
                'title' => "Férias de {$ferias->user->primeiro_nome} {$ferias->user->ultimo_nome}",
                'start' => $ferias->data_inicio,
                'end' => $ferias->data_fim,
                'color' => match ($ferias->status) {
                    'aprovado' => 'green', 
                    'pendente' => 'orange', 
                    'rejeitado' => 'red',   
                },
            ])
            ->all();
    }
}
