import galleryImagesJson from './images.json' with { type: 'json' }

const categoryNav = document.querySelectorAll('.category-nav li');
const galleryContainer = document.getElementById('galleryContainer');
const galleryImages = galleryImagesJson.images;
const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("modalImage");
const modalCaption = document.getElementById("modalCaption");
const closeModal = document.getElementById("closeModal");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

let currentCategory = null;
let currentYear = null;
let currentIndex = -1;
let currentImages = [];

function safeQuery(selector, parent = document) {
  const el = parent.querySelector(selector);
  return el || null;
}

function populateYearDropdowns() {
  const categories = [...new Set(galleryImages.map(img => img.category))];
  
  categories.forEach(category => {
    const years = [...new Set(
      galleryImages
        .filter(img => img.category === category)
        .map(img => img.year)
    )].sort((a, b) => b - a);
    
    const li = document.querySelector(`.category-nav li[data-category="${category}"]`);
    if (!li) return;
    const dropdown = safeQuery('.year-dropdown', li);
    if (!dropdown) return;
    dropdown.innerHTML = `<option value="all">All Time</option>`;
    years.forEach(year => {
      const option = document.createElement('option');
      option.value = year;
      option.textContent = year;
      dropdown.appendChild(option);
    });
    dropdown.value = 'all';
  });
}



function renderGallery() {
  galleryContainer.classList.remove('fade-in', 'fade-out');
  galleryContainer.classList.add('fade-out');

  setTimeout(() => {
    let images = galleryImages.filter(img => img.category === currentCategory);
    if (currentYear) images = images.filter(img => img.year === currentYear);
    if (!images || images.length === 0) {
      galleryContainer.innerHTML = `
        <div class="no-images">
          <p>No images found for this selection.</p>
        </div>
      `;

      currentImages = [];
      galleryContainer.classList.remove('fade-out');
      galleryContainer.classList.add('fade-in');
      setTimeout(() => galleryContainer.classList.remove('fade-in'), 400);
      return;
    }

    galleryContainer.innerHTML = images.map((img, index) => `
      <div class="images-card" data-index="${index}">
        <img src="${img.src}" alt="${img.title || 'Gallery image'}" loading="lazy" data-index="${index}">
        <div class="image-hover-caption">
          <span class="title">${img.title || ''}</span>
          <span class="description">${img.description || ''}</span>
          ${img.credit ? `<span class="credit">Credit: ${img.credit}</span>` : ''}
        </div>
      </div>
    `).join('');
    document.querySelectorAll('.images-card img').forEach((imgEl) => {
      imgEl.addEventListener('click', () => {
        const index = parseInt(imgEl.dataset.index, 10);
        openModal(index, images);
      });
    });
    galleryContainer.classList.remove('fade-out');
    galleryContainer.classList.add('fade-in');
    setTimeout(() => galleryContainer.classList.remove('fade-in'), 400);
  }, 300);
}

function openModal(index, images) {
  if (!images || images.length === 0) return;
  currentIndex = index;
  const imgData = images[index];

  currentImages = images;
  modal.classList.add('show');
  modalImg.src = imgData.src;
  modalImg.alt = imgData.title || 'Gallery image';

  // modalCaption.textContent = `${imgData.title || ''}${imgData.description ? ' — ' + imgData.description : ''}`;
  modalCaption.innerHTML = `
  <p><strong>${imgData.title || ''}</strong></p>
  ${imgData.description ? `<p>${imgData.description}</p>` : ''}
  ${imgData.credit ? `<p class="modal-credit"><em>Credit:</em> ${imgData.credit}</p>` : ''}
  ${imgData.date ? `<p class="modal-date"><strong>Date:</strong> ${imgData.date}</p>` : ''}
`;
}

function closeModalFn() {
  modal.classList.remove('show');
  setTimeout(() => {
    currentIndex = -1;
  }, 400);
}

function updateModalContent(index) {
  if (!currentImages || currentImages.length === 0) return;
  const imgData = currentImages[index];
  if (!imgData) return;
  modalImg.src = imgData.src;
  modalImg.alt = imgData.title || 'Gallery image';

  modalCaption.innerHTML = `
    <p><strong>${imgData.title || ''}</strong></p>
    ${imgData.description ? `<p>${imgData.description}</p>` : ''}
    ${imgData.credit ? `<p class="modal-credit"><em>Credit:</em> ${imgData.credit}</p>` : ''}
    ${imgData.date ? `<p class="modal-date"><strong>Date:</strong> ${imgData.date}</p>` : ''}
  `;
}

function showPrev() {
  if (!currentImages || currentImages.length === 0) return;
  currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
  updateModalContent(currentIndex);
}

function showNext() {
  if (!currentImages || currentImages.length === 0) return;
  currentIndex = (currentIndex + 1) % currentImages.length;
  updateModalContent(currentIndex);
}

closeModal.addEventListener('click', closeModalFn);
prevBtn.addEventListener('click', showPrev);
nextBtn.addEventListener('click', showNext);
modal.addEventListener('click', e => { if (e.target === modal) closeModalFn(); });
document.addEventListener('keydown', e => {
  if (!modal.classList.contains('show')) return;
  if (e.key === "Escape") closeModalFn();
  if (e.key === "ArrowLeft") showPrev();
  if (e.key === "ArrowRight") showNext();
});

function activateCategoryLi(li) {
  categoryNav.forEach(el => el.classList.remove('active'));
  li.classList.add('active');
  const dropdown = safeQuery('.year-dropdown', li);
  if (dropdown) dropdown.value = 'all';
  currentCategory = li.dataset.category;
  currentYear = null;
  renderGallery();
}

categoryNav.forEach(li => {
  li.addEventListener('click', (e) => {
    if (e.target.tagName.toLowerCase() === 'select' || e.target.tagName.toLowerCase() === 'option') return;
    activateCategoryLi(li);
  });
  const dropdown = safeQuery('.year-dropdown', li);
  if (dropdown) {
    dropdown.addEventListener('change', (e) => {
      categoryNav.forEach(el => el.classList.remove('active'));
      li.classList.add('active');
      currentCategory = li.dataset.category;
      currentYear = e.target.value === 'all' ? null : parseInt(e.target.value, 10);
      renderGallery();
    });
  }
});

const initialActive = document.querySelector('.category-nav li.active');
if (initialActive) currentCategory = initialActive.dataset.category;
else if (categoryNav[0]) {
  currentCategory = categoryNav[0].dataset.category;
  categoryNav[0].classList.add('active');
}




populateYearDropdowns();
renderGallery();
