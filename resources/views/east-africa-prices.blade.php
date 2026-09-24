@extends('layouts.site')

@section('title', 'Kilimanjaro Prices for East African Citizens (TZS) · Perfect Kilimanjaro')
@section('meta_description', Str::limit(strip_tags($page->intro ?? $page->hero_lede), 150))
@section('og_image', asset($page->hero_image ?: 'images/hero-kilimanjaro.jpg'))

@php
  $whatsappDigits = preg_replace('/\D+/', '', (string) $page->whatsapp);
@endphp

@section('content')

<header class="trip-hero" id="top" style="--pc:var(--c-kili)">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($page->hero_image ?: 'images/hero-kilimanjaro.jpg') }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <a href="{{ route('tours') }}?category=kilimanjaro">Kilimanjaro</a>
      <span class="sep">/</span>
      <span class="current">East African Citizens</span>
    </nav>

    <h1>{{ $page->hero_title }}</h1>
    @if (filled($page->hero_lede))
      <p class="hero-lede">{{ $page->hero_lede }}</p>
    @endif
  </div>
</header>

<section>
  <div class="wrap">
    <div class="article" style="--pc:var(--c-kili)">

      @if (filled($page->intro))
      <div class="art-section" style="max-width:860px">
        <p>{{ $page->intro }}</p>
      </div>
      @endif

      {{-- Why East African citizens climb with us --}}
      @if (filled($page->citizens_paragraphs) || filled($page->reasons))
      <div class="art-section" style="max-width:860px">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>
          {{ $page->citizens_title ?: 'Why East African citizens climb with us' }}
        </h2>
        @foreach ($page->citizens_paragraphs ?? [] as $para)
          <p>{{ $para }}</p>
        @endforeach

        @if (filled($page->reasons))
          <div class="compare-list">
            @foreach ($page->reasons as $reason)
              <div class="compare-item">
                <div>
                  <div class="compare-title">{{ $reason['title'] }}</div>
                  <p>{{ $reason['text'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
      @endif

      {{-- Why choose us --}}
      @if (filled($page->why_intro) || filled($page->why_points))
      <div class="art-section" style="max-width:860px">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>
          {{ $page->why_title ?: 'Why choose Perfect Kilimanjaro?' }}
        </h2>
        @if (filled($page->why_intro))
          <p>{{ $page->why_intro }}</p>
        @endif
        @if (filled($page->why_points))
          <div class="hl-list" style="margin-top:20px">
            @foreach ($page->why_points as $point)
              <div class="hl-item">
                <span class="hl-icon">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </span>
                <span>{{ $point }}</span>
              </div>
            @endforeach
          </div>
        @endif
      </div>
      @endif

      {{-- Prices by route --}}
      @if (filled($page->routes))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
          Prices by route
        </h2>
        <div class="tzs-grid">
          @foreach ($page->routes as $r)
            <div class="tzs-card">
              <div class="tzs-card-head">
                <div>
                  <div class="tzs-route">{{ $r['route'] }}</div>
                  <div class="tzs-days">{{ $r['days'] }} Days</div>
                </div>
                @if (filled($r['slug'] ?? null) && in_array($r['slug'], $liveSlugs, true))
                  <a class="tzs-link" href="{{ url($r['slug']) }}">
                    Itinerary
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                  </a>
                @endif
              </div>
              <table class="price-table tzs-table">
                <thead>
                  <tr><th>Group size</th><th>Price per person (TZS)</th></tr>
                </thead>
                <tbody>
                  @foreach ($groups as $key => $label)
                    <tr><td>{{ $label }}</td><td><strong>{{ number_format((int) ($r[$key] ?? 0)) }}</strong></td></tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Includes / excludes --}}
      @if (filled($page->included) || filled($page->excluded))
      <div class="art-section">
        <div class="incl-grid">
          <div class="incl-col incl-yes">
            <h3>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
              Price includes
            </h3>
            <ul>
              @foreach ($page->included ?? [] as $item)<li>{{ $item }}</li>@endforeach
            </ul>
          </div>
          <div class="incl-col incl-no">
            <h3>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
              Price excludes
            </h3>
            <ul>
              @foreach ($page->excluded ?? [] as $item)<li>{{ $item }}</li>@endforeach
            </ul>
          </div>
        </div>
      </div>
      @endif

      {{-- Contact --}}
      <div class="art-section">
        <div class="trip-cta-band">
          <h3>{{ $page->cta_title ?: 'Climb Kilimanjaro with confidence' }}</h3>
          @if (filled($page->cta_text))
            <p>{{ $page->cta_text }}</p>
          @endif
          <div class="btn-row">
            @if ($whatsappDigits !== '')
              <a class="btn btn-fill" href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener">
                WhatsApp {{ $page->whatsapp }}
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </a>
            @endif
            @if (filled($page->email))
              <a class="btn btn-line" href="mailto:{{ $page->email }}">{{ $page->email }}</a>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
