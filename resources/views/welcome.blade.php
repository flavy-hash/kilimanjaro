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
    <div class="stat"><div class="stat-num">3</div><div class="stat-label">Ways to explore</div></div>
    <div class="stat"><div class="stat-num">180<em>+</em></div><div class="stat-label">Trips run</div></div>
    <div class="stat"><div class="stat-num">4.9<em>★</em></div><div class="stat-label">Average rating</div></div>
    <div class="stat"><div class="stat-num">&lt;1<em>hr</em></div><div class="stat-label">Reply time</div></div>
  </div>
</header>

<section id="pillars">
  <div class="wrap">
    <div class="tag">What we run</div>
    <h2 class="section-title">Three trips. <em>One local team.</em></h2>
    <p class="section-lede">No third party brokers every itinerary below is planned and led by our own Tanzanian guides and crews.</p>

    <div class="pillars-grid" id="pillarsGrid"></div>
  </div>
</section>

<section id="trips">
  <div class="wrap">
    <div class="trips-head">
      <div>
        <div class="tag">Trips &amp; pricing</div>
        <h2 class="section-title" style="margin-bottom:8px">Pick your <em>adventure</em></h2>
        <p class="section-lede">All prices are per person, land cost only — park fees, guides, camps and meals on the trip included.</p>
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
      <div class="review">
        <div class="review-stars">★★★★★</div>
        <p>"Our guide rerouted us around a migration crossing three days before we arrived — something no brochure could have known. Best safari of our lives."</p>
        <div class="review-who">
          <div class="review-avatar">JM</div>
          <div><strong>Julia M.</strong><span>Northern Circuit Safari</span></div>
        </div>
      </div>
      <div class="review">
        <div class="review-stars">★★★★★</div>
        <p>"Summited via Lemosho with a crew that clearly knew what they were doing. Honest about pacing, never rushed us, and the food on the mountain was shockingly good."</p>
        <div class="review-who">
          <div class="review-avatar">DK</div>
          <div><strong>Daniel K.</strong><span>Kilimanjaro · Lemosho</span></div>
        </div>
      </div>
      <div class="review">
        <div class="review-stars">★★★★★</div>
        <p>"Stone Town, spice farms, then three days doing nothing on a beach that looked unreal in every photo. Booking on WhatsApp was refreshingly simple."</p>
        <div class="review-who">
          <div class="review-avatar">AR</div>
          <div><strong>Amara R.</strong><span>Zanzibar Beach Escape</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about">
  <div class="wrap">
    <div class="tag">About us</div>
    <h2 class="section-title">Run by the people <em>on the ground</em></h2>
    <p class="section-lede">Safiri is a small Arusha-based team, not a booking platform reselling someone else's itinerary.</p>

    <div class="about-grid">
      <div class="about-block">
        <h3>Our story</h3>
        <p>Started by guides who spent years leading trips for foreign-owned operators and wanted travelers to see more of what they were actually paying for. We plan every itinerary in-house and work only with crews we know personally.</p>
      </div>
      <div class="about-block">
        <h3>Our team</h3>
        <p>A dozen safari guides, six Kilimanjaro crew leads, and a small Zanzibar-based team — all Tanzanian, all full-time, and all paid above the standard park-wage rates.</p>
      </div>
    </div>

    <div id="faq" class="faq-list">
      <div class="faq-item">
        <button class="faq-q" onclick="toggleFaq(this)">Do I need a visa to visit Tanzania?
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-a"><p>Most nationalities can get an e-visa online before arrival, or a visa on arrival at Kilimanjaro International Airport. We send exact instructions once your trip is booked.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" onclick="toggleFaq(this)">When's the best time to see the migration?
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-a"><p>River crossings typically run July–October in the northern Serengeti; calving season is January–March in the south. We track current herd positions and adjust your route accordingly.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" onclick="toggleFaq(this)">Are these trips suitable for families?
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-a"><p>Yes — safaris and Zanzibar trips work well for all ages. Kilimanjaro climbs are better suited to kids 12+ who've done some hiking before.</p></div>
      </div>
    </div>
  </div>
</section>

<div class="cta-band">
  <div>
    <h3>Not sure which trip <em>fits you?</em></h3>
    <p>Tell us your dates, budget and what you want to see — we'll match you to the right itinerary and send a day-by-day plan.</p>
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
