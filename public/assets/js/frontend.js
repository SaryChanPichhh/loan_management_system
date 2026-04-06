/* ============================================================
   SetecLoan — Frontend Portfolio JavaScript
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Navbar scroll effect ---------- */
  const navbar = document.getElementById('sl-navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 60) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  /* ---------- Hamburger menu ---------- */
  const hamburger = document.getElementById('sl-hamburger');
  const navLinks  = document.getElementById('sl-nav-links');

  if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      const spans = hamburger.querySelectorAll('span');
      hamburger.classList.toggle('active');
      if (hamburger.classList.contains('active')) {
        spans[0].style.transform = 'translateY(7px) rotate(45deg)';
        spans[1].style.opacity   = '0';
        spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
      } else {
        spans[0].style.transform = '';
        spans[1].style.opacity   = '';
        spans[2].style.transform = '';
      }
    });

    // Close on nav link click
    navLinks.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        navLinks.classList.remove('open');
        hamburger.classList.remove('active');
        hamburger.querySelectorAll('span').forEach(s => s.style = '');
      });
    });
  }

  /* ---------- Smooth scroll ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 80;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  /* ---------- Particle generator ---------- */
  const particleContainer = document.getElementById('sl-particles');
  if (particleContainer) {
    const count = 18;
    for (let i = 0; i < count; i++) {
      const p   = document.createElement('div');
      const size = Math.random() * 12 + 4;
      p.className = 'sl-particle';
      Object.assign(p.style, {
        width:            size + 'px',
        height:           size + 'px',
        left:             Math.random() * 100 + '%',
        animationDuration: (Math.random() * 16 + 10) + 's',
        animationDelay:   (Math.random() * 12) + 's',
        opacity:          Math.random() * 0.5 + 0.1,
      });
      particleContainer.appendChild(p);
    }
  }

  /* ---------- Counter animation ---------- */
  function animateCounter(el) {
    const target   = parseFloat(el.dataset.target);
    const suffix   = el.dataset.suffix || '';
    const prefix   = el.dataset.prefix || '';
    const duration = 1800;
    const step     = 16;
    const increment = target / (duration / step);
    let current = 0;

    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      const display = Number.isInteger(target)
        ? Math.floor(current)
        : current.toFixed(1);
      el.textContent = prefix + display + suffix;
    }, step);
  }

  /* ---------- Intersection Observer: fade-up & counters ---------- */
  const fadeItems = document.querySelectorAll('.sl-fade-up, .sl-fade-in');
  const counters  = document.querySelectorAll('[data-target]');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  fadeItems.forEach((el, i) => {
    el.style.transitionDelay = (i % 6) * 0.08 + 's';
    observer.observe(el);
  });

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(c => counterObserver.observe(c));

  /* ---------- Back to top ---------- */
  const backTop = document.getElementById('sl-back-top');
  if (backTop) {
    window.addEventListener('scroll', () => {
      backTop.classList.toggle('visible', window.scrollY > 400);
    });
    backTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---------- Active nav link on scroll ---------- */
  const sections   = document.querySelectorAll('section[id]');
  const navAnchors = document.querySelectorAll('.sl-nav-links a[href^="#"]');

  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(sec => {
      if (window.scrollY >= sec.offsetTop - 120) {
        current = sec.getAttribute('id');
      }
    });
    navAnchors.forEach(a => {
      a.style.color = a.getAttribute('href') === '#' + current
        ? 'var(--accent-light)'
        : '';
    });
  });

});
