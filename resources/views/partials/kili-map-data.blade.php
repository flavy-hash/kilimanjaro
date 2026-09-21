{{-- Shared Kilimanjaro route-map data + SVG builders.
     Included wherever a route map is drawn (homepage explorer, individual
     trip pages) so waypoint positions and route paths never drift between
     the two — only the surrounding page-specific wiring differs. --}}
<script>
const KILI_W = {
  peak:        {x:500, y:225, label:"Uhuru Peak",       elev:"5,895 m", type:"peak"},

  londorossi:  {x:128, y:268, label:"Londorossi Gate",  elev:null,      type:"gate-up"},
  shiraPlat:   {x:222, y:250, label:"Shira Plateau",     elev:null,      type:"waypoint"},
  shira1:      {x:288, y:302, label:"Shira 1 Camp",      elev:"3,610 m", type:"camp"},
  shira2:      {x:352, y:336, label:"Shira 2 Camp",      elev:"3,850 m", type:"camp"},
  lavaTower:   {x:424, y:352, label:"Lava Tower",        elev:"4,630 m", type:"waypoint"},
  moirHut:     {x:410, y:258, label:"Moir Hut",          elev:"4,161 m", type:"camp"},
  buffaloCamp: {x:472, y:244, label:"Buffalo Camp",      elev:"4,020 m", type:"camp"},
  thirdCave:   {x:540, y:288, label:"3rd Cave Camp",     elev:"3,800 m", type:"camp"},
  schoolHut:   {x:512, y:344, label:"School Hut",        elev:"4,800 m", type:"camp"},

  machameGate: {x:328, y:624, label:"Machame Gate",      elev:null,      type:"gate-up"},
  machameCamp: {x:352, y:498, label:"Machame Camp",      elev:"2,835 m", type:"camp"},
  umbweGate:   {x:262, y:600, label:"Umbwe Gate",        elev:null,      type:"gate-up"},
  umbweCamp:   {x:298, y:512, label:"Umbwe Camp",        elev:"2,850 m", type:"camp"},
  barranco:    {x:404, y:404, label:"Barranco Camp",     elev:"3,900 m", type:"camp"},
  karanga:     {x:452, y:434, label:"Karanga Camp",      elev:"3,995 m", type:"camp"},
  barafu:      {x:498, y:400, label:"Barafu Camp",       elev:"4,673 m", type:"camp"},
  millenium:   {x:436, y:476, label:"Millenium Camp",    elev:"3,950 m", type:"camp"},
  mwekaGate:   {x:408, y:640, label:"Mweka Gate",        elev:null,      type:"gate-down"},

  maranguGate: {x:634, y:634, label:"Marangu Gate",      elev:null,      type:"gate-both"},
  mandara:     {x:606, y:534, label:"Mandara Camp",      elev:"2,700 m", type:"camp"},
  horombo:     {x:584, y:466, label:"Horombo Camp",      elev:"3,720 m", type:"camp"},
  kibo:        {x:560, y:342, label:"Kibo Camp",         elev:"4,720 m", type:"camp"},

  nalemuruGate:{x:660, y:196, label:"Nalemuru Gate",     elev:null,      type:"gate-up"},
  simba:       {x:626, y:238, label:"Simba Camp",        elev:"2,671 m", type:"camp"},
  secondCave:  {x:592, y:280, label:"Second Cave Camp",  elev:"3,450 m", type:"camp"},
  kikelewa:    {x:604, y:322, label:"Kikelewa Camp",     elev:"3,630 m", type:"camp"},
  mawenzi:     {x:572, y:352, label:"Mawenzi Tarn Camp", elev:"4,315 m", type:"camp"},
};

const KILI_ROUTE_DEFS = [
  {
    id:"machame", name:"Machame Route", days:"6–7 days", difficulty:"Moderate",
    ascent:["machameGate","machameCamp","shira2","lavaTower","barranco","karanga","barafu","peak"],
    descent:["peak","millenium","mwekaGate"],
    text:"Nicknamed the “Whiskey Route,” Machame is the second most popular trail on Kilimanjaro. It climbs steeply through rainforest on the southern slope before crossing the Shira Plateau toward Lava Tower, then drops into the Barranco Valley for a scenic acclimatization loop before the summit push."
  },
  {
    id:"lemosho", name:"Lemosho Route", days:"7–8 days", difficulty:"Moderate",
    ascent:["londorossi","shiraPlat","shira1","shira2","lavaTower","barranco","karanga","barafu","peak"],
    descent:["peak","millenium","mwekaGate"],
    text:"Starting on Kilimanjaro's remote western side, Lemosho has one of the longest and most gradual approaches, crossing the wide Shira Plateau below the jagged Cathedral Peak. Its extra acclimatization days give it one of the best summit-success rates of any route."
  },
  {
    id:"marangu", name:"Marangu Route", days:"5–6 days", difficulty:"Easiest",
    ascent:["maranguGate","mandara","horombo","kibo","peak"],
    descent:["peak","kibo","horombo","mandara","maranguGate"],
    text:"Marangu is the only route with sleeping huts instead of tents, and the only one where climbers descend the same path they climbed. Its gentle, direct profile makes it popular with first-timers, though the fast ascent gives less time to acclimatize."
  },
  {
    id:"rongai", name:"Rongai Route", days:"6–7 days", difficulty:"Moderate",
    ascent:["nalemuruGate","simba","secondCave","kikelewa","mawenzi","kibo","peak"],
    descent:["peak","kibo","horombo","maranguGate"],
    text:"Rongai is the only route approaching from the dry northern side, near the Kenyan border, through quiet pine forest. It sees far fewer climbers than the southern routes and descends via Marangu, giving trekkers views of two very different sides of the mountain."
  },
  {
    id:"umbwe", name:"Umbwe Route", days:"6 days", difficulty:"Hard",
    ascent:["umbweGate","umbweCamp","barranco","karanga","barafu","peak"],
    descent:["peak","millenium","mwekaGate"],
    text:"The steepest and most direct line up Kilimanjaro, Umbwe climbs a narrow ridge straight into the Barranco Valley with almost no gradual buildup. It's best suited to strong, experienced trekkers who can handle rapid elevation gain."
  },
  {
    id:"northern", name:"Northern Circuit", days:"8–9 days", difficulty:"Moderate",
    ascent:["londorossi","shiraPlat","shira1","moirHut","buffaloCamp","thirdCave","schoolHut","peak"],
    descent:["peak","millenium","mwekaGate"],
    text:"The longest route on the mountain, the Northern Circuit begins on the western slope and arcs almost all the way around the remote, rarely-visited northern side before summiting from the east. The extra distance gives outstanding acclimatization and some of the quietest trails on Kilimanjaro."
  },
];

function kiliSmoothPath(points){
  if (points.length < 2) return "";
  let d = `M ${points[0].x} ${points[0].y}`;
  for (let i = 0; i < points.length - 1; i++) {
    const p0 = points[i - 1] || points[i];
    const p1 = points[i];
    const p2 = points[i + 1];
    const p3 = points[i + 2] || p2;
    const c1x = p1.x + (p2.x - p0.x) / 6;
    const c1y = p1.y + (p2.y - p0.y) / 6;
    const c2x = p2.x - (p3.x - p1.x) / 6;
    const c2y = p2.y - (p3.y - p1.y) / 6;
    d += ` C ${c1x} ${c1y}, ${c2x} ${c2y}, ${p2.x} ${p2.y}`;
  }
  return d;
}

function kiliMarkerSvg(w, dim){
  const labelCls = dim ? "marker-label marker-label-dim" : "marker-label";
  const elevCls = dim ? "marker-elev marker-elev-dim" : "marker-elev";
  const fill = dim ? "#b9b7a4" : (w.type === "gate-down" ? "#3f6b4a" : "#20261f");
  const above = w.y > 380;
  const ly = above ? -14 : 20;
  const anchor = w.x > 620 ? "end" : (w.x < 140 ? "start" : "middle");
  const tx = anchor === "end" ? -8 : (anchor === "start" ? 8 : 0);

  let icon = "";
  if (w.type === "gate-up") {
    icon = `<rect x="-6" y="-6" width="12" height="12" rx="2" transform="rotate(45)" fill="${fill}"/>
            <path d="M0 -3 L0 3 M-2.5 -0.5 L0 -3 L2.5 -0.5" stroke="#fff" stroke-width="1.1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>`;
  } else if (w.type === "gate-down") {
    icon = `<rect x="-6" y="-6" width="12" height="12" rx="2" transform="rotate(45)" fill="${fill}"/>
            <path d="M0 3 L0 -3 M-2.5 0.5 L0 3 L2.5 0.5" stroke="#fff" stroke-width="1.1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>`;
  } else if (w.type === "gate-both") {
    icon = `<rect x="-6" y="-6" width="12" height="12" rx="2" transform="rotate(45)" fill="${fill}"/>
            <path d="M-1.6 -3 L-1.6 3 M-4 -0.5 L-1.6 -3 L0.8 -0.5" stroke="#fff" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 3 L2 -3 M-0.4 0.5 L2 3 L4.4 0.5" stroke="#fff" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>`;
  } else {
    icon = `<circle r="5" fill="#fff" stroke="${fill}" stroke-width="2.4"/>
            <circle r="1.6" fill="${fill}"/>`;
  }

  return `
  <g transform="translate(${w.x},${w.y})">
    ${icon}
    <text class="${labelCls}" x="${tx}" y="${ly}" text-anchor="${anchor}">${w.label}</text>
    ${w.elev ? `<text class="${elevCls}" x="${tx}" y="${ly + 13}" text-anchor="${anchor}">${w.elev}</text>` : ""}
  </g>`;
}

function kiliPeakSvg(){
  const w = KILI_W.peak;
  return `
  <g transform="translate(${w.x},${w.y})">
    <path d="M0 -14 L0 10" stroke="#20261f" stroke-width="2"/>
    <path d="M0 -14 L14 -8 L0 -2 Z" fill="#bd5433"/>
    <circle cy="11" r="2" fill="#20261f"/>
    <text class="peak-label" x="18" y="-1">${w.label}</text>
    <text class="marker-elev" x="18" y="12">${w.elev}</text>
  </g>`;
}

function kiliContours(){
  const rings = [
    {rx:620, ry:430, op:0.05}, {rx:540, ry:370, op:0.06}, {rx:460, ry:315, op:0.07},
    {rx:385, ry:265, op:0.09}, {rx:315, ry:220, op:0.11}, {rx:250, ry:178, op:0.14},
    {rx:190, ry:138, op:0.18}, {rx:135, ry:100, op:0.22}, {rx:85,  ry:64,  op:0.28},
  ];
  return rings.map(r => `<ellipse cx="500" cy="300" rx="${r.rx}" ry="${r.ry}" fill="none" stroke="#20261f" stroke-opacity="${r.op}" stroke-width="1.4"/>`).join("");
}

function kiliDifficultyColor(d){
  return { Easiest:"#7fae86", Moderate:"#c99a3f", Hard:"#bd5433" }[d] || "#c99a3f";
}

function kiliBuildMapSvg(route){
  const activeIds = new Set([...route.ascent, ...route.descent]);
  const allMarkers = Object.entries(KILI_W)
    .filter(([id]) => id !== 'peak')
    .map(([id, w]) => kiliMarkerSvg(w, !activeIds.has(id)))
    .join('');

  const ascentPts = route.ascent.map(id => KILI_W[id]);
  const descentPts = route.descent.map(id => KILI_W[id]);
  const ascentD = kiliSmoothPath(ascentPts);
  const descentD = kiliSmoothPath(descentPts);

  return `
    <rect x="0" y="0" width="800" height="660" fill="#f3f0e6"/>
    ${kiliContours()}
    <text x="500" y="622" text-anchor="middle" font-family="Syne, sans-serif" font-size="13" fill="#c9c3ac" letter-spacing="2">KILIMANJARO NATIONAL PARK</text>

    ${allMarkers}

    <path d="${ascentD}" fill="none" stroke="#20261f" stroke-width="2.4" stroke-dasharray="1 7" stroke-linecap="round" class="kili-route-path"/>
    ${route.descent.length > 1 ? `<path d="${descentD}" fill="none" stroke="#3f6b4a" stroke-width="2.4" stroke-dasharray="1 7" stroke-linecap="round" class="kili-route-path"/>` : ""}

    ${route.ascent.filter(id => KILI_W[id].type.startsWith('gate')).map(id => kiliMarkerSvg(KILI_W[id], false)).join('')}
    ${route.descent.filter(id => KILI_W[id].type.startsWith('gate')).map(id => kiliMarkerSvg(KILI_W[id], false)).join('')}
    ${route.ascent.filter(id => KILI_W[id].type === 'camp' || KILI_W[id].type === 'waypoint').map(id => kiliMarkerSvg(KILI_W[id], false)).join('')}
    ${route.descent.filter(id => KILI_W[id].type === 'camp' || KILI_W[id].type === 'waypoint').map(id => kiliMarkerSvg(KILI_W[id], false)).join('')}
    ${kiliPeakSvg()}
  `;
}

function kiliAnimateRoutePaths(mapEl){
  requestAnimationFrame(() => {
    mapEl.querySelectorAll('.kili-route-path').forEach(p => {
      const len = p.getTotalLength();
      p.style.transition = 'none';
      p.style.strokeDasharray = `${len}`;
      p.style.strokeDashoffset = `${len}`;
      p.getBoundingClientRect();
      p.style.transition = 'stroke-dashoffset 1.1s cubic-bezier(.2,.7,.3,1)';
      p.style.strokeDashoffset = '0';
      setTimeout(() => { p.style.strokeDasharray = '1 7'; p.style.transition = 'none'; }, 1150);
    });
  });
}
</script>
