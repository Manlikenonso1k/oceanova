<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Live Template</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8fafc; color: #0f172a; }
        h1 { margin: 0 0 8px; }
        p { margin: 0 0 16px; color: #475569; }
        .actions { display: flex; gap: 10px; margin-bottom: 16px; }
        .btn { border: 1px solid #cbd5e1; background: #fff; color: #0f172a; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 14px; }
        .btn-primary { background: #2563eb; color: #fff; border-color: #2563eb; }
        .table-wrap { overflow: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; min-width: 980px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 10px; font-size: 14px; text-align: left; }
        th { background: #f1f5f9; position: sticky; top: 0; }
        tr:last-child td { border-bottom: none; }
        input { width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 8px; font-size: 13px; }
    </style>
</head>
<body>
    <h1>Procurement Live Template</h1>
    <p>This template is generated from your current ingredients database. Fill rows and click <strong>Save to Database</strong> to post entries directly. Enter <strong>Amount</strong> as the total for that row (not quantity × price).</p>

    @if (session('success'))
        <div style="margin: 0 0 16px; padding: 10px 12px; border-radius: 8px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="margin: 0 0 16px; padding: 10px 12px; border-radius: 8px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
            {{ session('error') }}
        </div>
    @endif

    <div class="actions">
        <button class="btn" onclick="window.print()">Print / Save as PDF</button>
        <a class="btn" href="{{ route('filament.admin.resources.procurements.index') }}" target="_blank">Open Procurements</a>
    </div>

    <form method="POST" action="{{ route('procurements.live-template.store') }}">
        @csrf
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ingredient_id</th>
                        <th>ingredient_name</th>
                        <th>category</th>
                        <th>unit</th>
                        <th>quantity_received</th>
                        <th>unit_price</th>
                        <th>amount_total</th>
                        <th>supplier_name</th>
                        <th>status</th>
                        <th>received_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredients as $ingredient)
                        <tr>
                            <td>
                                {{ $ingredient->id }}
                                <input type="hidden" name="rows[{{ $loop->index }}][ingredient_id]" value="{{ $ingredient->id }}">
                            </td>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->category }}</td>
                            <td>{{ $ingredient->unit }}</td>
                            <td><input type="text" name="rows[{{ $loop->index }}][quantity_received]" placeholder="e.g. 5"></td>
                            <td><input type="text" name="rows[{{ $loop->index }}][unit_price]" placeholder="e.g. 6000"></td>
                            <td><input type="text" name="rows[{{ $loop->index }}][amount_total]" placeholder="e.g. 12000"></td>
                            <td><input type="text" name="rows[{{ $loop->index }}][supplier_name]" placeholder="Supplier"></td>
                            <td><input type="text" name="rows[{{ $loop->index }}][status]" value="completed"></td>
                            <td><input type="text" name="rows[{{ $loop->index }}][received_at]" value="{{ $today }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="actions" style="margin-top: 12px;">
            <button type="submit" class="btn btn-primary">Save to Database</button>
        </div>
    </form>
</body>
</html>
