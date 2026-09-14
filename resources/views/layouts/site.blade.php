<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Safiri · Tanzania Safaris, Kilimanjaro & Zanzibar')</title>
<meta name="description" content="@yield('meta_description', 'Locally owned safari, Kilimanjaro and Zanzibar trips out of Arusha, Tanzania.')">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Instrument+Serif:ital@0;1&family=Space+Grotesk:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  html{scroll-behavior:smooth}
  :root{
    --void:#05070d;
    --deep:#0a0f1a;
    --ink:#111826;
    --glow:#ff7a2f;
    --glow-soft:#ffb070;
    --sun:#ffd66b;
    --ice:#e8f1ff;
    --dim:#8a9bb5;
    --line:rgba(232,241,255,.08);
    --glass:rgba(255,255,255,.04);
    --c-safari:#e0b04a;
    --c-kili:#ff7a2f;
    --c-zanzibar:#33c7c2;
    --accent:var(--c-safari);
  }
  body{
    font-family:'Space Grotesk',sans-serif;
    background:var(--void);
    color:var(--ice);
    overflow-x:hidden;
    -webkit-font-smoothing:antialiased;
  }
  .serif{font-family:'Instrument Serif',serif;font-style:italic}
  .syne{font-family:'Syne',sans-serif}
  .mono{font-family:'JetBrains Mono',monospace}
  svg{display:block}

  body::before{
    content:"";position:fixed;inset:0;pointer-events:none;z-index:100;opacity:.35;
    background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
    mix-blend-mode:overlay;
  }

  #nav{
    position:fixed;top:0;left:0;right:0;z-index:60;
    display:flex;align-items:center;justify-content:space-between;
    padding:20px 5vw;transition:.4s cubic-bezier(.4,0,.2,1);
  }
  #nav.solid{
    background:rgba(5,7,13,.75);backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    border-bottom:1px solid var(--line);padding:14px 5vw;
  }
  .brand{display:flex;align-items:center;gap:12px;cursor:pointer;text-decoration:none}
  .brand-mark{
    width:36px;height:36px;border-radius:50%;position:relative;
    background:radial-gradient(circle at 30% 30%,var(--sun),var(--glow) 60%,#7a2e00);
    box-shadow:0 0 24px rgba(255,122,47,.5),inset 0 0 12px rgba(255,255,255,.3);
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
  }
  .brand-mark::after{
    content:"";position:absolute;inset:-6px;border-radius:50%;
    border:1px solid rgba(255,122,47,.3);animation:pulse 3s ease-in-out infinite;
  }
  @keyframes pulse{0%,100%{transform:scale(1);opacity:.6}50%{transform:scale(1.15);opacity:0}}
  .brand-name{font-family:'Syne',sans-serif;font-size:20px;font-weight:700;letter-spacing:-.02em;color:var(--ice)}
  .brand-name span{color:var(--glow)}
  .nav-links{display:flex;align-items:center;gap:6px;position:relative}
  .nav-item{position:static}
  .nav-top{
    display:flex;align-items:center;gap:6px;
    color:var(--dim);text-decoration:none;font-size:14px;font-weight:400;
    cursor:pointer;transition:.2s;letter-spacing:.01em;padding:10px 14px;
  }
  .nav-top svg{transition:.25s;opacity:.6}
  .nav-item:hover .nav-top{color:var(--ice)}
  .nav-item:hover .nav-top svg{transform:rotate(180deg);opacity:1}

  .dropdown-panel{
    position:absolute;top:calc(100% + 4px);left:50%;
    transform:translateX(-50%) translateY(6px);width:700px;
    background:rgba(10,15,26,.98);border:1px solid var(--line);border-radius:20px;
    padding:24px;opacity:0;pointer-events:none;transition:.22s ease;
    backdrop-filter:blur(20px);box-shadow:0 24px 60px -20px rgba(0,0,0,.6);z-index:70;
    display:grid;grid-template-columns:170px 1fr 190px;gap:24px;
  }
  .dropdown-panel.no-photo{grid-template-columns:170px 1fr;width:460px}
  .nav-item:hover .dropdown-panel{opacity:1;pointer-events:auto;transform:translateX(-50%) translateY(0)}

  .dropdown-links{display:flex;flex-direction:column;gap:1px}
  .dropdown-links a{
    display:block;padding:9px 10px;border-radius:8px;color:var(--dim);
    font-size:12px;font-weight:600;letter-spacing:.03em;text-transform:uppercase;
    text-decoration:none;transition:.15s;cursor:pointer;
  }
  .dropdown-links a:first-child{color:var(--glow-soft)}
  .dropdown-links a:hover{background:rgba(255,122,47,.08);color:var(--ice)}

  .dropdown-content{
    display:flex;flex-direction:column;justify-content:center;
    border-left:1px solid var(--line);border-right:1px solid var(--line);padding:0 24px;
  }
  .dropdown-panel.no-photo .dropdown-content{border-right:none}
  .dropdown-content h4{font-family:'Syne',sans-serif;font-weight:700;font-size:21px;letter-spacing:-.02em;margin-bottom:10px;color:var(--ice)}
  .dropdown-content p{color:var(--dim);font-size:13px;line-height:1.6;margin-bottom:18px}
  .dropdown-cta{
    display:inline-flex;align-items:center;gap:8px;width:fit-content;
    padding:11px 18px;border-radius:100px;border:1px solid var(--line);
    background:var(--glass);color:var(--ice);font-size:12px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;text-decoration:none;cursor:pointer;transition:.2s;
  }
  .dropdown-cta:hover{background:rgba(255,122,47,.1);border-color:rgba(255,122,47,.35);color:var(--glow-soft)}

  .dropdown-photo{border-radius:14px;overflow:hidden;background:var(--ink);cursor:pointer}
  .dropdown-photo img{width:100%;height:100%;object-fit:cover;display:block;min-height:180px}

  .nav-right{display:flex;align-items:center;gap:12px}
  .hamburger{
    display:none;width:40px;height:40px;border-radius:50%;flex-shrink:0;
    background:var(--glass);border:1px solid var(--line);color:var(--ice);
    align-items:center;justify-content:center;cursor:pointer;transition:.2s;
  }
  .hamburger:hover{background:rgba(255,255,255,.08)}

  @media(max-width:1080px){
    .nav-links{display:none}
    .hamburger{display:flex}
  }

  .mnav-group{border-bottom:1px solid var(--line)}
  .mnav-group:first-child{border-top:1px solid var(--line)}
  .mnav-head{
    width:100%;background:none;border:none;color:var(--ice);
    font-family:'Syne',sans-serif;font-weight:600;font-size:15px;
    padding:16px 2px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;
  }
  .mnav-head svg{transition:.25s;color:var(--dim)}
  .mnav-head.open svg{transform:rotate(180deg)}
  .mnav-body{max-height:0;overflow:hidden;transition:.3s ease}
  .mnav-body.open{max-height:260px;padding-bottom:10px}
  .mnav-body a{
    display:block;padding:9px 2px 9px 12px;color:var(--dim);
    font-size:13.5px;text-decoration:none;cursor:pointer;transition:.15s;
  }
  .mnav-body a:hover{color:var(--glow-soft)}
  .mnav-link{
    display:flex;align-items:center;padding:16px 2px;color:var(--ice);
    font-family:'Syne',sans-serif;font-weight:600;font-size:15px;text-decoration:none;
  }
  .mnav-link:hover{color:var(--glow-soft)}

  .btn{
    border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;
    font-weight:500;font-size:14px;letter-spacing:.01em;
    padding:13px 24px;border-radius:100px;transition:.3s;
    display:inline-flex;align-items:center;gap:8px;text-decoration:none;
  }
  .btn-fill{
    background:linear-gradient(135deg,var(--sun),var(--glow));
    color:#1a0a00;box-shadow:0 8px 30px rgba(255,122,47,.35);
  }
  .btn-fill:hover{transform:translateY(-2px);box-shadow:0 12px 40px rgba(255,122,47,.5)}
  .btn-line{
    background:var(--glass);color:var(--ice);
    border:1px solid var(--line);backdrop-filter:blur(10px);
  }
  .btn-line:hover{background:rgba(255,255,255,.08);border-color:rgba(232,241,255,.2)}
  .nav .btn-fill{padding:10px 20px;font-size:13px}

  .hero{
    min-height:100vh;position:relative;overflow:hidden;
    display:flex;flex-direction:column;justify-content:flex-end;
    padding:0 5vw 100px;
  }
  .hero-slides{position:absolute;inset:0}
  .hero-slide{
    position:absolute;inset:0;opacity:0;transition:opacity 1.1s ease;
    background-size:cover;background-position:center;
  }
  .hero-slide.active{opacity:1}
  .hero-overlay{
    position:absolute;inset:0;z-index:1;pointer-events:none;
    background:
      linear-gradient(180deg,rgba(3,4,10,.15) 0%,rgba(3,4,10,.5) 55%,rgba(3,4,10,.95) 100%),
      linear-gradient(100deg,rgba(3,4,10,.65) 0%,rgba(3,4,10,.1) 45%,rgba(3,4,10,.4) 100%);
  }
  .hero-arrow{
    position:absolute;top:44%;transform:translateY(-50%);z-index:5;
    width:44px;height:44px;border-radius:50%;
    background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.22);
    backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
    color:#fff;display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:.25s;
  }
  .hero-arrow:hover{background:rgba(255,255,255,.18);transform:translateY(-50%) scale(1.08)}
  .hero-arrow.prev{left:5vw}
  .hero-arrow.next{right:5vw}

  .hero-inner{position:relative;z-index:3;max-width:1400px;margin:0 auto;width:100%}
  .hero-eyebrow{
    display:inline-flex;align-items:center;gap:10px;
    font-family:'JetBrains Mono',monospace;font-size:12px;
    color:var(--glow-soft);letter-spacing:.15em;text-transform:uppercase;
    padding:8px 16px;border-radius:100px;
    background:rgba(255,122,47,.08);border:1px solid rgba(255,122,47,.25);
    backdrop-filter:blur(10px);margin-bottom:18px;
    animation:fadeUp .8s ease both;
  }

  .hero h1{
    font-family:'Syne',sans-serif;font-weight:700;
    font-size:clamp(36px,6vw,84px);line-height:1;max-width:14ch;
    letter-spacing:-.04em;margin-bottom:14px;
    animation:fadeUp .9s .1s ease both;
  }
  .hero h1 em{
    font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;
    background:linear-gradient(135deg,var(--sun),var(--glow) 60%,#ff9d5c);
    -webkit-background-clip:text;background-clip:text;color:transparent;
    display:inline-block;
  }
  .hero-lede{
    font-size:clamp(15px,1.3vw,18px);color:var(--dim);max-width:54ch;
    line-height:1.5;margin-bottom:28px;font-weight:300;
    animation:fadeUp 1s .2s ease both;
  }
  .hero-lede strong{color:var(--ice);font-weight:500}
  .hero-price-row{
    display:flex;align-items:center;justify-content:space-between;gap:20px;
    max-width:440px;margin-bottom:28px;flex-wrap:wrap;
    animation:fadeUp 1s .25s ease both;
  }
  .hero-rating{display:flex;align-items:center;gap:7px;font-size:14px;color:var(--ice)}
  .hero-rating svg{color:var(--sun);flex-shrink:0}
  .hero-rating .count{color:var(--dim);font-weight:300}
  .hero-price{font-family:'Syne',sans-serif;font-weight:700;font-size:22px;color:var(--ice);text-align:right}
  .hero-price small{
    display:block;font-family:'Space Grotesk',sans-serif;font-size:10px;color:var(--dim);
    font-weight:400;text-transform:uppercase;letter-spacing:.08em;margin-top:2px;
  }
  .hero-ctas{display:flex;gap:14px;flex-wrap:wrap;align-items:center;animation:fadeUp 1s .3s ease both}
  .hero-icon-btn{
    width:48px;height:48px;border-radius:50%;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    background:var(--glass);border:1px solid var(--line);backdrop-filter:blur(10px);
    color:var(--ice);cursor:pointer;transition:.25s;
  }
  .hero-icon-btn:hover{background:rgba(255,255,255,.12);border-color:rgba(232,241,255,.2)}
  .hero-dots{display:flex;gap:8px;margin-top:28px}
  .hero-dot{
    width:8px;height:8px;border-radius:100px;background:rgba(255,255,255,.3);
    cursor:pointer;transition:.25s;border:none;padding:0;
  }
  .hero-dot.active{background:var(--glow);width:24px}
  @keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}

  .hero-stats{
    position:absolute;bottom:0;left:0;right:0;z-index:4;
    display:grid;grid-template-columns:repeat(4,1fr);
    border-top:1px solid var(--line);
    background:rgba(5,7,13,.6);backdrop-filter:blur(20px);
  }
  .stat{padding:22px 5vw;border-right:1px solid var(--line);transition:.3s}
  .stat:last-child{border-right:none}
  .stat:hover{background:rgba(255,122,47,.05)}
  .stat-num{
    font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(22px,2.2vw,34px);
    letter-spacing:-.03em;color:var(--ice);line-height:1;
  }
  .stat-num em{font-family:'Instrument Serif',serif;font-style:italic;
    color:var(--glow);font-weight:400;font-size:.7em;margin-left:2px}
  .stat-label{font-family:'JetBrains Mono',monospace;font-size:10.5px;
    color:var(--dim);letter-spacing:.11em;text-transform:uppercase;margin-top:8px}

  section{padding:120px 5vw;position:relative}
  .wrap{max-width:1400px;margin:0 auto}
  .reveal{opacity:0;transform:translateY(24px);transition:opacity .7s ease,transform .7s ease}
  .reveal.in{opacity:1;transform:translateY(0)}

  .tag{
    display:inline-flex;align-items:center;gap:8px;
    font-family:'JetBrains Mono',monospace;font-size:11px;
    color:var(--glow-soft);letter-spacing:.18em;text-transform:uppercase;
    margin-bottom:24px;
  }
  .tag::before{content:"";width:24px;height:1px;background:var(--glow)}

  h2.section-title{
    font-family:'Syne',sans-serif;font-weight:700;
    font-size:clamp(34px,4.6vw,64px);line-height:1.04;
    letter-spacing:-.03em;margin-bottom:20px;max-width:22ch;
  }
  h2.section-title em{
    font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;
    color:var(--glow-soft);
  }
  .section-lede{color:var(--dim);font-size:16.5px;line-height:1.65;max-width:58ch;font-weight:300}

  /* pillars */
  .pillars-grid{
    display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:64px;
  }
  .pillar{
    --pc:var(--c-safari);
    padding:36px 30px;border-radius:24px;position:relative;overflow:hidden;cursor:pointer;
    background:linear-gradient(160deg,rgba(255,255,255,.05),rgba(255,255,255,.01));
    border:1px solid var(--line);backdrop-filter:blur(10px);
    transition:.4s cubic-bezier(.2,.8,.3,1);
  }
  .pillar:hover{transform:translateY(-6px);border-color:color-mix(in srgb,var(--pc) 45%,transparent);
    box-shadow:0 30px 70px -30px color-mix(in srgb,var(--pc) 55%,transparent)}
  .pillar-icon{
    width:52px;height:52px;border-radius:16px;margin-bottom:24px;
    background:color-mix(in srgb,var(--pc) 16%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 30%,transparent);
    display:flex;align-items:center;justify-content:center;color:var(--pc);
  }
  .pillar h3{font-family:'Syne',sans-serif;font-weight:600;font-size:23px;letter-spacing:-.02em;margin-bottom:12px}
  .pillar p{color:var(--dim);font-size:14.5px;line-height:1.65;margin-bottom:22px}
  .pillar-foot{display:flex;justify-content:space-between;align-items:center;padding-top:20px;border-top:1px solid var(--line)}
  .pillar-from{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--dim)}
  .pillar-from b{color:var(--ice);font-size:15px;font-family:'Syne',sans-serif}
  .pillar-arrow{
    width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.05);
    border:1px solid var(--line);display:flex;align-items:center;justify-content:center;
    color:var(--ice);transition:.3s;
  }
  .pillar:hover .pillar-arrow{background:var(--pc);color:#1a0a00;border-color:transparent;transform:rotate(-45deg)}

  /* trips / tabs */
  .trips-head{display:flex;justify-content:space-between;align-items:flex-end;gap:30px;flex-wrap:wrap;margin-bottom:40px}
  .tabs{display:flex;gap:8px;padding:6px;border-radius:100px;background:var(--glass);border:1px solid var(--line);width:fit-content}
  .tab{
    border:none;background:none;color:var(--dim);font-family:'Space Grotesk',sans-serif;
    font-size:13.5px;font-weight:500;padding:10px 20px;border-radius:100px;cursor:pointer;
    transition:.3s;display:flex;align-items:center;gap:8px;
  }
  .tab[data-active="true"]{background:var(--accent);color:#1a0a00;box-shadow:0 6px 20px color-mix(in srgb,var(--accent) 50%,transparent)}
  .tab:not([data-active="true"]):hover{color:var(--ice)}

  .trips-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
  .trip{
    --pc:var(--accent);
    position:relative;border-radius:24px;padding:0;
    background:linear-gradient(160deg,rgba(255,255,255,.05),rgba(255,255,255,.01));
    border:1px solid var(--line);backdrop-filter:blur(10px);
    display:flex;flex-direction:column;overflow:hidden;
    transition:.4s cubic-bezier(.2,.8,.3,1);cursor:pointer;
    animation:fadeUp .5s ease both;color:inherit;text-decoration:none;
  }
  .trip::after{
    content:"";position:absolute;inset:0;border-radius:24px;pointer-events:none;
    background:radial-gradient(circle at var(--mx,50%) var(--my,50%),color-mix(in srgb,var(--pc) 18%,transparent),transparent 40%);
    opacity:0;transition:opacity .3s;z-index:2;
  }
  .trip:hover::after{opacity:1}
  .trip:hover{transform:translateY(-6px);border-color:color-mix(in srgb,var(--pc) 40%,transparent);
    box-shadow:0 30px 70px -30px color-mix(in srgb,var(--pc) 45%,transparent)}

  .trip-media{position:relative;aspect-ratio:16/11;overflow:hidden;flex-shrink:0}
  .trip-media img{width:100%;height:100%;object-fit:cover;display:block;transition:.6s cubic-bezier(.2,.8,.3,1)}
  .trip:hover .trip-media img{transform:scale(1.06)}
  .trip-tag-overlay{
    position:absolute;top:14px;left:14px;padding:6px 12px;border-radius:100px;
    background:rgba(5,7,13,.6);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.15);
    color:#fff;font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.1em;text-transform:uppercase;
  }
  .trip-rating-overlay{
    position:absolute;top:14px;right:14px;padding:6px 11px;border-radius:100px;
    background:rgba(255,255,255,.94);color:#1a0a00;
    font-family:'Syne',sans-serif;font-weight:700;font-size:12px;
    display:flex;align-items:center;gap:4px;
  }
  .trip-rating-overlay svg{color:#ff9d00}

  .trip-body{padding:26px;display:flex;flex-direction:column;flex-grow:1}
  .trip-nick{font-family:'Instrument Serif',serif;font-style:italic;font-size:14.5px;color:var(--pc);margin-bottom:8px}
  .trip-name{font-family:'Syne',sans-serif;font-weight:600;font-size:22px;letter-spacing:-.02em;line-height:1.15;margin-bottom:16px}
  .trip-metrics{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
  .metric{
    font-family:'JetBrains Mono',monospace;font-size:11px;padding:6px 10px;border-radius:8px;
    background:rgba(255,255,255,.05);border:1px solid var(--line);color:var(--dim);letter-spacing:.03em;
  }
  .metric.accent{color:var(--pc);border-color:color-mix(in srgb,var(--pc) 35%,transparent);background:color-mix(in srgb,var(--pc) 10%,transparent)}
  .trip-blurb{color:var(--dim);font-size:14px;line-height:1.6;margin-bottom:20px;flex-grow:1}
  .trip-list{list-style:none;margin-bottom:24px;display:flex;flex-direction:column;gap:9px}
  .trip-list li{font-size:13px;color:var(--ice);display:flex;gap:10px;align-items:flex-start}
  .trip-list li::before{
    content:"";flex-shrink:0;width:14px;height:14px;margin-top:2px;border-radius:50%;
    background:color-mix(in srgb,var(--pc) 16%,transparent);border:1px solid var(--pc);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 6 9 17l-5-5'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:center;background-size:8px;
  }
  .trip-foot{display:flex;justify-content:space-between;align-items:center;padding-top:20px;border-top:1px solid var(--line)}
  .trip-price{font-family:'Syne',sans-serif;font-weight:700;font-size:26px;letter-spacing:-.03em;line-height:1}
  .trip-price small{display:block;font-family:'Space Grotesk',sans-serif;font-size:10.5px;color:var(--dim);font-weight:400;margin-top:6px;letter-spacing:.1em;text-transform:uppercase}
  .trip-reviews{font-size:11.5px;color:var(--dim);margin-top:6px}
  .trip-view-btn{
    display:inline-flex;align-items:center;gap:6px;flex-shrink:0;
    padding:10px 16px;border-radius:100px;
    background:rgba(255,255,255,.05);border:1px solid var(--line);
    color:var(--ice);font-family:'Space Grotesk',sans-serif;font-size:12.5px;font-weight:500;
    cursor:pointer;transition:.25s;
  }
  .trip:hover .trip-view-btn{background:var(--pc);color:#0a0f1a;border-color:transparent}
  .trip.flash{border-color:var(--pc);box-shadow:0 0 0 4px color-mix(in srgb,var(--pc) 30%,transparent),0 30px 70px -30px color-mix(in srgb,var(--pc) 45%,transparent);animation:flashPulse .6s ease 2}
  @keyframes flashPulse{50%{transform:translateY(-6px)}}

  /* trip detail page — full-bleed hero */
  .trip-hero{
    position:relative;min-height:560px;display:flex;align-items:flex-end;
    padding:140px 5vw 64px;overflow:hidden;
  }
  .trip-hero-bg{position:absolute;inset:0;background-size:cover;background-position:center}
  .trip-hero-overlay{
    position:absolute;inset:0;
    background:
      linear-gradient(180deg,rgba(3,4,10,.8) 0%,rgba(3,4,10,.3) 38%,rgba(3,4,10,.96) 100%),
      linear-gradient(90deg,rgba(3,4,10,.7) 0%,rgba(3,4,10,.05) 65%);
  }
  .trip-hero-inner{position:relative;z-index:3;max-width:1400px;margin:0 auto;width:100%}
  .breadcrumb{
    display:flex;align-items:center;gap:9px;flex-wrap:wrap;margin-bottom:22px;
    font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:.1em;text-transform:uppercase;
  }
  .breadcrumb a{color:var(--dim);text-decoration:none;transition:.2s}
  .breadcrumb a:hover{color:var(--glow-soft)}
  .breadcrumb .sep{color:var(--dim);opacity:.45}
  .breadcrumb .current{color:var(--ice)}
  .trip-hero h1{
    font-family:'Syne',sans-serif;font-weight:700;
    font-size:clamp(32px,4.8vw,64px);line-height:1.02;letter-spacing:-.03em;
    margin-bottom:20px;max-width:20ch;
  }
  .trip-hero-badges{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:28px}
  .hero-badge{
    display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:100px;
    background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);
    backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
    font-size:12.5px;color:var(--ice);
  }
  .hero-badge svg{flex-shrink:0;opacity:.85}
  .hero-badge.star svg{color:var(--sun);opacity:1}
  .trip-hero-foot{display:flex;align-items:center;gap:28px;flex-wrap:wrap}
  .trip-hero-price{font-family:'Syne',sans-serif;font-weight:700;font-size:34px;letter-spacing:-.03em;line-height:1}
  .trip-hero-price small{
    display:block;font-family:'Space Grotesk',sans-serif;font-size:10.5px;color:var(--dim);
    font-weight:400;margin-top:7px;letter-spacing:.1em;text-transform:uppercase;
  }

  /* trip detail page — article body */
  .article{max-width:1180px;margin:0 auto}
  .art-section{margin-bottom:56px}
  .art-section > h2{
    font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(22px,2.4vw,30px);
    letter-spacing:-.02em;margin-bottom:18px;display:flex;align-items:center;gap:11px;
  }
  .art-section > h2 svg{color:var(--pc);flex-shrink:0}
  .art-section p{color:var(--dim);font-size:15.5px;line-height:1.8}
  .hl-list{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .hl-item{
    display:flex;align-items:flex-start;gap:13px;padding:16px 18px;border-radius:14px;
    background:var(--glass);border:1px solid var(--line);font-size:14px;color:var(--ice);line-height:1.5;
  }
  .hl-icon{
    flex-shrink:0;width:26px;height:26px;border-radius:50%;margin-top:-1px;
    background:color-mix(in srgb,var(--pc) 14%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 35%,transparent);
    display:flex;align-items:center;justify-content:center;color:var(--pc);
  }
  .incl-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
  .incl-col{padding:26px;border-radius:18px;background:var(--glass);border:1px solid var(--line)}
  .incl-col h3{
    font-family:'Syne',sans-serif;font-weight:600;font-size:16px;margin-bottom:16px;
    display:flex;align-items:center;gap:9px;
  }
  .incl-col ul{list-style:none;display:flex;flex-direction:column;gap:11px}
  .incl-col li{font-size:13.8px;line-height:1.55;display:flex;gap:11px;align-items:flex-start}
  .incl-col li::before{
    content:"";flex-shrink:0;width:15px;height:15px;margin-top:2px;border-radius:50%;
    background-repeat:no-repeat;background-position:center;background-size:9px;
  }
  .incl-yes h3{color:#5dd39e}
  .incl-yes li{color:var(--ice)}
  .incl-yes li::before{
    background-color:rgba(93,211,158,.14);border:1px solid rgba(93,211,158,.55);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' viewBox='0 0 24 24' fill='none' stroke='%235dd39e' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 6 9 17l-5-5'/%3E%3C/svg%3E");
  }
  .incl-no h3{color:#ff7f7f}
  .incl-no li{color:var(--dim)}
  .incl-no li::before{
    background-color:rgba(255,127,127,.1);border:1px solid rgba(255,127,127,.4);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' viewBox='0 0 24 24' fill='none' stroke='%23ff7f7f' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M18 6 6 18M6 6l12 12'/%3E%3C/svg%3E");
  }
  /* itinerary accordion */
  .itin-list{display:flex;flex-direction:column;gap:12px}
  .itin-item{
    border:1px solid var(--line);border-radius:16px;background:var(--glass);
    overflow:hidden;transition:border-color .25s,background .25s;
  }
  .itin-item.open{
    border-color:color-mix(in srgb,var(--pc) 32%,transparent);
    background:rgba(255,255,255,.05);
  }
  .itin-head{
    width:100%;display:flex;align-items:center;gap:16px;padding:17px 20px;
    background:none;border:none;cursor:pointer;text-align:left;color:var(--ice);
    font-family:'Space Grotesk',sans-serif;
  }
  .itin-num{
    flex-shrink:0;width:38px;height:38px;border-radius:50%;
    background:color-mix(in srgb,var(--pc) 16%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 38%,transparent);
    display:flex;align-items:center;justify-content:center;
    font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--pc);
  }
  .itin-title{flex:1;font-family:'Syne',sans-serif;font-weight:600;font-size:16px;letter-spacing:-.01em;line-height:1.35}
  .itin-chev{flex-shrink:0;color:var(--dim);transition:transform .3s,color .3s}
  .itin-item.open .itin-chev{transform:rotate(180deg);color:var(--pc)}
  /* 0fr→1fr animates to the content's real height, whatever its length */
  .itin-body{display:grid;grid-template-rows:0fr;transition:grid-template-rows .32s ease}
  .itin-item.open .itin-body{grid-template-rows:1fr}
  .itin-body-inner{overflow:hidden}
  .itin-content{padding:17px 20px 20px 74px;border-top:1px solid var(--line)}
  .itin-content p{color:var(--dim);font-size:14.5px;line-height:1.75;margin-bottom:15px}
  .itin-meta{display:flex;gap:26px;flex-wrap:wrap}
  .itin-meta span{font-size:13px;color:var(--ice)}
  .itin-meta .k{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.15em;
    text-transform:uppercase;color:var(--dim);margin-right:8px;
  }
  .itin-meta .elev{color:var(--pc)}
  .pricing-note{
    padding:28px;border-radius:18px;
    background:linear-gradient(135deg,rgba(255,122,47,.1),rgba(255,122,47,.02));
    border:1px solid rgba(255,122,47,.22);
  }
  .pricing-note p{margin-bottom:20px}
  .why-list{display:flex;flex-direction:column;gap:1px;background:var(--line);border:1px solid var(--line);border-radius:18px;overflow:hidden}
  .why-item{background:var(--deep);padding:22px 24px;display:flex;gap:16px;align-items:flex-start}
  .why-num{
    flex-shrink:0;font-family:'Instrument Serif',serif;font-style:italic;
    font-size:22px;color:var(--pc);line-height:1;margin-top:2px;min-width:26px;
  }
  .why-item strong{display:block;font-family:'Syne',sans-serif;font-weight:600;font-size:15.5px;margin-bottom:5px}
  .why-item span{color:var(--dim);font-size:14px;line-height:1.6}
  .trip-cta-band{
    padding:48px;border-radius:26px;text-align:center;
    background:radial-gradient(ellipse at 50% 0%,rgba(255,122,47,.22),transparent 60%),linear-gradient(135deg,#1a0f2a,#0a0f1a 60%);
    border:1px solid rgba(255,122,47,.2);
  }
  .trip-cta-band h3{font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(24px,2.8vw,34px);letter-spacing:-.02em;margin-bottom:14px}
  .trip-cta-band p{color:var(--dim);font-size:15px;line-height:1.7;max-width:52ch;margin:0 auto 28px}
  .trip-cta-band .btn-row{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}

  /* two-column layout with sticky booking card */
  .trip-layout{display:grid;grid-template-columns:minmax(0,1fr) 370px;gap:56px;align-items:start}
  .trip-main{min-width:0}
  .trip-aside{position:sticky;top:104px}
  .book-card{
    padding:30px;border-radius:22px;
    background:linear-gradient(160deg,rgba(255,255,255,.055),rgba(255,255,255,.015));
    border:1px solid var(--line);backdrop-filter:blur(10px);
    box-shadow:0 30px 70px -40px rgba(0,0,0,.85);
  }
  .book-from{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.18em;
    text-transform:uppercase;color:var(--dim);margin-bottom:10px;
  }
  .book-price{font-family:'Syne',sans-serif;font-weight:700;font-size:42px;letter-spacing:-.03em;line-height:1}
  .book-note{
    color:var(--dim);font-size:12.5px;line-height:1.6;
    margin-top:12px;padding-bottom:20px;border-bottom:1px solid var(--line);
  }
  .spec-row{
    display:flex;justify-content:space-between;align-items:baseline;gap:16px;
    padding:13px 0;border-bottom:1px solid var(--line);font-size:13.5px;
  }
  .spec-row:last-of-type{border-bottom:none;padding-bottom:4px}
  .spec-row .k{color:var(--dim);white-space:nowrap}
  .spec-row .v{color:var(--ice);font-weight:500;text-align:right}
  .book-actions{display:flex;flex-direction:column;gap:11px;margin-top:22px}
  .book-actions .btn{width:100%;justify-content:center;padding:15px 20px}
  .book-fine{font-size:11.5px;color:var(--dim);text-align:center;margin-top:16px;line-height:1.55}

  /* itinerary day meta */
  /* where you'll stay */
  .stay-list{display:flex;flex-direction:column;gap:16px}
  .stay-count{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.12em;
    text-transform:uppercase;color:var(--dim);padding:5px 10px;border-radius:100px;
    background:var(--glass);border:1px solid var(--line);margin-left:2px;
  }
  .stay-card{
    display:grid;grid-template-columns:320px 1fr;
    border:1px solid var(--line);border-radius:20px;overflow:hidden;background:var(--glass);
  }
  .stay-photo{position:relative;min-height:250px}
  .stay-photo img{width:100%;height:100%;object-fit:cover;display:block}
  .stay-photo .trip-tag-overlay{top:14px;left:14px}
  .stay-body{padding:26px 28px}
  .stay-name{font-family:'Syne',sans-serif;font-weight:700;font-size:21px;letter-spacing:-.02em;margin-bottom:7px}
  .stay-stars{color:var(--sun);font-size:13px;letter-spacing:3px;margin-bottom:14px}
  .stay-stars .off{color:var(--line)}
  .stay-note{color:var(--dim);font-size:14px;line-height:1.65;margin:14px 0 16px}
  .amen{display:flex;gap:8px;flex-wrap:wrap}
  .amen span{
    display:inline-flex;align-items:center;gap:6px;padding:6px 11px;border-radius:8px;
    background:rgba(255,255,255,.05);border:1px solid var(--line);
    font-size:11.5px;color:var(--dim);
  }
  .amen span svg{color:var(--pc);flex-shrink:0}

  /* gallery */
  .gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
  .gal-item{
    position:relative;border-radius:16px;overflow:hidden;aspect-ratio:4/3;
    cursor:pointer;border:1px solid var(--line);background:var(--ink);
  }
  .gal-item img{width:100%;height:100%;object-fit:cover;display:block;transition:.55s cubic-bezier(.2,.8,.3,1)}
  .gal-item:hover img{transform:scale(1.07)}
  .gal-zoom{
    position:absolute;right:12px;bottom:12px;width:34px;height:34px;border-radius:50%;
    background:rgba(5,7,13,.6);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(8px);
    display:flex;align-items:center;justify-content:center;color:#fff;opacity:0;transition:.3s;
  }
  .gal-item:hover .gal-zoom{opacity:1}

  .lightbox{
    position:fixed;inset:0;z-index:300;background:rgba(3,4,10,.95);
    backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
    opacity:0;pointer-events:none;transition:.3s;
    display:flex;align-items:center;justify-content:center;padding:6vw;
  }
  .lightbox.open{opacity:1;pointer-events:auto}
  .lightbox img{max-width:100%;max-height:84vh;border-radius:14px;display:block}
  .lb-btn{
    position:absolute;width:46px;height:46px;border-radius:50%;cursor:pointer;
    background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.2);
    color:#fff;display:flex;align-items:center;justify-content:center;transition:.25s;
  }
  .lb-btn:hover{background:rgba(255,255,255,.18)}
  .lb-close{top:26px;right:26px}
  .lb-prev{left:3vw;top:50%;transform:translateY(-50%)}
  .lb-next{right:3vw;top:50%;transform:translateY(-50%)}
  .lb-count{
    position:absolute;bottom:26px;left:50%;transform:translateX(-50%);
    font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--dim);letter-spacing:.1em;
  }


  /* about + faq */
  .about-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin:56px 0}
  .about-block{padding:28px;border-radius:20px;background:var(--glass);border:1px solid var(--line)}
  .about-block h3{font-family:'Syne',sans-serif;font-weight:600;font-size:18px;margin-bottom:10px}
  .about-block p{color:var(--dim);font-size:14.5px;line-height:1.7}
  .faq-list{display:flex;flex-direction:column;gap:10px;max-width:800px}
  .faq-item{border:1px solid var(--line);border-radius:16px;background:var(--glass);overflow:hidden}
  .faq-q{
    width:100%;text-align:left;background:none;border:none;color:var(--ice);
    font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:500;
    padding:18px 20px;display:flex;justify-content:space-between;align-items:center;
    gap:16px;cursor:pointer;
  }
  .faq-q svg{transition:.3s;flex-shrink:0;color:var(--dim)}
  .faq-item.open .faq-q svg{transform:rotate(180deg)}
  .faq-a{max-height:0;overflow:hidden;transition:.3s ease;padding:0 20px}
  .faq-item.open .faq-a{max-height:200px;padding-bottom:18px}
  .faq-a p{color:var(--dim);font-size:14px;line-height:1.65}

  /* trust bar */
  .trust-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border-radius:20px;overflow:hidden;border:1px solid var(--line)}
  .trust-item{background:var(--deep);padding:28px 24px;display:flex;flex-direction:column;gap:10px}
  .trust-item svg{color:var(--glow-soft)}
  .trust-item strong{font-family:'Syne',sans-serif;font-size:15px;font-weight:600}
  .trust-item span{color:var(--dim);font-size:13px;line-height:1.5}

  /* testimonials */
  .reviews-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:56px}
  .review{
    padding:28px;border-radius:22px;background:var(--glass);border:1px solid var(--line);backdrop-filter:blur(10px);
  }
  .review-stars{color:var(--sun);font-size:14px;letter-spacing:2px;margin-bottom:16px}
  .review p{color:var(--ice);font-size:14.5px;line-height:1.7;margin-bottom:20px;opacity:.9}
  .review-who{display:flex;align-items:center;gap:12px}
  .review-avatar{
    width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--sun),var(--glow));
    display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;color:#1a0a00;font-size:14px;flex-shrink:0;
  }
  .review-who strong{display:block;font-size:13.5px}
  .review-who span{color:var(--dim);font-size:12px}

  /* cta band */
  .cta-band{
    margin:0 5vw 120px;padding:70px 56px;border-radius:32px;
    background:radial-gradient(ellipse at 80% 30%,rgba(255,122,47,.25),transparent 55%),linear-gradient(135deg,#1a0f2a,#0a0f1a 60%);
    border:1px solid rgba(255,122,47,.2);position:relative;overflow:hidden;
    display:grid;grid-template-columns:1.4fr 1fr;gap:56px;align-items:center;
  }
  .cta-band::before{
    content:"";position:absolute;top:-50%;right:-10%;width:500px;height:500px;border-radius:50%;
    background:radial-gradient(circle,rgba(255,214,107,.15),transparent 60%);filter:blur(40px);
  }
  .cta-band h3{font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(28px,3.2vw,48px);line-height:1.08;letter-spacing:-.03em;margin-bottom:18px;position:relative}
  .cta-band h3 em{font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;color:var(--glow-soft)}
  .cta-band p{color:var(--dim);font-size:15.5px;line-height:1.65;max-width:46ch;position:relative}
  .cta-band-right{position:relative;display:flex;flex-direction:column;gap:14px}
  .cta-stat{display:flex;align-items:center;gap:16px;padding:16px 18px;border-radius:16px;background:rgba(255,255,255,.03);border:1px solid var(--line);backdrop-filter:blur(10px)}
  .cta-stat-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,rgba(255,214,107,.2),rgba(255,122,47,.2));border:1px solid rgba(255,122,47,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--glow-soft)}
  .cta-stat-txt strong{display:block;font-size:14.5px;font-weight:500;margin-bottom:2px}
  .cta-stat-txt span{font-size:12.5px;color:var(--dim)}

  footer{padding:70px 5vw 36px;border-top:1px solid var(--line);position:relative}
  .footer-grid{max-width:1400px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr;gap:56px;padding-bottom:52px;border-bottom:1px solid var(--line)}
  .footer-brand .brand{margin-bottom:20px}
  .footer-brand p{color:var(--dim);font-size:14px;line-height:1.7;max-width:38ch}
  .footer-col h4{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--dim);letter-spacing:.15em;text-transform:uppercase;margin-bottom:18px;font-weight:400}
  .footer-col a,.footer-col p{display:block;color:var(--ice);text-decoration:none;font-size:14px;line-height:2;cursor:pointer;transition:.2s;opacity:.8}
  .footer-col a:hover{color:var(--glow-soft);opacity:1;transform:translateX(4px)}
  .footer-social{display:flex;gap:10px;margin-top:16px}
  .footer-social a{
    width:34px;height:34px;border-radius:50%;background:var(--glass);border:1px solid var(--line);
    display:flex;align-items:center;justify-content:center;transform:none!important;
  }
  .footer-social a:hover{background:rgba(255,122,47,.12);border-color:rgba(255,122,47,.3)}
  .footer-bottom{max-width:1400px;margin:36px auto 0;display:flex;justify-content:space-between;align-items:center;font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--dim);letter-spacing:.08em;flex-wrap:wrap;gap:14px}

  /* whatsapp float */
  .wa-float{
    position:fixed;bottom:26px;right:26px;z-index:70;width:56px;height:56px;border-radius:50%;
    background:linear-gradient(135deg,#3fd67a,#1fa855);display:flex;align-items:center;justify-content:center;
    color:#052e13;box-shadow:0 10px 30px rgba(31,168,85,.45);cursor:pointer;transition:.3s;text-decoration:none;
  }
  .wa-float::after{content:"";position:absolute;inset:-6px;border-radius:50%;border:1px solid rgba(63,214,122,.4);animation:pulse 2.4s ease-in-out infinite}
  .wa-float:hover{transform:scale(1.08)}

  /* drawer / modal */
  .overlay{
    position:fixed;inset:0;z-index:200;background:rgba(3,4,10,.85);backdrop-filter:blur(10px);
    opacity:0;pointer-events:none;transition:.4s;display:flex;justify-content:flex-end;
  }
  .overlay.open{opacity:1;pointer-events:auto}
  .drawer{
    width:min(520px,100vw);height:100%;overflow-y:auto;background:linear-gradient(180deg,#0a0f1a,#05070d);
    border-left:1px solid var(--line);padding:40px 40px;position:relative;
    transform:translateX(100%);transition:.5s cubic-bezier(.2,.8,.3,1);
  }
  .overlay.open .drawer{transform:translateX(0)}
  .drawer-close{
    position:absolute;top:24px;right:24px;width:40px;height:40px;border-radius:50%;
    background:rgba(255,255,255,.05);border:1px solid var(--line);color:var(--ice);cursor:pointer;
    display:flex;align-items:center;justify-content:center;transition:.2s;
  }
  .drawer-close:hover{background:rgba(255,255,255,.1);transform:rotate(90deg)}
  .drawer h3{font-family:'Syne',sans-serif;font-weight:700;font-size:30px;letter-spacing:-.02em;margin:16px 0 8px}
  .drawer h3 em{font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;color:var(--glow-soft)}
  .drawer .sub{color:var(--dim);font-size:14px;line-height:1.6;margin-bottom:32px}
  .field{margin-bottom:20px}
  .field label{display:block;font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--dim);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px}
  .field select,.field input{
    width:100%;background:rgba(255,255,255,.04);border:1px solid var(--line);color:var(--ice);
    padding:14px 16px;border-radius:12px;font-size:15px;font-family:'Space Grotesk',sans-serif;transition:.2s;
  }
  .field select:focus,.field input:focus{outline:none;border-color:var(--glow);background:rgba(255,122,47,.05);box-shadow:0 0 0 4px rgba(255,122,47,.1)}
  .field-row{display:flex;gap:14px}
  .field-row .field{flex:1}
  .price-box{padding:20px 22px;border-radius:16px;margin:26px 0;background:linear-gradient(135deg,rgba(255,122,47,.12),rgba(255,122,47,.03));border:1px solid rgba(255,122,47,.25);display:flex;justify-content:space-between;align-items:center}
  .price-box-label{font-size:13px;color:var(--dim)}
  .price-box-amt{font-family:'Syne',sans-serif;font-weight:700;font-size:27px;letter-spacing:-.02em}
  .submit-btn{
    width:100%;padding:17px;border-radius:100px;border:none;background:linear-gradient(135deg,var(--sun),var(--glow));
    color:#1a0a00;font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:600;cursor:pointer;
    box-shadow:0 10px 30px rgba(255,122,47,.4);transition:.3s;margin-top:8px;
  }
  .submit-btn:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(255,122,47,.55)}
  .submit-btn:disabled{opacity:.6;cursor:wait;transform:none}
  .form-error{
    color:#ff9d9d;font-size:13px;line-height:1.5;margin-bottom:12px;
    padding:12px 14px;border-radius:10px;
    background:rgba(255,127,127,.09);border:1px solid rgba(255,127,127,.3);
  }
  .confirm{text-align:center;padding:50px 0;display:none}
  .confirm.show{display:block;animation:fadeUp .5s ease}
  .confirm-icon{
    width:78px;height:78px;border-radius:50%;margin:0 auto 22px;background:radial-gradient(circle,rgba(255,122,47,.2),rgba(255,122,47,.05));
    border:1px solid rgba(255,122,47,.4);display:flex;align-items:center;justify-content:center;box-shadow:0 0 40px rgba(255,122,47,.3);
  }
  .confirm h3{font-size:27px;margin-bottom:10px}
  .confirm .sub{margin-bottom:26px}
  .confirm-ref{
    font-family:'JetBrains Mono',monospace;font-size:21px;color:var(--glow-soft);letter-spacing:.1em;margin-bottom:28px;
    padding:16px;border-radius:12px;background:rgba(255,122,47,.08);border:1px dashed rgba(255,122,47,.4);
  }

  @media(max-width:1024px){
    .pillars-grid{grid-template-columns:1fr}
    .trips-grid{grid-template-columns:repeat(2,1fr)}
    .trust-bar{grid-template-columns:repeat(2,1fr)}
    .reviews-grid{grid-template-columns:1fr}
    .cta-band{grid-template-columns:1fr;padding:56px 36px}
    .footer-grid{grid-template-columns:1fr 1fr;gap:36px}
    .hero-stats{grid-template-columns:repeat(2,1fr)}
    .stat:nth-child(2){border-right:none}
    .stat:nth-child(1),.stat:nth-child(2){border-bottom:1px solid var(--line)}
    .about-grid{grid-template-columns:1fr}
    .incl-grid{grid-template-columns:1fr}
    .stay-card{grid-template-columns:1fr}
    .stay-photo{min-height:220px}
    .trip-layout{grid-template-columns:1fr;gap:40px}
    .trip-aside{position:static;order:-1}
    .hl-list{grid-template-columns:1fr}
  }
  /* bottom nav (mobile) */
  .bottom-nav{
    display:none;grid-template-columns:repeat(6,1fr);
    position:fixed;bottom:0;left:0;right:0;z-index:65;
    background:rgba(5,7,13,.9);backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    border-top:1px solid var(--line);
    padding:8px 2px calc(6px + env(safe-area-inset-bottom));
  }
  .bn-item{
    display:flex;flex-direction:column;align-items:center;gap:4px;
    color:var(--dim);text-decoration:none;cursor:pointer;background:none;border:none;
    font-family:'JetBrains Mono',monospace;font-size:9.5px;letter-spacing:.03em;
    text-transform:uppercase;padding:6px 0;border-radius:12px;transition:.2s;
  }
  .bn-item svg{width:19px;height:19px;transition:.2s}
  .bn-item.active{color:var(--glow)}
  .bn-item:active{background:rgba(255,255,255,.06)}

  @media(max-width:640px){
    .trips-grid{grid-template-columns:1fr}
    .hero{padding-bottom:180px}
    .hero-arrow{width:36px;height:36px}
    section{padding:90px 5vw}
    .footer-grid{grid-template-columns:1fr}
    .drawer{padding:32px 24px}
    .trips-head{align-items:flex-start}
    .tabs{width:100%;overflow-x:auto}
    body{padding-bottom:66px}
    .bottom-nav{display:grid}
    .wa-float{bottom:80px;right:18px;width:50px;height:50px}
    .trip-hero{min-height:460px;padding:120px 5vw 48px}
    .trip-hero-foot{gap:18px}
    .incl-col,.pricing-note{padding:22px}
    .trip-cta-band{padding:36px 24px}
    .art-section{margin-bottom:44px}
    .gallery-grid{grid-template-columns:repeat(2,1fr);gap:10px}
    .stay-body{padding:22px}
    .book-card{padding:24px}
    .book-price{font-size:36px}
    .itin-head{padding:15px 16px;gap:13px}
    .itin-num{width:34px;height:34px;font-size:13px}
    .itin-title{font-size:15px}
    .itin-content{padding:15px 16px 18px 16px}
    .itin-meta{gap:8px 20px}
    .lb-prev{left:10px}
    .lb-next{right:10px}
  }
</style>
@stack('styles')
</head>
<body>

<nav id="nav">
  <a class="brand" href="{{ route('home') }}">
    <div class="brand-mark">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
    </div>
    <div class="brand-name">Safiri<span>.</span></div>
  </a>
  <div class="nav-links" id="navLinks"></div>
  <div class="nav-right">
    <button class="btn btn-fill nav-cta" onclick="openBooking()">
      Plan my trip
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
    <button class="hamburger" onclick="openMobileNav()" aria-label="Open menu">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </button>
  </div>
</nav>

@yield('content')

<footer id="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <a class="brand" href="{{ route('home') }}">
        <div class="brand-mark">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
        </div>
        <div class="brand-name">Safiri<span>.</span></div>
      </a>
      <p>Locally owned safari, Kilimanjaro and Zanzibar trips, run out of Arusha, Tanzania.</p>
      <div class="footer-social">
        <a href="#" aria-label="Instagram"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg></a>
        <a href="#" aria-label="Facebook"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
        <a href="#" aria-label="TikTok"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Destinations</h4>
      <a onclick="jumpTrip('safari','northern-circuit')">Serengeti</a>
      <a onclick="jumpTrip('safari','northern-circuit')">Ngorongoro</a>
      <a onclick="goTab('kilimanjaro')">Kilimanjaro</a>
      <a onclick="goTab('zanzibar')">Zanzibar</a>
    </div>
    <div class="footer-col">
      <h4>Experiences</h4>
      <a onclick="goTab('safari')">Wildlife Safaris</a>
      <a onclick="goTab('kilimanjaro')">Mountain Treks</a>
      <a onclick="jumpTrip('zanzibar','zanzibar-beach')">Beach Escapes</a>
      <a onclick="jumpTrip('zanzibar','zanzibar-dhow')">Cultural Tours</a>
    </div>
    <div class="footer-col">
      <h4>Company</h4>
      <a href="{{ route('tours') }}">All Tours</a>
      <a href="{{ route('home') }}#about">About Us</a>
      <a onclick="openBooking()">Plan Your Trip</a>
      <a href="https://wa.me/255712345678" target="_blank" rel="noopener">Contact</a>
      <a href="#">Privacy Policy</a>
    </div>
  </div>
  <div class="footer-bottom">
    <div>© 2026 Safiri Tanzania. All rights reserved. · hello@safiri.co.tz · +255 712 345 678</div>
    <div>Arusha, Tanzania · 3°22'S · 36°41'E</div>
  </div>
</footer>

<a class="wa-float" href="https://wa.me/255712345678" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12.01 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.36 5.07L2 22l5.06-1.33A9.94 9.94 0 0 0 12.01 22C17.52 22 22 17.52 22 12S17.52 2 12.01 2zm5.5 14.14c-.23.65-1.14 1.19-1.87 1.35-.5.11-1.15.19-3.35-.72-2.82-1.17-4.63-4.03-4.77-4.22-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.23-.25.5-.32.67-.32h.48c.15 0 .36-.06.56.43l.79 1.9c.09.19.15.42.03.67-.12.25-.18.4-.36.6l-.45.52c-.14.14-.29.29-.13.57.15.28.68 1.12 1.46 1.81 1 .89 1.82 1.17 2.11 1.3.23.1.51.08.68-.1l.62-.72c.24-.28.5-.24.79-.14l1.77.83c.28.14.48.21.55.33.07.12.07.7-.16 1.35z"/></svg>
</a>

<nav class="bottom-nav" id="bottomNav">
  <button class="bn-item active" data-key="home" onclick="bnGo(this,()=>window.location.href='{{ route('home') }}')">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1Z"/></svg>
    <span>Home</span>
  </button>
  <button class="bn-item" data-key="safari" onclick="bnGo(this,()=>goTab('safari'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
    <span>Safaris</span>
  </button>
  <button class="bn-item" data-key="southern" onclick="bnGo(this,()=>jumpTrip('safari','southern-circuit'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m14.5 9.5-1.8 4.7-4.7 1.8 1.8-4.7z"/></svg>
    <span>Southern</span>
  </button>
  <button class="bn-item" data-key="kilimanjaro" onclick="bnGo(this,()=>goTab('kilimanjaro'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
    <span>Climbing</span>
  </button>
  <button class="bn-item" data-key="zanzibar" onclick="bnGo(this,()=>goTab('zanzibar'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c2-2 4-2 6 0s4 2 6 0 4-2 6 0M2 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/></svg>
    <span>Zanzibar</span>
  </button>
  <button class="bn-item" data-key="contact" onclick="bnGo(this,()=>window.location.href='{{ route('home') }}#footer')">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    <span>Contact</span>
  </button>
</nav>

<div class="lightbox" id="lightbox" onclick="if(event.target===this)closeLightbox()">
  <button class="lb-btn lb-close" onclick="closeLightbox()" aria-label="Close">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
  </button>
  <button class="lb-btn lb-prev" onclick="lightboxStep(-1)" aria-label="Previous image">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
  </button>
  <button class="lb-btn lb-next" onclick="lightboxStep(1)" aria-label="Next image">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
  </button>
  <img id="lightboxImg" src="" alt="">
  <div class="lb-count" id="lightboxCount"></div>
</div>

<div class="overlay" id="mobileNavOverlay" onclick="if(event.target===this)closeMobileNav()">
  <div class="drawer" style="width:min(380px,100vw)">
    <button class="drawer-close" onclick="closeMobileNav()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <div class="brand" style="margin-bottom:32px">
      <div class="brand-mark">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
      </div>
      <div class="brand-name">Safiri<span>.</span></div>
    </div>
    <div id="mobileNavList"></div>
    <button class="btn btn-fill" style="width:100%;justify-content:center;margin-top:24px" onclick="closeMobileNav();openBooking()">
      Plan my trip
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
  </div>
</div>

<div class="overlay" id="overlay" onclick="if(event.target===this)closeBooking()">
  <div class="drawer">
    <button class="drawer-close" onclick="closeBooking()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    <div id="drawerForm">
      <div class="tag" style="margin-bottom:8px">Booking</div>
      <h3>Plan your <em>trip</em></h3>
      <p class="sub">Tell us who's coming and when — we'll confirm availability by email within one business day.</p>

      <div class="field">
        <label>Choose trip</label>
        <select id="tripSelect" onchange="onTripChange()"></select>
      </div>

      <div class="price-box">
        <div><div class="price-box-label" id="priceLabel">per person</div></div>
        <div class="price-box-amt" id="priceAmt">$0</div>
      </div>

      <form id="bookingForm" onsubmit="submitBooking(event)">
        <div class="field"><label>Full name</label><input name="name" required placeholder="Your name"></div>
        <div class="field-row">
          <div class="field"><label>Email</label><input name="email" required type="email" placeholder="you@email.com"></div>
          <div class="field"><label>Phone</label><input name="phone" placeholder="+255 ..."></div>
        </div>
        <div class="field-row">
          <div class="field"><label>Start date</label><input name="start_date" required type="date"></div>
          <div class="field"><label>Group size</label><input name="group_size" required type="number" min="1" value="1"></div>
        </div>
        <p class="form-error" id="bookingError" hidden></p>
        <button class="submit-btn" type="submit" id="bookingSubmit">Request this trip →</button>
      </form>
    </div>

    <div class="confirm" id="confirmView">
      <div class="confirm-icon">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ff7a2f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </div>
      <h3>Request received</h3>
      <p class="sub">We'll email your itinerary and payment details within one business day.</p>
      <div class="confirm-ref" id="confirmRef">SAF-XXXXX</div>
      <button class="btn btn-line" onclick="closeBooking()">Close</button>
    </div>
  </div>
</div>

<script>
const HOME_URL = "{{ route('home') }}";

const CATS = {
  safari:      { label: "Safaris",     singular: "safari",      color: "var(--c-safari)",   prefix: "SAF" },
  kilimanjaro: { label: "Kilimanjaro", singular: "Kilimanjaro",  color: "var(--c-kili)",     prefix: "KIL" },
  zanzibar:    { label: "Zanzibar",    singular: "Zanzibar",     color: "var(--c-zanzibar)", prefix: "ZNZ" }
};

const TRIPS = @json(collect($trips ?? [])->map(function ($t) {
    $t['image'] = asset($t['image']);
    return $t;
})->values());

const NAV = @json($navItems ?? []);

/* Menu entries are stored as data — an action type plus a target — never as
   code, so nothing an admin types in the panel can be executed. These turn a
   stored action into a click handler, and anything unrecognised is left inert. */
function navAction(a){
  if (!a || !a.type) return null;
  switch (a.type) {
    case 'category': return () => goTab(a.target);
    case 'package':  return () => a.category
      ? jumpTrip(a.category, a.target)
      : (window.location.href = '/' + a.target);
    case 'about':    return () => goAbout(a.target === 'about' ? null : a.target);
    case 'url':      return () => { window.location.href = a.target; };
    default: return null;
  }
}

const NAV_ACTIONS = [];
function navHandler(a, closeDrawer){
  let fn = navAction(a);
  if (!fn) return '';
  if (closeDrawer) { const go = fn; fn = () => { go(); closeMobileNav(); }; }
  return `onclick="NAV_ACTIONS[${NAV_ACTIONS.push(fn) - 1}]()"`;
}

function esc(s){
  return String(s ?? '').replace(/[&<>"']/g, c =>
    ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' })[c]);
}

document.getElementById('navLinks').innerHTML = NAV.map(g => {
  if (g.href) {
    return `<div class="nav-item"><a class="nav-top" href="${esc(g.href)}">${esc(g.label)}</a></div>`;
  }
  const cta = navHandler(g.ctaAction);
  return `
  <div class="nav-item">
    <a class="nav-top">${esc(g.label)}
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </a>
    <div class="dropdown-panel${g.image ? '' : ' no-photo'}">
      <div class="dropdown-links">
        ${(g.links || []).map(l => `<a ${navHandler(l.action)}>${esc(l.text)}</a>`).join('')}
      </div>
      <div class="dropdown-content">
        <h4>${esc(g.title)}</h4>
        ${g.desc ? `<p>${esc(g.desc)}</p>` : ''}
        ${g.cta ? `<a class="dropdown-cta" ${cta}>${esc(g.cta)}
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>` : ''}
      </div>
      ${g.image ? `<div class="dropdown-photo" ${cta}><img src="${esc(g.image)}" alt="${esc(g.title)}" loading="lazy"></div>` : ''}
    </div>
  </div>
`;
}).join('');

function renderMobileNav(){
  document.getElementById('mobileNavList').innerHTML = NAV.map((g,i) => {
    if (g.href) {
      return `<div class="mnav-group"><a class="mnav-link" href="${esc(g.href)}">${esc(g.label)}</a></div>`;
    }
    return `
    <div class="mnav-group">
      <button class="mnav-head" data-idx="${i}" onclick="toggleMNav(${i})">
        ${esc(g.label)}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>
      <div class="mnav-body" id="mnavBody${i}">
        ${(g.links || []).map(l => `<a ${navHandler(l.action, true)}>${esc(l.text)}</a>`).join('')}
      </div>
    </div>
  `;
  }).join('');
}
renderMobileNav();

function toggleMNav(i){
  document.getElementById('mnavBody'+i).classList.toggle('open');
  document.querySelector('.mnav-head[data-idx="'+i+'"]').classList.toggle('open');
}

const mobileNavOverlay = document.getElementById('mobileNavOverlay');
function openMobileNav(){ mobileNavOverlay.classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeMobileNav(){ mobileNavOverlay.classList.remove('open'); document.body.style.overflow = ''; }

let activeCat = "safari";

function setTab(cat){
  activeCat = cat;
  if (typeof renderTrips === 'function') renderTrips();
  bnSetActive(cat);
}
function goTab(cat){
  if (typeof renderTrips === 'function') { setTab(cat); scrollToId('trips'); }
  else { window.location.href = HOME_URL + '?tab=' + cat + '#trips'; }
}
function jumpTrip(category, tripId){
  if (typeof renderTrips === 'function') {
    setTab(category);
    scrollToId('trips');
    if(!tripId) return;
    setTimeout(() => {
      const card = document.querySelector(`.trip[data-id="${tripId}"]`);
      if(!card) return;
      card.scrollIntoView({behavior:'smooth', block:'center'});
      card.classList.add('flash');
      setTimeout(() => card.classList.remove('flash'), 1500);
    }, 350);
  } else {
    window.location.href = HOME_URL + '?tab=' + category + (tripId ? '&trip=' + tripId : '') + '#trips';
  }
}
function goAbout(anchor){
  if (document.getElementById('about')) { scrollToId(anchor || 'about'); }
  else { window.location.href = HOME_URL + '#' + (anchor || 'about'); }
}

function toggleFaq(btn){ btn.parentElement.classList.toggle('open'); }
function toggleItin(btn){ btn.parentElement.classList.toggle('open'); }

/* ---- gallery lightbox ---- */
let lbImages = [];
let lbIndex = 0;
const lightboxEl = document.getElementById('lightbox');

function openLightbox(images, index){
  lbImages = images;
  lbIndex = index;
  renderLightbox();
  lightboxEl.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLightbox(){
  lightboxEl.classList.remove('open');
  document.body.style.overflow = '';
}
function lightboxStep(delta){
  if(!lbImages.length) return;
  lbIndex = (lbIndex + delta + lbImages.length) % lbImages.length;
  renderLightbox();
}
function renderLightbox(){
  document.getElementById('lightboxImg').src = lbImages[lbIndex];
  document.getElementById('lightboxCount').textContent = `${lbIndex + 1} / ${lbImages.length}`;
}
document.addEventListener('keydown', e => {
  if(!lightboxEl.classList.contains('open')) return;
  if(e.key === 'Escape') closeLightbox();
  if(e.key === 'ArrowLeft') lightboxStep(-1);
  if(e.key === 'ArrowRight') lightboxStep(1);
});

function bnSetActive(key){
  document.querySelectorAll('.bn-item').forEach(el => el.classList.toggle('active', el.dataset.key === key));
}
function bnGo(el, action){ action(); bnSetActive(el.dataset.key); }

const bnTop = document.getElementById('top');
const bnFooter = document.getElementById('footer');
if (bnTop) { new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) bnSetActive('home'); }), { threshold:.5 }).observe(bnTop); }
if (bnFooter) { new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) bnSetActive('contact'); }), { threshold:.15 }).observe(bnFooter); }

function scrollToId(id){ document.getElementById(id)?.scrollIntoView({behavior:'smooth'}); }

function trackMouse(e, el){
  const r = el.getBoundingClientRect();
  el.style.setProperty('--mx', ((e.clientX-r.left)/r.width*100)+'%');
  el.style.setProperty('--my', ((e.clientY-r.top)/r.height*100)+'%');
}

const nav = document.getElementById('nav');
window.addEventListener('scroll', () => nav.classList.toggle('solid', window.scrollY > 60), {passive:true});

const revealTargets = document.querySelectorAll('section .wrap, .cta-band');
revealTargets.forEach(el => el.classList.add('reveal'));
// threshold must stay 0: sections taller than the viewport can never satisfy a
// percentage threshold, which would leave them permanently hidden.
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); } });
}, { threshold: 0, rootMargin: '0px 0px -60px 0px' });
revealTargets.forEach(el => io.observe(el));

document.getElementById('tripSelect').innerHTML = Object.entries(CATS).map(([key,c]) => `
  <optgroup label="${c.label}">
    ${TRIPS.filter(t=>t.category===key).map(t=>`<option value="${t.id}">${t.name} · ${t.days}d · $${t.price.toLocaleString()}</option>`).join('')}
  </optgroup>
`).join('');

const overlay = document.getElementById('overlay');
const tripSelect = document.getElementById('tripSelect');
const drawerForm = document.getElementById('drawerForm');
const confirmView = document.getElementById('confirmView');
const confirmRef = document.getElementById('confirmRef');
const priceLabel = document.getElementById('priceLabel');
const priceAmt = document.getElementById('priceAmt');

function openBooking(tripId){
  tripSelect.value = tripId || TRIPS.find(t=>t.category===activeCat)?.id || TRIPS[0].id;
  onTripChange();
  drawerForm.style.display = 'block';
  confirmView.classList.remove('show');
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeBooking(){
  overlay.classList.remove('open');
  document.body.style.overflow = '';
}
function onTripChange(){
  const t = TRIPS.find(x => x.id === tripSelect.value);
  if(!t) return;
  priceLabel.textContent = `${t.days}-day ${CATS[t.category].singular} trip · per person`;
  priceAmt.textContent = '$' + t.price.toLocaleString();
}
async function submitBooking(e){
  e.preventDefault();
  const form = document.getElementById('bookingForm');
  const btn = document.getElementById('bookingSubmit');
  const err = document.getElementById('bookingError');
  const data = Object.fromEntries(new FormData(form).entries());
  data.trip = tripSelect.value;

  err.hidden = true;
  btn.disabled = true;
  btn.textContent = 'Sending…';

  try {
    const res = await fetch('{{ route('inquiries.store') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify(data),
    });
    if(!res.ok) throw new Error('Request failed');
    const json = await res.json();
    confirmRef.textContent = json.reference;
    drawerForm.style.display = 'none';
    confirmView.classList.add('show');
    form.reset();
  } catch (_) {
    err.textContent = "Couldn't send that just now — please try again, or message us on WhatsApp.";
    err.hidden = false;
  } finally {
    btn.disabled = false;
    btn.textContent = 'Request this trip →';
  }
}
</script>
@stack('scripts')
</body>
</html>
