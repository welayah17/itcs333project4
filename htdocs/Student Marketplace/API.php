<?php

fetch('/api/create_item.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    title: 'Laptop',
    description: 'Used Dell XPS',
    price: 499.99,
    category: 'Electronics',
    status: true,
    phoneNumber: '123456789',
    publishDate: '2024-05-01 12:00:00',
    service: 'IT Department',
    image: 'https://picsum.photos/200',
    popularity: 0,
    userId: 1
  })
});
