@extends('layouts.site')

@section('content')

<header class="hero" id="top">
  <div class="hero-slides" id="heroSlides"></div>
  <div class="hero-overlay"></div>

  <button class="hero-arrow prev" onclick="heroPrev()" aria-label="Previous destination">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
  </button>
  <button class="hero-arrow next" onclick="heroNext()" aria-label="Next destination">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
  </button>

  <div class="hero-inner">
    <div id="heroContent"></div>
    <div class="hero-dots" id="heroDots"></div>
  </div>

  <div class="hero-stats">
    @foreach ($home->hero_stats as $stat)
      <div class="stat"><div class="stat-num">{{ $stat['num'] }}@if(!empty($stat['suffix']))<em>{{ $stat['suffix'] }}</em>@endif</div><div class="stat-label">{{ $stat['label'] }}</div></div>
    @endforeach
  </div>
</header>

<section id="intro">
  <div class="wrap intro-wrap reveal">
    <div class="tag">{{ $home->intro_tag }}</div>
    <h2 class="section-title">{!! $home->intro_title !!}</h2>
    <div class="intro-copy">
      @foreach ($home->intro_paragraphs as $paragraph)
        <p>{!! $paragraph !!}</p>
      @endforeach
    </div>
  </div>
</section>

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
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#20261f" stroke-width="2" stroke-dasharray="3 3"/></svg>Ascent</span>
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#3f6b4a" stroke-width="2" stroke-dasharray="3 3"/></svg>Descent</span>
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
        <div class="review">
          <div class="review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
          <p>&quot;{{ $review->body }}&quot;</p>
          <div class="review-who">
            <div class="review-avatar">{{ $review->initials() }}</div>
            <div><strong>{{ $review->name }}</strong><span>{{ $review->package->name ?? 'Perfect Kilimanjaro' }}</span></div>
          </div>
        </div>
      @empty
        <p class="section-lede">Be the first to leave a review.</p>
      @endforelse
    </div>
    <a class="section-more-link" href="{{ route('reviews.index') }}">
      Read all reviews
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
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

<section id="about">
  <div class="wrap">
    <div class="tag">{{ $home->about_tag }}</div>
    <h2 class="section-title">{!! $home->about_title !!}</h2>
    <p class="section-lede">{{ $home->about_lede }}</p>

    <div class="about-grid">
      <div class="about-block">
        <h3>{{ $home->about_story_title }}</h3>
        <p>{{ $home->about_story_text }}</p>
      </div>
      <div class="about-block">
        <h3>{{ $home->about_team_title }}</h3>
        <p>{{ $home->about_team_text }}</p>
      </div>
    </div>

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
  { cat:"safari", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M4 20 9 9l3 5 2-3 4 9"/><circle cx="18" cy="5" r="2"/></svg>',
    title:"Safaris", desc:"Serengeti, Ngorongoro and the southern parks — tracked to where the wildlife actually is this week, not a fixed loop.", from:"400" },
  { cat:"kilimanjaro", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>',
    title:"Kilimanjaro", desc:"Guided climbs up Africa's highest peak across five climate zones, on the route that fits your time and fitness.", from:"1,850" },
  { cat:"zanzibar", icon:'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c2-2 4-2 6 0s4 2 6 0 4-2 6 0M2 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><circle cx="12" cy="6" r="3"/></svg>',
    title:"Zanzibar", desc:"Stone Town, spice farms and dhow cruises, then days built for doing nothing at all on the coast.", from:"520" }
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

document.getElementById('tabs').innerHTML = Object.entries(CATS).map(([key,c]) => `
  <button class="tab" data-cat="${key}" onclick="setTab('${key}')">${c.label}</button>
`).join('');

function renderTrips(){
  document.querySelectorAll('.tab').forEach(el => el.dataset.active = el.dataset.cat === activeCat);
  document.querySelector('.trips-grid').parentElement.style.setProperty('--accent', CATS[activeCat].color);
  document.documentElement.style.setProperty('--accent', CATS[activeCat].color);

  document.getElementById('tripsGrid').innerHTML = TRIPS.filter(t => t.category === activeCat).map(t => `
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

const FEATURED = [
  { image:"{{ asset('images/hero-safari.jpg') }}", region:"Arusha Region", title:"Ngorongoro Crater", subtitle:"The world's largest intact caldera", rating:4.9, reviews:2847, price:1450, category:"safari", tripId:"northern-circuit" },
  { image:"{{ asset('images/hero-kilimanjaro.jpg') }}", region:"Kilimanjaro Region", title:"Mount Kilimanjaro", subtitle:"Africa's rooftop, five climate zones up", rating:4.8, reviews:1930, price:2450, category:"kilimanjaro", tripId:"machame" },
  { image:"{{ asset('images/hero-zanzibar.jpg') }}", region:"Zanzibar Archipelago", title:"Nungwi Beaches", subtitle:"Turquoise water, white sand, no rush", rating:4.9, reviews:1520, price:650, category:"zanzibar", tripId:"zanzibar-beach" }
];

let heroIdx = 0;
let heroTimer;

document.getElementById('heroSlides').innerHTML = FEATURED.map((f,i) =>
  `<div class="hero-slide${i===0?' active':''}" style="background-image:url('${f.image}')"></div>`
).join('');

document.getElementById('heroDots').innerHTML = FEATURED.map((f,i) =>
  `<button class="hero-dot${i===0?' active':''}" onclick="heroGoTo(${i})" aria-label="Show ${f.title}"></button>`
).join('');

function renderHeroContent(){
  const f = FEATURED[heroIdx];
  document.getElementById('heroContent').innerHTML = `
    <div class="hero-eyebrow">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
      ${f.region}
    </div>
    <h1>${f.title}</h1>
    <p class="hero-lede">${f.subtitle}</p>
    <div class="hero-price-row">
      <div class="hero-rating">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        <strong>${f.rating}</strong><span class="count">· ${f.reviews.toLocaleString()} reviews</span>
      </div>
      <div class="hero-price">$${f.price.toLocaleString()}<small>from, per person</small></div>
    </div>
    <div class="hero-ctas">
      <button class="btn btn-fill" onclick="openBooking('${f.tripId}')">
        Book this trip
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
      <a class="hero-icon-btn" href="/${f.tripId}" aria-label="View trip details">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      </a>
    </div>
  `;
  document.querySelectorAll('.hero-slide').forEach((el,i) => el.classList.toggle('active', i === heroIdx));
  document.querySelectorAll('.hero-dot').forEach((el,i) => el.classList.toggle('active', i === heroIdx));
}
function heroGoTo(i){ heroIdx = (i + FEATURED.length) % FEATURED.length; renderHeroContent(); resetHeroTimer(); }
function heroNext(){ heroGoTo(heroIdx + 1); }
function heroPrev(){ heroGoTo(heroIdx - 1); }
function resetHeroTimer(){ clearInterval(heroTimer); heroTimer = setInterval(heroNext, 6000); }
renderHeroContent();
resetHeroTimer();

const qp = new URLSearchParams(location.search);
if (qp.get('trip')) {
  setTimeout(() => jumpTrip(qp.get('tab') || TRIPS.find(t=>t.id===qp.get('trip'))?.category || 'safari', qp.get('trip')), 400);
} else if (qp.get('tab')) {
  setTab(qp.get('tab'));
}
</script>
@endpush
