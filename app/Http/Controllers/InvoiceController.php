<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Mail\InvoiceCreated; // Importer la classe Mail
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache; // Importer la facade Cache
use Illuminate\Support\Facades\Storage;



class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Cache::remember('invoices', 60, function () {
            return \App\Models\Invoice::paginate(10);
        });
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'in:unpaid,paid,canceled',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('invoices');
        }
        $invoice = \App\Models\Invoice::create($validated);
        // dd($request->all());

        Mail::to('yahyaelmhadder7@gmail.com')->send(new \App\Mail\InvoiceCreated($invoice));
        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès.');
    }

    public function edit($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        $validated = $request->validate([
            'client_name' => 'required|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'in:unpaid,paid,canceled',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);
        if ($request->hasFile('file')) {
            Storage::delete($invoice->file_path);
            $validated['file_path'] = $request->file('file')->store('invoices');
        }
        $invoice->update($validated);
        return redirect()->route('invoices.index')->with('success', 'Facture mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        if ($invoice->file_path) {
            Storage::delete($invoice->file_path);
        }
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Facture supprimée avec succès.');
    }

    public function view($id)
{
    $invoice = Invoice::findOrFail($id);

    if ($invoice->file_path && Storage::exists($invoice->file_path)) {
        return Storage::download($invoice->file_path);
    }

    return redirect()->route('invoices.index')->with('error', 'Fichier non trouvé.');
}

}
