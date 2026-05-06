import http from 'k6/http';
import { check } from 'k6';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.2/index.js';

export const options = {
    vus: 300,
    // duration: '30s',
    iterations: 50000,
};

// Parameters:
// TYPE: octane | fpm (default: fpm)
// RESOURCE: users | hello (default: hello)
const type = __ENV.TYPE || 'fpm';
const resource = __ENV.RESOURCE || 'hello';

const baseUrl = type === 'octane'
    ? 'http://127.0.0.1:8000'
    : 'https://octane-app.test';

const serverAddr = `${baseUrl}/${resource}-${type}`;

export default function () {
    let res = http.get(serverAddr);
    check(res, { "status is 200": (res) => res.status === 200 });
}

export function handleSummary(data) {
    const failed = data.metrics.http_req_failed.values.passes;

    // Reporting back to Laravel via API
    // This is the most stable and compatible way to store metrics
    http.post('http://127.0.0.1:8000/stats/report', JSON.stringify({
        connection_errors: failed,
        total_requests: data.metrics.http_reqs.values.count,
        engine: type
    }), {
        headers: { 'Content-Type': 'application/json' },
    });

    return {
        'stdout': textSummary(data, { indent: ' ', enableColors: true }),
    };
}
