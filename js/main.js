/**
 * FoodBook - Main JavaScript Application
 * Student Name : Ishara Nishshanka
 * Registration : ITT/2021/076
 * Course       : ICT 1209 – Web Technologies Mini Project
 */

document.addEventListener('DOMContentLoaded', () => {
  initNavbarScroll();
  initSmoothScroll();
  initHeroSlider();
  initRecipeFilter();
  initRecipeModal();
  initCounterAnimation();
  initScrollAnimations();
  initTooltips();
});

/* ============================================================
   1. NAVBAR SHRINK ON SCROLL
   ============================================================ */
function initNavbarScroll() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-scrolled', 'shadow-sm');
    } else {
      navbar.classList.remove('navbar-scrolled', 'shadow-sm');
    }
  });
}

/* ============================================================
   2. SMOOTH SCROLLING FOR NAVIGATION LINKS
   ============================================================ */
function initSmoothScroll() {
  const smoothLinks = document.querySelectorAll('a[href^="#"]:not([href="#"]):not([data-bs-toggle])');

  smoothLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);

      if (targetElement) {
        e.preventDefault();
        const headerOffset = 70;
        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
}

/* ============================================================
   3. INTERACTIVE HERO IMAGE SLIDER
   ============================================================ */
function initHeroSlider() {
  const slider = document.querySelector('.hero-slider');
  if (!slider) return;

  const slides = slider.querySelectorAll('.slide-item');
  const dotsContainer = document.querySelector('.slider-dots');
  const prevBtn = document.querySelector('.slider-prev');
  const nextBtn = document.querySelector('.slider-next');

  if (!slides.length) return;

  let currentSlide = 0;
  let slideInterval = null;
  const intervalTime = 4500;

  // Build dots dynamically if container exists
  if (dotsContainer) {
    dotsContainer.innerHTML = '';
    slides.forEach((_, idx) => {
      const dot = document.createElement('button');
      dot.className = `slider-dot ${idx === 0 ? 'active' : ''}`;
      dot.setAttribute('aria-label', `Slide ${idx + 1}`);
      dot.addEventListener('click', () => {
        goToSlide(idx);
        restartTimer();
      });
      dotsContainer.appendChild(dot);
    });
  }

  function goToSlide(index) {
    slides[currentSlide].classList.remove('active');
    const dots = document.querySelectorAll('.slider-dot');
    if (dots.length) dots[currentSlide].classList.remove('active');

    currentSlide = (index + slides.length) % slides.length;

    slides[currentSlide].classList.add('active');
    if (dots.length) dots[currentSlide].classList.add('active');
  }

  function nextSlide() {
    goToSlide(currentSlide + 1);
  }

  function prevSlide() {
    goToSlide(currentSlide - 1);
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      restartTimer();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      restartTimer();
    });
  }

  function startTimer() {
    if (!slideInterval) {
      slideInterval = setInterval(nextSlide, intervalTime);
    }
  }

  function stopTimer() {
    clearInterval(slideInterval);
    slideInterval = null;
  }

  function restartTimer() {
    stopTimer();
    startTimer();
  }

  // Pause on hover
  slider.addEventListener('mouseenter', stopTimer);
  slider.addEventListener('mouseleave', startTimer);

  startTimer();
}

/* ============================================================
   4. DYNAMIC RECIPE SEARCH & CATEGORY FILTER
   ============================================================ */
function initRecipeFilter() {
  const searchInput = document.getElementById('recipeSearchInput');
  const categorySelect = document.getElementById('recipeCategoryFilter');
  const cardsContainer = document.getElementById('recipesContainer');
  const recipeCards = document.querySelectorAll('.recipe-card-item');
  const noResultsMsg = document.getElementById('noRecipesMessage');
  const resultCounter = document.getElementById('filterResultCount');

  if (!recipeCards.length) return;

  function filterRecipes() {
    const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const selectedCategory = categorySelect ? categorySelect.value.toLowerCase().trim() : 'all';

    let visibleCount = 0;

    recipeCards.forEach(card => {
      const title = card.getAttribute('data-title') || '';
      const category = card.getAttribute('data-category') || '';
      const ingredients = card.getAttribute('data-ingredients') || '';

      const matchesSearch = query === '' || 
        title.toLowerCase().includes(query) || 
        ingredients.toLowerCase().includes(query);

      const matchesCategory = selectedCategory === 'all' || 
        category.toLowerCase() === selectedCategory;

      if (matchesSearch && matchesCategory) {
        card.style.display = 'block';
        card.classList.add('animate-fade-in');
        visibleCount++;
      } else {
        card.style.display = 'none';
        card.classList.remove('animate-fade-in');
      }
    });

    // Update Result count
    if (resultCounter) {
      resultCounter.textContent = `Showing ${visibleCount} recipe${visibleCount === 1 ? '' : 's'}`;
    }

    // Toggle No Results message
    if (noResultsMsg) {
      noResultsMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  }

  if (searchInput) {
    searchInput.addEventListener('input', filterRecipes);
  }

  if (categorySelect) {
    categorySelect.addEventListener('change', filterRecipes);
  }

  // Category Quick Pill Buttons if available
  const catPills = document.querySelectorAll('.category-filter-pill');
  catPills.forEach(pill => {
    pill.addEventListener('click', function () {
      catPills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');

      const catValue = this.getAttribute('data-category');
      if (categorySelect) {
        categorySelect.value = catValue;
      }
      filterRecipes();
    });
  });
}

/* ============================================================
   5. INTERACTIVE RECIPE DETAIL MODAL POPUP
   ============================================================ */
function initRecipeModal() {
  const modalEl = document.getElementById('recipeDetailModal');
  if (!modalEl) return;

  // View recipe detail button triggers
  const viewBtns = document.querySelectorAll('.btn-view-recipe');

  viewBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      const card = this.closest('.recipe-card-item');
      if (!card) return;

      const title = card.getAttribute('data-title') || 'Delicious Recipe';
      const category = card.getAttribute('data-category') || 'General';
      const time = card.getAttribute('data-time') || '30';
      const servings = card.getAttribute('data-servings') || '4';
      const rating = card.getAttribute('data-rating') || '4.5';
      const author = card.getAttribute('data-author') || 'FoodBook Chef';
      const image = card.getAttribute('data-image') || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600';
      const description = card.getAttribute('data-description') || '';
      const ingredients = (card.getAttribute('data-ingredients') || '').split('\n').filter(i => i.trim() !== '');
      const instructions = (card.getAttribute('data-instructions') || '').split('\n').filter(i => i.trim() !== '');

      // Populate Modal elements
      const mTitle = document.getElementById('modalRecipeTitle');
      const mCategory = document.getElementById('modalRecipeCategory');
      const mImage = document.getElementById('modalRecipeImage');
      const mTime = document.getElementById('modalRecipeTime');
      const mServings = document.getElementById('modalRecipeServings');
      const mRating = document.getElementById('modalRecipeRating');
      const mAuthor = document.getElementById('modalRecipeAuthor');
      const mDesc = document.getElementById('modalRecipeDescription');
      const mIngList = document.getElementById('modalRecipeIngredients');
      const mStepsList = document.getElementById('modalRecipeInstructions');

      if (mTitle) mTitle.textContent = title;
      if (mCategory) mCategory.textContent = category;
      if (mImage) {
        mImage.src = image;
        mImage.alt = title;
      }
      if (mTime) mTime.textContent = `${time} mins`;
      if (mServings) mServings.textContent = `${servings} servings`;
      if (mRating) mRating.textContent = `★ ${rating}`;
      if (mAuthor) mAuthor.textContent = `By ${author}`;
      if (mDesc) mDesc.textContent = description;

      // Ingredients list
      if (mIngList) {
        mIngList.innerHTML = '';
        ingredients.forEach(item => {
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex align-items-center';
          li.innerHTML = `<span class="badge bg-danger-subtle text-danger rounded-circle me-2 p-1">✔</span> ${escapeHTML(item)}`;
          mIngList.appendChild(li);
        });
      }

      // Instructions list
      if (mStepsList) {
        mStepsList.innerHTML = '';
        instructions.forEach(step => {
          const li = document.createElement('li');
          li.className = 'mb-2 pb-2 border-bottom';
          li.textContent = step;
          mStepsList.appendChild(li);
        });
      }

      // Show Bootstrap Modal
      const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
      bsModal.show();
    });
  });
}

function escapeHTML(str) {
  return str.replace(/[&<>'"]/g, 
    tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
  );
}

/* ============================================================
   6. STATS COUNTER ANIMATION
   ============================================================ */
function initCounterAnimation() {
  const counters = document.querySelectorAll('.stat-counter');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const counter = entry.target;
        const target = +counter.getAttribute('data-target') || 0;
        const duration = 1600; // ms
        const stepTime = 20;
        const steps = duration / stepTime;
        const increment = target / steps;
        let current = 0;

        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            counter.textContent = target.toLocaleString() + '+';
            clearInterval(timer);
          } else {
            counter.textContent = Math.floor(current).toLocaleString();
          }
        }, stepTime);

        obs.unobserve(counter);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(c => observer.observe(c));
}

/* ============================================================
   7. SCROLL FADE-IN ANIMATION
   ============================================================ */
function initScrollAnimations() {
  const animatedElements = document.querySelectorAll('.animate-on-scroll');
  if (!animatedElements.length) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  animatedElements.forEach(el => observer.observe(el));
}

/* ============================================================
   8. BOOTSTRAP TOOLTIPS INITIALIZATION
   ============================================================ */
function initTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}
