const { chromium } = require('playwright');

const base = 'https://skripsi-rista.masgenterr.tech';

async function login(page, user, pass) {
  await page.goto(`${base}/login`);
  await page.fill('input[name="username"]', user);
  await page.fill('input[name="password"]', pass);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('button[type="submit"]')
  ]);
}

async function loginSuccess(browser) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  await login(page, 'petugas1', 'password123');
  if (!page.url().includes('/dashboard')) throw new Error('Login success failed');
  console.log('Login success scenario passed');
  await context.close();
}

async function loginFailure(browser) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  await login(page, 'wronguser', 'wrongpass');
  if (page.url().includes('/dashboard')) throw new Error('Login failure unexpected redirect');
  const errorVisible = await page.locator('text=credential').isVisible();
  if (!errorVisible && page.url() !== `${base}/login`) throw new Error('Login failure message missing');
  console.log('Login failure scenario passed');
  await context.close();
}

async function createPatientSuccess(browser) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  await login(page, 'petugas1', 'password123');
  await page.goto(`${base}/pasien/create`);
  const today = new Date().toISOString().slice(0, 10);
  await page.fill('input[name="nama"]', 'Tes Otomatis');
  await page.fill('textarea[name="alamat"]', 'Jl. Otomatis 123');
  await page.fill('input[name="nomor_telepon"]', '628111111111');
  await page.fill('input[name="jadwal_pengobatan"]', today);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('text=Simpan Pasien')
  ]);
  const success = await page.locator('text=Pasien berhasil').isVisible();
  if (!success) throw new Error('Create patient success failed');
  console.log('Input data pasien sukses scenario passed');
  await context.close();
}

async function createPatientFailure(browser) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  await login(page, 'petugas1', 'password123');
  await page.goto(`${base}/pasien/create`);
  const today = new Date().toISOString().slice(0, 10);
  await page.fill('input[name="nama"]', '');
  await page.fill('textarea[name="alamat"]', 'Jl. Salah 456');
  await page.fill('input[name="nomor_telepon"]', '123');
  await page.fill('input[name="jadwal_pengobatan"]', today);
  await page.click("text=Simpan Pasien");
  await page.waitForSelector("text=Nomor WhatsApp", { timeout: 5000 });
  if (!page.url().includes('/pasien/create')) throw new Error('Create patient failure should stay on form');
  const error = await page.locator('text=Nomor WhatsApp').isVisible();
  if (!error) throw new Error('Create patient failure message missing');
  console.log('Input data pasien gagal scenario passed');
  await context.close();
}

async function sendReminderSuccess(browser) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  await login(page, 'petugas1', 'password123');
  await page.goto(`${base}/pengingat`);
  const firstButton = page.locator('text=Kirim Pengingat').first();
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    firstButton.click()
  ]);
  const success = await page.locator('text=Pengingat berhasil').isVisible();
  if (!success) throw new Error('Send reminder success failed');
  console.log('Kirim pengingat sukses scenario passed');
  await context.close();
}

async function sendReminderFailure(browser) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  await login(page, 'petugas1', 'password123');
  await page.goto(`${base}/pengingat/999999/kirim`);
  const notFound = await page.locator('text=Not Found').isVisible();
  if (!notFound) throw new Error('Send reminder failure not detected');
  console.log('Kirim pengingat gagal scenario passed');
  await context.close();
}

(async () => {
  const browser = await chromium.launch();
  try {
    await loginSuccess(browser);
    await loginFailure(browser);
    await createPatientSuccess(browser);
    await createPatientFailure(browser);
    await sendReminderSuccess(browser);
    await sendReminderFailure(browser);
    await browser.close();
  } catch (err) {
    console.error(err);
    await browser.close();
    process.exit(1);
  }
})();
