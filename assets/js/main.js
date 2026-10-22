/**
 * Royal Aura Luxury Jewellery - Main Customer Script
 * Live search, Buy Now modal with red-border validation, WhatsApp enquiry handler, Auth toggle
 */

document.addEventListener('DOMContentLoaded', function () {
  initLiveSearch();
  initBuyNowModal();
  initEnquiryButtons();
  initAuthToggle();
  initRegisterValidation();
  preventFormCaching();
});

/**
 * 1. LIVE SEARCH WITH THUMBNAILS
 */
function initLiveSearch() {
  const searchInput = document.getElementById('header-live-search');
  const dropdown = document.getElementById('search-results-dropdown');
  if (!searchInput || !dropdown) return;

  let debounceTimer;

  searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const query = this.value.trim();

    if (query.length < 2) {
      dropdown.innerHTML = '';
      dropdown.classList.remove('active');
      return;
    }

    debounceTimer = setTimeout(() => {
      fetch('api/search.php?q=' + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
          if (!data || data.length === 0) {
            dropdown.innerHTML = '<div class="search-empty">No luxury jewels found matching "' + escapeHtml(query) + '"</div>';
          } else {
            let html = '';
            data.forEach(item => {
              html += `
                <a href="product.php?id=${item.id}" class="search-result-item">
                  <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}" class="search-thumb" />
                  <div class="search-info">
                    <div class="search-name">${escapeHtml(item.name)}</div>
                    <div class="search-meta">
                      <span>Model: ${escapeHtml(item.model)}</span>
                      <span>•</span>
                      <span>⚖ ${escapeHtml(item.weight_grams)} g</span>
                    </div>
                  </div>
                  <div class="search-price">${escapeHtml(item.formatted_price)}</div>
                </a>
              `;
            });
            dropdown.innerHTML = html;
          }
          dropdown.classList.add('active');
        })
        .catch(err => {
          console.error('Search error:', err);
        });
    }, 250);
  });

  // Close dropdown on outside click or Escape
  document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove('active');
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      dropdown.classList.remove('active');
    }
  });
}

/**
 * 2. BUY NOW FLOW WITH STRICT RED BORDER VALIDATION & 3-SEC THANK YOU POPUP
 */
function initBuyNowModal() {
  const modal = document.getElementById('buy-now-modal');
  if (!modal) return;

  const closeBtn = modal.querySelector('.modal-close-btn');
  const form = document.getElementById('buy-now-form');
  const buyBtns = document.querySelectorAll('.btn-buy-now-trigger');

  // Input elements
  const inputProductId = document.getElementById('buy-product-id');
  const inputName = document.getElementById('buy-customer-name');
  const inputPhone = document.getElementById('buy-customer-phone');
  const inputAddress = document.getElementById('buy-customer-address');

  // Summary elements inside modal
  const summaryImg = document.getElementById('modal-summary-img');
  const summaryTitle = document.getElementById('modal-summary-title');
  const summaryModel = document.getElementById('modal-summary-model');
  const summaryPrice = document.getElementById('modal-summary-price');

  buyBtns.forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const pId = this.getAttribute('data-id');
      const pName = this.getAttribute('data-name');
      const pModel = this.getAttribute('data-model');
      const pPrice = this.getAttribute('data-price');
      const pImg = this.getAttribute('data-image');

      inputProductId.value = pId;
      if (summaryImg) summaryImg.src = pImg;
      if (summaryTitle) summaryTitle.textContent = pName;
      if (summaryModel) summaryModel.textContent = 'Model: ' + pModel;
      if (summaryPrice) summaryPrice.textContent = pPrice;

      // Clear any previous error states
      clearErrors(form);

      modal.classList.add('active');
    });
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', () => modal.classList.remove('active'));
  }

  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.remove('active');
  });

  // Form submission with validation
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      clearErrors(form);

      let isValid = true;
      const nameVal = inputName.value.trim();
      const phoneVal = inputPhone.value.trim();
      const addressVal = inputAddress.value.trim();

      // Validate Name
      if (!nameVal || nameVal.length < 2) {
        showFieldError(inputName, 'Please enter your full customer name');
        isValid = false;
      }

      // Validate Phone (10 digits)
      const phoneRegex = /^[0-9]{10}$/;
      if (!phoneRegex.test(phoneVal)) {
        showFieldError(inputPhone, 'Please enter a valid 10-digit mobile number');
        isValid = false;
      }

      // Validate Address
      if (!addressVal || addressVal.length < 8) {
        showFieldError(inputAddress, 'Please enter complete delivery address (min 8 characters)');
        isValid = false;
      }

      if (!isValid) return;

      // Submit via AJAX
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span>Processing Order...</span>';

      const formData = new FormData(form);

      fetch('api/buy.php', {
        method: 'POST',
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;

          if (data.success) {
            // Close purchase modal and show thank you modal
            modal.classList.remove('active');
            showThankYouModal(data.order_id);
            form.reset();
          } else {
            alert(data.error || 'Failed to place order. Please try again.');
          }
        })
        .catch(err => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
          console.error(err);
          alert('Network or server error while placing order.');
        });
    });

    // Realtime error removal on input
    [inputName, inputPhone, inputAddress].forEach(input => {
      if (!input) return;
      input.addEventListener('input', function () {
        if (this.classList.contains('is-invalid')) {
          this.classList.remove('is-invalid');
          const err = this.parentElement.querySelector('.error-message');
          if (err) err.remove();
        }
      });
    });
  }
}

function showFieldError(input, message) {
  input.classList.add('is-invalid');
  const existingErr = input.parentElement.querySelector('.error-message');
  if (existingErr) existingErr.remove();

  const errSpan = document.createElement('span');
  errSpan.className = 'error-message';
  errSpan.textContent = message;
  input.parentElement.appendChild(errSpan);
  input.focus();
}

function clearErrors(form) {
  if (!form) return;
  form.querySelectorAll('.form-control').forEach(input => {
    input.classList.remove('is-invalid');
  });
  form.querySelectorAll('.error-message').forEach(err => err.remove());
}

/**
 * Thank You Modal with 3-second progress bar & redirect
 */
function showThankYouModal(orderId) {
  let thankYouModal = document.getElementById('thank-you-modal');
  if (!thankYouModal) {
    thankYouModal = document.createElement('div');
    thankYouModal.id = 'thank-you-modal';
    thankYouModal.className = 'modal-overlay';
    document.body.appendChild(thankYouModal);
  }

  thankYouModal.innerHTML = `
    <div class="modal-content" style="text-align: center; max-width: 480px;">
      <div style="font-size: 3.5rem; color: #d4af37; margin-bottom: 0.8rem; filter: drop-shadow(0 0 15px rgba(212,175,55,0.6));">
        ✦
      </div>
      <h3 class="luxury-heading" style="font-size: 1.8rem; margin-bottom: 0.5rem;">Thank You for Your Order!</h3>
      <p style="color: #cbd5e1; font-size: 0.95rem; margin-bottom: 1rem;">
        Your royal jewellery order <strong>#${escapeHtml(orderId)}</strong> has been placed successfully.
      </p>
      <p style="color: #94a3b8; font-size: 0.85rem;">
        Our concierge will contact you shortly to confirm your dispatch.
      </p>
      <div class="redirect-progress-bar">
        <div class="redirect-progress-fill" id="redirect-fill"></div>
      </div>
      <p style="font-size: 0.78rem; color: #d4af37; margin-top: 0.8rem;">
        Redirecting to home in 3 seconds...
      </p>
    </div>
  `;

  thankYouModal.classList.add('active');

  // Trigger progress fill animation
  setTimeout(() => {
    const fill = document.getElementById('redirect-fill');
    if (fill) fill.style.width = '100%';
  }, 50);

  // Auto redirect after 3 seconds
  setTimeout(() => {
    window.location.href = 'index.php';
  }, 3000);
}

/**
 * 3. ENQUIRY BUTTON (SAVE TO DB + OPEN WHATSAPP)
 */
function initEnquiryButtons() {
  const enquiryBtns = document.querySelectorAll('.btn-enquiry-trigger');

  enquiryBtns.forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const pId = this.getAttribute('data-id');
      const pName = this.getAttribute('data-name');
      const pModel = this.getAttribute('data-model');
      const pWeight = this.getAttribute('data-weight');
      const pPrice = this.getAttribute('data-price');
      const pImg = this.getAttribute('data-image');

      // Record enquiry in DB via API
      fetch('api/enquiry.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + encodeURIComponent(pId)
      })
        .then(res => res.json())
        .then(data => {
          // Open WhatsApp link
          const fullImgUrl = pImg.startsWith('http') ? pImg : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '/') + pImg;
          const msg = `Hello, I am interested in this jewellery: ${pName}, Model: ${pModel}, Weight: ${pWeight} g, Price: ${pPrice}. Image: ${fullImgUrl}`;
          const waNumber = data.whatsapp_number || '916369058169';
          const waUrl = `https://wa.me/${waNumber}?text=` + encodeURIComponent(msg);

          window.open(waUrl, '_blank');
        })
        .catch(err => {
          console.error('Enquiry error:', err);
          // Fallback direct WhatsApp open
          const fullImgUrl = pImg.startsWith('http') ? pImg : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '/') + pImg;
          const msg = `Hello, I am interested in this jewellery: ${pName}, Model: ${pModel}, Weight: ${pWeight} g, Price: ${pPrice}. Image: ${fullImgUrl}`;
          window.open(`https://wa.me/916369058169?text=` + encodeURIComponent(msg), '_blank');
        });
    });
  });
}

/**
 * 4. LOGIN ROLE TOGGLE (CUSTOMER | ADMIN)
 */
function initAuthToggle() {
  const btnCustomer = document.getElementById('role-btn-customer');
  const btnAdmin = document.getElementById('role-btn-admin');
  const roleInput = document.getElementById('auth-role-input');
  const authTitle = document.getElementById('auth-title');
  const registerWrap = document.getElementById('register-link-wrap');

  if (!btnCustomer || !btnAdmin || !roleInput) return;

  btnCustomer.addEventListener('click', function () {
    btnCustomer.classList.add('active');
    btnAdmin.classList.remove('active');
    roleInput.value = 'customer';
    if (authTitle) authTitle.textContent = 'Customer Login';
    if (registerWrap) registerWrap.style.display = 'block';
  });

  btnAdmin.addEventListener('click', function () {
    btnAdmin.classList.add('active');
    btnCustomer.classList.remove('active');
    roleInput.value = 'admin';
    if (authTitle) authTitle.textContent = 'Admin Portal Login';
    if (registerWrap) registerWrap.style.display = 'none';
  });
}

/**
 * 5. REGISTRATION VALIDATION & "OK" POPUP
 */
function initRegisterValidation() {
  const regForm = document.getElementById('register-form');
  if (!regForm) return;

  const phoneInput = document.getElementById('reg-phone');
  const passInput = document.getElementById('reg-password');
  const confirmInput = document.getElementById('reg-confirm-password');

  regForm.addEventListener('submit', function (e) {
    clearErrors(regForm);
    let valid = true;

    // Phone validation (10 digits)
    const phoneVal = phoneInput.value.trim();
    if (!/^[0-9]{10}$/.test(phoneVal)) {
      showFieldError(phoneInput, 'Please enter a valid 10-digit mobile number');
      valid = false;
    }

    // Password length
    if (passInput.value.length < 6) {
      showFieldError(passInput, 'Password must be at least 6 characters');
      valid = false;
    }

    // Password match
    if (passInput.value !== confirmInput.value) {
      showFieldError(confirmInput, 'Passwords do not match');
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
    }
  });
}

/**
 * 6. PREVENT FORM CACHING ON LOGOUT / PAGESHOW
 */
function preventFormCaching() {
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      const forms = document.querySelectorAll('form[autocomplete="off"]');
      forms.forEach(form => form.reset());
    }
  });
}

function escapeHtml(text) {
  if (text === null || text === undefined) return '';
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
