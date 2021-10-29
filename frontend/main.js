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
