<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1a1a1a; font-size: 14px; line-height: 1.6; margin: 0; padding: 0; background: #f6f6f6; }
  .wrap { max-width: 540px; margin: 0 auto; padding: 32px 20px; }
  .card { background: #ffffff; border-radius: 12px; padding: 28px 32px; border: 1px solid #e5e5e5; }
  .brand { font-size: 12px; font-weight: bold; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
  h1 { font-size: 19px; margin: 0 0 18px; }
  table { width: 100%; border-collapse: collapse; }
  td { padding: 8px 0; border-bottom: 1px solid #eee; font-size: 13.5px; vertical-align: top; }
  td.label { width: 120px; color: #888; }
  .total { font-size: 17px; font-weight: bold; color: #1d4ed8; }
  .footer { text-align: center; font-size: 11px; color: #999; margin-top: 20px; }
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="brand">Perfect Kilimanjaro</div>
      <h1>New instant quote — {{ $quote->reference }}</h1>

      <table>
        <tr><td class="label">Customer</td><td>{{ $quote->customer_name }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $quote->customer_email }}</td></tr>
        <tr><td class="label">Phone</td><td>{{ $quote->customer_phone ?: '—' }}</td></tr>
        <tr><td class="label">Country</td><td>{{ $quote->customer_country ?: '—' }}</td></tr>
        <tr><td class="label">Trip</td><td>{{ $quote->package?->name ?: 'Not specified' }}</td></tr>
        <tr><td class="label">Travel date</td><td>{{ $quote->travel_start?->format('j M Y') ?: 'Not specified' }}</td></tr>
        <tr><td class="label">Travelers</td><td>{{ $quote->adults }} adult(s)@if ($quote->children), {{ $quote->children }} child(ren)@endif</td></tr>
        <tr><td class="label">Total</td><td class="total">{{ $quote->money($quote->total()) }}</td></tr>
      </table>
    </div>
    <div class="footer">The quote PDF is attached. Reply directly to this email to reach {{ $quote->customer_email }}.</div>
  </div>
</body>
</html>
