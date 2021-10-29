'use strict';

const p = console.log;
const host = 'http://localhost';

loginSubmit.onclick = () => {
  fetch(`${host}/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      email: loginEmail.value,
      password: loginPassword.value,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      localStorage.setItem('token', data.token);
      p(localStorage.getItem('token'));
    });
};

usersIdGetSubmit.onclick = () => {
  fetch(`${host}/users/${usersIdGetId.value}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })
    .then((res) => res.json())
    .then(p);
};

usersGetSubmit.onclick = () => {
  fetch(`${host}/users`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })
    .then((res) => res.json())
    .then(p);
};

threadsGetSubmit.onclick = () => {
  const params = {
    per_page: threadsGetPerPage.value,
    page: threadsGetPage.value,
    q: threadsGetQ.value,
  };
  const queryParams = new URLSearchParams(params);
  fetch(`${host}/threads?${queryParams}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })
    .then((res) => res.json())
    .then(p);
};

threadsPostSubmit.onclick = () => {
  fetch(`${host}/threads`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
    body: JSON.stringify({
      title: threadsPostTitle.value,
    }),
  })
    .then((res) => res.json())
    .then(p);
};

repliesGetSubmit.onclick = () => {
  const params = {
    thread_id: repliesGetThreadId.value,
  };
  const queryParams = new URLSearchParams(params);
  fetch(`${host}/replies?${queryParams}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })
    .then((res) => res.json())
    .then(p);
};

repliesPostSubmit.onclick = () => {
  fetch(`${host}/replies`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
    body: JSON.stringify({
      thread_id: repliesPostThreadId.value,
      text: repliesPostText.value,
    }),
  })
    .then((res) => res.json())
    .then(p);
};
