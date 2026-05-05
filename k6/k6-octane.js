import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
  vus: 300,
  duration: '30s',
  // iterations: 10000,
};

const serverAddr = 'http://127.0.0.1:8000/users'
// console.log(`Making request to ${serverAddr}`);

export default function() {
  let res = http.get(serverAddr);
  check(res, { "status is 200": (res) => res.status === 200 });
  sleep(1);
}
