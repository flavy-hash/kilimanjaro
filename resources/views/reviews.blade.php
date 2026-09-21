@extends('layouts.site')

@section('title', 'Traveler Reviews · Perfect Kilimanjaro')
@section('meta_description', 'Real reviews from travelers who\'ve done our safaris, Kilimanjaro climbs and Zanzibar trips.')

@section('content')

<header class="reviews-hero" id="top">
  <div class="reviews-hero-inner">
    <div>
      <div class="tag">Perfect Kilimanjaro</div>
      <h1 class="section-title">What travelers <em>are saying</em>.</h1>
      <p class="section-lede">{{ $stats['count'] }} verified {{ Str::plural('review', $stats['count']) }} from people who actually took the trip.</p>
    </div>
    <button class="btn btn-fill" onclick="openReviewForm()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
      Write a review
    </button>
  </div>
</header>

<div class="review-stat-band">
  <div class="review-stat-band-inner">
    <div class="review-summary-score">
      <div class="num">{{ $stats['overall'] }}</div>
      <div class="stars">{{ str_repeat('★', round($stats['overall'])) }}{{ str_repeat('☆', 5 - round($stats['overall'])) }}</div>
      <div class="count">{{ $stats['count'] }} {{ Str::plural('review', $stats['count']) }}</div>
    </div>
    <div class="review-stat-metrics">
      <div class="review-stat-metric">
        <div class="label">Guide &amp; service</div>
        <div class="num">{{ $stats['service'] }}</div>
        <div class="review-bar-track"><div class="review-bar-fill" style="width:{{ $stats['service'] / 5 * 100 }}%"></div></div>
      </div>
      <div class="review-stat-metric">
        <div class="label">Value for money</div>
        <div class="num">{{ $stats['value'] }}</div>
        <div class="review-bar-track"><div class="review-bar-fill" style="width:{{ $stats['value'] / 5 * 100 }}%"></div></div>
      </div>
    </div>
  </div>
</div>

<section>
  <div class="wrap">

    @if (session('review_submitted'))
      <div class="form-error" style="background:rgba(63,178,74,.1);border-color:rgba(63,178,74,.35);color:#7fd88f;margin-bottom:40px">
        Thanks — your review is in. We read every one before it goes live, so it may take a day or two to appear.
      </div>
    @endif

    <div class="review-list">
      @forelse ($reviews as $review)
        <div class="review-card">
          <div class="review-card-head">
            <div class="review-card-who">
              <div class="review-avatar">{{ $review['initials'] }}</div>
              <div><strong>{{ $review['name'] }}</strong></div>
            </div>
            <div class="review-card-meta">
              <div class="stars">{{ str_repeat('★', $review['rating']) }}{{ str_repeat('☆', 5 - $review['rating']) }}</div>
              @if ($review['stayed_at'])
                <div class="date">{{ $review['stayed_at'] }}</div>
              @endif
            </div>
          </div>

          <h3>{{ $review['title'] }}</h3>
          <p>{{ $review['body'] }}</p>

          @if ($review['service_rating'] || $review['value_rating'])
            <div class="review-card-sub">
              @if ($review['service_rating'])
                <span>Guide &amp; service: <b>{{ $review['service_rating'] }}/5</b></span>
              @endif
              @if ($review['value_rating'])
                <span>Value for money: <b>{{ $review['value_rating'] }}/5</b></span>
              @endif
            </div>
          @endif

          @if ($review['photo'])
            <img class="review-card-photo" src="{{ asset($review['photo']) }}" alt="Photo from {{ $review['name'] }}'s trip" loading="lazy">
          @endif

          @if ($review['trip'])
            <a class="review-card-trip" href="{{ $review['trip_url'] }}">{{ $review['trip'] }}</a>
          @endif
        </div>
      @empty
        <p class="section-lede">No reviews yet — <a href="#" onclick="openReviewForm();return false;" style="color:var(--glow-soft)">be the first to leave one</a>.</p>
      @endforelse
    </div>

  </div>
</section>

{{-- Write-a-review popup — same overlay/drawer pattern as the booking form. --}}
<div class="overlay review-drawer" id="reviewOverlay" onclick="if(event.target===this)closeReviewForm()">
  <div class="drawer" style="width:min(560px,100vw)">
    <button class="drawer-close" onclick="closeReviewForm()" aria-label="Close">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    <h3>Write a review</h3>
    <p class="section-lede" style="margin-bottom:28px">Been on one of our trips? We'd genuinely like to know how it went.</p>

    @if ($errors->any())
      <div class="form-error">
        <ul style="margin:0;padding-left:18px">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data" id="reviewForm">
      @csrf

      <div class="field-row">
        <div class="field"><label>Your name</label><input name="name" required maxlength="120" value="{{ old('name') }}" placeholder="Your name"></div>
        <div class="field"><label>Email (not published)</label><input name="email" type="email" maxlength="160" value="{{ old('email') }}" placeholder="you@email.com"></div>
      </div>

      <div class="field">
        <label>Which trip?</label>
        <select name="package">
          <option value="">Not sure / general feedback</option>
          @foreach ($packages as $package)
            <option value="{{ $package->slug }}" @selected(old('package') === $package->slug)>{{ $package->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label>Overall rating</label>
        <div class="star-picker" data-input="rating">
          @for ($i = 1; $i <= 5; $i++)
            <button type="button" data-value="{{ $i }}" aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
            </button>
          @endfor
        </div>
        <input type="hidden" name="rating" value="{{ old('rating') }}" required>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Guide &amp; service</label>
          <div class="star-picker" data-input="service_rating">
            @for ($i = 1; $i <= 5; $i++)
              <button type="button" data-value="{{ $i }}" aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
              </button>
            @endfor
          </div>
          <input type="hidden" name="service_rating" value="{{ old('service_rating') }}">
        </div>
        <div class="field">
          <label>Value for money</label>
          <div class="star-picker" data-input="value_rating">
            @for ($i = 1; $i <= 5; $i++)
              <button type="button" data-value="{{ $i }}" aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
              </button>
            @endfor
          </div>
          <input type="hidden" name="value_rating" value="{{ old('value_rating') }}">
        </div>
      </div>

      <div class="field"><label>Review headline</label><input name="title" required maxlength="160" value="{{ old('title') }}" placeholder="Sum it up in a few words"></div>

      <div class="field"><label>Your review</label><textarea name="body" required maxlength="2000" placeholder="What stood out — good or bad?">{{ old('body') }}</textarea></div>

      <div class="field"><label>Add a photo (optional)</label><input name="photo" type="file" accept="image/*"></div>

      <p class="review-form-note">We read every review before it goes live — yours may take a day or two to appear.</p>

      <button class="submit-btn" type="submit">Submit review</button>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.star-picker').forEach(picker => {
  const input = document.querySelector(`input[name="${picker.dataset.input}"]`);
  const buttons = [...picker.querySelectorAll('button')];

  function paint(value) {
    buttons.forEach(b => b.classList.toggle('active', Number(b.dataset.value) <= value));
  }
  // Stars start unselected — Number('') is 0, so nothing lights up until the
  // visitor actually clicks one. A validation-error reload still shows
  // whatever they'd already picked, since input.value carries old() through.
  paint(Number(input.value) || 0);

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      input.value = btn.dataset.value;
      paint(Number(btn.dataset.value));
    });
  });
});

function openReviewForm(){
  document.getElementById('reviewOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeReviewForm(){
  document.getElementById('reviewOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

@if ($errors->any())
  // A failed submission redirects back here — reopen the form so the
  // validation messages and whatever was typed are visible again.
  openReviewForm();
@endif
</script>
@endpush
