@extends('layouts.site')

@section('title', 'About Us · Perfect Kilimanjaro')
@section('meta_description', Str::limit(strip_tags($about->about_hero_lede), 150))

@section('content')

<header class="trip-hero" id="top" style="--pc:var(--sun)">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($about->about_hero_image) }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <span class="current">About</span>
    </nav>

    <h1>{!! $about->about_hero_title !!}</h1>
    <p class="hero-lede">{{ $about->about_hero_lede }}</p>
  </div>
</header>

<section>
  <div class="wrap" style="max-width:900px">
    <div class="tag">{{ $about->story_title }}</div>
    <h2 class="section-title">{!! $about->story_heading !!}</h2>
    <div class="intro-copy">
      @foreach ($about->story_paragraphs as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>

    @if (filled($about->statements))
      <div class="about-grid">
        @foreach ($about->statements as $statement)
          <div class="about-block">
            <h3>{{ $statement['title'] }}</h3>
            <p>{{ $statement['text'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

@if (filled($about->values))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">{{ $about->values_tag }}</div>
    <h2 class="section-title" style="margin-bottom:40px">{!! $about->values_heading !!}</h2>
    <div class="trust-bar">
      @foreach ($about->values as $value)
        <div class="trust-item">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <strong>{{ $value['title'] }}</strong>
          <span>{{ $value['text'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<div class="cta-band">
  <div>
    <h3>{!! $about->about_cta_title !!}</h3>
    <p>{{ $about->about_cta_text }}</p>
  </div>
  <div class="cta-band-right">
    <a class="btn btn-fill" style="justify-content:center;padding:16px" href="{{ route('team') }}">
      Meet the team
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
  </div>
</div>

@endsection
