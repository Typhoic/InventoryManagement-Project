// Sample JS to POST a weekly report (manager inputs quantities in kg where applicable)
// NOTE: adjust the endpoint URL if your backend runs on a different host/port.

async function postWeeklyReport(payload) {
  const url = '/backend/public/api/reports/weekly'; // if using Docker, app serves at :8000 and path may be http://localhost:8000/api/...
  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (!res.ok) {
      const err = await res.json();
      console.error('API error', err);
      return null;
    }
    const data = await res.json();
    console.log('Weekly report response', data);
    return data;
  } catch (e) {
    console.error('Network or fetch error', e);
    return null;
  }
}

// Example: load the mock payload and send it (for local testing)
async function sendMockReport() {
  const resp = await fetch('/frontend/mock/weekly-report-payload.json');
  const payload = await resp.json();
  // The backend normalizes kg->g automatically; frontend sends kg for user convenience.
  const result = await postWeeklyReport(payload);
  if (result) {
    // Display shortages or successful report UI updates here
    console.log('Shortages', result.shortages);
  }
}

// Chart.js sample fetch (assumes an endpoint that returns daily sales totals)
async function renderSalesChart(ctx) {
  // adjust endpoint if necessary
  const res = await fetch('/backend/public/api/sales/chart/data');
  if (!res.ok) return;
  const json = await res.json();
  // expected shape: { labels: [...], data: [...] }
  const { labels, data } = json;
  // eslint-disable-next-line no-undef
  new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Sales',
        data,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)'
      }]
    }
  });
}

// Expose helpers
window.sendMockReport = sendMockReport;
window.renderSalesChart = renderSalesChart;
