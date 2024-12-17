@extends('layout')
@section('content')
<div class="container py-4">
  <h1 class="mb-4">Créer une Facture</h1>
  <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
    @csrf
    <div class="form-group mb-3">
      <label for="client_name" class="form-label">Nom du client</label>
      <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Entrez le nom du client" required>
    </div>
    <div class="form-group mb-3">
      <label for="amount" class="form-label">Montant</label>
      <input type="number" id="amount" name="amount" class="form-control" placeholder="Entrez le montant" required>
    </div>
    <div class="form-group mb-3">
      <label for="status" class="form-label">Statut</label>
      <select id="status" name="status" class="form-select">
        <option value="unpaid">Non payé</option>
        <option value="paid">Payé</option>
        <option value="canceled">Annulé</option>
      </select>
    </div>
    <div class="form-group mb-3">
      <label for="file" class="form-label">Justificatif</label>
      <input type="file" id="file" name="file" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary w-100">Créer</button>
  </form>
</div>
@endsection
