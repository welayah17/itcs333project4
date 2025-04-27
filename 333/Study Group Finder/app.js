const groupsContainer = document.getElementById('groups-container');


groupsContainer.innerHTML = '<p>Loading study groups...</p>';


async function fetchGroups() {
  try {
    const response = await fetch('groups.json');

    if (!response.ok) {
      throw new Error('Failed to fetch groups');
    }

    const groups = await response.json();
    renderGroups(groups);

  } catch (error) {
    console.error(error);
    groupsContainer.innerHTML = '<p>Error loading groups. Please try again later.</p>';
  }
}


function renderGroups(groups) {
  if (groups.length === 0) {
    groupsContainer.innerHTML = '<p>No groups found.</p>';
    return;
  }

  groupsContainer.innerHTML = ''; 
  groups.forEach(group => {
    const card = document.createElement('div');
    card.className = 'group-card';

    card.innerHTML = `
      <h3>${group.title}</h3>
      <p><strong>Subject:</strong> ${group.subject}</p>
      <p><strong>Members:</strong> ${group.members}</p>
      <p>${group.description}</p>
      <a href="${group.link}">Join Group</a>
    `;

    groupsContainer.appendChild(card);
  });
}


fetchGroups();
