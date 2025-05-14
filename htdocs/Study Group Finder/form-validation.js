const form = document.getElementById('add-group-form');


if (form) {
  form.addEventListener('submit', function(event) {
    event.preventDefault(); 

    
    const title = document.getElementById('title').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const members = document.getElementById('members').value.trim();
    const description = document.getElementById('description').value.trim();
    const link = document.getElementById('link').value.trim();

  
    const errors = [];

  
    if (title === '') {
      errors.push('Title is required.');
    }
    if (subject === '') {
      errors.push('Subject is required.');
    }
    if (members === '' || isNaN(members) || members <= 0) {
      errors.push('Members must be a positive number.');
    }
    if (description === '') {
      errors.push('Description is required.');
    }
    if (link === '' || !isValidURL(link)) {
      errors.push('A valid link is required.');
    }

    
    const errorContainer = document.getElementById('error-messages');
    if (errors.length > 0) {
      errorContainer.innerHTML = errors.map(error => `<p>${error}</p>`).join('');
    } else {
      errorContainer.innerHTML = '<p style="color: green;">Form is valid! Ready to submit 🚀</p>';
    }
  });
}


function isValidURL(string) {
  try {
    new URL(string);
    return true;
  } catch (_) {
    return false;
  }
}
