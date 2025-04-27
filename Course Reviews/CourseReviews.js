let mockCourses = [];
let filteredCourses = [];
let currentPage = 1;
const itemsPerPage = 2;

const courseGrid = document.querySelector('.section-grid');
const searchInput = document.querySelector('.search-section input[type="search"]');
const collegeFilter = document.querySelectorAll('.search-section select')[0];
const sortSelect = document.querySelectorAll('.search-section select')[1];
const paginationDiv = document.querySelector('.pagination');
const form = document.querySelector('.form-container form');
const detailContainer = document.querySelector('.detail-container');
const addReviewButton = document.querySelector('.btn-add');

function showLoading() {
  courseGrid.innerHTML = `<p>Loading courses...</p>`;
}

async function fetchCourses() {
  showLoading();
  try {
    const response = await fetch('assets/data/courses.json');
    if (!response.ok) {
      throw new Error('Failed to fetch courses.');
    }
    const data = await response.json();
    mockCourses = data;
    filteredCourses = [...mockCourses];
    applyFilters();
  } catch (error) {
    courseGrid.innerHTML = `<p>Error loading courses: ${error.message}</p>`;
  }
}

function renderCourses(courses) {
  courseGrid.innerHTML = "";
  const start = (currentPage - 1) * itemsPerPage;
  const paginated = courses.slice(start, start + itemsPerPage);

  if (paginated.length === 0) {
    courseGrid.innerHTML = `<p>No courses found.</p>`;
    return;
  }

  paginated.forEach(course => {
    const card = document.createElement('article');
    card.classList.add('card');
    card.innerHTML = `
      <h2>${course.name}</h2>
      <p>By ${course.instructor} | ${course.rating.toFixed(2)} / 5</p>
      <p>${course.review}</p>
      <a href="#" data-id="${course.id}">Read More</a>
    `;
    courseGrid.appendChild(card);
  });
}

function renderPagination(courses) {
  paginationDiv.innerHTML = '';
  const pages = Math.ceil(courses.length / itemsPerPage);

  for (let i = 1; i <= pages; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    if (i === currentPage) btn.classList.add('highlight');
    btn.addEventListener('click', () => {
      currentPage = i;
      renderCourses(filteredCourses);
      renderPagination(filteredCourses);
    });
    paginationDiv.appendChild(btn);
  }
}

function applyFilters() {
  const search = searchInput.value.toLowerCase();
  const selectedCollege = collegeFilter.value;
  const sortOption = sortSelect.value;

  filteredCourses = mockCourses.filter(course => {
    const matchName = course.name.toLowerCase().includes(search);
    const matchCollege = selectedCollege === "Filter by Collages" || course.college === selectedCollege;
    return matchName && matchCollege;
  });

  if (sortOption === "Newest First") {
    filteredCourses.sort((a, b) => b.rating - a.rating);
  } else if (sortOption === "Oldest First") {
    filteredCourses.sort((a, b) => a.rating - b.rating);
  }

  currentPage = 1;
  renderCourses(filteredCourses);
  renderPagination(filteredCourses);
}

form.addEventListener('submit', (e) => {
  e.preventDefault();

  const courseName = form.querySelector('input[placeholder="e.g., Data Structures"]').value.trim();
  const instructor = form.querySelector('input[placeholder="e.g., Prof. Johnson"]').value.trim();
  const rating = form.querySelector('input[type="number"]').value.trim();
  const review = form.querySelector('textarea').value.trim();

  if (!courseName || !instructor || !rating || !review) {
    alert('Please fill all fields!');
    return;
  }

  if (rating < 1 || rating > 5) {
    alert('Rating must be between 1 and 5.');
    return;
  }

  const newCourse = {
    id: mockCourses.length + 1,
    name: courseName,
    instructor,
    rating: parseFloat(rating),
    review,
    college: "Collage of Information Technology"
  };

  mockCourses.push(newCourse);
  applyFilters();
  form.reset();
  alert('New review added successfully!');
});

courseGrid.addEventListener('click', (e) => {
  if (e.target.tagName === 'A') {
    e.preventDefault();
    const id = parseInt(e.target.dataset.id);
    const course = mockCourses.find(c => c.id === id);

    if (course) {
      detailContainer.innerHTML = `
        <h2>${course.name}</h2>
        <p><strong>Instructor:</strong> ${course.instructor}</p>
        <p><strong>Rating:</strong> ${"★".repeat(Math.round(course.rating))}</p>
        <p>${course.review}</p>
        <div class="form-group">
          <button class="btn-edit">Edit</button>
          <button class="btn-delete">Delete</button>
          <a href="#" class="back-link">← Back to listing</a>
        </div>
      `;
      detailContainer.scrollIntoView({ behavior: 'smooth' });
    }
  }
});

function initializeApp() {
  fetchCourses();
}

searchInput.addEventListener('input', applyFilters);
collegeFilter.addEventListener('change', applyFilters);
sortSelect.addEventListener('change', applyFilters);
addReviewButton.addEventListener('click', () => {
  form.scrollIntoView({ behavior: 'smooth' });
});

initializeApp();
