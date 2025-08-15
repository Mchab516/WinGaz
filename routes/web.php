<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExportReportingController;
use App\Models\MonthLock;
use App\Models\User; // ✅ Ajout pour aider Intelephense à comprendre

Route::get('/', fn() => redirect('/admin'));
Route::get('/login', fn() => redirect('/admin/login'))->name('login');

Route::get('/admin/export-reporting', [ExportReportingController::class, 'export'])
    ->name('export-reporting');

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {

    Route::post('/close-month', function () {
        /** @var User $user */ // ✅ Indication pour Intelephense
        $user = Auth::user();

        abort_unless($user && $user->hasAnyRole(['Admin', 'Comptabilité']), 403);

        request()->validate([
            'annee' => 'required|integer',
            'mois'  => 'required|string|size:2',
        ]);

        MonthLock::firstOrCreate([
            'societe'   => 'WINXO',
            'annee'     => request('annee'),
            'mois'      => request('mois'),
        ], [
            'locked_by' => $user->id,
            'locked_at' => now(),
        ]);

        return back()->with('status', '✅ Mois clôturé avec succès.');
    })->name('close-month');

    Route::delete('/close-month', function () {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user && $user->hasAnyRole(['Admin', 'Comptabilité']), 403);

        request()->validate([
            'annee' => 'required|integer',
            'mois'  => 'required|string|size:2',
        ]);

        MonthLock::where([
            'societe' => 'WINXO',
            'annee'   => request('annee'),
            'mois'    => request('mois'),
        ])->delete();

        return back()->with('status', '♻️ Clôture annulée.');
    })->name('open-month');
});
