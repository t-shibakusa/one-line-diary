import { expect, test } from '@playwright/test';
import { login } from './helpers/auth';

test.describe('Settings', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('user can open settings and password pages', async ({ page }) => {
        await page.getByRole('link', { name: '設定' }).click();
        await expect(page).toHaveURL(/\/settings$/);
        await expect(page.getByRole('heading', { name: '設定' })).toBeVisible();
        await expect(page.getByText('プロフィール画像')).toBeVisible();

        await page.getByRole('link', { name: 'パスワード変更' }).click();
        await expect(page).toHaveURL(/\/settings\/password$/);
        await expect(page.getByRole('heading', { name: 'パスワード変更' })).toBeVisible();
    });
});
