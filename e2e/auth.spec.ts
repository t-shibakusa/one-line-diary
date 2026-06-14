import { expect, test } from '@playwright/test';

test.describe('Authentication', () => {
    test('guest is redirected to login from root', async ({ page }) => {
        await page.goto('/');
        await expect(page).toHaveURL(/\/login$/);
        await expect(page.getByRole('heading', { name: 'ログイン' })).toBeVisible();
    });

    test('user can login and see home dashboard', async ({ page }) => {
        await page.goto('/login');
        await page.locator('#email').fill('test@example.com');
        await page.locator('#password').fill('password');
        await page.getByRole('button', { name: 'ログイン' }).click();

        await expect(page).toHaveURL(/\/diaries$/);
        await expect(page.getByText('今日の一行を書く')).toBeVisible();
        await expect(page.getByText('最近の日記')).toBeVisible();
        await expect(page.getByText('今日の植物')).toBeVisible();
    });
});
