@extends('layouts.site')

@section('title', $trip['name'] . ' · Safiri')
@section('meta_description', Str::limit($trip['blurb'], 150))

@php
  $catMeta = [
    'safari' => ['label' => 'Safaris', 'color' => 'var(--c-safari)', 'noun' => 'safari', 'adj' => 'safari'],
    'kilimanjaro' => ['label' => 'Kilimanjaro', 'color' => 'var(--c-kili)', 'noun' => 'climb', 'adj' => 'Kilimanjaro'],
    'zanzibar' => ['label' => 'Zanzibar', 'color' => 'var(--c-zanzibar)', 'noun' => 'trip', 'adj' => 'Zanzibar'],
  ][$trip['category']];
  $sectionWord = $catMeta['label'] === 'Safaris' ? 'Safari' : $catMeta['label'];
  $galleryUrls = array_map(fn ($g) => asset($g), $trip['gallery']);
@endphp

@section('content')

{{-- ── Hero ───────────────────────────────────────────── --}}
<header class="trip-hero" id="top" style="--pc:{{ $catMeta['color'] }}">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($trip['image']) }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <a href="{{ route('tours') }}">Tours</a>
      <span class="sep">/</span>
      <a href="{{ route('tours') }}?category={{ $trip['category'] }}">{{ $catMeta['label'] }}</a>
      <span class="sep">/</span>
      <span class="current">{{ $trip['name'] }}</span>
    </nav>

    <h1>{{ $trip['days'] }}-Day {{ $trip['name'] }}</h1>
    <p class="hero-lede">{{ $trip['blurb'] }}</p>

    <div class="trip-hero-badges">
      <span class="hero-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
        {{ $trip['location'] }}
      </span>
      <span class="hero-badge star">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        {{ $trip['rating'] }} · {{ number_format($trip['reviews']) }} reviews
      </span>
    </div>

    <div class="trip-hero-foot">
      <div class="trip-hero-price">
        ${{ number_format($trip['price']) }}
        <small>From, per person</small>
      </div>
      <button class="btn btn-fill" style="padding:16px 28px" onclick="openBooking('{{ $trip['id'] }}')">
        Book your {{ $catMeta['noun'] }}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </div>
  </div>
</header>

{{-- ── Article body ───────────────────────────────────── --}}
<section>
  <div class="wrap">
    <div class="article" style="--pc:{{ $catMeta['color'] }}">
     <div class="trip-layout">
      <div class="trip-main">

      {{-- Overview --}}
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/></svg>
          Overview
        </h2>
        <p>{{ $trip['overview'] }}</p>
      </div>

      {{-- Highlights --}}
      @if (filled($trip['highlights']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
          {{ $sectionWord }} highlights
        </h2>
        <div class="hl-list">
          @foreach ($trip['highlights'] as $h)
            <div class="hl-item">
              <span class="hl-icon">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m14.5 9.5-1.8 4.7-4.7 1.8 1.8-4.7z"/></svg>
              </span>
              <span>{{ $h }}</span>
            </div>
          @endforeach
        </div>
      </div>

      @endif

      {{-- Itinerary --}}
      @if (filled($trip['itinerary']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
          Detailed itinerary
        </h2>
        <div class="itin-list">
          @foreach ($trip['itinerary'] as $i => $day)
            <div class="itin-item{{ $i === 0 ? ' open' : '' }}">
              <button class="itin-head" onclick="toggleItin(this)" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                <span class="itin-num">{{ $i + 1 }}</span>
                <span class="itin-title">{{ $day['t'] }}</span>
                <svg class="itin-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </button>
              <div class="itin-body">
                <div class="itin-body-inner">
                  <div class="itin-content">
                    <p>{{ $day['d'] }}</p>
                    <div class="itin-meta">
                      @isset($day['elev'])
                        <span class="elev"><span class="k">Elevation</span>{{ $day['elev'] }}</span>
                      @endisset
                      <span><span class="k">Stay</span>{{ $day['stay'] }}</span>
                      <span><span class="k">Meals</span>{{ $day['meals'] }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      @endif

      {{-- Route map (Kilimanjaro routes only) --}}
      @if ($trip['category'] === 'kilimanjaro')
      <div class="art-section" id="tripRouteMapSection" style="display:none">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 20l-5.5-2.5V4L9 6.5m0 13.5 6-2.5m-6 2.5V6.5m6 11L20.5 20V6.5L15 4m0 13.5V4m0 0L9 6.5"/></svg>
          Route map
        </h2>
        <div class="route-explorer" style="margin-top:0">
          <div class="route-stats-row" id="tripRouteStats"></div>
          <div class="route-map-card">
            <div class="route-map-legend">
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#20261f" stroke-width="2" stroke-dasharray="3 3"/></svg>Ascent</span>
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#3f6b4a" stroke-width="2" stroke-dasharray="3 3"/></svg>Descent</span>
            </div>
            <svg id="tripKiliMap" viewBox="60 150 680 510" xmlns="http://www.w3.org/2000/svg"></svg>
          </div>
          <p class="route-note">Elevations and camp order are approximate — your booked itinerary is the one that governs.</p>
        </div>
      </div>
      @endif

      {{-- Where you'll stay --}}
      @if (filled($trip['stays']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M4 20V10l8-6 8 6v10M9 20v-6h6v6"/></svg>
          Where you'll stay
          @if (count($trip['stays']) > 1)
            <span class="stay-count">{{ count($trip['stays']) }} properties</span>
          @endif
        </h2>
        <div class="stay-list">
          @foreach ($trip['stays'] as $stay)
            <div class="stay-card">
              <div class="stay-photo">
                <img src="{{ asset($stay['image'] ?: $trip['image']) }}" alt="{{ $stay['name'] }}" loading="lazy">
                @if ($stay['class'])
                  <span class="trip-tag-overlay">{{ $stay['class'] }}</span>
                @endif
              </div>
              <div class="stay-body">
                <div class="stay-name">{{ $stay['name'] }}</div>
                <div class="stay-stars">
                  @for ($s = 1; $s <= 5; $s++)
                    <span class="{{ $s <= $stay['stars'] ? '' : 'off' }}">★</span>
                  @endfor
                </div>
                @if ($stay['place'] || $trip['accommodation'])
                  <div class="trip-metrics" style="margin-bottom:0">
                    @if ($stay['place'])<span class="metric accent">{{ $stay['place'] }}</span>@endif
                    @if ($trip['accommodation'])<span class="metric">{{ $trip['accommodation'] }}</span>@endif
                  </div>
                @endif
                @if ($stay['note'])
                  <p class="stay-note">{{ $stay['note'] }}</p>
                @endif
                @if (filled($stay['amenities']))
                  <div class="amen">
                    @foreach ($stay['amenities'] as $a)
                      <span>
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ $a }}
                      </span>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Gallery --}}
      @if (filled($trip['gallery']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          {{ $sectionWord }} gallery
        </h2>
        <div class="gallery-grid">
          @foreach ($trip['gallery'] as $i => $g)
            <div class="gal-item" onclick="openLightbox({{ Js::from($galleryUrls) }}, {{ $i }})">
              <img src="{{ asset($g) }}" alt="{{ $trip['name'] }} photo {{ $i + 1 }}" loading="lazy">
              <span class="gal-zoom">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M11 8v6M8 11h6"/></svg>
              </span>
            </div>
          @endforeach
        </div>
      </div>

      @endif

      {{-- Included / not included --}}
      @if (filled($trip['included']) || filled($trip['excluded']))
      <div class="art-section">
        <div class="incl-grid">
          <div class="incl-col incl-yes">
            <h3>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
              What's included
            </h3>
            <ul>
              @foreach ($trip['included'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
          <div class="incl-col incl-no">
            <h3>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
              What's not included
            </h3>
            <ul>
              @foreach ($trip['excluded'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>

      @endif

      {{-- Why book --}}
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>
          Why book with Perfect Kilimanjaro
        </h2>
        <div class="why-list">
          <div class="why-item">
            <div class="why-num">1</div>
            <div><strong>Tailor-made, not off the shelf</strong><span>Every itinerary is adjusted to your dates, pace and budget — this page is a starting point, not a fixed package.</span></div>
          </div>
          <div class="why-item">
            <div class="why-num">2</div>
            <div><strong>Tanzanian guides, on staff</strong><span>Local guides who have worked these parks and routes for years, employed by us rather than subcontracted per trip.</span></div>
          </div>
          <div class="why-item">
            <div class="why-num">3</div>
            <div><strong>Private departures</strong><span>Your vehicle and your group. We never add strangers to fill seats or combine bookings to cut our costs.</span></div>
          </div>
          <div class="why-item">
            <div class="why-num">4</div>
            <div><strong>Stays we've slept in</strong><span>Camps and lodges chosen from first-hand stays, not booked blind off a wholesale list.</span></div>
          </div>
          <div class="why-item">
            <div class="why-num">5</div>
            <div><strong>One thread, start to finish</strong><span>The same WhatsApp conversation from your first question to the day you fly home — usually answered within the hour.</span></div>
          </div>
        </div>
      </div>

      </div>{{-- /.trip-main --}}

      {{-- Booking card --}}
      <aside class="trip-aside">
        <div class="book-card">
          <div class="book-from">From</div>
          <div class="book-price">${{ number_format($trip['price']) }}</div>
          <p class="book-note">per person sharing, excluding international flights</p>

          <div class="spec-row">
            <span class="k">Duration</span>
            <span class="v">{{ $trip['days'] }} Days · {{ $trip['days'] - 1 }} Nights</span>
          </div>
          <div class="spec-row">
            <span class="k">Group size</span>
            <span class="v">{{ $trip['group'] }}</span>
          </div>
          <div class="spec-row">
            <span class="k">Difficulty</span>
            <span class="v">{{ $trip['difficulty'] }}</span>
          </div>
          <div class="spec-row">
            <span class="k">Best time</span>
            <span class="v">{{ $trip['best_time'] }}</span>
          </div>
          <div class="spec-row">
            <span class="k">Starts &amp; ends</span>
            <span class="v">{{ $trip['start_point'] }}</span>
          </div>

          <div class="book-actions">
            <button class="btn btn-fill" onclick="openBooking('{{ $trip['id'] }}')">
              Book this adventure
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
            <a class="btn btn-line" href="https://wa.me/255752967222" target="_blank" rel="noopener">Ask a question</a>
          </div>

          <p class="book-fine">No payment taken online — we confirm availability first.</p>
        </div>
      </aside>
     </div>{{-- /.trip-layout --}}

      {{-- Final CTA --}}
      <div class="art-section">
        <div class="trip-cta-band">
          <h3>Tell us your dates and group size</h3>
          <p>We'll confirm availability, send an exact per-person quote and a day-by-day plan within one business day. No deposit needed to get a quote.</p>
          <div class="btn-row">
            <button class="btn btn-fill" style="padding:16px 28px" onclick="openBooking('{{ $trip['id'] }}')">
              Book this {{ $catMeta['noun'] }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
            <a class="btn btn-line" href="https://wa.me/255752967222" target="_blank" rel="noopener">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.01 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.36 5.07L2 22l5.06-1.33A9.94 9.94 0 0 0 12.01 22C17.52 22 22 17.52 22 12S17.52 2 12.01 2zm5.5 14.14c-.23.65-1.14 1.19-1.87 1.35-.5.11-1.15.19-3.35-.72-2.82-1.17-4.63-4.03-4.77-4.22-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.23-.25.5-.32.67-.32h.48c.15 0 .36-.06.56.43l.79 1.9c.09.19.15.42.03.67-.12.25-.18.4-.36.6l-.45.52c-.14.14-.29.29-.13.57.15.28.68 1.12 1.46 1.81 1 .89 1.82 1.17 2.11 1.3.23.1.51.08.68-.1l.62-.72c.24-.28.5-.24.79-.14l1.77.83c.28.14.48.21.55.33.07.12.07.7-.16 1.35z"/></svg>
              Ask on WhatsApp
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── Related ────────────────────────────────────────── --}}
@if (count($related))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">More to explore</div>
    <h2 class="section-title" style="margin-bottom:20px">Other <em>{{ $catMeta['adj'] }}</em> trips</h2>
    <div class="trips-grid" style="--accent: {{ $catMeta['color'] }}">
      @foreach ($related as $r)
        <a class="trip" href="{{ url($r['id']) }}">
          <div class="trip-media">
            <img src="{{ asset($r['image']) }}" alt="{{ $r['name'] }}" loading="lazy">
            <span class="trip-tag-overlay">{{ $catMeta['label'] }}</span>
            <span class="trip-rating-overlay">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
              {{ $r['rating'] }}
            </span>
          </div>
          <div class="trip-body">
            <div class="trip-nick">{{ $r['nickname'] }}</div>
            <div class="trip-name">{{ $r['name'] }}</div>
            <div class="trip-metrics">
              <span class="metric">{{ $r['days'] }} days</span>
              <span class="metric">{{ $r['group'] }}</span>
              <span class="metric">{{ $r['difficulty'] }}</span>
            </div>
            <p class="trip-blurb">{{ $r['blurb'] }}</p>
            <div class="trip-foot">
              <div>
                <div class="trip-price">${{ number_format($r['price']) }}<small>per person</small></div>
                <div class="trip-reviews">{{ number_format($r['reviews']) }} reviews</div>
              </div>
              <span class="trip-view-btn">
                View
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($trip['category'] === 'kilimanjaro')
@push('scripts')
@include('partials.kili-map-data')
<script>
(function(){
  const mapEl = document.getElementById('tripKiliMap');
  const route = KILI_ROUTE_DEFS.find(r => r.id === @json($trip['id']));
  if (!mapEl || !route) return;

  document.getElementById('tripRouteMapSection').style.display = '';
  document.getElementById('tripRouteStats').innerHTML = `
    <div class="route-stat-pill">Duration<b>${route.days}</b></div>
    <div class="route-stat-pill">Difficulty<b style="color:${kiliDifficultyColor(route.difficulty)}">${route.difficulty}</b></div>
    <div class="route-stat-pill">Camps<b>${route.ascent.length + route.descent.length - 2}</b></div>`;

  mapEl.innerHTML = kiliBuildMapSvg(route);
  kiliAnimateRoutePaths(mapEl);
})();
</script>
@endpush
@endif

@endsection
