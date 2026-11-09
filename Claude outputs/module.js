// Persona switcher -- on click, mark the clicked button active and clear
// "active" from its siblings. Each button is a plain link (href can point
// anywhere); this does not call preventDefault, so clicking still follows
// the link as normal -- it only updates which button looks selected.
// Scoped per module instance via querySelectorAll so more than one
// Persona switcher can sit on the same page without interfering with
// each other.

function initPersonaSwitchers() {
  document.querySelectorAll('.nd-persona-switcher').forEach(function (switcher) {
    var tabs = switcher.querySelectorAll('.nd-persona-switcher__tab');

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          var isActive = t === tab;
          t.classList.toggle('active', isActive);
          t.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
      });
    });
  });
}

// Scroll reveal -- fades/slides the whole switcher up into place the first
// time it enters the viewport. Progressive enhancement only: the "nd-reveal"
// class (which is what actually hides the element, see module.html's <style>)
// is added here in JS rather than baked into the CSS, so a visitor without
// IntersectionObserver support, or with prefers-reduced-motion set, simply
// never gets the class and sees the switcher fully visible with no motion.
function initPersonaSwitcherReveal() {
  if (!('IntersectionObserver' in window)) { return; }
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

  document.querySelectorAll('.nd-persona-switcher').forEach(function (switcher) {
    if (switcher.dataset.ndAnimBound) { return; }
    switcher.dataset.ndAnimBound = 'true';

    var inner = switcher.querySelector('.nd-persona-switcher__in');
    if (!inner) { return; }
    inner.classList.add('nd-reveal');

    var revealed = false;
    function reveal() {
      if (revealed) { return; }
      revealed = true;
      inner.classList.add('nd-reveal-in');
    }

    var observer = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) { return; }
        reveal();
        obs.unobserve(switcher);
      });
    }, { threshold: 0.3 });

    observer.observe(switcher);

    // Safety net: a backgrounded tab at page load (common when a preview
    // link opens in a tab that isn't focused immediately), or any other
    // case where the browser never reports an intersection, must not be
    // able to leave this content invisible forever -- force it visible if
    // the observer hasn't fired within a couple of seconds.
    setTimeout(reveal, 2000);
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function () {
    initPersonaSwitchers();
    initPersonaSwitcherReveal();
  });
} else {
  initPersonaSwitchers();
  initPersonaSwitcherReveal();
}
