import { expect, type Page } from '@playwright/test';

export const e2eUser = {
    email: 'test@example.com',
    password: 'password',
    name: 'Tatsuki',
};

export async function login(page: Page): Promise<void> {
    await page.goto('/login');
    await page.locator('#email').fill(e2eUser.email);
    await page.locator('#password').fill(e2eUser.password);
    await page.getByRole('button', { name: 'ログイン' }).click();
    await expect(page).toHaveURL(/\/diaries$/);
    await expect(page.getByRole('heading', { name: /こんにちは/ })).toBeVisible();
}
