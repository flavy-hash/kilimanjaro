@extends('layouts.site')

@section('title', 'Blog · Perfect Kilimanjaro')
@section('meta_description', 'Field notes on climbing Kilimanjaro, safari planning and Zanzibar, written by the team that runs the trips.')

@section('content')

<header class="reviews-hero" id="top">
  <div class="reviews-hero-inner">
    <div>
      <div class="tag">Field notes</div>
      <h1 class="section-title">The <em>Perfect Kilimanjaro</em> blog</h1>
      <p class="section-lede">Route breakdowns, packing advice and planning notes, written by the team that actually runs these trips.</p>
    </div>
  </div>
</header>

<section>
  <div class="wrap">
    @if ($posts->isEmpty())
      <p class="section-lede">New posts are on the way — check back soon.</p>
    @else
      <div class="trips-grid">
        @foreach ($posts as $post)
          <a class="trip" href="{{ route('blog.show', $post['slug']) }}">
            <div class="trip-media">
              <img src="{{ asset($post['cover_image']) }}" alt="{{ $post['title'] }}" loading="lazy">
              <span class="trip-tag-overlay">{{ $post['published_at'] }}</span>
            </div>
            <div class="trip-body">
              <div class="trip-nick">{{ $post['author_name'] }}</div>
              <div class="trip-name">{{ $post['title'] }}</div>
              <p class="trip-blurb">{{ $post['excerpt'] }}</p>
              <div class="trip-metrics">
                <span class="metric">{{ $post['reading_minutes'] }} min read</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      <div class="pagination-wrap" style="margin-top:48px">
        {{ $posts->links() }}
      </div>
    @endif
  </div>
</section>

@endsection
