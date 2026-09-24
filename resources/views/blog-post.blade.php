@extends('layouts.site')

@section('title', $post->title . ' · Perfect Kilimanjaro Blog')
@section('meta_description', Str::limit(strip_tags($post->excerpt ?? $post->body), 150))
@section('og_image', asset($post->cover_image))
@section('og_type', 'article')

@php
  $articleLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => Str::limit(strip_tags($post->excerpt ?? $post->body), 200),
    'image' => asset($post->cover_image),
    'datePublished' => $post->published_at?->toAtomString(),
    'dateModified' => $post->updated_at?->toAtomString(),
    'author' => ['@type' => 'Organization', 'name' => $post->author_name],
    'publisher' => ['@type' => 'Organization', 'name' => 'Perfect Kilimanjaro', 'logo' => ['@type' => 'ImageObject', 'url' => asset('apple-touch-icon.png')]],
    'mainEntityOfPage' => route('blog.show', $post->slug),
  ];
@endphp

@section('content')

<script type="application/ld+json">{!! json_encode($articleLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<header class="trip-hero" id="top" style="--pc:var(--c-kili)">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($post->cover_image) }}')"></div>
  <div class="trip-hero-overlay"></div>


  

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <a href="{{ route('blog.index') }}">Blog</a>
      <span class="sep">/</span>
      <span class="current">{{ $post->title }}</span>
    </nav>

    <h1>{{ $post->title }}</h1>
    <p class="hero-lede">{{ $post->author_name }} · {{ $post->published_at?->format('M j, Y') }} · {{ $post->readingMinutes() }} min read</p>
  </div>
</header>

<section>
  <div class="wrap" style="max-width:820px">
    <div class="article-body">
      {!! $post->body !!}
    </div>
  </div>
</section>

@if ($related->isNotEmpty())
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">More field notes</div>
    <h2 class="section-title" style="margin-bottom:20px">Read <em>next</em></h2>
    <div class="trips-grid">
      @foreach ($related as $r)
        <a class="trip" href="{{ route('blog.show', $r['slug']) }}">
          <div class="trip-media">
            <img src="{{ asset($r['cover_image']) }}" alt="{{ $r['title'] }}" loading="lazy">
            <span class="trip-tag-overlay">{{ $r['published_at'] }}</span>
          </div>
          <div class="trip-body">
            <div class="trip-nick">{{ $r['author_name'] }}</div>
            <div class="trip-name">{{ $r['title'] }}</div>
            <p class="trip-blurb">{{ $r['excerpt'] }}</p>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
