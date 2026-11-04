<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Quote {{ $quote->reference }} · Perfect Kilimanjaro</title>
<style>
  @page { margin: 28px 36px; }
  body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a1a; font-size: 11.5px; line-height: 1.5; }

  .header { width: 100%; border-bottom: 2px solid #1d4ed8; padding-bottom: 10px; margin-bottom: 18px; }
  .header .logo { width: 44px; height: 44px; float: left; margin-right: 14px; }
  .header .brand { font-size: 13px; font-weight: bold; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px; }
  .header h1 { font-size: 20px; margin: 4px 0 2px; }
  .header .sub { font-size: 11px; color: #555; }
  .header .ref-box { float: right; text-align: right; font-size: 11px; color: #555; }
  .header .ref-box b { display: block; font-size: 14px; color: #1a1a1a; }
  .clear { clear: both; }

  h2.section { font-size: 13px; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin: 18px 0 8px; color: #1a1a1a; }

  table.facts { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
  table.facts td { padding: 5px 8px; border: 1px solid #e3e3e3; font-size: 11px; }
  table.facts td.label { width: 32%; color: #666; background: #faf7f3; }

  table.items { width: 100%; border-collapse: collapse; }
  table.items th, table.items td { padding: 7px 8px; border: 1px solid #e3e3e3; font-size: 11px; text-align: left; }
  table.items th { background: #1d4ed8; color: #fff; }
  table.items td.num, table.items th.num { text-align: right; }

  table.totals { width: 260px; float: right; border-collapse: collapse; margin-top: 10px; }
  table.totals td { padding: 5px 8px; font-size: 11.5px; }
  table.totals td.num { text-align: right; }
  table.totals tr.grand td { border-top: 2px solid #1d4ed8; font-weight: bold; font-size: 13px; padding-top: 8px; }

  .payment-box { background: #faf7f3; border: 1px solid #e3e3e3; border-radius: 6px; padding: 12px 14px; margin-top: 10px; }
  .payment-box p { margin: 0 0 6px; }
  .bank { margin-bottom: 10px; }
  .bank b { display: block; font-size: 11.5px; }
  .bank span { color: #555; font-size: 10.5px; }

  .terms { font-size: 10px; color: #666; white-space: pre-line; line-height: 1.6; }

  .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9.5px; color: #888; text-align: center; }
</style>
</head>
<body>

  <div class="header">
    @if ($logoBase64)
      <img class="logo" src="data:image/png;base64,{{ $logoBase64 }}" alt="Perfect Kilimanjaro">
    @endif
    <div class="ref-box">
      <b>{{ $quote->reference }}</b>
      Date: {{ $quote->created_at->format('j M Y') }}<br>
      @if ($quote->valid_until) Valid until: {{ $quote->valid_until->format('j M Y') }} @endif
    </div>
    <div class="brand">Perfect Kilimanjaro</div>
    <h1>Trip Quotation</h1>
    <div class="sub">perfectkilimanjaro.com &middot; info@perfectkilimanjaro.com &middot; +255 752 967 222</div>
  </div>
  <div class="clear"></div>

  <h2 class="section">Customer</h2>
  <table class="facts">
    <tr><td class="label">Name</td><td>{{ $quote->customer_name }}</td></tr>
    <tr><td class="label">Email</td><td>{{ $quote->customer_email }}</td></tr>
    @if ($quote->customer_phone) <tr><td class="label">Phone</td><td>{{ $quote->customer_phone }}</td></tr> @endif
    @if ($quote->customer_country) <tr><td class="label">Country</td><td>{{ $quote->customer_country }}</td></tr> @endif
  </table>

  <h2 class="section">Trip</h2>
  <table class="facts">
    <tr><td class="label">Trip</td><td>{{ $quote->package?->name ?? 'Not specified' }}</td></tr>
    <tr>
      <td class="label">Travel dates</td>
      <td>
        @if ($quote->travel_start)
          {{ $quote->travel_start->format('j M Y') }}@if ($quote->travel_end) – {{ $quote->travel_end->format('j M Y') }} @endif
        @else
          To be confirmed
        @endif
      </td>
    </tr>
    <tr><td class="label">Travelers</td><td>{{ $quote->adults }} {{ Str::plural('adult', $quote->adults) }}@if ($quote->children), {{ $quote->children }} {{ Str::plural('child', $quote->children) }} (under 16)@endif</td></tr>
  </table>

  <h2 class="section">Cost breakdown</h2>
  <table class="items">
    <tr><th>Description</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Line total</th></tr>
    @foreach ($quote->items ?? [] as $item)
      <tr>
        <td>{{ $item['description'] }}</td>
        <td class="num">{{ $item['quantity'] }}</td>
        <td class="num">{{ $quote->money((float) $item['unit_price']) }}</td>
        <td class="num">{{ $quote->money((float) $item['quantity'] * (float) $item['unit_price']) }}</td>
      </tr>
    @endforeach
  </table>

  <table class="totals">
    <tr><td>Subtotal</td><td class="num">{{ $quote->money($quote->subtotal()) }}</td></tr>
    @if ($quote->discount > 0)
      <tr><td>Discount</td><td class="num">-{{ $quote->money((float) $quote->discount) }}</td></tr>
    @endif
    <tr class="grand"><td>Total</td><td class="num">{{ $quote->money($quote->total()) }}</td></tr>
  </table>
  <div class="clear"></div>

  @if ($quote->show_bank_details && (filled($bankAccounts) || filled($paymentInstructions)))
    <h2 class="section">Payment details</h2>
    <div class="payment-box">
      @foreach ($bankAccounts as $account)
        <div class="bank">
          <b>{{ $account['bank'] ?? '' }}</b>
          <span>
            {{ $account['account_name'] ?? '' }}
            @if (filled($account['account_number'] ?? null)) &middot; Acc #{{ $account['account_number'] }} @endif
            @if (filled($account['branch'] ?? null)) &middot; {{ $account['branch'] }} @endif
            @if (filled($account['swift'] ?? null)) &middot; SWIFT {{ $account['swift'] }} @endif
          </span>
        </div>
      @endforeach
      @if (filled($paymentInstructions))
        <p>{{ $paymentInstructions }}</p>
      @endif
    </div>
  @endif

  @if (filled($terms))
    <h2 class="section">Terms</h2>
    <div class="terms">{{ $terms }}</div>
  @endif

  <div class="footer">
    This quotation is valid until {{ $quote->valid_until?->format('j M Y') ?? 'the date shown above' }}. Generated {{ now()->format('j M Y') }}.
  </div>

</body>
</html>
