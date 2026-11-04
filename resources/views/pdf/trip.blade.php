<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $trip['name'] }} · Perfect Kilimanjaro</title>
<style>
  @page { margin: 28px 36px; }
  body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1a1a1a; font-size: 12px; line-height: 1.5; }

  .header { border-bottom: 2px solid #1d4ed8; padding-bottom: 10px; margin-bottom: 18px; }
  .header .brand { font-size: 13px; font-weight: bold; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px; }
  .header h1 { font-size: 22px; margin: 6px 0 2px; }
  .header .sub { font-size: 12px; color: #555; }

  h2.section { font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin: 20px 0 10px; color: #1a1a1a; }

  table.facts { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
  table.facts td { padding: 5px 8px; border: 1px solid #e3e3e3; font-size: 11.5px; }
  table.facts td.label { width: 32%; color: #666; background: #faf7f3; }

  table.price { width: 100%; border-collapse: collapse; }
  table.price th, table.price td { padding: 6px 8px; border: 1px solid #e3e3e3; font-size: 11.5px; text-align: left; }
  table.price th { background: #1d4ed8; color: #fff; }

  ul.plain { margin: 0; padding-left: 16px; }
  ul.plain li { margin-bottom: 5px; }

  .cols { width: 100%; }
  .col { width: 48%; display: inline-block; vertical-align: top; }
  .col.left { margin-right: 2%; }

  .day { margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
  .day .day-title { font-weight: bold; font-size: 12.5px; margin-bottom: 3px; }
  .day .day-meta { color: #888; font-size: 10.5px; margin-bottom: 4px; }

  .quote { background: #faf7f3; border-left: 3px solid #1d4ed8; padding: 10px 14px; font-style: italic; margin: 14px 0; }

  .cover { width: 100%; height: 220px; object-fit: cover; margin-bottom: 14px; }

  .stay { margin-bottom: 14px; }
  .stay-photo { width: 150px; height: 100px; object-fit: cover; float: left; margin-right: 14px; }
  .stay-name { font-weight: bold; font-size: 12.5px; }
  .stay-meta { color: #888; font-size: 10.5px; margin-bottom: 4px; }
  .stay-note { font-size: 11.5px; }
  .clear { clear: both; }

  .gallery { width: 100%; }
  .gal-item { width: 31%; margin: 0 3% 10px 0; display: inline-block; vertical-align: top; }
  .gal-item img { width: 100%; height: 95px; object-fit: cover; }

  .why-item { margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
  .why-photo { width: 56px; height: 56px; object-fit: cover; float: left; margin-right: 12px; }
  .why-title { font-weight: bold; font-size: 12px; margin-bottom: 3px; }
  .why-text { font-size: 11px; color: #444; }

  .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; color: #888; text-align: center; }
</style>
</head>
<body>

  <div class="header">
    <div class="brand">Perfect Kilimanjaro</div>
    <h1>{{ $trip['name'] }}</h1>
    <div class="sub">
      @if (filled($trip['nickname'])) {{ $trip['nickname'] }} &middot; @endif
      {{ $trip['days'] }} Days
      @if (filled($trip['location'])) &middot; {{ $trip['location'] }} @endif
    </div>
  </div>

  @if (filled($trip['image'] ?? null))
    <img class="cover" src="{{ public_path($trip['image']) }}" alt="{{ $trip['name'] }}">
  @endif

  @if (filled($trip['quote']))
    <div class="quote">{{ $trip['quote'] }}</div>
  @endif

  @if (filled($trip['overview'] ?? $trip['blurb'] ?? null))
    <p>{{ $trip['overview'] ?? $trip['blurb'] }}</p>
  @endif

  <h2 class="section">Trip facts</h2>
  <table class="facts">
    <tr><td class="label">Duration</td><td>{{ $trip['days'] }} Days &middot; {{ $trip['days'] - 1 }} Nights</td></tr>
    @if (filled($trip['group'])) <tr><td class="label">Group size</td><td>{{ $trip['group'] }}</td></tr> @endif
    @if (filled($trip['difficulty'])) <tr><td class="label">Difficulty</td><td>{{ $trip['difficulty'] }}</td></tr> @endif
    @if (filled($trip['best_time'])) <tr><td class="label">Best time</td><td>{{ $trip['best_time'] }}</td></tr> @endif
    @if (filled($trip['start_point'])) <tr><td class="label">Starts &amp; ends</td><td>{{ $trip['start_point'] }}</td></tr> @endif
    @if (filled($trip['accommodation'])) <tr><td class="label">Accommodation</td><td>{{ $trip['accommodation'] }}</td></tr> @endif
  </table>

  @if (filled($trip['price_tiers'] ?? null))
    <h2 class="section">Pricing (per person)</h2>
    <table class="price">
      <tr><th>Group size</th><th>Total (USD)</th></tr>
      @foreach ($trip['price_tiers'] as $tier)
        <tr><td>{{ $tier['label'] }}</td><td>${{ number_format($tier['price']) }}</td></tr>
      @endforeach
    </table>
  @elseif (filled($trip['price'] ?? null))
    <h2 class="section">Pricing</h2>
    <p>From <strong>${{ number_format($trip['price']) }}</strong> per person.</p>
  @endif

  @if (filled($trip['highlights'] ?? null))
    <h2 class="section">Highlights</h2>
    <ul class="plain">
      @foreach ($trip['highlights'] as $item)
        <li>{{ $item }}</li>
      @endforeach
    </ul>
  @endif

  @if (filled($trip['itinerary'] ?? null))
    <h2 class="section">Day-by-day itinerary</h2>
    @foreach ($trip['itinerary'] as $i => $day)
      <div class="day">
        <div class="day-title">Day {{ $i + 1 }}: {{ $day['t'] }}</div>
        @if (filled($day['elev'] ?? null) || filled($day['distance'] ?? null) || filled($day['time'] ?? null))
          <div class="day-meta">
            {{ collect([$day['elev'] ?? null, $day['distance'] ?? null, $day['time'] ?? null])->filter()->implode(' · ') }}
          </div>
        @endif
        <div>{{ $day['d'] }}</div>
      </div>
    @endforeach
  @endif

  @if (filled($trip['stays'] ?? null))
    <h2 class="section">Where you'll stay</h2>
    @foreach ($trip['stays'] as $stay)
      <div class="stay">
        @if (filled($stay['image'] ?? null))
          <img class="stay-photo" src="{{ public_path($stay['image']) }}" alt="{{ $stay['name'] }}">
        @endif
        <div class="stay-name">{{ $stay['name'] }}</div>
        <div class="stay-meta">
          {{ collect([$stay['class'] ?? null, $stay['place'] ?? null, filled($stay['stars'] ?? null) ? $stay['stars'].'-star' : null])->filter()->implode(' · ') }}
        </div>
        @if (filled($stay['note'] ?? null))
          <div class="stay-note">{{ $stay['note'] }}</div>
        @endif
      </div>
      <div class="clear"></div>
    @endforeach
  @endif

  @if (filled($trip['gallery'] ?? null))
    <h2 class="section">Gallery</h2>
    <div class="gallery">
      @foreach (array_slice($trip['gallery'], 0, 9) as $i => $g)
        <div class="gal-item">
          <img src="{{ public_path($g) }}" alt="{{ $trip['name'] }} photo {{ $i + 1 }}">
        </div>
      @endforeach
    </div>
  @endif

  @if (filled($trip['included'] ?? null) || filled($trip['excluded'] ?? null))
    <h2 class="section">What's included</h2>
    <div class="cols">
      <div class="col left">
        @if (filled($trip['included'] ?? null))
          <ul class="plain">
            @foreach ($trip['included'] as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        @endif
      </div>
      <div class="col">
        @if (filled($trip['excluded'] ?? null))
          <ul class="plain">
            @foreach ($trip['excluded'] as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  @endif

  @if (filled($home->why_items ?? null))
    <h2 class="section">{{ strip_tags($home->why_title ?: 'Why Perfect Kilimanjaro') }}</h2>
    @foreach ($home->why_items as $item)
      <div class="why-item">
        @if (filled($item['image'] ?? null))
          <img class="why-photo" src="{{ public_path($item['image']) }}" alt="{{ $item['title'] }}">
        @endif
        <div class="why-title">{{ $item['title'] }}</div>
        <div class="why-text">{{ $item['text'] }}</div>
      </div>
      <div class="clear"></div>
    @endforeach
  @endif

  <div class="footer">
    perfectkilimanjaro.com &middot; WhatsApp +255 752 967 222 &middot; Generated {{ now()->format('d M Y') }}
  </div>

</body>
</html>
