@extends('layouts.site')

@section('title', 'Our Team · Perfect Kilimanjaro')
@section('meta_description', Str::limit(strip_tags($about->team_hero_lede), 150))

@section('content')

<header class="reviews-hero" id="top">
  <div class="reviews-hero-inner">
    <div>
      <div class="tag">{{ $about->team_hero_tag }}</div>
      <h1 class="section-title">{!! $about->team_hero_title !!}</h1>
      <p class="section-lede">{{ $about->team_hero_lede }}</p>
    </div>
    <a class="btn btn-fill" href="{{ route('about') }}">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      About us
    </a>
  </div>
</header>

<section>
  <div class="wrap">
    @forelse ($about->team_members as $member)
      @if ($loop->first)
        <div class="team-grid">
      @endif

      <div class="team-card">
        @if (!empty($member['photo']))
          <img class="team-photo" src="{{ asset($member['photo']) }}" alt="{{ $member['name'] }}" loading="lazy">
        @else
          @php
            $initials = collect(explode(' ', trim($member['name'])))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
          @endphp
          <div class="team-photo team-photo-fallback">{{ strtoupper($initials) }}</div>
        @endif
        <div class="team-name">{{ $member['name'] }}</div>
        <div class="team-role">{{ $member['role'] }}</div>
        @if (!empty($member['bio']))
          <p class="team-bio">{{ $member['bio'] }}</p>
        @endif
      </div>

      @if ($loop->last)
        </div>
      @endif
    @empty
      <p class="section-lede">Team profiles are coming soon.</p>
    @endforelse
  </div>
</section>

<div class="cta-band">
  <div>
    <h3>{!! $about->team_cta_title !!}</h3>
    <p>{{ $about->team_cta_text }}</p>
  </div>
  <div class="cta-band-right">
    <button class="btn btn-fill" style="justify-content:center;padding:16px" onclick="openBooking()">
      Start planning
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
  </div>
</div>

@endsection
