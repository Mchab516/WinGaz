<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ChargementsVentes;
use App\Models\Client;
use App\Support\ProfilGate as PG;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardKpis extends StatsOverviewWidget
{
    protected ?string $heading = 'Indicateurs clés';

    // 1) Lazy: le widget se charge après le reste de la page
    protected static bool $isLazy = true;

    public static function canView(): bool
    {
        return PG::can('can_reporting') || PG::can('can_chargements_ventes') || PG::can('can_clients');
    }

    protected function getStats(): array
    {
        $now   = Carbon::now();
        $y     = (int) $now->year;
        $m2    = str_pad((string) $now->month, 2, '0', STR_PAD_LEFT);

        $prev  = $now->copy()->subMonth();
        $py    = (int) $prev->year;
        $pm2   = str_pad((string) $prev->month, 2, '0', STR_PAD_LEFT);

        // Ranges (meilleure utilisation des index que whereYear/whereMonth)
        $currStart = $now->copy()->startOfMonth()->startOfDay();
        $currEnd   = $now->copy()->endOfMonth()->endOfDay();
        $prevStart = $prev->copy()->startOfMonth()->startOfDay();
        $prevEnd   = $prev->copy()->endOfMonth()->endOfDay();

        // TTL de cache (en secondes)
        $ttl = 300;

        // 2) Cache: calculs lourds mémorisés quelques minutes
        $ventesThisMonth = Cache::remember("kpi:ventes:$y:$m2", $ttl, function () use ($y, $m2) {
            return (int) (ChargementsVentes::query()
                ->where('annee', $y)->where('mois', $m2)
                ->selectRaw('SUM(qte_vendu_3kg + qte_vendu_6kg + qte_vendu_9kg + qte_vendu_12kg + qte_vendu_35kg + qte_vendu_40kg) as total')
                ->value('total') ?? 0);
        });

        $ventesPrevMonth = Cache::remember("kpi:ventes:$py:$pm2", $ttl, function () use ($py, $pm2) {
            return (int) (ChargementsVentes::query()
                ->where('annee', $py)->where('mois', $pm2)
                ->selectRaw('SUM(qte_vendu_3kg + qte_vendu_6kg + qte_vendu_9kg + qte_vendu_12kg + qte_vendu_35kg + qte_vendu_40kg) as total')
                ->value('total') ?? 0);
        });

        $deltaVentes = $ventesPrevMonth === 0 ? null : round((($ventesThisMonth - $ventesPrevMonth) / max(1, $ventesPrevMonth)) * 100);

        $newClientsThisMonth = Cache::remember(
            "kpi:newClients:$y:$m2",
            $ttl,
            fn() =>
            Client::whereBetween('created_at', [$currStart, $currEnd])->count()
        );
        $newClientsPrevMonth = Cache::remember(
            "kpi:newClients:$py:$pm2",
            $ttl,
            fn() =>
            Client::whereBetween('created_at', [$prevStart, $prevEnd])->count()
        );
        $deltaClients = $newClientsPrevMonth === 0 ? null : round((($newClientsThisMonth - $newClientsPrevMonth) / max(1, $newClientsPrevMonth)) * 100);

        $newOrdersThisMonth = Cache::remember(
            "kpi:newOrders:$y:$m2",
            $ttl,
            fn() =>
            ChargementsVentes::whereBetween('created_at', [$currStart, $currEnd])->count()
        );
        $newOrdersPrevMonth = Cache::remember(
            "kpi:newOrders:$py:$pm2",
            $ttl,
            fn() =>
            ChargementsVentes::whereBetween('created_at', [$prevStart, $prevEnd])->count()
        );
        $deltaOrders = $newOrdersPrevMonth === 0 ? null : round((($newOrdersThisMonth - $newOrdersPrevMonth) / max(1, $newOrdersPrevMonth)) * 100);

        $stats = [];

        if (PG::can('can_chargements_ventes')) {
            $stats[] = Stat::make('Volume vendu (ce mois)', number_format($ventesThisMonth, 0, ',', ' '))
                ->description($deltaVentes === null ? '—' : (($deltaVentes >= 0 ? '+' : '') . $deltaVentes . '% vs mois préc.'))
                ->descriptionIcon($deltaVentes !== null ? ($deltaVentes >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down') : null)
                ->color($deltaVentes !== null ? ($deltaVentes >= 0 ? 'success' : 'danger') : null)
                ->icon('heroicon-o-truck');
        }

        if (PG::can('can_clients')) {
            $stats[] = Stat::make('Nouveaux clients', number_format($newClientsThisMonth, 0, ',', ' '))
                ->description($deltaClients === null ? '—' : (($deltaClients >= 0 ? '+' : '') . $deltaClients . '% vs mois préc.'))
                ->descriptionIcon($deltaClients !== null ? ($deltaClients >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down') : null)
                ->color($deltaClients !== null ? ($deltaClients >= 0 ? 'success' : 'danger') : null)
                ->icon('heroicon-o-user-group');
        }

        if (PG::can('can_chargements_ventes')) {
            $stats[] = Stat::make('Nouvelles déclarations Ch/Ventes', number_format($newOrdersThisMonth, 0, ',', ' '))
                ->description($deltaOrders === null ? '—' : (($deltaOrders >= 0 ? '+' : '') . $deltaOrders . '% vs mois préc.'))
                ->descriptionIcon($deltaOrders !== null ? ($deltaOrders >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down') : null)
                ->color($deltaOrders !== null ? ($deltaOrders >= 0 ? 'success' : 'danger') : null)
                ->icon('heroicon-o-clipboard-document-check');
        }

        return $stats;
    }
}
