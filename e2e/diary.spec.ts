import { expect, test } from '@playwright/test';
import { login } from './helpers/auth';

test.describe('Diary flows', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('user can create a diary from the create page', async ({ page }) => {
        const body = `Playwright E2E ${Date.now()}`;

        await page.getByRole('link', { name: '新しく書く' }).first().click();
        await expect(page).toHaveURL(/\/diaries\/create$/);

        await page.locator('#diary_date').fill('2099-12-31');
        await page.locator('#body').fill(body);
        await page.getByRole('button', { name: '保存する' }).click();

        await expect(page).toHaveURL(/\/diaries$/);
        await expect(page.getByText(body)).toBeVisible();
    });

    test('user can open diary detail from recent list', async ({ page }) => {
        await page.getByText('散歩してリフレッシュできた').click();
        await expect(page).toHaveURL(/\/diaries\/\d+$/);
        await expect(page.getByRole('heading', { name: '日記詳細' })).toBeVisible();
    });

    test('pagination keeps recent diaries section in view', async ({ page }) => {
        await page.getByRole('link', { name: '2', exact: true }).click();
        await expect(page).toHaveURL(/page=2#recent-diaries$/);

        const recentSection = page.locator('#recent-diaries');
        await expect(recentSection).toBeVisible();
        await expect(recentSection.getByRole('heading', { name: '最近の日記' })).toBeVisible();
    });
});
