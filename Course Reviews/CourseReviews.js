let courses = [];
let filteredCourses = [];
let currentPage = 1;
const itemsPerPage = 2;
const API_URL = '/api.php';

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
        const response = await fetch(API_URL);
        courses = await response.json();
        filteredCourses = [...courses];
        applyFilters();
    } catch (error) {
        courseGrid.innerHTML = `<p>Error: ${error.message}</p>`;
    }
}

function renderCourses(data) {
    courseGrid.innerHTML = "";
    const start = (currentPage - 1) * itemsPerPage;
    const paginated = data.slice(start, start + itemsPerPage);

    if (paginated.length === 0) {
        courseGrid.innerHTML = `<p>No courses found.</p>`;
        return;
    }

    paginated.forEach(course => {
        const card = document.createElement('article');
        card.classList.add('card');
        card.innerHTML = `
            <h2>${course.name}</h2>
            <p>By ${course.instructor} | Rating: ${course.rating.toFixed(2)} / 5</p>
            <p>${course.review}</p>
            <a href="#" data-id="${course.id}">Read More</a>
        `;
        courseGrid.appendChild(card);
    });
}

function renderPagination(data) {
    paginationDiv.innerHTML = '';
    const pages = Math.ceil(data.length / itemsPerPage);

    for (let i = 1; i <= pages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if (i ===
