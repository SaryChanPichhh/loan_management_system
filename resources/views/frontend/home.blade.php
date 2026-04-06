<!DOCTYPE html>
<html lang="km">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="SetecLoan – សេវាកម្មកម្ចីឯកជន និងមីក្រូហិរញ្ញវត្ថុដែលគួរឱ្យទុកចិត្តនៅក្នុងប្រទេសកម្ពុជា។ កម្ចីផ្ទាល់ខ្លួន អាជីវកម្ម កម្ចីបន្ទាន់ និងបើកប្រាក់ខែមុន ជាមួយនឹងលក្ខខណ្ឌតម្លាភាព និងការអនុម័តរហ័ស។" />
  <meta name="keywords" content="SetecLoan, កម្ចីកម្ពុជា, មីក្រូហិរញ្ញវត្ថុ, កម្ចីផ្ទាល់ខ្លួន, កម្ចីអាជីវកម្ម, កម្ចីបន្ទាន់, ប្រាក់រៀល, ប្រាក់ដុល្លារ" />
  <meta property="og:title" content="SetecLoan – ដៃគូផ្តល់កម្ចីដែលគួរឲ្យទុកចិត្ត" />
  <meta property="og:description" content="កម្ចីរហ័ស តម្លាភាព និងបត់បែនសម្រាប់បុគ្គល និងអាជីវកម្មនៅកម្ពុជា។" />
  <title>SetecLoan – ដៃគូផ្តល់កម្ចីដែលគួរឲ្យទុកចិត្ត</title>

  <!-- Google Fonts for Khmer -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Frontend CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}" />
  <style>
    body, h1, h2, h3, h4, h5, h6, .brand-name, .sl-hero-title, .sl-section-title, p, a, span, div, li, ul, button {
      font-family: 'Battambang', 'Inter', sans-serif !important;
      line-height: 1.8;
    }
    .sl-hero-title { line-height: 1.5; }
  </style>
</head>
<body>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav class="sl-navbar" id="sl-navbar" role="navigation" aria-label="Main navigation">
  <a href="#hero" class="brand" aria-label="SetecLoan Home">
    <div class="brand-logo">S</div>
    <span class="brand-name">Setec<span>Loan</span></span>
  </a>

  <ul class="sl-nav-links" id="sl-nav-links" role="menubar">
    <li role="none"><a href="#products"    role="menuitem" id="nav-products">ផលិតផលកម្ចី</a></li>
    <li role="none"><a href="#rates"       role="menuitem" id="nav-rates">អត្រាការប្រាក់</a></li>
    <li role="none"><a href="#eligibility" role="menuitem" id="nav-eligibility">លក្ខខណ្ឌ</a></li>
    <li role="none"><a href="#process"     role="menuitem" id="nav-process">របៀបដំណើរការ</a></li>
    <li role="none"><a href="#policies"    role="menuitem" id="nav-policies">គោលការណ៍</a></li>
    <li role="none"><a href="#contact"     role="menuitem" id="nav-contact" class="sl-nav-cta">ដាក់ពាក្យឥឡូវនេះ</a></li>
  </ul>

  <button class="sl-hamburger" id="sl-hamburger" aria-label="Toggle menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="sl-hero" id="hero" aria-label="Hero section">
  <div class="sl-particles" id="sl-particles" aria-hidden="true"></div>

  <div class="sl-hero-inner">
    <!-- Left -->
    <div class="sl-hero-content">
      <div class="sl-hero-badge">
        <span class="dot"></span>
        ផ្តល់ប្រាក់កម្ចីប្រកបដោយទំនុកចិត្តតាំងពីថ្ងៃដំបូង
      </div>

      <h1 class="sl-hero-title">
        គោលដៅហិរញ្ញវត្ថុរបស់អ្នក<br/>
        ចាប់ផ្តើមជាមួយ<br/>
        <span class="highlight">SetecLoan</span>
      </h1>

      <p class="sl-hero-subtitle">
        កម្ចីរហ័ស តម្លាភាព និងបត់បែនសម្រាប់បុគ្គល និងអាជីវកម្ម។
        ចាប់ពីមូលនិធិបន្ទាន់ ៥០ ដុល្លារ រហូតដល់ដើមទុនអាជីវកម្ម ២០,០០០ ដុល្លារ — យើងមានជាប្រាក់ដុល្លារ និ​​ងរៀល។
      </p>

      <div class="sl-hero-btns">
        <a href="#process" class="sl-btn-primary" id="hero-apply-btn">
          ✦ ដាក់ពាក្យឥឡូវនេះ
        </a>
        <a href="#products" class="sl-btn-outline" id="hero-explore-btn">
          ស្វែងយល់បន្ថែម →
        </a>
      </div>

      <div class="sl-hero-stats">
        <div class="sl-hero-stat-item">
          <span class="num" data-target="4" data-suffix="+" id="stat-products">0+</span>
          <span class="lbl">ផលិតផលកម្ចី</span>
        </div>
        <div class="sl-hero-stat-item">
          <span class="num" data-target="1.5" data-suffix="%" id="stat-rate">0%</span>
          <span class="lbl">ចាប់ពី / ខែ</span>
        </div>
        <div class="sl-hero-stat-item">
          <span class="num" data-target="2" data-suffix=" Days" id="stat-approval">0 ថ្ងៃ</span>
          <span class="lbl">អនុម័តរហ័ស</span>
        </div>
        <div class="sl-hero-stat-item">
          <span class="num" data-target="100" data-suffix="%" id="stat-transparent">0%</span>
          <span class="lbl">មានតម្លាភាព</span>
        </div>
      </div>
    </div>

    <!-- Right -->
    <div class="sl-hero-image-wrap" aria-hidden="true">
      <img src="{{ asset('assets/img/setecloan_hero.png') }}" alt="SetecLoan financial services illustration" loading="lazy" />

      <div class="sl-hero-image-badge left">
        <div class="icon-wrap">💰</div>
        <div>
          <div class="bdg-title">រហូតដល់</div>
          <div class="bdg-value">$20,000</div>
        </div>
      </div>

      <div class="sl-hero-image-badge right">
        <div class="icon-wrap">⚡</div>
        <div>
          <div class="bdg-title">អនុម័តក្នុងរយៈពេល</div>
          <div class="bdg-value">១–២ ថ្ងៃ</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     LOAN PRODUCTS
     ============================================================ -->
<div class="sl-products-bg">
  <section class="sl-section" id="products" aria-labelledby="products-title">
    <div class="sl-section-header sl-fade-up">
      <span class="sl-section-tag">ផលិតផលរបស់យើង</span>
      <h2 class="sl-section-title" id="products-title">ផលិតផលកម្ចី <span>ដែលយើងផ្តល់ជូន</span></h2>
      <p class="sl-section-desc">
        ជម្រើសកម្ចីដែលអាចបត់បែនបានដែលត្រូវបានរចនាឡើងដើម្បីបំពេញតម្រូវការពិតប្រាកដរបស់បុគ្គលនិងអាជីវកម្ម
        ជាមួយចំនួនចាប់ពី ៥០ ដុល្លារ រហូតដល់ ២០,០០០ ដុល្លារ។
      </p>
    </div>

    <div class="sl-products-grid">
      <!-- Personal Loan -->
      <div class="sl-product-card sl-fade-up" id="product-personal">
        <div class="sl-product-icon blue">🏠</div>
        <div class="sl-product-name">កម្ចីផ្ទាល់ខ្លួន</div>
        <p class="sl-product-purpose">សម្រាប់តម្រូវការផ្ទាល់ខ្លួន និងការចំណាយប្រចាំថ្ងៃ</p>
        <div class="sl-product-range">
          <div>
            <div class="r-label">អប្បបរមា</div>
            <div class="r-value">$100</div>
          </div>
          <div style="text-align:right">
            <div class="r-label">អតិបរមា</div>
            <div class="r-value">$5,000</div>
          </div>
        </div>
        <span class="sl-product-badge">🇰🇭 ដុល្លារ / រៀល</span>
      </div>

      <!-- Business Loan -->
      <div class="sl-product-card sl-fade-up" id="product-business">
        <div class="sl-product-icon gold">🏢</div>
        <div class="sl-product-name">កម្ចីអាជីវកម្ម</div>
        <p class="sl-product-purpose">ជំនួយដើមទុនសម្រាប់អាជីវកម្មដែលកំពុងលូតលាស់</p>
        <div class="sl-product-range">
          <div>
            <div class="r-label">អប្បបរមា</div>
            <div class="r-value">$500</div>
          </div>
          <div style="text-align:right">
            <div class="r-label">អតិបរមា</div>
            <div class="r-value">$20,000</div>
          </div>
        </div>
        <span class="sl-product-badge">📈 ដើមទុនអាជីវកម្ម</span>
      </div>

      <!-- Emergency Loan -->
      <div class="sl-product-card sl-fade-up" id="product-emergency">
        <div class="sl-product-icon green">🚨</div>
        <div class="sl-product-name">កម្ចីបន្ទាន់</div>
        <p class="sl-product-purpose">ថវិការហ័សសម្រាប់ស្ថានភាពបន្ទាន់</p>
        <div class="sl-product-range">
          <div>
            <div class="r-label">អប្បបរមា</div>
            <div class="r-value">$50</div>
          </div>
          <div style="text-align:right">
            <div class="r-label">អតិបរមា</div>
            <div class="r-value">$1,000</div>
          </div>
        </div>
        <span class="sl-product-badge">⚡ បើកប្រាក់រហ័ស</span>
      </div>

      <!-- Salary Advance -->
      <div class="sl-product-card sl-fade-up" id="product-salary">
        <div class="sl-product-icon purple">💼</div>
        <div class="sl-product-name">កម្ចីបើកប្រាក់ខែមុន</div>
        <p class="sl-product-purpose">ផ្តាច់មុខសម្រាប់អ្នកមានការងារធ្វើ</p>
        <div class="sl-product-range">
          <div>
            <div class="r-label">អប្បបរមា</div>
            <div class="r-value">$50</div>
          </div>
          <div style="text-align:right">
            <div class="r-label">អតិបរមា</div>
            <div class="r-value">$2,000</div>
          </div>
        </div>
        <span class="sl-product-badge">👔 សម្រាប់បុគ្គលិកប៉ុណ្ណោះ</span>
      </div>
    </div>
  </section>
</div>


<!-- ============================================================
     INTEREST RATES
     ============================================================ -->
<section class="sl-section" id="rates" aria-labelledby="rates-title">
  <div class="sl-section-header sl-fade-up">
    <span class="sl-section-tag">តម្លៃប្រកបដោយតម្លាភាព</span>
    <h2 class="sl-section-title" id="rates-title">គោលការណ៍ <span>អត្រាការប្រាក់</span></h2>
    <p class="sl-section-desc">
      អត្រាការប្រាក់សមរម្យ និងយុត្តិធម៌ផ្អែកលើរយៈពេលកម្ចីរបស់អ្នក — គ្មានថ្លៃលាក់កំបាំង គ្មានរឿងគួរឱ្យភ្ញាក់ផ្អើល។
    </p>
  </div>

  <div class="sl-rates-grid">
    <!-- Table -->
    <div class="sl-rates-table-wrap sl-fade-up" id="rates-table">
      <table class="sl-rates-table" role="table" aria-label="Interest rate table">
        <thead>
          <tr>
            <th scope="col">រយៈពេលកម្ចី</th>
            <th scope="col">អត្រាការប្រាក់ប្រចាំខែ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>១ – ៣ ខែ</td>
            <td><span class="sl-rate-badge">3.0% / ខែ</span></td>
          </tr>
          <tr>
            <td>៤ – ៦ ខែ</td>
            <td><span class="sl-rate-badge">2.5% / ខែ</span></td>
          </tr>
          <tr>
            <td>៧ – ១២ ខែ</td>
            <td><span class="sl-rate-badge">2.0% / ខែ</span></td>
          </tr>
          <tr>
            <td>លើសពី ១២ ខែ</td>
            <td><span class="sl-rate-badge">1.5% / ខែ</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Highlight card -->
    <div class="sl-rate-highlight-card sl-fade-up" id="rates-example">
      <div class="tag">💡 ឧទាហរណ៍ខ្លីៗ</div>
      <h3>សាកមើលពីការបង់ប្រាក់សងរបស់អ្នក</h3>
      <p>
        យើងបង្ហាញអ្នកពីចំនួនសរុបពិតប្រាកដមុនពេលអ្នកចុះហត្ថលេខា — គ្មានការភ្ញាក់ផ្អើល,
        គ្មានការគិតថ្លៃលាក់កំបាំងឡើយ។
      </p>
      <div class="sl-example-box">
        <div class="ex-label">📌 ឧទាហរណ៍នៃការគណនា</div>
        <div class="ex-val">
          កម្ចី <strong style="color:var(--accent-light)">$1,000</strong> សម្រាប់រយៈពេល
          <strong style="color:var(--accent-light)">៦ ខែ</strong>
          ក្នុងអត្រា <strong style="color:var(--accent-light)">2.5% / ខែ</strong><br/><br/>
          → ការប្រាក់: <strong style="color:var(--accent-light)">$25 / ខែ</strong><br/>
          → ការប្រាក់សរុប: <strong style="color:var(--accent-light)">$150</strong><br/>
          → ការសងសរុប: <strong style="color:var(--accent-light)">$1,150</strong>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     ELIGIBILITY & DOCUMENTS
     ============================================================ -->
<div class="sl-elig-bg">
  <section class="sl-section" id="eligibility" aria-labelledby="elig-title">
    <div class="sl-section-header sl-fade-up">
      <span class="sl-section-tag">តើនរណាអាចដាក់ពាក្យបាន</span>
      <h2 class="sl-section-title" id="elig-title">លក្ខខណ្ឌ & <span>ឯកសារតម្រូវ</span></h2>
      <p class="sl-section-desc">
        សាមញ្ញ លក្ខខណ្ឌតម្រូវយុត្តិធម៌ដើម្បីធានានូវទំនាក់ទំនងផ្តល់កម្ចីប្រកបដោយការទទួលខុសត្រូវ។
      </p>
    </div>

    <div class="sl-elig-grid">
      <!-- Eligibility list -->
      <div class="sl-elig-list" id="elig-list">
        <div class="sl-elig-item sl-fade-up">
          <div class="sl-elig-check">✓</div>
          <div class="sl-elig-text"><strong>អាយុ ១៨ – ៦៥ ឆ្នាំ</strong><br/>ត្រូវតែជាអាយុស្របច្បាប់ និងអាចធ្វើការបាន</div>
        </div>
        <div class="sl-elig-item sl-fade-up">
          <div class="sl-elig-check">✓</div>
          <div class="sl-elig-text"><strong>មានប្រាក់ចំណូល ឬអាជីវកម្មច្បាស់លាស់</strong><br/>ការងារ មុខរបរខ្លួនឯង ឬចំណូលពាណិជ្ជកម្ម</div>
        </div>
        <div class="sl-elig-item sl-fade-up">
          <div class="sl-elig-check">✓</div>
          <div class="sl-elig-text"><strong>ឯកសារអត្តសញ្ញាណប័ណ្ណត្រឹមត្រូវ</strong><br/>អត្តសញ្ញាណប័ណ្ណ លិខិតឆ្លងដែន ឬសៀវភៅគ្រួសារ</div>
        </div>
        <div class="sl-elig-item sl-fade-up">
          <div class="sl-elig-check">✓</div>
          <div class="sl-elig-text"><strong>អាសយដ្ឋានក្នុងស្រុកដែលអាចផ្ទៀងផ្ទាត់បាន</strong><br/>លំនៅដ្ឋានបច្ចុប្បន្នត្រូវតែអាចបញ្ជាក់បាន</div>
        </div>
        <div class="sl-elig-item sl-fade-up">
          <div class="sl-elig-check">✓</div>
          <div class="sl-elig-text"><strong>ប្រវត្តិបំណុលល្អជាមួយយើង</strong><br/>គ្មានសមតុល្យមិនទាន់ទូទាត់ជាមួយ SetecLoan</div>
        </div>
        <div class="sl-elig-item sl-fade-up">
          <div class="sl-elig-check">✓</div>
          <div class="sl-elig-text"><strong>អ្នកធានាសម្រាប់កម្ចីលើសពី ៥០០ ដុល្លារ</strong><br/>បុគ្គលដែលទុកចិត្តបានដើម្បីចូលរួមចុះហត្ថលេខាលើកិច្ចព្រមព្រៀង</div>
        </div>
      </div>

      <!-- Documents card -->
      <div class="sl-docs-card sl-fade-up" id="docs-card">
        <h4>📄 ឯកសារដែលត្រូវការ</h4>
        <div class="sl-docs-col-head">
          <span>ផ្ទាល់ខ្លួន</span>
          <span>អាជីវកម្ម</span>
        </div>
        <div class="sl-docs-row">
          <span class="doc-name">អត្តសញ្ញាណប័ណ្ណ / លិខិតឆ្លងដែន</span>
          <span class="sl-doc-check">✅</span>
          <span class="sl-doc-check">✅</span>
        </div>
        <div class="sl-docs-row">
          <span class="doc-name">ឯកសារបញ្ជាក់អាសយដ្ឋាន</span>
          <span class="sl-doc-check">✅</span>
          <span class="sl-doc-check">✅</span>
        </div>
        <div class="sl-docs-row">
          <span class="doc-name">ឯកសារបញ្ជាក់ប្រាក់ចំណូល / ប័ណ្ណបើកប្រាក់ខែ</span>
          <span class="sl-doc-check">✅</span>
          <span class="sl-doc-check">✅</span>
        </div>
        <div class="sl-docs-row">
          <span class="doc-name">ការចុះបញ្ជីអាជីវកម្ម</span>
          <span class="sl-doc-cross">—</span>
          <span class="sl-doc-check">✅</span>
        </div>
        <div class="sl-docs-row">
          <span class="doc-name">អត្តសញ្ញាណប័ណ្ណអ្នកធានា</span>
          <span class="sl-doc-conditional" style="width:140px;text-align:center">កម្ចី &gt; $500</span>
          <span class="sl-doc-check">✅</span>
        </div>
        <div class="sl-docs-row">
          <span class="doc-name">ឯកសារទ្រព្យបញ្ចាំ</span>
          <span class="sl-doc-optional">ជាជម្រើស</span>
          <span class="sl-doc-conditional" style="width:70px;text-align:center">>&nbsp;$5,000</span>
        </div>
      </div>
    </div>
  </section>
</div>


<!-- ============================================================
     APPLICATION PROCESS
     ============================================================ -->
<section class="sl-section" id="process" aria-labelledby="process-title">
  <div class="sl-section-header sl-fade-up">
    <span class="sl-section-tag">ជំហានងាយៗ</span>
    <h2 class="sl-section-title" id="process-title">របៀប <span>ទទួលបានកម្ចីរបស់អ្នក</span></h2>
    <p class="sl-section-desc">
      ដំណើរការដ៏សាមញ្ញ ៦ ជំហានរបស់យើង ផ្តល់ប្រាក់ដល់ដៃអ្នកលឿនបំផុតនៅថ្ងៃដដែល ឬថ្ងៃធ្វើការបន្ទាប់។
    </p>
  </div>

  <div class="sl-process-steps" id="process-steps">
    <div class="sl-step-card sl-fade-up" id="step-1">
      <div class="sl-step-num">1</div>
      <div class="sl-step-icon">📝</div>
      <div class="sl-step-title">បំពេញពាក្យសុំ</div>
      <p class="sl-step-desc">បំពេញទម្រង់ពាក្យសុំកម្ចីដ៏សាមញ្ញរបស់យើង ជាមួយនឹងព័ត៌មានលម្អិតផ្ទាល់ខ្លួន និងហិរញ្ញវត្ថុរបស់អ្នក។</p>
    </div>

    <div class="sl-step-card sl-fade-up" id="step-2">
      <div class="sl-step-num">2</div>
      <div class="sl-step-icon">📁</div>
      <div class="sl-step-title">បញ្ជូនឯកសារ</div>
      <p class="sl-step-desc">ផ្តល់ឯកសារដែលត្រូវការ រួមមានអត្តសញ្ញាណប័ណ្ណ ឯកសារបញ្ជាក់ប្រាក់ចំណូល និងការផ្ទៀងផ្ទាត់អាសយដ្ឋាន។</p>
    </div>

    <div class="sl-step-card sl-fade-up" id="step-3">
      <div class="sl-step-num">3</div>
      <div class="sl-step-icon">🔍</div>
      <div class="sl-step-title">ការផ្ទៀងផ្ទាត់</div>
      <p class="sl-step-desc">យើងផ្ទៀងផ្ទាត់ប្រាក់ចំណូល និងប្រវត្តិរបស់អ្នកក្នុងរយៈពេល ១-២ ថ្ងៃធ្វើការ លម្អិតតែរហ័ស។</p>
    </div>

    <div class="sl-step-card sl-fade-up" id="step-4">
      <div class="sl-step-num">4</div>
      <div class="sl-step-icon">✅</div>
      <div class="sl-step-title">សេចក្តីសម្រេចអនុម័ត</div>
      <p class="sl-step-desc">ទទួលបានការអនុម័ត ឬបដិសេធកម្ចីជាមួយនឹងការពន្យល់ច្បាស់លាស់។ អាចប្តឹងតវ៉ាក្នុងរយៈពេល ៧ ថ្ងៃប្រសិនបើចាំបាច់។</p>
    </div>

    <div class="sl-step-card sl-fade-up" id="step-5">
      <div class="sl-step-num">5</div>
      <div class="sl-step-icon">✍️</div>
      <div class="sl-step-title">ចុះហត្ថលេខាលើកិច្ចព្រមព្រៀង</div>
      <p class="sl-step-desc">ពិនិត្យ និងចុះហត្ថលេខាលើកិច្ចព្រមព្រៀងកម្ចីរបស់អ្នក។ អ្នកនឹងដឹងពីចំនួនសងសរុបពិតប្រាកដជាមុន។</p>
    </div>

    <div class="sl-step-card sl-fade-up" id="step-6">
      <div class="sl-step-num">6</div>
      <div class="sl-step-icon">💵</div>
      <div class="sl-step-title">ទទួលប្រាក់</div>
      <p class="sl-step-desc">ប្រាក់ត្រូវបានបើកជូននៅថ្ងៃដដែល ឬថ្ងៃធ្វើការបន្ទាប់។ ចាប់ផ្តើមប្រើប្រាស់មូលនិធិរបស់អ្នកភ្លាមៗ។</p>
    </div>
  </div>
</section>


<!-- ============================================================
     POLICIES — Collateral, Repayment, Late Payment
     ============================================================ -->
<div class="sl-policy-bg">
  <section class="sl-section" id="policies" aria-labelledby="policies-title">
    <div class="sl-section-header sl-fade-up">
      <span class="sl-section-tag">លក្ខខណ្ឌ និងបទប្បញ្ញត្តិ</span>
      <h2 class="sl-section-title" id="policies-title">គោលការណ៍ <span>របស់យើង</span></h2>
      <p class="sl-section-desc">
        ច្បាប់ច្បាស់លាស់ យុត្តិធម៌ និងមានតម្លាភាពដែលការពារទាំងអ្នក និងយើងពេញមួយរយៈពេលនៃកម្ចី។
      </p>
    </div>

    <div class="sl-policy-grid">
      <!-- Repayment Terms -->
      <div class="sl-policy-card sl-fade-up" id="policy-repayment">
        <div class="sl-policy-icon blue">📅</div>
        <h4>លក្ខខណ្ឌនៃការសង</h4>
        <ul class="sl-policy-list">
          <li><span class="pi">📌</span> ការទូទាត់ប្រចាំខែតាមកាលបរិច្ឆេទដែលបានព្រមព្រៀង</li>
          <li><span class="pi">💡</span> ជម្រើស: បង់តែការប្រាក់មុន បន្ទាប់មកប្រាក់ដើម — ឬបង់រំលស់ប្រចាំខែស្មើៗគ្នា</li>
          <li><span class="pi">✅</span> អនុញ្ញាតឱ្យសងមុនកាលកំណត់ <strong>ដោយគ្មានការផាកពិន័យ</strong></li>
          <li><span class="pi">⏱</span> អនុគ្រោះរយៈពេល ៣ ថ្ងៃបន្ទាប់ពីថ្ងៃផុតកំណត់មុនពេលគិតថ្លៃយឺតយ៉ាវ</li>
          <li><span class="pi">⚠️</span> ថ្លៃយឺតយ៉ាវ: <strong>1.5% នៃសមតុល្យមិនទាន់ទូទាត់</strong> រាល់ការខកខានបង់ប្រាក់</li>
        </ul>
      </div>

      <!-- Collateral Policy -->
      <div class="sl-policy-card sl-fade-up" id="policy-collateral">
        <div class="sl-policy-icon gold">🏦</div>
        <h4>គោលការណ៍វត្ថុបញ្ចាំ</h4>
        <ul class="sl-policy-list">
          <li><span class="pi">🟢</span> កម្ចី <strong>ក្រោម $500</strong> — មិនត្រូវការវត្ថុបញ្ចាំ</li>
          <li><span class="pi">🟡</span> កម្ចី <strong>$500 – $5,000</strong> — ត្រូវការអ្នកធានា</li>
          <li><span class="pi">🔴</span> កម្ចី <strong>លើសពី $5,000</strong> — ត្រូវការទ្រព្យបញ្ចាំរូបវន្ត</li>
          <li><span class="pi">📋</span> ទទួលយក: ប្លង់ដី យានយន្ត ឬឧបករណ៍អាជីវកម្ម</li>
          <li><span class="pi">📊</span> វត្ថុបញ្ចាំត្រូវតែមានតម្លៃ <strong>យ៉ាងហោចណាស់ 120%</strong> នៃចំនួនកម្ចី</li>
        </ul>
      </div>

      <!-- Customer Rights -->
      <div class="sl-policy-card sl-fade-up" id="policy-rights">
        <div class="sl-policy-icon green">🛡️</div>
        <h4>សិទ្ធិរបស់អ្នកក្នុងនាមជាអតិថិជន</h4>
        <ul class="sl-policy-list">
          <li><span class="pi">📄</span> ទទួលបាន <strong>កិច្ចព្រមព្រៀងកម្ចីជាលាយលក្ខណ៍អក្សរពេញលេញ</strong> មុនពេលចុះហត្ថលេខា</li>
          <li><span class="pi">🔢</span> ដឹងពី <strong>ចំនួនការសងសរុបពិតប្រាកដ</strong> ជាមុន</li>
          <li><span class="pi">📆</span> ស្នើសុំ <strong>កាលវិភាគបង់ប្រាក់</strong> នៅពេលណាក៏បាន</li>
          <li><span class="pi">🗣</span> ប្តឹងតវ៉ា ឬអំពាវនាវពីការសម្រេចចិត្តណាមួយក្នុងរយៈពេល <strong>៧ ថ្ងៃ</strong></li>
          <li><span class="pi">💳</span> ទូទាត់មុនកាលកំណត់ដោយគ្មានការគិតថ្លៃបន្ថែម</li>
        </ul>
      </div>

      <!-- Confidentiality & Prohibited -->
      <div class="sl-policy-card sl-fade-up" id="policy-privacy">
        <div class="sl-policy-icon red">🔒</div>
        <h4>ឯកជនភាព និងវិន័យ</h4>
        <ul class="sl-policy-list">
          <li><span class="pi">🔐</span> ទិន្នន័យអតិថិជនទាំងអស់ត្រូវបានរក្សាទុក <strong>ជាសម្ងាត់បំផុត</strong></li>
          <li><span class="pi">🚫</span> យើងមិនដែលចែករំលែកទិន្នន័យរបស់អ្នកជាមួយភាគីទីបីឡើយ</li>
          <li><span class="pi">🗃</span> កំណត់ត្រាត្រូវបានរក្សាទុក ៥ ឆ្នាំ បន្ទាប់មកត្រូវលុបចោលដោយសុវត្ថិភាព</li>
          <li><span class="pi">🕐</span> យើងទាក់ទងអ្នកតែចន្លោះម៉ោង <strong>៧ ព្រឹក – ៨ យប់</strong> ប៉ុណ្ណោះ គ្មានការលើកលែង</li>
          <li><span class="pi">📜</span> យើង <strong>មិនដែល</strong> គិតថ្លៃលាក់កំបាំងក្រៅកិច្ចសន្យាឡើយ</li>
        </ul>
      </div>
    </div>

    <!-- Late Payment Table -->
    <div style="margin-top: 60px;">
      <div class="sl-section-header sl-fade-up">
        <span class="sl-section-tag">ការបង់ប្រាក់យឺត</span>
        <h2 class="sl-section-title" id="late-payment-title">ផលវិបាកនៃ <span>ការបង់ប្រាក់យឺត</span></h2>
      </div>
      <div class="sl-late-table-wrap sl-fade-up" id="late-table">
        <table class="sl-late-table" role="table" aria-label="Late payment consequences table">
          <thead>
            <tr>
              <th scope="col">ស្ថានភាព</th>
              <th scope="col">កម្រិតភាពធ្ងន់ធ្ងរ</th>
              <th scope="col">ផលវិបាក</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>យឺត ១ – ៣ ថ្ងៃ</td>
              <td><span class="sl-severity-badge low">🟢 ទាប</span></td>
              <td>ត្រឹមតែការហៅទូរស័ព្ទរំលឹក គ្មានការគិតថ្លៃផាកពិន័យទេ</td>
            </tr>
            <tr>
              <td>យឺត ៤ – ១៥ ថ្ងៃ</td>
              <td><span class="sl-severity-badge medium">🟡 មធ្យម</span></td>
              <td>គិតថ្លៃយឺត 1.5% លើសមតុល្យមិនទាន់ទូទាត់</td>
            </tr>
            <tr>
              <td>យឺត ១៦ – ៣០ ថ្ងៃ</td>
              <td><span class="sl-severity-badge high">🔴 ខ្ពស់</span></td>
              <td>បូកថ្លៃផាកពិន័យយឺត + ចេញលិខិតព្រមានជាផ្លូវការ</td>
            </tr>
            <tr>
              <td>លើសពី ៣០ ថ្ងៃ</td>
              <td><span class="sl-severity-badge severe">⛔ ធ្ងន់ធ្ងរ</span></td>
              <td>ទាក់ទងអ្នកធានា ដំណើរការផ្លូវច្បាប់អាចនឹងចាប់ផ្តើម</td>
            </tr>
            <tr>
              <td>លើសពី ៦០ ថ្ងៃ</td>
              <td><span class="sl-severity-badge severe">⛔ ធ្ងន់ធ្ងរបំផុត</span></td>
              <td>រឹបអូសទ្រព្យបញ្ចាំប្រសិនបើមាន — ចំណាត់ការផ្លូវច្បាប់</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>


<!-- ============================================================
     WHY CHOOSE US
     ============================================================ -->
<div class="sl-why-bg">
  <div class="sl-why-section" id="why-us" aria-labelledby="why-title">
    <div class="sl-section-header sl-fade-up">
      <span class="sl-section-tag">ហេតុអ្វី SetecLoan</span>
      <h2 class="sl-section-title" id="why-title">ហេតុអ្វី ជ្រើសរើស <span style="color:var(--accent-light)">យើង?</span></h2>
      <p class="sl-section-desc">
        យើងយកតម្លាភាព ល្បឿន និងសុខុមាលភាពហិរញ្ញវត្ថុរបស់អ្នកជាចំណុចកណ្តាលនៃរាល់សកម្មភាពដែលយើងធ្វើ។
      </p>
    </div>

    <div class="sl-why-grid">
      <div class="sl-why-card sl-fade-up" id="why-transparent">
        <div class="sl-why-icon">🔍</div>
        <div class="sl-why-title">តម្លាភាព 100%</div>
        <p class="sl-why-desc">គ្មានថ្លៃលាក់កំបាំងទេ។ អ្នកឃើញចំនួនសងសរុបពិតប្រាកដមុនពេលចុះហត្ថលេខា។</p>
      </div>
      <div class="sl-why-card sl-fade-up" id="why-fast">
        <div class="sl-why-icon">⚡</div>
        <div class="sl-why-title">ការអនុម័តរហ័ស</div>
        <p class="sl-why-desc">ក្រុមការងារយើងផ្ទៀងផ្ទាត់និងសម្រេចក្នុង ១-២ ថ្ងៃធ្វើការ។ បើកប្រាក់ថ្ងៃដដែលឬថ្ងៃបន្ទាប់។</p>
      </div>
      <div class="sl-why-card sl-fade-up" id="why-flexible">
        <div class="sl-why-icon">🔧</div>
        <div class="sl-why-title">ការសងបត់បែន</div>
        <p class="sl-why-desc">ជ្រើសរើសការបង់រំលស់ស្មើៗគ្នាឬបង់តែការប្រាក់មុន។ សងមុនកាលកំណត់ពេលណាក៏បាន គ្មានពិន័យ។</p>
      </div>
      <div class="sl-why-card sl-fade-up" id="why-private">
        <div class="sl-why-icon">🔒</div>
        <div class="sl-why-title">ឯកជន និងសុវត្ថិភាព</div>
        <p class="sl-why-desc">ទិន្នន័យរបស់អ្នកនៅជាមួយយើង។ ជាសម្ងាត់បំផុត មិនចែករំលែកជាមួយភាគីទីបីឡើយ។</p>
      </div>
      <div class="sl-why-card sl-fade-up" id="why-currency">
        <div class="sl-why-icon">💱</div>
        <div class="sl-why-title">ដុល្លារ និងរៀល</div>
        <p class="sl-why-desc">ខ្ចី និងសងជារូបិយប័ណ្ណដុល្លារអាមេរិក ឬប្រាក់រៀលខ្មែរ — តាមជម្រើសរបស់អ្នក។</p>
      </div>
      <div class="sl-why-card sl-fade-up" id="why-rights">
        <div class="sl-why-icon">🛡️</div>
        <div class="sl-why-title">សិទ្ធិអតិថិជនជាចម្បង</div>
        <p class="sl-why-desc">អ្នកអាចប្តឹងតវ៉ាពីសេចក្តីសម្រេចណាមួយក្នុងរយៈពេល ៧ ថ្ងៃ។ យើងគោរពសិទ្ធិសេរីភាពហិរញ្ញវត្ថុរបស់អ្នក។</p>
      </div>
    </div>
  </div>
</div>


<!-- ============================================================
     CTA BANNER
     ============================================================ -->
<section class="sl-cta-section" id="contact" aria-labelledby="cta-title">
  <div class="sl-cta-inner">
    <div class="sl-fade-up">
      <span class="sl-section-tag">ចាប់ផ្តើមថ្ងៃនេះ</span>
      <h2 class="sl-section-title" id="cta-title">ត្រៀមខ្លួន <span>ដាក់ពាក្យហើយឬនៅ?</span></h2>
      <p>
        មិនថាអ្នកត្រូវការ ៥០ ដុល្លារសម្រាប់ពេលបន្ទាន់ ឬ ២០,០០០ ដុល្លារដើម្បីពង្រីកអាជីវកម្មរបស់អ្នក —
        SetecLoan នៅទីនេះដើម្បីជួយ។ ទាក់ទងមកយើងថ្ងៃនេះ ឬមកកាន់ការិយាល័យរបស់យើងដើម្បីចាប់ផ្តើមពាក្យសុំរបស់អ្នក។
      </p>
    </div>
    <div class="sl-cta-btns sl-fade-up">
      <a href="tel:+85500000000" class="sl-btn-primary" id="cta-call-btn">📞 ទូរស័ព្ទមកយើងឥឡូវនេះ</a>
      <a href="mailto:info@setecloan.com" class="sl-btn-primary-dark" id="cta-email-btn">✉️ អ៊ីមែលមកយើង</a>
    </div>
  </div>
</section>


<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer class="sl-footer" id="footer" role="contentinfo">
  <div class="sl-footer-inner">
    <div class="sl-footer-grid">
      <!-- Brand -->
      <div class="sl-footer-brand">
        <div class="brand">
          <div class="brand-logo">S</div>
          <span class="brand-name">Setec<span>Loan</span></span>
        </div>
        <p>
          សេវាកម្មកម្ចីឯកជននិងមីក្រូហិរញ្ញវត្ថុដែលគួរឱ្យទុកចិត្តដែលបម្រើដល់បុគ្គល និង
          អាជីវកម្មខ្នាតតូចនៅកម្ពុជា។ រហ័ស យុត្តិធម៌ និងមានតម្លាភាពពេញលេញ។
        </p>
      </div>

      <!-- Products -->
      <div class="sl-footer-col">
        <h5>ផលិតផលកម្ចី</h5>
        <ul>
          <li><a href="#products" id="footer-personal">កម្ចីផ្ទាល់ខ្លួន</a></li>
          <li><a href="#products" id="footer-business">កម្ចីអាជីវកម្ម</a></li>
          <li><a href="#products" id="footer-emergency">កម្ចីបន្ទាន់</a></li>
          <li><a href="#products" id="footer-salary">កម្ចីបើកប្រាក់ខែមុន</a></li>
        </ul>
      </div>

      <!-- Quick Links -->
      <div class="sl-footer-col">
        <h5>តំណរភ្ជាប់រហ័ស</h5>
        <ul>
          <li><a href="#rates"       id="footer-rates">អត្រាការប្រាក់</a></li>
          <li><a href="#eligibility" id="footer-elig">លក្ខខណ្ឌ</a></li>
          <li><a href="#process"     id="footer-process">របៀបដំណើរការ</a></li>
          <li><a href="#policies"    id="footer-policies">គោលការណ៍</a></li>
          <li><a href="#why-us"      id="footer-why">ហេតុអ្វីជ្រើសរើសយើង</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="sl-footer-col">
        <h5>ទំនាក់ទំនង</h5>
        <div class="sl-contact-item">
          <div class="ci-icon">📍</div>
          <div class="ci-text">
            <strong>អាសយដ្ឋាន</strong>
            រាជធានីភ្នំពេញ, កម្ពុជា
          </div>
        </div>
        <div class="sl-contact-item">
          <div class="ci-icon">📞</div>
          <div class="ci-text">
            <strong>ទូរស័ព្ទ</strong>
            +855 00 000 000
          </div>
        </div>
        <div class="sl-contact-item">
          <div class="ci-icon">✉️</div>
          <div class="ci-text">
            <strong>អ៊ីមែល</strong>
            info@setecloan.com
          </div>
        </div>
        <div class="sl-contact-item">
          <div class="ci-icon">🕐</div>
          <div class="ci-text">
            <strong>ម៉ោងធ្វើការ</strong>
            ចន្ទ – សៅរ៍, 7:00 ព្រឹក – 6:00 ល្ងាច
          </div>
        </div>
      </div>
    </div>

    <hr class="sl-footer-divider" />

    <div class="sl-footer-bottom">
      <p>© {{ date('Y') }} <span class="accent">SetecLoan</span>. រក្សាសិទ្ធិគ្រប់យ៉ាង។ កម្ចីឯកជន & មីក្រូហិរញ្ញវត្ថុ។</p>
      <div class="sl-footer-bottom-links">
        <a href="#" id="footer-privacy">គោលការណ៍ឯកជនភាព</a>
        <a href="#" id="footer-terms">លក្ខខណ្ឌនៃសេវាកម្ម</a>
        <a href="#contact" id="footer-contact-link">ទំនាក់ទំនង</a>
      </div>
    </div>
  </div>
</footer>

<!-- Back to top -->
<button class="sl-back-top" id="sl-back-top" aria-label="Back to top" title="Back to top">↑</button>

<!-- Frontend JS -->
<script src="{{ asset('assets/js/frontend.js') }}"></script>
</body>
</html>
