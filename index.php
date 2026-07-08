<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Aura Creator — Build Anything with AI</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
:root{
  --bg:#07060F;--surf:#0D0B1A;--card:rgba(19,17,32,0.9);
  --b:rgba(255,255,255,0.07);--bv:rgba(139,92,246,0.32);
  --v:#8B5CF6;--v2:#6D28D9;--cyan:#22D3EE;--gold:#F59E0B;
  --pink:#EC4899;--green:#22C55E;--red:#EF4444;
  --tx:#F1F0FF;--mut:rgba(241,240,255,0.5);--dim:rgba(241,240,255,0.22);
  --r:12px;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;overflow:hidden;background:var(--bg);color:var(--tx);font-family:'Inter',sans-serif;font-size:13px}
button,input,textarea,select{font-family:'Inter',sans-serif}

/* ── NAV ── */
#app{display:flex;flex-direction:column;height:100vh;overflow:hidden}
.nav{display:flex;align-items:center;padding:0 18px;height:50px;border-bottom:1px solid var(--b);background:#09081A;gap:10px;flex-shrink:0}
.logo{display:flex;align-items:center;gap:8px;text-decoration:none;flex-shrink:0}
.lmark{width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,var(--v),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:900;color:#fff;font-family:'Space Grotesk',sans-serif}
.lname{font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:800;color:var(--tx)}
.lname b{color:var(--v)}
.mode-row{display:flex;gap:2px;flex:1;justify-content:center}
.mt{display:flex;align-items:center;gap:4px;padding:5px 12px;border-radius:18px;font-size:11px;font-weight:600;cursor:pointer;border:1px solid transparent;color:var(--mut);background:none;transition:all .15s;white-space:nowrap}
.mt:hover{color:var(--tx);border-color:var(--b)}
.mt.on{background:rgba(139,92,246,0.14);border-color:var(--bv);color:#C4B5FD}
.nav-r{display:flex;gap:7px;align-items:center;flex-shrink:0}
.nb{background:none;border:1px solid var(--b);color:var(--mut);padding:5px 13px;border-radius:7px;font-size:11px;cursor:pointer;transition:all .15s;white-space:nowrap}
.nb:hover{border-color:var(--bv);color:var(--tx)}
.nb.hi{background:linear-gradient(135deg,var(--v),var(--v2));border-color:transparent;color:#fff;font-weight:700}
.nb.hi:hover{opacity:.88}
.uav{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--v),var(--cyan));display:none;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;cursor:pointer;border:2px solid var(--bv)}

/* ── WORKSPACE ── */
.ws{display:flex;flex:1;overflow:hidden;min-height:0}

/* ── LEFT PANEL ── */
.lp{width:255px;flex-shrink:0;border-right:1px solid var(--b);background:#09081A;display:flex;flex-direction:column;overflow:hidden}
.lps{padding:13px;border-bottom:1px solid var(--b);flex-shrink:0}
.lbl{font-size:9px;font-weight:700;letter-spacing:.1em;color:var(--dim);text-transform:uppercase;margin-bottom:8px}
.pta{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--b);border-radius:10px;color:var(--tx);font-size:11px;padding:9px 11px;resize:none;outline:none;line-height:1.65;transition:border-color .2s;min-height:84px}
.pta:focus{border-color:var(--bv)}
.pta::placeholder{color:var(--dim)}
.hchips{display:flex;gap:5px;flex-wrap:wrap;margin-top:7px}
.hc{font-size:10px;color:var(--dim);background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:6px;padding:3px 8px;cursor:pointer;transition:all .15s}
.hc:hover{color:var(--v);border-color:var(--bv)}
.bcreate{width:100%;margin-top:9px;background:linear-gradient(135deg,var(--v),var(--v2));border:none;color:#fff;padding:10px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:opacity .2s}
.bcreate:hover{opacity:.87}
.bcreate:disabled{opacity:.4;cursor:not-allowed}
.lpscroll{flex:1;overflow-y:auto;padding:11px;scrollbar-width:thin;scrollbar-color:rgba(139,92,246,0.25) transparent}
.lpscroll::-webkit-scrollbar{width:3px}
.lpscroll::-webkit-scrollbar-thumb{background:rgba(139,92,246,0.25);border-radius:3px}
.pj{background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:9px;padding:9px 10px;margin-bottom:7px;cursor:pointer;transition:all .15s}
.pj:hover{border-color:rgba(139,92,246,0.28)}
.pj.on{border-color:rgba(139,92,246,0.45);background:rgba(139,92,246,0.09)}
.pjn{font-size:11px;font-weight:600;color:var(--tx);margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.pjm{font-size:10px;color:var(--mut);display:flex;align-items:center;gap:4px}
.sdot{width:5px;height:5px;border-radius:50%;flex-shrink:0}
.dlive{background:var(--green);animation:blink 2s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.4}}
.lpbot{padding:11px 12px;border-top:1px solid var(--b);flex-shrink:0}
.crrow{display:flex;justify-content:space-between;align-items:center;margin-bottom:5px}
.crl{font-size:10px;color:var(--mut)}
.crv{font-size:10px;font-weight:700;color:#A78BFA}
.ptrack{height:4px;background:rgba(255,255,255,0.06);border-radius:4px;overflow:hidden}
.pfill{height:100%;background:linear-gradient(90deg,var(--v),var(--cyan));border-radius:4px;transition:width .5s}
.bdeploy{width:100%;margin-top:9px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.22);color:#86EFAC;padding:8px;border-radius:9px;font-size:11px;font-weight:700;cursor:pointer;display:none;align-items:center;justify-content:center;gap:5px;transition:all .15s}
.bdeploy:hover{background:rgba(34,197,94,0.18)}

/* ── RIGHT PANEL ── */
.rp{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0}
.pbar{display:flex;align-items:center;gap:7px;padding:7px 13px;border-bottom:1px solid var(--b);background:#09081A;flex-shrink:0}
.wdots{display:flex;gap:5px;flex-shrink:0}
.wd{width:10px;height:10px;border-radius:50%}
.urlbar{flex:1;background:rgba(255,255,255,0.04);border:1px solid var(--b);border-radius:18px;padding:5px 13px;display:flex;align-items:center;gap:6px;font-size:10px;color:var(--mut);overflow:hidden;min-width:0}
.pbacts{display:flex;gap:5px}
.pa{background:rgba(255,255,255,0.04);border:1px solid var(--b);color:var(--mut);padding:4px 9px;border-radius:7px;font-size:11px;cursor:pointer;display:flex;align-items:center;gap:4px;transition:all .15s;white-space:nowrap}
.pa:hover{border-color:var(--bv);color:var(--tx)}
.pa.pu{background:rgba(139,92,246,0.13);border-color:var(--bv);color:#C4B5FD}

/* ── PREVIEW ── */
.pvwrap{flex:1;overflow:hidden;display:flex;flex-direction:column;min-height:0;background:#0C0A1C}
#pv-welcome{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:18px;padding:30px;text-align:center}
#pv-loading{flex:1;display:none;flex-direction:column;align-items:center;justify-content:center;gap:14px}
#pv-result{flex:1;display:none;overflow:hidden;background:#fff;min-height:0}
#pv-result iframe{width:100%;height:100%;border:none;display:block}
.spn{width:38px;height:38px;border:3px solid rgba(139,92,246,0.14);border-top-color:var(--v);border-radius:50%;animation:spin .72s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.wico{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,var(--v),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:900;color:#fff;font-family:'Space Grotesk',sans-serif}
.wtit{font-family:'Space Grotesk',sans-serif;font-size:19px;font-weight:800;color:var(--tx)}
.wsub{font-size:12px;color:var(--mut);line-height:1.75;max-width:400px}
.qrow{display:flex;gap:7px;flex-wrap:wrap;justify-content:center;max-width:380px}
.qc{padding:6px 13px;border-radius:18px;font-size:11px;font-weight:600;cursor:pointer;border:1px solid;transition:all .15s}
.qc:hover{opacity:.8;transform:translateY(-1px)}
.ltxt{font-size:13px;font-weight:600;color:rgba(241,240,255,.8)}
.lsub{font-size:11px;color:var(--dim);margin-top:2px}
.lprog{width:200px;height:3px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden;margin-top:6px}
.lpfill{height:100%;background:linear-gradient(90deg,var(--v),var(--cyan));border-radius:3px;transition:width .4s;width:0%}

/* ── CHAT BAR ── */
.chatbar{display:flex;gap:7px;align-items:center;padding:9px 13px;border-top:1px solid var(--b);background:#09081A;flex-shrink:0}
.ci{flex:1;background:rgba(255,255,255,0.04);border:1px solid var(--b);border-radius:9px;color:var(--tx);font-size:11px;padding:8px 12px;outline:none;transition:border-color .2s}
.ci:focus{border-color:var(--bv)}
.ci::placeholder{color:var(--dim)}
.csend{background:linear-gradient(135deg,var(--v),var(--v2));border:none;color:#fff;width:34px;height:34px;border-radius:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:opacity .2s}
.csend:hover{opacity:.85}
.csend:disabled{opacity:.35;cursor:not-allowed}

/* ── MODALS ── */
.mbg{position:fixed;inset:0;background:rgba(5,4,14,.9);display:none;align-items:center;justify-content:center;z-index:2000;padding:16px;backdrop-filter:blur(4px)}
.mbg.open{display:flex}
.mbox{background:#0F0C1F;border:1px solid var(--bv);border-radius:18px;padding:26px;width:100%;max-height:90vh;overflow-y:auto;position:relative}
.mbox::-webkit-scrollbar{width:3px}
.mbox::-webkit-scrollbar-thumb{background:rgba(139,92,246,0.3);border-radius:3px}
.mxbtn{position:absolute;top:14px;right:14px;background:none;border:none;color:var(--dim);font-size:18px;cursor:pointer;line-height:1;padding:4px}
.mxbtn:hover{color:var(--tx)}
.mtitle{font-family:'Space Grotesk',sans-serif;font-size:17px;font-weight:800;color:var(--tx);margin-bottom:5px}
.msub{font-size:11px;color:var(--mut);line-height:1.65;margin-bottom:18px}

/* plans */
.pgrid{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-bottom:16px}
.pc{background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:11px;padding:13px;cursor:pointer;transition:all .15s;position:relative}
.pc:hover,.pc.on{border-color:var(--bv);background:rgba(139,92,246,0.09)}
.pbadge{position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:var(--v);color:#fff;font-size:9px;font-weight:800;padding:2px 10px;border-radius:0 0 7px 7px;white-space:nowrap}
.pname{font-size:10px;font-weight:700;color:var(--mut);margin-bottom:4px}
.pprice{font-family:'Space Grotesk',sans-serif;font-size:19px;font-weight:800;color:var(--tx)}
.pprice span{font-size:10px;font-weight:400;color:var(--mut)}
.pdesc{font-size:10px;color:var(--mut);margin-top:4px;line-height:1.5}
.pfeats{margin-top:7px;display:flex;flex-direction:column;gap:3px}
.pf{font-size:10px;color:var(--mut);display:flex;align-items:center;gap:4px}
.pf i{color:var(--cyan);font-size:11px}

/* pay btn */
.bpay{width:100%;background:linear-gradient(135deg,var(--v),var(--v2));border:none;color:#fff;padding:12px;border-radius:11px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;margin-bottom:9px;transition:opacity .2s}
.bpay:hover{opacity:.9}
.pnote{font-size:10px;color:var(--dim);text-align:center;line-height:1.6}

/* form */
.ff{margin-bottom:12px}
.fl{font-size:10px;font-weight:600;color:var(--mut);margin-bottom:5px;display:block}
.fi{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--b);border-radius:8px;color:var(--tx);font-size:12px;padding:9px 12px;outline:none;transition:border-color .2s}
.fi:focus{border-color:var(--bv)}
.fi::placeholder{color:var(--dim)}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:9px}
.bfull{width:100%;padding:11px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;border:none;transition:opacity .2s}
.bfull:hover{opacity:.88}
.bv{background:linear-gradient(135deg,var(--v),var(--v2));color:#fff}
.bout{background:none;border:1px solid var(--b);color:var(--mut);margin-top:7px}
.slog{display:flex;gap:7px;margin-bottom:13px}
.sl{flex:1;background:rgba(255,255,255,0.04);border:1px solid var(--b);color:var(--mut);padding:8px;border-radius:8px;font-size:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;transition:all .15s}
.sl:hover{border-color:var(--bv);color:var(--tx)}
.ordiv{text-align:center;font-size:10px;color:var(--dim);position:relative;margin-bottom:12px}
.ordiv::before,.ordiv::after{content:'';position:absolute;top:50%;width:42%;height:1px;background:var(--b)}
.ordiv::before{left:0}.ordiv::after{right:0}

/* deploy */
.plats{display:grid;grid-template-columns:repeat(3,1fr);gap:7px;margin-bottom:14px}
.plt{background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:9px;padding:11px 7px;cursor:pointer;text-align:center;transition:all .15s}
.plt:hover,.plt.on{border-color:var(--bv);background:rgba(139,92,246,0.09)}
.plic{font-size:18px;margin-bottom:3px}
.plnm{font-size:10px;font-weight:700;color:var(--tx)}
.plsb{font-size:9px;color:var(--dim);margin-top:2px}
.dstep{background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:9px;padding:11px;margin-bottom:8px}
.dsh{display:flex;align-items:flex-start;gap:8px;margin-bottom:5px}
.dsn{width:20px;height:20px;border-radius:50%;background:var(--v);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;color:#fff;flex-shrink:0;margin-top:1px}
.dsti{font-size:11px;font-weight:700;color:var(--tx)}
.dsb{font-size:10px;color:var(--mut);line-height:1.7;padding-left:28px}
.dsb code{background:rgba(255,255,255,0.06);padding:1px 5px;border-radius:4px;font-size:10px;color:#C4B5FD;font-family:monospace}
.dsb a{color:var(--cyan);text-decoration:none}
.dsb a:hover{text-decoration:underline}

/* download */
.dlopt{background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:10px;padding:12px 14px;cursor:pointer;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;transition:all .15s}
.dlopt:hover,.dlopt.on{border-color:var(--bv);background:rgba(139,92,246,0.09)}
.dlname{font-size:12px;font-weight:700;color:var(--tx)}
.dldesc{font-size:10px;color:var(--mut);margin-top:2px}
.dlpr{font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:800;color:var(--tx);white-space:nowrap;margin-left:12px}

/* pricing helper */
.price-hint{background:rgba(139,92,246,0.08);border:1px solid rgba(139,92,246,0.2);border-radius:9px;padding:10px 13px;margin-bottom:14px;font-size:11px;color:#C4B5FD;line-height:1.65}

/* share */
.sharebox{background:rgba(255,255,255,0.04);border:1px solid var(--b);border-radius:8px;padding:9px 12px;display:flex;align-items:center;gap:8px;margin-bottom:12px}
.sharelink{flex:1;font-size:10px;color:var(--mut);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.cpybtn{background:var(--v);border:none;color:#fff;padding:5px 10px;border-radius:6px;font-size:10px;cursor:pointer;flex-shrink:0;font-family:'Inter',sans-serif}
.shareacts{display:flex;gap:7px}

/* zip preview */
#zip-files{background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:9px;padding:12px;margin-bottom:14px;font-size:10px;color:var(--mut);line-height:2;font-family:monospace;max-height:160px;overflow-y:auto}
#zip-files .f{color:#A78BFA}
#zip-files .d{color:var(--cyan)}

/* toast */
.toast{position:fixed;bottom:22px;right:22px;background:var(--v);color:#fff;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;opacity:0;transition:opacity .3s;pointer-events:none;z-index:9999;max-width:300px;line-height:1.4}
.toast.show{opacity:1}
.toast.ok{background:#15803D}
.toast.err{background:#B91C1C}

/* divider */
.hdiv{height:1px;background:var(--b);margin:14px 0}

@media(max-width:900px){.mode-row{display:none}}
@media(max-width:650px){.lp{display:none}}
</style>
</head>
<body>
<div id="app">

<!-- NAV -->
<nav class="nav">
  <a class="logo" href="#">
    <div class="lmark">A</div>
    <span class="lname">Aura <b>Creator</b></span>
  </a>
  <div class="mode-row">
    <button class="mt on" onclick="setMode(this,'Website')" data-m="Website"><i class="ti ti-world" style="font-size:11px"></i> Website</button>
    <button class="mt" onclick="setMode(this,'App')" data-m="App"><i class="ti ti-device-mobile" style="font-size:11px"></i> App</button>
    <button class="mt" onclick="setMode(this,'Design Studio')" data-m="Design Studio"><i class="ti ti-brush" style="font-size:11px"></i> Design Studio</button>
    <button class="mt" onclick="setMode(this,'Video')" data-m="Video"><i class="ti ti-video" style="font-size:11px"></i> Video</button>
    <button class="mt" onclick="setMode(this,'Launch Kit')" data-m="Launch Kit"><i class="ti ti-rocket" style="font-size:11px"></i> Launch Kit</button>
  </div>
  <div class="nav-r">
    <div class="uav" id="uav" onclick="showProfile()">S</div>
    <button class="nb" onclick="openM('m-signin')" id="btn-signin">Sign in</button>
    <button class="nb hi" onclick="openM('m-upgrade')">Upgrade ✦</button>
  </div>
</nav>

<!-- WORKSPACE -->
<div class="ws">

  <!-- LEFT -->
  <div class="lp">
    <div class="lps">
      <div class="lbl">What do you want to build?</div>
      <textarea class="pta" id="pt" placeholder="A booking website for my jewelry store in Hyderabad with WhatsApp and appointment form…" rows="4"></textarea>
      <div class="hchips">
        <span class="hc" onclick="fhint('Jewelry store website with product gallery, WhatsApp and booking')">💎 Jewellery</span>
        <span class="hc" onclick="fhint('Grocery delivery app with categories, cart and UPI payment')">🛒 Grocery</span>
        <span class="hc" onclick="fhint('Multiplayer games hub app with leaderboard and rewards')">🎮 Games</span>
        <span class="hc" onclick="fhint('Beauty clinic website with services, before/after photos and WhatsApp')">💆 Clinic</span>
      </div>
      <button class="bcreate" id="btn-create" onclick="generate()">
        <i class="ti ti-sparkles" style="font-size:12px"></i> Create with Aura
      </button>
    </div>
    <div class="lpscroll">
      <div class="lbl" style="margin-top:2px">My Projects</div>
      <div id="proj-list"><div style="text-align:center;padding:18px 8px;color:var(--dim);font-size:11px;line-height:1.7"><i class="ti ti-folder-open" style="font-size:22px;display:block;margin-bottom:6px;opacity:.4"></i>Your creations appear here</div></div>
    </div>
    <div class="lpbot">
      <div class="crrow"><span class="crl">Free credits</span><span class="crv" id="cr-v">3 left</span></div>
      <div class="ptrack"><div class="pfill" id="cr-bar" style="width:100%"></div></div>
      <button class="bdeploy" id="btn-deploy" onclick="openM('m-deploy')">
        <i class="ti ti-rocket" style="font-size:12px"></i> Deploy &amp; Download
      </button>
    </div>
  </div>

  <!-- RIGHT -->
  <div class="rp">
    <div class="pbar">
      <div class="wdots">
        <div class="wd" style="background:#FF5F57"></div>
        <div class="wd" style="background:#FEBC2E"></div>
        <div class="wd" style="background:#28C840"></div>
      </div>
      <div class="urlbar">
        <i class="ti ti-lock" style="font-size:11px;color:var(--green)"></i>
        <span id="url-txt" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1">aura-preview.local — describe your idea and create</span>
      </div>
      <div class="pbacts">
        <button class="pa" onclick="reloadPv()"><i class="ti ti-refresh" style="font-size:11px"></i></button>
        <button class="pa pu" onclick="openShare()"><i class="ti ti-share" style="font-size:11px"></i> Share</button>
        <button class="pa" onclick="openM('m-deploy')"><i class="ti ti-rocket" style="font-size:11px"></i> Deploy</button>
        <button class="pa" onclick="openM('m-download')"><i class="ti ti-download" style="font-size:11px"></i> Download</button>
      </div>
    </div>

    <div class="pvwrap">
      <!-- WELCOME -->
      <div id="pv-welcome">
        <div class="wico">A</div>
        <div>
          <div class="wtit">Build anything with AI</div>
          <div class="wsub" style="margin-top:7px">Type what you want — a jewelry store, a grocery app, a game — and Aura builds it live. Preview instantly. Pay only to download or deploy.</div>
        </div>
        <div class="qrow">
          <span class="qc" style="border-color:rgba(139,92,246,.35);color:#C4B5FD;background:rgba(139,92,246,.09)" onclick="qcreate('A beautiful jewelry store website with product gallery, gold/silver collections, WhatsApp order button and appointment booking','Website')">💎 Jewellery site</span>
          <span class="qc" style="border-color:rgba(245,158,11,.35);color:#FCD34D;background:rgba(245,158,11,.07)" onclick="qcreate('A grocery delivery app with categories, cart, UPI payment and order tracking','App')">🛒 Grocery app</span>
          <span class="qc" style="border-color:rgba(236,72,153,.35);color:#F9A8D4;background:rgba(236,72,153,.07)" onclick="qcreate('A multiplayer games hub with leaderboard, daily rewards and coin system','App')">🎮 Games app</span>
          <span class="qc" style="border-color:rgba(34,211,238,.35);color:#67E8F9;background:rgba(34,211,238,.07)" onclick="qcreate('A 30-second Instagram reel script for a skin glow treatment with hook, scenes and CTA','Video')">🎬 Reel script</span>
          <span class="qc" style="border-color:rgba(34,197,94,.35);color:#86EFAC;background:rgba(34,197,94,.07)" onclick="qcreate('Complete business launch kit for my Indian restaurant — pricing, WhatsApp scripts, 30-day content plan','Launch Kit')">🚀 Launch kit</span>
        </div>
      </div>
      <!-- LOADING -->
      <div id="pv-loading">
        <div class="spn"></div>
        <div class="ltxt" id="ld-t">Aura is building your creation…</div>
        <div class="lsub" id="ld-s">Reading your idea</div>
        <div class="lprog"><div class="lpfill" id="ld-f"></div></div>
      </div>
      <!-- RESULT -->
      <div id="pv-result">
        <iframe id="pv-frame" title="Live preview" sandbox="allow-scripts allow-same-origin" allow="same-origin"></iframe>
      </div>
    </div>

    <div class="chatbar">
      <input class="ci" id="ci" placeholder="Edit your creation — 'add WhatsApp button', 'make header dark purple', 'add contact form'…">
      <button class="csend" id="btn-chat" onclick="editNow()" aria-label="Send"><i class="ti ti-arrow-up" style="font-size:13px"></i></button>
    </div>
  </div>
</div>
</div>

<!-- ═══════════ MODALS ═══════════ -->

<!-- SIGN IN -->
<div class="mbg" id="m-signin">
  <div class="mbox" style="max-width:360px">
    <button class="mxbtn" onclick="closeM('m-signin')"><i class="ti ti-x"></i></button>
    <div class="wico" style="width:40px;height:40px;font-size:16px;margin-bottom:14px">A</div>
    <div class="mtitle">Welcome back</div>
    <div class="msub">Sign in to save projects and unlock free credits.</div>
    <div class="ff"><label class="fl">Email</label><input class="fi" type="email" id="li-email" placeholder="you@example.com"></div>
    <div class="ff"><label class="fl">Password</label><input class="fi" type="password" id="li-pw" placeholder="••••••••"></div>
    <button class="bfull bv" onclick="doSignIn()">Sign in</button>
    <button class="bfull bout" onclick="switchM('m-signin','m-signup')">New here? Create account →</button>
  </div>
</div>

<!-- SIGN UP -->
<div class="mbg" id="m-signup">
  <div class="mbox" style="max-width:360px">
    <button class="mxbtn" onclick="closeM('m-signup')"><i class="ti ti-x"></i></button>
    <div class="wico" style="width:40px;height:40px;font-size:16px;margin-bottom:14px">A</div>
    <div class="mtitle">Create account</div>
    <div class="msub">Free to start — 3 AI creations to try.</div>
    <div class="ff"><label class="fl">Full name</label><input class="fi" type="text" id="su-name" placeholder="Your name"></div>
    <div class="ff"><label class="fl">Email</label><input class="fi" type="email" id="su-email" placeholder="you@example.com"></div>
    <div class="ff"><label class="fl">Password</label><input class="fi" type="password" id="su-pw" placeholder="Min 8 characters"></div>
    <button class="bfull bv" onclick="doSignUp()">Create account — free</button>
    <button class="bfull bout" onclick="switchM('m-signup','m-signin')">Already have account? Sign in →</button>
  </div>
</div>

<!-- UPGRADE / PRICING -->
<div class="mbg" id="m-upgrade">
  <div class="mbox" style="max-width:500px">
    <button class="mxbtn" onclick="closeM('m-upgrade')"><i class="ti ti-x"></i></button>
    <div class="mtitle">Choose your plan</div>
    <div class="msub">Pricing starts at ₹1,499/month.</div>
    <div class="pgrid" id="pg">
      <div class="pc" onclick="selPlan(this,'starter',1499)">
        <div class="pname">STARTER</div>
        <div class="pprice">₹1,499<span>/mo</span></div>
        <div class="pdesc">Simple websites &amp; landing pages</div>
        <div class="pfeats">
          <div class="pf"><i class="ti ti-check"></i> 10 creations/month</div>
          <div class="pf"><i class="ti ti-check"></i> Website mode</div>
          <div class="pf"><i class="ti ti-check"></i> HTML download</div>
        </div>
      </div>
      <div class="pc on" onclick="selPlan(this,'business',2999)">
        <div class="pbadge">MOST POPULAR</div>
        <div class="pname">BUSINESS</div>
        <div class="pprice">₹2,999<span>/mo</span></div>
        <div class="pdesc">Full apps + all 5 creation modes</div>
        <div class="pfeats">
          <div class="pf"><i class="ti ti-check"></i> 30 creations/month</div>
          <div class="pf"><i class="ti ti-check"></i> All 5 modes</div>
          <div class="pf"><i class="ti ti-check"></i> APK export</div>
        </div>
      </div>
      <div class="pc" onclick="selPlan(this,'pro',4999)">
        <div class="pname">PRO</div>
        <div class="pprice">₹4,999<span>/mo</span></div>
        <div class="pdesc">Complex apps &amp; priority AI</div>
        <div class="pfeats">
          <div class="pf"><i class="ti ti-check"></i> Unlimited creations</div>
          <div class="pf"><i class="ti ti-check"></i> Custom domain</div>
          <div class="pf"><i class="ti ti-check"></i> Priority queue</div>
        </div>
      </div>
      <div class="pc" onclick="selPlan(this,'agency',9999)">
        <div class="pname">AGENCY</div>
        <div class="pprice">₹9,999<span>/mo</span></div>
        <div class="pdesc">Teams + white-label</div>
        <div class="pfeats">
          <div class="pf"><i class="ti ti-check"></i> 5 team seats</div>
          <div class="pf"><i class="ti ti-check"></i> White-label</div>
          <div class="pf"><i class="ti ti-check"></i> API access</div>
        </div>
      </div>
    </div>
    <button class="bpay" id="upg-btn" onclick="doUpgrade()">
      <i class="ti ti-credit-card" style="font-size:15px"></i>
      <span id="upg-txt">Pay ₹2,999 via Razorpay</span>
    </button>
    <div class="pnote">🔒 Secured by Razorpay · Cancel anytime · UPI / Cards / Netbanking</div>
  </div>
</div>

<!-- DOWNLOAD -->
<div class="mbg" id="m-download">
  <div class="mbox" style="max-width:420px">
    <button class="mxbtn" onclick="closeM('m-download')"><i class="ti ti-x"></i></button>
    <div class="mtitle">Download your creation</div>
    <div class="msub">Choose what you need. Pay once, download instantly.</div>
    <div class="price-hint" id="dl-hint">💡 Simple website = ₹1,499. Full app with APK = ₹4,999.</div>
    <div id="dl-opts">
      <div class="dlopt on" onclick="selDL(this,'html',1499)">
        <div><div class="dlname">HTML Source Code</div><div class="dldesc">Complete HTML + CSS + JS. Host anywhere.</div></div>
        <div class="dlpr">₹1,499</div>
      </div>
      <div class="dlopt" onclick="selDL(this,'zip',2999)">
        <div><div class="dlname">Full ZIP Package</div><div class="dldesc">Source + assets + README + Capacitor config</div></div>
        <div class="dlpr">₹2,999</div>
      </div>
      <div class="dlopt" onclick="selDL(this,'apk',4999)">
        <div><div class="dlname">APK + Full Package</div><div class="dldesc">ZIP + Capacitor + Gradle + Play Store guide</div></div>
        <div class="dlpr">₹4,999</div>
      </div>
    </div>
    <button class="bpay" id="dl-btn" onclick="doDLPay()">
      <i class="ti ti-lock-open" style="font-size:14px"></i>
      <span id="dl-btn-txt">Pay ₹1,499 to Download</span>
    </button>
    <div class="pnote">🔒 Razorpay · Instant delivery</div>
    <div id="dl-success" style="display:none;text-align:center;padding:14px 0">
      <div style="font-size:32px;margin-bottom:10px">🎉</div>
      <div style="font-size:14px;font-weight:700;color:var(--tx);margin-bottom:5px">Payment confirmed!</div>
      <div style="font-size:11px;color:var(--mut);margin-bottom:16px">Your files are ready to download.</div>
      <button class="bpay" onclick="triggerDownload()"><i class="ti ti-download" style="font-size:14px"></i> Download now</button>
    </div>
  </div>
</div>

<!-- DEPLOY -->
<div class="mbg" id="m-deploy">
  <div class="mbox" style="max-width:500px">
    <button class="mxbtn" onclick="closeM('m-deploy')"><i class="ti ti-x"></i></button>
    <div class="mtitle">Deploy your creation</div>
    <div class="msub">Pick where you want to go live.</div>
    <div class="plats" id="plt-row">
      <div class="plt on" onclick="selPlt(this,'vercel')"><div class="plic">▲</div><div class="plnm">Vercel</div><div class="plsb">Free &amp; instant</div></div>
      <div class="plt" onclick="selPlt(this,'netlify')"><div class="plic">◆</div><div class="plnm">Netlify</div><div class="plsb">Free &amp; fast</div></div>
      <div class="plt" onclick="selPlt(this,'playstore')"><div class="plic">▶</div><div class="plnm">Play Store</div><div class="plsb">Android APK</div></div>
      <div class="plt" onclick="selPlt(this,'hostinger')"><div class="plic">🌐</div><div class="plnm">Hostinger</div><div class="plsb">cPanel hosting</div></div>
      <div class="plt" onclick="selPlt(this,'github')"><div class="plic">⬡</div><div class="plnm">GitHub Pages</div><div class="plsb">Free static</div></div>
      <div class="plt" onclick="selPlt(this,'whatsapp')"><div class="plic">💬</div><div class="plnm">WhatsApp</div><div class="plsb">Share link</div></div>
    </div>
    <div id="deploy-guide"></div>
    <button class="bpay" onclick="aiDeployGuide()" id="ai-guide-btn">
      <i class="ti ti-sparkles" style="font-size:14px"></i> Generate AI Deploy Guide
    </button>
  </div>
</div>

<!-- SHARE -->
<div class="mbg" id="m-share">
  <div class="mbox" style="max-width:360px">
    <button class="mxbtn" onclick="closeM('m-share')"><i class="ti ti-x"></i></button>
    <div class="mtitle">Share your creation</div>
    <div class="msub">Anyone with the link can preview your creation in their browser.</div>
    <div class="sharebox">
      <span class="sharelink" id="share-url">—</span>
      <button class="cpybtn" onclick="copyLink()">Copy</button>
    </div>
    <div class="shareacts">
      <button class="pa" style="flex:1;justify-content:center" onclick="shareWA()"><i class="ti ti-brand-whatsapp" style="font-size:12px"></i> WhatsApp</button>
      <button class="pa" style="flex:1;justify-content:center" onclick="copyLink()"><i class="ti ti-copy" style="font-size:12px"></i> Copy link</button>
      <button class="pa" style="flex:1;justify-content:center" onclick="openM('m-deploy')"><i class="ti ti-rocket" style="font-size:12px"></i> Deploy</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<!-- ═══════════ SCRIPT ═══════════ -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// ─── STATE ───
const S = {
  mode:'Website', html:'', busy:false,
  loggedIn:false, user:'', email:'',
  credits:0, maxCredits:3,
  projects:[], curProj:-1, curProjectId:null,
  plan:'business', planPrice:2999,
  dlType:'html', dlPrice:1499,
  plt:'vercel', razorpayKeyId:''
};

// ─── INIT — real session check against the server, not localStorage ───
window.onload = async () => {
  showDeploy();
  try {
    const res = await fetch('session_check.php');
    const data = await res.json();
    if (data.logged_in) {
      S.loggedIn = true; S.user = data.user.name; S.email = data.user.email;
      S.credits = data.user.credits; S.maxCredits = data.user.plan === 'free' ? 3 : 9999;
      S.projects = (data.projects || []).map(p => ({id:p.id, name:p.name, mode:p.mode, html:p.html_content, ts:new Date(p.created_at).getTime()}));
      showLoggedIn();
      renderProjects();
    }
    updateCr();
  } catch(e) { console.error('Session check failed', e); }
};

// ─── MODE ───
function setMode(el, name) {
  document.querySelectorAll('.mt').forEach(t=>t.classList.remove('on'));
  el.classList.add('on'); S.mode = name;
  const ph = {Website:'A booking website for my jewelry store with product gallery and WhatsApp…',App:'A grocery delivery app with cart, categories and UPI payment…','Design Studio':'Instagram post kit for my restaurant launch — 4 vibrant creatives…',Video:'A 30-second reel script for my skin glow clinic with hook and CTA…','Launch Kit':'Full launch plan for my Indian restaurant — pricing, scripts, content plan…'};
  document.getElementById('pt').placeholder = ph[name]||'Describe what you want to build…';
}
function fhint(t) { document.getElementById('pt').value=t; document.getElementById('pt').focus(); }
function qcreate(t,m) {
  document.getElementById('pt').value=t;
  const el=document.querySelector(`.mt[data-m="${m}"]`); if(el) setMode(el,m);
  generate();
}

// ─── GENERATE — real server-side call, real credit check ───
async function generate() {
  if(S.busy) return;
  const p = document.getElementById('pt').value.trim();
  if(!p){toast('Describe your idea first!','err');return;}
  if(!S.loggedIn){toast('Please sign in to create.','err');openM('m-signin');return;}
  if(S.credits<=0){openM('m-upgrade');return;}
  await callGenerate(p, false, null);
}
async function editNow() {
  if(S.busy) return;
  const e = document.getElementById('ci').value.trim();
  if(!e) return;
  if(!S.html){toast('Create something first!','err');return;}
  document.getElementById('ci').value='';
  await callGenerate(e, true, S.curProjectId);
}

async function callGenerate(prompt, isEdit, projectId) {
  S.busy=true; setBusy(true); showPane('loading');
  const steps=isEdit
    ?[['Applying your edit…','Updating the layout'],['Rebuilding…','Almost done']]
    :[['Reading your idea…','Understanding your business'],['Designing layout…','Building the page'],['Adding content…','Almost ready']];
  let si=0,prog=0;
  const fill=document.getElementById('ld-f');
  fill.style.width='0%';
  setLd(steps[0][0],steps[0][1]);
  const tk=setInterval(()=>{
    prog=Math.min(prog+4,88); fill.style.width=prog+'%';
    si=Math.min(si+1,steps.length-1); setLd(steps[si][0],steps[si][1]);
  },900);

  try {
    const res = await fetch('generate.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({
        mode: S.mode, prompt, is_edit: isEdit,
        existing_html: isEdit ? S.html : '', project_id: projectId || 0
      })
    });
    const data = await res.json();
    clearInterval(tk); fill.style.width='100%';

    if (!data.success) {
      showPane('welcome');
      if (data.needs_upgrade) { openM('m-upgrade'); }
      toast(data.error || 'Generation failed.', 'err');
      return;
    }

    S.html = data.html;
    S.credits = data.credits;
    S.curProjectId = data.project_id;
    updateCr();

    if (!isEdit) {
      addProject({id:data.project_id, name:prompt.slice(0,30), mode:S.mode, html:data.html, ts:Date.now()});
    } else {
      const idx = S.projects.findIndex(p=>p.id===data.project_id);
      if (idx>=0) S.projects[idx].html = data.html;
    }

    const slug = prompt.trim().toLowerCase().replace(/[^a-z0-9]+/g,'-').slice(0,28);
    document.getElementById('url-txt').textContent = 'aura-preview.local/' + slug;
    renderPv(data.html);
    document.getElementById('btn-deploy').style.display='flex';
    updateDLHint();
    toast(isEdit ? 'Edit applied! ✨' : 'Creation ready! ✨', 'ok');

  } catch(e) {
    clearInterval(tk); showPane('welcome');
    toast('Network error. Please try again.', 'err');
  } finally { S.busy=false; setBusy(false); }
}

function renderPv(html) {
  showPane('result');
  const fr=document.getElementById('pv-frame');
  try { fr.contentDocument.open(); fr.contentDocument.write(html); fr.contentDocument.close(); }
  catch(e){ fr.srcdoc=html; }
}
function showPane(p){
  document.getElementById('pv-welcome').style.display=p==='welcome'?'flex':'none';
  document.getElementById('pv-loading').style.display=p==='loading'?'flex':'none';
  document.getElementById('pv-result').style.display=p==='result'?'block':'none';
}
function reloadPv(){ if(S.html) renderPv(S.html); }
function setLd(t,s){ document.getElementById('ld-t').textContent=t; document.getElementById('ld-s').textContent=s; }
function setBusy(b){ document.getElementById('btn-create').disabled=b; document.getElementById('btn-chat').disabled=b; }
function updateCr(){
  document.getElementById('cr-v').textContent = S.maxCredits>=9999 ? 'Unlimited' : S.credits+' left';
  document.getElementById('cr-bar').style.width = S.maxCredits>=9999 ? '100%' : (S.credits/S.maxCredits*100)+'%';
}

// ─── PROJECTS — real list from the DB, not localStorage ───
function addProject(p){
  S.projects.unshift(p);
  if(S.projects.length>20) S.projects.pop();
  S.curProj=0; renderProjects();
}
const ICONS={Website:'ti-world',App:'ti-device-mobile','Design Studio':'ti-brush',Video:'ti-video','Launch Kit':'ti-rocket'};
function renderProjects(){
  const el=document.getElementById('proj-list');
  if(!S.projects.length){el.innerHTML='<div style="text-align:center;padding:18px 8px;color:var(--dim);font-size:11px;line-height:1.7"><i class="ti ti-folder-open" style="font-size:22px;display:block;margin-bottom:6px;opacity:.4"></i>Your creations appear here</div>';return;}
  el.innerHTML=S.projects.map((p,i)=>`
    <div class="pj${i===S.curProj?' on':''}" onclick="loadProj(${i})">
      <div class="pjn">${p.name}</div>
      <div class="pjm">${i===0?'<span class="sdot dlive"></span>':`<i class="ti ${ICONS[p.mode]||'ti-file'}" style="font-size:10px"></i>`}
        <span>${p.mode}</span><span style="margin-left:auto;font-size:9px">${ago(p.ts)}</span>
      </div>
    </div>`).join('');
}
function loadProj(i){
  S.curProj=i; S.html=S.projects[i].html; S.curProjectId=S.projects[i].id;
  document.querySelectorAll('.pj').forEach((c,j)=>c.classList.toggle('on',j===i));
  renderPv(S.html); document.getElementById('btn-deploy').style.display='flex';
  updateDLHint();
}
function ago(ts){
  const d=Date.now()-ts;
  if(d<60000)return'just now';if(d<3600000)return Math.floor(d/60000)+'m ago';
  if(d<86400000)return Math.floor(d/3600000)+'h ago';return Math.floor(d/86400000)+'d ago';
}

// ─── AUTH — real signup/signin against the server ───
async function doSignIn(){
  const email=document.getElementById('li-email').value.trim();
  const pw=document.getElementById('li-pw').value;
  if(!email||!pw){toast('Enter email and password','err');return;}
  try {
    const res = await fetch('signin.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email,password:pw})});
    const data = await res.json();
    if(!data.success){toast(data.error,'err');return;}
    S.loggedIn=true; S.user=data.user.name; S.email=data.user.email;
    S.credits=data.user.credits; S.maxCredits=data.user.plan==='free'?3:9999;
    closeM('m-signin'); showLoggedIn(); updateCr();
    toast('Signed in ✓','ok');
  } catch(e){ toast('Network error. Please try again.','err'); }
}
async function doSignUp(){
  const name=document.getElementById('su-name').value.trim();
  const email=document.getElementById('su-email').value.trim();
  const pw=document.getElementById('su-pw').value;
  if(!name||!email||!pw){toast('Fill all fields','err');return;}
  try {
    const res = await fetch('signup.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,email,password:pw})});
    const data = await res.json();
    if(!data.success){toast(data.error,'err');return;}
    S.loggedIn=true; S.user=data.user.name; S.email=data.user.email;
    S.credits=data.user.credits; S.maxCredits=3;
    closeM('m-signup'); showLoggedIn(); updateCr();
    toast('Welcome to Aura Creator! 🎉','ok');
  } catch(e){ toast('Network error. Please try again.','err'); }
}
function showLoggedIn(){
  document.getElementById('btn-signin').style.display='none';
  const av=document.getElementById('uav');
  av.style.display='flex'; av.textContent=S.user.charAt(0).toUpperCase();
}
function showProfile(){ toast('Profile settings — coming soon'); }

// ─── PLAN SELECTION + REAL RAZORPAY UPGRADE ───
function selPlan(el,id,price){
  document.querySelectorAll('#pg .pc').forEach(c=>c.classList.remove('on'));
  el.classList.add('on'); S.plan=id; S.planPrice=price;
  document.getElementById('upg-txt').textContent=`Pay ₹${price.toLocaleString('en-IN')} via Razorpay`;
}
async function doUpgrade(){
  if(!S.loggedIn){toast('Please sign in first.','err');openM('m-signin');return;}
  const btn=document.getElementById('upg-btn');
  btn.disabled=true;
  try {
    const orderRes = await fetch('plan_order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({plan:S.plan})});
    const orderData = await orderRes.json();
    if(!orderData.success){toast(orderData.error,'err');btn.disabled=false;return;}

    const rzp = new Razorpay({
      key: orderData.razorpay_key_id,
      amount: orderData.amount_paise,
      currency: 'INR',
      name: 'Aura Creator',
      description: S.plan+' plan',
      order_id: orderData.razorpay_order_id,
      handler: async function(response){
        const verifyRes = await fetch('plan_verify.php',{
          method:'POST',headers:{'Content-Type':'application/json'},
          body:JSON.stringify({
            razorpay_order_id: response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature: response.razorpay_signature
          })
        });
        const verifyData = await verifyRes.json();
        if(verifyData.success){
          S.credits=verifyData.credits; S.maxCredits=9999;
          closeM('m-upgrade'); updateCr();
          toast('Plan upgraded! ✅','ok');
        } else {
          toast(verifyData.error||'Payment verification failed.','err');
        }
      },
      theme:{color:'#8B5CF6'}
    });
    rzp.open();
  } catch(e){ toast('Could not start payment.','err'); }
  finally { btn.disabled=false; }
}

// ─── DOWNLOAD — real Razorpay order + verify + real ZIP ───
function updateDLHint(){
  const hints={Website:'Simple website — recommended: HTML Source Code (₹1,499)',App:'App project — recommended: Full ZIP Package (₹2,999) or APK (₹4,999)','Design Studio':'Design kit — recommended: HTML Source (₹1,499)','Launch Kit':'Launch kit — recommended: HTML Source (₹1,499)',Video:'Video script — recommended: HTML Source (₹1,499)'};
  document.getElementById('dl-hint').textContent='💡 '+( hints[S.mode]||'Choose what you need below.');
}
function selDL(el,type,price){
  document.querySelectorAll('.dlopt').forEach(d=>d.classList.remove('on'));
  el.classList.add('on'); S.dlType=type; S.dlPrice=price;
  document.getElementById('dl-btn-txt').textContent=`Pay ₹${price.toLocaleString('en-IN')} to Download`;
}
async function doDLPay(){
  if(!S.loggedIn){toast('Please sign in first.','err');openM('m-signin');return;}
  if(!S.curProjectId){toast('Create something first!','err');return;}
  const btn=document.getElementById('dl-btn');
  btn.disabled=true;
  try {
    const orderRes = await fetch('download_order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({project_id:S.curProjectId,download_type:S.dlType})});
    const orderData = await orderRes.json();
    if(!orderData.success){toast(orderData.error,'err');btn.disabled=false;return;}

    const rzp = new Razorpay({
      key: orderData.razorpay_key_id,
      amount: orderData.amount_paise,
      currency: 'INR',
      name: 'Aura Creator',
      description: S.dlType+' download',
      order_id: orderData.razorpay_order_id,
      handler: async function(response){
        const verifyRes = await fetch('download_verify.php',{
          method:'POST',headers:{'Content-Type':'application/json'},
          body:JSON.stringify({
            razorpay_order_id: response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature: response.razorpay_signature
          })
        });
        const verifyData = await verifyRes.json();
        if(verifyData.success){
          S.pendingDownloadUrl = verifyData.download_url;
          document.getElementById('dl-opts').style.display='none';
          document.getElementById('dl-hint').style.display='none';
          document.getElementById('dl-btn').style.display='none';
          document.getElementById('dl-success').style.display='block';
          toast('Payment confirmed! ✅','ok');
        } else {
          toast(verifyData.error||'Payment verification failed.','err');
        }
      },
      theme:{color:'#8B5CF6'}
    });
    rzp.open();
  } catch(e){ toast('Could not start payment.','err'); }
  finally { btn.disabled=false; }
}
function triggerDownload(){
  if(!S.pendingDownloadUrl){toast('No download ready.','err');return;}
  window.location.href = S.pendingDownloadUrl; // real server-built ZIP or HTML file
  closeM('m-download');
}

// ─── DEPLOY — real Gemini-generated guide ───
const DG_STATIC = {
  vercel:[['Download your file','Click Download above and save your file.'],['Go to vercel.com','Sign up free with GitHub or Google.'],['Deploy instantly','Add New → Project → Deploy without Git → drag your file → Deploy.'],['Live! 🎉','You get a free .vercel.app link in under 60 seconds.']],
  netlify:[['Download your file','Save your HTML file.'],['Go to netlify.com','Sign up free.'],['Drag and drop','Drop your file in the deploy zone.'],['Done! 🎉','Live instantly at a .netlify.app URL.']],
  playstore:[['Get the APK package','Purchase APK + Full Package in Download.'],['Install Android Studio','Free from developer.android.com/studio.'],['Build your APK','Open android/ folder → Build → Generate Signed APK.'],['Submit','play.google.com/console, $25 one-time fee.']],
  hostinger:[['Download your file','Save your HTML file.'],['Open File Manager','hpanel.hostinger.com → Hosting → File Manager.'],['Upload','Open public_html, upload and rename to index.html.'],['Live! 🎉','Open your domain — it is live.']],
  github:[['Create account','github.com, sign up free.'],['New repository','Name it yourusername.github.io, set Public.'],['Upload file','Add file → Upload, rename to index.html.'],['Enable Pages','Settings → Pages → Deploy from main. Live in 2 min!']],
  whatsapp:[['Get share link','Click Share above, copy the link.'],['Open WhatsApp','web.whatsapp.com or the app.'],['Share','Paste the link in any chat.'],['Upgrade for a permanent URL 🚀','Business/Pro plan gives a real live domain.']]
};
function selPlt(el,id){
  document.querySelectorAll('.plt').forEach(b=>b.classList.remove('on'));
  el.classList.add('on'); S.plt=id; showDeploy();
}
function showDeploy(){
  const steps = DG_STATIC[S.plt] || DG_STATIC.vercel;
  document.getElementById('deploy-guide').innerHTML = steps.map((s,i)=>`
      <div class="dstep">
        <div class="dsh"><div class="dsn">${i+1}</div><div class="dsti">${s[0]}</div></div>
        <div class="dsb">${s[1]}</div>
      </div>`).join('');
}
async function aiDeployGuide(){
  if(!S.loggedIn){toast('Please sign in first.','err');openM('m-signin');return;}
  const btn=document.getElementById('ai-guide-btn');
  btn.innerHTML='<span class="spn" style="width:16px;height:16px;border-width:2px;margin-right:6px"></span> Generating…';
  btn.disabled=true;
  try {
    const res = await fetch('deploy_guide.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({platform:S.plt})});
    const data = await res.json();
    if(data.success){
      document.getElementById('deploy-guide').innerHTML=`<div style="background:rgba(255,255,255,0.03);border:1px solid var(--b);border-radius:9px;padding:13px;margin-bottom:12px;font-size:11px;color:var(--mut);line-height:1.85">${data.guide_html}</div>`;
      toast('AI guide ready! ✓','ok');
    } else { toast(data.error||'Could not generate guide','err'); }
  } catch(e){ toast('Could not generate guide','err'); }
  finally {
    S.busy=false;
    btn.innerHTML='<i class="ti ti-sparkles" style="font-size:14px"></i> Generate AI Deploy Guide';
    btn.disabled=false;
  }
}

// ─── SHARE ───
function openShare(){
  if(!S.html){toast('Create something first!','err');return;}
  const slug = document.getElementById('url-txt').textContent.split('/').pop();
  document.getElementById('share-url').textContent = window.location.origin + '/preview/' + (S.curProjectId||slug);
  openM('m-share');
}
function copyLink(){ navigator.clipboard?.writeText(document.getElementById('share-url').textContent).catch(()=>{}); toast('Link copied! 🔗','ok'); }
function shareWA(){ window.open('https://wa.me/?text=Check this out! '+encodeURIComponent(document.getElementById('share-url').textContent),'_blank'); }

// ─── MODAL HELPERS ───
function openM(id){
  if(id==='m-download'){ document.getElementById('dl-success').style.display='none'; document.getElementById('dl-opts').style.display='block'; document.getElementById('dl-hint').style.display='block'; document.getElementById('dl-btn').style.display='flex'; }
  document.getElementById(id).classList.add('open');
}
function closeM(id){ document.getElementById(id).classList.remove('open'); }
function switchM(a,b){ closeM(a); openM(b); }
document.querySelectorAll('.mbg').forEach(bg=>bg.addEventListener('click',e=>{ if(e.target===bg) bg.classList.remove('open'); }));

// ─── TOAST ───
function toast(msg,type=''){
  const t=document.getElementById('toast');
  t.textContent=msg; t.className='toast show'+(type?' '+type:'');
  clearTimeout(t._t); t._t=setTimeout(()=>t.classList.remove('show'),3200);
}

// ─── ENTER KEY ───
document.getElementById('ci').addEventListener('keydown',e=>{ if(e.key==='Enter'&&!e.shiftKey) editNow(); });
</script>
</body>
</html>
