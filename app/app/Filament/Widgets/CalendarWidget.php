<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use App\Models\Ferias;
use App\Models\Evento;
use Filament\Forms;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CalendarWidget extends FullCalendarWidget
{
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasRole('colaborador');
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $ferias = Ferias::query()
            ->whereBetween('data_inicio', [$fetchInfo['start'], $fetchInfo['end']])
            ->where('user_id', auth()->id())
            ->get()
            ->map(fn (Ferias $ferias) => [
                'id' => $ferias->id,
                'title' => "Férias de {$ferias->user->name}",
                'start' => $ferias->data_inicio,
                'end' => $ferias->data_fim,
                'color' => match ($ferias->status) {
                    'aprovado' => 'green',
                    'pendente' => 'orange',
                    'rejeitado' => 'red',
                },
            ]);

        $eventos = Evento::query()
            ->whereBetween('data_inicio', [$fetchInfo['start'], $fetchInfo['end']])
            ->get()
            ->map(fn (Evento $evento) => [
                'id' => 'evento-'.$evento->id,
                'title' => $evento->nome,
                'start' => $evento->data_inicio,
                'end' => $evento->data_fim,
                'color' => $evento->tipo === 'feriado' ? 'red' : 'blue',
                'display' => 'background',
            ]);

        return $ferias->merge($eventos)->all();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->model(Ferias::class)
                ->label('Marcar Férias')
                ->mountUsing(
                    function (Forms\Form $form, array $arguments) {
                        $start = Carbon::parse($arguments['start'] ?? now());
                        $end = Carbon::parse($arguments['end'] ?? now()->addDays(1));

                        $form->fill([
                            'data_inicio' => $this->adjustToWorkday($start),
                            'data_fim' => $this->adjustToWorkday($end),
                        ]);
                    }
                )
                ->form([
                    Forms\Components\DatePicker::make('data_inicio')
                        ->required()
                        ->label('Data de Início')
                        ->minDate(now())
                        ->disabledDates(fn () => $this->getDisabledDates()),

                    Forms\Components\DatePicker::make('data_fim')
                        ->required()
                        ->label('Data de Fim')
                        ->minDate(now())
                        ->disabledDates(fn () => $this->getDisabledDates())
                        ->afterStateUpdated(function ($state, $set) {
                            $set('data_inicio', min($state, $state));
                        }),
                ])
                ->mutateFormDataUsing(function (array $data): array {
                    $this->validatePeriod($data['data_inicio'], $data['data_fim']);
                    return array_merge($data, [
                        'user_id' => Auth::id(),
                        'status' => 'pendente',
                    ]);
                }),
        ];
    }

    /** 🔹 Ajustar data para o próximo dia útil */
    private function adjustToWorkday(Carbon $date): Carbon
    {
        while ($this->isInvalidDate($date)) {
            $date->addDay();
        }
        return $date;
    }

    private function getDisabledDates(): array
    {
        $invalidDates = [];
        $start = Carbon::now();
        $end = Carbon::now()->addMonths(12);

        while ($start->lte($end)) {
            if ($this->isInvalidDate($start)) {
                $invalidDates[] = $start->format('Y-m-d');
            }
            $start->addDay();
        }

        return $invalidDates;
    }

    private function isInvalidDate(Carbon $date): bool
    {
        if ($date->isWeekend()) {
            return true;
        }

        if (Evento::where('tipo', 'feriado')
            ->whereDate('data_inicio', '<=', $date)
            ->whereDate('data_fim', '>=', $date)
            ->exists()) {
            return true;
        }

        return false;
    }

    private function validatePeriod(string $start, string $end): void
    {
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        if ($startDate->gt($endDate)) {
            throw ValidationException::withMessages([
                'data_fim' => 'A data final não pode ser anterior à data inicial',
            ]);
        }

        $current = $startDate->copy();
        while ($current <= $endDate) {
            if ($this->isInvalidDate($current)) {
                throw ValidationException::withMessages([
                    'data_inicio' => 'O período selecionado contém dias não permitidos',
                    'data_fim' => 'O período selecionado contém dias não permitidos',
                ]);
            }
            $current->addDay();
        }

        $existing = Ferias::where('user_id', Auth::id())
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('data_inicio', [$startDate, $endDate])
                    ->orWhereBetween('data_fim', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('data_inicio', '<', $startDate)
                            ->where('data_fim', '>', $endDate);
                    });
            })
            ->whereIn('status', ['pendente', 'aprovado'])
            ->exists();

        if ($existing) {
            throw ValidationException::withMessages([
                'data_inicio' => 'Já existe um período de férias marcado neste intervalo',
            ]);
        }
    }
}
