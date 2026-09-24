{{-- Icon-card grid for "why choose us" style sections. Expects $items as an
     array of {title, text}. Icons/colors cycle by position rather than being
     picked per item, so callers stay a plain title+text list. --}}
@php
  $whyIcons = [
    ['bg' => 'var(--glow)', 'svg' => '<path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3"/><circle cx="4" cy="13" r="2"/><circle cx="12" cy="10" r="2"/><circle cx="20" cy="14" r="2"/>'],
    ['bg' => 'var(--c-safari)', 'svg' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>'],
    ['bg' => 'var(--c-zanzibar)', 'svg' => '<rect x="3" y="7" width="18" height="12" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>'],
    ['bg' => 'var(--sun)', 'svg' => '<path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1Z"/>'],
    ['bg' => 'var(--c-biking)', 'svg' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
  ];
@endphp
<div class="why-grid">
  @foreach ($items as $i => $item)
    @php($icon = $whyIcons[$i % count($whyIcons)])
    <div class="why-card">
      <div class="why-card-icon" style="background:color-mix(in srgb, {{ $icon['bg'] }} 18%, transparent); color:{{ $icon['bg'] }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icon['svg'] !!}</svg>
      </div>
      <h3>{{ $item['title'] }}</h3>
      <p>{{ $item['text'] }}</p>
    </div>
  @endforeach
</div>
