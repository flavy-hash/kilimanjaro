@extends('layouts.site')

@section('title', 'Climbing Preparation · Perfect Kilimanjaro')
@section('meta_description', Str::limit(strip_tags($prep->hero_lede), 150))
@section('og_image', asset($prep->hero_image))

@section('content')

<header class="trip-hero" id="top" style="--pc:var(--c-kili)">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($prep->hero_image) }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <span class="current">Climbing Preparation</span>
    </nav>

    <h1>{!! $prep->hero_title !!}</h1>
    <p class="hero-lede">{{ $prep->hero_lede }}</p>
  </div>
</header>

<section>
  <div class="wrap" style="max-width:900px">
    <div class="tag">{{ $prep->fitness_title }}</div>
    <h2 class="section-title">Train for <em>time on your feet</em></h2>
    <div class="intro-copy">
      @foreach ($prep->fitness_paragraphs as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>
  </div>
</section>

<section style="padding-top:0">
  <div class="wrap" style="max-width:900px">
    <div class="tag">{{ $prep->altitude_title }}</div>
    <h2 class="section-title">Respect the <em>altitude</em></h2>
    <div class="intro-copy">
      @foreach ($prep->altitude_paragraphs as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>
  </div>
</section>

@if (filled($prep->tips))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">Before you go</div>
    <h2 class="section-title" style="margin-bottom:40px">{{ $prep->tips_title }}</h2>
    <div class="trust-bar">
      @foreach ($prep->tips as $tip)
        <div class="trust-item">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <strong>{{ $tip['title'] }}</strong>
          <span>{{ $tip['text'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if (filled($equipPdf))
<section style="padding-top:0">
  <div class="wrap">
    <div class="equip-banner">
      <div class="equip-glow"></div>
      <div class="equip-icon">
        <span class="equip-icon-label">PDF</span>
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8V6a4 4 0 0 1 8 0v2M4 8h16l-1 13H5L4 8Z"/></svg>
      </div>
      <div class="equip-body">
        <h3>Kilimanjaro equipment list</h3>
        <p>The full packing list, layer by layer, from rainforest trailhead to summit night.</p>
        <a class="btn btn-fill equip-download" href="{{ asset($equipPdf) }}" download>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v13m0 0-4-4m4 4 4-4M4 19h16"/></svg>
          Download checklist
        </a>
      </div>
    </div>
  </div>
</section>
@endif

@if (filled($prep->faqs))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">Questions</div>
    <h2 class="section-title" style="margin-bottom:32px">Climbing <em>FAQ</em></h2>
    <div class="faq-list">
      @foreach ($prep->faqs as $faq)
        <div class="faq-item">
          <button class="faq-q" onclick="toggleFaq(this)">{{ $faq['question'] }}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="faq-a"><p>{{ $faq['answer'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<div class="cta-band">
  <div>
    <h3>{!! $prep->cta_title !!}</h3>
    <p>{{ $prep->cta_text }}</p>
  </div>
  <div class="cta-band-right">
    <button class="btn btn-fill" style="justify-content:center;padding:16px" onclick="openBooking()">
      Talk to us
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
  </div>
</div>

@endsection
