<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CsrfLabController extends Controller
{
    public function index(): View
    {
        return view('csrf-lab.index');
    }

    public function howItWorks(): View
    {
        return view('csrf-lab.how-it-works');
    }

    public function attackDemo(): View
    {
        return view('csrf-lab.attack-demo', [
            'transfers' => session('csrf_transfers', []),
            'balance' => session('csrf_balance', 10000000),
        ]);
    }

    public function protectionDemo(): View
    {
        return view('csrf-lab.protection-demo', [
            'actions' => session('csrf_protected_actions', []),
        ]);
    }

    public function ajaxDemo(): View
    {
        return view('csrf-lab.ajax-demo', [
            'ajaxResults' => session('csrf_ajax_results', []),
        ]);
    }

    public function vulnerableTransfer(Request $request): RedirectResponse
    {
        $amount = (int) $request->input('amount', 0);
        $to = $request->input('to', 'Unknown');
        $source = $request->input('source', 'form');

        $balance = session('csrf_balance', 10000000);

        if ($amount > 0 && $amount <= $balance) {
            $balance -= $amount;
            session(['csrf_balance' => $balance]);

            $transfers = session('csrf_transfers', []);
            $transfers[] = [
                'to' => $to,
                'amount' => $amount,
                'balance_after' => $balance,
                'source' => $source,
                'time' => now()->format('H:i:s'),
                'status' => 'success',
                'warning' => 'Transfer TANPA validasi CSRF!',
            ];
            session(['csrf_transfers' => $transfers]);

            return redirect()
                ->route('csrf-lab.attack-demo')
                ->with('danger', "⚠️ Transfer Rp " . number_format($amount) . " ke {$to} BERHASIL tanpa validasi CSRF!");
        }

        return redirect()
            ->route('csrf-lab.attack-demo')
            ->with('error', 'Transfer gagal: saldo tidak cukup atau amount invalid.');
    }

    public function protectedTransfer(Request $request): RedirectResponse
    {
        $amount = (int) $request->input('amount', 0);
        $to = $request->input('to', 'Unknown');

        $balance = session('csrf_balance', 10000000);

        if ($amount > 0 && $amount <= $balance) {
            $balance -= $amount;
            session(['csrf_balance' => $balance]);

            $transfers = session('csrf_transfers', []);
            $transfers[] = [
                'to' => $to,
                'amount' => $amount,
                'balance_after' => $balance,
                'source' => 'protected_endpoint',
                'time' => now()->format('H:i:s'),
                'status' => 'success',
                'info' => 'Transfer via protected endpoint (CSRF valid)',
            ];
            session(['csrf_transfers' => $transfers]);

            return redirect()
                ->route('csrf-lab.attack-demo')
                ->with('success', "Transfer Rp " . number_format($amount) . " berhasil!");
        }

        return redirect()
            ->route('csrf-lab.attack-demo')
            ->with('error', 'Transfer gagal.');
    }

    public function secureTransfer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'to' => 'required|string|max:100',
        ]);

        $amount = $validated['amount'];
        $to = $validated['to'];

        $balance = session('csrf_balance', 10000000);

        if ($amount <= $balance) {
            $balance -= $amount;
            session(['csrf_balance' => $balance]);

            $transfers = session('csrf_transfers', []);
            $transfers[] = [
                'to' => $to,
                'amount' => $amount,
                'balance_after' => $balance,
                'source' => 'secure_form',
                'time' => now()->format('H:i:s'),
                'status' => 'success',
                'info' => 'Transfer DENGAN validasi CSRF ✓',
            ];
            session(['csrf_transfers' => $transfers]);

            return redirect()
                ->route('csrf-lab.attack-demo')
                ->with('success', "✅ Transfer Rp " . number_format($amount) . " ke {$to} berhasil dengan CSRF protection!");
        }

        return redirect()
            ->route('csrf-lab.attack-demo')
            ->with('error', 'Transfer gagal: saldo tidak cukup.');
    }

    public function protectedAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action_type' => 'required|in:update_profile,change_settings,delete_data',
            'description' => 'nullable|string|max:255',
        ]);

        $actions = session('csrf_protected_actions', []);
        $actions[] = [
            'type' => $validated['action_type'],
            'description' => $validated['description'] ?? '-',
            'time' => now()->format('H:i:s'),
            'status' => 'success',
            'csrf_valid' => true,
        ];
        session(['csrf_protected_actions' => $actions]);

        return redirect()
            ->route('csrf-lab.protection-demo')
            ->with('success', 'Aksi berhasil dijalankan dengan CSRF protection!');
    }

    public function ajaxAction(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $results = session('csrf_ajax_results', []);
        $results[] = [
            'message' => $validated['message'],
            'time' => now()->format('H:i:s'),
            'method' => 'AJAX with CSRF',
        ];
        session(['csrf_ajax_results' => $results]);

        return response()->json([
            'success' => true,
            'message' => 'Request berhasil! CSRF token valid.',
            'data' => $validated['message'],
            'time' => now()->format('H:i:s'),
        ]);
    }

    public function resetDemo(): RedirectResponse
    {
        session()->forget([
            'csrf_transfers',
            'csrf_balance',
            'csrf_protected_actions',
            'csrf_ajax_results',
        ]);

        return redirect()
            ->route('csrf-lab.index')
            ->with('success', 'Demo data berhasil direset!');
    }
}
