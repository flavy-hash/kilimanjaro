@extends('layouts.site')

@section('content')

<header class="hero" id="top">
  @if (filled($home->hero_video))
    <video class="hero-video" autoplay muted loop playsinline poster="{{ asset($home->hero_poster) }}">
      <source src="{{ asset($home->hero_video) }}" type="video/mp4">
    </video>
  @else
    <img class="hero-video" src="{{ asset($home->hero_poster) }}" alt="{{ $home->hero_headline }}">
  @endif
  <div class="hero-overlay"></div>

  <div class="hero-inner">
    <div id="heroContent"></div>
  </div>

  <div class="hero-stats">
    @foreach ($home->hero_stats as $stat)
      <div class="stat"><div class="stat-num">{{ $stat['num'] }}@if(!empty($stat['suffix']))<em>{{ $stat['suffix'] }}</em>@endif</div><div class="stat-label">{{ $stat['label'] }}</div></div>
    @endforeach
  </div>
</header>

<section id="intro">
  <div class="wrap">
    <div class="intro-split">
      <div class="intro-split-text">
        <div class="tag">{{ $home->intro_tag }}</div>
        <h2 class="section-title">{!! $home->intro_title !!}</h2>
        <div class="intro-copy">
          @foreach ($home->intro_paragraphs as $paragraph)
            <p>{!! $paragraph !!}</p>
          @endforeach
        </div>
        @if (filled($home->intro_cta_label))
          <a class="btn btn-fill" href="{{ route('tours') }}">
            {{ $home->intro_cta_label }}
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        @endif
      </div>
      <div class="intro-split-media">
        <img src="{{ asset($home->intro_image) }}" alt="{{ $home->intro_tag }}" loading="lazy">
      </div>
    </div>
  </div>
</section>

@if (filled($home->why_items))
<section id="why-us">
  <div class="wrap">
    <div class="tag">{{ $home->why_tag }}</div>
    <h2 class="section-title">{!! $home->why_title !!}</h2>
    <p class="section-lede">{{ $home->why_lede }}</p>

    @include('partials.why-grid', ['items' => $home->why_items])
  </div>
</section>
@endif

<section id="pillars">
  <div class="wrap">
    <div class="tag">{{ $home->pillars_tag }}</div>
    <h2 class="section-title">{!! $home->pillars_title !!}</h2>
    <p class="section-lede">{{ $home->pillars_lede }}</p>

    <div class="pillars-grid" id="pillarsGrid"></div>
  </div>
</section>

<section id="routes">
  <div class="wrap">
    <div class="tag">{{ $home->routes_tag }}</div>
    <h2 class="section-title">{!! $home->routes_title !!}</h2>
    <p class="section-lede">{{ $home->routes_lede }}</p>

    <div class="route-explorer">
      <div class="route-stats-row" id="routeStatsRow"></div>
      <div class="route-explorer-panel">
        <nav class="route-nav" id="routeNav" role="tablist" aria-label="Kilimanjaro routes"></nav>
        <div>
          <div class="route-map-card">
            <div class="route-map-legend">
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#0a0a0a" stroke-width="2" stroke-dasharray="3 3"/></svg>Ascent</span>
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#1d4ed8" stroke-width="2" stroke-dasharray="3 3"/></svg>Descent</span>
            </div>
            <svg id="kiliMap" viewBox="60 150 680 510" xmlns="http://www.w3.org/2000/svg"></svg>
          </div>

          <div class="route-desc-card">
            <h3>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
              <span id="routeDescTitle"></span>
            </h3>
            <div class="route-desc-meta" id="routeDescMeta"></div>
            <p id="routeDescText"></p>
            <a class="route-view" id="routeViewLink" href="#">
              View the full itinerary
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
          </div>
          <p class="route-note">Elevations and camp order are approximate — your booked itinerary is the one that governs.</p>
        </div>
      </div>
    </div>

    <div class="equip-banner">
      <div class="equip-glow"></div>
      <div class="equip-icon">
        <span class="equip-icon-label">PDF</span>
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 8V6a4 4 0 0 1 8 0v2M4 8h16l-1 13H5L4 8Z"/><path d="M9 12v4M15 12v4"/></svg>
      </div>
      <div class="equip-body">
        <h3>{{ $home->equip_title }}</h3>
        <p>{{ $home->equip_description }}</p>
        <a class="btn btn-fill equip-download" href="{{ asset($home->equip_pdf) }}" download>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v13m0 0-4-4m4 4 4-4M4 19h16"/></svg>
          Download checklist
        </a>
      </div>
    </div>
  </div>
</section>

<section id="trips">
  <div class="wrap">
    <div class="trips-head">
      <div>
        <div class="tag">{{ $home->trips_tag }}</div>
        <h2 class="section-title" style="margin-bottom:8px">{!! $home->trips_title !!}</h2>
        <p class="section-lede">{{ $home->trips_lede }}</p>
      </div>
      <div class="tabs" id="tabs"></div>
    </div>
    <div class="trips-grid" id="tripsGrid"></div>
    <div style="text-align:center;margin-top:40px">
      <a class="btn btn-fill" href="{{ route('tours') }}">
        Explore more
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </div>
  </div>
</section>

<section id="why" style="padding-top:0">
  <div class="wrap">
    <div class="trust-bar" id="trustBar"></div>
  </div>
</section>

<section id="reviews">
  <div class="wrap">
    <div class="tag">Field notes</div>
    <h2 class="section-title">What <em>travelers</em> say</h2>
    <div class="reviews-grid">
      @forelse ($featuredReviews as $review)
        @php($photos = $review->photoList())
        <div class="review">
          @if (filled($photos))
            <div class="review-photos">
              @foreach ($photos as $photo)
                <img src="{{ asset($photo) }}" alt="Photo from {{ $review->name }}'s trip" loading="lazy">
              @endforeach
            </div>
          @endif

          <div class="review-who">
            <div class="review-avatar">{{ $review->initials() }}</div>
            <div>
              <div class="review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
              <div class="review-meta">
                <strong>{{ $review->name }}</strong>
                @if (filled($review->country)) / {{ $review->country }} @endif
                @if ($review->stayed_at) &middot; {{ $review->stayed_at->format('M Y') }} @endif
              </div>
            </div>
          </div>

          <h3>{{ $review->title }}</h3>
          <p>{{ Str::limit($review->body, 140) }}</p>

          <div class="review-foot">
            <a href="{{ route('reviews.index') }}">Read Review</a>
            @if ($review->package)
              <a class="review-tag" href="{{ url($review->package->slug) }}">{{ $review->package->name }}</a>
            @endif
          </div>
        </div>
      @empty
        <p class="section-lede">Be the first to leave a review.</p>
      @endforelse
    </div>

    <a class="section-more-link" href="{{ route('reviews.index') }}">
      Read More Reviews
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>

    <div class="review-platforms">
      <a class="review-platform" href="{{ $home->tripadvisor_url ?: '#' }}" target="_blank" rel="noopener">
        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#34e0a1" stroke-width="1.5">
          <circle cx="12" cy="13.2" r="8.3"/><circle cx="8.3" cy="13.2" r="2.5"/><circle cx="15.7" cy="13.2" r="2.5"/>
          <circle cx="8.3" cy="13.2" r=".6" fill="#34e0a1"/><circle cx="15.7" cy="13.2" r=".6" fill="#34e0a1"/>
          <path d="M3.3 8.6h4.4M16.3 8.6h4.4M8.7 5c1.1-.7 2.2-1 3.3-1s2.2.3 3.3 1" stroke-linecap="round"/>
        </svg>
        <span class="review-platform-word" style="color:#34e0a1">tripadvisor</span>
        <span class="review-platform-caption">
          Read our reviews on Tripadvisor
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
        </span>
      </a>
      <a class="review-platform" href="{{ $home->google_reviews_url ?: '#' }}" target="_blank" rel="noopener">
        <svg width="40" height="40" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.07-1.47-.22-2.12H12v3.85h6.6c-.13 1.08-.86 2.72-2.47 3.82l-.02.15 3.59 2.72.25.02c2.28-2.07 3.57-5.12 3.57-8.44"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.05 7.94-2.87l-3.78-2.87c-1.02.69-2.4 1.17-4.16 1.17-3.18 0-5.88-2.07-6.84-4.92l-.14.01-3.73 2.84-.05.13C3.23 21.3 7.3 24 12 24"/><path fill="#FBBC05" d="M5.16 14.5a7.6 7.6 0 0 1-.42-2.5c0-.87.16-1.72.41-2.5l-.01-.17L1.36 6.44l-.12.06A11.9 11.9 0 0 0 0 12c0 1.93.48 3.76 1.24 5.5z"/><path fill="#EB4335" d="M12 4.75c2.26 0 3.78.94 4.65 1.73l3.4-3.25C17.94 1.2 15.24 0 12 0 7.3 0 3.23 2.7 1.24 6.5l3.91 3c.97-2.85 3.67-4.75 6.85-4.75"/></svg>
        <span class="review-platform-word">
          <span style="color:#4285F4">G</span><span style="color:#EA4335">o</span><span style="color:#FBBC05">o</span><span style="color:#4285F4">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span>
          <span style="color:var(--dim)">Reviews</span>
        </span>
        <span class="review-platform-caption">
          Read our reviews on Google
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
        </span>
      </a>
    </div>
  </div>
</section>

<section id="activities">
  <div class="wrap">
    <div class="tag">{{ $home->activities_tag }}</div>
    <h2 class="section-title">{!! $home->activities_title !!}</h2>
    <p class="section-lede">{{ $home->activities_lede }}</p>

    <div class="activity-rows">
      @foreach ($home->activities as $activity)
        <div class="activity-row">
          <div class="activity-media">
            <img src="{{ asset($activity['image']) }}" alt="{{ $activity['title'] }}" loading="lazy">
            <div class="trip-tag-overlay">{{ $activity['tag'] }}</div>
          </div>
          <div class="activity-text">
            <div class="activity-eyebrow">{{ $activity['eyebrow'] }}</div>
            <h3>{{ $activity['title'] }}</h3>
            <p>{{ $activity['text'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@if (filled($home->video_youtube_id))
<section id="video">
  <div class="wrap">
    <div class="tag">{{ $home->video_tag }}</div>
    <h2 class="section-title">{!! $home->video_title !!}</h2>

    <div class="video-card" id="videoCard" data-yt="{{ $home->video_youtube_id }}" onclick="playPageVideo()">
      <img class="video-thumb" src="https://img.youtube.com/vi/{{ $home->video_youtube_id }}/maxresdefault.jpg" alt="{{ strip_tags($home->video_title) }}" loading="lazy">
      <div class="video-scrim"></div>
      <div class="video-channel">
        <div class="brand-mark">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="#ffffff"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
        </div>
        <div class="video-channel-text">
          <strong class="notranslate">Perfect Kilimanjaro</strong>
          <span>Tanzania Safaris, Kilimanjaro &amp; Zanzibar</span>
        </div>
      </div>
      <button class="video-play" aria-label="Play video">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
      </button>
      <span class="video-yt-badge">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.5v-7l6.3 3.5-6.3 3.5Z"/></svg>
        Watch on YouTube
      </span>
    </div>
  </div>
</section>
@endif

<section id="about">
  <div class="wrap">
    <div class="tag">{{ $home->about_tag }}</div>
    <h2 class="section-title">{!! $home->about_title !!}</h2>
    <p class="section-lede">{{ $home->about_lede }}</p>

    <div class="home-about">
      <div class="home-about-cards">
        <div class="home-about-card is-story">
          <div class="home-about-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z"/><path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"/></svg>
          </div>
          <h3>{{ $home->about_story_title }}</h3>
          <p>{{ $home->about_story_text }}</p>
        </div>
        <div class="home-about-card is-team">
          <div class="home-about-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3>{{ $home->about_team_title }}</h3>
          <p>{{ $home->about_team_text }}</p>
        </div>
      </div>

      <div class="home-about-faq">
        <h3 class="home-about-faq-title">Common questions</h3>
        <div id="faq" class="faq-list">
          @foreach ($home->faqs as $faq)
            <div class="faq-item">
              <button class="faq-q" onclick="toggleFaq(this)">{{ $faq['question'] }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </button>
              <div class="faq-a"><p>{{ $faq['answer'] }}</p></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<div class="cta-band">
  <div>
    <h3>{!! $home->cta_title !!}</h3>
    <p>{{ $home->cta_text }}</p>
  </div>
  <div class="cta-band-right">
    <div class="cta-stat">
      <div class="cta-stat-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
      </div>
      <div class="cta-stat-txt"><strong>Free 20-min planning call</strong><span>Speak with a local guide</span></div>
    </div>
    <div class="cta-stat">
      <div class="cta-stat-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 10h16"/><path d="M10 4v16"/></svg>
      </div>
      <div class="cta-stat-txt"><strong>Day-by-day itinerary</strong><span>Sent within one business day</span></div>
    </div>
    <button class="btn btn-fill" style="justify-content:center;padding:16px" onclick="openBooking()">
      Start planning
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
  </div>
</div>

@endsection

@push('scripts')
@include('partials.kili-map-data')
<script>
const PILLARS = [
  { cat:"kilimanjaro", color:"var(--c-kili)", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>',
    title:"Kilimanjaro", desc:"Guided climbs up Africa's highest peak across five climate zones, on the route that fits your time and fitness.", from:"1,850" },
  { cat:"safari", color:"var(--c-safari)", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M4 20 9 9l3 5 2-3 4 9"/><circle cx="18" cy="5" r="2"/></svg>',
    title:"Safaris", desc:"Serengeti, Ngorongoro and the southern parks — tracked to where the wildlife actually is this week, not a fixed loop.", from:"400" },
  { cat:"zanzibar", color:"var(--c-zanzibar)", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c2-2 4-2 6 0s4 2 6 0 4-2 6 0M2 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><circle cx="12" cy="6" r="3"/></svg>',
    title:"Zanzibar", desc:"Stone Town, spice farms and dhow cruises, then days built for doing nothing at all on the coast.", from:"520" },
  { cat:"biking", color:"var(--c-biking)", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="M6 17 9 10h4l3 7M9 10l2-3h3"/></svg>',
    title:"Biking", desc:"Mountain bike through highland farms, villages and crater rims, with a support vehicle following the whole way.", from:"780" }
];

document.getElementById('pillarsGrid').innerHTML = PILLARS.map(p => `
  <div class="pillar" style="--pc:${p.color}" onclick="goTab('${p.cat}')">
    <div class="pillar-icon">${p.icon}</div>
    <h3>${p.title}</h3>
    <p>${p.desc}</p>
    <div class="pillar-foot">
      <div class="pillar-from">FROM <b>$${p.from}</b></div>
      <div class="pillar-arrow">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </div>
    </div>
  </div>
`).join('');

/* ---- Kilimanjaro route explorer ----
   An illustrated map, not a data-driven view: waypoint positions and each
   route's text are fixed reference content (approximate — see the on-page
   note), unlike the rest of the site which reads live from the database.
   "View the full itinerary" only appears for the three routes we actually
   guide, matched against real packages by slug. */
const KILI_MAP_ROUTES = TRIPS.filter(t => t.category === 'kilimanjaro');

const kiliMapEl = document.getElementById("kiliMap");
const kiliNavEl = document.getElementById("routeNav");
const kiliStatsRow = document.getElementById("routeStatsRow");

function renderKiliRoute(routeId){
  const route = KILI_ROUTE_DEFS.find(r => r.id === routeId);

  kiliNavEl.innerHTML = KILI_ROUTE_DEFS.map(r => `
    <button role="tab" aria-selected="${r.id === routeId}" data-id="${r.id}">
      <span class="dot"></span>${r.name}<span class="sub">${r.days}</span>
    </button>`).join('');
  kiliNavEl.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => renderKiliRoute(btn.dataset.id));
  });

  kiliStatsRow.innerHTML = `
    <div class="route-stat-pill">Duration<b>${route.days}</b></div>
    <div class="route-stat-pill">Difficulty<b style="color:${kiliDifficultyColor(route.difficulty)}">${route.difficulty}</b></div>
    <div class="route-stat-pill">Camps<b>${route.ascent.length + route.descent.length - 2}</b></div>`;

  document.getElementById('routeDescTitle').textContent = route.name;
  document.getElementById('routeDescMeta').textContent = `${route.days} · ${route.difficulty} difficulty · summits at Uhuru Peak, 5,895 m`;
  document.getElementById('routeDescText').textContent = route.text;

  // Only the routes we actually guide get a link through to a real package.
  const booked = KILI_MAP_ROUTES.find(t => t.id === route.id);
  const viewLink = document.getElementById('routeViewLink');
  if (booked) {
    viewLink.style.display = '';
    viewLink.href = '/' + booked.id;
  } else {
    viewLink.style.display = 'none';
  }

  kiliMapEl.innerHTML = kiliBuildMapSvg(route);
  kiliAnimateRoutePaths(kiliMapEl);
}
if (kiliMapEl) renderKiliRoute('machame');

document.getElementById('tabs').innerHTML = Object.entries(CATS).filter(([key]) => TRIPS.some(t => t.category === key)).map(([key,c]) => `
  <button class="tab" data-cat="${key}" onclick="setTab('${key}')">${c.label}</button>
`).join('');

function renderTrips(){
  document.querySelectorAll('.tab').forEach(el => el.dataset.active = el.dataset.cat === activeCat);
  document.querySelector('.trips-grid').parentElement.style.setProperty('--accent', CATS[activeCat].color);
  document.documentElement.style.setProperty('--accent', CATS[activeCat].color);

  document.getElementById('tripsGrid').innerHTML = TRIPS.filter(t => t.category === activeCat).slice(0, 3).map(t => `
    <a class="trip" data-id="${t.id}" href="/${t.id}" onmousemove="trackMouse(event,this)">
      <div class="trip-media">
        <img src="${t.image}" alt="${t.name}" loading="lazy">
        <span class="trip-tag-overlay">${CATS[t.category].label}</span>
        <span class="trip-rating-overlay">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
          ${t.rating}
        </span>
      </div>
      <div class="trip-body">
        <div class="trip-nick">${t.nickname}</div>
        <div class="trip-name">${t.name}</div>
        <div class="trip-metrics">
          <span class="metric">${t.days} days</span>
          <span class="metric">${t.tag}</span>
          <span class="metric accent">${t.rate}</span>
        </div>
        <p class="trip-blurb">${t.blurb}</p>
        <div class="trip-foot">
          <div>
            <div class="trip-price">$${t.price.toLocaleString()}<small>per person</small></div>
            <div class="trip-reviews">${t.reviews.toLocaleString()} reviews</div>
          </div>
          <span class="trip-view-btn">
            View
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </span>
        </div>
      </div>
    </a>
  `).join('');
}
renderTrips();

const TRUST = [
  { icon:'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>', title:"Tanzanian owned &amp; guided", desc:"Every guide and driver is local, not subcontracted from abroad." },
  { icon:'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>', title:"Fair wages for crew", desc:"Porters and guides paid above the industry standard, always." },
  { icon:'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>', title:"Reply within the hour", desc:"WhatsApp first — real answers from people who run the trips." },
  { icon:'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>', title:"No brochure markup", desc:"Priced directly off what the trip costs to run, not an agency margin." }
];
document.getElementById('trustBar').innerHTML = TRUST.map(t => `
  <div class="trust-item">${t.icon}<strong>${t.title}</strong><span>${t.desc}</span></div>
`).join('');

// The hero always features Kilimanjaro (the flagship trip) rather than
// rotating through categories — rating/price/reviews are read live from the
// real Machame package so they never drift out of sync with its own page.
const HERO = TRIPS.find(t => t.id === 'machame') || TRIPS.find(t => t.category === 'kilimanjaro') || TRIPS[0];

function renderHeroContent(){
  document.getElementById('heroContent').innerHTML = `
    <div class="hero-eyebrow">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
      {{ $home->hero_region }}
    </div>
    <h1>{{ $home->hero_headline }}</h1>
    <p class="hero-lede">{{ $home->hero_subtitle }}</p>
    <div class="hero-price-row">
      <div class="hero-rating">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        <strong>${HERO.rating}</strong><span class="count">· ${HERO.reviews.toLocaleString()} reviews</span>
      </div>
      <div class="hero-price">$${HERO.price.toLocaleString()}<small>from, per person</small></div>
    </div>
    <div class="hero-ctas">
      <button class="btn btn-fill" onclick="openBooking('${HERO.id}')">
        Book this trip
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
      <a class="hero-icon-btn" href="/${HERO.id}" aria-label="View trip details">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      </a>
    </div>
  `;
}
renderHeroContent();

const qp = new URLSearchParams(location.search);
if (qp.get('trip')) {
  setTimeout(() => jumpTrip(qp.get('tab') || TRIPS.find(t=>t.id===qp.get('trip'))?.category || 'safari', qp.get('trip')), 400);
} else if (qp.get('tab')) {
  setTab(qp.get('tab'));
}
</script>
@endpush
