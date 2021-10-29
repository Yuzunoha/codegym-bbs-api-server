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
    .then((data) => console.log(data));
};
