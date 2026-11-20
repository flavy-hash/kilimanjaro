@extends('layouts.site')

@section('title', 'All Tours & Experiences · Perfect Kilimanjaro')
@section('meta_description', 'Every trip we run in Tanzania — Serengeti and Ruaha safaris, Kilimanjaro climbs and Zanzibar escapes, all planned and guided in-house.')

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

<section id="tours">
  <div class="wrap">
    <div class="tour-filters" id="tourFilters">
      <div class="tour-filter-row">
        <div class="tour-filter-label">Destination</div>
        <div class="tour-filter-chips" data-group="dest"></div>
      </div>
      <div class="tour-filter-row">
        <div class="tour-filter-label">Style</div>
        <div class="tour-filter-chips" data-group="style"></div>
      </div>
      <div class="tour-filter-row">
        <div class="tour-filter-label">Duration</div>
        <div class="tour-filter-chips" data-group="days"></div>
      </div>
      <div class="tour-filter-foot">
        <div class="tour-filter-count" id="toursCount"></div>
        <button class="tour-filter-clear" id="toursClear" type="button" onclick="clearTourFilters()">Clear filters</button>
      </div>
    </div>

    <div class="trips-grid" id="toursGrid"></div>
    <div class="tour-empty" id="toursEmpty" hidden>
      No trip matches all of those filters. <button type="button" onclick="clearTourFilters()">Clear filters</button> or
      <a href="#" onclick="openBooking();return false;">ask us to plan one</a>.
    </div>
  </div>
</section>

@endsection

@push('styles')
<style>
  :root[data-theme="light"] #tours{background:var(--deep)}
  #tours{padding-top:64px}
  .tour-filters{
    background:var(--panel);border:1px solid var(--line);border-radius:24px;
    padding:30px 34px 22px;margin-bottom:44px;box-shadow:0 18px 40px -28px var(--shadow-deep);
  }
  .tour-filter-row{display:grid;grid-template-columns:150px 1fr;gap:16px;align-items:center;padding:8px 0}
  .tour-filter-label{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:500;letter-spacing:.18em;text-transform:uppercase;color:var(--dim)}
  .tour-filter-chips{display:flex;flex-wrap:wrap;gap:8px}
  .tour-chip{
    all:unset;box-sizing:border-box;cursor:pointer;
    padding:9px 18px;border-radius:100px;border:1px solid var(--line-hi);background:var(--ink);
    font-family:'Space Grotesk',sans-serif;font-size:13.5px;font-weight:500;color:var(--ice);
    transition:background .15s,border-color .15s,color .15s;
  }
  .tour-chip:hover{border-color:var(--glow);color:var(--glow-soft)}
  .tour-chip:focus-visible{outline:2px solid var(--glow);outline-offset:2px}
  .tour-chip[aria-pressed="true"]{background:var(--glow);border-color:var(--glow);color:var(--on-accent)}
  .tour-chip:disabled{opacity:.38;cursor:default;border-color:var(--line);color:var(--dim)}
  .tour-chip small{font-size:11.5px;opacity:.7;margin-left:5px;font-weight:400}
  .tour-filter-foot{
    display:flex;justify-content:space-between;align-items:center;gap:16px;
    margin-top:14px;padding-top:16px;border-top:1px solid var(--line);
  }
  .tour-filter-count{font-size:14px;color:var(--dim)}
  .tour-filter-count b{color:var(--ice);font-weight:700}
  .tour-filter-clear{all:unset;cursor:pointer;font-size:13.5px;font-weight:600;color:var(--glow-soft)}
  .tour-filter-clear:hover{text-decoration:underline}
  .tour-filter-clear[hidden]{display:none}
  .tour-empty{text-align:center;color:var(--dim);font-size:15px;padding:40px 0}
  .tour-empty button,.tour-empty a{all:unset;cursor:pointer;color:var(--glow-soft);font-weight:600}
  .tour-empty button:hover,.tour-empty a:hover{text-decoration:underline}
  @media(max-width:760px){
    .tour-filters{padding:22px 18px 16px;border-radius:20px}
    .tour-filter-row{grid-template-columns:1fr;gap:10px;padding:10px 0}
    .tour-chip{padding:8px 14px;font-size:13px}
  }
</style>
@endpush

@push('scripts')
<script>
/* Filter rows are built from the trips themselves, so a destination or trip
   length only appears once there's a published trip for it. "Style" has no
   field of its own; it is the per-person price band. */
const TOUR_FILTERS = {
  dest: [
    { key:'all', label:'All', test:() => true },
    { key:'kilimanjaro', label:CATS.kilimanjaro.label, test:t => t.category === 'kilimanjaro' },
    { key:'safari', label:CATS.safari.label, test:t => t.category === 'safari' },
    { key:'northern', label:'Northern Circuit', test:t => t.category === 'safari' && t.circuit === 'northern' },
    { key:'southern', label:'Southern Circuit', test:t => t.category === 'safari' && t.circuit === 'southern' },
    { key:'zanzibar', label:CATS.zanzibar.label, test:t => t.category === 'zanzibar' },
    { key:'east_africa', label:CATS.east_africa.label, test:t => t.category === 'east_africa' },
    { key:'biking', label:CATS.biking.label, test:t => t.category === 'biking' },
  ],
  style: [
    { key:'all', label:'All', test:() => true },
    { key:'budget', label:'Budget', hint:'under $1,500', test:t => t.price < 1500 },
    { key:'mid', label:'Mid-range', hint:'$1,500–1,999', test:t => t.price >= 1500 && t.price < 2000 },
    { key:'luxury', label:'Luxury', hint:'$2,000+', test:t => t.price >= 2000 },
  ],
  days: [
    { key:'all', label:'Any', test:() => true },
    ...[...new Set(TRIPS.map(t => Number(t.days)))].sort((a, b) => a - b)
      .map(d => ({ key:String(d), label:`${d} day${d === 1 ? '' : 's'}`, test:t => Number(t.days) === d })),
  ],
};
for (const group in TOUR_FILTERS) {
  TOUR_FILTERS[group] = TOUR_FILTERS[group].filter(o => o.key === 'all' || TRIPS.some(o.test));
}

const tourState = { dest:'all', style:'all', days:'all' };
const optionFor = (group, key) => TOUR_FILTERS[group].find(o => o.key === key);

// Trips that pass every group except `skip` — used to grey out chips that
// would leave nothing to show given the other choices.
function tripsMatching(skip){
  return TRIPS.filter(t => Object.keys(tourState).every(g => g === skip || optionFor(g, tourState[g]).test(t)));
}

document.querySelectorAll('#tourFilters [data-group]').forEach(row => {
  const group = row.dataset.group;
  row.innerHTML = TOUR_FILTERS[group].map(o => `
    <button type="button" class="tour-chip" data-key="${o.key}" onclick="setTourFilter('${group}','${o.key}')"
      ${o.hint ? `title="${o.hint} per person"` : ''}>${o.label}${o.hint ? `<small>${o.hint}</small>` : ''}</button>
  `).join('');
});

function renderTours(){
  document.querySelectorAll('#tourFilters [data-group]').forEach(row => {
    const group = row.dataset.group;
    const pool = tripsMatching(group);
    row.querySelectorAll('.tour-chip').forEach(chip => {
      const active = chip.dataset.key === tourState[group];
      chip.setAttribute('aria-pressed', active);
      chip.disabled = !active && !pool.some(optionFor(group, chip.dataset.key).test);
    });
  });

  const list = tripsMatching(null);
  const filtered = Object.values(tourState).some(v => v !== 'all');
  document.getElementById('toursCount').innerHTML = `<b>${list.length}</b> of ${TRIPS.length} trips`;
  document.getElementById('toursClear').hidden = !filtered;
  document.getElementById('toursEmpty').hidden = list.length > 0;

  const params = new URLSearchParams();
  for (const g in tourState) if (tourState[g] !== 'all') params.set(g === 'dest' ? 'destination' : g, tourState[g]);
  history.replaceState(null, '', location.pathname + (params.toString() ? '?' + params : ''));

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
function setTourFilter(group, key){ tourState[group] = key; renderTours(); }
function clearTourFilters(){ Object.keys(tourState).forEach(g => tourState[g] = 'all'); renderTours(); }

// Deep links: ?category= (from trip breadcrumbs) or the ?destination=&style=&days= this page writes.
const qp = new URLSearchParams(location.search);
const wanted = { dest: qp.get('destination') || qp.get('category'), style: qp.get('style'), days: qp.get('days') };
for (const g in wanted) if (wanted[g] && optionFor(g, wanted[g])) tourState[g] = wanted[g];
renderTours();
</script>
@endpush
