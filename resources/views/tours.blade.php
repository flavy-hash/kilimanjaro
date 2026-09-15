@extends('layouts.site')

@section('title', 'All Tours & Experiences · Perfect Kilimanjaro')
@section('meta_description', 'Every trip we run in Tanzania — perfect kilimanajro, Kilimanjaro climbs and Zanzibar escapes, all planned and guided in-house.')

@section('content')

<header class="trip-hero" id="top" style="--pc:var(--c-safari)">
  <div class="trip-hero-bg" style="background-image:url('{{ asset('images/hero-safari.jpg') }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <span class="current">All Tours</span>
    </nav>

    <h1>Tours &amp; Experiences</h1>

    <p class="hero-lede">Every trip we run: safaris, Kilimanjaro routes and island escapes — all privately guided, all built around small groups.</p>

    <div class="trip-hero-badges" style="margin-bottom:0">
      <span class="hero-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        {{ count($trips) }} trips
      </span>
      <span class="hero-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
        3 destinations
      </span>
      <span class="hero-badge star">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        4.9 average rating
      </span>
    </div>
  </div>
</header>

<section>
  <div class="wrap">
    <div class="trips-head">
      <div class="tabs" id="toursTabs"></div>
      <div class="tours-count" id="toursCount"></div>
    </div>
    <div class="trips-grid" id="toursGrid"></div>
  </div>
</section>

@endsection

@push('styles')
<style>
  .tours-count{font-family:'JetBrains Mono',monospace;font-size:12.5px;color:var(--dim);letter-spacing:.05em;white-space:nowrap}
  @media(max-width:640px){
    .trips-head{align-items:flex-start;gap:14px}
  }
</style>
@endpush

@push('scripts')
<script>
const TOUR_TABS = [
  { key:'all', label:'All Tours', color:'var(--c-safari)' },
  { key:'safari', label:'Safaris', color:'var(--c-safari)' },
  { key:'kilimanjaro', label:'Kilimanjaro', color:'var(--c-kili)' },
  { key:'zanzibar', label:'Zanzibar', color:'var(--c-zanzibar)' }
];
let toursFilter = 'all';

document.getElementById('toursTabs').innerHTML = TOUR_TABS.map(t => `
  <button class="tab" data-key="${t.key}" onclick="setToursFilter('${t.key}')">${t.label}</button>
`).join('');

function renderTours(){
  document.querySelectorAll('#toursTabs .tab').forEach(el => el.dataset.active = el.dataset.key === toursFilter);
  const active = TOUR_TABS.find(t => t.key === toursFilter);
  document.getElementById('toursGrid').parentElement.style.setProperty('--accent', active.color);

  const list = toursFilter === 'all' ? TRIPS : TRIPS.filter(t => t.category === toursFilter);
  document.getElementById('toursCount').textContent = `Showing ${list.length} of ${TRIPS.length} tours`;

  document.getElementById('toursGrid').innerHTML = list.map(t => `
    <a class="trip" style="--pc:${CATS[t.category].color}" href="/${t.id}" onmousemove="trackMouse(event,this)">
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
function setToursFilter(key){ toursFilter = key; renderTours(); }
renderTours();

const qp = new URLSearchParams(location.search);
if (qp.get('category')) setToursFilter(qp.get('category'));
</script>
@endpush
