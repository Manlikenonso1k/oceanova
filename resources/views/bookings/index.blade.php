@extends('layouts.app')

@section('content')
<section class="ftco-section">
  <div class="container">
    <h2 class="mb-4">Bookings</h2>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>WhatsApp</th>
            <th>Table</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bookings as $b)
            <tr>
              <td>{{ $b->name }}</td>
              <td><a href="mailto:{{ $b->email }}">{{ $b->email }}</a></td>
              <td><a href="tel:{{ $b->whatsapp_number }}">{{ $b->whatsapp_number }}</a></td>
              <td>{{ $b->table_label ?? $b->table_id }}</td>
              <td>{{ optional($b->booking_date)->format('Y-m-d') }}</td>
              <td>{{ $b->booking_time }}</td>
              <td>{{ ucfirst($b->status) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $bookings->links() }}
    </div>
  </div>
</section>
@endsection
